<?php
/*
 * 设置公用模板变量
 *
 * @access  public
 *
 * @return	string
 */
function smarty_header($cache=false){
	$GLOBALS['smarty']->template_dir	=ROOT_PATH.'templates/'.$GLOBALS['config']['site_template'];
	$GLOBALS['smarty']->cache_dir		=ROOT_PATH.'temps/cache';
	$GLOBALS['smarty']->compile_dir	=ROOT_PATH.'temps/compile';
	if($cache){
		$GLOBALS['smarty']->caching=true;
	}
	$GLOBALS['smarty']->assign('language',$GLOBALS['language']);
	$GLOBALS['smarty']->assign('config',$GLOBALS['config']);
	$GLOBALS['smarty']->assign('top_menu',get_menu(0));
	$GLOBALS['smarty']->assign('bottom_menu',get_menu(1));
}
/*
 * 提示消息
 *
 * @access  public
 * @param	array	$message
 * @return	void
 */
function message($message=array()){
	$smarty=new smarty();
	$smarty->template_dir	=ROOT_PATH.'templates/'.$GLOBALS['config']['site_template'];
	$smarty->cache_dir		=ROOT_PATH.'temps/cache';
	$smarty->compile_dir	=ROOT_PATH.'temps/compile';
	$smarty->assign('language',$GLOBALS['language']);
	$smarty->assign('config',$GLOBALS['config']);
	$smarty->assign('message',$message);
	$smarty->display('message.html');
	exit;
}
function get_content_comment($channel_id=0){
	$array=array();

	if($channel_id>0){
		$res=$GLOBALS['db']->getall("SELECT * FROM ".$GLOBALS['db_prefix']."content_comment AS A LEFT JOIN ".$GLOBALS['db_prefix']."content as b on a.content_id=b.content_id AND b.channel_id=1 AND a.parent_id=0 ORDER BY a.comment_id DESC limit 0,".$GLOBALS['config']['content_index_comment_list_size']);
	}else{
		$res=$GLOBALS['db']->getall("SELECT * FROM ".$GLOBALS['db_prefix']."content_comment WHERE parent_id=0 ORDER BY comment_id DESC limit 0,".$GLOBALS['config']['content_index_comment_list_size']);
	}
	if($res){
		foreach($res as $row){
			$array[$row['comment_id']]['id']=$row['comment_id'];
			$array[$row['comment_id']]['content']=encode_comment(truncate($row['comment_content'],$GLOBALS['config']['content_index_comment_content_size']));
			$array[$row['comment_id']]['reply']=$row['comment_reply'];
			$array[$row['comment_id']]['time']=date("Y-m-d H:i:s",$row['comment_time']);
			$array[$row['comment_id']]['member_id']=$row['member_id'];
			$array[$row['comment_id']]['member_photo']=get_member_photo($row['member_id']);
			$array[$row['comment_id']]['content_id']=$row['content_id'];
			$array[$row['comment_id']]['url']=create_uri('content',array('id'=>$row['content_id']));
		}
	}
	return $array;
}
function get_hot_content($channel_id=0){
	$is_channel=$channel_id>0?'AND channel_id='.$channel_id:'';
    $sql ="SELECT * FROM ".$GLOBALS['db_prefix']."content WHERE content_state=1 ";
	$sql.=" AND channel_id in (SELECT channel_id FROM ".$GLOBALS['db_prefix']."content_channel WHERE channel_list_style!=5 ".$is_channel.")";
	$sql.=" ORDER BY content_click_count DESC limit 0,".$GLOBALS['config']['content_hot_list_size'];
    $res = $GLOBALS['db']->getall($sql);
	$array=array();
    foreach ($res AS $row){
		$array[$row['content_id']]['id']=$row['content_id'];
		$array[$row['content_id']]['title']=truncate($row['content_title'],$GLOBALS['config']['content_hot_title_size']);
		$array[$row['content_id']]['thumb']=$row['content_thumb'];
		if(empty($row['content_url'])){
			$array[$row['content_id']]['url']=create_uri('content',array('id'=>$row['content_id']));
			$array[$row['content_id']]['target']=false;
		}else{
			$array[$row['content_id']]['url']=$row['content_url'];
			$array[$row['content_id']]['target']=true;
		}
    }
    return $array;
}

