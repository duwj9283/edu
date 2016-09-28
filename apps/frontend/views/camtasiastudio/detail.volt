{% extends "template/basic.volt" %}
{% block pageCss %}
    {{ stylesheet_link("css/frontend/less/lessbase.css") }}
{% endblock %}
{% block content %}
    <div class="content">
        <div class="line"></div>
        <div class="breadcrumb-box margin-top-10">
            <div class="w1020">
                <div class="breadcrumb">
                    <a href="">首页</a>
                    &gt;
                    <a href="">微课</a>
                    &gt;
                    <a href="">语言学习</a>
                    &gt;
                    <span>商务学习</span>
                </div>
            </div>
        </div>
        <div class="con-infor">
            <div class="w1020">
                <div class="voide-tit">
                    语言形式与含义
                </div>
                <div class="">
                    <img src="/img/frontend/camtasiastudio/yk.jpg"  />
                </div>
                <div class="voide-infor margin-top-40">
                    <div class="voide-infor-text">
                        <h4>简介：</h4>
                        <p>金牌讲师：牙医十七（宋老师）全程带课（主战6大主科）个性化教学，一站式服务，寓教于乐的学习模式，让您的学习
                            快乐起来！</p>
                    </div>
                    <div class="line"></div>
                    <div class="voide-infor-text">
                        <h4>知识点：</h4>
                        <p>通过系统复习，通讲各科高频考点，让您一次通过口腔执业医师资格考试！</p>
                    </div>
                </div>
                <ul class="live-list clearfix ">
                    <li class="fl">
                        <div>
                            <a href="" class="live-img-box">
                                <img src="/img/frontend/camtasiastudio/live01.png">
                                <b></b>
                                <em></em>
                            </a>
                            <a href="" class="live-tit">和外教一起学英语口语</a>
                            <div class="clearfix live-text">
                                <span class="fl">BY在线教育</span>
                                <span class="fr">19807人已学</span>
                            </div>
                        </div>
                    </li>
                    <li class="fl">
                        <div>
                            <a href="" class="live-img-box">
                                <img src="/img/frontend/camtasiastudio/live02.png">
                                <b></b>
                                <em></em>
                            </a>
                            <a href="" class="live-tit">和外教一起学英语口语</a>
                            <div class="clearfix live-text">
                                <span class="fl">BY在线教育</span>
                                <span class="fr">19807人已学</span>
                            </div>

                        </div>
                    </li>
                    <li class="fl">
                        <div>
                            <a href="" class="live-img-box">
                                <img src="/img/frontend/camtasiastudio/live03.png">
                                <b></b>
                                <em></em>
                            </a>
                            <a href="" class="live-tit">和外教一起学英语口语</a>
                            <div class="clearfix live-text">
                                <span class="fl">BY在线教育</span>
                                <span class="fr">19807人已学</span>
                            </div>

                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
{% endblock %}
{% block pageJs %}
{% endblock %}