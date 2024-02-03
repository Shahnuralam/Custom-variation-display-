// Display dynamic pricing with dropdown if enabled
add_action('woocommerce_single_product_summary', 'display_dynamic_pricing_with_dropdown');
function display_dynamic_pricing_with_dropdown() {
    if (get_option('custom_product_variation_display') === 'yes') {
        global $product;
        
        if ($product->is_type('variable')) {
            $variations = $product->get_available_variations();
            
            if (!empty($variations)) {
                echo '<div class="dynamic-pricing">';
                echo '<select id="size-select">';
                echo '<option value="">Select Size</option>';
                
                foreach ($variations as $variation) {
                    $size = $variation['attributes']['attribute_pa_size'];
                    $color = $variation['attributes']['attribute_pa_color'];
                    $price = $variation['display_price'];
                    $image_src = $variation['image']['url']; // Assuming the variation has an image
                    
                    echo '<option value="' . $size . '" data-color="' . $color . '" data-price="' . $price . '" data-image="' . $image_src . '">' . $size . '</option>';
                }
                
                echo '</select>';
                echo '</div>';
            }
        }
    }
}

// Add JavaScript for dropdown functionality if enabled
add_action('wp_footer', 'dynamic_pricing_dropdown_js');
function dynamic_pricing_dropdown_js() {
    if (get_option('custom_product_variation_display') === 'yes') {
        ?>
        <script>
            jQuery(document).ready(function($) {
                $('#size-select').change(function() {
                    var selectedOption = $('option:selected', this);
                    var selectedColor = selectedOption.data('color');
                    var selectedPrice = selectedOption.data('price');
                    var selectedImage = selectedOption.data('image');
                    
                    if (selectedOption.val()) {
                        // Update the product image
                        $('.woocommerce-product-gallery__image img').attr('src', selectedImage);
                        
                        // Update the variation price
                        $('.woocommerce-variation-price .woocommerce-Price-amount').html('<span class="woocommerce-Price-amount amount">' + selectedPrice + '</span>');
                    }
                });
            });
        </script>
        <?php
    }
}

// Add custom setting to WooCommerce settings page
add_filter('woocommerce_get_settings_products', 'add_custom_product_variation_setting');
function add_custom_product_variation_setting($settings) {
    $settings[] = array(
        'title'    => __('Custom Product Variation Display', 'woocommerce'),
        'desc'     => __('Enable or disable the custom product variation display feature.', 'woocommerce'),
        'id'       => 'custom_product_variation_display',
        'type'     => 'checkbox',
        'default'  => 'no' // Default value is set to 'no'
    );

    return $settings;
}


