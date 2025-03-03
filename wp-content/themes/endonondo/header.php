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
				right: -100%;
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
			.top-banner a{
				color: #fff;
				padding-right: 65px;
				position: relative;
			}

			.top-banner:hover {
				background: #333;
			}
			.top-banner dotlottie-player{
				    display: inline-block;
					width: 89px;
					height: 37px;
					position: absolute;
					top: -5px;
					right: -11px;
			}
		</style>
<!-- 		<div class="top-banner" onclick="window.location.href='/enfit';"> -->
		<div class="top-banner">
			<a href="https://apps.apple.com/us/app/home-workout-fitness-enfit/id6738675309" target="_blank">
				<span>Discover our app:</span>
				<script src="https://unpkg.com/@dotlottie/player-component@2.7.12/dist/dotlottie-player.mjs" type="module"></script>
				<dotlottie-player src="https://lottie.host/a6a0e8a1-c508-46f3-85eb-6d4c5d0afee1/bFJHYaM6dR.lottie" background="transparent" speed="1" loop autoplay></dotlottie-player>
			</a>
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