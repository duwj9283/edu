
{% extends "template/basic-v2.volt" %}
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
<!--                    <h2>注册</h2>-->

                    <form name="register-form" onsubmit="return false" >
                        <input type="hidden" name="type" value="register">
                        <div class="form">
                            <div class="field  login_username">
                                <div class="field-input clearfix"><span class="user-icon icon"></span>
                                    <input type="tel" placeholder="请输入用户名" class="txt" name="username"></div>
                            </div>
                            <div class="field  login_password">
                                <div class="field-input clearfix"><span class="pw-icon icon"></span>
                                    <input type="password" placeholder="请输入密码" class="txt" name="password">
                                </div>
                            </div>
                            <div class="field  login_password">
                                <div class="field-input clearfix"><span class="pw-icon icon"></span>
                                    <input type="password" placeholder="请再次输入密码" class="txt" name="password_repeat"></div>
                            </div>

                            <div class="field">

                                <input class="btn-org btn" data-role="submit" type="submit" value="下一步"></div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

{% endblock %}
{% block pageJs %}
    {{ javascript_include("js/frontend/register.js") }}
    {{ javascript_include("js/frontend/less2/layer/layer.js") }}

{% endblock %}


