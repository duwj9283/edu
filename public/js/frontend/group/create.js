/**
 * Created by 20150112 on 2016/4/11.
 */
$(function () {
    CREATE.headActive($('#navbar-collapse-basic ul li.active'),$('#navbar-collapse-basic ul .group'));
    CREATE.showColSide($('ul.body_side li.my_group_wrap'));
    /*radio*/
    $(".pri-list .mod-radio label").click(function(){
        if($(this).find("input[type='radio']").is(":checked")){
            $(this).parent().parent().find("label").removeClass("active");
            $(this).addClass("active");
        }
    });

    //创建群组提交
    var $form=$('form[name="group"]');
    $form.submit(function(){
        if($form.valid()){
            var pic = $('#fileList').data('picSrc');
            if(!pic){
                layer.alert('请先上传添加群logo');return false;
            }
            $form.find(':submit').prop('disabled',true);
            CREATE.createDetail($form.serialize()+"&headpic="+pic,dealResult);
        }

    });
    //提交后处理
    var dealResult=function(data){

        $form.find(':submit').prop('disabled',false);
        if(data.code==0){//成功
            layer.alert('添加成功！');
        }else{
            layer.alert(data.msg);
        }
    };
    /*初始化上传webuploader*/
    webUploader.init({
        label:'点击更换封面',
        pick:'#filePicker',
        thumbSize:{
            width:340,
            height:189
        }
    });
});