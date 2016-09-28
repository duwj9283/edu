
$(function(){
    $("#infor-overflow").niceScroll();

    $('ul.col_side_ul_2 li.btn_group_manage ').addClass('selected');
    $('.col_side_li_2_wrap.my_group_wrap').show();

    var teams={1:'班级组',2:'交流组',3:'工作组'};//组别
    var list;//group list
    var dataResult=function(data){
        console.log(data);
        list=data.data.groups;
        console.log(teams);
        $('.grounp-list').html(template('templateList',{list:list,teams:teams}));
    };
    manageModel.getList({},dataResult);

})
