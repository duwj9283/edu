<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1">
    <meta name="renderer" content="webkit">
    <title>{{ title }}</title>
    {{ stylesheet_link("3rdpart/bootstrap/css/bootstrap.min.css") }}
    {{ stylesheet_link("/css/frontend/web/common.css") }}
    {% block pageCss %}
    {% endblock %}
</head>
<body>
<div class="header" id="top" name="top">
    <div class="header-top">
        <div class="container">
            <div class="row">
                <div class="col-sm-4"><a href="" class="logo"><img src="/{{ logo }}" alt=""></a></div>
            </div>
        </div>
    </div>
    <div class="header-nav">
        <div class="container">
            <div class="row">

                <div class="col-sm-8">
                    <ul class="menu clearfix" id="js-header-menu">
                        <li class="nav-current"><a href="/frontend">发现</a></li>
                        <li><a href="/resource">资源中心</a></li>
                        <li><a href="/course">在线课程</a></li>
                        <li><a href="/live">直播课堂</a></li>
                        <li><a href="/mico/publist">精品微课</a></li>
                        <li><a href="/zone">名师空间</a></li>
                        <li><a href="/news">公告中心</a></li>
                    </ul>
                </div>
                <div class="col-sm-4">
                    {% if bSignedIn %}
                    <div class="nav-user-info">
                        <!--                            <a href="#" class="icon icon-email"><i></i></a>-->
                        {% if msgStatus > 0%}
                        <a href="/user/message" rel="nofollow" class="icon icon-msg"><i></i></a>
                        {% else %}
                        <a href="javascript:;" rel="nofollow" class="icon icon-msg"></a>
                        {% endif%}
                        <div class="pull-left dropdown">
                            {% if userInfo.nick_name %}
                            <a href="javascript:;" class="user-name"  data-toggle="dropdown"  id="js-user-name"  data-uid="{{ userInfo.uid }}" data-token="{{ user.user_token }}">{{ userInfo.nick_name }}<i class="icon-arrow"></i></a>
                            {% else %}
                            <a href="javascript:;" class="user-name"  data-toggle="dropdown"  id="js-user-name"  data-uid="{{ userInfo.uid }}" data-token="{{ user.user_token }}">{{ userInfo.realname }}<i class="icon-arrow"></i></a>
                            {% endif %}
                            <ul class="user-top-menu dropdown-menu">
                                <li><a href="/file">我的文件</a></li>
                                {% if userInfo.role_id !=1 %}
                                <li><a href="/user/zone">我的空间</a></li>
                                {% endif%}
                                <li><a href="/user">设置</a></li>
                                <li role="separator" class="divider"></li>
                                <li class="set-out"><a href="/login/signout">退出</a></li>
                            </ul>
                        </div>
                    </div>
                    {% else %}
                    <ul class="nav-login">
                        <li class="log"><a href="/login/login">登录</a></li>
                        <li class="line">|</li>
                        <li class="rel"><a href="/login/register">注册</a></li>
                    </ul>
                    {% endif %}
                </div>
            </div>
        </div>
    </div>
</div>
{% block content%}
{% endblock %}
<div class="footer">
    <img src="/{{ logo }}" class="logo" alt="">
    <div class="copyright text-center">
        {{ footerInfo }}
    </div>
    <a href="#top" class="to-top" title="返回顶部">TOP</a>
</div>
{% block commonModal %}
{% endblock %}
<!--登录弹出层-->
<script id="loginTemplate" type="text/html">
    <div id="js-login-poper">
        <div id="login-frame">
            <a href="javascript:;" rel="nofollow" class="close-login js-close-login">x</a>
            <form name="form-login" onsubmit="return false">
                <div class="form-wrap">
                    <h2>登录</h2>
                    <div class="form">
                        <div class="form-group  login-username">
                            <div class="field-input"><span class="user-icon icon"></span>
                                <input type="text" placeholder="请输入用户名" class="txt"  name="username">
                            </div>
                        </div>
                        <div class="form-group  login-password">
                            <div class="field-input"><span class="pw-icon icon"></span>
                                <input type="password" placeholder="请输入密码" class="txt"  name="password">
                            </div>
                        </div>
                        <div class="form-group clearfix">
                            <a href="/login/forget" class="pull-right">忘记密码</a>
                            <input value="14" checked="" name="stay-login" type="checkbox" class="stay-login"
                                   id="stay-login">
                            <label for="stay-login">记住密码</label>
                        </div>
                        <div class="form-group ">
                            <input class="btn-org btn" data-role="submit" type="submit" value="登 录">
                        </div>
                        <div class="form-reg">没有账户？<a href="/login/register">注册</a></div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</script>
</body>
{{ javascript_include("3rdpart/jquery/jquery.min.js") }}
{{ javascript_include("3rdpart/bootstrap/js/bootstrap.min.js") }}
<script type="text/javascript">
    // 设置导航高亮
    var pathname =location.pathname;
    $("#js-header-menu a").each(function(){
        if(pathname.indexOf($(this).attr('href'))>=0){
            $(this).closest('li').addClass('nav-current').siblings().removeClass('nav-current');
        }
    })
</script>
{% block pageJs%}
{% endblock %}
</html>
