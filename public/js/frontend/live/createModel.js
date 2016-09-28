
/**
 * 直播model
 * Created by sherry on 2016/5/14.
 */

var createModel={
    getList: function (data,cb) {
        $.post('/api/live/getMyList',data,cb,'json');
    },
    getListByDate: function (data,cb) {
        $.post('/api/live/getMyListByDate',data,cb,'json');
    },
    createDetail: function (data,cb) {
        $.post('/api/live/create',data,cb,'json');
    },
    updateDetail: function (data,cb) {
        $.post('/api/live/update',data,cb,'json');
    },
    deleteDetail: function (data,cb) {
        $.post('/api/live/delete',data,cb,'json');
    },
    getGroupList: function (data,cb) {
        $.post('/api/group/getMyGroup',data,cb,'json');
    },
    getInLiveList: function (data,cb){
        $.post('/api/live/getInLiveList',data,cb,'json');
    },
    getFilesList: function (data,cb) {
        $.post('/api/file/getFiles',data,cb,'json');
    },
    getFileNameByIds:function(data,cb){
        $.post('/api/file/getFileNameByIds',data,cb,'json');
    }
};