/**
 * Created by 20150112 on 2016/8/2.
 */
/**
 * iPage 当前页
 * iStatus 直播状态
 * oKeywords 关键词
 */
var iPage = 1;
var iStatus =0 ;
var oKeywords = null;
$(function(){
    LIVEMODEL.getPubList(iPage,iStatus,oKeywords);
    //通过直播状态检索
    $('.js-menu li').off();
    $('.js-menu li').on('click',function(){
        $(this).addClass('active').siblings().removeClass('active');
        iStatus = $(this).data('status');
        LIVEMODEL.getPubList(iPage,iStatus,oKeywords);
    });
    //通过关键词检索直播
    $('input[name="live-keyword"]').on("keypress",function(event){
        if(event.keyCode == "13"){
            oKeywords = $.trim($(this).val());
            LIVEMODEL.getPubList(iPage,subjectID,iStatus,oKeywords);
        }
    });
    $('.js-search-live').on('click',function(){
        oKeywords = $.trim($('input[name="live-keyword"]').val());
        LIVEMODEL.getPubList(iPage,iStatus,oKeywords);
    })
})