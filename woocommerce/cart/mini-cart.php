<?php
/**
 * Mini-cart template customized 
 * 
 * Contains the markup for the mini-cart, used by the cart widget.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/mini-cart.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 5.2.0
 */

defined( 'ABSPATH' ) || exit;

?>

<?php
if ( !is_admin() ) {
	// nicht im Editor anzeigen

    // do_action( 'woocommerce_before_mini_cart' ); ?>

    <?php if ( ! WC()->cart->is_empty() ) : ?>

        <div class="woocommerce-mini-cart cart_list product_list_widget">
            <table class="ud-wc-block-cart-items ud-wc-block-mini-cart-items">
                <tbody>
                <?php
                    do_action( 'woocommerce_before_mini_cart_contents' );

                    foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
                        $_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
                        $product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

                        if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_widget_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
                            $product_name      = apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key );
                            $thumbnail         = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );
                            $product_price     = apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );
                            $product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
                            ?>
                            <tr class="ud-woocommerce-mini-cart-item <?php echo esc_attr( apply_filters( 'woocommerce_mini_cart_item_class', 'ud-mini_cart_item', $cart_item, $cart_item_key ) ); ?>">
                                <td class="ud-wc-block-cart-item__image" aria-hidden="true">
                                    <?php if ( empty( $product_permalink ) ) : ?>
                                        <?php echo $thumbnail . wp_kses_post( $product_name ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                                    <?php else : ?>
                                        <a href="<?php echo esc_url( $product_permalink ); ?>" class="ud-product-name">
                                            <?php echo $thumbnail . wp_kses_post( $product_name ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                                        </a>
                                    <?php endif; ?>
                                </td>
                                <td class="ud-wc-block-cart-item__product">
                                    <?php echo wc_get_formatted_cart_item_data( $cart_item ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                                    <a href="<?php echo esc_url( $product_permalink ); ?>" class="ud-product-name"><?php echo wp_kses_post( $product_name ); ?></a>
                                    <div class="ud-wc-block-cart-item__prices">
                                        <span class="price ud-wc-block-components-product-price">
                                            <span class="ud-wc-block-formatted-money-amount ud-wc-block-components-formatted-money-amount ud-wc-block-components-product-price__value">
                                                <?php
                                                $product = $cart_item['data'];
                                                $price = WC()->cart->get_product_price( $product );
                                                echo $price;
                                                ?>
                                            </span>
                                        </span>
                                    </div><!-- .ud-wc-block-cart-item__prices -->

                                    <div class="ud-product-quantity" data-title="<?php esc_attr_e( 'Quantity', 'woocommerce' ); ?>">
                                        <?php
                                        if ( $_product->is_sold_individually() ) {
                                            $min_quantity = 1;
                                            $max_quantity = 1;
                                        } else {
                                            $min_quantity = 0;
                                            $max_quantity = $_product->get_max_purchase_quantity();
                                        }
			
                                        // workaround for disabled stock managment
                                        if ( $max_quantity < 0 ) {
                                            $max_quantity = 9999999999;
                                        } // endif
				
                                        $product_quantity = woocommerce_quantity_input(
                                            array(
                                                'input_name'   => "cart[{$cart_item_key}][qty]",
                                                'input_value'  => $cart_item['quantity'],
                                                'max_value'    => $max_quantity,
                                                'min_value'    => $min_quantity,
                                                'product_name' => $_product->get_name(),
                                            ),
                                            $_product,
                                            false
                                        );
                                        ?>
                                        <div class="ud-wc-block-cart-item__quantity">
                                            <div class="ud-wc-block-components-quantity-selector">
                                                <button aria-label="Menge von <?php echo $_product->get_name(); ?> verringern" class="ud-wc-block-components-quantity-selector__button ud-wc-block-components-quantity-selector__button--minus" data-product_id="<?php echo $product_id; ?>">－</button>
                                                <input class="ud-wc-block-components-quantity-selector__input qty" type="text" inputmode="numeric" step="1" min="<?php echo $min_quantity; ?>" max="<?php echo $max_quantity; ?>" id="<?php echo $cart_item_key; ?>" aria-label="Anzahl von <?php echo $_product->get_name(); ?> in deinem Warenkorb." value="<?php echo $cart_item['quantity']; ?>" autocomplete="off">
                                                <button aria-label="Menge von <?php echo $_product->get_name(); ?> erhöhen" class="ud-wc-block-components-quantity-selector__button ud-wc-block-components-quantity-selector__button--plus" data-product_id="<?php echo $product_id; ?>">＋</button>
                                                <?php
                                                // echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item ); // PHPCS: XSS ok.
                                                ?>
                                            </div><!-- .ud-wc-block-components-quantity-selector -->
                                            <?php
                                            echo apply_filters( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                                                'woocommerce_cart_item_remove_link',
                                                sprintf(
                                                    '<a href="%s" class="remove remove_from_cart_button" aria-label="%s" data-product_id="%s" data-cart_item_key="%s" data-product_sku="%s">Artikel entfernen</a>',
                                                    esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
                                                    esc_attr__( 'Remove this item', 'woocommerce' ),
                                                    esc_attr( $product_id ),
                                                    esc_attr( $cart_item_key ),
                                                    esc_attr( $_product->get_sku() )
                                                ),
                                                $cart_item_key
                                            );
                                            ?>
                                        </div><!-- .ud-wc-block-cart-item__quantity -->
                                        <?php
                                        // echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity . '&times; ' . $product_price, $cart_item_key, $cart_item ); // PHPCS: XSS ok.
                                        // echo apply_filters( 'woocommerce_widget_cart_item_quantity', '<span class="quantity">' . sprintf( '%s &times; %s', $cart_item['quantity'], $product_price ) . '</span>', $cart_item, $cart_item_key ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                                        ?>
                                    </div>
                                </td>
                                <td class="ud-product-subtotal" data-title="<?php esc_attr_e( 'Subtotal', 'woocommerce' ); ?>">
                                    <?php
                                        // echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ); // PHPCS: XSS ok.
                                        $subtotal = WC()->cart->get_product_subtotal( $product, $cart_item['quantity'] );
                                        echo $subtotal;
                                    ?>
                                </td>
                            </tr>
                            <?php
                        }
                    }
                    // do_action( 'woocommerce_mini_cart_contents' );
                    ?>
                </tbody>
            </table>
        </div><!-- .woocommerce-mini-cart -->

        <div class="ud-wp-block-woocommerce-mini-cart-footer-block ud-wc-block-mini-cart__footer">

            <div class="ud-wc-block-components-totals-item ud-wc-block-mini-cart__footer-subtotal">

                <span class="ud-woocommerce-mini-cart__total total">
                    <?php
                    /**
                     * Hook: woocommerce_widget_shopping_cart_total.
                     *
                     * @hooked woocommerce_widget_shopping_cart_subtotal - 10
                     */
                    do_action( 'woocommerce_widget_shopping_cart_total' );
                    // echo WC()->cart->get_cart_subtotal();
                    // echo WC()->cart->subtotal_ex_tax;
                    // echo WC()->cart->subtotal;
                    // echo WC()->cart->get_displayed_subtotal();
                    ?>
                </span>

                <div class="ud-wc-block-components-totals-item__description"></div>

                <?php do_action( 'woocommerce_widget_shopping_cart_before_buttons' ); ?>

                <div class="ud-woocommerce-mini-cart__buttons buttons"><?php do_action( 'woocommerce_widget_shopping_cart_buttons' ); ?></div>

                <?php do_action( 'woocommerce_widget_shopping_cart_after_buttons' ); ?>

            </div>

        </div>

    <?php else : ?>

        <p class="ud-woocommerce-mini-cart__empty-message"><?php esc_html_e( 'No products in the cart.', 'woocommerce' ); ?></p>

    <?php endif; ?>

    <?php // do_action( 'woocommerce_after_mini_cart' ); ?>
<?php
} ?>
