<?php
/**
 * The template for displaying comments
 *
 * The area of the page that contains both current comments
 * and the comment form.
 *
 * @package WordPress
 * @subpackage Twenty_Fifteen
 * @since Twenty Fifteen 1.0
 */

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if (post_password_required()) {
	return;
}
$commenter = wp_get_current_commenter();
$req = get_option('require_name_email');
$aria_req = $req ? " aria-required='true'" : '';

?>

<div id="comments">
	<div id="write-comment">
		<div class="section-header flex">
			<p id="add-comment-title" class="h1 has-medium-font-size"><?= get_comments_number() ?> Comments</p>
		</div>
		<div class="grid form-grid">
			<div class="grid-item large--three-fifths push--large--one-fifth">
				<?php
				$fields = array(
					'author' =>
						'<div class="user-col">' .
						'<p class="comment-form-author">' .
						'<input placeholder="Name*: " id="author" name="author" type="text" value="' . esc_attr($commenter['comment_author']) .
						'" size="30"' . $aria_req . ' required /></p></div>',

					'email' =>
						'<div class="email-col">' .
						'<p class="comment-form-email">' .
						'<input placeholder="Email*:" id="email" name="email" type="text" value="' . esc_attr($commenter['comment_author_email']) .
						'" size="30"' . $aria_req . ' required /></p></div>',
				);

				$args = array(
					'id_form' => 'comment-form',
					'class_form' => 'comment-form',
					'id_submit' => 'submit',
					'class_submit' => 'btn btn-primary',
					'name_submit' => 'submit',
					'title_reply' => __(''),
					'comment_notes_before' => '',
					'title_reply_to' => __(''),
					'label_submit' => __('Submit'),
					'format' => 'xhtml',
					'fields' => $fields,
					'comment_field' => '<div class="cmt-col"><p class="comment-form-comment"><textarea style="resize: none;" placeholder="Add Comment" id="comment" name="comment" aria-required="true">' .
						'</textarea></p></div>',
					'submit_field' => '<div class="form-submit flex">
						<div class="error-section"></div>
						<div class="submit-action flex">
							<a rel="nofollow" href="#" class="btn btn-secondary cancle-btn">Cancel</a>
							%1$s %2$s
						</div>
					</div>',
				);

				comment_form($args);
				?>
			</div>
		</div>
	</div>
	<?php if (comments_open() && get_comments_number() > 0 && post_type_supports(get_post_type(), 'comments')): ?>
	<ul class="commentlist">
		<?php wp_list_comments('type=comment&callback=mytheme_comment'); ?>
	</ul>
	<?php endif;?>
	<?php if (comments_open() && get_comments_number() > 2 && post_type_supports(get_post_type(), 'comments')): ?>
		<div class="seeAll">
			<a href="#" id="seeCmt" data-post-id="<?php echo get_the_ID(); ?>" data-page="2">See all comments</a>
		</div>
	<?php endif; ?>

	<?php
	if (!comments_open() && get_comments_number() && post_type_supports(get_post_type(), 'comments')):
		?>
		<p class="no-comments"><?php _e('Bình luận đã đóng.', 'twentyfifteen'); ?></p>
	<?php endif; ?>

</div><!-- .comments-area -->