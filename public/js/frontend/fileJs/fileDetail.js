/**
 * Created by 20150112 on 2016/5/4.
 */
$(function(){
    FILEDETAIL.init();
    FILEDETAIL.byBind();
    /*收藏文件*/
    $('.operate .collect').on('click', function () {
        var fileId = $('#file_info_container').data('id');
        FILEDETAIL.collectFile(fileId);
    });
    /*下载文件*/
    $('.operate .down').on('click', function () {
        var fileId = $('#file_info_container').data('id');
        FILEDETAIL.download(fileId);
    });
    /*评论*/
    $('.file-comment').on('click', function () {
        var refUid = 0;
        var refId = 0;
        var content = $('.file-comment-input').val();
        if(!content){
            layer.tips('评论不能为空','.file-comment-input',{
                tips:[3,'#2cb07e'],
            });
            return false;
        }
        FILEDETAIL.fileComment(refUid,refId,content);
    });

    /*取消回复*/
    $('body').on('click', function(event) {
        var ifRm =true;
        var evt = event.srcElement ? event.srcElement : event.target;             // IE支持 event.srcElement ， FF支持 event.target
        if($(evt).hasClass('replay-row-container') || $(evt).hasClass('reply-comment-input') || $(evt).hasClass('btn-reply-comment')){
            ifRm=false;
        }
        if(ifRm){
            $('.replay-row-container').remove();
        }
    });
    //文档预览

    $.post('/api/file/filePreview/'+$('.js-ppt').data('fileid'),{},function(data){
        if(data.code==0){
            var total=data.data.total;
            var aImages=data.data.images;
            var iPicIndex=$('#txtPictureIndex').val();
            $('.js-ppt .ppt-con img:first').remove();
            $('.js-ppt .ppt-con').append('<img src="'+aImages[0]+'" class="img js-img"  alt="">');
            var oImg=$('.js-ppt .js-img');
            var iHeight=null;
            oImg.load(function(){
                $(this).show();
                iHeight=$(this).height();
                $('.loading').hide();
            });
            //回车跳转
            $('#txtPictureIndex').on("keypress",function(event){
                if(event.keyCode == "13"){
                    iPicIndex = $.trim($(this).val());
                    showImg();
                }
            });
            $('.js-ppt .js-total').text(total);
            function showImg(){
                oImg.attr('src',aImages[iPicIndex-1]);
                $('.loading').css('height',iHeight).show();
                oImg.css('display','none');
                oImg.load(function(){
                    $(this).css('display','block');
                    iHeight=$(this).height();
                    $('.loading').hide();
                });
            }
            function pptToRight(){
                if(iPicIndex >= total){
                    return false;
                }
                iPicIndex++;
                $('#txtPictureIndex').val(iPicIndex);
                showImg();
            }
            function pptToLeft(){
                if(iPicIndex <= 1){
                    return false;
                }
                iPicIndex--;
                $('#txtPictureIndex').val(iPicIndex);
                showImg();
            }
            $('.js-ppt .pre,.js-ppt .next').on('click',function(){
                if($(this).hasClass('pre')){
                    pptToLeft();
                }
                else if($(this).hasClass('next')){
                    pptToRight();
                }
            });
            $(".js-ppt").off('mousemove,click','.js-img');
            $(".js-ppt").on('mousemove','.js-img',function(e) {
                var positionX = e.pageX - $(this).offset().left || 0;//获取当前鼠标相对img的x坐标
                if(positionX<=$(this).width()/2){
                    $(this).addClass('to-left').removeClass('to-right');
                }
                else{
                    $(this).addClass('to-right').removeClass('to-left')
                }
            });
            $(".js-ppt").on('click','.js-img',function(e) {
                var positionX = e.pageX - $(this).offset().left || 0;//获取当前鼠标相对img的x坐标
                if(positionX<=$(this).width()/2){
                    pptToLeft();
                }
                else{
                    pptToRight();
                }
            });
        }
        else{
            $('.js-ppt').html('<div class="text-center">'+data.msg+'</div>');
        }
    },'json');


});