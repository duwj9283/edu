/**
 * Created by 20150112 on 2016/3/31.
 */
$(function () {
    /*初始化拖拽狂*/
    $('#label-popover').draggable({
        containment:'.label-pop',
        handle:'.pop-header',
        scroll:false
    });
    /*点击选择标签*/
    $('#label-popover .pop-content ul li').on('click', function () {
        $(this).toggleClass('selected');
    });
    /*关闭标签*/
    $('#label-popover .pop-footer button.btn_add_label_cancel').on('click', function () {
        $('#label-popover-container').fadeOut(function(){
            $('#label-popover li.selected').removeClass('selected');
        });
    });

});
var LABELPOP={
    show:function($list){
        $list.find('li').each(function () {
            var $sText = $(this).find('span').text();
            $('#label-popover-container .pop-content ul li').each(function () {
                var $text = $(this).text();
                if($sText === $text){
                    $(this).addClass('selected');
                }
            });
        });
        $('#label-popover-container').fadeIn();
    },
    addLabel:function($list){
        $list.empty();
        $('#label-popover-container ul li.selected').each(function () {
            var label = $(this).text();
            var $liHtmlStr = $('<li><span>'+label+'</span><a>✕</a></li>')
            $list.append($liHtmlStr);
        });
        //绑定删除已添加的标签按钮
        $list.find('li a').unbind();
        $list.find('li a').on('click', function () {
            $(this).parent().remove();
        });
        //隐藏标签页
        $('#label-popover-container').fadeOut(function(){
            $('#label-popover li.selected').removeClass('selected');
        });

    },

};