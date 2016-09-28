{% extends "template/basic.volt" %}
{% block pageCss %}
    {{ stylesheet_link("css/frontend/less/lessbase.css") }}
    {{ stylesheet_link("css/frontend/col-side.css")  }}
    <style>
        .w1120{
            width: 60%;
            min-width: 1150px;
            margin: 0 auto;
        }
        .widthauto.grounp-block{
            box-sizing: content-box;
        }
        #activity-list{
            font: 12px "Microsoft YaHei",SimSun,tahoma,arial,sans-serif;
            color: #515567;
            line-height: 1.5;
        }
    </style>
{% endblock %}
{% block content %}
    <div class="content">
        <div class="line"></div>
        <div class="person-con-infor">
            <div class="w1120 clearfix">
                {% include 'template/col-side.volt' %}
                <div class="person-con fr ">
                    <ul class="grounp-tab clearfix">
                        <li class="clearfix">
                            <div class="clearfix dt-list-img fl">
                                <b></b>
                                <img src="/img/frontend/camtasiastudio/small_pic02.png" width="25" height="25">
                            </div>
                            <span class="fl">dotdog</span>
                            <b class="fr grounp-tab-close"></b>
                        </li>
                        <li class="clearfix">
                            <div class="clearfix dt-list-img fl">
                                <b></b>
                                <img src="/img/frontend/camtasiastudio/small_pic02.png" width="25" height="25">
                            </div>
                            <span class="fl">dotdog</span>
                            <b class="fr grounp-tab-close"></b>
                        </li>
                        <li class="clearfix">
                            <div class="clearfix dt-list-img fl">
                                <b></b>
                                <img src="/img/frontend/camtasiastudio/small_pic02.png" width="25" height="25">
                            </div>
                            <span class="fl">dotdog</span>
                            <b class="fr grounp-tab-close"></b>
                        </li>
                    </ul>
                    <div class="line"></div>
                    <div class="person-index-block02 person-index-block widthauto margin-top-10 grounp-block">
                        <div class="clearfix space-person-infor">
                            <img class="fl" src="/img/frontend/camtasiastudio/person_pic02.jpg" width="306" height="273">
                            <div class="person-index-text fl">
                                <div class="clearfix per-infor-text">
                                    <div class="person-name fl">xx教研组</div>
                                    <div class="role fr group-member-list">
                                        <span class="tab-ico">群成员</span>
                                    </div>
                                </div>
                                <p class="margin-top-25">创建者：yyy</p>
                                <div class="margin-top-5">群主题：英语教育</div>
                                <p class="introduction">简介：Dream Offer，致力于为在校大学生提供专业的名企求职辅导与职业规划<br>咨询服务</p>

                                <div class="share-btn-box clearfix">
                                    <label class="fl follow-notic"> 51关注 / 125学生</label>
                                    <span class="share-btn fr orange-btn" >动态发布</span>
                                </div>

                            </div>
                        </div>
                        <div class="line margin-top-10"></div>
                        <div class="dt-list space-list-header" >
                            <a  class="active" id="space-dynamic">
                                <i class="dt-ico dt-member"> </i>
                                <span>群动态</span>
                            </a>
                            <a id="space-resource" >
                                <i class="dt-ico dt-resource"></i>
                                <span>群资料</span>
                            </a>
                            <a id="space-live">
                                <i class="dt-ico dt-living"></i>
                                <span>群直播</span>
                            </a>
                            <a id="space-mico">
                                <i class="dt-ico dt-mir"></i>
                                <span>群微课</span>
                            </a>
                            <a id="space-course">
                                <i class="dt-ico dt-class"></i>
                                <span>群课程</span>
                            </a>
                            <a id="space-activity">
                                <i class="dt-ico dt-active"></i>
                                <span>群活动</span>
                            </a>
                        </div>

                        <div class="line margin-top-20"></div>
                        <div class="space-detail-container"></div>
                        <div id="group-space-container">
                            <div class="td-static">
                                <h2>群动态</h2>
                                <ul class="td-list">
                                    <li>
                                        <label>2016年04月01日<span>14:21</span></label>
                                        <b class="active"></b>
                                        <div class="dt-list-box">
                                            <div class="dt-list-infor clearfix">
                                                <div class="clearfix dt-list-img fl">
                                                    <b></b>
                                                    <img src="/img/frontend/camtasiastudio/small_pic.png" width="33" height="33">
                                                </div>
                                                <div class="dt-list-text fl">
                                                    <h3>dotdog</h3>
                                                    <p>第二章课程的学习资料将在晚上上传。</p>
                                                    <div class="fj">
                                                        <img src="/img/frontend/camtasiastudio/fj.png">
                                                        <a href=""></a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="text-align-right dt-text">
                                                <span><span>34</span><span>点赞</span></span>
                                                <span><span>34</span><span>评论</span></span>
                                            </div>
                                        </div>
                                        <ul class="dt-detail-list">
                                            <li class="clearfix">
                                                <div class="clearfix dt-list-img fl">
                                                    <b></b>
                                                    <img src="/img/frontend/camtasiastudio/small_pic.png" width="33" height="33">
                                                </div>
                                                <div class="fl">
                                                    <h4>序言</h4>
                                                    <p>基本界面是如何操作的</p>
                                                </div>
                                            </li>
                                            <li class="clearfix">
                                                <div class="clearfix dt-list-img fl">
                                                    <b></b>
                                                    <img src="/img/frontend/camtasiastudio/small_pic.png" width="33" height="33">
                                                </div>
                                                <div class="fl">
                                                    <h4>序言</h4>
                                                    <p>基本界面是如何操作的</p>
                                                </div>
                                            </li>
                                            <li class="clearfix">
                                                <div class="clearfix dt-list-img fl">
                                                    <b></b>
                                                    <img src="/img/frontend/camtasiastudio/small_pic.png" width="33" height="33">
                                                </div>
                                                <div class="fl">
                                                    <h4>序言</h4>
                                                    <p>基本界面是如何操作的</p>
                                                </div>
                                            </li>
                                        </ul>
                                    </li>
                                    <li>
                                        <label>2016年04月01日<span>14:21</span></label>
                                        <b></b>
                                        <div class="dt-list-infor clearfix">
                                            <div class="clearfix dt-list-img fl">
                                                <b></b>
                                                <img src="/img/frontend/camtasiastudio/small_pic.png" width="33" height="33">
                                            </div>
                                            <div class="dt-list-text fl">
                                                <h3>dotdog</h3>
                                                <p>第二章课程的学习资料将在晚上上传。</p>
                                                <div class="fj">
                                                    <img src="/img/frontend/camtasiastudio/fj.png">
                                                    <a href=""></a>
                                                </div>
                                            </div>

                                        </div>
                                        <ul class="dt-detail-list">
                                            <li class="clearfix">
                                                <div class="clearfix dt-list-img fl">
                                                    <b></b>
                                                    <img src="/img/frontend/camtasiastudio/small_pic.png" width="33" height="33">
                                                </div>
                                                <div class="fl">
                                                    <h4>序言</h4>
                                                    <p>基本界面是如何操作的</p>
                                                </div>
                                            </li>
                                            <li class="clearfix">
                                                <div class="clearfix dt-list-img fl">
                                                    <b></b>
                                                    <img src="/img/frontend/camtasiastudio/small_pic.png" width="33" height="33">
                                                </div>
                                                <div class="fl">
                                                    <h4>序言</h4>
                                                    <p>基本界面是如何操作的</p>
                                                </div>
                                            </li>
                                            <li class="clearfix">
                                                <div class="clearfix dt-list-img fl">
                                                    <b></b>
                                                    <img src="/img/frontend/camtasiastudio/small_pic.png" width="33" height="33">
                                                </div>
                                                <div class="fl">
                                                    <h4>序言</h4>
                                                    <p>基本界面是如何操作的</p>
                                                </div>
                                            </li>
                                        </ul>
                                    </li>

                                </ul>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
{% endblock %}
{% block commonModal %}
    {% include "template/group-list-pop.volt" %}
{% endblock %}
{% block pageJs %}
    {{ javascript_include("js/frontend/col-side.js") }}
    {{ javascript_include("js/frontend/group/zone.js") }}
    {{ javascript_include("js/frontend/group/zoneModal.js") }}
    {{ javascript_include("js/frontend/jquery.nicescroll.js") }}
{% endblock %}