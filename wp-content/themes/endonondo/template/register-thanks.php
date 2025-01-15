<?php 
/* Template Name: Register thanks */
if(is_user_logged_in()) wp_redirect('/');
$pageid = get_the_ID();
get_header(); 
the_post();
?>

<main id="login-page">
    <div class="login-box list-flex flex-center">
		<div class="login-left">
			<div class="login-form">
				<h1 class="title">Thank you for being our customer</h1>
				<div class="register-tks-please pass-code pass-code-first">
					<p>Please check your email for confirmation</p>
				</div>
			</div>
		</div>
		<div class="login-right">
			<img src="<?php echo get_template_directory_uri(); ?>/assets/images/account/pass.png" />
		</div>
	</div>
</main>
<?php get_footer(); ?>