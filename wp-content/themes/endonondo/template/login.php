<?php 
/* Template Name: Login */
if(is_user_logged_in()) wp_redirect('/');
$pageid = get_the_ID();
if(isset($_POST['login_user']) && $_POST['login_user'] != '') {
	$user_c = get_user_by('login',$_POST['login_user']);
	$user_role = $user_c->roles;
	if($user_role[0] == 'customer') {
		$user_active = get_field('user_active','user_'.$user_c->ID);
		if($user_active == true) {
			$creds = array();
			$creds['user_login'] = $_POST['login_user'];
			$creds['user_password'] = $_POST['login_pass'];
			$creds['remember'] = true;
			$user = wp_signon( $creds, false );
			if ( is_wp_error($user) ) $err = $user->get_error_message();
			else wp_redirect('/my-profile');
		} else {
			$err = 'Your account has not confirmed email, please check your email again to confirm or contact the administrator.';
		}
	} else {
		$creds = array();
		$creds['user_login'] = $_POST['login_user'];
		$creds['user_password'] = $_POST['login_pass'];
		$creds['remember'] = true;
		$user = wp_signon( $creds, true );
		if ( is_wp_error($user) ) $err = $user->get_error_message();
		else wp_redirect('/wp-admin');
	}
	
}
get_header(); 
the_post();
?>
<main id="login-page">
    <div class="login-box list-flex">
		<div class="login-left login-left-new">
			<div class="login-form">
				<h1 class="title">Log In</h1>
				<div id="message" class="message-err">
					<?php
						if(! empty($err) ) :
							echo ''.$err.'';
						endif;
					?>
				</div>
				<form id="formLogin" action="" method="post">
					<p class="login-username">
						<label for="user_login">Email<span>*</span></label>
						<input type="text" name="login_user" id="user_login" autocomplete="username" class="input-it input" value="" size="20">
					</p>
					<p class="login-password">
						<label for="user_pass">Password<span>*</span></label>
						<span class="password-note text-center"><a href="/forgot-password">Forgot password</a></span>
						<input type="password" name="login_pass" id="user_password" autocomplete="current-password" spellcheck="false" class="input-it input" value="" size="20">
						<span class="pass-hide" id="togglePassword"></span>
					</p>
					<div class="cf-turnstile" data-sitekey="0x4AAAAAAA36SXtFLiwbt_yM"></div>
					<p class="login-submit">
						<input type="submit" name="wp-submit" id="wp-submit" class="button button-primary" value="Log In">
						<input type="hidden" name="redirect_to" value="/login">
					</p>
				</form>
				<p class="note">No account? <a href="/register/">Create an Account</a></p>
				<div class="login-or">
					<span>or</span>
				</div>
				<div class="login-other">
					<?php echo do_shortcode('[nextend_social_login]'); ?>
				</div>
				<div class="login-privacy"><?php  echo get_field('terms_of_use','option'); ?></div>
			</div>
		</div>
		<div class="login-right">
			<img src="<?php echo get_template_directory_uri(); ?>/assets/images/account/login1.png" />
		</div>
	</div>
</main>
<?php get_footer(); ?>
<script src="<?php echo get_template_directory_uri(); ?>/assets/js/jquery.validate.min.js"></script>
<script src="https://challenges.cloudflare.com/turnstile/v0/api.js" defer></script>
<script>
	jQuery(function($){
		$("#formLogin").validate({
		  rules: {
		    login_user: "required",
		    login_pass: "required"
		  }
		});
		 $('#user_pass').after('<div class="pass-hide" id="togglePassword"></div>');
		$('#togglePassword').click(function(){
			var passwordInput = $('#user_password');
			if (passwordInput.attr('type') === 'password') {
			  passwordInput.attr('type', 'text');
			  $(this).addClass('active');
			} else {
			  passwordInput.attr('type', 'password'); 
			  $(this).removeClass('active');
			}
		 });
	})
</script>