<?php
/**
 * enqueue styles and scripts
 */
add_action('wp_enqueue_scripts', 'vinnodokan_enqueue_styles');
function vinnodokan_enqueue_styles() {
    $parent_style = 'parent-style';
    wp_enqueue_style($parent_style, get_template_directory_uri() . '/style.css');
    wp_enqueue_style('child-style', get_stylesheet_directory_uri() . '/style.css', array(), time());
    wp_enqueue_style('child-style-min', get_stylesheet_directory_uri() . '/assets/css/style.min.css', array(), time());

}

/**
 * VinnoDokan Top Bar
 */
add_action( 'astra_header_before', function() {
    ?>
    <div class="vinnodokan-top-bar-wrapper">
        <div class="ast-container vinnodokan-top-bar-container">
            <div class="vinnodokan-top-bar">
                <div class="vinnodokan-top-bar-left">
                    <p class="vinnodokan-top-lap"><?php echo esc_html( 'Please Call us to check the Stock before Placing the order!' ); ?></p>
                    <p class="vinnodokan-top-mob"><?php echo esc_html( 'Please Call us to check the Stock!' ); ?></p>
                </div>
                <div class="vinnodokan-top-bar-right">
                    <p><span>Call Us:</span> 01988232393</p>
                    <a href="https://www.facebook.com/vinnodokan" target="_blank">FB Page</a>
                    <a href="https://www.facebook.com/groups/2702224469804560" target="_blank">FB Group</a>
                </div>
            </div>
        </div>
    </div>
    <?php
} );

/**
 * VinnoDokan Header
 */
function vinnodokan_header() {
    
    ?>
    <!DOCTYPE html>
    <?php astra_html_before(); ?>
    <html <?php language_attributes(); ?>>
    <head>
    <?php astra_head_top(); ?>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php 
    if ( apply_filters( 'astra_header_profile_gmpg_link', true ) ) {
        ?>
        <link rel="profile" href="https://gmpg.org/xfn/11"> 
        <?php
    } 
    ?>
    <?php wp_head(); ?>
    <?php astra_head_bottom(); ?>
    </head>

    <body <?php astra_schema_body(); ?> <?php body_class(); ?>>
    <?php astra_body_top(); ?>
    <?php wp_body_open(); ?>

    <a
        class="skip-link screen-reader-text"
        href="#content"
        role="link"
        title="<?php echo esc_attr( astra_default_strings( 'string-header-skip-link', false ) ); ?>">
            <?php echo esc_html( astra_default_strings( 'string-header-skip-link', false ) ); ?>
    </a>

    <div
    <?php
        echo astra_attr(
            'site',
            array(
                'id'    => 'page',
                'class' => 'hfeed site',
            )
        );
        ?>
    >
        <?php
        astra_header_before();

        astra_header();

        astra_header_after();

        astra_content_before();
}

/**
 * VinnoDokan Footer
 */
function vinnodokan_footer() { 
	astra_content_after();
		
	astra_footer_before();
		
	astra_footer();
		
	astra_footer_after(); 
?>
	</div><!-- #page -->
<?php 
	astra_body_bottom();    
	wp_footer(); 
?>
	</body>
</html>
<?php
}


add_filter('woocommerce_currency_symbol', 'change_currency_symbol', 10, 2);
function change_currency_symbol($currency_symbol, $currency) {
    if ($currency === 'BDT') {
        $currency_symbol = '৳'; // Unicode representation for Bangladeshi Taka (TK)
    }
    return $currency_symbol;
}

function custom_add_breadcrumbs_before_product_summary() {
    woocommerce_breadcrumb();
}
add_action( 'woocommerce_before_single_product_summary', 'custom_add_breadcrumbs_before_product_summary', 5 );

// Removde Shipping address
add_action( 'wp_head', function(){
	if ( is_checkout() ):
	?>
	<style>
		label.woocommerce-form__label.woocommerce-form__label-for-checkbox.checkbox,
		#billing_country_field{
			display: none !important;
		}
	</style>
<?php
	endif;
});

// Redirect to checkout after adding to cart
function custom_add_to_cart_redirect() {
    global $woocommerce;
    $checkout_url = wc_get_checkout_url();

    return $checkout_url;
}
add_filter('woocommerce_add_to_cart_redirect', 'custom_add_to_cart_redirect');

