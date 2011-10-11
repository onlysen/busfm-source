<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head profile="http://gmpg.org/xfn/11">

	<title><?php if (is_home()) echo("落网"); else { wp_title(''); echo("@落网音乐电台");} ?></title>

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

</ul>
</div>

<div id="container">

	<?php if(have_posts()) : ?><?php while(have_posts()) : the_post(); ?>

		<div class="post" id="post-<?php the_ID(); ?>">

			<h2><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h2>

			<p class="post-info">            
            </p>	 

			
			<div class="zhuti"><?php the_content(); ?></div>
                        

			<div class="entry">
		<?php link_pages('<p><strong>Pages:</strong> ', '</p>', 'number'); ?>

				<div class="entry">
				<p class="postmetadata">
                                <iframe src="http://open.qzone.qq.com/like?url=http%3A%2F%2Fuser.qzone.qq.com%2F640022414&type=button_num&width=400&height=30" allowtransparency="true" scrolling="no" border="0" frameborder="0" style="width:400px;height:30px;border:none;overflow:hidden;"></iframe><br />

			</div>

		</div>			
			<div class="comments-template">
				<?php comments_template(); ?>
			</div>

		</div>

	<?php endwhile; ?>

		<div class="navigation">
			<?php previous_post_link('« %link') ?> <?php next_post_link(' %link »') ?>
		</div>

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

     <li><h2>Preface</h2>
<P><FONT color=#767869>&nbsp;&nbsp;&nbsp; 音乐是一种态度 </FONT></P>
<P><FONT color=#767869>&nbsp;&nbsp;&nbsp; 不论是作者还是聆听者 </FONT></P>
<P><FONT color=#767869>&nbsp;&nbsp;&nbsp; 它流经心灵 </FONT></P>
<P><FONT color=#767869>&nbsp;&nbsp;&nbsp; 纵然期待影响现实的力量是种奢望 </FONT></P>
<P><FONT color=#767869>&nbsp;&nbsp;&nbsp; 如果刚巧有些声音 </FONT></P>
<P><FONT color=#767869>&nbsp;&nbsp;&nbsp; 会叫你记得一段往事 </FONT></P>
<P><FONT color=#767869>&nbsp;&nbsp;&nbsp; 伤痛或是甜蜜 </FONT></P>
<P><FONT color=#767869>&nbsp;&nbsp;&nbsp; 那记忆便有了厚厚的壳 </FONT></P>
<P><FONT color=#767869>&nbsp;&nbsp;&nbsp; 恒久的 温暖有如初生</FONT> </P>
</ul>
</div>



<ul>
	

	

	<li><h2><?php _e('Recent Post'); ?></h2>
		<ul>
			<?php get_archives('postbypost', 200); ?>
		</ul>
	</li>
	



	
</ul>



<?php endif; ?>
</ul>
</div>
</div>



<?php get_footer(); ?>. 

</div></body>
</html>