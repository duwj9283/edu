/**
 * Created by 20150112 on 2016/3/24.
 */
var webUploader =$.extend({
    init:function(option){
        if(option){
            if(option.pick){
                var pick = option.pick;
            }else{
                var pick = '#filePicker';
            }
            if(option.label){
                var innerLabel = option.label;
            }else{
                var innerLabel = '点击上传';
            }
            if(option.thumbSize && option.thumbSize){
                var thumbW = option.thumbSize.width;
                var thumbH = option.thumbSize.height;
            }else{
                var thumbW = 100;
                var thumbH = 100;
            }
        }else{
            var pick = '#filePicker';
            var innerLabel = '点击上传';
            var thumbW = 100;
            var thumbH = 100;
        }

        var accept = $.extend({
            title: 'Images',
            extensions: 'gif,jpg,jpeg,bmp,png',
            mimeTypes: 'image/*'
        },option.accept);

        var serverCustom = $.extend({url:'/source/uploadPic',type:'pic'},option.serverCustom);
        var $list = $('#fileList');
        //var uploader = WebUploader.create({
        var uploader = new WebUploader.Uploader({
            // 选完文件后，是否自动上传。
            auto: true,
            //强制使用flash模式
            //runtimeOrder:'flash',
            // swf文件路径
            swf:'/js/frontend/webUpload/Uploader.swf' ,
            // 文件接收服务端。
            server:serverCustom.url,
            pick: {
                id:pick,
                innerHTML:innerLabel,
                multiple:false
            },
            //上传的参数
            formData:{},
            //压缩
            compress:false,
            //是否重复上传
            duplicate: true,
            // 只允许选择w文件类型。
            accept: accept,
        });
        uploader.on( 'fileQueued', function( file ) {
            var $li = $(
                    '<div id="' + file.id + '" class="file-item thumbnail">' +
                    '<img>' +
                    '</div>'
                ),
                $img = $li.find('img');

            // $list为容器jQuery实例
            $list.html( $li );
            // 创建缩略图
            // 如果为非图片文件，可以不用调用此方法。
            // thumbnailWidth x thumbnailHeight 为 100 x 100
            uploader.makeThumb( file, function( error, src ) {
                if ( error ) {
                    $img.replaceWith('<span>不能预览</span>');
                    return;
                }

                $img.attr( 'src', src );
            }, thumbW, thumbH );
        });
// 文件上传过程中创建进度条实时显示。
        uploader.on( 'uploadProgress', function( file, percentage ) {
            var $li = $( '#'+file.id ),
                $percent = $li.find('.webUpload-progress span');
            // 避免重复创建
            if ( !$percent.length ) {
                $percent = $('<p class="webUpload-progress"><span></span></p>')
                    .appendTo( $li )
                    .find('span');
            }

            $percent.css( 'width', percentage * 100 + '%' );
        });

// 文件上传成功，给item添加成功class, 用样式标记上传成功。
        uploader.on( 'uploadSuccess', function( file,response ) {
            if(serverCustom.type == 'excel-model'){
                //如果上传的是excel，处理方式：
                var fileName = response.data.name;
                $(pick).data('fileName',fileName);
                layer.msg('上传成功',{
                        icon: 1,
                        time: 1000
                });
            }else{
                //如果上传的是其他，处理方式：
                $( '#'+file.id ).addClass('upload-state-done');
                var picSrc = response.data.name;
                $('#fileList').data('picSrc',picSrc);
            }
        });

// 文件上传失败，显示上传出错。
        uploader.on( 'uploadError', function( file ) {
            var $li = $( '#'+file.id ),
                $error = $li.find('div.error');
            // 避免重复创建
            if ( !$error.length ) {
                $error = $('<div class="error"></div>').appendTo( $li );
            }

            $error.text('上传失败');
        });

// 完成上传完了，成功或者失败，先删除进度条。
        uploader.on( 'uploadComplete', function( file ) {
            $( '#'+file.id ).find('.webUpload-progress').remove();
        });
    }
});