function get_best_content($channel_id=0){
	$is_channel=$channel_id>0?'AND channel_id='.$channel_id:'';
    $sql ="SELECT * FROM ".$GLOBALS['db_prefix']."content WHERE content_state=1 AND  content_is_best=1";
	$sql.=" AND channel_id in (SELECT channel_id FROM ".$GLOBALS['db_prefix']."content_channel WHERE channel_list_style!=5 ".$is_channel.")";
	$sql .=" ORDER BY content_id DESC limit 0,".$GLOBALS['config']['content_best_list_size'];
    $res = $GLOBALS['db']->getall($sql);
	$array=array();
    foreach ($res AS $row){
		$array[$row['content_id']]['id']=$row['content_id'];
		$array[$row['content_id']]['title']=truncate($row['content_title'],$GLOBALS['config']['content_best_title_size']);
		$array[$row['content_id']]['thumb']=$row['content_thumb'];
		if(empty($row['content_url'])){
			$array[$row['content_id']]['url']=create_uri('content',array('id'=>$row['content_id']));
			$array[$row['content_id']]['target']=false;
		}else{
			$array[$row['content_id']]['url']=$row['content_url'];
			$array[$row['content_id']]['target']=true;
		}
    }
    return $array;
}
/*
 * 获取会员名称
 *
 * @access  public
 *
 * @return	bool
 */
function check_login(){
	if(isset($_SESSION['member_id'])&&$_SESSION['member_id']>0){
		return true;
	}else{
		return false;
	}
}
/*
 * 获取会员名称
 *
 * @access  public
 *
 * @return	string
 */
function get_member_nickname($member_id){
	if($member_id=='')return'';
	if($member_id>0){
		$row=$GLOBALS['db']->getone("SELECT member_nickname FROM ".$GLOBALS['db_prefix']."member WHERE member_id='".$member_id."'");
		$member_nickname=$row['member_nickname'];
	}else{
		$member_nickname='';
	}
	return $member_nickname;
}
/*
 * 获取会员照片
 *
 * @access  public
 *
 * @return	string
 */
function get_member_photo($member_id){
	if($member_id=='')return'';
	if($member_id>0){
		$row=$GLOBALS['db']->getone("SELECT member_photo FROM ".$GLOBALS['db_prefix']."member WHERE member_id='".$member_id."'");
		$member_photo=$row['member_photo'];
	}else{
		$member_photo='';
	}
	return $member_photo;
}
/*
 * 获取菜单列表
 *
 * @access  public
 *
 * @return	array
 */
function get_menu($mode){
	$array=array();
	$res=$GLOBALS['db']->getall("SELECT * FROM ".$GLOBALS['db_prefix']."menu WHERE menu_mode=".$mode." and menu_state=1 ORDER BY menu_order ASC,menu_id ASC");
	if($res){
		$URI=substr(strrchr($_SERVER['REQUEST_URI'],'/'),1);
		$n=1;
		foreach($res as $row){
			$array[$row['menu_id']]['id']=$row['menu_id'];
			$array[$row['menu_id']]['name']=$row['menu_name'];
			$array[$row['menu_id']]['link']=$row['menu_link'];
			$array[$row['menu_id']]['target']=$row['menu_target'];
			if(empty($URI)){
				if($n==1||$row['menu_link']=="./"){
					$array[$row['menu_id']]['active']=true;
				}
			}else{
				if($URI==$row['menu_link']){
					$array[$row['menu_id']]['active']=true;
				}
				if(substr($URI,0,7)=='content'){
					$content_id=empty($_GET['id'])?'':intval($_GET['id']);
					$content_row=$GLOBALS['db']->getone("SELECT channel_id FROM ".$GLOBALS['db_prefix']."content WHERE content_id='$content_id'");
					if($content_row){
						$rewrite=$GLOBALS['config']['site_rewrite'];
						if($rewrite=='yes'){
							preg_match('/channel\-(.*)\.html/',$row['menu_link'],$matches);
							if(!empty($matches)){
								if($matches[1]==$content_row['channel_id']){
									$array[$row['menu_id']]['active']=true;
								}
							}
						}else{
							preg_match('/channel\.php\?id\=(.*)/',$row['menu_link'],$matches);
							if(!empty($matches)){
								if($matches[1]==$content_row['channel_id']){
									$array[$row['menu_id']]['active']=true;
								}
							}
						}
					}
				}
			}
			$n++;
		}
	}
	return $array;
}
/*
 * 获取频道信息
 *
 * @access  public
 * @param	integer $member_id 会员编号
 *
 * @return	array
 */
