<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

// BEGIN ENQUEUE PARENT ACTION
// AUTO GENERATED - Do not modify or remove comment markers above or below:
include 'custom.php';
if ( !function_exists( 'chld_thm_cfg_locale_css' ) ):
    function chld_thm_cfg_locale_css( $uri ){
        if ( empty( $uri ) && is_rtl() && file_exists( get_template_directory() . '/rtl.css' ) )
            $uri = get_template_directory_uri() . '/rtl.css';
        return $uri;
    }
endif;
add_filter( 'locale_stylesheet_uri', 'chld_thm_cfg_locale_css' );
         
if ( !function_exists( 'child_theme_configurator_css' ) ):
    function child_theme_configurator_css() {
        wp_enqueue_style( 'chld_thm_cfg_child', trailingslashit( get_stylesheet_directory_uri() ) . 'style.css', array( 'hello-elementor','hello-elementor','hello-elementor-theme-style' ) );
        wp_enqueue_style( 'slickcss', '//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css' ); 
        wp_enqueue_script( 'slickjs', '//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js', array( 'jquery' ), false, true );
        wp_enqueue_script( 'custom_js', get_stylesheet_directory_uri() . '/custom.js', array( 'jquery' ), (string) time(), true );
        $localise = array( 'ajax_url' => admin_url( 'admin-ajax.php' ), 'checkout_url' => get_permalink( wc_get_page_id( 'checkout' ) ) );
        wp_localize_script( 'custom_js', 'SO_TEST_AJAX', $localise );
    }
endif;
add_action( 'wp_enqueue_scripts', 'child_theme_configurator_css', 10 );

// END ENQUEUE PARENT ACTION
add_action("wp_enqueue_scripts", "gbi_jquery");
function gbi_jquery(){
	wp_register_script('jquery', 'https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.js', array('jquery'), '3.6.0', true );
    wp_enqueue_script('jquery');
}

function gbi_enqueue_analytics(){
	?>
<!-- SCRIPT ANALYTICS -->
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-104261759-1', 'auto');
  ga('send', 'pageview');

</script>

<?php
}
add_action("wp_head", "gbi_enqueue_analytics");

