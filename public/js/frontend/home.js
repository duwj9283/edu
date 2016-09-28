$(function(){
    /**
     *这里默认页是home_page
     *这里加判断的原因：是不是默认页，如果是默认页，则在hover事件还未结束时，强制默认页按正常流程进行；
     *因为下面的离开hover事件可能导致默认的标签显示不正常
     */
    $('.link_ul li').hover(function(){
        if(!$(this).hasClass('current_page')){
            $(this).find('.label_focus').animate({top:"0px"},'fast');
        }
    },function(){
        if(!$(this).hasClass('current_page')){
            $(this).find('.label_focus').animate({top:"32px"},'fast');
        }
    });

    /**
     * 点击跳转导航
     * */
    $('.link_ul li').on('click',function(){
        if(!$(this).hasClass('current_page')){
            if($(this).hasClass('home_page')){
                window.location.href = '/';
            }else if($(this).hasClass('publicResource_page')){
                window.location.href = '/resource';
            }else if($(this).hasClass('file_page')){
                window.location.href = '/file';
            }else if($(this).hasClass('class_page')){
                window.location.href = '/course';
            }
        }
    });


});
/*显示登录后的个人头像*/
/*function logined_header(){
    $('.auth_wrap').hide();
    $('.search_wrap .user_info_wrap').show();
}*/