function get_member_info($member_id){
	if(empty($member_id)){
		return array();
	}
	$row=$GLOBALS['db']->getone("SELECT * FROM ".$GLOBALS['db_prefix']."member WHERE member_id='".$member_id."'");
	$array=array();
	$array['id']			=$row['member_id'];
	$array['mail']			=$row['member_mail'];
	$array['nickname']		=$row['member_nickname'];
	$array['name']			=$row['member_name'];
	$array['sex']			=$row['member_sex'];
	$array['birthday']		=date("Y-m-d",$row['member_birthday']);
	$array['phone']			=$row['member_phone'];
	$array['photo']			=$row['member_photo'];
	$array['from']			=$row['member_from'];
	$array['other']			=$row['member_other'];
	return $array;
}

function create_uri($app,$params=''){
	if(!empty($params)){
		extract($params);
	}
	$rewrite=$GLOBALS['config']['site_rewrite'];
	$ext='html';
	if($app=='index'){
		if($rewrite=='yes'){
			$uri=$app;
		}else{
			$uri=$app.'.php';
		}
	}elseif($app=='content'){
		if($rewrite=='yes'){
			$uri=$app.'-'.$id;
		}else{
			$uri=$app.'.php?id='.$id;
		}
	}elseif($app=='channel'){
		if($rewrite=='yes'){
			$uri=$app.'-'.$id;
			if(!empty($category_id)){
				$uri.='-'.$category_id;
			}
			if(!empty($page)){
				$uri.='-p'.$page;
			}
		}else{
			$uri=$app.'.php?id='.$id;
			if(!empty($category_id)){
				$uri.='&amp;category_id='.$category_id;
			}
			if(!empty($page)){
				$uri.='&amp;page='.$page;
			}
		}
	}elseif($app=='page'){
		if($rewrite=='yes'){
			$uri=$app.'-'.$id;
		}else{
			$uri=$app.'.php?id='.$id;
		}
	}
	if($rewrite=='yes'){
		$uri.='.'.$ext;
	}
	return $uri;
}
/*
 * 分页导航
 *
 * @access  public
 * @param	string	$page_name			页面名称
 * @param	string	$page_parameters	页面参数
 * @param	integer $page_current		当前页
 * @param	integer $page_size			每页面显示各数
 * @param	integer $count				数据总数
 *
 * @return	array
 */
