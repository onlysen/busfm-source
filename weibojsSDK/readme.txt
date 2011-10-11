Tencent Weibo SDK for Javascript/Flash/AIR Developer Version 1.0

说明:
1,SDK里面包含什么?
weibo.html: 授权逻辑在其中，并且包括了JS调用接口的范例 (关联的JS文件可通过源代码中的地址下载到本地来提速页面启动速度)。
weibo.xml: 用来编译AIR程序的文件（预览效果直接在命令行运行 >adl weibo.xml ）(AIR SDK编译环境配置方法请参考Adobe官方文档 http://url.cn/1ttFXQ )
proxy.swf: Flash代理文件，实现数据的跨域拉取和提交操作（配合weibo.html一同使用，不可删除）
proxy.fla: Flash代理文件源文件。
flash.fla: Flash使用范例文件

2,如何使用该SDK?
本SDK已经实现微博Oauth授权的全过程。开发者可专注做自己的功能开发，不用关心Oauth实现原理。

如果你是一名JS开发人员，使用如下方法：
获取数据使用 getWB("api_url")
发送数据使用 postWB("api_url")

如果你是一名 Flash 开发人员，使用如下方法
getWBStr("api_url")
postWBStr("api_url")

如果你是一名AIR开发人员，以上两种方法任选。

在使用上面方法前，你需要先通过weibo.html来登录腾讯微博获取授权。

认证步骤:
1,将你在腾讯微博开放平台申请的 App Key 和 App Secret 填入表单中。
2,点击sign跳转到腾讯微博官方登陆页。
3,将登录成功返回的授权码替换表单中的中文字符。
4,再次点击sign按钮，看到按钮状态变成 connected 后就代表成功和腾讯微博建立连接。


注意事项：
1，请确保你的电脑系统时间准确，如果和服务器时间相差5分钟以上，将无法完成授权过程。
2，使用该SDK，请确保电脑上有安装 Flash Player 8.0及以上版本。（下载地址:http://get.adobe.com/flashplayer）
3，如果你需要在本地硬盘上调试程序，需要先设置Flash文件的本地硬盘访问权限。来保证该SDK可以正常运行。
设置地址是：  http://url.cn/3VIL0i
（将该SDK所在目录添加到允许运行的列表中）
4，如果你的浏览器启动了拦截弹出窗口功能，请关闭。
5，关于AppKey和AppSecret安全问题，请自行在本地做加密保存处理。
6，如果你开发的是Web应用，给oauth_callback=null指定URL后，登录成功后会跳转到你的应用。

关于本SDK的任何意见和建议可以到我的微博提出。 http://t.qq.com/danger
要是做出好玩的应用，也别忘了告诉我~