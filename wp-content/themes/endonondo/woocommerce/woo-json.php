<?php 
// Add to cart
function is_ajax_get_addtocart_json(){
  return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
}
add_action('init', 'get_addtocart_json');
function get_addtocart_json() {
    if($_GET['get_addtocart_json'] && is_ajax_get_addtocart_json()){
        if (!isset($_GET['product_id'])) {
            $mess = 'Product ID missing.';
        }

        $product_id = (int) $_GET['product_id'];
        $variation_id = isset($_GET['variation_id']) ? (int) $_GET['variation_id'] : 0;
        $quantity = isset($_GET['quantity']) ? (int) $_GET['quantity'] : 1;
        $variation_attributes = isset($_GET['variation']) ? $_GET['variation'] : [];
        $product = wc_get_product($product_id);
        if (!$product) {
            $mess = 'Invalid product ID.';
        }
        $WC_Cart = new WC_Cart();
        $added = $WC_Cart->add_to_cart($product_id, $quantity, $variation_id, $variation_attributes);
        if ($added) {
            $mess = 'Product added to cart successfully!';
        } else {
            $mess = 'Failed to add product to cart.';
        }
        echo json_encode($mess);
        exit;
    }
}
?>