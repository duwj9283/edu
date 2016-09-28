{% extends "template/basic.volt" %}
{% block pageCss %}
{% endblock %}
{% block content %}
    <div id="container" ></div>
    <input type="button" class="player-play" value="播放1" />
    <input type="button" class="player-play2" value="播放2" />
    <input type="button" class="player-stop" value="停止" />
    <input type="button" class="player-status" value="取得状态" />
    <input type="button" class="player-current" value="当前播放秒数" />
    <input type="button" class="player-goto" value="转到第30秒播放" />
    <input type="button" class="player-length" value="视频时长(秒)" />
{% endblock %}
{% block pageJs %}
    {{ javascript_include("/3rdpart/jwplay/jwplayer.js") }}
    <script type="text/javascript">
        var playerInstance;  //保存当前播放器以便操作
        $(function() {
            jwplayer.key="O4G/7OoH6r9ioOg0VZQ1Ptmr+rAfP9BNQQzQYQ==";
            playerInstance = jwplayer('container').setup({
                file: 'rtmp://www.ahlll.net:1938/vod/mp3:21/236/2.mp3',
//                file: 'rtmp://123.59.146.75/vod/flv:21/236/sample.flv',
                image: "/api/source/getImageThumb/148/260/260",
                width: 500,
                height: 500,
                autostart:true,
                skin: {
                    name: "vapor"
                }
            });

            //flashplayer: '/3rdpart/jwplay/jwplayer.flash.swf',
            //file: 'rtmp://10.19.134.221/vod/mp4:9/选秀曝大批灵魂歌姬 歌声甜美直指梦想接班人 38_高清.mp4',
            //file: 'rtmp://10.19.134.221/vod/mp3:9/周传雄 - 冬天的秘密.mp3',
            //file: 'rtmp://ahlll.net:5535/live/1234',//直播
            //file: 'rtmp://10.19.134.221/vod/flv:21/236/sample.flv',//录播1


            /*动态播放*/
            $('.player-play').on('click', function () {
                playerInstance.load([{
                    file:"/3rdpart/jwplay/testvideo/4.mp4",
                    image: "/img/frontend/live/live01.png",
                }]);
            });
            /*列表播放*/
            $('.player-play2').on('click', function () {
                playerInstance.load([
                    {
                        file:"3rdpart/jwplay/testvideo/5.mp4",
                        image: "/img/frontend/live/live02.png",
                    },
                    {
                        file:"3rdpart/jwplay/testvideo/1.mp4",
                        image: "/img/frontend/live/live02.png",
                    }
                ]);
                playerInstance.play();
            });


            /*播放完成事件*/
            playerInstance.on('complete',function(){
                //alert(playerInstance.getPosition());
            });
            //停止
            $('.player-stop').click(function() { playerInstance.stop(); });

            //获取状态
            $('.player-status').click(function() {
                var state = playerInstance.getState();
                var msg;
                switch (state) {
                    case 'BUFFERING':
                        msg = '加载中';
                        break;
                    case 'PLAYING':
                        msg = '正在播放';
                        break;
                    case 'PAUSED':
                        msg = '暂停';
                        break;
                    case 'IDLE':
                        msg = '停止';
                        break;
                }
                alert(msg);
            });

            //获取播放进度
            $('.player-current').click(function() { alert(playerInstance.getPosition()); });

            //跳转到指定位置播放
            $('.player-goto').click(function() {
                if (playerInstance.getState() != 'PLAYING') {    //若当前未播放，先启动播放器
                    playerInstance.play();
                }
                playerInstance.seek(30); //从指定位置开始播放(单位：秒)
            });

            //获取视频长度
            $('.player-length').click(function() { alert(playerInstance.getDuration()); });
        });
    </script>
{% endblock %}