function gbi_tag_manager_head(){
    ?>
<!-- Google Tag Manager -->
<script>
    (function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','GTM-N6WXZWC');
</script>
<!-- End Google Tag Manager -->

<?php
}
add_action("wp_head","gbi_tag_manager_head");

function gbi_tag_manager_body(){
    ?>
<!-- Google Tag Manager (noscript) -->
<noscript>
    <iframe src="https://www.googletagmanager.com/ns.html?id=GTM-N6WXZWC"
    height="0" width="0" style="display:none;visibility:hidden"></iframe>
</noscript>
<!-- End Google Tag Manager (noscript) -->
    <?php
}

add_action("wp_body","gbi_tag_manager_body");

add_action( 'wp_enqueue_scripts', 'enqueue_load_fa' );
function enqueue_load_fa() {
wp_enqueue_style( 'load-fa', 'https://use.fontawesome.com/releases/v6.1.1/css/all.css' );
}


/********************************************************* */
/****/
add_action( 'wp_ajax_nopriv_myajax', 'myajax_callback' );
add_action( 'wp_ajax_myajax', 'myajax_callback' );
function myajax_callback() {        
        ob_start();

        $product_id = $_POST['productId'];
        $quantity = $_POST['quantity'];

        $passed_validation = apply_filters( 'woocommerce_add_to_cart_validation', true, $product_id, $quantity );
        $product_status    = get_post_status( $product_id );

        if ( $passed_validation && WC()->cart->add_to_cart( $product_id, $quantity ) && 'publish' === $product_status ) {

            do_action( 'woocommerce_ajax_added_to_cart', $product_id );

            //wc_add_to_cart_message( $product_id );

        } else {
            $data = array(
                'error'       => true,
                'product_url' => apply_filters( 'woocommerce_cart_redirect_after_error', get_permalink( $product_id ), $product_id )
            );

            wp_send_json( $data );

        }
        //echo wc_get_template( 'cart/mini-cart.php' );

        die();
}
/****/



function add_file_types_to_uploads($file_types){
    $new_filetypes = array();
    $new_filetypes['svg'] = 'image/svg+xml';
    $file_types = array_merge($file_types, $new_filetypes );
    return $file_types;
    }
add_filter('upload_mimes', 'add_file_types_to_uploads');


function filter_woocommerce_cart_item_price( $price_html, $cart_item, $cart_item_key ) {
    // Get the product object
    $product = $cart_item['data'];
    
    // Is a WC product
    if ( is_a( $product, 'WC_Product' ) ) {
        // Price without VAT
        //$price_excl_tax = (float) wc_get_price_excluding_tax( $product );
        
        // Price with VAT
        $price_incl_tax = (float) wc_get_price_including_tax( $product );
        
        // Edit price html
        $price_html = '<div style="display:none;">'.wc_price( $price_excl_tax ) . '<span class="my-class" >&nbsp;' . __( 'ex VAT', 'woocommerce' ) . '</span><br></div>';
        $price_html .= wc_price( $price_incl_tax ) . '<span class="my-class">&nbsp;' . __( '', 'woocommerce' ) . '</span>';
    }

    return $price_html;
}
add_filter( 'woocommerce_cart_item_price', 'filter_woocommerce_cart_item_price', 10, 3 );


add_action( 'woocommerce_before_cart', 'bbloomer_find_product_in_cart' );
    
function bbloomer_find_product_in_cart() {
  
   $product_id = 70954;
   
   if ( ! WC()->cart->is_empty() ) {

    $totalprice=WC()->cart->subtotal_ex_tax;
   
    if($totalprice > 500){

            // $product_id22 = 70955;
            $product_id222 = 70954;
            $product_cart_id1 = WC()->cart->generate_cart_id( $product_id22 );
            // $product_cart_id22 = WC()->cart->generate_cart_id( $product_id222 );
            $cart_item_key23 = WC()->cart->find_product_in_cart( $product_cart_id1 );
            // $cart_item_key33 = WC()->cart->find_product_in_cart( $product_cart_id22 );
            if ( $cart_item_key23 ){
                $_product = wc_get_product( $product_id22 );
                $pricet= $totalprice - $_product->get_regular_price();
              
            }
            // if ( $cart_item_key33 ){
            //     $_product = wc_get_product( $product_id222 );
            //     $pricet2= $pricet - $_product->get_regular_price();
              
            // }

            if(!empty($pricet2)){
                $PRICET=$pricet2;
            }else{
                $PRICET=$totalprice;
            }

           
            if($PRICET > 500){
               
                if(! WC()->cart->find_product_in_cart( WC()->cart->generate_cart_id( 70954 ) ) ) {
                    WC()->cart->add_to_cart(70954, 1);
                }
        
                // if(! WC()->cart->find_product_in_cart( WC()->cart->generate_cart_id( 70955 ) ) ) {
                //     WC()->cart->add_to_cart(70955, 1);
                // }

            }else{


                $priced= 500 - $PRICET;

                wc_print_notice( 'Te faltan '.$priced.' € en el carrito para llegar al pedido mínimo de 500€. Por favor, continúe añadiendo productos. ¡Gracias!', 'notice' );
                remove_action( 'woocommerce_proceed_to_checkout', 'woocommerce_button_proceed_to_checkout', 20 );
        
                // $product_id = 70955;
                $product_id2 = 70954;
                // $product_cart_id = WC()->cart->generate_cart_id( $product_id );
                $product_cart_id2 = WC()->cart->generate_cart_id( $product_id2 );
                // $cart_item_key = WC()->cart->find_product_in_cart( $product_cart_id );
                $cart_item_key3 = WC()->cart->find_product_in_cart( $product_cart_id2 );
                // if ( $cart_item_key ) WC()->cart->remove_cart_item( $cart_item_key );
                if ( $cart_item_key3 ) WC()->cart->remove_cart_item( $cart_item_key3 );
            }

      
    }
    else{
        $priced= 500 - $totalprice;

        wc_print_notice( 'Te faltan '.$priced.' € en el carrito para llegar al pedido mínimo de 500€. Por favor, continúe añadiendo productos. ¡Gracias!', 'notice' );
        remove_action( 'woocommerce_proceed_to_checkout', 'woocommerce_button_proceed_to_checkout', 20 );


            // $product_id = 70955;
            $product_id2 = 70954;
            // $product_cart_id = WC()->cart->generate_cart_id( $product_id );
            $product_cart_id2 = WC()->cart->generate_cart_id( $product_id2 );
            // $cart_item_key = WC()->cart->find_product_in_cart( $product_cart_id );
            $cart_item_key3 = WC()->cart->find_product_in_cart( $product_cart_id2 );
            // if ( $cart_item_key ) WC()->cart->remove_cart_item( $cart_item_key );
            if ( $cart_item_key3 ) WC()->cart->remove_cart_item( $cart_item_key3 );


    }

     
    //     $totalprice=WC()->cart->subtotal_ex_tax;
    //     //$totalprice=WC()->cart->total;
    //    if($totalprice > 500){

    //          $product_id22 = 70955;
    //         $product_id222 = 70954;
    //         $product_cart_id1 = WC()->cart->generate_cart_id( $product_id22 );
    //         $product_cart_id22 = WC()->cart->generate_cart_id( $product_id222 );
    //         $cart_item_key23 = WC()->cart->find_product_in_cart( $product_cart_id1 );
    //         $cart_item_key33 = WC()->cart->find_product_in_cart( $product_cart_id22 );
    //         if ( $cart_item_key23 ){
    //             $_product = wc_get_product( $product_id22 );
    //             $pricet= $totalprice - $_product->get_regular_price();
              
    //         }
    //         if ( $cart_item_key33 ){
    //             $_product = wc_get_product( $product_id222 );
    //             $pricet2= $pricet - $_product->get_regular_price();
              
    //         }

    //         if(!empty($pricet2)){
    //             $PRICET=$pricet2;
    //         }else{
    //             $PRICET=$totalprice;
    //         }

           
    //         if($PRICET > 500){
               
    //             if(! WC()->cart->find_product_in_cart( WC()->cart->generate_cart_id( 70954 ) ) ) {
    //                 WC()->cart->add_to_cart(70954, 1);
    //             }
        
    //             if(! WC()->cart->find_product_in_cart( WC()->cart->generate_cart_id( 70955 ) ) ) {
    //                 WC()->cart->add_to_cart(70955, 1);
    //             }

    //         }else{

    //             $product_id = 70955;
    //             $product_id2 = 70954;
    //             $product_cart_id = WC()->cart->generate_cart_id( $product_id );
    //             $product_cart_id2 = WC()->cart->generate_cart_id( $product_id2 );
    //             $cart_item_key = WC()->cart->find_product_in_cart( $product_cart_id );
    //             $cart_item_key3 = WC()->cart->find_product_in_cart( $product_cart_id2 );
    //             if ( $cart_item_key ) WC()->cart->remove_cart_item( $cart_item_key );
    //             if ( $cart_item_key3 ) WC()->cart->remove_cart_item( $cart_item_key3 );
    //         }
           
           

       

    //    }else{
   
    //         $product_id = 70955;
    //         $product_id2 = 70954;
    //         $product_cart_id = WC()->cart->generate_cart_id( $product_id );
    //         $product_cart_id2 = WC()->cart->generate_cart_id( $product_id2 );
    //         $cart_item_key = WC()->cart->find_product_in_cart( $product_cart_id );
    //         $cart_item_key3 = WC()->cart->find_product_in_cart( $product_cart_id2 );
    //         if ( $cart_item_key ) WC()->cart->remove_cart_item( $cart_item_key );
    //         if ( $cart_item_key3 ) WC()->cart->remove_cart_item( $cart_item_key3 );
              

    //    }



        if(! WC()->cart->find_product_in_cart( WC()->cart->generate_cart_id( 70954 ) ) ) {
            WC()->cart->add_to_cart(70954, 1);
        }
                
        // if(! WC()->cart->find_product_in_cart( WC()->cart->generate_cart_id( 70955 ) ) ) {
        //     WC()->cart->add_to_cart(70955, 1);
        // }

        if (count( WC()->cart->get_cart() ) < 2) {
            WC()->cart->empty_cart();
        }
   }
} 


/*ginni*/
function wpc_shop_url_redirect() {
    if( is_shop() ){
        wp_redirect( home_url( '/entregas-new/' ) ); // Assign custom internal page here
        exit();
    }
}
add_action( 'template_redirect', 'wpc_shop_url_redirect' );




// add_action( 'template_redirect', 'wc_custom_user_redirect' );


// /**
//  * Redirect Code 
//         exit;
//     }
// }

// add_filter( 'woocommerce_cart_item_remove_link', 'wp_kama_woocommerce_cart_item_remove_link_filter', 10, 2 );

// /**
//  * Function for `woocommerce_cart_item_remove_link` filter-hook.
//  * 
//  * @param  $sprintf       
//  * @param  $cart_item_key 
//  *
//  * @return 
//  */
// function wp_kama_woocommerce_cart_item_remove_link_filter( $sprintf, $cart_item_key ){
   
// 	return $sprintf;
// }*/
// function wc_custom_user_redirect() {
//     $url = home_url( '/entregas-new/' );
//     if(strtok($_SERVER["REQUEST_URI"], '?') == "/gastronomia/"){ //https://micecatering.com/gastronomia/
//         wp_redirect( $url ); 
//  

add_action('woocommerce_checkout_fields', 'fdev_remove_shipping_company', 10, 1);

function fdev_remove_shipping_company($checkout_fields){
    /**
    ** Add your other fields below this comment
    **/
    //unset($checkout_fields['billing']['billing_city']);
    //unset($checkout_fields['billing']['billing_postcode']);
    unset($checkout_fields['billing']['billing_country']);
    unset($checkout_fields['billing']['billing_state']);
    //unset($checkout_fields['billing']['billing_address_1']);
    unset($checkout_fields['billing']['billing_address_2']);


    
    $checkout_fields['billing']['billing_cif'] = array(
        'label' => __(' CIF', 'woocommerce'),
       // 'placeholder' => _x('Your NIF here....', 'placeholder', 'woocommerce'), 
        'required' => false, 
        'clear' => false, 
        'type' => 'text', 
        'class' => array('form-row-wide'),    
       

    );

    
    $checkout_fields['billing']['billing_event_date'] = array(
        'label'       => __('Fecha del entrega', 'woocommerce'),
        'placeholder' => _x('Fecha del entrega *', 'placeholder', 'woocommerce'),
        'required'    => true,
        'class'       => array('form-row-wide'),
        'clear'       => true,
        'priority'    => 55,  
        'type' => 'date',  
        'id'       => 'eventdate',
       
    );


    $checkout_fields['billing']['billing_event_time'] = array(
        'label'       => __('Hora de entrega', 'woocommerce'),
        'placeholder' => _x('Hora de entrega', 'placeholder', 'woocommerce'),
        'required'    => true,
        'class'       => array('form-row-wide'),
        'clear'       => true,
        'priority'    => 56,  
        'type' => 'time',           
    );
    

    $checkout_fields['billing']['billing_no_of_people'] = array(
        'label'       => __('', 'woocommerce'),
        'placeholder' => _x('Nº de personas', 'placeholder', 'woocommerce'),
        'required'    => false,
        'class'       => array('form-row-wide'),
        'clear'       => true,
        'priority'    => 56,  
        'type' => 'number',           
    );

    // $checkout_fields['billing']['billing_IFEMA'] = array(
    //     'label' => __('¿Se trata de una Feria en IFEMA?', 'woocommerce'), // Add custom field label
    //    // 'placeholder' => _x('Your NIF here....', 'placeholder', 'woocommerce'), // Add custom field placeholder
    //     'required' => true, 
    //     'clear' => false, 
    //     'type' => 'radio', 
    //     'class' => array('form-row-wide'),    
    //     'options'         => array(
    //         'si'    => 'si',
    //         'no'    => 'no',
    //     ),

    // );

    $checkout_fields['billing']['billing_IFEMA'] = array(
        'label' => __('¿Donde quieres recibir la entrega?', 'woocommerce'), // Add custom field label
       // 'placeholder' => _x('Your NIF here....', 'placeholder', 'woocommerce'), // Add custom field placeholder
        'required' => true, 
        'clear' => false, 
        'type' => 'radio', 
        'class' => array('form-row-wide'),    
        'options'         => array(
            'IFEMA'    => 'IFEMA',
            'no'    => 'otro espacio',
        ),

    );

    $checkout_fields['billing']['billing_stand_number'] = array(
        'label' => __(' Especificar Feria y nº de Stand', 'woocommerce'),
       // 'placeholder' => _x('Your NIF here....', 'placeholder', 'woocommerce'), 
        'required' => false, 
        'clear' => false, 
        'type' => 'text', 
        'class' => array('form-row-wide'),    
       

    );


    $checkout_fields['billing']['billing_contacto_number'] = array(
        'label' => __(' Contacto en el stand', 'woocommerce'),
       // 'placeholder' => _x('Your NIF here....', 'placeholder', 'woocommerce'), 
        'required' => false, 
        'clear' => false, 
        'type' => 'text', 
        'class' => array('form-row-wide'),    
       

    );

    $checkout_fields['billing']['billing_delivery_contact'] = array(
        //'label' => __(' ', 'woocommerce'),
       // 'placeholder' => _x('Your NIF here....', 'placeholder', 'woocommerce'), 
        'required' => false, 
        'clear' => false, 
        'type' => 'text', 
        'placeholder' => 'Contacto de entrega', 
        'class' => array('form-row-wide'),    
    );
   
    

    return $checkout_fields;
}
/* Product Image show in Cart Page */
/**
 * @snippet    WooCommerce Show Product Image @ Checkout Page
*/
add_action( 'woocommerce_form_field_text','reigel_custom_heading', 10, 2 );
function reigel_custom_heading( $field, $key ){
    // will only execute if the field is billing_company and we are on the checkout page...
    if ( is_checkout() && ( $key == 'billing_cif') ) {
        $field .= '<h5 class="form-row form-row-wide tittcusdtev">Datos del evento</h5>';
    }
    return $field;
}






add_filter( 'woocommerce_cart_item_name', 'ts_product_image_on_checkout', 10, 3 );

function ts_product_image_on_checkout( $name, $cart_item, $cart_item_key ) {  

    /* Return if not checkout page */
    if ( ! is_checkout() ) {
        return $name;
    }

    /* Get product object */
    $_product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );

    /* Get product thumbnail */
    $thumbnail = $_product->get_image();

    /* Add wrapper to image and add some css */
    $image = '<div class="ts-product-image" style="width: 52px; height: 45px; display: inline-block; padding-right: 7px; vertical-align: middle;">'
                . $thumbnail .
            '</div>';

    /* Prepend image to name and return it */
    return $image . $name;

}

