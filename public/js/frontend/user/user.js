/**
 * Created by 20150112 on 2016/3/3.
 */
$(function(){
    $('.body_side li.btn_person_info').addClass('selected');

    var city=$('#city').attr('data');
    var cityArray = city.split(',');
    /*
     * 省市二级联动
     * ie select问题
     * */
    $("#city").citySelect({
        prov:cityArray[0], //省份
        city:cityArray[1], //城市
        nodata:"none" //当子集无数据时，隐藏select
    });

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
    webUploader.init({
        label:'点击更换封面',
        pick:'#filePicker',
        thumbSize:{
            width:120,
            height:120
        }
    });


    var $form=$('form[name="user"]');
    $form.submit(function(){
        if($form.valid()){
            var params=$form.serialize();
            console.log(params);
            var pic = $('#fileList').data('picSrc');

            if(pic){//user logo
                params=params+'&headpic='+pic;
            }
            var city=$('.prov').val()+','+$('.city').val();
            $form.find(':submit').prop('disabled',true);
            userModel.updateInfo(params+'&city='+city,dealResult);
        }

    });
    //提交后处理
    var dealResult=function(data){
        $form.find(':submit').prop('disabled',false);
        if(data.code==0){//成功
            layer.msg('修改成功！');
        }else{
            layer.alert(data.msg);
        }
    };
    //学科
    subObject.init({
        pt:$('input[name="subjectFatherName"]').val(),
        ct:$('input[name="subjectName"]').val()
    });



    //js得到现在的时间,然后现实前5年 和后5年的年份
    var date=new Date();
    var now_year=date.getFullYear();//现在的时间
    var start_y=now_year-10;//开始时间
    var year_html='';//年份select 下option
    for(var i=now_year;i>=start_y;i--){
        year_html+='<option value="'+i+'">'+i+'</option>';
    }
    $('select[name="class_year"]').html(year_html);



    //班级年份选择的时候计算年级
    $('select[name="class_year"]').change(function(){
        var c_y=$(this).val();
        $('input[name="class"]').val((now_year-c_y)+1);


    });
    $('select[name="class_year"]').trigger('change');

});
