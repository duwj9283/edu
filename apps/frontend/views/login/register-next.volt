{% extends "template/basic-v3.volt" %}
{% block pageCss %}
    {{ stylesheet_link("css/frontend/login.css") }}
{% endblock %}
{% block content %}
    <div class="main">
        <div class="login-banner">
            <div class="container text-center">
                <a href="/login/login" class="btn">登  录</a>
                <a href="/login/register" class="btn active">注 册<span class="caret"></span></a>
            </div>
        </div>
        <div class="reg-wrap">
            <div class="container">
                <div class="reg-block">

                    <div class="form-wrap">
<!--                        <h2>注册</h2>-->

                        <form name="register-next-form" onsubmit="return false">
                            <div class="form_two">
                                <div class="field clearfix">
                                    <div class="field-input clearfix"><span class="name">姓名</span>
                                        <input type="text" class="txt" name="realname" placeholder="请输入您的真实姓名" required>
                                    </div>
                                </div>
                                <div class="field clearfix">
                                    <div class="field-input clearfix"><span class="name">昵称</span>
                                        <input type="text" class="txt" name="nick_name" placeholder="请输入昵称" required>
                                    </div>
                                </div>
                                <div class="field clearfix">
                                    <div class="field-input clearfix" id="city">
                                        <span class="name">地区</span>
                                        <select class="mod-select mod-select-short prov">
                                            <option value="0">请选择地区</option>
                                        </select>
                                        <select class="mod-select mod-select-short city">
                                            <option value="0">请先选择地区</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="field clearfix">
                                    <div class="field-input clearfix"><span class="name">个人简介</span>
                                        <textarea class="about" name="desc" required placeholder="请输入个人简介"></textarea>
                                    </div>
                                </div>
                                <div class="field clearfix">
                                    <div class="field-input clearfix"><span class="name">QQ</span>
                                        <input type="text" class="txt" name="qq" required placeholder="请输入qq"></div>
                                </div>
                                <div class="field clearfix">
                                    <div class="field-input clearfix"><span class="name">邮箱</span>
                                        <input type="email" class="txt" name="email" required placeholder="请输入邮箱">
                                    </div>
                                </div>
                                <div class="field clearfix">
                                    <div class="field-input clearfix"><span class="name">班级年份</span>
                                        <select class="mod-select" name="class_year" required>

                                        </select>
                                    </div>
                                </div>
                                <div class="field clearfix">
                                    <div class="field-input clearfix"><span class="name">年级</span>
                                        <input type="text" class="txt" name="class" disabled placeholder="请先选择班级年份">
                                    </div>
                                </div>
                                <div class="field clearfix">
                                    <div class="field-input clearfix"><span class="name">班级</span>

                                        <select class="mod-select" name="class" required>
                                            {% for item in classArr %}
                                                <option>{{ item }}</option>

                                            {% endfor %}

                                        </select>
                                    </div>
                                </div>
                                <div class="field clearfix">
                                    <div class="field-input clearfix" id="subject-select"><span class="name">学科</span>
                                        <select class="mod-select mod-select-short sub-type" required></select>
                                        <select class="mod-select mod-select-short sub-child-type"
                                                name="subject"></select>

                                    </div>
                                </div>

                                <div class="field">

                                    <input class="btn-org btn" data-role="submit" type="submit" value="确定"></div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
{% endblock %}
{% block pageJs %}
    {#<script type="text/javascript">
        //msg-success 成功
        //msg-warning 提醒
        $("input[type=text],input[type=password]").change(function(){
            var msg='提示信息';
            $(".form-wrap .field-msg").remove();
            $(this).parents(".field").append('<div class="field-msg msg-success">'+msg+'</div>');
            $(this).parents(".field").append('<div class="field-msg msg-warning">'+msg+'</div>');
        });

    </script>#}
    {{ javascript_include("/js/frontend/cityselect/jquery.cityselect.js") }}
    {{ javascript_include("js/frontend/subjectselect/jq.subselect.js") }}

    {{ javascript_include("js/frontend/register-next.js") }}
{% endblock %}

