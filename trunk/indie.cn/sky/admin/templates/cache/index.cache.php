<?php if (!class_exists('template')) die('Access Denied');$template->getInstance()->check('index.htm', '64728106c3f2722b73bd8c6a64724b2e', 1297877333);?>

<?php include($template->getfile("header.htm")); ?>
<div class="mainarea">
<div class="maininner">
<div class="bdrcontent">
<form method="post" action="index.php">
    <div class="title"><h3>站点设置</h3></div>
    <table border="0" width="100%">
        <tr height="35px">
            <td width="80" align="right">网站名称：</td>
            <td width="350px"><input name="web_name" type="text" value="<?php echo $webn1?>" style="width:300px"/></td>
            <td rowspan="5" valign="top">
            <b>系统信息</b>
            <hr style="border:1px dashed; height:1px" color="#DDDDDD">
            <table border="0">
                <tr height="30px">
                    <td width="100px">EasyTalk版本</td>
                    <td>EasyTalk <?php echo $version?> <a href="http://www.nextsns.com/checknew.php?v=<?php echo base64_encode($version);; ?>" target="_blank">查看新版本</a></td>
                </tr>
                <tr height="30px">
                    <td>操作系统及PHP</td>
                    <td><?php echo $serverinfo?></td>
                </tr>
                <tr height="30px">
                    <td>MySQL版本</td>
                    <td><?php echo $dbversion?></td>
                </tr>
                <tr height="30px">
                    <td>数据库大小</td>
                    <td><?php echo $dbsize?></td>
                </tr>
            </table>
            </td>
        </tr>
        <tr height="35px">
            <td align="right">网站副标题：</td>
            <td width="350px"><input name="web_name2" type="text" value="<?php echo $webn2?>" style="width:300px"/></td>
        </tr>
        <tr height="35px">
            <td align="right">网站关键字：</td>
            <td width="350px"><input name="seokey" type="text" value="<?php echo $seokey?>" style="width:300px"/></td>
        </tr>
        <tr height="35px">
            <td align="right" valign="top">网站描述：</td>
            <td width="350px"><textarea name="description" style="width:300px;height:50px"/><?php echo $description?></textarea></td>
        </tr>
        <tr height="35px">
            <td align="right">网站备案：</td>
            <td width="350px"><input name="web_miibeian" type="text" value="<?php echo $webm?>" style="width:300px"/></td>
        </tr>
        <tr height="35px">
            <td width="80" align="right">用户注册：</td>
            <td><input type="radio" name="userreg" id="r1" value="0" <?php if(($closereg==0)) { ?>checked<?php } ?>><label for="r1">&nbsp;允许&nbsp;</label>
            </font><input type="radio" name="userreg" id="r2" value="1" <?php if(($closereg==1)) { ?>checked<?php } ?>><label for="r2">&nbsp;关闭&nbsp;</label>
            </font><input type="radio" name="userreg" id="r3" value="3" <?php if(($closereg==3)) { ?>checked<?php } ?>><label for="r3">&nbsp;邀请注册&nbsp;</label>
            </td>
        </tr>
        <tr height="35px">
            <td align="right">关闭网站：</td>
            <td><input type="radio" name="closeweb" id="c1" value="1" <?php if(($webclose==1)) { ?>checked<?php } ?>><label for="c1">&nbsp;关闭&nbsp;</label>
            <input type="radio" name="closeweb" id="c2" value="0" <?php if(($webclose==0)) { ?>checked<?php } ?>><label for="c2">&nbsp;开启&nbsp;</label>
            </td>
        </tr>
        <tr height="35px">
            <td>&nbsp;</td>
            <td><input type="hidden" name="action" value="edit" /><input type="submit" value="保存设置" /></td>
        </tr>
    </table>
</form>
</div>
</div>
</div>
<?php include($template->getfile("foot.htm")); ?>
