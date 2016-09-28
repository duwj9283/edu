/* 展开我的消息内容 */
var newsModel={
    /*添加文章*/
    editNews:function(data,cb){
        $.post('/api/news/editNews',data,cb,'json');
    },
}
$(function(){
    //编辑器
    var ue_config={
        initialFrameWidth : '100%',
        initialFrameHeight: 600,
        elementPathEnabled:false,
        enableAutoSave:false,
        toolbars: [['fullscreen', 'source', 'undo', 'redo', 'bold', 'simpleupload']]
    };
    var ue= UE.getEditor('news-content',ue_config);

    var $form=$('form[name="news"]');
    $form.on("submit",function(){
        var $id = $('input[name="id"]'),$title = $('input[name="title"]'),$status=$('input[name="status"]');
        if(!$title.val()){
            layer.msg("请填写标题");
            $title.focus();
            return false;
        }
        newsModel.editNews($form.serialize(),function(serverData){
            console.log(serverData);
            if(serverData.code===0){
                layer.msg('创建成功');
                window.location.href='/user/news    ';
            }
            else{
                layer.msg(serverData.msg);
            }
        })
    });
});

