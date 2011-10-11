<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head profile="http://gmpg.org/xfn/11">

	<title><?php if ( is_single() || is_page() || is_category() || is_tag() ) { wp_title(''); } else { bloginfo('name'); } ?></title>

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
	<a title="Home" href="<?php echo get_settings('home'); ?>/">[ 首页 ]</a>
</li>
<li>
<a target="_blank" href="http://www.luoo.net/bbs/">[ 论坛 ]</a>
</li>
<li>
<a href="http://www.luoo.net/time/">[ 季节 ]</a>
</li>
<li>
<a href="http://www.luoo.net/about-luoo/">[ 关于落网 ]</a>
</li>
<li>
<a href="http://www.luoo.net/help/">[ 捐助 ]</a>
</li>
<li>
<a href="http://www.bus.fm/">[ 巴士电台 ]</a>
</li>



<li style="float:right;">
<form id="searchform" method="get" action="<?php bloginfo('home'); ?>">
	<div>
		<input type="text" name="s" id="s" size="15" style="height: 16px; padding: 2px; border: 1px solid #000;"/>
		<input type="submit" value="<?php esc_attr_e('Search'); ?>" style="background:#333;color:#999;border:1px solid #333;padding:1px;" />
	</div>
</form>
</li>
</ul>
</div>




<div id="container">

	<?php if(have_posts()) : ?><?php while(have_posts()) : the_post(); ?>

		<div class="post" id="post-<?php the_ID(); ?>">
<h2><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a> </h2>
			


			<p class="post-info">            
            </p>	 
			<?php the_content(); ?>
			<div class="zhuti">
			
			</div>

			<div class="entry">



			</div>
	
		</div>

	<?php endwhile; ?>



	<?php else : ?>

		<div class="post">
			<h2><?php _e('Not Found'); ?></h2>
		</div>

	<?php endif; ?>

</div>




<?php get_sidebar(); ?>


<?php get_footer(); ?>. 

</div></body>
</html>