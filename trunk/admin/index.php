<?php
require_once'includes/global.php';
require_once'includes/front.php';
require_once(ROOT_PATH.'languages/'.$config['site_language'].'/front.php');
set_online(create_uri("index"));
$smarty=new smarty();smarty_header(true);
$cache_id = sprintf('%X', crc32(ROOT_PATH));
if (!$smarty->is_cached('index.html',$cache_id )){
	$smarty->assign('here',here('index'));
	$smarty->assign('ad',get_ad(1));
	$smarty->assign('vote',get_vote(1));
	$smarty->assign('link',get_link());
	$smarty->assign('banner',get_banner());
	$smarty->assign('hot_content',get_hot_content());
	$smarty->assign('best_content',get_best_content());
	$smarty->assign('content_comment',get_content_comment());
	$smarty->assign('channel_panel',get_channel_panel());
	$sql ="SELECT * FROM ".$GLOBALS['db_prefix']."content_channel WHERE channel_state=1 and channel_index=1 ORDER BY channel_sort ASC,channel_id ASC";
	$res = $GLOBALS['db']->getAll($sql);
	foreach ($res AS $row){
		$smarty->assign('channel_'.$row['channel_id'],get_channel_panel_array_data($row['channel_id']));
	}
}
	$smarty->display('index.html',$cache_id);


