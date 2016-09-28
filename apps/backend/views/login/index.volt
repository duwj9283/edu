<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>INSPINIA | Login</title>

    {{ stylesheet_link("3rdpart/bootstrap/css/bootstrap.min.css") }}
    {{ stylesheet_link("3rdpart/font-awesome/css/font-awesome.css") }}

    {{ stylesheet_link("3rdpart/animate/animate.css") }}
    {{ stylesheet_link("css/backend/style.css") }}
    {{ stylesheet_link("3rdpart/simple-slider/css/simple-slide.css") }}
    <style type="text/css">
        .randImage{width: 100px;cursor: pointer;}
        .alert{display: none;}
    </style>
</head>

<body class="gray-bg">
<div class="middle-box text-center loginscreen animated fadeInDown">
    <div>
        <div>
            <h1 class="logo-name">IN+</h1>
        </div>
        <h3>Welcome to IN+</h3>
        <p>Perfectly designed and precisely prepared admin theme with over 50 pages with extra new web app views.
            <!--Continually expanded and constantly improved Inspinia Admin Them (IN+)-->
        </p>
        <p>Login in. To see it in action.</p>
        <form class="m-t" role="form" id="loginform" action="/backend/login/doLogin" onsubmit="return false;">
            <div id="submit_alert" class="alert alert-success">
                <span></span>
            </div>
            <div class="form-group">
                <input type="text" id="username" name="username" class="form-control" placeholder="用户名" required="">
                <div id="username_alert" class="alert alert-danger">
                    <span></span>
                </div>
            </div>

            <div class="form-group">
                <input type="password" id="password" name="password" class="form-control" placeholder="密码" required="">
                <div id="password_alert" class="alert alert-danger">
                    <span></span>
                </div>
            </div>
            <div class="form-group">
                <div id="simple-slider" class="scale dragdealer">
                    <div class="scale-in" style="width: 31%;">
                        <span class="scale-bar handle" style="perspective: 1000px; backface-visibility: hidden; transform: translateX(115px);"></span>
                    </div>

                    <div class="scale-text">
                        <span class="scale-textin">请按住滑块，拖动到最右边</span>
                    </div>
                </div>
            </div>

            {#<div class="input-group">#}
                {#<input type="text" id="authcode" name="authcode" placeholder="验证码" class="form-control" required="">#}
                {#<div class="input-group-btn">#}
                    {#<img class="randImage" border="0" src="" name="admincode" alt="点击重置验证码"/>#}
                {#</div>#}
            {#</div>#}
            <div id="authcode_alert" class="alert alert-danger alert-dismissable">
                <span></span>
            </div>
            <input type="hidden" id="authcode" name="authcode" placeholder="验证码" class="form-control" required="">
            <button style="margin-top: 10px;" id="login_but" type="button" class="btn btn-primary block full-width m-b">登陆+</button>
        </form>
        <p class="m-t"> <small>Inspinia we app framework base on Bootstrap 3 &copy; 2014</small> </p>
    </div>
</div>

<!-- Mainly scripts -->
{{ javascript_include("3rdpart/jquery/jquery.min.js") }}
{{ javascript_include("3rdpart/bootstrap/js/bootstrap.min.js") }}
{{ javascript_include("js/backend/common.js") }}
{#滑动验证#}
{{ javascript_include("3rdpart/simple-slider/dragdealer.js") }}
{{ javascript_include("3rdpart/simple-slider/stringExt.js") }}
<script type="text/javascript">
$(function () {
    $("#username").focus();
    init_slider();
});
function init_slider()
{
    var slider_valid = 0;
    $('.auth_lay_body input').prop("readonly", false);
    new Dragdealer('simple-slider', {
        animationCallback: function(x, y) {
            var percent = Math.round(x * 100);
            $(".scale-in").css("width", percent+"%");
        },
        dragStopCallback: function(x, y){
            var that = this;
            slider_valid = Math.floor(x);
            if (slider_valid) {
                that.disable();
                $.get('/backend/ajax/getCheckCode',function (e) {
                    $("#authcode").val(e);
                });
            }
            else
            {
                that.setValue(0,0);
            }
        }
    });
}
</script>
</body>

</html>
