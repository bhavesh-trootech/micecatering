<?php 
add_filter( 'woocommerce_cart_needs_payment', '__return_false' );


 
 add_action( 'woocommerce_email_order_details', 'bbloomer_add_content_specific_email', 20, 4 );
  
function bbloomer_add_content_specific_email( $order, $sent_to_admin, $plain_text, $email ) {
    $ordeid=$order->id;
    $order = new WC_Order( $ordeid );
    $items = $order->get_items();
    $html='';
    foreach ( $items as $item ) {
        $product_name = $item['name'];
        $product_id = $item['product_id'];
        $quantity = $item->get_quantity();
        $product_instance = wc_get_product($product_id);
        $product_full_description = $product_instance->get_description();
        $product_short_description = $product_instance->get_short_description();
       

        $html.='<table border="1" cellpadding="20" cellspacing="0" width="100%"><tr><td><b>Product name:</b></td><td>'.$product_name.'</td></tr>';
        $html.='<tr><td><b>Product Sale Price:</b></td><td>'.$product_instance->get_sale_price().'</td></tr>';
        $html.='<tr><td><b>Product Price:</b></td><td>'.$product_instance->get_regular_price().'</td></tr>';
        $html.='<tr><td><b>Quantity:</b></td><td>'.$quantity.'</td></tr>';
        $html.='<tr><td colspan="2">'.$product_full_description.'</td></tr>';
        $html.='</table><br/>';
    }
    $item_totals = $order->get_order_item_totals();
    if ( $item_totals ) {
        $i = 0;
        $html.='<table border="1" cellpadding="20" cellspacing="0" width="100%">';
        foreach ( $item_totals as $total ) {
            $i++;
            
             $html.='<tr><th>'.wp_kses_post( $total['label'] ).'</td><td>'.wp_kses_post( $total['value'] ).'</td></tr>';
            
        }
        $html.='</table>';
    }

   if ( $email->id == 'customer_processing_order' ) {
      echo $html;
   }
}
add_action( 'woocommerce_email_order_details', 'modifyEmailText', 10, 4 );  

function disable_coupon_field_on_checkout( $enabled ) {
    if ( is_checkout() ) {
    $enabled = false;
    }
    return $enabled;
    }
   add_filter( 'woocommerce_coupons_enabled', 'disable_coupon_field_on_checkout' );
function disable_coupon_field_on_cart( $enabled ) {
        if ( is_cart() ) {
            $enabled = false;
        }
        return $enabled;
    }
   add_filter( 'woocommerce_coupons_enabled', 'disable_coupon_field_on_cart' );


    add_action( 'woocommerce_thankyou', 'bbloomer_redirectcustom');
  
    function bbloomer_redirectcustom( $order_id ){
        $order = wc_get_order( $order_id );
        $url = get_site_url().'/gracias/';
        if ( ! $order->has_status( 'failed' ) ) {
            wp_safe_redirect( $url );
            exit;
        }
    }




    add_action( 'woocommerce_email_after_order_table', 'add_link_back_to_order', 10, 2 );
    function add_link_back_to_order( $order, $is_admin ) {

	// Only for admin emails
	if ( ! $is_admin ) {
		return;
	}
    $ordeid=$order->id;
    $order = new WC_Order( $ordeid );
    $items = $order->get_items();
	$customer_id = $order->get_customer_id();
    $user_id = $order->get_user_id();
    $user = $order->get_user();
    $user_roles = $user->roles;

    $billing_first_name = $order->get_billing_first_name();
    $billing_last_name  = $order->get_billing_last_name();
    $billing_company    = $order->get_billing_company();
    $billing_address_1  = $order->get_billing_address_1();
    $billing_address_2  = $order->get_billing_address_2();
    $billing_city       = $order->get_billing_city();
    $billing_state      = $order->get_billing_state();
    $billing_postcode   = $order->get_billing_postcode();
    $billing_country    = $order->get_billing_country();
    $billing_email  = $order->get_billing_email();
    $billing_phone  = $order->get_billing_phone();

    $billing_display_data = Array("First Name" => $billing_first_name,
    "Last Name" => $billing_last_name,
    "Company" => $billing_company,
    "Address Line 1" => $billing_address_1,
    "Address Line 2" => $billing_address_2,
    "City" => $billing_city,
    "State" => $billing_state,
    "Post Code" => $billing_postcode,
    "Country" => $billing_country,
    "Email" => $billing_email,
    "Phone" => $billing_phone);

    $link= "<h3>Here is the customer's  address from the order - </h3><br>";
    $link.='<table border="1" cellpadding="20" cellspacing="0" width="100%">';
    foreach ( $billing_display_data as $key => $value ) {
        $link.='<tr><th>'.wp_kses_post( $key).'</td><td>'.wp_kses_post($value).'</td></tr>';
    }
    $link.= "</table><br>";
    $link.='<h3>Product Details User Find Budget Details:</h3><br/>';
    foreach ( $items as $item ) {
        $product_name = $item['name'];
        $product_id = $item['product_id'];
        $quantity = $item->get_quantity();
        $product_instance = wc_get_product($product_id);
        $link.='<table border="1" cellpadding="20" cellspacing="0" width="100%"><tr><td><b>Product name:</b></td><td>'.$product_name.'</td></tr>';
        $link.='<tr><td><b>Product Sale Price:</b></td><td>'.$product_instance->get_sale_price().'</td></tr>';
        $link.='<tr><td><b>Product Price:</b></td><td>'.$product_instance->get_regular_price().'</td></tr>';
        $link.='<tr><td><b>Quantity:</b></td><td>'.$quantity.'</td></tr>';
        $link.='</table><br/>';
    }
    $item_totals = $order->get_order_item_totals();
    if ( $item_totals ) {
        $i = 0;
        $link.='<table border="1" cellpadding="20" cellspacing="0" width="100%">';
        foreach ( $item_totals as $total ) {
            $i++;
            
             $link.='<tr><th>'.wp_kses_post( $total['label'] ).'</td><td>'.wp_kses_post( $total['value'] ).'</td></tr>';
            
        }
        $link.='</table>';
    }

    echo $link;

}