/**Remove all possible fields
**/
add_filter( 'cfw_get_billing_checkout_fields', 'remove_checkout_fields', 100 );

function remove_checkout_fields( $fields ) {
	unset( $fields['billing_stand_number_field'] );
	return $fields;
}


add_filter( 'woocommerce_checkout_fields' , 'custom_override_checkout_fields',999 );

function custom_override_checkout_fields( $fields ) {
    $fields['billing']['billing_stand_number']['placeholder'] = 'Especificar Feria y nº de Stand';
    $fields['billing']['billing_stand_number']['label'] = '';
    $fields['order']['order_comments']['placeholder'] = 'Comentarios adicionales';
    $fields ['order']['order_comments']['label']='';
    $fields ['billing']['billing_contacto_number']['placeholder']= 'Contacto en el stand';
    $fields ['billing']['billing_contacto_number']['label']='';
    $fields ['billing']['billing_cif']['placeholder']= 'CIF';
    $fields ['billing']['billing_cif']['label']='';

    $fields ['billing']['billing_first_name']['placeholder']= 'Nombre *';
    $fields ['billing']['billing_first_name']['label']='';
    $fields ['billing']['billing_last_name']['placeholder']= 'Apellidos *';
    $fields ['billing']['billing_last_name']['label']='';
    $fields ['billing']['billing_phone']['placeholder']= 'Teléfono *';
    $fields ['billing']['billing_phone']['label']='';
    $fields ['billing']['billing_email']['placeholder']= 'Correo electrónico  *';
    $fields ['billing']['billing_email']['label']='';
    $fields ['billing']['billing_company']['placeholder']= 'Empresa *';
    $fields ['billing']['billing_company']['label']='';
    $fields ['billing']['billing_event_date']['placeholder']= 'Fecha de entrega *';
    $fields ['billing']['billing_event_time']['placeholder']= 'Hora de entrega *';
    $fields ['billing']['billing_event_time']['label']='Hora de entrega';

    $fields[ 'billing' ][ 'billing_address_1' ][ 'label' ] = '';
    $fields[ 'billing' ][ 'billing_address_1' ][ 'placeholder' ] = 'Dirección';
    $fields[ 'billing' ][ 'billing_city' ][ 'label' ] = '';
    $fields[ 'billing' ][ 'billing_city' ][ 'placeholder' ] = 'Ciudad';
    $fields[ 'billing' ][ 'billing_postcode' ][ 'label' ] = '';
    $fields[ 'billing' ][ 'billing_postcode' ][ 'placeholder' ] = 'Código postal';


    $fields[ 'billing' ][ 'billing_first_name' ][ 'priority' ] = 1;
    $fields[ 'billing' ][ 'billing_last_name' ][ 'priority' ] = 1;
	$fields[ 'billing' ][ 'billing_phone' ][ 'priority' ] = 2;
    $fields[ 'billing' ][ 'billing_email' ][ 'priority' ] = 3;
    $fields[ 'billing' ][ 'billing_company' ][ 'priority' ] = 4;
    $fields[ 'billing' ][ 'billing_cif' ][ 'priority' ] = 5;
    $fields ['billing']['billing_event_date']['priority']= 6;
    $fields ['billing']['billing_event_time']['priority']= 7;
    $fields ['billing']['billing_no_of_people']['priority']= 8;
    $fields ['billing']['billing_IFEMA']['priority']= 9;
    //$fields ['billing']['billing_stand_number']['priority']= 10;
    //$fields ['billing']['billing_contacto_number_field']['priority']= 11;
    

    $fields[ 'billing' ][ 'billing_address_1' ][ 'required' ] = false;
    $fields[ 'billing' ][ 'billing_city' ][ 'required' ] = false;
    $fields[ 'billing' ][ 'billing_postcode' ][ 'required' ] = false;
    
    
    return $fields;
}


