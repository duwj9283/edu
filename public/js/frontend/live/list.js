$(function () {
    $('#navbar-collapse-basic ul li').removeClass('active');
    $('#navbar-collapse-basic ul .live').addClass('active');

    //获取list
    var getMyLiveList = function(page){
        createModel.getInLiveList({page:page},function(data){
            console.log(data.data);
            if (data.code == 0){
                var liveList=data.data.rows,allCount = data.data.count;
                pagination(allCount,page);
                $('.classList').html(template('templateList',{list:liveList}));
            }
            else{
                layer.msg(data.msg)
            }
        });
    }
    /*加载分页*/
    var pagination = function  (allCount,startPage) {
        /*
         * allCount:需要分页的总数
         * eCount：每页显示数目
         * startPage:起始页
         * */
        var eCount = 12;
        var pageCount = Math.ceil(allCount / eCount);
        var pageHtml =  page(pageCount,eCount,startPage);
        $('.page-wrap').empty();
        if (pageHtml !== ''){
            $('.page-wrap').html(pageHtml);
            paginationBind();
        }
    }
    /*分页跳转函数*/
    var paginationBind = function  () {
        $('.pagination li a').on('click', function () {
            var startPage = parseInt($(this).attr('rel'));
            // var $nowCol = RESOURCE.getColType($('ul.nav_list li.sel_list_ele')); //获取当前页的共有数据
            getMyLiveList(startPage);  //加载当前页内容
        });
    }

    //删除
    $('.classList').delegate('.js-del','click',function(){
        var $item=$(this).parents('.item');
        var id=$item.data('id');
        layer.confirm('确定删除么？', {
            time: 0 //不自动关闭
            ,btn: ['确定', '取消']
            ,yes: function(index){
                createModel.deleteDetail({id:id},function(data){
                    if(data.code==0){
                        $item.remove();
                        layer.close(index);
                        return false;
                    }
                    layer.msg(data.msg);
                });
            }
        });
    });
    getMyLiveList(1);
});