add_shortcode( 'esp_category_productsnew', 'esp_category_productsnew' );

function esp_category_productsnew($atts) {
    ob_start();
    extract(shortcode_atts(array(
        'product_cat'      => '',
        'per_page'  => '10',
        'orderby' => 'date',
        'order' => 'desc'
), $atts));

$args = array(
        'post_type' => 'product',
        'post_status' => 'publish',
        'posts_per_page' => $per_page,
        'orderby' => $orderby,
        'order' => $order,
        'tax_query'             => array(
                array(
                        'taxonomy'      => 'product_cat',
                        'terms'         => array( esc_attr($product_cat) ),
                        'field'         => 'term_id'
                )
        )
);
$loop = new WP_Query( $args );

echo '<div class="slicksilderproduct" id="sliccatid_'.$product_cat.'" data-catid="'.$product_cat.'">';
if ($loop->have_posts()) {
  while ($loop->have_posts()) : $loop->the_post();
    $pid=get_the_ID();
    $title=get_the_title();
    $_product = wc_get_product( $pid );
    $feat_image = wp_get_attachment_url( get_post_thumbnail_id($pid) );
    if( $_product->is_on_sale() ) {
       $pricesla= $_product->get_sale_price();
    }else{
        $pricesla=$_product->get_regular_price();
    }

    ?>
    <div class="cus_product" data-id="custproduct_<?php echo get_the_ID(); ?>">
        <div class="productimg"><a href="<?php echo get_the_permalink(); ?>"><img src="<?php echo $feat_image; ?>"></a></div>
        <div class="product_descrption">
			<div class="product_metas">
				<div class="prdtitle"><h1><a href="<?php echo get_the_permalink(); ?>"><?php echo $title; ?></a></h1></div>
				<div class="prdmetadetails">
					<span class="unite">1 Ud</span>
					<span class="productprice"><?php echo $pricesla;?>€</span>
				</div>
			</div>
            <div class="custaddcartbtn" data-id="addcartdiv_<?php echo $pid; ?>">
                
                <div class="quanityBox" data-id="quanityprd_<?php echo $pid; ?>">
                <input type="number" class="qty-box-in" id="quantity" name="quantity" value="1" min="1" max="100" data-min="1">
                </div>
                
                <a class="custprdbtnaddcard" data-quantity="1" data-id="<?php echo $pid; ?>"> Añadir a la cesta <span class="btn-ring"></span></a>

                <a href="<?php echo $_product->add_to_cart_url() ?>" value="<?php echo esc_attr( $_product->get_id() ); ?>" 
                class="ajax_add_to_cart add_to_cart_button btnhiden custaddbtnhidden" id="btaddhidden_<?php echo get_the_ID(); ?>" data-product_id="<?php echo get_the_ID(); ?>" data-product_sku="<?php echo esc_attr($sku) ?>"
                aria-label="Add “<?php the_title_attribute() ?>” to your cart"> Añadir a la cesta</a>               

             </div>
             <!-- <div class="viewcartdivbtn" data-id="viewcartdiv_<?php echo get_the_ID(); ?>">
                <a href="<?php echo get_site_url(); ?>/cart/" value="view cart" 
                class="viewcartbuttn" id="viewcustprd<?php echo get_the_ID(); ?>" >Ver carrito</a>
             </div> -->
        </div>
    </div>
    <?php
    endwhile;
    wp_reset_postdata();
  
}
echo '</div>';
?>
<script>
    
    jQuery('input[type="number"]').on('change', function () {
     var min = parseInt(this.dataset.min),
        num = isNaN(parseInt(this.value)) ? 0 : parseInt(this.value),
        clamped = Math.max(num, min);
            console.log(min,"::min");
            console.log(num,"::num");
            console.log(clamped,"::clamped");
                if(num != clamped) {
                    //alert(num + ' is less than 1');
                    jQuery(this).val(clamped);
                }
            });
      
     jQuery(document).on('keypress','.qty-box-in',function(evt){

        if (evt.which != 8 && evt.which != 0 && evt.which < 48 || evt.which > 57)
        {
            evt.preventDefault();
        }
    });
        
    // jQuery(document).on('input','.qty-box-in',function(evt){
    //     if (/^0/.test(this.value)) {
    //         this.value = this.value.replace(/^0/, 1)
    //     }
    // })

    // jQuery('input','.qty-box-in').on('input', function () {
    //     if (!this.validity.valid) {
    //         alert(this.value + ' is not a number or is less than 1');
    //         this.value = 1;
    //     }    
    // });

    // jQuery(document).ready(function ($) {
    //     jQuery('input[type="number"]').on('change', function () {
    //         var min = parseInt(this.dataset.min),
    //             num = isNaN(parseInt(this.value)) ? "" : parseInt(this.value),
    //             clamped = Math.max(num, min);

    //         if(num != clamped) {
            
    //             jQuery(this).val(clamped);
    //         }
    //     });
    // });

      </script>
<?php
	$html=ob_get_clean();

	return $html;

	
	
}

