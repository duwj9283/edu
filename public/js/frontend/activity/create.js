$(function () {
    $('.body_side li.btn_activity').addClass('selected');

    /*radio*/
    $('.mod-radio label').click(function(){
        var $radio=$(this).find("input[type='radio']");
        if(!$radio.is(':disabled')&&$radio.prop("checked")==false){
            $(this).parents('li').find("label").removeClass("active");
            $(this).parents('li').find("input").prop("checked",false);
            $(this).addClass("active");
            $radio.prop("checked",true);
            if($radio.prop('name')=='type'&&$radio.val()==3){
                $('input[name="password"]').show();
            }else if($radio.prop('name')=='type'){
                $('input[name="password"]').hide();
            }
            if($radio.prop('name')=='is_pay'&&$radio.val()==1){

               $('input[name="price"]').show();
            }else if($radio.prop('name')=='is_pay'){
               $('input[name="price"]').hide();
            }
        }
        return false;
    })

    var ue_config={
        initialFrameWidth : 442,
        initialFrameHeight: 100,
        elementPathEnabled:false,
        enableAutoSave:false,
        toolbars: [[
            'fullscreen', 'source', '|', 'undo', 'redo', '|',
            'bold', 'italic', 'underline', 'fontborder', 'strikethrough', 'superscript', 'subscript', 'removeformat', 'formatmatch', 'autotypeset', 'blockquote', 'pasteplain', '|', 'forecolor', 'backcolor', 'insertorderedlist', 'insertunorderedlist','|',
            'customstyle', 'paragraph', 'fontfamily', 'fontsize', '|',
            'directionalityltr', 'directionalityrtl', 'indent', '|',
            'justifyleft', 'justifycenter', 'justifyright', 'justifyjustify','|',
            'simpleupload', 'insertimage', 'emotion',
            'horizontal', 'date', 'time', 'spechars', 'snapscreen', 'wordimage', '|',
            'inserttable', 'deletetable', 'insertparagraphbeforetable', 'insertrow', 'deleterow', 'insertcol', 'deletecol', 'mergecells', 'mergeright', 'mergedown', 'splittocells', 'splittorows', 'splittocols', 'charts', '|',
            'print', 'preview', 'searchreplace', 'help'
        ]]
    };
    var ue= UE.getEditor('about-content',ue_config);
    /*********************初始化上传webuploader -S*********************/
    webUploader.init({
        label:'点击更换封面',
        pick:'#filePicker',
        thumbSize:{
            width:340,
            height:189
        }
    });
    /*********************初始化上传webuploader -E*********************/


    var type=$('input[name="type"]').val();//活动类型
    var group_ids=($('input[name="group_ids"]').val()).split(',');//活动群组ids array
    //群组list
    var groupResult=function(data){
        if(data.data.groups.length>0){
            var groups=data.data.groups,html;
            for(var i in groups){
                if(type==2&&$.inArray(groups[i].gid,group_ids)>-1){
                    //编辑活动 如果活动发布类型是群组 且 此id包含在群组id里
                    html+='<option value="'+groups[i].gid+'" selected>'+groups[i].name
                }else{
                    html+='<option value="'+groups[i].gid+'">'+groups[i].name
                }

            }
            $('select[name="group_ids"]').html(html);
            $('select[name="group_ids"]').prev('input').removeAttr('disabled');//如果有群组 则可选择
        }
    };
    createModel.getGroupList({},groupResult);
    var $form=$('form[name="acitvity"]');
    /*************************附加信息-js-S*************************/
    ///添加附加信息
    $form.delegate('.icon_add_label','click',function(){
        var html='<input name="questions[]" type="text" class="pri-input" placeholder="请添加附加信息" required="" > ';
        if($(this).parent('li').find('input').size()>0){
            html='<input name="questions[]" type="text" class="pri-input question-other-input" placeholder="请添加附加信息" required="" >';
        }

        $(this).before(html);
    })
    ///删除附加信息
    $form.delegate('.icon_del_label','click',function(){
        if($(this).parent('li').find('input').size()>0){
            $(this).parent('li').find('input:last').remove();
        }

    })
    /*************************附加信息-js-D*************************/
    //活动费用
    $('input[name="is_pay"]').click(function(){
        if($(this).val()==0){
            $('input[name="price"]').hide();return false;
        }
        $('input[name="price"]').show();return false;

    })

    //创建群组提交
    var activity_id=$('input[name="activity_id"]').val();//活动id
    $form.submit(function(){
        if($form.valid()){

            var pic = $('#fileList').data('picSrc');
            if(!pic&&!activity_id){//新增的时候必须传群logo
                layer.alert('请先上传添加群logo');return false;
            }
            $form.find(':submit').prop('disabled',true);
            var form_data=$form.serialize();
            if(pic){
                form_data=form_data+"&cover_pic="+pic;
            }

            if(activity_id){//编辑
                createModel.updateDetail(form_data+"&id="+activity_id,dealResult);
                return false;
            }
            //新增
            createModel.createDetail(form_data,dealResult);
        }

    });
    //提交后处理
    var dealResult=function(data){
        $form.find(':submit').prop('disabled',false);
        if(data.code==0){//成功
            window.location.href='/activity/manage';
        }else{
            layer.alert(data.msg);
        }
    };
});