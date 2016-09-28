{% extends "template/basic.volt" %}
{% block pageCss %}
    {{ stylesheet_link("css/frontend/less/lessbase.css") }}
{% endblock %}

{% block content %}
    <div class="inner_body content">
        <div class="person-index-con grounp-con">
            <div class="container clearfix">
                <div class="person-index-block01 person-index-block clearfix margin-top-30 grounp-block">
                    <img class="fl" src="/img/frontend/camtasiastudio/person_pic02.jpg" width="306" height="273" />
                    <div  class="person-index-text fl">
                        <div class="clearfix per-infor-text">
                            <div class="person-name fl">xx教研组</div>
                        </div>
                        <p class="margin-top-25">主题：英语教育</p>
                        <p class="introduction">简介：Dream Offer，致力于为在校大学生提供专业的名企求职辅导与职业规划<br>咨询服务</p>

                        <div class="share-btn-box clearfix">
                            <label class="fl follow-notic"> 51关注 / 125学生</label>
                            <span class="share-btn fr orange-btn" onclick="this.className='share-btn active'" >申请加入</span>
                        </div>

                    </div>
                </div>
                <div class="clearfix margin-top-25">
                    <div class="person-index-block02 person-index-block fl grounp-block min-height">
                        <div class="dt-list">
                            <a href="">
                                <i class="dt-ico dt-member"> </i>
                                <span>群成员</span>
                            </a>
                            <a href="">
                                <i class="dt-ico dt-resource"></i>
                                <span>群资料</span>
                            </a>
                            <a href="">
                                <i class="dt-ico dt-living"></i>
                                <span>群直播</span>
                            </a>
                            <a href="">
                                <i class="dt-ico dt-mir"></i>
                                <span>群微课</span>
                            </a>
                            <a href="">
                                <i class="dt-ico dt-class"></i>
                                <span>群课程</span>
                            </a>
                            <a href="">
                                <i  class="dt-ico dt-active"></i>
                                <span>群活动</span>
                            </a>
                        </div>
                        <div class="line margin-top-30"></div>
                        <div class="td-static">
                            <h2>群组动态</h2>
                            <ul class="td-list">
                                <li>
                                    <label>2016年04月01日<span>14:21</span></label>
                                    <b class="active"></b>
                                    <div class="dt-list-box">
                                        <div class="dt-list-infor clearfix">
                                            <div class="clearfix dt-list-img fl">
                                                <b></b>
                                                <img src="/img/frontend/camtasiastudio/small_pic.png" width="33" height="33"  />
                                            </div>
                                            <div class="dt-list-text fl">
                                                <h3>dotdog</h3>
                                                <p>第二章课程的学习资料将在晚上上传。</p>
                                                <div class="fj">
                                                    <img src="/img/frontend/camtasiastudio/fj.png"  />
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
                                                <img src="/img/frontend/camtasiastudio/small_pic.png" width="33" height="33"  />
                                            </div>
                                            <div class="fl">
                                                <h4>序言</h4>
                                                <p>基本界面是如何操作的</p>
                                            </div>
                                        </li>
                                        <li class="clearfix">
                                            <div class="clearfix dt-list-img fl">
                                                <b></b>
                                                <img src="/img/frontend/camtasiastudio/small_pic.png" width="33" height="33"  />
                                            </div>
                                            <div class="fl">
                                                <h4>序言</h4>
                                                <p>基本界面是如何操作的</p>
                                            </div>
                                        </li>
                                        <li class="clearfix">
                                            <div class="clearfix dt-list-img fl">
                                                <b></b>
                                                <img src="/img/frontend/camtasiastudio/small_pic.png" width="33" height="33"  />
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
                                    <b ></b>
                                    <div class="dt-list-infor clearfix">
                                        <div class="clearfix dt-list-img fl">
                                            <b></b>
                                            <img src="/img/frontend/camtasiastudio/small_pic.png" width="33" height="33"  />
                                        </div>
                                        <div class="dt-list-text fl">
                                            <h3>dotdog</h3>
                                            <p>第二章课程的学习资料将在晚上上传。</p>
                                            <div class="fj">
                                                <img src="/img/frontend/camtasiastudio/fj.png"  />
                                                <a href=""></a>
                                            </div>
                                        </div>

                                    </div>
                                    <ul class="dt-detail-list">
                                        <li class="clearfix">
                                            <div class="clearfix dt-list-img fl">
                                                <b></b>
                                                <img src="/img/frontend/camtasiastudio/small_pic.png" width="33" height="33"  />
                                            </div>
                                            <div class="fl">
                                                <h4>序言</h4>
                                                <p>基本界面是如何操作的</p>
                                            </div>
                                        </li>
                                        <li class="clearfix">
                                            <div class="clearfix dt-list-img fl">
                                                <b></b>
                                                <img src="/img/frontend/camtasiastudio/small_pic.png" width="33" height="33"  />
                                            </div>
                                            <div class="fl">
                                                <h4>序言</h4>
                                                <p>基本界面是如何操作的</p>
                                            </div>
                                        </li>
                                        <li class="clearfix">
                                            <div class="clearfix dt-list-img fl">
                                                <b></b>
                                                <img src="/img/frontend/camtasiastudio/small_pic.png" width="33" height="33"  />
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
                    <div class="fr">
                        <div class="person-index-block03 person-index-block grounp-block">
                            <div class="per-info-tit per-info-tit02 margin-top-30 clearfix">
                                <span class="fl">最近访客	</span>
                                <label class="fr">273访客</label>
                            </div>
                            <ul class="clearfix fk-list">
                                <li class="fl">
                                    <a href="">
                                        <img src="/img/frontend/camtasiastudio/middle_pic.png" width="60" height="60"  />
                                        <b></b>
                                    </a>
                                    <span>用户1</span>
                                </li>
                                <li class="fl">
                                    <a href="">
                                        <img src="/img/frontend/camtasiastudio/middle_pic.png" width="60" height="60"  />
                                        <b></b>
                                    </a>
                                    <span>用户1</span>
                                </li>
                                <li class="fl">
                                    <a href="">
                                        <img src="/img/frontend/camtasiastudio/middle_pic.png" width="60" height="60"  />
                                        <b></b>
                                    </a>
                                    <span>用户1</span>
                                </li>
                                <li class="fl">
                                    <a href="">
                                        <img src="/img/frontend/camtasiastudio/middle_pic.png" width="60" height="60"  />
                                        <b></b>
                                    </a>
                                    <span>用户1</span>
                                </li>
                                <li class="fl">
                                    <a href="">
                                        <img src="/img/frontend/camtasiastudio/middle_pic.png" width="60" height="60"  />
                                        <b></b>
                                    </a>
                                    <span>用户1</span>
                                </li>
                                <li class="fl">
                                    <a href="">
                                        <img src="/img/frontend/camtasiastudio/middle_pic.png" width="60" height="60"  />
                                        <b></b>
                                    </a>
                                    <span>用户1</span>
                                </li>
                            </ul>
                        </div>
                        <div class="person-index-block03 person-index-block margin-top-30 grounp-block">
                            <div class="per-info-tit per-info-tit02 margin-top-30">
                                <span >群组成员	</span>
                            </div>
                            <ul class="clearfix fk-list">
                                <li class="fl">
                                    <a href="">
                                        <img src="/img/frontend/camtasiastudio/middle_pic.png" width="60" height="60"  />
                                        <b></b>
                                    </a>
                                    <span>用户1</span>
                                </li>
                                <li class="fl">
                                    <a href="">
                                        <img src="/img/frontend/camtasiastudio/middle_pic.png" width="60" height="60"  />
                                        <b></b>
                                    </a>
                                    <span>用户1</span>
                                </li>
                                <li class="fl">
                                    <a href="">
                                        <img src="/img/frontend/camtasiastudio/middle_pic.png" width="60" height="60"  />
                                        <b></b>
                                    </a>
                                    <span>用户1</span>
                                </li>
                                <li class="fl">
                                    <a href="">
                                        <img src="/img/frontend/camtasiastudio/middle_pic.png" width="60" height="60"  />
                                        <b></b>
                                    </a>
                                    <span>用户1</span>
                                </li>
                                <li class="fl">
                                    <a href="">
                                        <img src="/img/frontend/camtasiastudio/middle_pic.png" width="60" height="60"  />
                                        <b></b>
                                    </a>
                                    <span>用户1</span>
                                </li>
                                <li class="fl">
                                    <a href="">
                                        <img src="/img/frontend/camtasiastudio/middle_pic.png" width="60" height="60"  />
                                        <b></b>
                                    </a>
                                    <span>用户1</span>
                                </li>

                                <li class="fl">
                                    <a href="">
                                        <img src="/img/frontend/camtasiastudio/middle_pic.png" width="60" height="60"  />
                                        <b></b>
                                    </a>
                                    <span>用户1</span>
                                </li>
                                <li class="fl">
                                    <a href="">
                                        <img src="/img/frontend/camtasiastudio/middle_pic.png" width="60" height="60"  />
                                        <b></b>
                                    </a>
                                    <span>用户1</span>
                                </li>
                                <li class="fl">
                                    <a href="">
                                        <img src="/img/frontend/camtasiastudio/middle_pic.png" width="60" height="60"  />
                                        <b></b>
                                    </a>
                                    <span>用户1</span>
                                </li>
                                <li class="fl">
                                    <a href="">
                                        <img src="/img/frontend/camtasiastudio/middle_pic.png" width="60" height="60"  />
                                        <b></b>
                                    </a>
                                    <span>用户1</span>
                                </li>
                                <li class="fl">
                                    <a href="">
                                        <img src="/img/frontend/camtasiastudio/middle_pic.png" width="60" height="60"  />
                                        <b></b>
                                    </a>
                                    <span>用户1</span>
                                </li>
                                <li class="fl">
                                    <a href="">
                                        <img src="/img/frontend/camtasiastudio/middle_pic.png" width="60" height="60"  />
                                        <b></b>
                                    </a>
                                    <span>用户1</span>
                                </li>
                            </ul>
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
            $('#navbar-collapse-basic ul .group').addClass('active');
        });
    </script>
{% endblock %}