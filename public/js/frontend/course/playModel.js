/**
 * Created by sherry on 2016/6/6.
 */
var PlayModel={
    getLessonList: function (data,cb) {
        $.post('/api/lesson/getLessonListDetail',data,cb,'json');
    },
    courseComment: function (data,cb) {
        $.post('/api/lesson/commentLesson',data,cb,'json');
    },
    noteLesson: function (data,cb) {
        $.post('/api/lesson/note',data,cb,'json');
    },
    askLesson: function (data,cb) {
        $.post('/api/lesson/ask',data,cb,'json');
    },
    getLessonFilePath: function (data,cb) {
        $.post('/api/lesson/getLessonFilePath',data,cb,'json');
    },
    answer: function (data,cb) {
        $.post('/api/lesson/postAnswer',data,cb,'json');
    },
    lessonQuestion: function (data,cb) {
        $.post('/api/lesson/lessonQuestion',data,cb,'json');
    },
    download:function(data){
        $.post('/frontend/file/downloadFiles',data,function(serverData){
            if(serverData.code === 0){
                $("#contextMenu").stop().fadeOut(200);
                var dlUrl = serverData.data;
                window.location.href = dlUrl;
            }
        },'json');

    }
};
