
/**
 * 习题js-model
 * Created by sherry on 2016/4/20.
 */

var examModel={
    getList: function (data,cb) {
        $.post('/api/question/getQuestionList',data,cb,'json');
    },
    addDetail: function (data,cb) {
        $.post('/api/question/addQuestion',data,cb,'json');
    },
    deleteDetail: function (data,cb) {
        $.post('/api/question/delQuestion',data,cb,'json');
    }
};