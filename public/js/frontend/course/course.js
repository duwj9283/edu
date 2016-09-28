/**
 * Created by 20150112 on 2016/3/21.
 */
/**
 * subjectID 学科id
 * iPage 当前页
 * iType 课程状态
 * oKeywords 关键词
 * iSort 排序
 */
var subjectID= 0;
var iPage = 1;
var iType =0 ;
var oKeywords = null;
var iSort = 0;
$(function(){
    //获取课程列表
    COURSEMODEL.getLessons(iPage,subjectID);
    //通过专业检索用户
    $('.js-menu li').off();
    $('.js-menu li').on('click',function(){
        $(this).addClass('active').siblings().removeClass('active');
        subjectID = $(this).data('id');
        COURSEMODEL.getLessons(iPage,subjectID,iType,oKeywords,iSort);
    })
    //课程状态
    $('.js-type li').on('click',function(){
        $(this).addClass('active').siblings().removeClass('active');
        iType = $(this).data('type');
        COURSEMODEL.getLessons(iPage,subjectID,iType,oKeywords,iSort);
    })
    //排序
    $('.js-sort li').on('click',function(){
        $('.js-sort-name').text($(this).text());
        iSort = $(this).data('sort');
        COURSEMODEL.getLessons(iPage,subjectID,iType,oKeywords,iSort);
    })
    //搜索课程
    $('input[name="course-keyword"]').on("keypress",function(event){
        if(event.keyCode == "13"){
            oKeywords = $.trim($(this).val());
            COURSEMODEL.getLessons(iPage,subjectID,iType,oKeywords,iSort);
        }
    });
    $('.js-search-course').on('click',function(){
        oKeywords = $.trim($('input[name="course-keyword"]').val());
        COURSEMODEL.getLessons(iPage,subjectID,iType,oKeywords,iSort);
    })
});
