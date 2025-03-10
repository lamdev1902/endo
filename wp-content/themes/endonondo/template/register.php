<?php 
/* Template Name: Register */
if(is_user_logged_in()) wp_redirect('/');
function sendMail($to, $subject, $message, $headers = "") {
    $headers .= "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= 'From: your_email@example.com' . "\r\n";
    return wp_mail($to, $subject, $message, $headers);
}
$err = ''; 
$success = ''; 
global $wpdb, $PasswordHash, $current_user, $user_ID; 
if(isset($_POST['task']) && $_POST['task'] == 'register' ) { 
	$pwd1 = $wpdb->escape(trim($_POST['pwd1']));
	$email = $wpdb->escape(trim($_POST['email']));
	$username = $wpdb->escape(trim($_POST['username']));
	if( $email == "" || $pwd1 == "" || $username == "") {
		$err = 'Please do not leave required information blank!';
	} else if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		$err = 'Invalid Email Address!';
	} else if(email_exists($email) ) {
		$err = 'Email address already exists!';
	} else {
		$userdata = array(
			'user_login'  =>  $username,
			'user_email' => $email,
			'user_pass'   =>  $pwd1,
			'role' => 'customer'
		);
		$user_id = wp_insert_user( $userdata ) ; 
		if( is_wp_error($user_id) ) {
			$err = 'Error on user creation.';
		} else {
			do_action('user_register', $user_id);
		 	$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		    $ucode = '';
		    for ($i = 0; $i < 50; $i++) {
		        $ucode .= $characters[rand(0, strlen($characters) - 1)];
		    }
			update_field('field_6789c01109ffe',$ucode,'user_'.$user_id);
			$to = $_POST['email'];
			$subject = "ICSI - Account Verification";
			$message = "<h1>Hello!</h1><p>Successfully registered an account on endomondo.com, please click on the link below to confirm your account:</p><p>https://www.endomondo.com/register-verify/?uco=".$ucode."</p>";

			if(sendMail($to, $subject, $message) == true) {
				?>
				<script>
					window.location.href = "/register-thanks";
				</script>
				<?php
			}
		}
	}
}
$pageid = get_the_ID();
get_header(); 
the_post();
?>
<main id="login-page">
    <div class="login-box list-flex">
		<div class="login-left">
			<div class="login-form">
				<h1 class="title">Create an Account</h1>
				<div class="register-account">
					<div id="message" class="message-err">
						<?php
							if(! empty($err) ) :
								echo ''.$err.'';
							endif;
							if(! empty($success) ) :
								$login_page  = home_url( '/login' );
								echo ''.$success. '<a href='.$login_page.'>Login</a>'.'';
							endif;
						?>
					</div>
					<form class="form-register" method="post" role="form">
						<label>Name<span>*</span></label>
						<input class="input-it" name="username" id="username" type="text" placeholder="Your Name"/>
						<label>Email<span>*</span></label>
						<input name="email" id="email" class="input-it" type="email" placeholder="Your Email"/>
						<div class="password-box">
							<label>Password<span>*</span></label>
							<input  name="pwd1" id="pwd1" id="passwordInput" class="input-it" type="password"/>
							<div class="pass-hide" id="togglePassword"></div>
						</div>
						<div class="cf-turnstile" data-sitekey="0x4AAAAAAA36SXtFLiwbt_yM"></div>
						<?php wp_nonce_field( 'post_nonce', 'post_nonce_field' ); ?>
						<input class="submit-it register-submit" type="submit" value="Create Account"/>
						<input type="hidden" name="task" value="register" />
					</form>
				</div>
				<div class="notificationlogin">
					<?php
						$login  = (isset($_GET['login']) ) ? $_GET['login'] : 0;
						if ( $login === "failed" ) {
								echo '<strong>ERROR:</strong>Wrong username or password!';
						} elseif ( $login === "empty" ) {
								echo '<strong>ERROR:</strong> Username and password cannot be blank.';
						} elseif ( $login === "false" ) {
								echo '<strong>ERROR:</strong> You have logged out.';
						}
					?>
				</div>
				<p class="note">Already have an account? <a href="/login/">Login</a></p>
				<div class="login-or">
					<span>or</span>
				</div>
				<div class="login-other">
					<?php echo do_shortcode('[nextend_social_login]'); ?>
					<a href="#"><img src="<?php echo get_template_directory_uri(); ?>/assets/images/account/facebook1.png" /></a>
				</div>
				<div class="login-privacy"><?php  echo get_field('terms_of_use','option'); ?></div>
			</div>
		</div>
		<div class="login-right">
			<img src="<?php echo get_template_directory_uri(); ?>/assets/images/account/account.png" />
		</div>
	</div>
</main>
<?php get_footer(); ?>
<script src="<?php echo get_template_directory_uri(); ?>/assets/js/jquery.validate.min.js"></script>
<script src="https://challenges.cloudflare.com/turnstile/v0/api.js" defer></script>
<script>
	jQuery(function($){
		$(".form-register").validate({
		  rules: {
		    username: "required",
		    email: {
		      required: true,
		      email: true
		    },
		    pwd1: "required"
		  }
		});
		$('#togglePassword').click(function(){
			var passwordInput = $('#pwd1');
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