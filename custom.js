jQuery(document).ready(function($){ 

if ( jQuery(".um-login").hasClass("um-err") ) {
    setTimeout(() => {
      jQuery( ".elementor-tabs-wrapper #elementor-tab-title-1492" ).trigger( "click" );
    }, 1000);
} 
//jQuery("#billing_stand_number_field label").text('Especificar Feria y nº de Stand (opcional)');
setTimeout(() => {
  // console.log("cat attached");
      jQuery('.product-category img').each(function () {
        jQuery(this).closest('ul.products li').attr("id",this.alt);
      });


    // document.querySelectorAll("#espcatp li.product-category").forEach(function(e,i){
    //   e.addEventListener("click", function(a){
    //       var sectionId = a.currentTarget.id;
    //       setTimeout(() => {
    //           var curr_sec = document.getElementsByClassName(sectionId)[0];
    //           console.log('test' +curr_sec);
    //             curr_sec.scrollIntoView({ behavior: "smooth"});
    //       }, 500);
    //   });
    // }) 


}, 1000);


jQuery( document ).on('click', '#espcatp li.product-category', function(e){
    e.preventDefault();

    var classes = jQuery(this).attr("id");
    //console.log(classes);
    if (jQuery(window).width() < 1025)
    {
      jQuery('html, body').animate({
        scrollTop: jQuery("."+classes).offset().top - 100
      }, 1000);
    }else{
      jQuery('html, body').animate({
        scrollTop: jQuery("."+classes).offset().top - 80
      }, 1000);
    }
    
});


jQuery( document.body ).on( 'updated_cart_totals', function(){
  var sum = 0;
  jQuery(".qty").each(function(){
      sum += +jQuery(this).val();
  });
  if(sum == 0){   
    window.location.reload(); 
  }
});

jQuery( document.body ).on( 'wc_cart_emptied', function(){
  var sum = 0;
  jQuery(".qty").each(function(){
      sum += +jQuery(this).val();
  });
  if(sum == 0){   
    window.location.reload(); 
  }
});

$("input#quantity").keyup(function(){
  var quanityChange = $(this).val();
  $(this).parents(".cus_product").find(".custprdbtnaddcard").attr('data-quantity', quanityChange);
});

$("input#quantity").change(function(){
  var quanityChangeArw = $(this).val();
  $(this).parents(".cus_product").find(".custprdbtnaddcard").attr('data-quantity', quanityChangeArw);
});

  // $(".custprdbtnaddcard").click(function(e){   
  // $(this).parents('.cus_product').find(".btn-ring").show();
  //   e.preventDefault();  // Prevent the click from going to the link

  //   var quantity = $(this).data('quantity');
  //   var productId= $(this).data('id');

  //   $.ajax({
  //       url: wc_add_to_cart_params.ajax_url,
  //       method: 'post',
  //       data: { 
  //           'action': 'myajax',
  //           'quantity': quantity,
  //           'productId': productId,
  //       }
  //   }).done( function (response) {
  //         if( response.error != 'undefined' && response.error ){
  //           //some kind of error processing or just redirect to link
  //           // might be a good idea to link to the single product page in case JS is disabled
  //           return true;
  //         } else {
  //           $(this).parents('.cus_product').find(".btn-ring").hide();
  //           $(".btn-ring").hide();
  //           //alert(response);
  //           //$("body .elementor-menu-cart__main").replaceWith(response);
  //           //jQuery('[data-id="quanityprd_'+productId+'"]').hide();
  //           //jQuery('#quanityprd_'+productId).hide();
  //           //jQuery('[data-id="'+productId+'"]').hide();
  //           //jQuery('#'+productId).hide();
  //           jQuery('#custproduct_'+productId).addClass("cus_active");
  //           //jQuery('#viewcartdiv_'+productId).show();
  //           //jQuery('[data-id="viewcartdiv_'+productId+'"]').show();
  //           $(document.body).trigger('wc_fragment_refresh');
  //           //window.location.href = SO_TEST_AJAX.checkout_url;
  //         }
  //   });

  // });
  /****/

  });

  jQuery( "body" ).on( "click", ".custprdbtnaddcard", function(e) {
    jQuery(this).parents('.cus_product').find(".btn-ring").show();
    e.preventDefault();  // Prevent the click from going to the link
    var quantity = jQuery(this).attr('data-quantity');

    var productId= jQuery(this).data('id');

    jQuery.ajax({
        url: wc_add_to_cart_params.ajax_url,
        method: 'post',
        data: { 
            'action': 'myajax',
            'quantity': quantity,
            'productId': productId,
        }
    }).done( function (response) {
          if( response.error != 'undefined' && response.error ){
            //some kind of error processing or just redirect to link
            // might be a good idea to link to the single product page in case JS is disabled
            return true;
          } else {
            jQuery(this).parents('.cus_product').find(".btn-ring").hide();
            jQuery(".btn-ring").hide();
            //alert(response);
            //$("body .elementor-menu-cart__main").replaceWith(response);
            //jQuery('[data-id="quanityprd_'+productId+'"]').hide();
            //jQuery('#quanityprd_'+productId).hide();
            //jQuery('[data-id="'+productId+'"]').hide();
            //jQuery('#'+productId).hide();
            jQuery('#custproduct_'+productId).addClass("cus_active");
            //jQuery('#viewcartdiv_'+productId).show();
            //jQuery('[data-id="viewcartdiv_'+productId+'"]').show();
            jQuery(document.body).trigger('wc_fragment_refresh');
            //window.location.href = SO_TEST_AJAX.checkout_url;
          }
    });

  });

  /**
 * Slider code for product category sections
 */
// jQuery(window).on('resize', function() {
  jQuery(document).ready(function(){ 
  // var viewportWidth = jQuery(window).width();

  if (jQuery(window).width() < 767) {
    jQuery("#esp_tab_2 .elementor-tabs .elementor-tabs-wrapper, #esp_tab .elementor-tabs .elementor-tabs-wrapper").slick({
      dots: false,
      centerMode: false,
      infinite: false,
      slidesToShow: 6,
      slidesToScroll: 1,
      responsive: [
          {
            breakpoint: 768,
            settings: {
              dots: true,
              centerMode: false,
              infinite: false,
              slidesToShow: 2,
              slidesToScroll: 1,
              prevArrow:"<button type='button' class='slick-prev pull-left'><i class='fa fa-arrow-left' aria-hidden='true'></i></button>",
              nextArrow:"<button type='button' class='slick-next pull-right'><i class='fa fa-arrow-right' aria-hidden='true'></i></button>",
            },
          },
      ] 
    });
  }
});


jQuery(window).scroll(function(){
  var sticky = jQuery('.headermenu-section'),
      scroll = jQuery(window).scrollTop();

  if (scroll >= 200) sticky.addClass('fixed');
  else sticky.removeClass('fixed');
});



// jQuery("#user_email-73327").change(function() { 
// 	var email = jQuery(this).val();
// 	var email1 = email.split('@')[0];
// 	var domain = email.split('@')[1];
// 	console.log(email1);
// 	console.log(domain);
// 	if (domain == 'amexgbt.com' || domain == 'amexgbt' || domain == 'gbtspain' || domain == 'gbtspain.com' || domain == 'viajeseci' || domain == 'viajeseci.es') {
// 		jQuery(".valid-email-error").hide();
// 	} else {
// 		jQuery("input#user_email-73327").after('<p class="valid-email-error" style="color: red;">No tienes permiso para acceder a esta página</p>');
// 		jQuery(this).val("");
// 		setTimeout(function() {
// 	      jQuery(".valid-email-error").hide();
// 	    }, 3000);
// 	}
// });