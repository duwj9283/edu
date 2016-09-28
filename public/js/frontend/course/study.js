/**
 * Created by 20150112 on 2016/4/11.
 */
$(function () {
    /*标头选中*/
    COURSESTUDYMODEL.headActive($('#navbar-collapse-basic ul li.active'),$('#navbar-collapse-basic ul .course'));
    COURSESTUDYMODEL.getlessonStudy(1);
});