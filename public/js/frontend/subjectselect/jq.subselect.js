/**
 * Created by 20150112 on 2016/4/27.
 */
var subject = {};
var subObject={
    getSubject: function (ptVal,ctVal) {
        $.ajax({
            url:'/frontend/source/getSubject',
            type:'post',
            dataType:'json',
        }).done(function(serverData){
            if(serverData.code ===0 ){
                subject =serverData.data;

                //第一层
                for(var key in subject){
                    var htmlStr = "<option value="+key+">"+subject[key]['subject_name']+"</option>";
                    $("#subject-select .sub-type").append(htmlStr);
                    if(subject[key]['id'] == ptVal){
                        $("#subject-select .sub-type").val(key);
                    }
                }
                //第二层
                //var ctKey = $("#subject-select .sub-type").val();
                //var ctKey = $("#subject-select .sub-child-type").val();
                subObject.secondStart(ptVal,ctVal);
            }
        });
    },
    init:function(settings){
        settings=$.extend({
            pt:34,
            ct:35,
        },settings);
        var ptVal = settings.pt;
        var ctVal = settings.ct;
        /*获取学科数据*/
        subObject.getSubject(ptVal,ctVal);
        /*二级级联开始*/
        $('#subject-select .sub-type').bind('change',function () {
            subObject.firstChange($(this));
        });

    },
    /*第一层监听改变事件*/
    firstChange:function($tar){
        var $key = $tar.val();
        subObject.secondStart($key);
    },
    /*第二层改变开始*/
    secondStart: function ($key,$default) {
        var ctArray = subject[$key]['child'];
        if(ctArray){
            $("#subject-select .sub-child-type").empty();
            for(var key in ctArray){
                var htmlStr = "<option value="+ctArray[key]['id']+">"+ctArray[key]['subject_name']+"</option>";
                $("#subject-select .sub-child-type").append(htmlStr);
                if($default == ctArray[key]['id']){
                    $("#subject-select .sub-child-type").val(ctArray[key]['id']);
                }
            }
        }
    }
};
$(function(){
    /*初始化*/

});