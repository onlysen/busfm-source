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
	$smarty->assign('channel_content',get_channel_list());
	$smarty->assign('hot_content',get_hot_content($channel_id));
	$smarty->assign('content_comment',get_content_comment($channel_id));
	$smarty->assign('best_content',get_best_content($channel_id));
	$smarty->assign('channel_category',get_category($channel_id,$category_id));
	$smarty->assign('read_permissions',check_permissions($channel_info['read_permissions']));
	$smarty->assign('write_permissions',check_permissions($channel_info['write_permissions']));
	$smarty->assign('comment_permissions',check_permissions($channel_info['comment_permissions']));
}
$smarty->display('channel.html',$cache_id);

function get_channel_list(){
	$category_id=isset($_GET['category_id'])?intval($_GET['category_id']):0;
	$best_content_list=array();
	$top_sql="select * from ".$GLOBALS['db_prefix']."content where content_state=1 and content_is_best=1 and channel_id='".$GLOBALS['channel_id']."'";
	if($category_id!=''){
		$top_sql.=" and ".create_sql_in(category_id_list($category_id,$GLOBALS['channel_id']),'category_id');
	}
	$res=$GLOBALS['db']->getall($top_sql." order by content_id desc");
	foreach($res as $row){
				$best_content_list[$row['content_id']]['id']=$row['content_id'];
				$best_content_list[$row['content_id']]['channel_id']=$row['channel_id'];
				$best_content_list[$row['content_id']]['category_id']=$row['category_id'];
				$best_content_list[$row['content_id']]['category_name']=get_category_name($row['category_id']);
				$best_content_list[$row['content_id']]['nickname']=get_member_nickname($row['member_id']);
				$best_content_list[$row['content_id']]['title']=truncate($row['content_title'],$GLOBALS['channel_info']['list_truncate']);
				$best_content_list[$row['content_id']]['time']=date("Y/m/d",$row['content_time']);
				$best_content_list[$row['content_id']]['click_count']=$row['content_click_count'];
				$best_content_list[$row['content_id']]['comment_count']=$row['content_comment_count'];
				$best_content_list[$row['content_id']]['thumb']=$row['content_thumb'];
				if(substr($row['content_thumb'],0,4)=='http'){
					$best_content_list[$row['content_id']]['thumb_http']=true;
				}else{
					$best_content_list[$row['content_id']]['thumb_http']=false;
				}
				$best_content_list[$row['content_id']]['text']=$row['content_text'];
				$best_content_list[$row['content_id']]['short_text']=truncate(strip_tags($row['content_text']),$GLOBALS['channel_info']['list_truncate']*5);
				$best_content_list[$row['content_id']]['password']=$row['content_password'];
				$best_content_list[$row['content_id']]['is_new']=date("Ymd",$row['content_time'])==date("Ymd")?true:false;
				$best_content_list[$row['content_id']]['member_id']=$row['member_id'];
				if(empty($row['content_url'])){
					$best_content_list[$row['content_id']]['url']=create_uri('content',array('id'=>$row['content_id']));
					$best_content_list[$row['content_id']]['target']=false;
				}else{
					$best_content_list[$row['content_id']]['url']=$row['content_url'];
					$best_content_list[$row['content_id']]['target']=true;
				}
				$best_content_list[$row['content_id']]['category_url']=create_uri('channel',array('id'=>$row['channel_id'],'category_id'=>$row['category_id']));

	}
	$content_list=array();
	$sql="select * from ".$GLOBALS['db_prefix']."content where content_state=1 and content_is_best=0 and channel_id='".$GLOBALS['channel_id']."'";
	if($category_id!=''){
		$sql.=" and ".create_sql_in(category_id_list($category_id,$GLOBALS['channel_id']),'category_id');
	}
	$page_size=$GLOBALS['channel_info']['list_size'];
	$page_current=isset($_GET['page'])?intval($_GET['page']):1;
	$count=$GLOBALS['db']->getcount($sql);
	$res=$GLOBALS['db']->getall($sql." order by content_id desc limit ".(($page_current-1)*$page_size).",".$page_size);
	if($count>0){
			$no=$count-(($page_current-1)*$page_size);
			foreach($res as $row){
				$content_list[$row['content_id']]['no']=$no;
				$content_list[$row['content_id']]['id']=$row['content_id'];
				$content_list[$row['content_id']]['channel_id']=$row['channel_id'];
				$content_list[$row['content_id']]['category_id']=$row['category_id'];
				$content_list[$row['content_id']]['category_name']=get_category_name($row['category_id']);
				$content_list[$row['content_id']]['nickname']=get_member_nickname($row['member_id']);
				$content_list[$row['content_id']]['title']=truncate($row['content_title'],$GLOBALS['channel_info']['list_truncate']);
				$content_list[$row['content_id']]['time']=date("Y/m/d",$row['content_time']);
				$content_list[$row['content_id']]['click_count']=$row['content_click_count'];
				$content_list[$row['content_id']]['comment_count']=$row['content_comment_count'];
				$content_list[$row['content_id']]['thumb']=$row['content_thumb'];
				if(substr($row['content_thumb'],0,4)=='http'){
					$content_list[$row['content_id']]['thumb_http']=true;
				}else{
					$content_list[$row['content_id']]['thumb_http']=false;
				}
				$content_list[$row['content_id']]['text']=$row['content_text'];
				$content_list[$row['content_id']]['short_text']=truncate(strip_tags($row['content_text']),$GLOBALS['channel_info']['list_truncate']*3);
				$content_list[$row['content_id']]['password']=$row['content_password'];
				$content_list[$row['content_id']]['is_new']=date("Ymd",$row['content_time'])==date("Ymd")?true:false;
				$content_list[$row['content_id']]['member_id']=$row['member_id'];
				$content_list[$row['content_id']]['url']=create_uri('content',array('id'=>$row['content_id']));
				if(empty($row['content_url'])){
					$content_list[$row['content_id']]['url']=create_uri('content',array('id'=>$row['content_id']));
					$content_list[$row['content_id']]['target']=false;
				}else{
					$content_list[$row['content_id']]['url']=$row['content_url'];
					$content_list[$row['content_id']]['target']=true;
				}
				$content_list[$row['content_id']]['category_url']=create_uri('channel',array('id'=>$row['channel_id'],'category_id'=>$row['category_id']));
				$no--;
			}
			$parameters="id=".$GLOBALS['channel_id']."&";
			if(!empty($GLOBALS['category_id'])){
				$parameters.="category_id=".$GLOBALS['category_id']."&";
			}
			$pagebar=pagebar("channel.php",$parameters,$page_current,$page_size,$count);
	}else{
			$pagebar="";
	}
	$GLOBALS['smarty']->assign('best_content_list',$best_content_list);
	$GLOBALS['smarty']->assign('content_list',$content_list);
	$GLOBALS['smarty']->assign('pagebar',$pagebar);
	$out=$GLOBALS['smarty']->fetch("channel_list_".$GLOBALS['channel_info']['list_style'].".html");
	return $out;
}
?>