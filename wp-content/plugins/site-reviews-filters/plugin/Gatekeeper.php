<?php
/**
 * @version 5.0
 */

namespace GeminiLabs\SiteReviews\Addon\Filters;

class Gatekeeper
{
    public string $pluginId;
    public string $pluginName;

    protected array $dependencies;
    protected array $errors;

    public function __construct(
        string $file,
        string $minVersion,
        string $unsupportedVersion,
        array $dependencies = []
    ) {
        require_once ABSPATH.'wp-admin/includes/plugin.php';
        $plugin = get_file_data($file, [
            'id' => 'Text Domain',
            'name' => 'Plugin Name',
        ]);
        $this->dependencies = $this->dependencies($minVersion, $unsupportedVersion, $dependencies);
        $this->errors = [];
        $this->pluginId = $plugin['id'];
        $this->pluginName = $plugin['name'];
        if (!$this->hasPermission()) {
            return;
        }
        if (!$this->hasDependencies()) {
            return;
        }
        add_action('admin_notices', [$this, 'renderNotice']);
        add_action('current_screen', [$this, 'activatePlugin']);
    }

    /**
     * PHP executes synchronously so even if multiple addon gateways add this hook
     * only the first one will actually run.
     *
     * @action current_screen
     */
    public function activatePlugin(): void
    {
        $action = filter_input(INPUT_GET, 'action');
        $plugin = filter_input(INPUT_GET, 'plugin');
        $trigger = filter_input(INPUT_GET, 'trigger');
        if ('activate' !== $action || 'notice' !== $trigger || empty($plugin)) {
            return;
        }
        check_admin_referer('activate-plugin_'.$plugin);
        $result = activate_plugin($plugin, '', is_network_admin(), true);
        if (is_wp_error($result)) {
            wp_die($result->get_error_message());
        }
        wp_safe_redirect(wp_get_referer());
        exit;
    }

    public function authorize(): bool
    {
        return !$this->hasErrors();
    }

    /**
     * @action admin_notices
     */
    public function renderNotice(): void
    {
        if ($notice = $this->getNoticeDependency()) {
            wp_admin_notice($notice, [
                'additional_classes' => ['glsr-notice'],
                'dismissible' => true,
                'paragraph_wrap' => false,
                'type' => 'warning',
            ]);
        }
        if ($notice = $this->getNoticeUnsupported()) {
            wp_admin_notice($notice, [
                'additional_classes' => ['glsr-notice'],
                'dismissible' => true,
                'paragraph_wrap' => false,
                'type' => 'error',
            ]);
        }
        global $glsr_gatekeeper_addon;
        $glsr_gatekeeper_addon = [];
    }

    protected function buildActionForInactive(string $plugin): string
    {
        if (!current_user_can('activate_plugins')) {
            return '';
        }
        $data = $this->getPluginData($plugin);
        $url = self_admin_url(sprintf('plugins.php?action=activate&plugin=%s&plugin_status=%s&paged=%s&s=%s&trigger=notice',
            $data['plugin'],
            filter_input(INPUT_GET, 'plugin_status'),
            filter_input(INPUT_GET, 'paged'),
            filter_input(INPUT_GET, 's')
        ));
        $url = wp_nonce_url($url, 'activate-plugin_'.$data['plugin']);
        return $this->buildButton($url, __('Activate'), $data['name']);
    }

    protected function buildActionForNotFound(string $plugin): string
    {
        if (!current_user_can('install_plugins')) {
            return '';
        }
        $data = $this->getPluginData($plugin);
        $url = self_admin_url(sprintf('update.php?action=install-plugin&plugin=%s&trigger=notice', $data['slug']));
        $url = wp_nonce_url($url, 'install-plugin_'.$data['slug']);
        return $this->buildButton($url, __('Install'), $data['name']);
    }

