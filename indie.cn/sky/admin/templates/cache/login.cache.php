<?php if (!class_exists('template')) die('Access Denied');$template->getInstance()->check('login.htm', '84615d9ff32cf0cb30ab65ddf1235624', 1297877334);?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title>EasyTalk 后台管理系统 | powered by 9italk.com</title>
<style type="text/css">* { margin: 0; padding: 0; }body { text-align: center; color: #333; }body, td, th { font: 12px/1.5em Arial; }.loginbox { margin: 180px auto 60px; text-align: left; }td { }.logo { width: 296px; w\idth: 226px; padding: 90px 70px 30px 0; background:url(images/logo1.gif) no-repeat 100% 50%; text-align: right; }.logo p { margin: -40px 0 0 0; }.loginform th, .loginform td { padding: 3px; font-size: 14px; }.t_input { padding: 3px 2px; border: 1px solid; border-color: #666 #EEE #EEE #666; }.submit { height: 22px; padding: 0 5px; border: 1px solid; border-color: #EEE #666 #666 #EEE; background: #DDD; font-size: 14px; cursor: pointer; }.footer { position: absolute; bottom: 10px; left: 50%; width: 500px; margin-left: -250px; color: #999; }a { color: #2366A8; text-decoration: none; }a:hover { text-decoration: underline; }</style>
</head>
<body>
<div class="mainarea">
<form method="post" action="login.php">
    <table class="loginbox">
        <tr>
            <td class="logo"><p>填写账号和密码，进入管理平台</p></td>
            <td>
                <table callspacing="0" cellpadding="0" class="loginform">
                    <tr>
                        <th>账　号：</th>
                        <td><?php if(($my['user_name'])) { ?><input type="type" name="userid" class="t_input" value="<?php echo $my['user_name']?>" readonly="readonly"/><?php } else { ?><a href="../op.php?op=login">请先登录前台</a><?php } ?></td>
                    </tr>
                    <tr>
                        <th>密　码：</th>
                        <td><input type="password" name="password" class="t_input" /></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>
                        <input type="hidden" name="action" value="login">
                        <input type="submit" name="btnsubmit" value="登录后台" class="submit" /> or <a href="../index">返回前台</a>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</form>
</div>
<p class="footer">Powered by <a href="http://www.9italk.com" target="_blank">EasyTalk</a> &copy; 2008 - 2010 <a href="http://www.9italk.com" target="_blank">9italk.com</a></p>
</body>
</html>