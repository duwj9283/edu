/**
 * Created by 20150112 on 2016/8/4.
 */
var subjectID = 0,subjectPage= 1,subjecKeywords =null;
var micoPage = 1,micoKeywords =null;
$(function(){
    PUBLISTMODEL.getMlessonList(1,subjectID);
    //弹出专业层
    $("#js-subject-more").on('click',function(){
        subjectPage = 1;
        layer.open({
            type: 1,
            title: false,
            shade: 0,
            area:['1000px','430px'],
            skin:'subject-layer',
            content:$('#js-subject-wrap'),
            success: function(layero, index){
                PUBLISTMODEL.getChildSubject(subjectPage);
            }
        });
    });
    //加载更多专业
    $('#js-subject-wrap .js-more').off('click');
    $('#js-subject-wrap .js-more').on('click',function(){
        subjectPage++;
        PUBLISTMODEL.getChildSubject(subjectPage,subjecKeywords);
    });
    //搜索专业
    $('#js-subject-wrap .js-s-btn').on('click',function(){
        subjecKeywords = $.trim($('input[name="subject-name"]').val());
        PUBLISTMODEL.getChildSubject(1,subjecKeywords);
    });
    $('input[name="subject-name"]').focus(function(){
        $('input[name="subject-name"]').select();
    });
    $('input[name="subject-name"]').on('keypress',function(event){
        if(event.keyCode == "13")
        {
            subjecKeywords = $.trim($(this).val());
            PUBLISTMODEL.getChildSubject(1,subjecKeywords);
        }
    });
    //    搜索微课
    $('#js-s-mico-btn').on('click',function(){
        micoKeywords = $.trim($('input[name="mico-kewords"]').val());
        PUBLISTMODEL.getMlessonList(micoPage,subjectID,micoKeywords);
    });
    $('input[name="mico-kewords"]').on('keypress',function(event){
        if(event.keyCode == "13")
        {
            micoKeywords = $.trim($(this).val());
            PUBLISTMODEL.getMlessonList(micoPage,subjectID,micoKeywords);
        }
    });
    // 通过专业检索微课
    $(".js-subject-list").off('click','li');
    $(".js-subject-list").on('click','li:not(".active")',function(){
        $(this).addClass('active').siblings().removeClass('active');
        subjectID = $(this).data('id');
        PUBLISTMODEL.getMlessonList(micoPage,subjectID);
        layer.closeAll('page');
    });
})
