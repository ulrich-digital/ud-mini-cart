jQuery(document).ready(function ($) {

    console.log("Mini-Cart UD ready");

    $('.ud-mini-cart-wrapper').on( 'click', 'button.ud-wc-block-components-quantity-selector__button--minus, button.ud-wc-block-components-quantity-selector__button--plus', function() {

        // Get current quantity values
        var qty = $( this ).closest( '.ud-wc-block-components-quantity-selector' ).find( '.qty' );
        var val   = parseFloat(qty.val());
        var max = parseFloat(qty.attr( 'max' ));
        var min = parseFloat(qty.attr( 'min' ));
        var step = parseFloat(qty.attr( 'step' ));

        // Change the value if plus or minus
        if ( $( this ).is( '.ud-wc-block-components-quantity-selector__button--plus' ) ) {
           if ( max && ( max <= val ) ) {
              qty.val( max );
           }
        else {
           qty.val( val + step );
             }
        }
        else {
           if ( min && ( min >= val ) ) {
              qty.val( min );
           }
           else if ( val > 1 ) {
              qty.val( val - step );
           }
        } // endif
    });

    $(document).on('change', '.qty', function (e) {
        el = $(this);
        ajax_qty_update(el);
    });

    $(document).on('click', 'button.ud-wc-block-components-quantity-selector__button--minus, button.ud-wc-block-components-quantity-selector__button--plus', function(e) {
        el = $(this);
        ajax_qty_update(el);
    });

    /* click outside mini-cart__drawer */
    $(document).click(function(event) {
        var container = $(".ud-wc-block-mini-cart__drawer");
        var minicart_button = $('.ud-wc-block-mini-cart__button');
        var addcart_button = $('.cart_button');
        var autopen = $('.ud-wc-block-mini-cart').hasClass('autopen');
        console.log(autopen);
        if ( minicart_button.is(event.target) || minicart_button.has(event.target).length || ( addcart_button.is(event.target) && autopen ) || ( addcart_button.has(event.target).length && autopen ) ) {
            console.log("click");
            container.removeClass('closed').addClass('open');
            $("body").css("overflow", "hidden"); // Scrollbar ausblenden
            $(".ud-wc-block-mini-cart").prepend('<div class="ud-drawer__screen-overlay"></div>'); // add overlay
        } else
        if ( !container.is(event.target) && !container.has(event.target).length && container.hasClass('open')) {
            container.removeClass('open').addClass('closed');
            $("body").css("overflow", "auto"); // Scrollbar einblenden
            $('.ud-drawer__screen-overlay').delay(500).remove(); // remove overlay
        } // endif
    });

    /* close button */
    $(document).on('click','.ud-wc-block-mini-cart__drawer .ud-components-button', function(e) {
        $(this).closest('.ud-wc-block-mini-cart__drawer').removeClass('open').addClass('closed');
        $("body").css("overflow", "auto"); // Scrollbar einblenden
        $('.ud-drawer__screen-overlay').delay(500).remove(); // remove overlay
    });

    function ajax_qty_update(el) {
        var qty = el.closest( '.ud-wc-block-components-quantity-selector' ).find( 'input.qty' ).val();
        // console.log("qty: " + qty);
        var cart_item_key = el.closest( '.ud-wc-block-components-quantity-selector' ).find( '.qty' ).attr("id");
        // console.log("cart_item_key: " + cart_item_key);
        $.ajax({
            type: 'POST',
            // dataType: 'json',
            dataType: 'text',
            cache: false,
            url: cart_show_ajax.ajax_url,
            data: {
                action: 'update_item_from_cart',
                'cart_item_key': cart_item_key,
                'qty': qty,
            },
            success: function (data) {
                var json = $.parseJSON(data);
                // console.log(json.quantity);
                // console.log(json.subtotal);
                // var price_string = (json.price*qty).toFixed(2);
                // console.log(json.cart_total);
                // el.closest( '.mini_cart_item' ).find( '.product-subtotal .woocommerce-Price-currencySymbol' ).html(json.currency); 
                el.closest('.ud-mini_cart_item' ).find( '.ud-product-subtotal' ).html(json.subtotal); 
                el.closest('.ud-mini-cart-wrapper').find('#ud-mini-cart-count').html(json.cart_count);
                $('.ud-wc-block-mini-cart #cart-count').html(json.cart_count_int);
                $('#ud-mini-cart-total').html(json.subtotal);
                el.closest('.ud-mini-cart-wrapper').find('.ud-woocommerce-mini-cart__total .woocommerce-Price-amount.amount').replaceWith(json.cart_total);
            }

        });

    }


});
