<div id="cartModal" class="modal-trans">
	<div class="mot-bg"></div>
	<div class="mot-content">
		<div class="mot-close"></div>
		<h2 class="mot-title text-center">Your Cart</h2>
		<div class="mot-notice"><i>Free Shipping $70+</i></div>
		<?php
			$carts = WC()->cart->get_cart();
			if(count($carts) > 0) {
		?>
		<div class="mot-carts">
			<?php
				foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
					$_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
					$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );
					$product_name = apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key );
					if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
						$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
			?>
			<div class="motc-item">
				<div class="list-flex">
					<?php
						$thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );

						if ( ! $product_permalink ) {
							echo $thumbnail; 
						} else {
							printf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $thumbnail ); 
						}
					?>
					<div class="info">
						<?php
						if ( ! $product_permalink ) {
							echo wp_kses_post( $product_name . '&nbsp;' );
						} else {
							/**
							 * This filter is documented above.
							 *
							 * @since 2.1.0
							 */
							echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', sprintf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $_product->get_name() ), $cart_item, $cart_item_key ) );
						}
						?>
					</div>
				</div>
			</div>
			<?php } } ?>
		</div>
		<?php
			} else {
		?>
		<div class="mot-section text-center">
			<div class="motc-empty">Your cart is empty!</div>
			<p>Add your favorite items to your cart.</p>
			<a href="/shop" class="btn btn-goshop">Shop Now</a>
		</div>
		<div class="mot-section mot-like">
			<div class="text-center ml-title">You might also like</div>
			<div class="motlk-products">
				<?php
					$args = array(
						'post_type' => 'product',
						'posts_per_page' => 6
					);
				 	$the_query = new WP_Query( $args );
					while ($the_query->have_posts() ) : $the_query->the_post();
					$product = wc_get_product($post->ID);
				?>
				<div class="motlk-pitem">
					<a href="<?php the_permalink(); ?>" class="motp-image">
						<?php the_post_thumbnail(); ?>
					</a>
					<div class="motp-info">
						<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
						<?php 
	        				 if ($product->is_type('variable')) {
	        				 		$regular_price = $product->get_variation_regular_price('min');
	        				 		$sale_price = $product->get_variation_sale_price('min');
	        				 } else {
	        				 		$regular_price = $product->get_regular_price();
											$sale_price = $product->get_sale_price();
	        				 }
	        			?>
	        			<?php if($sale_price) { ?>
	        			<div class="price-current">$<span class="pnum"><?php echo $regular_price; ?></span></div>
	        			<div class="price-sale">$<span class="pnum"><?php echo $sale_price; ?></span></div>
	        			<?php } else { ?>
	        			<div class="price-sale"><?php echo $regular_price; ?></div>
	        			<?php } ?>
	        			<a href="" class="btn btn-addtocart">Add</a>
					</div>
				</div>
				<?php
					endwhile;
					wp_reset_query();
				?>
			</div>
		</div>
		<?php } ?>
	</div>
</div>
<script>
jQuery(function($) {
	$('.mot-bg').on('click',function() {
		$(this).parents('.modal-trans').removeClass('active');
	});
})	
</script>