function pagebar($page_name,$page_parameters='',$page_current,$page_size,$count){
	$rewrite_parameters=array();
	parse_str($page_parameters);
	if($page_name=='channel.php'){
			if(!empty($id)){
				$rewrite_parameters['id']=$id;
			}
			if(!empty($category_id)){
				$rewrite_parameters['category_id']=$category_id;
			}
	}
	$page_count		=ceil($count/$page_size);
	$page_start		=$page_current-4;
	$page_end		=$page_current+4;
	if($page_current<5){
		$page_start	=1;
		$page_end	=5;
	}
	if($page_current>$page_count-4){
		$page_start	=$page_count-8;
		$page_end	=$page_count;
	}
	if($page_start<1)$page_start=1;
	if($page_end>$page_count)$page_end=$page_count;
	$html="";
	$html.="<div class=\"pagebar\">";
	$html.="<span class=\"info\">".$page_current." / ".$page_count."</span>";
	if($page_current!=1){

		if($page_name=='channel.php'){
			$rewrite_parameters['page']=1;
			$html.="<a href='".create_uri('channel',$rewrite_parameters)."'>&laquo;</a>";
		}else{
			$html.="<a href='".$page_name."?".$page_parameters."page=1'>&laquo;</a>";
		}
	}
	for($i=$page_start;$i<=$page_end;$i++){
		if($i==$page_current){
			$html.="<span class=\"current\">".$i."</span>";
		}else{
			if($page_name=='channel.php'){
				$rewrite_parameters['page']=$i;
				$html.="<a href='".create_uri('channel',$rewrite_parameters)."'>".$i."</a>";
			}else{
				$html.="<a href='".$page_name."?".$page_parameters."page=".$i."'>".$i."</a>";
			}
		}
	}
	if($page_current!=$page_count){
		if($page_name=='channel.php'){
			$rewrite_parameters['page']=$page_count;
			$html.="<a href='".create_uri('channel',$rewrite_parameters)."'>&raquo;</a>";
		}else{
			$html.="<a href='".$page_name."?".$page_parameters."page=".$page_count."'>&raquo;</a>";
		}
	}
	$html.="</div>";
	return $html;
}

/*
 * 获取投票信息
 *
 * @access  public
 * @param	integer $place_id 位置代号
 *
 * @return	array
 */
function get_vote($place_id){
	$array=array();
	$res=$GLOBALS['db']->getall("SELECT * FROM ".$GLOBALS['db_prefix']."vote WHERE vote_place=$place_id and vote_state=1 ORDER BY vote_id DESC");
	if($res){
		foreach($res as $row){
			if($row['vote_start']<=time()&&$row['vote_end']>=time()){
			$array[$row['vote_id']]['id']=$row['vote_id'];
			$array[$row['vote_id']]['title']=$row['vote_title'];
			$array[$row['vote_id']]['mode']=$row['vote_mode'];
			$array[$row['vote_id']]['items']=get_vote_items($row['vote_id']);
			}
		}
	}
	return $array;
}
/*
 * 获取投票选项
 *
 * @access  public
 * @param	integer $vote_id 投票编号
 *
 * @return	array
 */
function get_vote_items($vote_id){
	$array=array();
	$res=$GLOBALS['db']->getall("SELECT * FROM ".$GLOBALS['db_prefix']."vote_item WHERE vote_id=".$vote_id." ORDER BY vote_id ASC");
	if($res){
		foreach($res as $row){
			$array[$row['item_id']]['id']=$row['item_id'];
			$array[$row['item_id']]['title']=$row['item_title'];
			$array[$row['item_id']]['count']=$row['item_count'];
		}
	}
	return $array;
}

/*
 * 获取广告信息
 *
 * @access  public
 * @param	integer $place_id 位置代号
 *
 * @return	array
 */
function get_ad($place_id){
	$array=array();
	$res=$GLOBALS['db']->getall("SELECT * FROM ".$GLOBALS['db_prefix']."ad WHERE ad_place=$place_id and ad_state=1 ORDER BY ad_id DESC");
	if($res){
		foreach($res as $row){
			if($row['ad_start']<=time()&&$row['ad_end']>=time()){
			$array[$row['ad_id']]['id']=$row['ad_id'];
			$array[$row['ad_id']]['name']=$row['ad_name'];
			$array[$row['ad_id']]['content']=$row['ad_content'];
			}
		}
	}
	return $array;
}

/*
 * 登记在线信息
 *
 * @access  public
 * @param	string $url 访问URL
 *
 * @return	void
 */
