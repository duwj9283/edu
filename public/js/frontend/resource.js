/**
 * Created by 20150112 on 2016/3/14.
 */
// 初始属性
var type= 0,subjectID = 0,iPage= 1,templateName = 'template-resource';
$(function () {
    RESOURCE.getPushFiles(type,subjectID,iPage,templateName);
    //子级学科操作
    $('#subject_sub_list').delegate('.select-name:not(".active")','click',function(){
        // RESOURCE.getSubject($(this).data('id'));
        $('#subject_sub_list').find(".select-name").removeClass('active');
        $(this).addClass('active');
        subjectID = $(this).data('id');
        iPage = 1;
        $("#js-subject-check .sun_label").remove();
        $("#js-subject-check").append('<a href="javascript:;" rel="nofollow" class="sun_label select-name" data-id='+ $(this).data('id') +'><i class="icon-close js-clear-select"></i>'+$(this).text()+'</a>');
        RESOURCE.getPushFiles(type,subjectID,iPage,templateName);
    });
    //取消选中学科
    $("#js-subject-check").delegate('.select-name','click',function(){
        if($(this).hasClass('father_label')){
            $("#subject_sub_list,#js-subject-check").empty();
        }
        $(this).remove();
        $(".subject_list_wrap .select-name[data-id="+ $(this).data('id') +"]").removeClass('active');
        subjectID = 0;
        iPage = 1;
        RESOURCE.getPushFiles(type,subjectID,iPage,templateName);
    });
    /*导航选择*/
    $('.js-menu li').off('click');
    $('.js-menu li').on('click',function(){
        $(this).addClass('active').siblings().removeClass('active');
        type = $(this).data('type');
        RESOURCE.getPushFiles(type,subjectID,iPage,templateName);
    });
    /*设置文档类型*/
    template.helper('setType', function (data, type) {
        var dataSuffix=data.slice(data.lastIndexOf("."));
        if(dataSuffix===".pptx" || dataSuffix===".ppt"){
            type='ppt';
        }
        if(dataSuffix===".docx" || dataSuffix===".doc"){
            type='word';
        }
        if(dataSuffix===".xlsx" || dataSuffix===".xls"){
            type='xls';
        }
        if(dataSuffix===".txt"){
            type='txt';
        }
        if(dataSuffix===".pdf"){
            type='pdf';
        }
        if(dataSuffix===".rar"){
            type='rar';
        }
        if(dataSuffix===".zip"){
            type='zip';
        }
        if(dataSuffix===".xml"){
            type='xml';
        }
        return type;
    });
});


