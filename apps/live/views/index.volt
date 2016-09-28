<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>INSPINIA | Dashboard</title>
    {{ stylesheet_link("3rdpart/bootstrap/css/bootstrap.min.css") }}
    {{ stylesheet_link("3rdpart/font-awesome/css/font-awesome.css") }}
    {{ stylesheet_link("3rdpart/animate/animate.css") }}
    {{ stylesheet_link("css/backend/style.css") }}
    <!-- Toastr style -->
    {{ stylesheet_link("3rdpart/toastr/toastr.min.css") }}
</head>
<body>
<div id="wrapper">
    <?php echo $this->getContent(); ?>
</div>

<!-- Mainly scripts -->
{{ javascript_include("3rdpart/jquery/jquery.min.js") }}
{{ javascript_include("3rdpart/bootstrap/js/bootstrap.min.js") }}
{{ javascript_include("3rdpart/metisMenu/jquery.metisMenu.js") }}
{{ javascript_include("3rdpart/slimscroll/jquery.slimscroll.min.js") }}

<!-- Peity -->
{{ javascript_include("3rdpart/peity/jquery.peity.min.js") }}
{{ javascript_include("js/backend/peity-demo.js") }}

<!-- Custom and plugin javascript -->
{{ javascript_include("js/backend/inspinia.js") }}
{{ javascript_include("3rdpart/pace/pace.min.js") }}

<!-- iCheck -->
{{ javascript_include("3rdpart/iCheck/icheck.min.js") }}
<!-- Toastr -->
{{ javascript_include("3rdpart/toastr/toastr.min.js") }}

{{ javascript_include("js/backend/socket.io.js") }}
{{ javascript_include("js/backend/md5.js") }}
{{ javascript_include("js/backend/spark-md5.js") }}

<script>
    $(document).ready(function() {
        $('.i-checks').iCheck({
            checkboxClass: 'icheckbox_square-green',
            radioClass: 'iradio_square-green',
        });
    });
</script>

<script type="text/javascript">
    var UploadFiles = {};
    $('#choose-file').on('change', function () {
        var files = document.getElementById('choose-file').files;
        $('#choose-file').attr('disabled',true);
        $.each(files,function() {
            UploadFiles[this.name] = { // define storage structure
                currentFile: this,
                currentFileReader: null
            };
            get_file_md5(UploadFiles[this.name].currentFile);
        });
    });

    var socket = io.connect('http://www.ahlll.net:3000');
    function start_upload(currentFile,FileMd5)
    {
        if (currentFile) {
            UploadFiles[currentFile.name].currentFileReader = new FileReader();
            UploadFiles[currentFile.name].currentFileReader.onload = function (evnt) {
                socket.emit('upload', {
                    'Name': currentFile.name,
                    'Segment': evnt.target.result
                });
            };
            socket.emit('start', {
                'uid':{{ userInfo['uid'] }},
                'user_token':'{{ userInfo['user_token'] }}',
                'FileMd5':FileMd5,
                'folder':'/',
                'file_from':0,
                'file_type':4,
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
    });

    function get_file_md5(f){
        $('#file_uploading').append('<li id="'+ hex_md5(f.name)+'_li"><input type="hidden" id="'+ hex_md5(f.name)+'" /><div class="small pull-right m-t-xs">'+ (f.size/(1024*1024)).toFixed(2)+'M</div><h4>'+ f.name+'</h4>'
                +'<div class="small"><span id="'+ hex_md5(f.name)+'file_msg" style="color: #f00">文件扫描中<img width="16" height="16" src="/public/img/backend/loading.gif"></span></div>'
                +'<div class="progress progress-small">'
                +' <div id="'+ hex_md5(f.name)+'pos" style="width: 0%;" class="progress-bar"></div>'
                +' </div>'
                +'</li>');
        $('#small-chat i').removeClass('fa-folder').addClass('fa-remove');
        $('.small-chat-box').addClass('active');
        var fileReader = new FileReader(),
                blobSlice = File.prototype.mozSlice || File.prototype.webkitSlice || File.prototype.slice,chunkSize = 2097152,
                chunks = Math.ceil(f.size / chunkSize), currentChunk = 0, spark = new SparkMD5();
        fileReader.onload = function(e) {
            spark.appendBinary(e.target.result);
            currentChunk++;
            if (currentChunk < chunks) {
                loadNext();
            } else {
                var FileMd5 = spark.end();
                //查询文件上传进度
                var _datas = {uid:{{ userInfo['uid'] }},user_token:'{{ userInfo['user_token'] }}',file_md5:FileMd5};
                var _urls = '/backend/file/checkFilePosition';
                $.ajax({
                    type:'GET',
                    url: _urls,
                    dataType:'json',
                    data:_datas,
                    success:function(data){
                        if(data.status)
                        {
                            $('#'+hex_md5(f.name)).val(FileMd5);
                            $('#'+hex_md5(f.name)+'file_msg').text('开始上传').attr('style','color:green');
                            start_upload(f,FileMd5);
                        }
                        else
                        {
                            toastr.error(data.msg, '警告');
                            $('#'+hex_md5(f.name)+'_li').remove();
                            $('#choose-file').val('');
                            $('#choose-file').attr('disabled',false);
                            return false;
                        }
                    },
                    error:function(xhr,status,error){
                        toastr.error(error,'警告');
                    }
                });
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
            $('#'+hex_md5(data.name)+'_li').fadeOut(3000);
            $.get("/backend/ajax/filesLog",{name:data.name,size:data.size,md5:data.md5},function(result){
                $('#upload_files').after(result);
                $('.i-checks').iCheck({
                    checkboxClass: 'icheckbox_square-green',
                    radioClass: 'iradio_square-green',
                });
                $("span.pie").peity("pie", {
                    fill: ['#1ab394', '#d7d7d7', '#ffffff']
                });
                var timeout = setTimeout(function(){
                    $('#'+hex_md5(data.name)+'_li').remove();
                    clearTimeout(timeout);
                    $('#small-chat i').removeClass('fa-remove').addClass('fa-folder');
                    $('.small-chat-box').removeClass('active');
                },3000);
            });
        }
    }
</script>
</body>
</html>
