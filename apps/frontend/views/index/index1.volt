<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>独角兽教育平台</title>
        {{ stylesheet_link("3rdpart/bootstrap/css/bootstrap.min.css") }}
        {{ stylesheet_link("3rdpart/simple-slider/css/simple-slide.css") }}
        {{ stylesheet_link("css/frontend/home.css") }}
    </head>
    <style>
        i{cursor:pointer;}
        .carousel{text-align:center;min-height:100px;border-bottom: 8px solid #f1f1f1;}
        .carousel_wrap{width:50%;min-width:950px;text-align:center;margin: 0 auto;padding:50px 20px;position:relative;}
        .carousel_wrap:after{content:'\200B';display:block;height:0;clear:both;}
        .carousel_header{width:25%;height:310px;float:left;text-align:center;margin: 0 auto;border-radius:5px;}
        .carousel_header img{margin-top:40%;}
        .carousel .carousel-inner{width:71%;min-width:645px;float:left;text-align:center;margin: 0 auto;overflow:hidden;margin-left:32px;}
        .carousel-inner .item:after{content:'\200B';display:block;height:0;clear:both;}
        .carousel-inner .item .item_detail{width:47%;height:310px;border:1px solid #e0e0e0;float:left;border-radius:5px;}
        .detail_part{margin-left:5%;}
        .carousel .left.carousel-control{left:-9%;background-image:url('img/frontend/home/arrow_left.png?v=1');background-repeat: no-repeat;background-size:65px 65px;background-color:#fff;}
        .carousel .right.carousel-control{right:-9%;background-image:url('img/frontend/home/arrow_right.png?v=1');background-repeat: no-repeat;background-size:65px 65px;}
        .right.carousel-control{background-image:url('img/frontend/home/arrow_right.png?v=1');}

        .carousel-control.left,.carousel-control.right{width:65px;height:65px;filter:none; }
        .item_detail{text-align:left;overflow:hidden;color:#626574;}
        .item_detail_img_wrap{width:100%;height:240px;overflow:hidden;position:relative;}
        .item_detail img{width:100%;}
        .item_detail p{padding:20px;}
        .cover_layer_wrap{position:absolute;width:100%;height:240px;top:0px;left:0px;}
        .opacity_layer{width:100%;height:240px;opacity:0.5;background-color:black;}
        .opacity_layer_white{width:100%;height:240px;top:-240px;opacity:0.9;filter:alpha(opacity=90);background-color:#fff;}
        .cover_btn{width:75px;height:75px;text-align:center;position:absolute;z-index:10;top: 35%;left: 45%;cursor:pointer;background:url('img/frontend/home/btn_play_g.png?v=2') no-repeat;}
        .video_info_wrap{width:100%;}
        .video_info_wrap:after{content:'\200B';display:block;height:0;clear:both;}
        .video_info_wrap .author_info{width:40%;height:70px;float:left;}
        .video_info_wrap .video_download_info{width:30%;height:70px;float:right;}
        .item_detail  .author_header{width:40px;height:40px;border-radius:20px;margin:15px;}
        .item_detail .icon_down{width:19px;height:17px;display: inline-block;background:url('img/frontend/home/icon_download.png?v=1') no-repeat;}
        .video_time{margin-left:20px;}
        .pic_desc_wrap{padding:30px;}
        .title{width:40%;overflow:hidden;white-space:nowrap;text-overflow:ellipsis;}
        .pic_desc{height:200px;overflow:hidden;text-indent:2em;}
        .pic_name{width:100%;height:30px;padding:10px 25px;}
        .author_name{float:right;font-size:12px;}
        .download_info{width:100%;text-align:right;}
        .item_doc{padding: 0px 30px;}
        .doc_wrap{padding-top:55px;height:251px;text-align:center;border-bottom:1px solid #e0e0e0; position:relative;}
        .doc_wrap img{width:auto;}
        .doc_name{margin-top:25px;}
        .item_doc .author_info{width:100%;height:60px;line-height:60px;position: relative;text-align:center;}
        .item_doc .author_info span{display:inline-block;max-width:100px;}
        .item_doc .icon_down{position: absolute;top:20px;right:-15px;}
        .item_doc .doc_desc{padding: 30px 0px;}
        .item_doc .title{width: 100%;text-align: left;}
        .doc_pdf,.doc_word,.doc_xml,.doc_ppt{width:73px;height:81px;display:inline-block;}
    </style>
    <body>
        <div class="container_header">
            {% include "template/header.volt" %}
        </div>
        <div class="container_inner">
            <div id="myCarousel" class="carousel slide" data-interval='false'>
                <div class="carousel_wrap">
                    <div class="carousel_header" style="background-color:#ff8b8c;"><img src="img/frontend/home/gifts_zx.png?v=1"/></div>
                    <!-- 轮播（Carousel）项目 -->
                    <div class="carousel-inner">
                        <div class="item active">
                            <div class="item_detail" ><p>计算思维导论</p><hr/><p>从1971年到现在，被称之为“大规模集成电路计算机时代”。第四代计算机使用的元件依然是集成电路，不过，这种集成电路已经大大改善，它包含着几十万到上百万个晶体管，人们称之为大规模集成电路（LargeScale lntegrated Circuit，简称LSI）和超大规模集成电路（Very Large Scale lntegrated Circuit，简称VLSI）</p>
                            </div>
                            <div class="item_detail detail_part"><img  src="img/frontend/tem_material/vBuild.jpg?v=1" /></div>
                        </div>
                        <div class="item">
                            <div class="item_detail "><p style="color:#0e9aef;font-size:25px;font-weight: 700;">网络教育的未来时代</p><hr/><img  src="img/frontend/tem_material/sc2.jpg?v=2" /></div>
                            <div class="item_detail detail_part"><img  src="img/frontend/tem_material/sc1.jpg?v=1" /><p style="text-indent:2em;padding: 0px 20px 10px;">现代职业教育体系的构建也因此成为当前教育界，特别是职业教育界十分关心的热点问题；各地方政府和各类职业院校、地方应用本科院校一起也在进行职业教育各层次衔接试点工作</p></div>
                        </div>
                    </div>
                    <!-- 轮播（Carousel）导航 -->
                    <a class="carousel-control left" style="top:175px;" href="#myCarousel" data-slide="prev"></a>
                    <a class="carousel-control right" style="top:175px;" href="#myCarousel" data-slide="next"></a>
                </div>

            </div>
            <div id="myCarouselVideo" class="carousel slide video" data-interval='false'>
                <div class="carousel_wrap">
                    <div class="carousel_header" style="background-color:#ffca8c;"><img src="img/frontend/home/gifts_video.png?v=1"/></div>
                    <!-- 轮播（Carousel）项目 -->
                    <div class="carousel-inner">
                        <div class="item active">
                            <div class="item_detail" style="width:100%;">
                                <div class="item_detail_img_wrap">
                                    <img src="img/frontend/tem_material/sc4.jpg?v=1" />
                                    <div class="cover_layer_wrap">
                                        <div class="opacity_layer"></div>
                                        <div class="cover_btn"></div>
                                    </div>
                                </div>
                                <div class="video_info_wrap">
                                    <div class="author_info">
                                        <img class="author_header" src="img/frontend/tem_material/sc6.png?v=1">
                                        <label class="video_name" >最美不过夕阳红</label>
                                    </div>
                                    <div class="video_download_info">
                                        <i class="icon_down" style="margin-top:25px;"></i>
                                        <label style="margin-left:5px;"><sapn>下载：</sapn><span>928</span></label>
                                        <label class="video_time">1天前</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="item">
                            <div class="item_detail" style="width:100%;">
                                <div class="item_detail_img_wrap">
                                    <img src="img/frontend/tem_material/sc91.jpg?v=1" />
                                    <div class="cover_layer_wrap">
                                        <div class="opacity_layer"></div>
                                        <div class="cover_btn"></div>
                                    </div>
                                </div>
                                <div class="video_info_wrap">
                                    <div class="author_info">
                                        {#<img class="author_header" src="img/frontend/tem_material/sc91.jpg?v=1">#}
                                        {{ image("img/frontend/tem_material/sc91.jpg?v=1",'class':"author_header") }}
                                        <label class="video_name" >传统手工艺</label>
                                    </div>
                                    <div class="video_download_info">
                                        <i class="icon_down" style="margin-top:25px;"></i>
                                        <label style="margin-left:5px;"><sapn>下载：</sapn><span>98</span></label>
                                        <label class="video_time">1天前</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- 轮播（Carousel）导航 -->
                    <a class="carousel-control left" style="top:175px;" href="#myCarouselVideo" data-slide="prev"></a>
                    <a class="carousel-control right" style="top:175px;" href="#myCarouselVideo" data-slide="next"></a>
                </div>
            </div>
            <div id="myCarouselPic" class="carousel slide" data-interval='false'>
                <div class="carousel_wrap">
                    <div class="carousel_header" style="background-color:#a3d8c4;"><img src="img/frontend/home/gifts_pic.png?v=1"/></div>
                    <!-- 轮播（Carousel）项目 -->
                    <div class="carousel-inner">
                        <div class="item active">
                            <div class="item_detail">
                                <div class="item_detail_img_wrap">
                                    <img class="hover_controller" src="img/frontend/tem_material/sc6.png?v=1" />
                                    <div class="cover_layer_wrap opacity_layer_white pic_desc_wrap">
                                        <label class="title">创意海报</label>
                                        <p class="pic_desc">BY shuaiqi 当我登上高楼的时候，想要看到更高的风景，我必须要登上更高的楼</p>
                                    </div>
                                </div>
                                <div class="author_info">
                                    <label class="pic_name">
                                        <label>创意海报</label>
                                        <label class="author_name"><span>BY</span><sapn style="margin-left:5px;">周吴郑王</sapn></label>
                                    </label>
                                    <div class="download_info" style="padding:0px 20px;">
                                        <i class="icon_down"></i>
                                        <label style="font-size:12px;"><sapn>下载：</sapn><span>928</span></label>
                                    </div>
                                </div>
                            </div>
                            <div class="item_detail detail_part">
                                <div class="item_detail_img_wrap">
                                    <img class="hover_controller" src="img/frontend/tem_material/sc8.jpg?v=1" />
                                    <div class="cover_layer_wrap opacity_layer_white pic_desc_wrap">
                                        <label class="title">梦幻</label>
                                        <p class="pic_desc">我在烈日当空的时候在挥舞着锄头锄田，汗水都滴落到禾苗下的土地。</p>
                                    </div>
                                </div>
                                <div class="author_info">
                                    <label class="pic_name">
                                        <label>梦幻迎秋</label>
                                        <label class="author_name"><span>BY</span><sapn style="margin-left:5px;">春欢</sapn></label>
                                    </label>
                                    <div class="download_info" style="padding:0px 20px;">
                                        <i class="icon_down"></i>
                                        <label style="font-size:12px;"><sapn>下载：</sapn><span>9</span></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="item">
                            <div class="item_detail">
                                <div class="item_detail_img_wrap">
                                    <img class="hover_controller" src="img/frontend/tem_material/sc92.jpg?v=1" />
                                    <div class="cover_layer_wrap opacity_layer_white pic_desc_wrap">
                                        <label class="title">创意海报</label>
                                        <p class="pic_desc">BY shuaiqi 当我登上高楼的时候，想要看到更高的风景，我必须要登上更高的楼</p>
                                    </div>
                                </div>
                                <div class="author_info">
                                    <label class="pic_name">
                                        <label>创意海报</label>
                                        <label class="author_name"><span>BY</span><sapn style="margin-left:5px;">周吴郑王</sapn></label>
                                    </label>
                                    <div class="download_info" style="padding:0px 20px;">
                                        <i class="icon_down"></i>
                                        <label style="font-size:12px;"><sapn>下载：</sapn><span>928</span></label>
                                    </div>
                                </div>
                            </div>
                            <div class="item_detail detail_part">
                                <div class="item_detail_img_wrap">
                                    <img class="hover_controller" src="img/frontend/tem_material/sc93.jpg?v=1" />
                                    <div class="cover_layer_wrap opacity_layer_white pic_desc_wrap">
                                        <label class="title">梦幻</label>
                                        <p class="pic_desc">我在烈日当空的时候在挥舞着锄头锄田，汗水都滴落到禾苗下的土地。</p>
                                    </div>
                                </div>
                                <div class="author_info">
                                    <label class="pic_name">
                                        <label>梦幻迎秋</label>
                                        <label class="author_name"><span>BY</span><sapn style="margin-left:5px;">春欢</sapn></label>
                                    </label>
                                    <div class="download_info" style="padding:0px 20px;">
                                        <i class="icon_down"></i>
                                        <label style="font-size:12px;"><sapn>下载：</sapn><span>9</span></label>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <!-- 轮播（Carousel）导航 -->
                    <a class="carousel-control left" style="top:175px;" href="#myCarouselPic" data-slide="prev"></a>
                    <a class="carousel-control right" style="top:175px;" href="#myCarouselPic" data-slide="next"></a>
                </div>
            </div>
            <div id="myCarouselWord" class="carousel slide" data-interval='false'>
                <div class="carousel_wrap">
                    <div class="carousel_header" style="background-color:#8dddff;"><img src="img/frontend/home/gifts_word.png?v=1"/></div>
                    <!-- 轮播（Carousel）项目 -->
                    <div class="carousel-inner">
                        <div class="item active">
                            <div class="item_detail item_doc">
                                <div class="doc_wrap">
                                    <i class="hover_controller doc_pdf"></i>
                                    <p class="doc_name">论互联网思维做智能家居</p>
                                    <div class="cover_layer_wrap opacity_layer_white pic_desc_wrap">
                                        <label class="title">论互联网思维做智能家居</label>
                                        <p class="pic_desc doc_desc">未来是互联网的时代——亚摩斯提</p>
                                    </div>
                                </div>
                                <div class="author_info">
                                    <span class="ellipsis">BY</span>
                                    <span class="ellipsis">小小的梦想</span>
                                    <i class="icon_down"></i>
                                </div>
                            </div>
                            <div class="item_detail item_doc detail_part">
                                <div class="doc_wrap">
                                    <i class="hover_controller doc_word"></i>
                                    <p class="doc_name">论未来教育的发展趋势</p>
                                    <div class="cover_layer_wrap opacity_layer_white pic_desc_wrap">
                                        <label class="title">论未来教育的发展趋势</label>
                                        <p class="pic_desc doc_desc">教育也将且必将与时俱进——比奇盎德斯</p>
                                    </div>
                                </div>
                                <div class="author_info">
                                    <span class="ellipsis">BY</span>
                                    <span class="ellipsis">小小的梦想</span>
                                    <i class="icon_down"></i>
                                </div>
                            </div>
                        </div>
                        <div class="item">
                            <div class="item_detail item_doc">
                                <div class="doc_wrap">
                                    <i class="hover_controller doc_xml"></i>
                                    <p class="doc_name">黄冈名师精品课程</p>
                                    <div class="cover_layer_wrap opacity_layer_white pic_desc_wrap">
                                        <label class="title">黄冈名师精品课程</label>
                                        <p class="pic_desc doc_desc">学习是从在努力和勤奋的海水里获取的盐粒</p>
                                    </div>
                                </div>
                                <div class="author_info">
                                    <span class="ellipsis">BY</span>
                                    <span class="ellipsis">快快长大</span>
                                    <i class="icon_down"></i>
                                </div>
                            </div>
                            <div class="item_detail item_doc detail_part">
                                <div class="doc_wrap">
                                    <i class="hover_controller doc_ppt"></i>
                                    <p class="doc_name">三年模拟一次高考</p>
                                    <div class="cover_layer_wrap opacity_layer_white pic_desc_wrap">
                                        <label class="title">三年模拟一次高考</label>
                                        <p class="pic_desc doc_desc">高考不仅仅是一场考试</p>
                                    </div>
                                </div>
                                <div class="author_info">
                                    <span class="ellipsis">BY</span>
                                    <span class="ellipsis">快快长大</span>
                                    <i class="icon_down"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- 轮播（Carousel）导航 -->
                    <a class="carousel-control left" style="top:175px;" href="#myCarouselWord" data-slide="prev"></a>
                    <a class="carousel-control right" style="top:175px;" href="#myCarouselWord" data-slide="next"></a>
                </div>
            </div>
        </div>
        <div class="container_bottom">
            {% include "template/footer.volt" %}
        </div>
        {% include "template/authContainer.volt" %}

        {{ javascript_include("3rdpart/jquery/jquery.min.js") }}
        {{ javascript_include("3rdpart/bootstrap/js/bootstrap.min.js") }}
        {{ javascript_include("js/frontend/constants.js") }}
        {#滑动验证#}
        {{ javascript_include("3rdpart/simple-slider/dragdealer.js") }}
        {{ javascript_include("3rdpart/simple-slider/stringExt.js") }}
        {{ javascript_include("js/frontend/home.js") }}
        {{ javascript_include("js/frontend/login.js") }}
        {{ javascript_include("js/frontend/register.js") }}
        <script type="text/javascript">
            $(function(){
                $('.home_page').addClass('current_page');
                $('.item_detail').hover(function(){
                    $(this).find('.opacity_layer_white').animate({top:'0px'},'fast');
                },function(){
                    $(this).find('.opacity_layer_white').animate({top:'-240px'},'fast');
                });
            });
        </script>
    </body>
</html>

