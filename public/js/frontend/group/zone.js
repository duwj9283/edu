/**
 * Created by 20150112 on 2016/4/11.
 */
$(function () {
    ZONE.headActive($('#navbar-collapse-basic ul li.active'),$('#navbar-collapse-basic ul .group'));
    ZONE.showColSide($('ul.body_side li.my_group_wrap'));
    /*初始化群成员列表的滚动条*/
    $("#list-overflow").niceScroll({cursorwidth :"8px",cursorcolor:"#515567",cursorborderradius:"4px"});
    /*分类导航*/
    $('.dt-list.space-list-header').children('a').on('click',function(){
        ZONE.getDetail($(this));
    });
    /*显示群成员列表*/
    $('.group-member-list').on('click',function(){
        $('#group-member-list-pop').fadeIn();
    });
    /*关闭群成员列表*/
    $('#group-member-list-pop .pop-header a.pop-close').on('click',function(){
        $('#group-member-list-pop').fadeOut();
    });
});