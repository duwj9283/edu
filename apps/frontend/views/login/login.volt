{% extends "template/basic-v2.volt" %}
{% block pageCss %}
    {{ stylesheet_link("css/frontend/login.css") }}
{% endblock %}
{% block content %}



    <div class="main">
        <div class="login-banner">
            <div class="container text-center">
                <a href="/login/login" class="btn active">登  录<span class="caret"></span></a>
                <a href="/login/register" class="btn">注 册</a>
            </div>
        </div>
        <div class="login-wrap">
            <div class="container">
                <div class="login-block clearfix">
                    <form name="form-login" onsubmit="return false">
                        <div class="form-wrap">
<!--                            <h2>登录</h2>-->
                            <div class="form">
                                <div class="field  login_username">
                                    <div class="field-input"><span class="user-icon icon"></span>
                                        <input type="text" placeholder="请输入用户名" class="txt"  name="username">
                                    </div>
                                </div>
                                <div class="field  login_password">
                                    <div class="field-input"><span class="pw-icon icon"></span>
                                        <input type="password" placeholder="请输入密码" class="txt"  name="password">
                                    </div>
                                </div>
                                <div class="field clearfix">
                                    <a href="/login/forget" class="pull-right">忘记密码</a>
                                    <input value="14" checked="" name="stay-login" type="checkbox" class="stay-login"
                                           id="stay-login">
                                    <label for="stay-login">记住密码</label>
                                </div>
                                <div class="field ">
                                    <input class="btn-org btn" data-role="submit" type="submit" value="登 录"></div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


{% endblock %}
{% block pageJs %}
    {{ javascript_include("js/frontend/login-page.js") }}
{% endblock %}
