﻿<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head profile="http://gmpg.org/xfn/11">

	<title><?php bloginfo('name'); ?><?php wp_title(); ?></title>

	<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />	
	<meta name="generator" content="WordPress <?php bloginfo('version'); ?>" /> <!-- leave this for stats please -->

	<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
	<link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="<?php bloginfo('rss2_url'); ?>" />
	<link rel="alternate" type="text/xml" title="RSS .92" href="<?php bloginfo('rss_url'); ?>" />
	<link rel="alternate" type="application/atom+xml" title="Atom 0.3" href="<?php bloginfo('atom_url'); ?>" />
	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />

	<?php wp_get_archives('type=monthly&format=link'); ?>
	<?php //comments_popup_script(); // off by default ?>
	<?php wp_head(); ?>
</head>
<body><div id="wrapper">
<?php get_header(); ?>
<div class="menu">
<ul>
<li class="<?php echo($home_menu_class); ?>">
	<a title="Home" href="<?php echo get_settings('home'); ?>/">[ Home ]</a>
</li>
<li>
<?php wp_list_categories('depth=1&title_li=0&orderby=name&show_count=0&show_count=1'); ?>
</li>
</ul>
</div>

<div id="container">

	<?php if(have_posts()) : ?><?php while(have_posts()) : the_post(); ?>

		<div class="post" id="post-<?php the_ID(); ?>">

			<h2><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h2>

						<p class="post-info">            
            <?php the_time(__('M jS, Y','ml')) ?> <?php _e('by','ml');?> <?php the_author_posts_link() ?> </p>	 

			<div class="zhuti"><?php the_excerpt(); ?></div>
			
			
			<div class="entry">
				<p class="postmetadata">
<?php _e('Filed under&#58;'); ?> <?php the_category(', ') ?>  <?php the_tags(); ?><br />
<?php comments_popup_link('No Comments &#187;', '1 Comment &#187;', '% Comments &#187;'); ?> <?php edit_post_link('Edit', ' &#124; ', ''); ?>
				</p>

			</div>

		</div>

	<?php endwhile; ?>

	<div class="postnav"><?php if(function_exists('wp_pagenavi')){ wp_pagenavi(); } else { ?><div class="prev"><?php next_posts_link(__('« Previous Entries')) ?></div><div class="next"><?php previous_posts_link(__('Next Entries »')) ?></div> <?php } ?></div>

	<?php else : ?>

		<div class="post">
			<h2><?php _e('Not Found'); ?></h2>
		</div>

	<?php endif; ?>

</div>


<div class="sidebar">
<div id="center">
<ul>
	
    <?php if ( function_exists('dynamic_sidebar') && dynamic_sidebar() ) : else : ?>

    
</ul>
</div>



<ul>
	

	<?php wp_list_pages('depth=3&title_li=<h2>Pages</h2>'); ?>

	<li><h2><?php _e('Recent Post'); ?></h2>
		<ul>
			<?php get_archives('postbypost', 10); ?>
		</ul>
	</li>
	<li><h2><?php _e('Recent comment'); ?></h2>
	<ul class="r-c">
	<?php
global $wpdb;
$sql = "SELECT DISTINCT ID, post_title, post_password, comment_ID,
comment_post_ID, comment_author, comment_date_gmt, comment_approved,
comment_type,comment_author_url,
SUBSTRING(comment_content,1,20) AS com_excerpt
FROM $wpdb->comments
LEFT OUTER JOIN $wpdb->posts ON ($wpdb->comments.comment_post_ID =
$wpdb->posts.ID)
WHERE comment_approved = '1' AND comment_type = '' AND
post_password = ''
ORDER BY comment_date_gmt DESC
LIMIT 10";
$comments = $wpdb->get_results($sql);
$output = $pre_HTML;

foreach ($comments as $comment) {
$output .= "\n<li>".strip_tags($comment->comment_author)
.":" . " <a href=\"" . get_permalink($comment->ID) .
"#comment-" . $comment->comment_ID . "\" title=\"on " .
$comment->post_title . "\">" . strip_tags($comment->com_excerpt)
."</a></li>";
}

$output .= $post_HTML;
echo $output;?>
</ul>
</li>
	



	
</ul>


<div id="meta">
<ul>
	<li><h2><?php _e('Meta'); ?></h2>
		<ul>
			<?php wp_register(); ?>
			<li><?php wp_loginout(); ?></li>
			<?php wp_meta(); ?>
		</ul>
	</li>
<?php endif; ?>
</ul>
</div>
</div>


<?php get_footer(); ?>. 

</div></body>
</html>