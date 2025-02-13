<!DOCTYPE HTML>
<html lang="en-US">

<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="p:domain_verify" content="28c2a5883d9783b56cfa793df37dcd1a" />
	<title><?php
	global $page, $paged;
	wp_title('|', true, 'right');
	$site_description = get_bloginfo('description', 'display');
	if ($site_description && (is_home() || is_front_page()))
		echo " | $site_description";
	if ($paged >= 2 || $page >= 2)
		echo ' | ' . sprintf(__('Page %s', 'twentyeleven'), max($paged, $page));
	?></title>
	<?php
	if (is_singular() && get_option('thread_comments'))
		wp_enqueue_script('comment-reply');
	wp_head();
	?>

	<link rel="preload" href="<?= get_template_directory_uri() . '/assets/images/enfit/first-mb.png' ?>" as="image">

	<!-- Canon Link -->
	<link rel="canonical" href="<?= the_permalink() ?>" />

	<!-- Favicon -->
	<link rel="shortcut icon" type="image/x-icon" href="<?php echo get_field('favicon', 'option'); ?>" />
	<link rel="icon" href="<?= (get_field('favicon_16', 'option')) ? get_field('favicon_16', 'option') : "#"; ?>"
		sizes="16x16" />
	<link rel="icon" href="<?= (get_field('favicon_32', 'option')) ? get_field('favicon_32', 'option') : "#"; ?>"
		sizes="32x32" />
	<link rel="icon" href="<?= (get_field('favicon_96', 'option')) ? get_field('favicon_96', 'option') : "#"; ?>"
		sizes="96x96" />
	<link rel="apple-touch-icon"
		href="<?= (get_field('favicon_180', 'option')) ? get_field('favicon_180', 'option') : "#"; ?>"
		sizes="180x180" />
	<link rel="icon" href="<?= (get_field('favicon_256', 'option')) ? get_field('favicon_256', 'option') : "#"; ?>"
		sizes="256x256" />
	<meta name="msapplication-TileImage" content="<?php echo get_field('favicon_512', 'option'); ?>" />

	<script src="https://www.youtube.com/iframe_api"></script>
	<script type="text/javascript"
		src="<?php echo get_template_directory_uri(); ?>/assets/js/jquery-3.5.0.min.js"></script>
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
	<meta name="msapplication-TileImage" content="<?php echo get_field('favicon_size_270', 'option'); ?>" />
	<?php
	$pty = get_post_type();
	$args = array(
		'post_type' => 'gp_elements',
		'posts_per_page' => 5,
		'meta_query' => array(
			array(
				'key' => 'emposition',
				'value' => 'in_head_tag'
			),
			array(
				'key' => 'emdisplay',
				'value' => sprintf('"%s"', $pty),
				'compare' => 'LIKE'
			)
		)
	);
	$the_query = new WP_Query($args);
	while ($the_query->have_posts()):
		$the_query->the_post();
		echo get_field('emcode', $post->ID, false, false);
	endwhile;
	wp_reset_query();
	$args = array(
		'post_type' => 'gp_elements',
		'posts_per_page' => 5,
		'meta_query' => array(
			array(
				'key' => 'emposition',
				'value' => 'in_head_tag'
			),
			array(
				'key' => 'display_with_id_$_pid',
				'value' => get_the_ID(),
				'compare' => '='
			)
		)
	);
	$the_query = new WP_Query($args);
	while ($the_query->have_posts()):
		$the_query->the_post();
		echo get_field('emcode', $post->ID, false, false);
	endwhile;
	wp_reset_query();
	if (is_front_page()) {
		$args = array(
			'post_type' => 'gp_elements',
			'posts_per_page' => 5,
			'meta_query' => array(
				array(
					'key' => 'emposition',
					'value' => 'in_head_tag'
				),
				array(
					'key' => 'emdisplay',
					'value' => 'home_page',
					'compare' => 'LIKE'
				)
			)
		);
		$the_query = new WP_Query($args);
		while ($the_query->have_posts()):
			$the_query->the_post();
			echo get_field('emcode', $post->ID, false, false);
		endwhile;
		wp_reset_query();
	}
	?>
