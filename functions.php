<?php
/**
 * enqueue styles and scripts
 */
add_action('wp_enqueue_scripts', 'vinnodokan_enqueue_styles');
function vinnodokan_enqueue_styles()
{
    $parent_style = 'parent-style';
    wp_enqueue_style($parent_style, get_template_directory_uri() . '/style.css');
    wp_enqueue_style('child-style', get_stylesheet_directory_uri() . '/style.css', array(), time());

}

/**
 * 
 */
add_action( 'astra_header_before', function(){
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