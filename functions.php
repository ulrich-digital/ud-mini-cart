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


?>
