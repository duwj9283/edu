/**
 * Created by 20150112 on 2016/4/15.
 */
$(function () {
    /*切换个人空间选项*/
    $('.dt-list.dt-list02.dt-center a').on('click', function () {
        SPACE.toggleList($(this));
    });
});