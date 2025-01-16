
<script id="tmpl-glsri-media-button" type="text/html">
    <button class="button">{{{ data.text }}}</button>
</script>

<script id="tmpl-glsri-media-item" type="text/html">
    <input class="glsri-media-input" type="hidden" name="{{{ data.controller.fieldName }}}" value="{{{ data.id }}}">
    <div class="attachment-preview {{{ data.orientation }}}">
        <div class="thumbnail">
            <div class="centered">
                <# if ( 'image' === data.type && data.sizes ) { #>
                    <# if ( data.sizes[data.controller.imageSize] ) { #>
                        <img src="{{{ data.sizes[data.controller.imageSize].url }}}">
                    <# } else { #>
                        <img src="{{{ data.sizes.full.url }}}">
                    <# } #>
                <# } else { #>
                    <# if ( data.image && data.image.src && data.image.src !== data.icon ) { #>
                        <img src="{{ data.image.src }}" />
                    <# } else { #>
                        <img src="{{ data.icon }}" />
                    <# } #>
                <# } #>
            </div>
        </div>
    </div>
    <span class="glsri-switch-media">
        <svg viewBox="0 0 150 150" preserveAspectRatio="none">
            <radialGradient id="glsri-svg-overlay-radial-gradient" cx=".5" cy="1.25" r="1.15">
                <stop offset="50%" stop-color="#000000"></stop>
                <stop offset="56%" stop-color="#0a0a0a"></stop>
                <stop offset="63%" stop-color="#262626"></stop>
                <stop offset="69%" stop-color="#4f4f4f"></stop>
                <stop offset="75%" stop-color="#808080"></stop>
                <stop offset="81%" stop-color="#b1b1b1"></stop>
                <stop offset="88%" stop-color="#dadada"></stop>
                <stop offset="94%" stop-color="#f6f6f6"></stop>
                <stop offset="100%" stop-color="#ffffff"></stop>
            </radialGradient>
            <mask id="glsri-svg-overlay-mask">
                <rect x="0" y="0" width="150" height="150" fill="url(#glsri-svg-overlay-radial-gradient)"></rect>
            </mask>
            <rect x="0" width="150" height="150" fill="currentColor" mask="url(#glsri-svg-overlay-mask)"></rect>
        </svg>
    </span>
    <a class="check glsri-edit-media" title="{{{ GLSR.addons['<?= $addon; ?>'].text.edit }}}" href="{{{ data.editLink }}}" target="_blank">
        <span class="dashicons dashicons-edit"></span>
        <span class="screen-reader-text">{{{ GLSR.addons['<?= $addon; ?>'].text.edit }}}</span>
    </a>
    <button class="check glsri-remove-media" title="{{{ GLSR.addons['<?= $addon; ?>'].text.remove }}}">
        <span class="dashicons dashicons-no"></span>
        <span class="screen-reader-text">{{{ GLSR.addons['<?= $addon; ?>'].text.remove }}}</span>
    </button>
</script>

<script id="tmpl-glsri-media-status" type="text/html">
    <# if ( data.maxFiles > 0 ) { #>
        {{{ data.length }}}/{{{ data.maxFiles }}}
        <# if ( 1 < data.maxFiles ) { #>{{{ GLSR.addons['<?= $addon; ?>'].text.multiple }}}<# } else {#>{{{ GLSR.addons['<?= $addon; ?>'].text.single }}}<# } #>
    <# } #>
</script>
