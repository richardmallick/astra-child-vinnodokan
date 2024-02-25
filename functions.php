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
                    <a href="#">FB Page</a>
                    <a href="#">FB Group</a>
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