</head>

<body <?php body_class(); ?>>
	<?php global $template; ?>
	<div id="wapper" class="<?php if (is_front_page()) {
		echo 'home-main color-white';
	} elseif (basename($template) == 'single-best_exercise.php' || basename($template) == 'single-exercise.php') {
		echo 'home-main best-exercise';
	} else {
		echo '';
	} ?>">
		<style>
			.btn-header {
				gap: 10px;
			}

			.user-menu-overlay {
				position: fixed;
				top: 0;
				left: 0;
				width: 100%;
				height: 100%;
				background-color: rgba(0, 0, 0, 0.5);
				display: none;
				z-index: 999996;
			}

			.user-menu {
				position: fixed;
				top: 0;
				right: -500px;
				width: 500px;
				height: 100%;
				background: white;
				box-shadow: -4px 0px 10px rgba(0, 0, 0, 0.2);
				transition: right 0.3s ease-in-out;
				z-index: 9999999;
				display: flex;
				flex-direction: column;
				justify-content: space-between;
				padding: 20px;
			}

			.user-menu.active {
				right: 0;
			}

			.user-menu-header {
				display: flex;
				justify-content: center;
				align-items: center;
				border-bottom: 1px solid #ddd;
				padding-bottom: 10px;
				position: relative;
			}

			.user-menu-header h3 {
				font-size: 18px;
				font-weight: bold;
				margin: 0;
				text-align: center;
				flex-grow: 1;
			}

			.close-user-menu {
				background: none;
				border: none;
				font-size: 12px;
				cursor: pointer;
				position: absolute;
				font-weight: bold;
				right: 10px;
			}

			.user-menu-links {
				list-style: none;
				padding: 20px 0;
				margin: 0;
			}

			.user-menu-links li {
				padding: 5px 0;
			}

			.user-menu-links li:last-child {
				border-bottom: none;
			}

			.user-menu-links li a {
				text-decoration: none;
				color: black;
				display: block;
			}

			.user-menu-footer {
				margin-top: auto;
			}

			.logout-btn {
				display: block;
				width: 100%;
				padding: 12px 0;
				background: black;
				color: white !important;
				text-align: center;
				text-decoration: none;
			}

			#cartIcon {
				cursor: pointer;
				z-index: 999999;
			}

			.xoo-wsc-modal.xoo-wsc-cart-active.xoo-wsc-container {
				right: 0 !important;
			}

			body.xoo-wsc-open {
				overflow: hidden;
			}

			@media (max-width: 768px) {
				.user-menu {
					width: 100%;
				}
			}

			.top-banner {
				background: #363636;
				color: #ffffff;
				display: flex;
				justify-content: center;
				gap: 6px;
				padding: 8px 15px;
				font-size: 16px;
				cursor: pointer;
			}

			.top-banner:hover {
				background: #333;
			}
		</style>
		<div class="top-banner" onclick="window.location.href='/enfit';">
			<span>Discover our app:</span>
			<svg width="48" height="18" viewBox="0 0 48 18" fill="none" xmlns="http://www.w3.org/2000/svg">
				<path d="M11.4091 9.22957C11.4091 9.22957 11.4071 9.22352 11.405 9.21949C11.3826 9.15501 11.3562 9.05224 11.3318 8.92529C11.3318 8.92328 11.3318 8.92126 11.3318 8.91925C11.2179 8.51824 11.0592 8.14746 10.8558 7.80087C10.8538 7.80087 10.8538 7.79684 10.8517 7.79482C10.8334 7.67391 10.8192 7.5651 10.809 7.47845C10.807 7.4583 10.805 7.44016 10.805 7.42404C10.8009 7.39382 10.7989 7.36762 10.7968 7.34545C10.7907 7.2447 10.7623 7.15805 10.7134 7.07342C10.4714 6.64017 10.089 6.38828 9.61911 6.25529C9.57639 6.2432 9.53164 6.23312 9.48892 6.22304C9.46655 6.217 9.44214 6.21297 9.41977 6.20894C9.38926 6.20289 9.35671 6.19685 9.3262 6.19282C9.28755 6.18476 9.24891 6.18073 9.20822 6.17468C9.16958 6.17065 9.12889 6.16662 9.08821 6.16461C8.91735 6.15655 8.77497 6.21498 8.65903 6.33992C8.62038 6.38425 8.58377 6.42657 8.54308 6.4709C8.45969 6.56561 8.37426 6.65629 8.29493 6.74899C8.11593 6.95049 8.12 7.17619 8.25221 7.40188C8.44341 7.72631 8.75463 7.80288 9.09025 7.83512C9.16551 7.8452 9.2367 7.81699 9.30789 7.78676C9.4157 7.74041 9.46045 7.76862 9.46655 7.88147C9.47062 7.94192 9.47062 8.00439 9.47265 8.06686C9.48689 8.29053 9.14924 9.45728 9.04957 9.65677C9.02923 9.71319 8.99261 9.78171 8.95193 9.84014C8.93973 9.85828 8.92549 9.8744 8.91328 9.88851C8.8665 9.79984 8.79938 9.71722 8.71801 9.64267C8.64682 9.57617 8.56342 9.51571 8.46986 9.46534C8.20746 9.3142 7.86981 9.22554 7.49757 9.22554C7.00736 9.22554 6.57004 9.38272 6.29544 9.62856H6.29137C5.30892 8.9132 3.97457 8.90917 2.82939 9.93485C2.80498 9.95702 2.78057 9.97919 2.75616 10.0054H2.71548C2.88024 9.24368 3.2423 8.64721 3.79964 8.21396C4.35697 7.78071 5.06483 7.5651 5.92524 7.5651C6.78565 7.5651 7.44672 7.77265 7.95931 8.19179C8.24204 8.42152 8.46172 8.70766 8.61834 9.05224C8.6753 8.85879 8.74446 8.61295 8.79531 8.40539C8.44341 8.34494 8.00812 8.18373 7.72742 7.70817C7.45282 7.24873 7.4935 6.74092 7.83319 6.35403C7.89015 6.28551 7.95524 6.217 8.01829 6.14647L8.20339 5.94093C8.23187 5.90869 8.26238 5.87846 8.29086 5.85227C8.31934 5.82809 8.34171 5.80995 8.36205 5.79383C8.37426 5.78375 8.4007 5.7636 8.42918 5.74547L8.46375 5.7233C7.7437 5.41096 6.93414 5.25781 6.03711 5.25781C4.13933 5.25781 2.65853 5.82607 1.59674 6.96057C0.530892 8.09507 0 9.54997 0 11.3273C0 13.1046 0.577675 14.63 1.72896 15.7262C2.88227 16.8245 4.40782 17.3726 6.30764 17.3726C8.20746 17.3726 9.88557 16.5827 11.0775 14.9988L9.49503 13.4532C8.72615 14.4849 7.6786 14.9988 6.35239 14.9988C5.3435 14.9988 4.50546 14.761 3.84235 14.2834C3.17925 13.8059 2.79684 13.1187 2.69107 12.222H9.72691C9.76352 12.2159 9.80014 12.218 9.83675 12.222C9.84896 12.222 9.85913 12.222 9.87133 12.222H10.7378C11.0369 12.222 11.3155 12.0527 11.4335 11.7807C11.4335 11.7807 11.4335 11.7767 11.4355 11.7767C11.5311 11.551 11.5983 11.3233 11.6328 11.1076C11.6328 11.0915 11.6349 11.0774 11.6369 11.0613L11.641 11.0311C11.6511 10.9484 11.6593 10.8739 11.6633 10.8054C11.6654 10.7671 11.6674 10.7308 11.6674 10.6945C11.6694 10.6583 11.6694 10.622 11.6694 10.5857C11.6674 10.1142 11.5474 9.60236 11.4091 9.22957ZM7.95117 9.41697C7.94303 9.45325 8.01016 9.44317 7.95117 9.41697V9.41697Z" fill="white" />
				<path d="M34.6896 1.59179C34.9825 1.33789 35.3405 1.21094 35.7636 1.21094C36.1866 1.21094 36.5406 1.34192 36.8253 1.60187C37.1121 1.86383 37.2545 2.1802 37.2545 2.55299C37.2545 2.95601 37.1121 3.2885 36.8253 3.55046C36.5385 3.81243 36.1846 3.94139 35.7636 3.94139C35.312 3.94139 34.9459 3.81041 34.6672 3.55046C34.3885 3.2885 34.2482 2.95601 34.2482 2.55299C34.2482 2.14997 34.3946 1.84368 34.6896 1.58978V1.59179Z" fill="white" />
				<path d="M13.4634 5.52129H16.0406V6.57318C16.9295 5.70668 18.1357 5.27344 19.6572 5.27344C22.8059 5.27344 24.3823 7.04269 24.3823 10.5812V17.1645H21.8051V10.7827C21.8051 9.67844 21.5712 8.87845 21.1054 8.38676C20.6376 7.89508 19.9074 7.64722 18.9127 7.64722C18.2801 7.64722 17.7065 7.82254 17.1939 8.17316C16.6813 8.52379 16.2969 9.0054 16.0406 9.61799V17.1645H13.4634V5.52129Z" fill="white" />
				<path d="M32.9481 5.5232H31.0503V5.14235C31.0503 3.58871 30.6578 2.4522 29.8746 1.72676C29.0915 1.00334 28.1111 0.640625 26.9354 0.640625C26.2113 0.640625 25.6234 0.767576 25.1719 1.02148L26.077 3.23809C26.2723 3.08897 26.5144 3.01441 26.8012 3.01441C27.9158 3.01441 28.4732 3.74589 28.4732 5.20885V5.5232H25.4668V7.89699H28.4732V17.1684H31.0503V7.89699H32.9481V5.5232Z" fill="white" />
				<path d="M37.0382 5.52344H34.4611V17.1687H37.0382V5.52344Z" fill="white" />
				<path d="M38.666 5.52876H40.7V3.13281H43.2772V5.52876H47.0748V7.90255H43.2772V12.4043C43.2772 13.3292 43.3931 13.9821 43.6271 14.3629C43.861 14.7438 44.2495 14.9332 44.7905 14.9332C45.3926 14.9332 45.952 14.6048 46.4626 13.9478L48.0003 15.3806C47.6688 15.9488 47.1826 16.4103 46.5419 16.769C45.9012 17.1277 45.1892 17.307 44.4061 17.307C43.2609 17.307 42.3558 16.9483 41.6927 16.233C41.0296 15.5156 40.698 14.4778 40.698 13.1196V7.90255H38.6639V5.52876H38.666Z" fill="white" />
			</svg>
		</div>
		<header id="header" class="position-relative">
			<div class="container">
				<div class="list-flex flex-middle flex-center">
					<div>
						<div class="toogle-menu">
							<span></span>
						</div>
						<div class="hd-search">
							<a class="position-relative" href="#"></a>
						</div>
					</div>
					<div class="logo"><a href="<?php echo home_url(); ?>"><img
								src="<?php echo get_field('logo', 'option') ?>" alt=""></a>
					</div>
					<!-- <div class="btn-header">
						<a target="_blank" href="<?php echo get_field('subscribe_link', 'option') ?>"
							class="ed-btn btn-popup"><?php echo get_field('subscribe_title', 'option') ?></a>
						<div class="en-logo">
						<div class="en-logo-bg"></div>
							<img src="<?php echo get_template_directory_uri(); ?>/assets/images/en-logo.svg" alt="">
						</div>
					</div> -->
					<div class="btn-header">
						<!-- <a href="<?php echo get_field('subscribe_link', 'option') ?>"
							class="ed-btn btn-popup"><?php echo get_field('subscribe_title', 'option') ?></a> -->
						<div class="user_icon_wrapper">
							<div class="user-menu-overlay" id="userMenuOverlay"></div>
							<div class="user_icon" id="userIcon">
								<?php if (is_user_logged_in()): ?>
									<svg width="18" height="17" viewBox="0 0 18 17" fill="none" xmlns="http://www.w3.org/2000/svg">
										<circle cx="9" cy="4" r="3.25" stroke="white" stroke-width="1.5" />
										<path d="M1 16.1895L2.1049 12.7701C2.63846 11.1189 4.17583 10 5.91112 10H12.0889C13.8242 10 15.3615 11.1189 15.8951 12.7701L17 16.1895" stroke="white" stroke-width="1.5" />
									</svg>


									<div class="user-menu user-menu-slide" id="userMenu">
										<div class="user-menu-header">
											<h3>My Profile</h3>
											<button class="close-user-menu" id="closeUserMenu">✕</button>
										</div>
										<ul class="user-menu-links">
											<li><a href="<?php echo home_url('/my-account'); ?>">Profile</a></li>
											<li><a href="<?php echo home_url('/my-account/orders'); ?>">Orders</a></li>
											<li>
												<a href="<?php echo wp_logout_url(home_url()); ?>" class="logout-btn">Log Out</a>
											</li>
										</ul>
										<div class="user-menu-footer">
										</div>
									</div>
								<?php else: ?>
									<a href="<?php echo home_url('/login'); ?>">
										<svg width="18" height="17" viewBox="0 0 18 17" fill="none" xmlns="http://www.w3.org/2000/svg">
											<circle cx="9" cy="4" r="3.25" stroke="white" stroke-width="1.5" />
											<path d="M1 16.1895L2.1049 12.7701C2.63846 11.1189 4.17583 10 5.91112 10H12.0889C13.8242 10 15.3615 11.1189 15.8951 12.7701L17 16.1895" stroke="white" stroke-width="1.5" />
										</svg>
									</a>
								<?php endif; ?>
							</div>
						</div>
						<svg width="1" height="20" viewBox="0 0 1 20" fill="none" xmlns="http://www.w3.org/2000/svg">
							<line x1="0.5" y1="2.18557e-08" x2="0.499999" y2="20" stroke="white" />
						</svg>
						<div class="cart_icon" id="cartIcon">
							<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M17.25 22C17.9404 22 18.5 21.4404 18.5 20.75C18.5 20.0596 17.9404 19.5 17.25 19.5C16.5596 19.5 16 20.0596 16 20.75C16 21.4404 16.5596 22 17.25 22Z" fill="white" />
								<path d="M9.25 22C9.94036 22 10.5 21.4404 10.5 20.75C10.5 20.0596 9.94036 19.5 9.25 19.5C8.55964 19.5 8 20.0596 8 20.75C8 21.4404 8.55964 22 9.25 22Z" fill="white" />
								<path d="M10 9L16 9" stroke="white" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="square" stroke-linejoin="round" />
								<path d="M13 12L13 6" stroke="white" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="square" stroke-linejoin="round" />
								<path d="M1.5 2H5.5L7.5 17H19L21 7" stroke="white" stroke-width="1.5" />
							</svg>
						</div>
					</div>
					<script>
						document.addEventListener("DOMContentLoaded", function() {
							const userIcon = document.getElementById("userIcon");
							const userMenu = document.getElementById("userMenu");
							const userMenuOverlay = document.getElementById("userMenuOverlay");
							const closeUserMenu = document.getElementById("closeUserMenu");

							if (userIcon && userMenu && userMenuOverlay && closeUserMenu) {
								userIcon.addEventListener("click", function() {
									userMenu.classList.add("active");
									userMenuOverlay.style.display = "block";
								});

								closeUserMenu.addEventListener("click", function() {
									void userMenu.offsetWidth;
									setTimeout(() => {
										userMenuOverlay.style.display = "none";
										userMenu.classList.remove("active");
									}, 100);
								});

								userMenuOverlay.addEventListener("click", function() {
									void userMenu.offsetWidth;
									setTimeout(() => {
										userMenuOverlay.style.display = "none";
										userMenu.classList.remove("active");
									}, 100);
								});
							}
						});
						document.addEventListener("DOMContentLoaded", function() {
							const cartIcon = document.getElementById("cartIcon");
							if (cartIcon) {
								cartIcon.addEventListener("click", function() {
									if (typeof jQuery !== "undefined") {
										jQuery(".xoo-wsc-modal").addClass("xoo-wsc-cart-active");
										jQuery("body").addClass("xoo-wsc-open");
									}
								});
							}
						});
					</script>
				</div>
			</div>
			<nav class="menu-main">
				<div class="hd-search-form">
					<div class="container">
						<form action="<?php echo get_home_url(); ?>/" method="get">
							<input type="text" id="s" name="s" class="form-control" value=""
								placeholder="Type here ...">
						</form>
					</div>
				</div>
				<div class="list-menu">
					<div class="container">
						<nav class="menu-box">
							<?php
							wp_nav_menu(
								array(
									'theme_location' => 'menu_main',
								)
							);
							?>
						</nav>
					</div>
				</div>
			</nav>
			<div class="popup" id="popup-email">
				<div class="popup-click"></div>
				<div class="popup-bg">
					<div class="popup-box list-flex">
						<div class="featured image-fit">
							<img class="on-pc"
								src="<?php echo get_template_directory_uri(); ?>/assets/images/news-popup.png" alt="">
							<img class="on-sp"
								src="<?php echo get_template_directory_uri(); ?>/assets/images/news-popup-mb.jpg"
								alt="">
						</div>
						<div class="info">
							<img class="close"
								src="<?php echo get_template_directory_uri(); ?>/assets/images/sticker-popup.svg"
								alt="">
							<div class="box">
								<div class="feature">
									<img src="<?php echo get_template_directory_uri(); ?>/assets/images/logo-popup.svg"
										alt="">
								</div>
								<div class="klaviyo-form-UPY2r8"></div>
								<div class="social">
									<?php
									$socials = get_field('social', 'option');
									if ($socials) {
										foreach ($socials as $social) {
											?>
											<a target="_blank" href="<?php echo $social['link']; ?>"><img
													src="<?= $social['icon']['url']; ?>"
													alt="<?= $social['icon']['alt']; ?>" /></a>
										<?php }
									} ?>
								</div>
								<p class="note has-small-font-size"><i>* <a
											href="https://www.endomondo.com/privacy-policy">Your privacy</a> is
										important to
										us</i></p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</header>
		<?php
		$pty = get_post_type();
		$args = array(
			'post_type' => 'gp_elements',
			'posts_per_page' => 5,
			'meta_query' => array(
					array(
						'key' => 'emposition',
						'value' => 'after_header'
					),
					array(
						'key' => 'emdisplay',
						'value' => sprintf('"%s"', $pty),
						'compare' => 'LIKE'
					)
				)
		);
		$the_query = new WP_Query($args);
		while ($the_query->have_posts()):
			$the_query->the_post();
			echo get_field('emcode', $post->ID);
		endwhile;
		wp_reset_query();
		$args = array(
			'post_type' => 'gp_elements',
			'posts_per_page' => 5,
			'meta_query' => array(
					array(
						'key' => 'emposition',
						'value' => 'after_header'
					),
					array(
						'key' => 'display_with_id',
						'value' => sprintf('"%s"', get_the_ID()),
						'compare' => 'LIKE'
					)
				)
		);
		$the_query = new WP_Query($args);
		while ($the_query->have_posts()):
			$the_query->the_post();
			echo get_field('emcode', $post->ID, false, false);
		endwhile;
		wp_reset_query();
		if (is_front_page()) {
			$args = array(
				'post_type' => 'gp_elements',
				'posts_per_page' => 5,
				'meta_query' => array(
						array(
							'key' => 'emposition',
							'value' => 'after_header'
						),
						array(
							'key' => 'emdisplay',
							'value' => 'home_page',
							'compare' => 'LIKE'
						)
					)
			);
			$the_query = new WP_Query($args);
			while ($the_query->have_posts()):
				$the_query->the_post();
				echo get_field('emcode', $post->ID, false, false);
			endwhile;
			wp_reset_query();
		}
		?>