add_shortcode( 'catename', 'catename' );

function catename($atts) {
    ob_start();
    extract(shortcode_atts(array(
        'id'      => '',
      
), $atts));

$terms_post = get_the_terms( $id , 'product_cat' );
    // get the thumbnail id using the queried category term_id
    $thumbnail_id = get_term_meta( $id, 'thumbnail_id', true ); 

    // get the image URL
    $image = wp_get_attachment_url( $thumbnail_id ); 
    $cat_url = get_category_link($id);

    // print the IMG HTML
    echo "<a href='$cat_url'><div class='catimg aa'><img src='{$image}' alt='' width='762' height='365' /></div>";
echo '<div class="catlistprd" id="'.$id.'">'.get_the_category_by_ID($id).'</div></a>';

$html=ob_get_clean();

	return $html;
}


add_action('wp_footer','footerscript');
function footerscript(){

    foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
        $prdid= $cart_item['product_id'];
            $class='custproduct_'.$prdid; 
            $class_vc='viewcartdiv_'.$prdid;
            $class_ad='addcartdiv_'.$prdid;
            
            ?>
            <script>
                setTimeout(function () {
                    jQuery('[data-id="<?php echo $class;?>"]').removeClass("cus_active");
                    
                    //jQuery('#<?php //echo $class;?>').removeClass("cus_active");

                    jQuery(document).ready(function(){
                    
                        //jQuery('#<?php //echo $class;?>').addClass("cus_active");
                        jQuery('[data-id="<?php echo $class;?>"]').addClass("cus_active");

                        jQuery('.cus_active .product_descrption [data-id="<?php echo $class_vc; ?>"]').show();
                        
                        //jQuery('.cus_active .product_descrption #<?php //echo $class_vc;?>').show();
                        jQuery('.cus_active .product_descrption [data-id="<?php echo $class_ad; ?>"]').hide();
                        //jQuery('.cus_active .product_descrption #<?php //echo $class_ad;?>').hide();
                    });
                }, 2000);
            </script>
            <?php
        
    } 

    ?>
    <script>

    // jQuery(document).ready(function(){

    //     jQuery('.slicksilderproduct').slick({
    //         slidesToShow: 4,
    //         slidesToScroll: 4,
    //         autoplay: false,
	// 		dots: true,
    //         autoplaySpeed: 2000,
	// 		responsive: [
	// 			{
	// 				breakpoint: 1024,
	// 				settings: {
	// 					slidesToShow: 2,
	// 					slidesToScroll: 1,
	// 					infinite: true,
	// 					dots: true
	// 				}
	// 			},
	// 			{
	// 				breakpoint: 690,
	// 				settings: {
	// 					slidesToShow: 1,
	// 					slidesToScroll: 1,
	// 					dots: true
	// 				}
	// 			},
	// 			{
	// 				breakpoint: 480,
	// 				settings: {
	// 					slidesToShow: 1,
	// 					slidesToScroll: 1,
	// 					dots: true
	// 				}
	// 			}

	// 		]
    //   });


    // });
        
