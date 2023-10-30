<?php
/**
 * The template for displaying the footer.
 *
 * Contains the body & html closing tags.
 *
 * @package HelloElementor
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'footer' ) ) {
	if ( did_action( 'elementor/loaded' ) && hello_header_footer_experiment_active() ) {
		get_template_part( 'template-parts/dynamic-footer' );
	} else {
		get_template_part( 'template-parts/footer' );
	}
}
?>

<script>
	jQuery(document).ready(function(){
	    jQuery('.quanityBox').each(function() {
            jQuery('<div class="quantity-nav"><button class="quantity-button quantity-up"></button><button class="quantity-button quantity-down"></button></div>').insertAfter(jQuery(this).find('input'));
            });
	    jQuery('.quanityBox').each(function () {
            var spinner = jQuery(this),
                input = spinner.find('input[type="number"]'),
                btnUp = spinner.find('.quantity-up'),
                btnDown = spinner.find('.quantity-down'),
                min = input.attr('min'),
                max = input.attr('max');

            btnUp.click(function () {

            var oldValue = parseFloat(input.val());
            if (oldValue >= max) {
                var newVal = oldValue;
            } else {
                var newVal = oldValue + 1;
            }
            spinner.find("input").val(newVal);
            spinner.find("input").trigger("change");
            });

            btnDown.click(function () {
            var oldValue = parseFloat(input.val());
            if (oldValue <= min) {
                var newVal = oldValue;
            } else {
                var newVal = oldValue - 1;
            }
            spinner.find("input").val(newVal);
            spinner.find("input").trigger("change");
            });

        });
    });


    jQuery(".page-id-70705 .woocommerce").each(function () {
            jQuery(this).find(".woocommerce-cart-form,.cart-collaterals").wrapAll('<div class="woocommerce-new"></div>');
    });
    

</script>

<?php wp_footer(); ?>

</body>
</html>
