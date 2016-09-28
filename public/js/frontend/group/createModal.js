/**
 * Created by 20150112 on 2016/4/11.
 */
var CREATE={
    headActive: function ($del,$tar) {
        $del.removeClass('active');
        $tar.addClass('active');
    },
    showColSide: function ($t) {
        $t.show();
        $('.col_side_wrap.body_side .col_side_2_title.my_group').addClass('gly-up');
        $t.find('ul li.btn_create_group').addClass('selected');
    },
    createDetail: function (data,cb) {
        $.post('/api/group/create',data,cb,'json');
    }

};