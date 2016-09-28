{% extends "template/basic-v2.volt" %}
{% block pageCss %}
    {{ stylesheet_link("css/frontend/less/lessbase.css") }}
{% endblock %}

{% block content %}
    <div class="content">
        <div class="line"></div>
        <div class=" w1100">
            <div class="grounps-searech clearfix padding-all-20">
                <div class="fr header-search">
                    <button></button>
                    <input type="text" value="标题+文件格式">
                </div>
            </div>
        </div>
        <div class="line"></div>
        <div class="grounp-con ">
            <div class="w1100">
                <div class="text-align-center no-list-box" >
                    <img src="/img/frontend/camtasiastudio/no_list.png"  />
                </div>
                <ul class="grounp-con-list clearfix">
                    <li class="clearfix">
                        <a class="clearfix dt-list-img fl">
                            <b></b>
                            <img src="/img/frontend/camtasiastudio/small_pic.png" width="88" height="88">
                        </a>
                        <div class="grounp-list-text fl" href="">
                            <p>名称：在线教育</p>
                            <p>主题：英语教育</p>
                            <div class="clearfix"><span class="fl">简介</span><p>：Dream Offer，致力于为在校大学生提供专业的名企求职辅导与职业规划咨询服务</p></div>
                            <div class="clearfix grounp-num">
                                <p class="fl">人数：<span class="orange">1515</span></p>
                                <p class="fl">文件数：<span class="orange">1515</span></p>
                                <p class="fl">直播数：<span class="orange">1515</span></p>
                                <p class="fl">动态数：<span class="orange">1515</span></p>
                            </div>

                        </div>
                        <button class="fr apply-add-btn">申请加入</button>
                    </li>
                    <li class="clearfix">
                        <a class="clearfix dt-list-img fl">
                            <b></b>
                            <img src="/img/frontend/camtasiastudio/small_pic.png" width="88" height="88">
                        </a>
                        <div class="grounp-list-text fl" href="">
                            <p>名称：在线教育</p>
                            <p>主题：英语教育</p>
                            <div class="clearfix"><span class="fl">简介</span><p>：Dream Offer，致力于为在校大学生提供专业的名企求职辅导与职业规划咨询服务</p></div>
                            <div class="clearfix grounp-num">
                                <p class="fl">人数：<span class="orange">1515</span></p>
                                <p class="fl">文件数：<span class="orange">1515</span></p>
                                <p class="fl">直播数：<span class="orange">1515</span></p>
                                <p class="fl">动态数：<span class="orange">1515</span></p>
                            </div>

                        </div>
                        <button class="fr apply-add-btn">申请加入</button>
                    </li>
                    <li class="clearfix">
                        <a class="clearfix dt-list-img fl">
                            <b></b>
                            <img src="/img/frontend/camtasiastudio/small_pic.png" width="88" height="88">
                        </a>
                        <div class="grounp-list-text fl" href="">
                            <p>名称：在线教育</p>
                            <p>主题：英语教育</p>
                            <div class="clearfix"><span class="fl">简介</span><p>：Dream Offer，致力于为在校大学生提供专业的名企求职辅导与职业规划咨询服务</p></div>
                            <div class="clearfix grounp-num">
                                <p class="fl">人数：<span class="orange">1515</span></p>
                                <p class="fl">文件数：<span class="orange">1515</span></p>
                                <p class="fl">直播数：<span class="orange">1515</span></p>
                                <p class="fl">动态数：<span class="orange">1515</span></p>
                            </div>

                        </div>
                        <button class="fr apply-add-btn">申请加入</button>
                    </li>
                    <li class="clearfix">
                        <a class="clearfix dt-list-img fl">
                            <b></b>
                            <img src="/img/frontend/camtasiastudio/small_pic.png" width="88" height="88">
                        </a>
                        <div class="grounp-list-text fl" href="">
                            <p>名称：在线教育</p>
                            <p>主题：英语教育</p>
                            <div class="clearfix"><span class="fl">简介</span><p>：Dream Offer，致力于为在校大学生提供专业的名企求职辅导与职业规划咨询服务</p></div>
                            <div class="clearfix grounp-num">
                                <p class="fl">人数：<span class="orange">1515</span></p>
                                <p class="fl">文件数：<span class="orange">1515</span></p>
                                <p class="fl">直播数：<span class="orange">1515</span></p>
                                <p class="fl">动态数：<span class="orange">1515</span></p>
                            </div>

                        </div>
                        <button class="fr apply-add-btn">申请加入</button>
                    </li>
                    <li class="clearfix">
                        <a class="clearfix dt-list-img fl">
                            <b></b>
                            <img src="/img/frontend/camtasiastudio/small_pic.png" width="88" height="88">
                        </a>
                        <div class="grounp-list-text fl" href="">
                            <p>名称：在线教育</p>
                            <p>主题：英语教育</p>
                            <div class="clearfix"><span class="fl">简介</span><p>：Dream Offer，致力于为在校大学生提供专业的名企求职辅导与职业规划咨询服务</p></div>
                            <div class="clearfix grounp-num">
                                <p class="fl">人数：<span class="orange">1515</span></p>
                                <p class="fl">文件数：<span class="orange">1515</span></p>
                                <p class="fl">直播数：<span class="orange">1515</span></p>
                                <p class="fl">动态数：<span class="orange">1515</span></p>
                            </div>

                        </div>
                        <button class="fr apply-add-btn">申请加入</button>
                    </li>
                </ul>
            </div>
        </div>
    </div>
{% endblock %}
{% block commonModal %}
{% endblock %}
{% block pageJs%}
    <script type="text/javascript">
        $(function(){
            $('#navbar-collapse-basic ul li').removeClass('active');
            $('#navbar-collapse-basic ul .group').addClass('active');
        })
    </script>
{% endblock %}