function footercss(){
?>
<style>
    #billing_company_field .optional,#order_comments_field .optional,#billing_stand_number_field .optional,#billing_contacto_number_field .optional
    ,#billing_cif_field .optional
     {display: none;}
    .woocommerce form .form-row input.input-text, .woocommerce form .form-row textarea {
    border: 0px !important;
    border-radius: 0px !important;
    margin-bottom: 0px !important;
}
.paymenttotle {
    background: #efefef !important;
    padding:0px 15px 10px 15px;
    margin-bottom: 15px;
}
.paymenttotle:nth-child(2) {
    display: none;
}

.paymenttotle table tbody > tr:nth-child(2n+1) > th, .paymenttotle table tbody > tr:nth-child(2n+1) > td{
    background: #efefef !important;
    padding: 0px !important;
}
.paymenttotle th {
    text-align: left;
    font-size: 20px;
}
.paymenttotle td {
    text-align: right;
    font-size: 20px;
}
table.shop_table.woocommerce-checkout-review-order-table {
    margin-bottom: 0px;
}
input#billing_event_time {
    padding: 8px 16px;
  
    -webkit-appearance: textfield;
    -moz-appearance: textfield;
    min-height: 30px;
    min-width: 95% !important;
}
input#eventdate {
  
    -webkit-appearance: textfield;
    -moz-appearance: textfield;
    min-height: 30px;
    min-width: 95% !important;
}

