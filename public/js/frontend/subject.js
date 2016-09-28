/**
 * Created by 20150112 on 2016/8/7.
 */
var selectSubject ={
    getSubject: function($id){
        var data={url:'/frontend/source/getSubject',father_id:$id};
        remote_interface_api(data, function (serverData) {
            if(serverData.code ===0 ){
                subject =serverData.data[$id].child;
                //第一层
                var htmlStr = '';
                for(var key in subject){
                    htmlStr += "<a href='javascript:;' rel='nofollow' class='select-name' data-id="+subject[key]['id']+" title="+subject[key]['subject_name']+">"+subject[key]['subject_name']+"</a>";
                }
                $("#subject_sub_list").html(htmlStr);
                $("#subject_sub_list").closest(".select_label_wrapper").show();
            }

        });
    },
    getSubjectKeWords: function($keywords){
        var data={url:'/frontend/source/getSubject',keywords:$keywords};
        remote_interface_api(data, function (serverData) {
            if(serverData.code ===0 ){
                subject =serverData.data;
                //第一层
                var htmlStr = '';
                for(var key in subject){
                    htmlStr += "<a href='javascript:;' rel='nofollow' class='select-name' data-id="+subject[key]['id']+" title="+subject[key]['subject_name']+">"+subject[key]['subject_name']+"</a>";
                }
                $("#js-subject-more .subject_list_wrap").empty().html(htmlStr);
            }

        });
    },
}

$(function(){
    /*学科按钮*/
    $('#js-open-select').off('click');
    $('#js-open-select').on('click',function(){
        if($('.subject-content').is(':visible')){
            $('.subject-content').hide()
        }
        else{
            $('.subject-content').show()
        }
    });
    //父级学科操作
    $('#subject_father_list').delegate('.select-name:not(".active")','click',function(){
        $('#subject_father_list').find(".select-name").removeClass('active');
        $(this).addClass('active');
        $("#subject_sub_list").empty();
        $("#js-subject-check").empty().append('<a href="javascript:;" rel="nofollow" class="father_label select-name" data-id='+ $(this).data('id') +'><i class="icon-close js-clear-select"></i>'+$(this).text()+'</a>');
        selectSubject.getSubject($(this).data('id'));
    });

    /*搜索学科*/
    $('input[name=subject-kewords]').focus(function(){
        $('input[name=subject-kewords]').select();
    });
    $('input[name=subject-kewords]').on('keypress',function(event){
        if(event.keyCode == "13")
        {
            var $keywords = $.trim($(this).val());
            selectSubject.getSubjectKeWords($keywords);
        }
    });
    /*学科赛选*/
    $("#js-open-subject").on('click',function(){
        if($("#js-subject-more").is(":visible")){
            $("#js-subject-more").hide();
            $(this).text('展开');
        }
        else{
            $("#js-subject-more").show();
            $(this).text('关闭');
        }

    });
})