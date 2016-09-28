/*
 * 省市二级联动
 * ie select问题
 * */
$("#city").citySelect({
    nodata:"none" //当子集无数据时，隐藏select
})
//班级年份  js得到现在的时间,然后现实前5年 和后5年的年份
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

//学科
subObject.init();

var $form=$('form[name="register-next-form"]');
$form.submit(function(){
    var params=$form.serialize();
    var city=$('.prov').val()+','+$('.city').val();
    $form.find(':submit').prop('disabled',true);
    $.post('/api/user/updateInfo',params+'&city='+city,function(data){
        if(data.code==0){//成功
            window.location.href = '/user';
        }else{
            $form.find(':submit').prop('disabled',false);
            $(".field").eq($('.field').size()-2).append('<div class="field-msg msg-warning">'+data.msg+'</div>');

        }
    },'json');


});