// Remove checkout fields.
add_filter( 'woocommerce_checkout_fields' , 'custom_override_checkout_fields' );

function custom_override_checkout_fields( $fields ) {
    // Remove fields except for the ones you need
    unset($fields['billing']['billing_last_name']);
	unset($fields['billing']['billing_company']);
    unset($fields['billing']['billing_address_2']);
    unset($fields['billing']['billing_city']);
    unset($fields['billing']['billing_postcode']);
    unset($fields['billing']['billing_email']);
	$fields['billing']['billing_country']['type'] = 'hidden';
	
	// Rearrange
	$fields['billing']['billing_first_name']['priority'] = 10;
    $fields['billing']['billing_phone']['priority'] = 20;
    $fields['billing']['billing_state']['priority'] = 30;
    $fields['billing']['billing_address_1']['priority'] = 40;
    $fields['order']['order_comments']['priority'] = 50; // Move Order Notes to the bottom
	
	
	// Rename labels and placeholders
    $fields['billing']['billing_first_name']['label'] = 'নাম লিখুন';
    $fields['billing']['billing_first_name']['placeholder'] = 'আপনার নাম লিখুন';

    $fields['billing']['billing_phone']['label'] = 'মোবাইল নাম্বার';
    $fields['billing']['billing_phone']['placeholder'] = 'আপনার নাম্বার দিন';

    $fields['billing']['billing_state']['label'] = 'জেলা';
    $fields['billing']['billing_state']['default'] = 'BD-13';

    $fields['billing']['billing_address_1']['label'] = 'ঠিকানা';
    $fields['billing']['billing_address_1']['placeholder'] = 'আপনার সম্পূর্ণ ঠিকানাটি লিখুন';

    $fields['order']['order_comments']['label'] = 'অর্ডার নোট';
    $fields['order']['order_comments']['placeholder'] = 'প্রোডাক্ট এর কালার মেনশন, সাইজ মেনশন অথবা ডেলিভারি নোট অথবা কিছু বলতে চাইলে এখানে বিস্তারিত বলুন';


    return $fields;
}

// Single Product page

add_filter('woocommerce_get_price_html', 'display_sale_price_before_regular_price', 10, 2);

function display_sale_price_before_regular_price($price_html, $product) {
    // Check if we are on the single product page
    if (is_product()) {
        // If the product is on sale
        ob_start();
        if ($product->is_on_sale() && $product->get_regular_price() && $product->get_sale_price()) {
            $discount_percentage = round(($product->get_regular_price() - $product->get_sale_price()));
            ?>
            <div class="vinno-single-product-price">
                <div class="regular-price">
                    <ins><?php echo wc_price($product->get_sale_price()); ?><span class="discount"> <?php echo $discount_percentage; ?> ৳ ছাড়</span></ins>
                </div>
                <div class="sale-price">
                    <span>M.R.P: </span><del><?php echo wc_price($product->get_regular_price()); ?></del>
                </div>
            </div>
            <?php
        } elseif ($product->is_on_sale() && $product->get_regular_price() && !$product->get_sale_price()) {
            ?>
            <div class="vinno-single-product-price">
                <div class="regular-price">
                    <ins><?php echo wc_price($product->get_regular_price()); ?></ins>
                </div>
            </div>
            <?php
        }

        $price_html = ob_get_clean();

        return $price_html;
    } else {
        // Return the original price HTML for other pages
        return $price_html;
    }
}


add_filter('woocommerce_price_trim_zeros', '__return_true');

add_filter('woocommerce_product_single_add_to_cart_text', 'change_add_to_cart_button_text');
function change_add_to_cart_button_text($text) {
    return __('BUY NOW', 'woocommerce');
}

add_action( 'woocommerce_after_add_to_cart_button', 'add_phone_number_after_add_to_cart_button', 20 );
function add_phone_number_after_add_to_cart_button() {
    if (is_product()) {
    ?>
    <div class="phone-number-wrapper">
        <button class="button"><a href="tel:01988232393"><span class="dashicons dashicons-phone"></span> 01988232393</a></button>
    </div>
    <?php
    }
}