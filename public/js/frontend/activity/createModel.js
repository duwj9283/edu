
/**
 * �js-model
 * Created by sherry on 2016/4/26.
 */

var createModel={
    getList: function (data,cb) {
        $.post('/api/activity/getMyList',data,cb,'json');
    },
    createDetail: function (data,cb) {
        $.post('/api/activity/create',data,cb,'json');
    },
    updateDetail: function (data,cb) {
        $.post('/api/activity/update',data,cb,'json');
    },
    deleteDetail: function (data,cb) {
        $.post('/api/activity/delete',data,cb,'json');
    },
    getGroupList: function (data,cb) {
        $.post('/api/group/getMyGroup',data,cb,'json');
    }
};