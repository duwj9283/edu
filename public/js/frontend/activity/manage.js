$(function () {
    $('.body_side li.btn_activity').addClass('selected');



    //活动管理list
    var listResult=function(data){
        var list=data.data.rows;

        if(list.length>0){
            for(var i in list){
                list[i].start_time_s=TimeStamp(list[i].start_time).Format("yyyy年M月d日");
                list[i].start_time_e=TimeStamp(list[i].start_time).Format("hh:mm");
                list[i].end_time_s=TimeStamp(list[i].end_time).Format("yyyy年M月d日");
                list[i].end_time_e=TimeStamp(list[i].end_time).Format("hh:mm");

            }
        }
        $('.person-activity-list').html(template('templateList',{list:list}));
    };
    var filter={'title':''};
    createModel.getList(filter,listResult);
    //标题搜索
    $('input[name="title"]').keydown(function(event){
        var title=$(this).val();
        if(event.keyCode==13&&title){
            filter.title=title;
            createModel.getList(filter,listResult);
        }
    });

    //删除活动
    var cur_id=0;
    $('.activity-infor-list').delegate('.del-btn','click',function(){
        cur_id=$(this).parents('li').data('id');
        layer.msg('确定删除么？', {
            time: 0 //不自动关闭
            ,btn: ['确定', '取消']
            ,yes: function(index){
                createModel.deleteDetail({id:cur_id},function(data){
                    if(data.code==0){//成功
                        $('.activity-infor-list li[data-id='+cur_id+']').remove();
                    }else{
                        layer.alert(data.msg);
                    }
                });
            }
        });

    })
   //报名管理
   var checked_num=0;
   $(".activity-infor-list").delegate('.ba-man','click',function(){
	   layer.open({
		   type: 1,
		   title:'<span class="checked_num fr">已选<b>'+checked_num+'</b>人</span>报名管理',
		   area: ['920px', '90%'],
		   content: $(".activity-manage-popLayer")
		});
	  });
   //填表信息
   $(".activity-manage-popLayer").delegate('.arrow-btn',"click",function(){
	 if($(this).hasClass("open")){
		 $(this).removeClass("open");
		 $(".activity-manage-popLayer .info-"+$(this).data("id")).hide(); 
		 }
	 else{
		 $(this).addClass("open");
		 $(".activity-manage-popLayer .info-"+$(this).data("id")).show(); 
		 }
   });
  //审核
   $(".activity-manage-popLayer").delegate('.checked-no',"click",function(){
	   // $(this).addClass("checked-blue");
	   if($(this).hasClass("checked-blue")){$(this).removeClass("checked-blue");}
	   else{$(this).addClass("checked-blue");}
	   checked_num = $(".activity-manage-popLayer .checked-blue").length;
	   $(".checked_num b").html(checked_num);
	 });

});