<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ title }}</title>

    {{ stylesheet_link("3rdpart/bootstrap/css/bootstrap.min.css") }}
    {{ stylesheet_link("3rdpart/font-awesome/css/font-awesome.css") }}
    {{ stylesheet_link("css/backend/style.css") }}

    {#在子类里添加替换css#}
    {% block css%}
    {% endblock %}
</head>

<body>

<div id="wrapper">
    <nav class="navbar-default navbar-static-side" role="navigation">
        <div class="sidebar-collapse">
            <ul class="nav metismenu" id="side-menu">
                <li class="nav-header">
                    <div class="dropdown profile-element"> <span>
                        {{ image("img/backend/profile_small.jpg","class":"img-circle", "alt":"image") }}
                             </span>
                        <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                            <span class="clear"> <span class="block m-t-xs"> <strong class="font-bold">{{ userInfo['realname'] }}</strong>
                             </span> <span class="text-muted text-xs block">Art Director <b
                                            class="caret"></b></span> </span> </a>
                        <ul class="dropdown-menu animated fadeInRight m-t-xs">
                            <li><a href="profile.html">Profile</a></li>
                            <li><a href="contacts.html">Contacts</a></li>
                            <li><a href="mailbox.html">Mailbox</a></li>
                            <li class="divider"></li>
                            <li><a href="/backend/logout">Logout</a></li>
                        </ul>
                    </div>
                    <div class="logo-element">
                        IN+
                    </div>
                </li>
                <li>
                    <a href="index.html"><i class="fa fa-user"></i><span class="nav-label">账号管理</span> <span
                                class="fa arrow"></span></a>
                    <ul class="nav nav-second-levuserel">
                        <li class="active"><a href="/backend/index/teacher/"><i class="fa fa-user"></i>教师账号管理</a></li>
                        <li><a href="/backend/index/student"><i class="fa fa-user"></i>学生账号管理</a></li>
                    </ul>
                </li>
                <li>
                    <a href="index.html"><i class="fa fa-clipboard"></i><span class="nav-label">全部文件</span> <span
                                class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        <li class="active"><a href="index.html"><i class="fa fa-file"></i>我的文件</a></li>
                        <li><a href="dashboard_2.html"><i class="fa fa-share"></i>您分享的文件</a></li>
                        <li><a href="dashboard_3.html"><i class="fa fa-star"></i>收藏</a></li>
                    </ul>
                </li>

            </ul>

        </div>
    </nav>


    <div id="page-wrapper" class="gray-bg">
        <div class="row border-bottom">
            <nav class="navbar navbar-static-top white-bg" role="navigation" style="margin-bottom: 0">
                <div class="navbar-header">
                    <a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#"><i class="fa fa-bars"></i> </a>
                    <form role="search" class="navbar-form-custom" method="post" action="#">
                        <div class="form-group">
                            <input type="text" placeholder="查找" class="form-control" name="top-search" id="top-search">
                        </div>
                    </form>
                </div>
                <ul class="nav navbar-top-links navbar-right">
                    <li>
                        <a href="/backend/index/signout">
                            <i class="fa fa-sign-out"></i>退出
                        </a>
                    </li>
                </ul>

            </nav>
        </div>

        {% block content%}
        {% endblock %}

        <div class="footer">
            <div class="pull-right">
                10GB of <strong>250GB</strong> Free.
            </div>
            <div>
                <strong>Copyright</strong> Example Company &copy; 2014-2015
            </div>
        </div>

    </div>
</div>

{{ javascript_include("3rdpart/jquery/jquery.min.js") }}
{{ javascript_include("3rdpart/bootstrap/js/bootstrap.min.js") }}
{{ javascript_include("3rdpart/metisMenu/jquery.metisMenu.js") }}
{{ javascript_include("3rdpart/slimscroll/jquery.slimscroll.min.js") }}

{% block plugin_js %}
{% endblock %}

{{ javascript_include("3rdpart/inspinia/inspinia.js") }}
{{ javascript_include("3rdpart/pace/pace.min.js") }}

{#在子类里添加替换js#}
{% block page_js%}
{% endblock %}

</body>

</html>
