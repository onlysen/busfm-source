<?php
require_once('includes/global.php');
require_once('includes/front.php');
require_once(ROOT_PATH.'languages/'.$config['site_language'].'/front.php');
if (empty($_GET['id'])){
	exit();
}else{
	$channel_id=intval($_GET['id']);
}
if (isset($_GET['category_id'])){
    $category_id=intval($_GET['category_id']);
}else{
	$category_id=0;
}
$channel_info=get_channel_info($channel_id);
if(!check_permissions($channel_info['read_permissions'])){
	message(array('text'=>$language['permissions_is_not_enough'],'link'=>''));
}
set_online(create_uri("channel",array('id'=>$channel_id)));
$smarty=new smarty();smarty_header($channel_info['cache']!=1?false:true);
$cache_id = sprintf('%X', crc32(md5($channel_id.($category_id>0?"-".$category_id:'').(isset($_GET['page'])?intval($_GET['page']):1))));
if (!$smarty->is_cached('channel.html',$cache_id)){
	$parameters=array();
	$parameters['id']=$channel_id;
	if($category_id>0){
		$parameters['category_id']=$category_id;
	}
	$smarty->assign('here',here('channel',$parameters));
	$smarty->assign('ad',get_ad(2));
	$smarty->assign('vote',get_vote(2));
	$smarty->assign('channel_info',$channel_info);
 
 
} 
$smarty->display('channel.html',$cache_id);
 
?>