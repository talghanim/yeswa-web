<?php
/**
 * Storefront engine room
 *
 * @package storefront
 */

/**
 * Assign the Storefront version to a var
 */
$theme              = wp_get_theme( 'storefront' );
$storefront_version = $theme['Version'];
//include('api/config/database.php');
//include('api/objects/user.php');
/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) ) {
	$content_width = 980; /* pixels */
}

$storefront = (object) array(
	'version'    => $storefront_version,

	/**
	 * Initialize all the things.
	 */
	'main'       => require 'inc/class-storefront.php',
	'customizer' => require 'inc/customizer/class-storefront-customizer.php',
);

require 'inc/storefront-functions.php';
require 'inc/storefront-template-hooks.php';
require 'inc/storefront-template-functions.php';

if ( class_exists( 'Jetpack' ) ) {
	$storefront->jetpack = require 'inc/jetpack/class-storefront-jetpack.php';
}

if ( storefront_is_woocommerce_activated() ) {
	$storefront->woocommerce            = require 'inc/woocommerce/class-storefront-woocommerce.php';
	$storefront->woocommerce_customizer = require 'inc/woocommerce/class-storefront-woocommerce-customizer.php';

	require 'inc/woocommerce/storefront-woocommerce-template-hooks.php';
	require 'inc/woocommerce/storefront-woocommerce-template-functions.php';
	require 'inc/woocommerce/storefront-woocommerce-functions.php';
}

if ( is_admin() ) {
	$storefront->admin = require 'inc/admin/class-storefront-admin.php';

	require 'inc/admin/class-storefront-plugin-install.php';
}

/**
 * NUX
 * Only load if wp version is 4.7.3 or above because of this issue;
 * https://core.trac.wordpress.org/ticket/39610?cversion=1&cnum_hist=2
 */
if ( version_compare( get_bloginfo( 'version' ), '4.7.3', '>=' ) && ( is_admin() || is_customize_preview() ) ) {
	require 'inc/nux/class-storefront-nux-admin.php';
	require 'inc/nux/class-storefront-nux-guided-tour.php';

	if ( defined( 'WC_VERSION' ) && version_compare( WC_VERSION, '3.0.0', '>=' ) ) {
		require 'inc/nux/class-storefront-nux-starter-content.php';
	}
}

/**
 * Note: Do not add any custom code here. Please use a custom plugin so that your customizations aren't lost during updates.
 * https://github.com/woocommerce/theme-customisations
 */


// Adds a custom rule type.
add_filter( 'acf/location/rule_types', function( $choices ){
    $choices[ __("Other",'acf') ]['wc_prod_attr'] = 'WC Product Attribute';
    return $choices;
} );

// Adds custom rule values.
add_filter( 'acf/location/rule_values/wc_prod_attr', function( $choices ){
    foreach ( wc_get_attribute_taxonomies() as $attr ) {
        $pa_name = wc_attribute_taxonomy_name( $attr->attribute_name );
        $choices[ $pa_name ] = $attr->attribute_label;
    }
    return $choices;
} );

// Matching the custom rule.
add_filter( 'acf/location/rule_match/wc_prod_attr', function( $match, $rule, $options ){
    if ( isset( $options['taxonomy'] ) ) {
        if ( '==' === $rule['operator'] ) {
            $match = $rule['value'] === $options['taxonomy'];
        } elseif ( '!=' === $rule['operator'] ) {
            $match = $rule['value'] !== $options['taxonomy'];
        }
    }
    return $match;
}, 10, 3 );

// view order API


 
/*
 * Add a image link field to the Order API response.
*/ 
function prefix_wc_rest_prepare_order_object( $response, $object, $request ) { 
	$i=0;
 	foreach ($response->data['line_items'] as $key=>$value) {
 		$product_id = $response->data['line_items'][$key]['product_id']; 
	    $product = new WC_product($product_id); 
	    $url = $product->get_image_id();
	    $product_image_url = wp_get_attachment_url( $url );
	    $response->data['line_items'][$i]['product_image_url'] = array('link' => $product_image_url);

	    $attachment_ids = $product->get_gallery_attachment_ids(); 
	    $j=0;
	    foreach( $attachment_ids as $attachment_id ) 
	        {
	          $original_image_url = wp_get_attachment_url( $attachment_id ); 
	          $response->data['line_items'][$i]['product_gallery_image_url'][$j] = array('link' => $original_image_url);
	          $j++;
	        }
 		$i++;
 	}
 	$result[id] = $response->data['id'];
 	$result[date_created] = $response->data['date_created'];
 	$result[date_modified] = $response->data['date_modified'];
 	$result[total] = $response->data['total'];
 	$result[status] = $response->data['status'];
 	$result[line_items] = $response->data['line_items'];
 	//return $response;
	return $result;
}
add_filter( 'woocommerce_rest_prepare_shop_order_object', 'prefix_wc_rest_prepare_order_object', 10, 3 );