#billing_address_1_field, #billing_postcode_field, #billing_city_field, #billing_delivery_contact_field, #billing_stand_number_field, #billing_contacto_number_field{display: none;}

.woocommerce .checkout .col2-set .col-2 .woocommerce-checkout-review-order .shop_table tfoot tr.order-total th, .woocommerce .checkout .col2-set .col-2 .woocommerce-checkout-review-order .shop_table tfoot tr.order-total td{border: 0px !important; font-size: 20px; padding: 0px !important; padding-top: 15px !important;}
.woocommerce .checkout .col2-set .col-2 .woocommerce-checkout-review-order .shop_table tfoot tr.order-total td{text-align:right !important; }
.woocommerce-checkout-review-order-table .fee span.desc-text {font-size: 12px;  display: block;  font-weight: 400;}

</style>
<script>
//     jQuery( "#eventdate" ).focusin(function() {
//    jQuery('#eventdate').attr('type', 'datetime-local');
// });

// jQuery( "#eventdate" ).focusout(function() {
//    jQuery('#eventdate').attr('type', 'text');
// });

// jQuery( "#billing_event_time" ).focusin(function() {
//    jQuery('#billing_event_time').attr('type', 'datetime-local');
// });

// jQuery( "#billing_event_time" ).focusout(function() {
//    jQuery('#billing_event_time').attr('type', 'text');
// });

    var fee = jQuery(document).find("table.shop_table.woocommerce-checkout-review-order-table tfoot tr.fee").length
    if(fee != 0){
        jQuery("input#billing_IFEMA_IFEMA").click();
        jQuery("#billing_stand_number_field, #billing_contacto_number_field").show();
        
    }

    jQuery( document ).on( "ajaxComplete", function() {
       var fee = jQuery(document).find("table.shop_table.woocommerce-checkout-review-order-table tfoot tr.fee").length
        if(fee != 0){
           
            var desc_text = jQuery(document).find("table.shop_table.woocommerce-checkout-review-order-table tfoot tr.fee .desc-text").length 
            if(desc_text == 0){
                jQuery(document).find( "table.shop_table.woocommerce-checkout-review-order-table tfoot tr.fee th" ).append( jQuery( "<span class='desc-text'>Porcentaje de canon que cobra IFEMA por facturación</span>" ) );
            }
        }
        
        // jQuery(".facetwp-template").load(location.href + " .facetwp-template");
        // setTimeout(function(){
        //     jQuery(document).find(".facetwp-template .fwpl-item img").each(function () {
                
        //         var src = jQuery(this).attr("src");
        //         var srcset = jQuery(this).attr("srcset");
        //         var url = document.location.origin;
        //         var newsrc = src.split(url);
        //         console.log(newsrc[1]);
        //         if(newsrc[0] != ''){
        //             var newsrc = url +''+ newsrc[1];
        //             jQuery(this).attr('src', newsrc);
        //             jQuery(this).attr('srcset', newsrc);
        //         }
                
        //     });
        // }, 2000);
    });

    // jQuery( document ).on( "change", function() {
    //     setTimeout(function(){
    //         console.log('settings.url');
    //         jQuery(document).find(".facetwp-template .fwpl-item img").each(function () {
            
    //             var src = jQuery(this).attr("src");
    //             var srcset = jQuery(this).attr("srcset");
    //             var url = document.location.origin;
    //             var newsrc = src.split(url);
    //             console.log(newsrc[1]);
    //             if(newsrc[0] != ''){
    //                 var newsrc = url +''+ newsrc[1];
    //                 jQuery(this).attr('src', newsrc);
    //                 jQuery(this).attr('srcset', newsrc);
    //             }
                
    //         });
    //     }, 3000);
      
    // });

    // jQuery( document ).on("click", ".facetwp-facet-pagination a.facetwp-page", function() {
    //     setTimeout(function(){
    //         console.log('settings.url');
    //         jQuery(document).find(".facetwp-template .fwpl-item img").each(function () {
            
    //             var src = jQuery(this).attr("src");
    //             var srcset = jQuery(this).attr("srcset");
    //             var url = document.location.origin;
    //             var newsrc = src.split(url);
    //             console.log(newsrc[1]);
    //             if(newsrc[0] != ''){
    //                 var newsrc = url +''+ newsrc[1];
    //                 jQuery(this).attr('src', newsrc);
    //                 jQuery(this).attr('srcset', newsrc);
    //             }
                
    //         });
    //     }, 3000);
      
    // });
