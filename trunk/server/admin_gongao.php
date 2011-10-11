<?php
require_once'includes/global.php';
require_once'includes/admin.php';
require_once'languages/'.$config['site_language'].'/admin.php';
$action		=empty($_GET['action'])?'':trim($_GET['action']);
$do			=empty($_GET['do'])?'':trim($_GET['do']);
/**************************************************************************************/
if($action==''){
	if($do==''){
		$smarty=new smarty();smarty_header();
		$smarty->display('login.htm');
	}
	 
}
 
/**************************************************************************************/
if($action=='content'){
  
	if($do==''||$do=='list'){//内容列表
		check_permissions('page_read');
		$where = "";
		$uid=empty($_GET['uid'])?0:intval($_GET['uid']);
		if($uid!=""&&$uid!=0){
			$where = " where uid=".$uid;
		}
		$sql = "SELECT * FROM ".$db_prefix."gongao ".$where." order by id desc";
		$page_size=15;
		$page_current=isset($_GET['page'])&&is_numeric($_GET['page'])?intval($_GET['page']):1;
		$count=$db->getcount($sql);
		$gongao_list=array();
		$res=$GLOBALS['db']->getall($sql." limit ".(($page_current-1)*$page_size).",".$page_size);
		if($res){
			foreach($res as $row){
				$gongao_list[$row['id']]['id']=$row['id'];
				$gongao_list[$row['id']]['content']=$row['content'];
				$gongao_list[$row['id']]['uid']=$row['uid'];
				$gongao_list[$row['id']]['uname']=$row['uname'];
				 
				$gongao_list[$row['id']]['recom']=$row['recom'];
				$gongao_list[$row['id']]['addtime']=date("Y-m-d H:i:s",$row['addtime']);
				$gongao_list[$row['id']]['endtime']=date("Y-m-d H:i:s",$row['endtime']);
			}
			$parameter='action=content&do=list&';
			if(!empty($_GET['category_id'])){
				$parameter.="category_id='".intval($_GET['category_id'])."'";
			}
			if(!empty($_GET['keyword'])){
				$parameter.="keyword='".trim($_GET['keyword'])."'";
			}
			$pagebar=pagebar(get_self(),$parameter,$page_current,$page_size,$count); 
		}else{
			$pagebar="";
		}
		$smarty=new smarty();smarty_header();
		$smarty->template_dir='templates/admin';
		$smarty->assign('gongao_list',$gongao_list);
		$smarty->assign('pagebar',$pagebar);
		$smarty->display('gongao_list.htm');
	}
	  
	if($do=='edit'){
		check_permissions("category_write");
		$category_id=empty($_GET['id'])?0:intval($_GET['id']);
		$row=$db->getone("SELECT * FROM ".$db_prefix."gongao WHERE id='".$category_id."'");
		$category=array();
		$category['id']=$row['id'];
		$category['content']=$row['content'];
		$category['uid']=$row['uid'];
		$category['addtime']=$row['addtime'];
		$category['endtime']=$row['endtime'];
		$smarty=new smarty();
		$smarty->template_dir='templates/admin';
		$smarty->assign('language',$language);
		$smarty->assign('category',$category);
		$smarty->assign('category_list',$category);
		$smarty->assign('mode','update');
		$smarty->display('gongao_info.htm');
	}
	if($do=='update'){
		check_permissions('category_write');
		$category_id=empty($_POST['id'])?0:intval($_POST['id']);
		
		$content=empty($_POST['content'])?'':addslashes(trim($_POST['content']));
		 
		 
		$update=array();
		 
		$update['content']=$content;
		 
		$db->update($db_prefix."gongao",$update,"id=$category_id");
	 
		clear_cache();
		message(array('text'=>$language['category_update_is_success'],'link'=>'admin_gongao.php?action=content'));
	}
	
	if($do=='rerecom'){
		check_permissions('category_write');
		$category_id=empty($_GET['id'])?0:intval($_GET['id']); 
		$update=array(); 
		$update['recom']=0; 
		$db->update($db_prefix."gongao",$update,"id=$category_id");
	 
		clear_cache();
		message(array('text'=>$language['category_update_is_success'],'link'=>'admin_gongao.php?action=content'));
	}
	if($do=='recom'){
		check_permissions('category_write');
		$category_id=empty($_GET['id'])?0:intval($_GET['id']); 
		$update=array(); 
		$update['recom']=1; 
		$db->update($db_prefix."gongao",$update,"id=$category_id");
	 
		clear_cache();
		message(array('text'=>$language['category_update_is_success'],'link'=>'admin_gongao.php?action=content'));
	}
	
	if($do=='delete'){
		check_permissions('category_delete');
		if(isset($_GET['id'])){
			$category_id=empty($_GET['id'])?'':intval($_GET['id']);
			$db->delete($db_prefix."gongao","id=$category_id");
		}
		if(isset($_POST['content_id'])){ 
			$content_id=empty($_POST['content_id'])?array():$_POST['content_id'];
			 foreach($content_id as $value){
				if(!empty($value)){
					$db->delete($db_prefix."gongao","id=$value");
				} 
			}
		}
		clear_cache();
		message(array('text'=>$language['category_delete_is_success'],'link'=>'admin_gongao.php?action=content'));
	} 
	
	
	if($do=='add'){ 
		//check_request();
		$smarty=new smarty();smarty_header();
		$smarty->template_dir='templates/admin';
		$smarty->display('ajax_gongao_add.htm');
	}
	
	if($do=='add_ok'){
		//check_request();
		$content=empty($_POST['content3'])?'':trim(addslashes($_POST['content3']));
		$isprivate=3;
		$uid=1;
		if(empty($content)){
			exit('0|无内容，请重新填写');
		} 
		$content = trim($content); // 去掉数据两端的空格  
		$content =str_replace("<","&lt;",$content); 
		$content =str_replace(">","&gt;",$content); 
		$content =str_replace("\n","<br />",$content); 
		//$content// 转换HTML
		$count=$db->getcount("SELECT * FROM ".$db_prefix."gongao WHERE  content='".$content."'");
		if($count>0){
			exit('0|重复内容');
		}
		$uname="落";
		$insert=array();
		$insert['content']=$content;
		$insert['recom']=$isprivate;
		$insert['addtime']=strtotime($_POST['addtime']);
		$insert['endtime']=strtotime($_POST['endtime']);
		$insert['uid']=$uid;
		$insert['uname']=$uname;
		$db->insert($db_prefix."gongao",$insert);
		clear_cache(); 
		message(array('text'=>$language['category_update_is_success'],'link'=>'admin_gongao.php?action=content'));
		
	}
}
  
?>