// New order Shipped status AFTER woo 2.2
/*add_action( 'init', 'register_my_new_order_statuses' );

function register_my_new_order_statuses() {
    register_post_status( 'wc-shipped', array(
        'label'                     => _x( 'Shipped', 'Order status', 'woocommerce' ),
        'public'                    => true,
        'exclude_from_search'       => false,
        'show_in_admin_all_list'    => true,
        'show_in_admin_status_list' => true,
        'label_count'               => _n_noop( 'Shipped <span class="count">(%s)</span>', 'Shipped<span class="count">(%s)</span>', 'woocommerce' )
    ) );
}

add_filter( 'wc_order_statuses', 'my_new_wc_order_statuses' );

// Register in wc_order_statuses.
function my_new_wc_order_statuses( $order_statuses ) {
    $order_statuses['wc-shipped'] = _x( 'Shipped', 'Order status', 'woocommerce' );

    return $order_statuses;
}*/

// Display User Multiple Address Lines 

function fb_add_custom_user_profile_fields( $user ) {
	$blank_array = 0;
	for($i=1; $i<=50; $i++) { 
		$get_user_info = get_user_meta($user->ID,'title'.$i);
		if(empty($get_user_info)){ 
			$blank_array = 1;
		}

		if(!empty($get_user_info) || $blank_array == 1) {
		?>

		<h3><?php _e('Additional Address Line '.$i); ?></h3>
    
    <table class="form-table">
        <tr>
            <th>
                <label for="title<?php echo $i; ?>"><?php _e('Address line title', 'your_textdomain'); ?>
            </label></th>
            <td>
                <input type="text" name="title<?php echo $i; ?>" id="title<?php echo $i; ?>" value="<?php echo esc_attr( get_the_author_meta( 'title'.$i, $user->ID ) ); ?>" class="regular-text" /><br />
                <span class="description"><?php _e('Please enter your address line title', 'your_textdomain'); ?></span>
            </td>
        </tr>

        <tr>
            <th>
                <label for="address<?php echo $i; ?>"><?php _e('Address line 1', 'your_textdomain'); ?>
            </label></th>
            <td>
                <input type="text" name="address<?php echo $i; ?>_line_1" id="address<?php echo $i; ?>_line_1" value="<?php echo esc_attr( get_the_author_meta( 'address'.$i.'_line_1', $user->ID ) ); ?>" class="regular-text" /><br />
                <span class="description"><?php _e('Please enter your address line 1', 'your_textdomain'); ?></span>
            </td>
        </tr>

        <tr>
            <th>
                <label for="address<?php echo $i; ?>"><?php _e('Address line 2', 'your_textdomain'); ?>
            </label></th>
            <td>
                <input type="text" name="address<?php echo $i; ?>_line_2" id="address<?php echo $i; ?>_line_2" value="<?php echo esc_attr( get_the_author_meta( 'address'.$i.'_line_2', $user->ID ) ); ?>" class="regular-text" /><br />
                <span class="description"><?php _e('Please enter your address line 1', 'your_textdomain'); ?></span>
            </td>
        </tr>

        <tr>
            <th>
                <label for="postcode<?php echo $i; ?>"><?php _e('Postcode', 'your_textdomain'); ?>
            </label></th>
            <td>
                <input type="text" name="postcode<?php echo $i; ?>" id="postcode<?php echo $i; ?>" value="<?php echo esc_attr( get_the_author_meta( 'postcode'.$i, $user->ID ) ); ?>" class="regular-text" /><br />
                <span class="description"><?php _e('Please enter your postcode', 'your_textdomain'); ?></span>
            </td>
        </tr>
    </table>

<?php		if($blank_array == 1) {
				break;
			}
		}
	}
?> 
    
<?php }

function fb_save_custom_user_profile_fields( $user_id ) {
    for($i=1; $i<=50; $i++) {
	    if ( !current_user_can( 'edit_user', $user_id ) )
	        return FALSE;

	    update_usermeta( $user_id, 'title'.$i, $_POST['title'.$i] );
	    update_usermeta( $user_id, 'address'.$i.'_line_1', $_POST['address'.$i.'_line_1'] );
	    update_usermeta( $user_id, 'address'.$i.'_line_2',$_POST['address'.$i.'_line_2'] );
	    update_usermeta( $user_id, 'postcode'.$i, $_POST['postcode'.$i] );
	}
}

add_filter( 'show_user_profile', 'fb_add_custom_user_profile_fields' );
add_filter( 'edit_user_profile', 'fb_add_custom_user_profile_fields' );

add_filter( 'personal_options_update', 'fb_save_custom_user_profile_fields' );
add_filter( 'edit_user_profile_update', 'fb_save_custom_user_profile_fields' );

//* Password reset activation E-mail -> Body
add_filter('gettext', 'change_lost_password' );
function change_lost_password($translated) {
    if(strpos($_SERVER['REQUEST_URI'], 'lost-password') !== false || strpos($_SERVER['REQUEST_URI'], 'users/forgetpass.php') !== false) {
        if($translated === "Password Reset Request"){
            return 'Password Reset';
        }else if(strpos($translated, 'Someone has requested a new password for the following account on') !== false){
            //error_log($translated);
            return str_replace('Someone has requested a new password for the following account on',"If you've lost your password or wish to reset it, use the link below to get startted ",$translated);
        }else if($translated === "If you didn't make this request, just ignore this email. If you'd like to proceed:"){
            return "If you did not request a password reset, you can safely ignore this email.";
        }else if($translated === 'Click here to reset your password'){
            return 'Password Reset';
        }else if($translated === 'Thanks for reading.'){
            return 'Thank you,<br>'.get_bloginfo( 'name' ).' Team';
        }
    }
    return $translated; 
}