</script>

<?php
}
add_action('wp_footer','footercss');





// jQuery - Ajax script
add_action( 'wp_footer', 'checkout_shipping_packing_script' );
function checkout_shipping_packing_script() {

    // Only checkout page
    if ( is_checkout() && ! is_wc_endpoint_url() ) :

    WC()->session->__unset('billing_IFEMA');
    ?>
    <script type="text/javascript">
    jQuery( function($){
        $('form.checkout').on('change', '#billing_IFEMA_field input ', function(){
            var p = jQuery(".input-radio:checked").val();
            //console.log("test" + p);

            if(p == 'IFEMA'){
                jQuery("#billing_stand_number_field, #billing_contacto_number_field").show();
                jQuery("#billing_address_1_field, #billing_postcode_field, #billing_city_field, #billing_delivery_contact_field").hide();
            }else{
                jQuery("#billing_address_1_field, #billing_postcode_field, #billing_city_field, #billing_delivery_contact_field").show();
                jQuery("#billing_stand_number_field, #billing_contacto_number_field").hide();
            }
            
            $.ajax({
                type: 'POST',
                url: wc_checkout_params.ajax_url,
                data: {
                    'action': 'woo_get_ajax_data',
                    'packing': p,
                },
                success: function (result) {
                    $('body').trigger('update_checkout');
                    console.log('response: '+result); // just for testing | TO BE REMOVED
                    // jQuery(document).find( "table.shop_table.woocommerce-checkout-review-order-table tfoot tr.fee th" ).append( jQuery( "<span class='desc-text'>Porcentaje de canon que cobra IFEMA por facturación</span>" ) );
                },
                error: function(error){
                    console.log(error); // just for testing | TO BE REMOVED
                }
            });
        });
    });
    </script>
    <?php
    endif;
}

