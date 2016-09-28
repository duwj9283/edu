
{% extends "template/basic-v2.volt" %}
{% block pageCss %}
    {{ stylesheet_link("css/frontend/login.css") }}

{% endblock %}
{% block content %}
    <div class="main">
        <div class="reg-wrap">
            <div class="container">
                <div class="reg-block">

                    <div class="form-wrap">
                        <h2>忘记密码</h2>

                        <form name="register-form" onsubmit="return false">
                            <input type="hidden" name="type" value="forget">

                            <div class="form">
                                <div class="field  login_username">
                                    <div class="field-input clearfix"><span class="user-icon icon"></span>
                                        <input type="tel" placeholder="请输入需要找回密码的用户名" class="txt" name="username"></div>
                                </div>
                                <!--<div class="field  login_password">-->
                                    <!--<div class="clearfix">-->
                                        <!--<div class="field-input pull-left"><span class="pw-icon icon"></span>-->
                                            <!--<input type="text" placeholder="请输入验证码" class="txt yzm" name="code"-->
                                                   <!--required></div>-->
                                        <!--<input type="button" class="btn btn-send pull-right" value="发送验证码">-->
                                    <!--</div>-->
                                <!--</div>-->
                                <!--<div class="field  login_password">-->
                                    <!--<div class="field-input clearfix"><span class="pw-icon icon"></span>-->
                                        <!--<input type="password" placeholder="请输入密码" class="txt" name="password">-->
                                    <!--</div>-->
                                <!--</div>-->
                                <!--<div class="field  login_password">-->
                                    <!--<div class="field-input clearfix"><span class="pw-icon icon"></span>-->
                                        <!--<input type="password" placeholder="请再次输入密码" class="txt" name="password_repeat"-->
                                               <!--required></div>-->
                                <!--</div>-->

                                <div class="field">

                                    <input class="btn-org btn" data-role="submit" type="submit" value="找回密码"></div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

{% endblock %}
{% block pageJs %}
    {{ javascript_include("js/frontend/less2/layer/layer.js") }}
    <script type="text/javascript">
        var $form = $('form[name="register-form"]'),$username = $('input[name="username"]');
        $form.submit(function(){
            var username = $.trim($username.val());
            if(!username){
                return false;
            }
            $.post('/api/signup/forgetPassword', {username:username}, function(data){
               if(data.code == 0){
                   layer.msg('重置密码提交成功，等待管理员重置')
               }
               else{
                   layer.msg(data.msg)
               }
            }, 'json');
        });

    </script>
{% endblock %}