function set_online($url){
	if($GLOBALS['db']->getcount("select * from ".$GLOBALS['db_prefix']."online where online_ip='".get_ip()."'")>0){
		$sql="update ".$GLOBALS['db_prefix']."online set online_time='".$_SERVER['REQUEST_TIME']."',online_url='".$url."',online_agent='".get_os()."/".get_bs()."' where online_ip='".get_ip()."'";
	}else{
		$sql="insert into ".$GLOBALS['db_prefix']."online(online_time,online_ip,online_url,online_agent) values('".$_SERVER['REQUEST_TIME']."','".get_ip()."','".$url."','".get_os()."/".get_bs()."');";
	}
	$GLOBALS['db']->query($sql);
	$GLOBALS['db']->delete($GLOBALS['db_prefix']."online","online_time<UNIX_TIMESTAMP(NOW())-(60*".$GLOBALS['config']['site_online_over'].")");
}

function check_permissions($permissions){
	$state=false;
	if($permissions==-1){//当权限是游客
		$state=true;
	}elseif($permissions==0){//当权限是注册用户
		if(check_login()){
			$state=true;
		}
	}elseif($permissions>0){//当权限是用户组设定的级别
		if(check_login()){
			$row=$GLOBALS['db']->getall("SELECT group_id FROM ".$GLOBALS['db_prefix']."member WHERE member_id='".$_SESSION['member_id']."'");
			if($row){
				if($row['group_id']>=$permissions){
					$state=true;
				}
			}
		}
	}
	return $state;
}
function get_category($channel_id,$category_id=0){
	$children_count= $GLOBALS['db']->getcount("SELECT * FROM ".$GLOBALS['db_prefix']."content_category WHERE parent_id='$category_id' and channel_id=$channel_id AND category_state = 1");
	if($children_count==0){
        $row=$GLOBALS['db']->getone("SELECT parent_id FROM ".$GLOBALS['db_prefix']."content_category WHERE category_id='$category_id' and channel_id=$channel_id AND category_state = 1");
		$category_id=$row['parent_id'];
	}
    if ($category_id > 0){
        $row=$GLOBALS['db']->getone("SELECT parent_id FROM ".$GLOBALS['db_prefix']."content_category WHERE category_id='$category_id' and channel_id=$channel_id AND category_state = 1");
        $parent_id=$row['parent_id'];

    }else{
        $parent_id=0;
    }
	$res = $GLOBALS['db']->getall("SELECT category_id,category_name,category_deep,parent_id,category_state FROM ".$GLOBALS['db_prefix']."content_category WHERE parent_id = '$parent_id' AND category_state = 1 AND channel_id=$channel_id ORDER BY category_sort ASC, category_id ASC");
	$array=array();
	foreach ($res AS $row){
		$array[$row['category_id']]['id']   = $row['category_id'];
		$array[$row['category_id']]['name'] = $row['category_name'];
		$array[$row['category_id']]['deep'] = $row['category_deep'];
		$array[$row['category_id']]['children'] = get_category_children($channel_id,$row['category_id']);
		$array[$row['category_id']]['url']   = create_uri("channel",array('id'=>$channel_id,'category_id'=>$row['category_id']));
	}
	return $array;
}
function get_category_children($channel_id,$category_id=0){
    $array = array();
	$res = $GLOBALS['db']->getall("SELECT category_id,category_name,category_deep,parent_id FROM ".$GLOBALS['db_prefix']."content_category WHERE parent_id = '$category_id' AND category_state = 1 AND channel_id=$channel_id ORDER BY category_sort ASC, category_id ASC");
	foreach ($res AS $row){
		$array[$row['category_id']]['id']=$row['category_id'];
		$array[$row['category_id']]['name']=$row['category_name'];
		$array[$row['category_id']]['deep']=$row['category_deep'];
		$array[$row['category_id']]['url']=create_uri("channel",array('id'=>$channel_id,'category_id'=>$row['category_id']));
	}
    return $array;
}
function here($page,$parameters=array()){
	$html='';
	if($page=='index'){
		$html.=$GLOBALS['config']['site_notice'];
	}else if($page=='page'){
		$page_id=empty($parameters['id'])?'':intval($parameters['id']);
		$row=$GLOBALS['db']->getone("SELECT page_title FROM ".$GLOBALS['db_prefix']."page WHERE page_id='$page_id'");
		if($row){
			$html.="<a href=\"./\">".$GLOBALS['language']['position_index']."</a>&nbsp;&raquo;&nbsp;".$row['page_title'];
		}
	}else if($page=='search'){
		$html.=$GLOBALS['language']['position_index']."&nbsp;&raquo;&nbsp;".$GLOBALS['language']['search_result'];
	}else if($page=='channel'){
		$channel_id=empty($parameters['id'])?'':intval($parameters['id']);
		$category_id=empty($parameters['category_id'])?'':intval($parameters['category_id']);
		$row=$GLOBALS['db']->getone("SELECT channel_id,channel_name FROM ".$GLOBALS['db_prefix']."content_channel WHERE channel_id='$channel_id'");
		if($row){
			$html.="<a href=\"./\">".$GLOBALS['language']['position_index']."</a>&nbsp;&raquo;&nbsp;<a href=\"".create_uri('channel',array('id'=>$row['channel_id']))."\">".$row['channel_name']."</a>";
		}
		$row=$GLOBALS['db']->getone("SELECT channel_id,category_id,category_name FROM ".$GLOBALS['db_prefix']."content_category WHERE category_id='$category_id'");
		if($row){
			$html.="&nbsp;&raquo;&nbsp;<a href=\"".create_uri('channel',array('id'=>$row['channel_id'],'category_id'=>$row['category_id']))."\">".$row['category_name']."</a>";
		}
	}else if($page=='content'){
		$content_id=empty($parameters['id'])?'':intval($parameters['id']);
		$content_info=get_content_info($content_id);
		$channel_id=$content_info['channel_id'];
		$category_id=$content_info['category_id'];
		$row=$GLOBALS['db']->getone("SELECT channel_id,channel_name FROM ".$GLOBALS['db_prefix']."content_channel WHERE channel_id='$channel_id'");
		if($row){
			$html.="<a href=\"./\">".$GLOBALS['language']['position_index']."</a>&nbsp;&raquo;&nbsp;<a href=\"".create_uri('channel',array('id'=>$row['channel_id']))."\">".$row['channel_name']."</a>";
		}
		$row=$GLOBALS['db']->getone("SELECT channel_id,category_id,category_name FROM ".$GLOBALS['db_prefix']."content_category WHERE category_id='$category_id'");
		if($row){
			$html.="&nbsp;&raquo;&nbsp;<a href=\"".create_uri('channel',array('id'=>$row['channel_id'],'category_id'=>$row['category_id']))."\">".$row['category_name']."</a>";
		}
	}else if($page=='member_edit'){
		$html.="<a href=\"./\">".$GLOBALS['language']['position_index']."</a>&nbsp;&raquo;&nbsp;".$GLOBALS['language']['member_edit']."";
	}else if($page=='member_content'){
		$channel_id=empty($parameters['id'])?'':intval($parameters['id']);
		$mode=empty($parameters['mode'])?'':trim($parameters['mode']);
		$row=$GLOBALS['db']->getone("SELECT channel_id,channel_name FROM ".$GLOBALS['db_prefix']."content_channel WHERE channel_id='$channel_id'");
		if($row){
			$html.="<a href=\"./\">".$GLOBALS['language']['position_index']."</a>&nbsp;&raquo;&nbsp;<a href=\"".create_uri('channel',array('id'=>$row['channel_id']))."\">".$row['channel_name']."</a>";
		}
		$html.="&nbsp;&raquo;&nbsp;";
		if($mode=='insert'){
			$html.=$GLOBALS['language']['content_add'];
		}else{
			$html.=$GLOBALS['language']['content_edit'];
		}
	}
	return $html;
}
function check_site(){
	$ec=encode_char(array(87,101,101,100));
	if($GLOBALS['config']['site_open']=='no'){
		exit($GLOBALS['config']['site_close_text']);
	}
	
} 
check_site();
?>