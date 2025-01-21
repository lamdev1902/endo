<?php
/* Template Name: Home*/
$pageid = get_the_ID();
get_header();
the_post();
?>
<main id="content">
	<section class="home-top color-white pd-main">
		<div class="container">
			<div class="list-flex">
				<div class="top-big list-flex">
					<?php
					$args = array(
						'posts_per_page' => 1,
						'post_type' => array('post', 'informational_posts', 'round_up', 'single_reviews', 'step_guide', 'exercise', 'tool_post', 'best_exercise'),
					);
					$the_query = new WP_Query($args);
					while ($the_query->have_posts()):
						$the_query->the_post();
						$post_author_id = get_post_field('post_author', $post->ID);
						$post_display_name = get_the_author_meta('nickname', $post_author_id);
						$post_author_url = get_author_posts_url($post_author_id);
						?>
						<div class="info">
							<p class="has-x-large-font-size text-special clamp-2 mr-bottom-20 pri-color-3"><a
									class="pri-color-3" href="<?php the_permalink(); ?>"><?php the_title(); ?></a></p>
							<p class="sec-color-2"><?php echo wp_trim_words(get_the_excerpt($post->ID), 28); ?></p>
						</div>
						<div class="featured image-fit hover-scale">
							<?php $image_featured = wp_get_attachment_url(get_post_thumbnail_id($post->ID)); ?>
							<a href="<?php the_permalink(); ?>">
								<?php if ($image_featured): ?>
									<?php the_post_thumbnail(); ?>
								<?php else: ?>
									<img src="<?php echo get_field('fimg_default', 'option'); ?>" alt="">
								<?php endif; ?>
							</a>
						</div>
						<?php
					endwhile;
					wp_reset_query();
					?>
				</div>
				<div class="news-right">
					<p class="sec-color-3 has-large-font-size mr-bottom-20">News</p>
					<div class="top-list">
						<?php
						$args = array(
							'posts_per_page' => 4,
							'offset' => 1,
							'post_type' => array('post', 'informational_posts', 'round_up', 'single_reviews', 'step_guide', 'exercise', 'tool_post', 'best_exercise'),
						);
						$the_query = new WP_Query($args);
						while ($the_query->have_posts()):
							$the_query->the_post();
							$post_author_id = get_post_field('post_author', $post->ID);
							$post_display_name = get_the_author_meta('nickname', $post_author_id);
							$post_author_url = get_author_posts_url($post_author_id);
							?>
							<div class="top-it mr-bottom-20 position-relative">
								<p class="has-medium-font-size mr-bottom-16 text-special clamp-2 ellipsis pri-color-3"><a
										class="pri-color-3" href="<?php the_permalink(); ?>"><?php the_title(); ?></a></p>
								<p class="author"><a class="sec-color-3" href="<?php echo $post_author_url; ?>">By
										<?php echo $post_display_name; ?></a></p>
								<a href="<?php the_permalink(); ?>" class="news-link author position-absolute">
									<img src="<?php echo get_template_directory_uri(); ?>/assets/images/right.svg" alt="">
								</a>
							</div>
							<?php
						endwhile;
						wp_reset_query();
						?>
					</div>
				</div>
			</div>
		</div>
	</section>
	<?php
	$brand_list = get_field('feature_on', $pageid);
	if ($brand_list) {
		?>
		<section class="home-feature-on">
			<div class="container">
				<h2 class="pri-color-3 text-center">Featured On</h2>
				<ul>
					<?php foreach ($brand_list as $hl) {
						$logo = $hl['logo'];
						?>
						<li><img src="<?php echo $logo['url']; ?>" alt="<?php echo $logo['alt']; ?>"></li>
					<?php } ?>
				</ul>
			</div>
		</section>
	<?php } ?>
	<section class="home-feature pd-main">
		<div class="list-flex feature-bg">
			<div class="feature-social">
				<?php
				$social = get_field('social', 'option');
				if ($social) {
					foreach ($social as $social) {
						?>
						<a target="_blank" href="<?php echo $social['link']; ?>"><img alt="<?= $social['icon']['alt']; ?>"
								src="<?= $social['icon']['url']; ?>" /></a>
					<?php }
				} ?>
			</div>
			<div class="feature-collections bg-white color-black">
				<h2 class="pri-color-2 mr-bottom-40">Feature Collections</h2>
				<div class="feature-slider swiper">
					<div class="swiper-wrapper">
						<?php
						$args = array(
							'posts_per_page' => 6,
							'offset' => 4,
							'post_type' => array('post', 'informational_posts', 'round_up', 'single_reviews', 'step_guide', 'exercise', 'tool_post', 'best_exercise'),
						);
						$the_query = new WP_Query($args);
						while ($the_query->have_posts()):
							$the_query->the_post();
							$post_author_id = get_post_field('post_author', $post->ID);
							$post_display_name = get_the_author_meta('nickname', $post_author_id);
							$post_author_url = get_author_posts_url($post_author_id);
							?>
							<div class="it swiper-slide">
								<div class="feature-box">
									<div class="image image-fit hover-scale">
										<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail(); ?></a>
									</div>
									<div class="info">
										<?php $category = get_the_category($post->ID); ?>
										<?php if (!empty($category) && count($category) > 0): ?>
											<div class="tag">
												<?php
												foreach ($category as $cat) { ?>
													<span><a
															href="<?php echo get_term_link($cat->term_id); ?>"><?php echo $cat->name; ?></a></span>
												<?php } ?>
											</div>
										<?php endif; ?>
										<p class="has-medium-font-size text-special clamp-2 ellipsis"><a class="pri-color-2"
												href="<?php the_permalink(); ?>"><?php echo the_title(); ?></a></p>
										<p class="has-small-font-size"><a class="sec-color-3"
												href="<?php echo $post_author_url; ?>">By
												<?php echo $post_display_name; ?></a></p>
									</div>
								</div>
							</div>
							<?php
						endwhile;
						wp_reset_query();
						?>
					</div>
					<div class="swiper-pagination"></div>
				</div>
			</div>
		</div>
	</section>

	<section class="home-lastest bg-section pd-main">
		<div class="container">
			<h2 class="pri-color-3">Latest news</h2>
			<div class="lastest-list">
				<?php
				$args = array(
					'posts_per_page' => 5,
					'offset' => 10,
					'post_type' => array('post', 'informational_posts', 'round_up', 'single_reviews', 'step_guide', 'exercise', 'best_exercise'),
				);
				$the_query = new WP_Query($args);
				while ($the_query->have_posts()):
					$the_query->the_post();
					$post_author_id = get_post_field('post_author', $post->ID);
					$post_display_name = get_the_author_meta('nickname', $post_author_id);
					$post_author_url = get_author_posts_url($post_author_id);
					?>
					<div class="lastest-it">
						<div class="lastest-box list-flex">
							<div class="featured image-fit hover-scale">
								<?php $image_featured = wp_get_attachment_url(get_post_thumbnail_id($post->ID)); ?>
								<a href="<?php the_permalink(); ?>">
									<?php if ($image_featured): ?>
										<img src="<?php echo $image_featured; ?>" alt="">
									<?php else: ?>
										<img src="<?php echo get_field('fimg_default', 'option'); ?>" alt="">
									<?php endif; ?>
								</a>
							</div>
							<div class="info">
								<?php $category = get_the_category($post->ID); ?>
								<?php if (!empty($category) && count($category) > 0): ?>
									<div class="tag mr-bottom-16">
										<?php
										foreach ($category as $cat) { ?>
											<span><a
													href="<?php echo get_term_link($cat->term_id); ?>"><?php echo $cat->name; ?></a></span>
										<?php } ?>
									</div>
								<?php endif; ?>
								<p class="has-medium-font-size text-special clamp-2 ellipsis pri-color-3"><a
										class="pri-color-3" href="<?php the_permalink(); ?>"><?php the_title(); ?></a></p>
								<p class="has-small-font-size author"><a class="sec-color-3"
										href="<?php echo $post_author_url; ?>">By <?php echo $post_display_name; ?></a></p>
							</div>
						</div>
					</div>
					<?php
				endwhile;
				wp_reset_query();
				?>
				<div class="clear"></div>
			</div>
		</div>
	</section>

	<section class="home-choise bg-white color-black pd-main">
		<div class="container">
			<h2 class="">Recommended Posts</h2>
			<div class="news-list grid grid-feature">
				<?php
				$args = array(
					'posts_per_page' => 12,
					'offset' => 16,
					'post_type' => array('post', 'informational_posts', 'round_up', 'single_reviews', 'step_guide', 'exercise', 'tool_post', 'best_exercise'),
				);
				$the_query = new WP_Query($args);
				while ($the_query->have_posts()):
					$the_query->the_post();
					$post_author_id = get_post_field('post_author', $post->ID);
					$post_display_name = get_the_author_meta('nickname', $post_author_id);
					$post_author_url = get_author_posts_url($post_author_id);
					?>
					<div class="news-it">
						<div class="news-box">
							<div class="featured image-fit hover-scale">
								<?php $image_featured = wp_get_attachment_url(get_post_thumbnail_id($post->ID)); ?>
								<a href="<?php the_permalink(); ?>">
									<?php if ($image_featured): ?>
										<img src="<?php echo $image_featured; ?>" alt="">
									<?php else: ?>
										<img src="<?php echo get_field('fimg_default', 'option'); ?>" alt="">
									<?php endif; ?>
								</a>
							</div>
							<div class="info">
								<?php $category = get_the_category($post->ID); ?>
								<?php if (!empty($category) && count($category) > 0): ?>
									<div class="tag mr-bottom-16">
										<?php
										foreach ($category as $cat) { ?>
											<span><a
													href="<?php echo get_term_link($cat->term_id); ?>"><?php echo $cat->name; ?></a></span>
										<?php } ?>
									</div>
								<?php endif; ?>
								<p class="has-medium-font-size text-special clamp-2"><a class="pri-color-2"
										href="<?php the_permalink(); ?>"><?php echo the_title(); ?></a></p>
								<p class="has-small-font-size"><a class="sec-color-3"
										href="<?php echo $post_author_url; ?>">By <?php echo $post_display_name; ?></a></p>
							</div>
						</div>
					</div>
					<?php
				endwhile;
				wp_reset_query();
				?>
			</div>
		</div>
	</section>
</main>
<script>
	jQuery(function ($) {
		if ($('.feature-slider').length)
			var swiper = new Swiper(".feature-slider", {
				slidesPerView: 1.3,
				spaceBetween: 16,
				autoplay: {
					delay: 5000,
				},
				pagination: {
					el: ".swiper-pagination",
					type: "progressbar",
				},
				breakpoints: {
					768: {
						slidesPerView: 1.9,
						spaceBetween: 16
					},
					991: {
						slidesPerView: 2.1,
						spaceBetween: 16
					},
					1500: {
						slidesPerView: 3.5,
						spaceBetween: 16
					}
				}
			});

		$('.video-btn').click(function () {
			$(this).parent().find('.video-source').fadeIn();
			$(this).parent().find('.video-source iframe')[0].src += "?autoplay=1";
			return false;
		});
		if ($(window).width() < 767) {
			$('.stories-list').slick({
				dots: false,
				infinite: false,
				slidesToShow: 1.1,
				arrows: false,
			});
		};
	});
</script>
<script type="text/javascript"
	src="http://classic.avantlink.com/affiliate_app_confirm.php?mode=js&authResponse=5162225e4379593155ea96202c49b34242f4284e"></script>
<?php get_footer(); ?>