    protected function buildActionForWrongVersion(string $plugin): string
    {
        if (!current_user_can('update_plugins')) {
            return '';
        }
        $data = $this->getPluginData($plugin);
        $url = self_admin_url(sprintf('update.php?action=upgrade-plugin&plugin=%s&trigger=notice', $data['plugin']));
        $url = wp_nonce_url($url, 'upgrade-plugin_'.$data['plugin']);
        return $this->buildButton($url, __('Update'), $data['name']);
    }

    protected function buildButton(string $href, string $action, string $pluginName): string
    {
        return sprintf('<a href="%s" class="button button-small">%s %s</a>', $href, $action, $pluginName);
    }

    protected function buildLink(string $plugin): string
    {
        $data = $this->getPluginData($plugin);
        return sprintf('<span class="plugin-%s"><a href="%s">%s</a></span>',
            $data['slug'],
            $data['pluginuri'],
            $data['name']
        );
    }

    protected function buildPluginActions(array $errors): string
    {
        $actions = [];
        foreach ($errors as $plugin => $error) {
            $value = ucwords(str_replace('_', ' ', $error));
            $value = str_replace(' ', '', $value);
            $method = "buildActionFor{$value}";
            if (method_exists($this, $method)) {
                $actions[] = call_user_func([$this, $method], $plugin);
            }
        }
        return implode(' ', $actions);
    }

    protected function buildPluginLinks(array $errors): string
    {
        $plugins = array_keys($errors);
        $plugins = array_map(fn ($plugin) => $this->buildLink($plugin), $plugins);
        return implode(', ', $plugins);
    }

    protected function catchError(string $plugin, string $errorType, bool $isValidResult): bool
    {
        if (!$isValidResult) {
            $this->errors[$plugin] = $errorType;
        }
        return $isValidResult;
    }

    protected function dependencies(string $minVersion, string $maxVersion, array $dependencies)
    {
        $results = [
            'site-reviews/site-reviews.php' => [
                'Name' => 'Site Reviews',
                'Version' => $minVersion,
                'UnsupportedVersion' => $maxVersion,
                'PluginURI' => 'https://wordpress.org/plugins/site-reviews',
            ],
        ];
        foreach ($dependencies as $plugin => $data) {
            $results[$plugin] = wp_parse_args($data, [
                'Name' => '',
                'Version' => '',
                'UnsupportedVersion' => '',
                'PluginURI' => '',
            ]);
        }
        return $results;
    }

    protected function getErrors(array $errors): array
    {
        global $glsr_gatekeeper_addon;
        if (empty($glsr_gatekeeper_addon)) {
            return [];
        }
        return array_filter($glsr_gatekeeper_addon,
            fn ($addon) => !empty(array_filter($addon['errors'], 
                fn ($error) => in_array($error, $errors)
            ))
        );
    }

    protected function getMustUsePlugins(): array
    {
        $plugins = get_mu_plugins();
        if (in_array('Bedrock Autoloader', array_column($plugins, 'Name'))) {
            $autoloadedPlugins = get_site_option('bedrock_autoloader');
            if (!empty($autoloadedPlugins['plugins'])) {
                return array_merge($plugins, $autoloadedPlugins['plugins']);
            }
        }
        return $plugins;
    }

    protected function getNotice(array $noop, array $errors): string
    {
        $errors = $this->getErrors($errors);
        if (empty($errors)) {
            return '';
        }
        $names = array_values(wp_list_pluck($errors, 'name'));
        $names = array_map(fn ($name) => "&rArr; {$name}", $names);
        $names = implode('<br>', $names);
        $values = wp_list_pluck($errors, 'errors');
        $values = array_reduce($values, fn ($carry, $error) => array_merge($carry, $error), []);
        $notice = translate_nooped_plural($noop, count($values), 'site-reviews-filters');
        $notice = "<strong>{$notice} {$this->buildPluginLinks($values)}</strong>";
        $notice .= PHP_EOL.PHP_EOL.$names;
        $notice .= PHP_EOL.PHP_EOL.$this->buildPluginActions($values);
        return wpautop($notice);
    }