// jQuery( "#esp_tab .elementor-tab-title" ).click(function() {
//     jQuery('#esp_tab .slicksilderproduct').slick('unslick');
  
//     var  content = jQuery(this).attr('aria-controls');
//     var  catid = jQuery("#"+content+" .slicksilderproduct").data('catid');
   
//     jQuery('#sliccatid_'+catid).slick({
//             slidesToShow: 4,
//             slidesToScroll: 1,
//             autoplay: false,
// 			dots: true,
//             autoplaySpeed: 2000,
// 			responsive: [
// 				{
// 					breakpoint: 1024,
// 					settings: {
// 						slidesToShow: 2,
// 						slidesToScroll: 1,
// 						infinite: true,
// 						dots: true
// 					}
// 				},
// 				{
// 					breakpoint: 690,
// 					settings: {
// 						slidesToShow: 1,
// 						slidesToScroll: 1,
// 						dots: true
// 					}
// 				},
// 				{
// 					breakpoint: 480,
// 					settings: {
// 						slidesToShow: 1,
// 						slidesToScroll: 1,
// 						dots: true
// 					}
// 				}

// 			]
//       });
//       jQuery('#sliccatid_'+catid+' .slick-prev.slick-arrow').trigger('click');
// });
// jQuery( "#esp_tab_2 .elementor-tab-title" ).click(function() {
//     jQuery('#esp_tab_2 .slicksilderproduct').slick('unslick');
  
//     var  content = jQuery(this).attr('aria-controls');
//     var  catid = jQuery("#"+content+" .slicksilderproduct").data('catid');
   
//     jQuery('#sliccatid_'+catid).slick({
//             slidesToShow: 4,
//             slidesToScroll: 1,
//             autoplay: false,
// 			dots: true,
//             autoplaySpeed: 2000,
// 			responsive: [
// 				{
// 					breakpoint: 1024,
// 					settings: {
// 						slidesToShow: 2,
// 						slidesToScroll: 1,
// 						infinite: true,
// 						dots: true
// 					}
// 				},
// 				{
// 					breakpoint: 690,
// 					settings: {
// 						slidesToShow: 1,
// 						slidesToScroll: 1,
// 						dots: true
// 					}
// 				},
// 				{
// 					breakpoint: 480,
// 					settings: {
// 						slidesToShow: 1,
// 						slidesToScroll: 1,
// 						dots: true
// 					}
// 				}

// 			]
//       });
//       jQuery('#sliccatid_'+catid+' .slick-prev.slick-arrow').trigger('click');
// });
   
   	
    </script>
<style>
    .ajax_add_to_cart.add_to_cart_button.added,.custaddbtnhidden { display: none;}
    .custprdbtnaddcard {cursor: pointer;}
	#espcatp li.product-category {cursor: pointer;}
</style>

	<?php

}



// add_filter( 'woocommerce_cart_contents_count', 'misha_get_cart_count' );

// function misha_get_cart_count( $count ) {
// 	return count( WC()->cart->get_cart() ); // easy!
// }
?>