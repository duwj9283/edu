/**
 * Created by 20150112 on 2016/4/11.
 */
var MYMICO={
    headActive: function ($del,$tar) {
        $del.removeClass('active');
        $tar.addClass('active');
    },
    remoteInterface: function (data,cb) {
        $.ajax({
            url:data.url,
            type:'post',
            data:data,
            dataType:'json'
        }).done(cb);
    },
    getMyMlessons: function (page) {
        $.post('/api/mlesson/getMyMlessons',{page:page},function(serverData){
            if(serverData.code == 0){
                var list=serverData.data.mLessons,allCount=serverData.data.count;
                $('.classList').html(template('templateList',{list:list}));
                MYMICO.pagination(allCount,page);
            }
            else{
                console.log(serverData.msg);
            }
        },'json');
    },
    getDetail: function (data,cb) {
        $.post('/api/mlesson/getMlesson',data,cb,'json');
    },
    addDetail: function (data,cb) {
        $.post('/api/mlesson/addMlesson',data,cb,'json');
    },
    editDetail: function (data,cb) {
        $.post('/api/mlesson/editMlesson',data,cb,'json');
    },
    deleteDetail: function (data,cb) {
        $.post('/api/mlesson/delMlesson',data,cb,'json');
    },
    getGroupList: function (data,cb) {
        $.post('/api/group/getMyGroup',data,cb,'json');
    },
    getFilesList: function (data,cb) {
        $.post('/api/file/getFiles',data,cb,'json');
    },
    /*加载分页*/
    pagination: function  (allCount,startPage) {
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
            MYMICO.paginationBind();
        }
    },
    /*分页跳转函数*/
    paginationBind: function  () {
        $('.pagination li a').on('click', function () {
            var startPage = parseInt($(this).attr('rel'));
            // var $nowCol = RESOURCE.getColType($('ul.nav_list li.sel_list_ele')); //获取当前页的共有数据
            MYMICO.getMyMlessons(startPage);
        });
    },
};
