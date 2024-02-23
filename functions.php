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