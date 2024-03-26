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
    return __('অর্ডার করুন', 'woocommerce');
}

add_action( 'woocommerce_after_add_to_cart_button', 'add_phone_number_after_add_to_cart_button', 20 );
function add_phone_number_after_add_to_cart_button() {
    if (is_product()) {
    ?>
    <div class="phone-number-wrapper">
        <button class="button"><a href="tel:01988232393"><div class="vinno-phone-icon"><svg aria-hidden="true" class="e-font-icon-svg e-fas-phone-alt" viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg"><path d="M497.39 361.8l-112-48a24 24 0 0 0-28 6.9l-49.6 60.6A370.66 370.66 0 0 1 130.6 204.11l60.6-49.6a23.94 23.94 0 0 0 6.9-28l-48-112A24.16 24.16 0 0 0 122.6.61l-104 24A24 24 0 0 0 0 48c0 256.5 207.9 464 464 464a24 24 0 0 0 23.4-18.6l24-104a24.29 24.29 0 0 0-14.01-27.6z"></path></svg></div> 01988232393</a></button>
    </div>
    <?php
    }
}

/**
 * Change WooCommerce "Place Order" button text
 * 
 * @param string $button_text Existing button text
 * @return string Modified button text
 */
function custom_checkout_button_text( $button_text ) {
	return 'অর্ডার কনফার্ম করুন'; // Replace with your desired text
  }
  add_filter( 'woocommerce_order_button_text', 'custom_checkout_button_text' );
  

add_filter('woocommerce_states', 'change_district_name_language', 10, 1);
function change_district_name_language($states) {
    // Define your translation array for district names
	 $district_translations = array(
		'Bagerhat' => 'বাগেরহাট',
		'Bandarban' => 'বান্দরবান',
		'Barguna' => 'বরগুনা',
		'Barishal' => 'বরিশাল',
		'Bhola' => 'ভোলা',
		'Bogura' => 'বগুড়া',
		'Brahmanbaria' => 'ব্রাহ্মণবাড়িয়া',
		'Chandpur' => 'চাঁদপুর',
		'Chapainawabganj' => 'চাঁপাইনবাবগঞ্জ',
		'Chattogram' => 'চট্টগ্রাম',
		'Chuadanga' => 'চুয়াডাঙ্গা',
		'Cumilla' => 'কুমিল্লা',
		"Cox's Bazar" => 'কক্সবাজার',
		'Dhaka' => 'ঢাকা',
		'Dinajpur' => 'দিনাজপুর',
		'Faridpur ' => 'ফরিদপুর',
		'Feni' => 'ফেনী',
		'Gaibandha' => 'গাইবান্ধা',
		'Gazipur' => 'গাজীপুর',
		'Gopalganj' => 'গোপালগঞ্জ',
		'Habiganj' => 'হবিগঞ্জ',
		'Jamalpur' => 'জামালপুর',
		'Jashore' => 'যশোর',
		'Jhalokati' => 'ঝালকাঠি',
		'Jhenaidah' => 'ঝিনাইদহ',
		'Joypurhat' => 'জয়পুরহাট',
		'Khagrachhari' => 'খাগড়াছড়ি',
		'Khulna' => 'খুলনা',
		'Kishoreganj' => 'কিশোরগঞ্জ',
		'Kurigram' => 'কুড়িগ্রাম',
		'Kushtia' => 'কুষ্টিয়া',
		'Lakshmipur' => 'লক্ষ্মীপুর',
		'Lalmonirhat' => 'লালমনিরহাট',
		'Madaripur' => 'মাদারীপুর',
		'Magura' => 'মাগুরা',
		'Manikganj ' => 'মানিকগঞ্জ',
		'Meherpur' => 'মেহেরপুর',
		'Moulvibazar' => 'মৌলভীবাজার',
		'Munshiganj' => 'মুন্সিগঞ্জ',
		'Mymensingh' => 'ময়মনসিংহ',
		'Naogaon' => 'নওগাঁ',
		'Narail' => 'নড়াইল',
		'Narayanganj' => 'নারায়ণগঞ্জ',
		'Narsingdi' => 'নরসিংদী',
		'Natore' => 'নাটোর',
		'Nawabganj' => 'নওয়াবগঞ্জ',
		'Netrakona' => 'নেত্রকোনা',
		'Nilphamari' => 'নীলফামারী',
		'Noakhali' => 'নোয়াখালী',
		'Pabna' => 'পাবনা',
		'Panchagarh' => 'পঞ্চগড়',
		'Patuakhali' => 'পটুয়াখালী',
		'Pirojpur' => 'পিরোজপুর',
		'Rajbari' => 'রাজবাড়ি',
		'Rajshahi' => 'রাজশাহী',
		'Rangamati' => 'রাঙ্গামাটি',
		'Rangpur' => 'রংপুর',
		'Satkhira' => 'সাতক্ষীরা',
		'Shariatpur' => 'শরীয়তপুর',
		'Sherpur' => 'শেরপুর',
		'Sirajganj' => 'সিরাজগঞ্জ',
		'Sunamganj' => 'সুনামগঞ্জ',
		'Sylhet' => 'সিলেট',
		'Tangail' => 'টাঙ্গাইল',
		'Thakurgaon' => 'ঠাকুরগাঁও'
	);
    // Loop through each state and change the district names if translation is available
    foreach ($states['BD'] as $key => $state) {
        if (isset($district_translations[$state])) {
            $states['BD'][$key] = $district_translations[$state];
        }
    }

    return $states;
}

add_filter('woocommerce_thankyou_order_received_text', 'change_order_received_text');

function change_order_received_text($text) {
    // Change the text to your desired message
    $new_text = "আপনার অর্ডার টি কনফার্ম করা হলো। দয়া করে অপেক্ষা করুন আপনাকে কল করা হবে।";
    
    return $new_text;
}
