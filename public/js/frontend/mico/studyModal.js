/**
 * Created by 20150112 on 2016/4/8.
 */
var STUDY={
    headActive: function ($del,$tar) {
        $del.removeClass('active');
        $tar.addClass('active');
    },
    /*΢��ѧϰ��¼*/
    getMlessonStudy :function(page){
        var data ={url:'/api/mlesson/getMlessonStudy',page:page}
        remote_interface_api(data,function(serverData){
            console.log(serverData);
            if(serverData.code == 0){
                var list=serverData.data.mLessons,allCount=serverData.data.count;
                console.log(serverData);
                $('.classList').html(template('templateList',{list:list}));
                STUDY.pagination(allCount,page);
            }
        });
    },
    /*���ط�ҳ*/
    pagination: function  (allCount,startPage) {
        /*
         * allCount:��Ҫ��ҳ������
         * eCount��ÿҳ��ʾ��Ŀ
         * startPage:��ʼҳ
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
    /*��ҳ��ת����*/
    paginationBind: function  () {
        $('.pagination li a').on('click', function () {
            var startPage = parseInt($(this).attr('rel'));
            MYMICO.getMyMlessons(startPage);
        });
    },
};