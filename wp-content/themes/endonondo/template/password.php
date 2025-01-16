<?php 
/* Template Name: Password */
$pageid = get_the_ID();
get_header(); 
the_post();
?>

<main id="login-page">
    <div class="login-box list-flex">
		<div class="login-left">
			<div class="login-form">
				<h1 class="title">Forgot Password</h1>
				<div class="pass-code pass-code-first">
					<p>Enter your email to change new password</p>
				</div>
<!-- 				<h1 class="title">Verify your email and enter a new password.</h1> -->
<!-- 				<div class="pass-code">
					<p>We've sent a code to</p>
					<p>loremipsum@gmail.com <a href="#">Edit</a></p>
				</div> -->
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
				
<!-- 				<form>
					<label>Code<span>*</span></label>
					<input class="input-it" type="text" placeholder="Your Code"/>
					<div class="password-box">
						<label>Password<span>*</span></label>
						<input id="passwordInput" class="input-it" type="password"/>
						<div class="pass-hide" id="togglePassword"></div>
					</div>
					<div class="form-btn">
						<a href="#" class="form-btn-it">Cancel</a>
						<input class="submit-it" type="submit" value="Confirm and Log In"/>
					</div>
				</form> -->
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
		$('#togglePassword').click(function(){
			var passwordInput = $('#passwordInput');
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