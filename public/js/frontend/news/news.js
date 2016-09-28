/**
 * Created by 20150112 on 2016/8/11.
 */
$(function(){
    var pagination = function  (startPage) {
        /*
         * allCount:需要分页的总数
         * eCount：每页显示数目
         * startPage:起始页
         * */
        var allCount = $('.js-list').data('count');
        var eCount = 12;
        var pageCount = Math.ceil(allCount / eCount);
        var pageHtml =  page(pageCount,eCount,startPage);
        $('.page-wrap').empty();
        if (pageHtml !== ''){
            $('.page-wrap').html(pageHtml);
            paginationBind();
        }
    };
    /*分页跳转函数*/
    var paginationBind = function  () {
        $('.pagination li a').on('click', function () {
            var startPage = parseInt($(this).attr('rel'));
            location.pathname='/news/'+startPage;
        });
    };
    pagination(1);
    $('.js-search-news').on('click',function(){
        kewords = $.trim($('input[name="news-kewords"]').val());
        window.open('/news'+location.search,'_self');
        location.search = '?keywords='+kewords;
    });
    $('input[name="news-kewords"]').on('keypress',function(event){
        if(event.keyCode == "13")
        {
            kewords = $.trim($(this).val());
            window.open('/news'+location.search,'_self');
            location.search = '?keywords='+kewords
        }
    });
    $('.js-post-more').off('click');
    $('.js-post-more').on('click',function(){
        if($(this).hasClass('open')){
            $(this).removeClass('open');
            $(this).text('详情');
            $(this).parents('.post').find('.post-content p:first').removeClass('show');
        }
        else{
            $(this).addClass('open');
            $(this).text('收起');
            $(this).parents('.post').find('.post-content p:first').addClass('show');
        }
    });
})
