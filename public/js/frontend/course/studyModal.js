/**
 * Created by 20150112 on 2016/4/11.
 */

var COURSESTUDYMODEL={
    headActive: function ($del,$tar) {
        $del.removeClass('active');
        $tar.addClass('active');
    },
    //ѧϰ�б�
    getlessonStudy: function(page){
        var data={url:'/api/lesson/getlessonStudy',page:page}
        remote_interface_api(data,function(serverData){
            if(serverData.code == 0){
                var list = serverData.data.lessons,count=serverData.data.count;
                //for(var item in list){
                //    list[item].addtime=TimeStamp(list[item].addtime).Format("yyyy��M��d��");
                //}
                $('#classList .list').html(template('templateList',{list:list}));
                COURSESTUDYMODEL.pagination(count,page);
            }
            else{
                layer.msg(serverData.msg);
            }
        })
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
            COURSESTUDYMODEL.paginationBind();
        }
    },
    /*��ҳ��ת����*/
    paginationBind: function  () {
        $('.pagination li a').on('click', function () {
            var startPage = parseInt($(this).attr('rel'));
            COURSESTUDYMODEL.getlessonStudy(startPage);
        });
    },

};