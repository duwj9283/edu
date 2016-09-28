
/**
 * 用户js-model
 * Created by sherry on 2016/4/28.
 */

var userModel={
    updateInfo: function (data,cb) {
        $.post('/api/user/updateInfo',data,cb,'json');
    }
};