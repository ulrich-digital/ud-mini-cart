<?php

/* ------------------- dieser Block greift auf das benutzerdefinierte Mini-Cart über Template zu -------------------------------------

    woocommerce_mini_cart();
    template: \ud_stall-werk_theme\woocommerce\cart\mini-cart.php
    
*/

$classes = 'ud-mini-cart';

if( !empty( $block['align'] ) ):
	$classes[] = 'align' . $block['align'];
endif;

$anchor = '';
if( !empty( $block['anchor'] ) ):
	$anchor = ' id="' . sanitize_title( $block['anchor'] ) . '"';
endif;

// ACF fields
$minicart_titel = get_field('field_646367e92f140');
$minicart_total = get_field('field_6437e7f107c6d');
$minicart_hinweis = get_field('field_6451455a39a3b');
$minicart_autopen = get_field('field_6464cd3581639');

// some css
if ( !$minicart_total ) { ?>
	<style>
		#ud-mini-cart-total {
			display: none;
		}
	</style><?php
} // endif

if ( !empty( $minicart_hinweis ) ) { ?>
    <style>
        .ud-wc-block-components-totals-item__description::before {
            content: "<?php echo $minicart_hinweis; ?>";
        }
    </style>
    <?php
} // endif

?>

<div class="ud-wc-block-mini-cart <?php if( $minicart_autopen == true) { echo "autopen"; }?>">
    <div class="ud-wc-block-mini-cart__button">
        <span id="ud-mini-cart-total"><?php echo WC()->cart->get_cart_total(); ?></span>
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><!--! Font Awesome Pro 6.4.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M0 24C0 10.7 10.7 0 24 0H69.5c22 0 41.5 12.8 50.6 32h411c26.3 0 45.5 25 38.6 50.4l-41 152.3c-8.5 31.4-37 53.3-69.5 53.3H170.7l5.4 28.5c2.2 11.3 12.1 19.5 23.6 19.5H488c13.3 0 24 10.7 24 24s-10.7 24-24 24H199.7c-34.6 0-64.3-24.6-70.7-58.5L77.4 54.5c-.7-3.8-4-6.5-7.9-6.5H24C10.7 48 0 37.3 0 24zM128 464a48 48 0 1 1 96 0 48 48 0 1 1 -96 0zm336-48a48 48 0 1 1 0 96 48 48 0 1 1 0-96z"/></svg>
        <span id="cart-count"><?php echo sprintf ( _n( '%d', '%d', WC()->cart->get_cart_contents_count() ), WC()->cart->get_cart_contents_count() ); ?></span>
    </div>
    <div class="ud-wc-block-mini-cart__drawer" role="dialog">

        <div class="ud-mini-cart-wrapper">

            <div class="ud-components-modal__header">
                <div class="ud-components-modal__header-heading-container"></div>
                <button type="button" class="ud-components-button has-icon" aria-label="Close"><svg width="24" height="24" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M12 13.06l3.712 3.713 1.061-1.06L13.061 12l3.712-3.712-1.06-1.06L12 10.938 8.288 7.227l-1.061 1.06L10.939 12l-3.712 3.712 1.06 1.061L12 13.061z"></path></svg>
                </button>
            </div><!-- .ud-components-modal__header -->

            <?php
            if ( WC()->cart->get_cart_contents_count() ) { ?>
                <h2 class="ud-wp-block-woocommerce-mini-cart-title-block wc-block-mini-cart__title">
                    <?php
                    if ( !empty( $minicart_titel ) ) {
                        echo $minicart_titel . ' '; ?>
                        (<span id="ud-mini-cart-count"><?php echo sprintf ( _n( '%d Artikel', '%d Artikel', WC()->cart->get_cart_contents_count() ), WC()->cart->get_cart_contents_count() ); ?></span>)
                        <?php
                    } else { ?>
                        Dein Warenkorb (<span id="ud-mini-cart-count"><?php echo sprintf ( _n( '%d Artikel', '%d Artikel', WC()->cart->get_cart_contents_count() ), WC()->cart->get_cart_contents_count() ); ?></span>)
                        <?php
                    } // endif ?>
                </h2>
                <?php
            } // endif ?>

            <div class="ud-wp-block-woocommerce-filled-mini-cart-contents-block">

                <div class="widget_shopping_cart_content ud-wc-block-mini-cart__items">

                    <?php
                    woocommerce_mini_cart();
                    ?>

                </div><!-- .widget_shopping_cart_content ud-wc-block-mini-cart__items -->
            </div><!-- .ud-wp-block-woocommerce-filled-mini-cart-contents-block -->
        </div><!-- .ud-mini-cart-wrapper -->
    </div><!-- .ud-wc-block-mini-cart__drawer -->
</div><!-- .ud-wc-block-mini-cart -->
