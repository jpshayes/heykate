/**
 * The JavaScript code you place here will be processed by esbuild, and the
 * output file will be created at `../theme/js/script.min.js` and enqueued in
 * `../theme/functions.php`.
 *
 * For esbuild documentation, please see:
 * https://esbuild.github.io/
 */


// Toggle local storage theme mode when theme-toggle checkbox is checked/unchecked
 const themeToggle = document.getElementById('theme-toggle')

 themeToggle.addEventListener('change', (event) => {
   if (event.currentTarget.checked) {
     localStorage.theme = 'dark'
     document.documentElement.classList.add('dark')
   } else {
     localStorage.theme = 'light'
     document.documentElement.classList.remove('dark')
   }
 })
 if (localStorage.getItem("theme") == "dark") {
   themeToggle.checked = true;
   document.documentElement.classList.add('dark')
 } else {
   themeToggle.checked = false;
   document.documentElement.classList.remove('dark')
 }
 
 (function($) {
   $( document ).ready(function() {
     
       // Select the node that will be observed for mutations
       // const targetNode = document.getElementById('header-cart-count');
 
       // // Options for the observer (which mutations to observe)
       // const config = { attributes: true, childList: true, subtree: true };
 
       // // Callback function to execute when mutations are observed
       // const callback = (mutationList, observer) => {
       //   for (const mutation of mutationList) {
       //    if (mutation.type === 'attributes') {
       //       $('#cart-drawer').show();
       //       console.log(`The ${mutation.attributeName} attribute was modified.`);
       //     }
       //   }
       // };
 
       // const observer = new MutationObserver(callback);
 
       // observer.observe(targetNode, config);
       
       // $('.single_add_to_cart_button').click(function(){
       //   "use strict";
       //   setTimeout(() => { //set a 1 second delay so the cart has time to populate
       //     $.post({
       //       url: woocommerce_params.ajax_url, // The AJAX URL
       //       data: {'action': 'heykate_update_mini_cart'}, // Send our PHP function
       //       success: function(response){
       //         $('#heykate-cart').html(response); // Repopulate the specific element with the new content
       //         // console.log(response)
       //       }
       //     });
       //   }, 1000)
       // })

       //Product added to cart dialog box via ajax
       // create a variable to hold a message ID. This ID will be assigned to each individual message displayed
       var message_id = 0;

       $('.single_add_to_cart_button').on('click', function (event) {
         event.preventDefault();
         message_id ++;
         var pid = $(this).val();
         var thisbtn = $(this);
         $.ajax({
             url: woocommerce_params.ajax_url,
             type: 'POST',
             data: {
                 action: 'heykate_ajax_message',
                 product_id: pid,
                 message_id: message_id,
             },
             success: function(response) { 
                 $('#message').append(response);
                 thisbtn.prop('disabled', true);
                 var current = $('#message' + message_id);
                 // setTimeout(() => {
                   $(current).delay(5000).fadeOut('slow', function(){
                     $(this).remove();
                     thisbtn.prop('disabled', false);
                   })
                 // }, 4000)
             },
             error: function(error) {
                 console.log(error);
             }
         });
     });
 
   
     //Hide show profile/mobile menus
     $("#menu-toggle").click(function(){
       $("#mobile-menu").toggleClass("hidden");
     });
     $("#profile-toggle").click(function(){
       $("#profile-menu").toggleClass("hidden");
     });
 
     //add .active class to active <a>'s when path matches
     var current = location.pathname;
     $('.get-active a').each(function(){
         var $this = $(this);
         // if the current path is like this link, make it active
         if($this.attr('href').indexOf(current) !== -1){
             $this.addClass('active');
             $this.attr('aria-current' , 'page');
         }
     });
 
     //add html5 spec for required fields to WC inputs
     var fieldreq = $('form label span.required');
     fieldreq.each( function(){
       var label = $(this).parent('label');
       label.next('input').attr('required', true);
     })
 
     // if ($('#sale').length) {
     // console.log('sale is present');
     // setTimeout(() => {
 
     //     $('#sale').fadeTo( "slow" , 0.75, function() {
     //       $('#sale').click( function(){
     //         $(this).fadeOut()
     //       })
     //     });
 
     //   }, 5000)
     // }  else {
     //   console.log('sale is not present');
     // }
 
   });
 })(jQuery);
 
 