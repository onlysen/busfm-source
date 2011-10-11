<?php
function load_config(){
	$config_temp=array();
	$row=$GLOBALS['db']->getone("SELECT config_value  FROM ".$GLOBALS['db_prefix']."config WHERE config_type='config'");
	$config_temp=unserialize($row['config_value']);
	return $config_temp;
}
function encode_char($ASCII){
	$s='';
	foreach($ASCII as $v){
		$s.=chr($v);
	}
	return $s;
}
function clear_cache($filename=''){
	$dirs=array();
	$dirs[] = ROOT_PATH.'/temps/cache/';
	$dirs[] = ROOT_PATH.'/temps/compile/';
	if(empty($filename)){
		foreach ($dirs AS $dir){
			$folder = @opendir($dir);
			if ($folder === false){
				continue;
			}
			while ($file = readdir($folder)){
				if ($file == '.'||$file=='..'){
					continue;
				}
				if (is_file($dir.$file)){
					 @unlink($dir . $file);
				}
			}
			closedir($folder);
		}
	}else{
		foreach ($dirs AS $dir){
			$folder = @opendir($dir);
			if ($folder === false){
				continue;
			}
			if (is_file($dir.$filename)){
				 @unlink($dir . $filename);
			}
			closedir($folder);
		}
	}
}
function category_list($parent_id=0,$channel_id){
	if(!isset($category_list)){
		$category_list=array();
	}
	$res=$GLOBALS['db']->getall("SELECT * FROM ".$GLOBALS['db_prefix']."content_category WHERE parent_id=$parent_id ORDER BY category_sort asc");
	if($res){
		foreach($res as $row){
			$category_list[$row['category_id']]['id']=$row['category_id'];
			$category_list[$row['category_id']]['name']=$row['category_name'];
			$category_list[$row['category_id']]['deep']=$row['category_deep'];
			if(category_have_child($row['category_id'])){
				$category_list[$row['category_id']]['children']=category_list($row['category_id'],$channel_id);
			}
		}
	}
	return $category_list;
}
function category_id_list($parent_id,$channel_id){
	if(!isset($category_id_list)){
		$category_id_list=array();
		$category_id_list[]=$parent_id;
	}
	$res=$GLOBALS['db']->getall("SELECT category_id FROM ".$GLOBALS['db_prefix']."content_category WHERE parent_id=$parent_id  ORDER BY category_sort asc");
	if($res){
		foreach($res as $row){
			$category_id_list[]=$row['category_id'];
			if(category_have_child($row['category_id'])){
				$category_id_list[]=category_id_list($row['category_id'],$channel_id);
			}
		}
	}
	return $category_id_list;
}
function check_have_category($channel_id){
	if(empty($channel_id))return false;
	$count=$GLOBALS['db']->getcount("SELECT * FROM ".$GLOBALS['db_prefix']."content_category WHERE channel_id='$channel_id'");
	if($count>0){
		return true;
	}else{
		return false;
	}
}
function category_have_child($category_id){
	$count=$GLOBALS['db']->getcount("SELECT * FROM ".$GLOBALS['db_prefix']."content_category WHERE parent_id=$category_id");
	if($count>0){
		return true;
	}else{
		return false;
	}
}
function category_html_list($parent_id=0,$channel_id){
	if(!isset($category_html)){
		$category_html='';
	}
	$res=$GLOBALS['db']->getall("SELECT * FROM ".$GLOBALS['db_prefix']."content_category WHERE parent_id=$parent_id AND channel_id=$channel_id ORDER BY category_sort asc");
	if($res){
		foreach($res as $row){
			$check_have_child=category_have_child($row['category_id']);
			$category_html.="<div class='category_list'>".str_repeat('&nbsp;',($row['category_deep']*6))."-&nbsp;&nbsp;&nbsp;<a href='?action=content&do=category_edit&category_id=".$row['category_id']."&channel_id=".$row['channel_id']."'>".$row['category_name']."</a>";
			if(!$check_have_child){
				$category_html.="&nbsp;&nbsp;&nbsp;&nbsp;<a href=\"?action=content&do=category_delete&category_id=".$row['category_id']."&channel_id=".$row['channel_id']."\" onclick=\"return confirm('".$GLOBALS['language']['confirm_delete']."')\">".$GLOBALS['language']['delete']."</a></div>";
			}
			if($check_have_child){
				$category_html.=category_html_list($row['category_id'],$channel_id);
			}
		}
	}
	return $category_html;
}

