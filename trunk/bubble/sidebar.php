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
