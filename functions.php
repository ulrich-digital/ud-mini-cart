<?php

/* =============================================================== *\
   ACF - Blocks
\* =============================================================== */

add_action('acf/init', 'my_acf_blocks_init');

function my_acf_blocks_init() {

    if (function_exists('acf_register_block_type')) {

        // Block: Mini-Cart
        register_block_type(dirname(__FILE__) . '/blocks/acf_mini-cart');

    } // endif

} // function


/* =============================================================== *\
   Ajax Cart Fragments
\* =============================================================== */

add_filter('woocommerce_add_to_cart_fragments', 'ajax_add_to_cart_fragment');

function ajax_add_to_cart_fragment($fragments) {
    ob_start();
    $fragments['#cart-count'] = '<span id="cart-count">' . WC()->cart->get_cart_contents_count() . '</span>';
    $fragments['#ud-mini-cart-count'] = '<span id="ud-mini-cart-count">' . sprintf(_n('%d Artikel', '%d Artikel', WC()->cart->get_cart_contents_count()), WC()->cart->get_cart_contents_count()) . '</span>';
    $fragments['#ud-mini-cart-total .woocommerce-Price-amount.amount'] = WC()->cart->get_cart_total();
    ob_get_clean();
    return $fragments;
}


/* =============================================================== *\
   UD Mini-Cart 
\* =============================================================== */

function enqueue_cart_show_ajax() {
    // wp_register_script( 'cart-show-ajax-js', get_template_directory_uri() . '/assets/js/cart-qty-ajax.js', array( 'jquery' ), '', true );
    wp_register_script('cart-show-ajax-js', get_template_directory_uri() . '/blocks/acf_mini-cart/block.js', array('jquery'), '', true);
    wp_localize_script('cart-show-ajax-js', 'cart_show_ajax', array('ajax_url' => admin_url('admin-ajax.php')));
    wp_enqueue_script('cart-show-ajax-js');
}
add_action('wp_enqueue_scripts', 'enqueue_cart_show_ajax');

function update_item_from_cart() {
    $cart_item_key = $_POST['cart_item_key'];
    $quantity = $_POST['qty'];

    // Get mini cart
    ob_start();

    foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
        if ($cart_item_key == $_POST['cart_item_key']) {
            $product = $cart_item['data'];
            WC()->cart->set_quantity($cart_item_key, $quantity, $refresh_totals = true);

            /*
			$quantity = $cart_item['quantity'];
			$subtotal = WC()->cart->get_product_subtotal( $product, $cart_item['quantity'] );
			$price = WC()->cart->get_product_price( $product );
			*/

            /* https://rudrastyh.com/woocommerce/get-number-of-items-in-cart.html */
            $cart_count = sprintf(_n('%d Artikel', '%d Artikel', WC()->cart->get_cart_contents_count()), WC()->cart->get_cart_contents_count());
            $cart_count_int = WC()->cart->get_cart_contents_count();
            $subtotal = WC()->cart->get_product_subtotal($product, $quantity); // Wichtig: per Ajax übergebener Wert verwenden und nicht nicht $cart_item['quantity']
            $price = $product->get_price();
        }
    }
	
    WC()->cart->calculate_totals();
    WC()->cart->maybe_set_cart_cookies();
    $cart_total = WC()->cart->get_cart_total();

    $response = array();
    $response['price'] = $price;
    $response['quantity'] = $quantity;
    $response['subtotal'] = $subtotal;
    $response['currency'] = $currency;
    $response['cart_count'] = $cart_count;
    $response['cart_count_int'] = $cart_count_int;
    $response['cart_total'] = $cart_total;
    echo json_encode($response);
    wp_die();
}

add_action('wp_ajax_update_item_from_cart', 'update_item_from_cart');
add_action('wp_ajax_nopriv_update_item_from_cart', 'update_item_from_cart');


?>
