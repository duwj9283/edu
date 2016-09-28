{% extends "template/basic.volt" %}
{% block pageCss %}
    {{ stylesheet_link("css/frontend/less/lessbase.css") }}
{% endblock %}

{% block content %}
    <div class="content">
        <div class="banner position-relative cam-banner">
            <a href="javascirpt:void(0)" class="banner-img" ></a>
            <div class="banner-text" >
                <div class="search clearfix">
                    <input type="text" class="fl"  placeholder="一起探索吧" />
                    <button class="fr"></button>
                </div>
                <div class="banner-link">
                    <a href="">1238空间</a>
                    <a href="">45246资源</a>
                    <a href="">4664社群</a>
                </div>
            </div>
        </div>
        <div class="con-infor">
            <div class="contol-infor acm-infor">
                <div class="control-tab-tit w1060">
                    <span onclick="showMore(this)"><span>全部直播</span></span>
                </div>
                <ul class="control-list text-align-center" id="controlList">
                    <li>
                        <p class="w1060">
                            <a class="active" href="javascript:void(0);">IT互联网</a>
                            <a href="javascript:void(0);">职场技能</a>
                            <a href="javascript:void(0);">语言学习</a>
                            <a href="javascript:void(0);">工程建筑</a>
                            <a href="javascript:void(0);">育儿教育</a>
                            <a href="javascript:void(0);">医疗卫生</a>
                            <a href="javascript:void(0);">兴趣爱好</a>
                        </p>
                    </li>
                    <li>
                        <p class="w1060">
                            <a class="active" href="javascript:void(0);">商务英语</a>
                            <a href="javascript:void(0);">雅思</a>
                            <a href="javascript:void(0);">GRE</a>
                            <a href="javascript:void(0);">托福</a>
                            <a href="javascript:void(0);">法语</a>
                            <a href="javascript:void(0);">韩语</a>
                            <a href="javascript:void(0);">日语</a>
                            <a href="javascript:void(0);">德语</a>
                        </p>
                    </li>
                </ul>
            </div>
            <div class="w1100 ">
                <ul class="cam-list">
                    <li class="clearfix">
                        <div class="fl cam-img">
                            <a href="" >
                                <img src="/img/frontend/camtasiastudio/cam01.png" width="504" height="280"  />
                                <b></b>
                                <em></em>
                            </a>
                        </div>
                        <div  class="fl cam-infor">
                            <h3><a href="">和外教一起学英语口语</a></h3>
                            <p>随着人工智能大战李世石的围棋赛落幕，人们已经对高速发展的人工智能唏嘘不已。人工智能
                                最后会成为我们的敌人还是朋友？它会让我们新生还是毁灭？</p>
                            <div class="clearfix cam-text">
								<span class="fr">
									19807人已学
								</span>
								<span class="fr">
									BY 在线教育
								</span>
                            </div>
                        </div>
                    </li>
                    <li class="clearfix">
                        <div class="fl cam-img">
                            <a href="" >
                                <img src="/img/frontend/camtasiastudio/cam02.png" width="504" height="280"  />
                                <b></b>
                                <em></em>
                            </a>
                        </div>
                        <div  class="fl cam-infor">
                            <h3><a href="">和外教一起学英语口语</a></h3>
                            <p>随着人工智能大战李世石的围棋赛落幕，人们已经对高速发展的人工智能唏嘘不已。人工智能
                                最后会成为我们的敌人还是朋友？它会让我们新生还是毁灭？</p>
                            <div class="clearfix cam-text">
								<span class="fr">
									19807人已学
								</span>
								<span class="fr">
									BY 在线教育
								</span>
                            </div>
                        </div>
                    </li>
                    <li class="clearfix">
                        <div class="fl cam-img">
                            <a href="" >
                                <img src="/img/frontend/camtasiastudio/cam03.png" width="504" height="280"  />
                                <b></b>
                                <em></em>
                            </a>
                        </div>
                        <div  class="fl cam-infor">
                            <h3><a href="">和外教一起学英语口语</a></h3>
                            <p>随着人工智能大战李世石的围棋赛落幕，人们已经对高速发展的人工智能唏嘘不已。人工智能
                                最后会成为我们的敌人还是朋友？它会让我们新生还是毁灭？</p>
                            <div class="clearfix cam-text">
								<span class="fr">
									19807人已学
								</span>
								<span class="fr">
									BY 在线教育
								</span>
                            </div>
                        </div>
                    </li>
                    <li class="clearfix">
                        <div class="fl cam-img">
                            <a href="" >
                                <img src="/img/frontend/camtasiastudio/cam04.png" width="504" height="280"  />
                                <b></b>
                                <em></em>
                            </a>
                        </div>
                        <div  class="fl cam-infor">
                            <h3><a href="">和外教一起学英语口语</a></h3>
                            <p>随着人工智能大战李世石的围棋赛落幕，人们已经对高速发展的人工智能唏嘘不已。人工智能
                                最后会成为我们的敌人还是朋友？它会让我们新生还是毁灭？</p>
                            <div class="clearfix cam-text">
								<span class="fr">
									19807人已学
								</span>
								<span class="fr">
									BY 在线教育
								</span>
                            </div>
                        </div>
                    </li>
                </ul>
                <div class="page margin-top-60" >
                    <div class="page-list ">
                        <div class="clearfix page-deatil">
                            <a href="" class="fl page-prve gray"> &lt;上一页</a>
                            <div class="page-middle fl">
                                <a class="active" href="">1</a>
                                <a href="">2</a>
                                <a href="">3</a>
                                <a href="">4</a>
                                <a href="">5</a>
                            </div>
                            <span class="fl ">...</span>
                            <a href="" class="fl page-next">下一页 &gt; </a>
                            <span class="fl">共3页</span>
                            <label class="fl go-page">
                                到<input type="text" value="1"  />页
                            </label>
                            <button class="fl">确认</button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
{% endblock %}
{% block pageJs%}
    <script type="text/javascript">
        $(function(){
            $('#navbar-collapse-basic ul li').removeClass('active');
            $('#navbar-collapse-basic ul .camtasiastudio').addClass('active');

            $("#controlList a").bind("click",function(){
                $(this).addClass("active").siblings().removeClass("active");
            });
        })
        function showMore(obj){
            if($(obj).hasClass("selected")){
                $(obj).removeClass("selected");
                $("#controlList").fadeIn("slow");
            }else{
                $(obj).addClass("selected");
                $("#controlList").fadeOut("slow")
            }
        }
    </script>
{% endblock %}