/**
 * Created by 20150112 on 2016/3/30.
 */
var COLSODEMODAL = {
    remoteInterface: function (data,cb) {
        $.ajax({
            url:data.url,
            type:'post',
            data:data,
            dataType:'json'
        }).done(cb);
    },

    setColShowCook: function (p,c) {
        document.cookie="colUp="+encodeURIComponent(p);
        document.cookie="colShow="+encodeURIComponent(c);
    },
    getCookie: function () {
        var oneCookie = document.cookie.split(';');
        var colUpString='';
        var colShowString='';
        for(var i=0;i<oneCookie.length;i++){
            var twoCookie = oneCookie[i].split('=');
            if($.trim(twoCookie[0]) == 'colUp'){
                colUpString = decodeURIComponent(twoCookie[1]);
            }else if($.trim(twoCookie[0]) == 'colShow'){
                colShowString = decodeURIComponent(twoCookie[1]);
            }
        }
        if(colUpString !='' && colShowString != ''){
            var $tgColUp  =$.trim(colUpString.replace('gly-up'));
            var $tgColShow  =$.trim(colShowString);
            $('ul.col_side_wrap.body_side li').each(function () {
                var $thisClass = $.trim($(this).attr('class'));
                //一级菜单改变状态
                if($thisClass == $tgColUp){
                    $(this).addClass('gly-up');
                }
                //设置二级菜单显示
                if($thisClass == $tgColShow){
                    $(this).show();
                }
            });
        }
    }
};
$(function(){

    /*侧边栏*/
    $('[data-event="toggle"]').on('click',function(){
        $this = $(this);
        if($this.next().is(":hidden")){
            //自身隐藏的情况下：隐藏其他，显示自己
            $('.col_side_wrap .col_side_li_2_wrap').slideUp();
            $('.col_side_2_title.gly-up').removeClass('gly-up');
            $this.next().slideDown();
            $this.addClass('gly-up');
        }else{
            //自身就是显示状态的情况下：只隐藏自己
            $this.next().slideUp();
            $this.removeClass('gly-up');
        }
    });

    $(".col_side_wrap a").each(function(){

        if($(this).attr('href') == (location.pathname+location.search)){
            $(this).closest('li').addClass('selected');
            console.log($(this).attr('href'));
        }

    });
    $(".col_side_li_2_wrap .selected").closest('.col_side_li_2_wrap').show();
    $(".col_side_li_2_wrap .selected").closest('.col_side_li_2_wrap').prev().addClass('gly-up');
    /*退出按钮*/
    $('li.btn_logout').on('click', function () {
        var uid=$('.user_info_wrap .user_name_wrap .user_name').attr('targetid');
        /*COLSODEMODAL.remoteInterface(data, function () {

         });*/
    });

});
