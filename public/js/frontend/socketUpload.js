/**
 * Created by 20150112 on 2016/3/14.
 */
/*socket upload*/
$(function(){
    var UploadFiles = {};
    var userID = $('#js-user-name').data('uid');
    var user_token = $('#js-user-name').data('token');
    $('#choose-file').on('change', function () {
        var files = document.getElementById('choose-file').files;
        $('#file_uploading').empty();
        $('#choose-file').attr('disabled',true);
        if($('#choose-file').attr('disabled') == 'disabled'){
            $(window).on('beforeunload',function(){return '文件上传还没有结束，确认终止？';});
        };
        //初始化上传
        $('.countFile').text(files.length);
        $('.count_chat_file').text(0);
        $(".count_chat_min").html("-");
        $(".small-chat-box.fadeInRight.animated ").stop().css({width:"500px",height:"320px",bottom:'0'});
        $(".slim_scroll_div_wrap").show();
        $(".count_chat_min").removeClass("count_chat_max");
        //
        $.each(files,function() {
            UploadFiles[this.name] = { // define storage structure
                currentFile: this,
                currentFileReader: null
            };
            get_file_md5(UploadFiles[this.name].currentFile);
        });
    });
    $(".count_chat_close").on("click",function(){
       $(".small-chat-box.fadeInRight.animated ").animate({bottom:"-320px"})
    });
    $(".count_chat_min").on("click",function(){
       if($(this).hasClass("count_chat_max")){
         $(this).html("-");
         $(this).removeClass("count_chat_max");
         $(".small-chat-box.fadeInRight.animated ").stop().animate({width:"500px",height:"320px"})
         $(".slim_scroll_div_wrap").show();
       }
       else{
        $(this).html("+");
        $(".small-chat-box.fadeInRight.animated ").stop().animate({width:"200px",height:"50px"})
        $(".slim_scroll_div_wrap").hide();
        $(this).addClass("count_chat_max");
       }
    });
    //var socket = io.connect('http://192.168.1.200:3000');
    var socket = io.connect('http://edu.iguangj.com:3000');

    function start_upload(currentFile){
        if (currentFile) {
            var folderDir = $('.inner_body .detail_body_wrapper').attr('level')+'/';
            var FileMd5 = $('#'+hex_md5(currentFile.name)).val();
            UploadFiles[currentFile.name].currentFileReader = new FileReader();
            UploadFiles[currentFile.name].currentFileReader.onload = function (evnt) {
                socket.emit('upload', {
                    'Name': currentFile.name,
                    'Segment': evnt.target.result
                });
            };
            socket.emit('start', {
                'uid':userID,
                'user_token':user_token,
                'FileMd5':FileMd5,
                'folder':folderDir,
                'file_from':0,
                'Name':currentFile.name,
                'Size':currentFile.size
            });
        }
    }

    socket.on('moreData', function (data) {
        updateProgressBar(data,data.percent);
        var position = data.position;
        var newFile = null;
        if (UploadFiles[data.name].currentFile.slice)
            newFile = UploadFiles[data.name].currentFile.slice(position, position + Math.min(524288, UploadFiles[data.name].currentFile.size - position));
        else if (UploadFiles[data.name].currentFile.webkitSlice)
            newFile = UploadFiles[data.name].currentFile.webkitSlice(position, position + Math.min(524288, UploadFiles[data.name].currentFile.size - position));
        else if (UploadFiles[data.name].currentFile.mozSlice)
            newFile = UploadFiles[data.name].currentFile.mozSlice(position, position + Math.min(524288, UploadFiles[data.name].currentFile.size - position));
        if (newFile)
            UploadFiles[data.name].currentFileReader.readAsBinaryString(newFile); // trigger upload event
    });

    socket.on('done', function (data) {
        delete UploadFiles[data.name].currentFileReader;
        delete UploadFiles[data.name].currentFile;
        updateProgressBar(data,100);
        /*下载完获取当前目录文件，不刷新*/
        getCurrentDir();
        FILEMODEL.getUserCapacity(); //获取容量
    });

    socket.on('err', function (data) {
        if(data.code == 0){
            layer.msg(data.msg);
            $(".upload-status").text('上传失败').css("color","f00");
        }
        else if(data.code == 1){
            layer.msg(data.msg);
            $("input[value="+data.FileMd5+"]").next().find(".upload-status").text('上传失败').attr('style','color:#f00');
        }
        $('#choose-file').val('');
        $('#choose-file').attr('disabled',false);
    });

    function get_file_md5(f){
        var fileReader = new FileReader(),
            blobSlice = File.prototype.mozSlice || File.prototype.webkitSlice || File.prototype.slice,chunkSize = 2097152,
            chunks = Math.ceil(f.size / chunkSize), currentChunk = 0, spark = new SparkMD5();

        $('#file_uploading').append('<li id="'+ hex_md5(f.name)+'_li"><input type="hidden" id="'+ hex_md5(f.name)+'" /><p><span class="col-1 ellipsis">'+ f.name+'</span><span class="small col-2">'+ (f.size/(1024*1024)).toFixed(2)+'M</span><span class="small col-3 m-t-xs upload-status" id="'+ hex_md5(f.name)+'file_msg" >文件扫描中</span></p>'
            +'<div class="progress progress-small">'
            +' <div id="'+ hex_md5(f.name)+'pos" style="width: 0%;" class="progress-bar"></div>'
            +' </div>'
            +'</li>');
        $('#small-chat i').removeClass('fa-folder').addClass('fa-remove');


        //$('.small-chat-box').addClass('active');
        fileReader.onload = function(e) {
            spark.appendBinary(e.target.result);
            // append binary string
            currentChunk++;
            if (currentChunk < chunks) {
                loadNext();
            } else {
                $('#'+hex_md5(f.name)).val(spark.end());
                $('#'+hex_md5(f.name)+'file_msg').text('等待上传').attr('style','color:green');
                start_upload(f);
            }
        };
        function loadNext() {
            var start = currentChunk * chunkSize, end = start + chunkSize >= f.size ? f.size : start + chunkSize;
            fileReader.readAsBinaryString(blobSlice.call(f, start, end));
        }
        loadNext();
    }

    function updateProgressBar(data,pos){
        $('#'+hex_md5(data.name)+'pos').attr('style','width:'+pos+'%');
        $('#'+hex_md5(data.name)+'file_msg').text('开始上传:'+pos+'%').attr('style','color:green');
        if(pos==100){
            $('#choose-file').val('');
            $('#choose-file').attr('disabled',false);
            $('#'+hex_md5(data.name)+'file_msg').text('上传结束');
            //$('#'+hex_md5(data.name)+'_li').fadeOut(3000);
            /*
             $('.i-checks').iCheck({
             checkboxClass: 'icheckbox_square-green',
             radioClass: 'iradio_square-green',
             });
             */
            //$("span.pie").peity("pie", {
            //    fill: ['#1ab394', '#d7d7d7', '#ffffff']
            //});

            //$('#'+hex_md5(data.name)+'_li').remove();
            $('#small-chat i').removeClass('fa-remove').addClass('fa-folder');
            var countFile =  parseInt($('.count_chat_file').text());
            $('.count_chat_file').text((countFile+1));
            if(parseInt($('.count_chat_file').text()) === parseInt($('.countFile').text())){
                $('.countState').html('上传完成');
                $(window).off('beforeunload');
                $(".count_chat_min").html("+");
                $(".small-chat-box.fadeInRight.animated ").stop().animate({width:"200px",height:"50px"})
                $(".slim_scroll_div_wrap").hide();
                $(".count_chat_min").addClass("count_chat_max");

                //var timeout = setTimeout(function(){
                //    clearTimeout(timeout);
                //    $(".small-chat-box.fadeInRight.animated ").animate({bottom:"-320px"});
                //    $('#file_uploading').empty();
                //    $(".count_chat_min").html("-");
                //    $(".count_chat_min").removeClass("count_chat_max");
                //    $(".small-chat-box.fadeInRight.animated ").attr("style",'')
                //    $(".slim_scroll_div_wrap").show();
                //},5000);
            }
        }
    }
    //上传列表滚动条
    $("#file_uploading").slimscroll({
        height: '100%',
        size:'5px',
        color:'#e7ebee',
        distance:'5px',
    });
});

