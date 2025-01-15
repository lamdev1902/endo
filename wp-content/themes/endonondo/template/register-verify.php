<?php 
/* Template Name: Register verify */
if(is_user_logged_in()) wp_redirect('/');
$ucode = $_GET['uco'];
if(isset($ucode) && $ucode != '') {
	$ulogin = get_users(array(
	    'meta_key' => 'user_active_code',
	    'meta_value' => $ucode
	));
	if($ulogin) {
		//update_field('field_676fcae4cccba', true , 'user_'.$ulogin[0]->data->ID);
	} else {
		wp_redirect('/');
	}
} else {
	wp_redirect('/');
}
$pageid = get_the_ID();
get_header(); 
the_post();
?>

<main id="login-page">
    <div class="login-box list-flex flex-center">
		<div class="login-left">
			<div class="login-form">
				<h1 class="title">Your Email Confirmed</h1>
				<div class="pass-code pass-code-first">
					<a href="/login" class="submit-it">Return to login</a>
				</div>
			</div>
		</div>
		<div class="login-right">
			<img src="<?php echo get_template_directory_uri(); ?>/assets/images/account/pass.png" />
		</div>
	</div>
</main>
<?php get_footer(); ?>