    protected function getNoticeDependency(): string
    {
        $noop = _nx_noop('These plugins require the latest version of', 'These plugins require the latest version of:', 'admin-text', 'site-reviews-filters');
        return $this->getNotice($noop, [
            'inactive',
            'not_found',
            'wrong_version',
        ]);
    }

    protected function getNoticeUnsupported(): string
    {
        $noop = _nx_noop('These plugins need an update to work with', 'These plugins need an update to work with:', 'admin-text', 'site-reviews-filters');
        return $this->getNotice($noop, [
            'unsupported_version',
        ]);
    }

    protected function getPluginData(string $plugin): array
    {
        $plugins = $this->isPluginInstalled($plugin)
            ? $this->getPlugins()
            : $this->dependencies;
        $data = $this->values($plugins, $plugin);
        if (empty($data)) {
            wp_die("Plugin information not found for: {$plugin}");
        }
        $data['plugin'] = $plugin;
        $data['slug'] = substr($plugin, 0, strrpos($plugin, '/'));
        return array_change_key_case($data);
    }

    protected function getPlugins(): array
    {
        return array_merge(get_plugins(), $this->getMustUsePlugins());
    }

    protected function hasDependencies(): bool
    {
        foreach ($this->dependencies as $plugin => $data) {
            if (!$this->isPluginInstalled($plugin)) {
                continue;
            }
            if (!$this->isPluginVersionSupported($plugin)) {
                continue;
            }
            if (!$this->isPluginVersionValid($plugin)) {
                continue;
            }
            $this->isPluginActive($plugin);
        }
        if ($this->hasErrors()) {
            global $glsr_gatekeeper_addon;
            if (!is_array($glsr_gatekeeper_addon)) {
                $glsr_gatekeeper_addon = [];
            }
            $glsr_gatekeeper_addon[$this->pluginId] = [
                'id' => $this->pluginId,
                'name' => $this->pluginName,
                'errors' => $this->errors,
            ];
            return true;
        }
        return false;
    }

    protected function hasErrors(): bool
    {
        return !empty($this->errors);
    }

    protected function hasPermission(): bool
    {
        return (is_admin() || is_network_admin()) && !wp_doing_ajax();
    }

    protected function isPluginActive(string $plugin): bool
    {
        $isActive = is_plugin_active($plugin) || array_key_exists($plugin, $this->getMustUsePlugins());
        return $this->catchError($plugin, 'inactive', $isActive);
    }

    protected function isPluginInstalled(string $plugin): bool
    {
        $isInstalled = array_key_exists($plugin, $this->getPlugins());
        return $this->catchError($plugin, 'not_found', $isInstalled);
    }

    protected function isPluginVersionSupported(string $plugin): bool
    {
        $unsupportedVersion = $this->value($this->dependencies, $plugin, 'UnsupportedVersion');
        $installedVersion = $this->value($this->getPlugins(), $plugin, 'Version');
        $isVersionValid = empty($unsupportedVersion) || version_compare($installedVersion, $unsupportedVersion, '<');
        return $this->catchError($plugin, 'unsupported_version', $isVersionValid);
    }

    protected function isPluginVersionValid(string $plugin): bool
    {
        $requiredVersion = $this->value($this->dependencies, $plugin, 'Version');
        $installedVersion = $this->value($this->getPlugins(), $plugin, 'Version');
        $isVersionValid = version_compare($installedVersion, $requiredVersion, '>=');
        return $this->catchError($plugin, 'wrong_version', $isVersionValid);
    }

    protected function value(array $data, string $slug, string $key = ''): string
    {
        return $this->values($data, $slug)[$key] ?? '';
    }

    protected function values(array $data, string $slug): array
    {
        return $data[$slug] ?? [];
    }
}