// Php Ajax (Receiving request and saving to WC session)
add_action( 'wp_ajax_woo_get_ajax_data', 'woo_get_ajax_data' );
add_action( 'wp_ajax_nopriv_woo_get_ajax_data', 'woo_get_ajax_data' );
function woo_get_ajax_data() {
    if ( isset($_POST['packing']) ){
        $packing = sanitize_key( $_POST['packing'] );
        WC()->session->set('billing_IFEMA', $packing );
        //echo $subtotal = WC()->cart->subtotal_ex_tax;
        echo json_encode( $packing );
    }
    die(); // Alway at the end (to avoid server error 500)
}

// Add a custom dynamic packaging fee
add_action( 'woocommerce_cart_calculate_fees', 'add_packaging_fee', 20, 1 );
function add_packaging_fee( $cart ) {
    if ( is_admin() && ! defined( 'DOING_AJAX' ) )
        return;

    $domain      = "woocommerce";
    $packing_fee = WC()->session->get( 'billing_IFEMA' ); // Dynamic packing fee

    $subtotal = WC()->cart->subtotal_ex_tax;
    $IFEMA_fee = ($subtotal * 17.85)/100;

    if ( $packing_fee === 'ifema' ) {

        $label = esc_html("Canon IFEMA 17.85%");
        $cost  = $IFEMA_fee;
        if ( isset($cost) )
        $cart->add_fee( $label, $cost );

    }

    // if ( isset($cost) )
    //     $cart->add_fee( $label, $cost );
}

