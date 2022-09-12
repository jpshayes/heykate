<?php
   /*
   Plugin Name: Hey Kate
   Plugin URI: https://resume.hayesjp.com
   description: Custom plugin for Oak Moss
   Version: 1.0
   Author: JP Hayes
   Author URI: https://resume.hayesjp.com
   License: GPL2
   */

/*
*
* Styling for this plugin requires TailWind CSS V3.0+
*
*/


/*
* Exit if accessed directly.
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/*
* Add plugin js and css assets
*/
function heykate_files() {
  $styles = [
      ['handle' => 'heykate', 'src' => 'heykate.css', 'deps' => false, 'media'=>"all"],
  ];
  for ($i = 0; $i < sizeof($styles); $i++) {
      wp_enqueue_style($styles[$i]['handle'], plugin_dir_url( __FILE__ ) . 'css/' . $styles[$i]['src'], $styles[$i]['deps'], $styles[$i]['media'] );
  }
  $scripts = [
      ['handle' => 'heykate', 'src'=>'heykate.min.js','dep'=> array( 'jquery' ),'var'=> false,'in_foot'=> true],
  ];
  for ($i=0; $i < sizeof($scripts); $i++) {
      wp_enqueue_script( $scripts[$i]['handle'], plugin_dir_url( __FILE__ ) . 'js/' . $scripts[$i]['src'], $scripts[$i]['dep'], $scripts[$i]['ver'], $scripts[$i]['in_foot'] );    
  }
}
add_action( 'wp_enqueue_scripts', 'heykate_files' );


/*
* Ajax function to update <span> count for cart items in header (see /wp-content/plugins/heykate/js/heykate.js)
*/
add_filter( 'woocommerce_add_to_cart_fragments', 'heykate_refresh_header_cart_count');
function heykate_refresh_header_cart_count($fragments){
  ob_start();
  $items_count = WC()->cart->get_cart_contents_count();
  ?>
  <span id="header-cart-count" class="<?php echo $items_count ? 'block' : 'hidden'; ?>"><?php echo $items_count ? $items_count : '0'; ?></span>
  <?php
  $fragments['#header-cart-count'] = ob_get_clean();
    return $fragments;
}

/*
* Header menu profile dropdown (see wp-content/themes/heykate/theme/template-parts/layout/header-content.php and plugin screenshots)
*/
add_action( 'loop_start', 'heykate_profile_links' );
function heykate_profile_links() {
  if ( is_user_logged_in() ) {
    $links =  [
      'Dashboard' => '/my-account',
      'Logout' => '/my-account/customer-logout'
    ];
    } else {
    $links =  [
      'Log in' => '/my-account'
    ];
  }
  return $links;
}

/*
* Header menu profile dropdown button (see wp-content/themes/heykate/theme/template-parts/layout/header-content.php and plugin screenshots)
* Use current avatar for authenticated users or default for anonymous
*/
add_action( 'loop_start', 'heykate_avatar' );
function heykate_avatar() {
  $user = wp_get_current_user();
  if ($user) {
    $path = esc_url( get_avatar_url( $user->ID ) );
  } else {
    $path = esc_url('https://www.gravatar.com/avatar/00000000000000000000000000000000?d=mp&f=y');
  }
  return $path;
}

/*
* Add ajax added to cart success message (see plugin screenshots)
*/
function heykate_ajax_message() {
  $pid = $_POST['product_id'];
  $mid = $_POST['message_id'];
  $product = wc_get_product($pid);
  $title = $product->get_title();
  $message = '<div id="message' . $mid . '" class="message-inner">
  <div class="col-span-2 TEXT-CENTER"><strong>' . $title . '</strong> has been added to your cart</div>
  <div class=""><a href="/cart" class="btn secondary lg">View Cart</a></div>
  <div class=""><a href="/checkout" class="btn primary lg">Checkout</a></div>
  </div>';
  echo $message;
      
  wp_die();
}
add_action( 'wp_ajax_heykate_ajax_message', 'heykate_ajax_message' );
add_action( 'wp_ajax_nopriv_heykate_ajax_message', 'heykate_ajax_message' );

/*
* General function to fetch cart contents
*/
function heykate_update_mini_cart() {
  echo wc_get_template( 'cart/mini-cart.php' );
  die();
}
add_filter( 'wp_ajax_nopriv_heykate_update_mini_cart', 'heykate_update_mini_cart' );
add_filter( 'wp_ajax_heykate_update_mini_cart', 'heykate_update_mini_cart' );

//custom image sizes. (see snipptes.php for url to good guide)
add_image_size( 'heykate-sm', 300, 300, FALSE );