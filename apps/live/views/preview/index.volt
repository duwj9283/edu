{% extends "templates/basic.volt" %}
{% block pageCss %}
    {{ stylesheet_link("css/frontend/less2/common.css") }}
    {{ stylesheet_link("css/frontend/less2/preview.css") }}
{% endblock %}
{% block content %}
    <div id="wraper">
        <div class="container" style="width:1100px;">
                <div class="preview cl">
                    <div class="previewLeft">
                        <div class="unknow">无法预览</div>
                        <div class="video">
                            <img src="/img/frontend/less2/wk_video.jpg" width="730" />
                        </div>
                        <div class="doc"></div>
                        <div class="pic"></div>
                        <div class="operate">
                            <a href="#" class="share">分享</a>
                            <a href="#" class="down">下载</a>
                            <a href="#" class="collect">收藏</a>
                            <a href="#" class="like">点赞</a>
                        </div>
                    </div>
                    <div class="previewRight">
                        <h1>茶树重金属胁迫研究概况</h1>
                        <ul class="list">
                            <li>冰雪奇观大PK</li>
                            <li>发布时间：2016.03.25</li>
                            <li>应用类型：<i class="fa-type fa-video"></i>视频
                            </li>
                            <li>学科：商务英语</li>
                            <li>所属知识点：创意海报</li>
                            <li>文件大小：346kb</li>
                            <li><span class="fl">简介：</span>
                                <p class="about">当世界缺少了一种极致
                                    遗留下的将成为永恒/

                                    让一切争论就此停止吧
                                    让天堂多一刻绽放/

                                    像她一样去想象

                                    The future has pass away,
                                    明天又是新的一天</p>
                            </li>
                        </ul>
                    </div>
                </div>
                <div id="comments" class="bm">
                    <div class="title">评论</div>
                    <div id="cPost" class="cl">
                        <form>
                            <div class="input-wrap">
                                <textarea class="cText"></textarea>
                            </div>
                            <button class="cBtn">发表评论</button>
                        </form>
                    </div>
                    <div class="cList">
                        <!-- 评论循环 -->
                        <div class="cItem cl">
                            <div class="faceBar fl"><img src="/img/frontend/less2/user.png" width="34" height="34" class="face" /></div>
                            <div class="info">
                                <div class="t cl">
                                    <span class="floor fr">1</span>
                                    <a href="#" class="name">用户姓名</a></div>
                                <p class="msg">用户评论信息</p>
                                <div class="b cl">
                                    <p class="time">时间：两天前</p>
                                    <div class="operate"><a href="#" class="reply">回复</a><a href="#" class="good"><i class="fa-good"></i>10</a></div>
                                </div>
                                <div class="replyCon cl">
                                    <span class="fl">回复：</span>
                                    <div class="info">
                                        <a href="#" class="name">用户姓名</a>
                                        <p class="msg">用户评论信息</p>
                                        <p class="time">时间：两天前</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- 评论循环 end -->
                        <!-- 评论循环 -->
                        <div class="cItem cl">
                            <div class="faceBar fl"><img src="/img/frontend/less2/user.png" width="34" height="34" class="face" /></div>
                            <div class="info"> <a href="#" class="name">用户姓名</a>
                                <p class="msg">用户评论信息</p>
                                <div class="b cl">
                                    <p class="time">时间：两天前</p>
                                    <div class="operate"><a href="#" class="reply">回复</a><a href="#" class="good"><i class="fa-good"></i>10</a></div>
                                </div>
                            </div>
                        </div>
                        <!-- 评论循环 end -->

                    </div>
                    <!-- 评论列表 end -->
                </div>
            </div>
    </div>
{% endblock %}
{% block commonModal %}
{% endblock %}
{% block pageJs %}
{% endblock %}