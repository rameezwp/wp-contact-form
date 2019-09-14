<?php
/*
Plugin Name: Contact Us Plugin
Description: Renders a contact form
Plugin URI: http://google.com
Author: Team-HTML
Author URI: http://google.com
Version: 2.0
License: GPL2
Text Domain: Text Domain
Domain Path: Domain Path
*/

/**
 * Below are all the actions
 */
add_shortcode( 'our-form', 'render_our_form' );
add_action( 'wp_head', 'link_css_file' );
add_action( 'admin_head', 'save_settings_script' );
add_action( 'admin_menu', 'add_our_menu_also' );
add_action( 'wp_ajax_send_email', 'do_this_from_server' );
add_action( 'wp_ajax_save_wpform', 'saving_form_wp' );

function render_our_form(){ ?>
<form action="" class="abc-form">
<input type="hidden" name="action" value="send_email">
<input type="text" name="the_name" placeholder="Your Name">
<br>
<br>
<input type="email" name="the_email" placeholder="Your Email">
<br>
<br>
<textarea name="the_message" placeholder="Your Message"></textarea>
<br>
<br>
<p class="blabla"></p>
<input type="submit">

</form>

<?php }

function link_css_file(){ ?>
	<style>
	.abc-form {
		border: 1px solid #eee;
		padding: 20px;
		margin-bottom: 20px;
	}	
	</style>
	<script>
		jQuery(document).ready(function($) {
			$('.abc-form').submit(function(event) {
				event.preventDefault();
				$('.blabla').text('Please Wait, Sending Email');
				var data = $(this).serialize();
				// console.log(data);

				var ajaxUrl = '<?php echo admin_url( "admin-ajax.php" ); ?>';

				// Sending to server
				$.post(ajaxUrl, data, function(resp) {
					$('.blabla').text(resp);
				});
			});
			
		});
	</script>
<?php }



function do_this_from_server(){
	
	$data = get_option( 'wpcf_settings' );

	$subject = $data['email_subject'];

	$message = 'Sender: '.$_REQUEST['the_name'].'<br>';
	$message .= 'Email: '.$_REQUEST['the_email'].'<br>';
	$message .= $_REQUEST['the_message'];

	$to = $data['admin_email'];

	wp_mail( $to, $subject, $message, );

	echo "Email Sent Successfully!";

	die(0);
}

function add_our_menu_also(){
	add_menu_page( 'Contact us settings', 'Contact Form Settings', 'manage_options', 'wp-contact-form', 'render_menu_page', 'dashicons-email-alt' );
}

function render_menu_page(){
	$data = get_option( 'wpcf_settings' );
	?>
		<div class="wrap">
			<form class="wp-cform">
				<input type="hidden" name="action" value="save_wpform">
				<table class="widefat">
					<tr>
						<td>Admin Email</td>
						<td><input value="<?php echo $data['admin_email']; ?>" name="admin_email" type="email" class="regular-text"></td>
					</tr>
					<tr>
						<td>Email Subject</td>
						<td><input type="text" value="<?php echo $data['email_subject']; ?>" name="email_subject" class="regular-text"></td>
					</tr>
					<tr>
						<td colspan="2"><input class="button button-primary" type="submit" value="Save Changes"></td>
					</tr>
				</table>
			</form>
		</div>
	<?php
}

function save_settings_script(){
	?>
	<script>
		jQuery(document).ready(function($) {
			$('.wp-cform').submit(function(event) {
				event.preventDefault();
				var data = $(this).serialize();
				$.post(ajaxurl, data, function(resp) {
					alert(resp);
				});
			});
		});
	</script>
	<?php
}

function saving_form_wp(){

	update_option( 'wpcf_settings', $_REQUEST );

	echo 'Data Saved!';

	die(0);
}
?>