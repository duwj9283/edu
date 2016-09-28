{% extends "template/basic.volt" %}
{% block pageCss %}
    {{ stylesheet_link("css/frontend/less/lessbase.css") }}
{% endblock %}
{% block content %}
    <div class="content">
        <div class="line"></div>
        <div class="person-index-con">
            <div class="w1100 clearfix">
                <div class="person-index-block01 person-index-block clearfix margin-top-30">
                    <img class="fl" src="/img/frontend/camtasiastudio/person_pic.jpg" width="235" height="271" />
                    <div  class="person-index-text fl">
                        <div class="clearfix per-infor-text">
                            <div class="person-name fl">田甜甜 <span>教师</span><p>安徽  合肥</p></div>
                            <span class="fr"> 51关注 / 125学生</span>
                        </div>
                        <p>简介：Dream Offer，致力于为在校大学生提供专业的名企求职辅导与职业规划<br>咨询服务</p>
                        <p><span>学科：英语</span><span>三年级二班</span></p>
                        <p>
                            <span>qq：22343434</span>
                            <span>电话：13567854456</span>
                            <span>邮箱：135678@126.com</span>
                        </p>
                        <div class="share-btn-box text-align-right">
                            <span class="share-btn" onclick="this.className='share-btn active'" >关注</span>
                        </div>

                    </div>
                </div>
                <div class="clearfix margin-top-25">
                    <div class="person-index-block02 person-index-block fl min-height">
                        <div class="per-info-tit per-info-tit02 margin-top-30">
                            <span>TA 的动态	</span>
                        </div>
                        <div class="line margin-top-10"></div>
                        <div class="dt-list new-dt-list">
                            <a href="/user/zoneResource" >
                                <i class="dt-ico dt-resource"> </i>
                                <span>资源</span>
                            </a>
                            <a href="/user/zone" >
                                <i class="dt-ico dt-actiing"></i>
                                <span>动态</span>
                            </a>
                            <a href="">
                                <i class="dt-ico dt-circle"></i>
                                <span>文章</span>
                            </a>
                            <a href="/user/zoneLive" >
                                <i class="dt-ico dt-living"></i>
                                <span>直播</span>
                            </a>
                            <a href="/user/zoneMico" >
                                <i class="dt-ico dt-mir"></i>
                                <span>微课</span>
                            </a>
                            <a href="/user/zoneCourse">
                                <i class="dt-ico dt-class"></i>
                                <span>课程</span>
                            </a>
                            <a href="/user/zoneActivity" class="active">
                                <i  class="dt-ico dt-active"></i>
                                <span>活动</span>
                            </a>
                        </div>
                        <div class="text-align-right dt-text">
                            <span><span >34</span><span>群组</span></span>
                            <span><span >34</span><span>资源</span></span>
                            <span><span >34</span><span>点赞</span></span>
                        </div>
                        <div class="text-align-right dt-send-btn">
                            <span class="send-btn">发布动态/文章</span>
                        </div>
                        <div class="line margin-top-30"></div>
                        <div id="zone-content-list">
                            <ul class="activity-infor-list clearfix live-list person-activity-list activity-infor-list02">
                                <li>
                                    <div class="">
                                        <a href="" class="live-img-box">
                                            <img src="/img/frontend/camtasiastudio/activity01.png">
                                            <b></b>
                                        </a>
                                        <a href="" class="live-tit">Global Market世界文化<span>跳蚤市集</span></a>
                                        <div class=" activity-text">
                                            <p class="clearfix"><span class="fl time-ico inner-ico">2016年3月18日  </span> <span class="fr	">18:30 ～  21:30</span></p>
                                            <p><span class="loction-ico  inner-ico">华南农业大学五山学生公寓创客空间</span></p>
                                        </div>
                                        <div class="activity-pk"><span class=" text-ico">冰雪奇观大PK</span></div>
                                    </div>
                                </li>
                                <li>
                                    <div class="">
                                        <a href="" class="live-img-box">
                                            <img src="/img/frontend/camtasiastudio/activity02.png">
                                            <b></b>
                                        </a>
                                        <a href="" class="live-tit">Global Market世界文化<span>跳蚤市集</span></a>
                                        <div class=" activity-text">
                                            <p class="clearfix"><span class="fl time-ico inner-ico">2016年3月18日  </span> <span class="fr	">18:30 ～  21:30</span></p>
                                            <p><span class="loction-ico  inner-ico">华南农业大学五山学生公寓创客空间</span></p>
                                        </div>
                                        <div class="activity-pk"><span class=" text-ico">冰雪奇观大PK</span></div>
                                    </div>
                                </li>
                                <li>
                                    <div class="">
                                        <a href="" class="live-img-box">
                                            <img src="/img/frontend/camtasiastudio/activity01.png">
                                            <b></b>
                                        </a>
                                        <a href="" class="live-tit">Global Market世界文化<span>跳蚤市集</span></a>
                                        <div class=" activity-text">
                                            <p class="clearfix"><span class="fl time-ico inner-ico">2016年3月18日  </span> <span class="fr	">18:30 ～  21:30</span></p>
                                            <p><span class="loction-ico  inner-ico">华南农业大学五山学生公寓创客空间</span></p>
                                        </div>
                                        <div class="activity-pk"><span class=" text-ico">冰雪奇观大PK</span></div>
                                    </div>
                                </li>
                                <li>
                                    <div class="">
                                        <a href="" class="live-img-box">
                                            <img src="/img/frontend/camtasiastudio/activity02.png">
                                            <b></b>
                                        </a>
                                        <a href="" class="live-tit">Global Market世界文化<span>跳蚤市集</span></a>
                                        <div class=" activity-text">
                                            <p class="clearfix"><span class="fl time-ico inner-ico">2016年3月18日  </span> <span class="fr	">18:30 ～  21:30</span></p>
                                            <p><span class="loction-ico  inner-ico">华南农业大学五山学生公寓创客空间</span></p>
                                        </div>
                                        <div class="activity-pk"><span class=" text-ico">冰雪奇观大PK</span></div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="fr">
                        <div class="person-index-block03 person-index-block">
                            <div class="per-info-tit per-info-tit02 margin-top-30 clearfix  ">
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
                        <div class="person-index-block03 person-index-block margin-top-30">
                            <div class="per-info-tit per-info-tit02 margin-top-30 clearfix">
                                <span class="fl">最近关注	</span>
                                <label class="fr">273关注</label>
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
                    </div>
                </div>
            </div>
        </div>
    </div>
{% endblock %}
{% block pageJs %}
    {{ javascript_include("js/frontend/userspace/userspace.js") }}
    {{ javascript_include("js/frontend/userspace/userspaceModal.js") }}
{% endblock %}