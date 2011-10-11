//====================================================================================================
// [插件名称] dialogDrag
//----------------------------------------------------------------------------------------------------
//[描      述]  给元素增加可拖动功能
//[使用方法]  
//简单调用：$("#diva").dialogDrag();
//[源码下载]  
//----------------------------------------------------------------------------------------------------
// [作者网名] walkerwang
// [邮    箱] walkerwzy@gmail.com
// [作者博客] http://walkerwang.cnblogs.com
// [更新日期] 2011-01-17
// [版 本 号] ver1.1.1
//====================================================================================================
(function($) {
    $.fn.dialogDrag = function(options) {
        var options = $.extend({}, $.fn.dialogDrag.defaults, options);
        var debug = {};
        this.each(function() {
            //得到对话拖动窗体元素
            var box = $(this).css({ position: "absolute" });
            //得到手柄元素
            var h;
            if (options.handler == "") h = box;
            else h = $("#" + options.handler, this);
            if (h.length == 0) h = $("." + options.handler, this);
            h.mousedown(function(e) {
                var p = box.position();
                $(this).data({ ox: e.pageX, oy: e.pageY, onmove: true, left: p.left, top: p.top });
            }).css("cursor", "move");
            $("*").mousemove(function(e) {
                if (h.data("onmove")) {
                    //防止窗体出现margin之类的位移
                    var mleft = box.css("margin-left").replace(/px/gi, '');
                    var mtop = box.css("margin-top").replace(/px/gi, '');
                    if (mleft == 'auto') mleft = 0;
                    if (mtop == 'auto') mtop = 0;
                    var x = e.pageX - h.data("ox");
                    var y = e.pageY - h.data("oy");
                    var fx = h.data("left") + x;
                    var fy = h.data("top") + y;
                    $.extend(debug, { "event": e, "box": box });
                    //设置不允许超出窗体范围
                    if (options.inWindow) {
                        if (fx < -mleft) fx = -mleft;
                        else fx = Math.min(fx, document.body.clientWidth - box.width() - options.s_right - mleft);
                        if (fy < -mtop) fy = -mtop;
                        else fy = Math.min(fy, document.body.clientHeight - box.height() - options.s_bottom - mtop);
                        fx = Math.max(fx, options.s_left);
                        fy = Math.max(fy, options.s_top);
                    }
                    box.css({ left: fx, top: fy });
                    this.onselectstart = function() { event.returnValue = false; return false; }
                }
            });
            $("*").mouseup(function(e) { h.data("onmove", false); });
        });
        return debug;
    };
    //默认值
    $.fn.dialogDrag.defaults = {
        handler: "", //拖动动的手柄元素class名，为空则为可手动的元素本身
        inWindow: false, //是否允许超出窗体范围
        s_top: 0, //不允许超出窗体范围时距离窗体上方的距离
        s_bottom: 0, //不允许超出窗体范围时距离窗体下方的距离
        s_left: 0, //不允许超出窗体范围时距离窗体左边的距离
        s_right: 0//不允许超出窗体范围时距离窗体右边的距离
    }
})(jQuery);