function get_channel_panel(){
    $sql ="SELECT * FROM ".$GLOBALS['db_prefix']."content_channel WHERE channel_state=1 and channel_index=1 ORDER BY channel_sort ASC,channel_id ASC";
    $res = $GLOBALS['db']->getall($sql);
	$array=array();
    foreach ($res AS $row){
		$array[$row['channel_id']]['id']=$row['channel_id'];
		$array[$row['channel_id']]['name']=$row['channel_name'];
		$array[$row['channel_id']]['content']=get_channel_panel_data($row['channel_id']);
		$array[$row['channel_id']]['url']=create_uri('channel',array('id'=>$row['channel_id']));

    }
    return $array;
}
function get_channel_panel_data($channel_id){
	$channel_info=get_channel_info($channel_id);
	$best=array();
    $sql = "SELECT * FROM ".$GLOBALS['db_prefix']."content WHERE content_state=1 and content_is_best=1 and channel_id='".$channel_id."' ORDER BY content_id DESC";
    $res = $GLOBALS['db']->getall($sql);
	if(count($res)>0){
		foreach($res as $row){
			$best[$row['content_id']]['id']=$row['content_id'];
			$best[$row['content_id']]['title']=truncate($row['content_title'],$channel_info['index_truncate']);
			$best[$row['content_id']]['text']=truncate(strip_tags($row['content_text']),40);
			$best[$row['content_id']]['thumb']=$row['content_thumb'];
			if(substr($row['content_thumb'],0,4)=='http'){
				$best[$row['content_id']]['thumb_http']=true;
			}else{
				$best[$row['content_id']]['thumb_http']=false;
			}
			$best[$row['content_id']]['time']=date("Y-m-d",$row['content_time']);
			$best[$row['content_id']]['comment_count']=$row['content_comment_count'];
			$array[$row['content_id']]['member_photo']=get_member_photo($row['member_id']);
			if(empty($row['content_url'])){
				$best[$row['content_id']]['url']=create_uri('content',array('id'=>$row['content_id']));
				$best[$row['content_id']]['target']=false;
			}else{
				$best[$row['content_id']]['url']=$row['content_url'];
				$best[$row['content_id']]['target']=true;
			}
		}
	}


    $sql = "SELECT * FROM ".$GLOBALS['db_prefix']."content WHERE content_state=1 and content_is_best=0 and channel_id='".$channel_id."' ORDER BY content_id DESC LIMIT 0,".$channel_info['index_size'];
    $res = $GLOBALS['db']->getall($sql);
	$array=array();
	if(count($res)>0){
		foreach($res as $row){
			$array[$row['content_id']]['id']=$row['content_id'];
			$array[$row['content_id']]['title']=truncate($row['content_title'],$channel_info['index_truncate']);
			$array[$row['content_id']]['text']=truncate(strip_tags($row['content_text']),40);
			$array[$row['content_id']]['thumb']=$row['content_thumb'];
			if(substr($row['content_thumb'],0,4)=='http'){
				$array[$row['content_id']]['thumb_http']=true;
			}else{
				$array[$row['content_id']]['thumb_http']=false;
			}
			$array[$row['content_id']]['time']=date("Y-m-d",$row['content_time']);
			$array[$row['content_id']]['comment_count']=$row['content_comment_count'];
			$array[$row['content_id']]['member_photo']=get_member_photo($row['member_id']);
			if(empty($row['content_url'])){
				$array[$row['content_id']]['url']=create_uri('content',array('id'=>$row['content_id']));
				$array[$row['content_id']]['target']=false;
			}else{
				$array[$row['content_id']]['url']=$row['content_url'];
				$array[$row['content_id']]['target']=true;
			}
		}
	}
	$GLOBALS['smarty']->assign('best_content_list',$best);
	$GLOBALS['smarty']->assign('content_list',$array);
	$out=$GLOBALS['smarty']->fetch("channel_index_".$channel_info['index_style'].".html");
	return $out;
}
function get_channel_panel_array_data($channel_id){
	$channel_info=get_channel_info($channel_id);
    $sql = "SELECT * FROM ".$GLOBALS['db_prefix']."content WHERE content_state=1 and channel_id='".$channel_id."' ORDER BY content_id DESC LIMIT 0,".$channel_info['index_size'];
    $res = $GLOBALS['db']->getall($sql);
	$array=array();
	if(count($res)>0){
		foreach($res as $row){
			$array[$row['content_id']]['id']=$row['content_id'];
			$array[$row['content_id']]['title']=truncate($row['content_title'],$channel_info['index_truncate']);
			$array[$row['content_id']]['text']=truncate(strip_tags($row['content_text']),40);
			$array[$row['content_id']]['thumb']=$row['content_thumb'];
			if(substr($row['content_thumb'],0,4)=='http'){
				$array[$row['content_id']]['thumb_http']=true;
			}else{
				$array[$row['content_id']]['thumb_http']=false;
			}
			$array[$row['content_id']]['time']=date("Y-m-d",$row['content_time']);
			$array[$row['content_id']]['member_photo']=get_member_photo($row['member_id']);
			if(empty($row['content_url'])){
				$array[$row['content_id']]['url']=create_uri('content',array('id'=>$row['content_id']));
				$array[$row['content_id']]['target']=false;
			}else{
				$array[$row['content_id']]['url']=$row['content_url'];
				$array[$row['content_id']]['target']=true;
			}
		}
	}
	return $array;
}
function get_link(){
	$array=array();
	$res=$GLOBALS['db']->getall("SELECT * FROM ".$GLOBALS['db_prefix']."link WHERE link_state=1 ORDER BY link_id ASC");
	if($res){
		foreach($res as $row){
			$array[$row['link_id']]['id']=$row['link_id'];
			$array[$row['link_id']]['name']=$row['link_name'];
			$array[$row['link_id']]['logo']=$row['link_logo'];
			$array[$row['link_id']]['text']=$row['link_text'];
			$array[$row['link_id']]['url']=$row['link_url'];
		}
	}
	return $array;
}

function get_banner(){
	$array=array();
	$res=$GLOBALS['db']->getall("SELECT * FROM ".$GLOBALS['db_prefix']."banner WHERE banner_state=1");
	if($res){
		$no=1;
		foreach($res as $row){
			$array[$row['banner_id']]['no']=$no;
			$array[$row['banner_id']]['name']=$row['banner_name'];
			$array[$row['banner_id']]['image']=$row['banner_image'];
			$array[$row['banner_id']]['link']=$row['banner_link'];
			$no++;
		}
	}
	return $array;
}
 
?>