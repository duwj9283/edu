/**
 * Created by 20150112 on 2016/3/14.
 */
/*socket upload*/
$(function(){
    var UploadFiles = {};
    var userID = $('.user_info_wrap .user_name').attr('data-uid');
    var user_token = $('.user_info_wrap #token').attr('token');
    var $chooseType='';
    /*上传课件*/
    $('#choose-kj').on('change', function () {
        //$chooseType = $(this).parents('#file-select-popover').attr('choose-type');
        $('#transparent-cover').show();
        var files = document.getElementById('choose-kj').files;
        $('#choose-kj').attr('disabled',true);
        //$('#choose-zl').attr('disabled',true);
        $('.count_chat_file').find('span').text(files.length);
        $(".small-chat-box.fadeInRight.animated ").animate({bottom:"0px"});
        $.each(files,function() {
            UploadFiles[this.name] = { // define storage structure
                currentFile: this,
                currentFileReader: null
            };
            get_file_md5(UploadFiles[this.name].currentFile);
        });
    });
    /*上传资料*/
    /*$('#choose-zl').on('change', function () {
        $chooseType = 'zl';
        $('#transparent-cover').show();
        var files = document.getElementById('choose-zl').files;
        $('#choose-zl').attr('disabled',true);
        $('#choose-kj').attr('disabled',true);
        $('.count_chat_file').find('span').text(files.length);
        $(".small-chat-box.fadeInRight.animated ").animate({bottom:"0px"});

        $.each(files,function() {
            UploadFiles[this.name] = { // define storage structure
                currentFile: this,
                currentFileReader: null
            };
            get_file_md5(UploadFiles[this.name].currentFile);
        });
    });*/

    //var socket = io.connect('http://192.168.5.200:3000');
    var socket = io.connect('http://edu.iguangj.com:3000');

    function start_upload(currentFile){
        if (currentFile) {
            var folderDir = '/';
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
        $('#transparent-cover').hide();
        updateProgressBar(data,100);
        /*添加元素并绑定时间*/
        data.file_name = data.name;
        data.id = data.file_id;
        var $htmlStr = $('<li class="ellipsis" file="'+data.id+'">'+data.name+'</li>');
        $('#file-select-popover ul.file_list').append($htmlStr);
        $htmlStr.data('file',data);
        $('#file-select-popover ul.file_list li').unbind();
        $('#file-select-popover ul.file_list li').on('click', function () {
            /*
             * 先判断是否是目录，是目录则展开，不是则选中
             * */
            if(!$(this).hasClass('folder')){
                if($('#file-select-popover').hasClass('multiple')){
                    $(this).toggleClass('selected');
                }else{
                    $('#file-select-popover ul.file_list li.selected').removeClass('selected');
                    $(this).addClass('selected');
                }
            }
        });
    });

    function get_file_md5(f){
        var fileReader = new FileReader(),
            blobSlice = File.prototype.mozSlice || File.prototype.webkitSlice || File.prototype.slice,chunkSize = 2097152,
            chunks = Math.ceil(f.size / chunkSize), currentChunk = 0, spark = new SparkMD5();

        $('#file_uploading').append('<li id="'+ hex_md5(f.name)+'_li"><input type="hidden" id="'+ hex_md5(f.name)+'" /><div class="small pull-right m-t-xs">'+ (f.size/(1024*1024)).toFixed(2)+'M</div><h4>'+ f.name+'</h4>'
            +'<div class="small"><span id="'+ hex_md5(f.name)+'file_msg" style="color: #f00">文件扫描中<img width="16" height="16" src="/public/img/backend/loading.gif"></span></div>'
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
                $('#'+hex_md5(f.name)+'file_msg').text('开始上传').attr('style','color:green');
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
        //console.log(data);
        $('#'+hex_md5(data.name)+'pos').attr('style','width:'+pos+'%');
        $('#'+hex_md5(data.name)+'file_msg').text('开始上传:'+pos+'%').attr('style','color:green');
        if(pos==100){
            $('#choose-kj').val('');
            $('#choose-kj').attr('disabled',false);
            $('#choose-zl').val('');
            $('#choose-zl').attr('disabled',false);

            $('#'+hex_md5(data.name)+'file_msg').text('上传结束');
            //$('#'+hex_md5(data.name)+'_li').fadeOut(3000);
            $('#upload_files').after('上传成功');
            $('.i-checks').iCheck({
                checkboxClass: 'icheckbox_square-green',
                radioClass: 'iradio_square-green',
            });
            $("span.pie").peity("pie", {
                fill: ['#1ab394', '#d7d7d7', '#ffffff']
            });

            //$('#'+hex_md5(data.name)+'_li').remove();
            $('#small-chat i').removeClass('fa-remove').addClass('fa-folder');

            var countFile =  parseInt($('.count_chat_file').find('span').text());
            $('.count_chat_file').find('span').text((countFile-1));
            if(parseInt($('.count_chat_file').find('span').text()) === 0){
                var timeout = setTimeout(function(){
                    clearTimeout(timeout);
                    $(".small-chat-box.fadeInRight.animated ").animate({bottom:"-320px"});
                    $('#file_uploading').empty();
                },3000);
            }
        }
    }
});

