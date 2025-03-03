<?php 
/* Template Name: Password */
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['new_password'])) {
    $user = check_password_reset_key($_GET['key'], $_GET['login']);
    if (!is_wp_error($user)) {
        reset_password($user, $_POST['new_password']);
        wp_redirect(site_url('/login'));
        exit;
    } else {
        $error = "Password change failed, please check information again";
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
				<?php if(isset($_GET['login']) && isset($_GET['key'])) { ?>
				<h1 class="title">Enter a new password.</h1>
				<form id="resetPassForm" method="post">
					<div class="password-box">
						<label>New Password<span>*</span></label>
						<input id="passwordInput" class="input-it" type="password" name="new_password" />
						<div class="pass-hide" id="togglePassword"></div>
					</div>
					<div class="password-box">
						<label>Confirm Password<span>*</span></label>
						<input id="RepasswordInput" class="input-it" type="password" name="re_new_pass" />
						<div class="pass-hide" id="togglePassword2"></div>
					</div>
					<div class="form-btn">
						<a href="#" class="form-btn-it">Cancel</a>
						<input class="submit-it" type="submit" value="Save"/>
					</div>
				</form>
				<?php } else { ?>
				<h1 class="title">Forgot Password</h1>
				<div class="pass-code pass-code-first">
					<p>Enter your email to change new password</p>
				</div>
				<div class="pass-code">
				<?php 
					if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['user_login'])) {
						$user_login = sanitize_text_field($_POST['user_login']);
						$user = get_user_by('email', $user_login);
						if ($user) {
							$result = retrieve_password($user_login);
							if (is_wp_error($result)) {
								echo '<p class="error">' . $result->get_error_message() . '</p>';
							} else {
								echo '<p class="success">Check your email for the password reset link.</p>';
							}
						} else {
							echo '<p class="error">No user found with this email.</p>';
						}
					}
					?>
				</div>
				<form id="forgot-password-form" method="post">
                    <label>Email<span>*</span></label>
                    <input class="input-it" type="email" id="user_login" name="user_login" placeholder="Your Email" required/>
                    <div class="form-btn">
                        <a href="/login" class="form-btn-it">Cancel</a>
                        <input class="submit-it" type="submit" value="Send Reset Link"/>
                    </div>
                </form>
            	<?php } ?>		
			</div>
		</div>
		<div class="login-right">
			<img src="<?php echo get_template_directory_uri(); ?>/assets/images/account/pass.png" />
		</div>
	</div>
</main>
<?php get_footer(); ?>
<script>
	jQuery(function($){
		$('.pass-hide').click(function(){
			var par = $(this).parents('.password-box');
			var passwordInput = par.find('.input-it');
			if (passwordInput.attr('type') === 'password') {
			  passwordInput.attr('type', 'text');
			  $(this).addClass('active');
			} else {
			  passwordInput.attr('type', 'password'); 
			  $(this).removeClass('active');
			}
		});
		$("#resetPassForm").validate({
		  rules: {
		    new_password: "required",
		    re_new_pass: {
		      required: true,
		      equalTo: "#passwordInput"
		    },
		    pwd1: "required"
		  }
		});
	})
</script>