function category_option_list($parent_id=0,$channel_id,$select_id=0){
	if(!isset($category_option)){
		$category_option='';
	}
	$res=$GLOBALS['db']->getall("SELECT * FROM ".$GLOBALS['db_prefix']."content_category WHERE parent_id=$parent_id AND channel_id=$channel_id ORDER BY category_sort asc");
	if($res){
		foreach($res as $row){
			$category_option.="<option value=\"".$row['category_id']."\" ".($select_id==$row['category_id']?'selected':'').">".str_repeat('&nbsp;',($row['category_deep']*4)).$row['category_name']."</option>";
			if(category_have_child($row['category_id'])){
				$category_option.=category_option_list($row['category_id'],$channel_id,$select_id);
			}
		}
	}
	return $category_option;
}
function get_content_link_list($content_id){
	$array=array();
	$res=$GLOBALS['db']->getall("SELECT * FROM ".$GLOBALS['db_prefix']."content_link WHERE content_id='".$content_id."'");
	foreach($res as $row){
		$array[$row['link_id']]['id']=$row['link_id'];
		$array[$row['link_id']]['url']=$row['link_url'];
	}
	return $array;
}
function get_content_attachment_list($content_id){
	$array=array();
	$res=$GLOBALS['db']->getall("SELECT * FROM ".$GLOBALS['db_prefix']."content_attachment WHERE content_id='".$content_id."'");
	foreach($res as $row){
		$array[$row['attachment_id']]['id']=$row['attachment_id'];
		$array[$row['attachment_id']]['name']=$row['attachment_name'];
	}
	return $array;
}
function get_content_attachment_image_list($content_id){
	$array=array();
	$res=$GLOBALS['db']->getall("SELECT * FROM ".$GLOBALS['db_prefix']."content_attachment WHERE content_id='".$content_id."'");
	if($res){
		foreach($res as $row){
			$ext=trim(strtolower(get_ext($row['attachment_name'])));
			if($ext=='jpg'||$ext=='png'||$ext=='gif'){
				$array[$row['attachment_id']]['id']=$row['attachment_id'];
				$array[$row['attachment_id']]['name']=$row['attachment_name'];
			}
		}
	}
	return $array;
}
function get_content_attachment_other_list($content_id){
	$array=array();
	$res=$GLOBALS['db']->getall("SELECT * FROM ".$GLOBALS['db_prefix']."content_attachment WHERE content_id='".$content_id."'AND (RIGHT(attachment_name,3)!='jpg' OR RIGHT(attachment_name,3)!='gif' OR RIGHT(attachment_name,3)!='png')");
	if($res){
		foreach($res as $row){
			$ext=trim(strtolower(get_ext($row['attachment_name'])));
			if($ext!='jpg'&&$ext!='png'&&$ext!='gif'){
				$array[$row['attachment_id']]['id']=$row['attachment_id'];
				$array[$row['attachment_id']]['name']=$row['attachment_name'];
			}
		}
	}
	return $array;
}
function get_channel_info($channel_id){
	if(empty($channel_id)){
		return array();
	}
	$row=$GLOBALS['db']->getone("SELECT * FROM ".$GLOBALS['db_prefix']."content_channel WHERE channel_id='".$channel_id."'");
	$array=array();
	$array['id']				=$row['channel_id'];
	$array['name']				=$row['channel_name'];
	$array['description']		=$row['channel_description'];
	$array['banner']			=$row['channel_banner'];
	$array['index']				=$row['channel_index'];
	$array['index_truncate']	=$row['channel_index_truncate'];
	$array['index_size']		=$row['channel_index_size'];
	$array['index_style']		=$row['channel_index_style'];
	$array['list_truncate']		=$row['channel_list_truncate'];
	$array['list_size']			=$row['channel_list_size'];
	$array['list_style']		=$row['channel_list_style'];
	$array['content_style']		=$row['channel_content_style'];
	$array['content_count']		=$row['channel_content_count'];
	$array['sort']				=$row['channel_sort'];
	$array['read_permissions']	=$row['channel_read_permissions'];
	$array['write_permissions']	=$row['channel_write_permissions'];
	$array['comment_permissions']=$row['channel_comment_permissions'];
	$array['upload_ext']		=$row['channel_upload_ext'];
	$array['cache']				=$row['channel_cache'];
	$array['state']				=$row['channel_state'];
	$array['url']				=create_uri('channel',array('id'=>$row['channel_id']));
	return $array;
}
function get_content_info($content_id){
	if(empty($content_id)){
		return array();
	}
	$row=$GLOBALS['db']->getone("SELECT * FROM ".$GLOBALS['db_prefix']."content WHERE content_id='".$content_id."'");
	$array=array();
	$array['id']			=$row['content_id'];
	$array['title']			=$row['content_title'];
	$array['keywords']		=$row['content_keywords'];
	$array['description']	=truncate(strip_tags($row['content_text']),200);
	$array['text']			=$row['content_text'];
	$array['password']		=$row['content_password'];
	$array['thumb']			=$row['content_thumb'];
	$array['support']		=$row['content_support'];
	$array['against']		=$row['content_against'];
	$array['click_count']	=$row['content_click_count'];
	$array['comment_count']	=$row['content_comment_count'];
	$array['is_best']		=$row['content_is_best'];
	$array['is_comment']	=$row['content_is_comment'];
	$array['time']			=date("Y-m-d",$row['content_time']);
	$array['state']			=$row['content_state'];
	$array['channel_id']	=$row['channel_id'];
	$array['special_id']	=$row['special_id'];
	$array['category_id']	=$row['category_id'];
	$array['member_id']		=$row['member_id'];
	$array['nickname']	=get_member_nickname($row['member_id']);
	$array['links']			=get_content_link_list($content_id);
	$array['attachments']	=get_content_attachment_list($content_id);
	$array['attachments_image']	=get_content_attachment_image_list($content_id);
	$array['attachments_other']	=get_content_attachment_other_list($content_id);
	$array['url']				=create_uri('channel',array('id'=>$row['channel_id']));
	return $array;
}
function get_category_name($category_id){
	if(empty($category_id))return'';
	$row=$GLOBALS['db']->getone("SELECT category_name FROM ".$GLOBALS['db_prefix']."content_category WHERE category_id='$category_id'");
	return $row[0];
}

function encode_comment($content){
	$content=htmlspecialchars($content);
	for($i=1;$i<22;$i++){
		$content=str_replace("[e:".$i."]","<img src=\"images/emot".$i.".gif\" alt=\"\" style=\"margin:2px;\" align=\"absmiddle\"/>",$content);
	}
	return $content;
}
?>