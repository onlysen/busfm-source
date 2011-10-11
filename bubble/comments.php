<?php // Do not delete these lines
	if (isset($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
		die ('Please do not load this page directly. Thanks!');

	if (!empty($post->post_password)) { // if there's a password
		if ($_COOKIE['wp-postpass_' . COOKIEHASH] != $post->post_password) {  // and it doesn't match the cookie
			?>

			<p class="nocomments"><?php _e('This post is password protected. Enter the password to view comments.', 'kubrick'); ?></p>

			<?php
			return;
		}
	}

	/* This variable is for alternating comment background */
	$oddcomment = 'class="alt" ';
?>

<!-- You can start editing here. -->

<div id="comments">
<?php if ($comments) : ?>
<p></p>
	<ol class="commentlist">

	<?php foreach ($comments as $comment) : ?>
        
		<li <?php echo $oddcomment; ?>id="comment-<?php comment_ID() ?>">
			<?php echo get_avatar( $comment, 32 ); ?>	
			<span class="commentdate"><?php printf(__('%1$s / %2$s', 'kubrick'), get_comment_date(__('M jS, Y')), get_comment_time()); ?></span>
			<cite><?php if(function_exists("yus_reply")) yus_reply(); ?><?php printf(__('%s Says:'), get_comment_author_link()); ?> <?php edit_comment_link(__('edit'),'&nbsp;&nbsp;',''); ?></cite>
			<?php if ($comment->comment_approved == '0') : ?>
			<em><?php _e('Your comment is awaiting moderation.'); ?></em>
			<?php endif; ?>


			<?php comment_text() ?>

		</li>

	<?php
		/* Changes every other comment to a different class */
		$oddcomment = ( empty( $oddcomment ) ) ? 'class="alt" ' : '';
	?>

	<?php endforeach; /* end for each comment */ ?>

	</ol>

 <?php else : // this is displayed if there are no comments so far ?>

	<?php if ('open' == $post->comment_status) : ?>
		<!-- If comments are open, but there are no comments. -->

	 <?php else : // comments are closed ?>
		<!-- If comments are closed. -->


	<?php endif; ?>
<?php endif; ?>


<?php if ('open' == $post->comment_status) : ?>
<h3><?php _e('Leave a Reply'); ?></h3>

<?php if ( get_option('comment_registration') && !$user_ID ) : ?>
<p><?php printf(__('You must be <a href="%s">logged in</a> to post a comment.'), get_option('siteurl') . '/wp-login.php?redirect_to=' . urlencode(get_permalink())); ?></p>
<?php else : ?>

<div id="commentform">
<?php if ('open' == $post->comment_status) : ?>
	<?php if ( get_option('comment_registration') && !$user_ID ) : ?>
	<p>You must be <a href="<?php echo get_option('siteurl'); ?>/wp-login.php?redirect_to=<?php the_permalink(); ?>">logged in</a> to post a comment.</p>

<?php else : ?>

<form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post">

	<?php if ( $user_ID ) : ?>
	<p>Logged in as <a href="<?php echo get_option('siteurl'); ?>/wp-admin/profile.php"><?php echo $user_identity; ?></a>. <a href="<?php echo get_option('siteurl'); ?>/wp-login.php?action=logout" title="Log out of this account">Logout &raquo;</a></p>

	<?php else : ?>

	<p><input type="text" name="author" id="author" value="<?php echo $comment_author; ?>" size="22" tabindex="1" /><label for="author"><small>Name <?php if ($req) echo "(required)"; ?></small></label></p>

	<p><input type="text" name="email" id="email" value="<?php echo $comment_author_email; ?>" size="22" tabindex="2" /><label for="email"><small>Mail (will not be published) <?php if ($req) echo "(required)"; ?></small></label></p>

	<p><input type="text" name="url" id="url" value="<?php echo $comment_author_url; ?>" size="22" tabindex="3" /><label for="url"><small>Website</small></label></p>

	<?php endif; ?>

	<p><textarea name="comment" id="commentbox" cols="100%" rows="10" tabindex="4"></textarea></p>

	<p><input name="submit" type="submit" id="submit" tabindex="5" value="Submit" /> 
	<input type="hidden" name="comment_post_ID" value="<?php echo $id; ?>" /></p>
	
	<?php do_action('comment_form', $post->ID); ?>
        <?php douban_connect(); ?>
</form>

<?php endif; // if registration required and not logged in ?>

<?php endif; // if you delete this the sky will fall on your head ?>

</div>
<?php endif; // If registration required and not logged in ?>

<?php endif; // if you delete this the sky will fall on your head ?>


	<?php if ('open' == $post->comment_status) : ?>
		<!-- If comments are open, but there are no comments. -->

	 <?php else : // comments are closed ?>
		<!-- If comments are closed. -->
		<p class="nocomments"><?php _e('<h3>Sorry, the comment form is closed at this time</h3>', 'kubrick'); ?></p>
<p><input name="submit" type="submit" id="submit" tabindex="5" value="Submit" />
	<?php endif; ?>

</div>