/**
 * Redirect user to a page on email domain after login
 * Date: 28-07-2023 7-22pm
*/
function redirect_to_email_role_page() {
    $user = wp_get_current_user();
    $roles = ( array ) $user->roles;
    $user_data = $user->data;
    $user_email = $user_data->user_email;
    $email_domain = substr(strrchr($user_email, "@"), 1);
        
    if (!empty($roles[0]) && ($roles[0] != 'administrator') ) {
        if($email_domain == 'amexgbt' || $email_domain == 'amexgbt.com' || $email_domain == 'gbtspain.com' || $email_domain == 'gbtspain' ){
            wp_redirect('https://micecatering.com/espacios-madrid-amex/');
            exit();
        } else if ($email_domain == 'viajeseci' || $email_domain == 'viajeseci.es') {
            wp_redirect('https://micecatering.com/espacios-madrid-eci/');
            exit();
        } 
    }
}
add_action( 'wp_login', 'redirect_to_email_role_page', 10, 1);

add_action( 'template_redirect', 'redirect_user_has_not_login', 10 );
function redirect_user_has_not_login() {
    if ((is_page( 'espacios-madrid-amex') || is_page( 'espacios-madrid-eci' )) && !is_user_logged_in()) {
        wp_redirect('https://micecatering.com/zona-empresa/');
        exit();
    }
}

add_action( 'um_registration_after_auto_login', 'my_registration_after_auto_login', 10, 1 );
function my_registration_after_auto_login( $user_id ) {
    $user_info = get_userdata($user_id);
    $user_email = $user_info->user_email;
    $email_domain = substr(strrchr($user_email, "@"), 1);
    $email_domain_array = preg_split('/(?=\.[^.]+$)/', $email_domain);
    if (isset($email_domain_array[0]) && $email_domain_array[0]) {
        $email_domain = $email_domain_array[0];
    }
    if ($email_domain == 'viajeseci' || $email_domain == 'viajeseci.es') {
        $u = new WP_User( $user_id );
        $u->set_role( 'um_eci' );
        wp_safe_redirect( urldecode("https://micecatering.com/espacios-madrid-eci/") );
        exit;
    } else if ($email_domain == 'amexgbt' || $email_domain == 'amexgbt.com' || $email_domain == 'gbtspain.com' || $email_domain == 'gbtspain' ) {
        wp_safe_redirect( urldecode("https://micecatering.com/espacios-madrid-amex/") );
        exit;
    }
}
 

// //On login check for user login to allowed list or not.
add_action('um_submit_form_errors_hook_','mice_user_validate', 10, 1);
function mice_user_validate( $args ) {	
    
    $user_email = $args[ 'user_email' ];
    $email_domain = substr( strrchr( $user_email, "@" ), 1 );
    $email_domain_array = preg_split( '/(?=\.[^.]+$)/', $email_domain );
    if( isset( $email_domain_array[0] ) && $email_domain_array[0] ){
        $email_domain = $email_domain_array[0];
    }

    $allowedEmailDomain = ['amexgbt', 'amexgbt.com', 'gbtspain', 'gbtspain.com', 'viajeseci', 'viajeseci.es'];

    if( !in_array( $email_domain, $allowedEmailDomain ) )
    {
        UM()->form()->add_error( 'user_email', 'No tienes permiso para acceder.' );
        return;
    }else{
        return;
    }
}

add_action( 'um_submit_form_errors_hook_login', 'um_071621_login_for_specific_role', 10 );
function um_071621_login_for_specific_role( $args ){
    
	if ( isset( $args['user_email'] ) && $args['user_email'] == '' ) {
        return;
    }
    $user_email = $args['user_email'];
    $email_domain = substr( strrchr( $user_email, "@" ), 1 );
    $email_domain_array = preg_split( '/(?=\.[^.]+$)/', $email_domain );
    if( isset( $email_domain_array[0] ) && $email_domain_array[0] ){
        $email_domain = $email_domain_array[0];
    }

    $allowedEmailDomain = ['amexgbt', 'amexgbt.com', 'gbtspain', 'gbtspain.com', 'viajeseci', 'viajeseci.es'];

    if( !in_array( $email_domain, $allowedEmailDomain ) )
    {
        UM()->form()->add_error( 'user_email', 'No tienes permiso para acceder.' );
        return;
    }else{
        return;
    }
}
/*
function preserve_random_order( $orderby ) {
  $seed = floor( time() / 10800 ); // randomize every 3 hours
  $orderby = str_replace( 'RAND()', "RAND({$seed})", $orderby );
  return $orderby;
}
add_filter( 'posts_orderby', 'preserve_random_order' );
*/