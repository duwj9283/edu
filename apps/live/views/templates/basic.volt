<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>{{ title }}</title>
	{{ stylesheet_link("3rdpart/bootstrap/css/bootstrap.min.css") }}
	{#{{ stylesheet_link("3rdpart/semantic/semantic.min.css") }}#}
	{{ stylesheet_link("css/frontend/less/common.css") }}
	{{ stylesheet_link("css/frontend/less/basic.css") }}
	{{ stylesheet_link("css/frontend/pop-modal.css") }}
	{{ stylesheet_link("3rdpart/simple-slider/css/simple-slide.css") }}
	{% block pageCss %}
	{% endblock %}
</head>

<body>
	<div class="w1200">
		<div class="header">
			<div class="w970 clearfix">
				<a href="/" class="header-logo fl">
					<img width="238" height="61" src="/img/frontend/home/logo.jpg"/>
				</a>
				<div style="" id="navbar-collapse-basic">
					<ul class="header-nav fl" >
						<li class="header_option find"><a href="/frontend">发现</a></li>
						<li ><a> | </a> </li>
						<li class="header_option source"><a href="/resource">资源</a></li>
						<li><a> | </a> </li>
						<li class="header_option course">
							<a class="" href="/course">课程</a>
						</li>
						<li><a> | </a> </li>
						<li class="header_option live"><a href="/live">直播</a></li>
						<li><a> | </a> </li>
						<li class="header_option camtasiastudio"><a href="/mico/publist">微课</a></li>
						<li><a> | </a> </li>
						<li class="header_option activity"><a href="/activity">活动</a></li>
						<li><a> | </a> </li>
						<li class="header_option group"><a href="/group">群组</a></li>
					</ul>
					<ul class="header-nav fr">
						<li class="btn_login"><a href="#">登录</a></li>
						<li><a>|</a></li>
						<li class="btn_register"><a href="#">注册</a></li>
					</ul>
					<div style="content:'';clear:both">
					</div>

				</div>
				<div style="clear:both;"></div>
			</div>
		</div>
		{% block content%}
		{% endblock %}
		<div id="footer">
		  <div class="w970">
		    <div class="copyright">
		      <p class="p_1">乐行云享<br>
		        致力于让信息技术深度融合教学应用，实现教育资源生态化建设！</p>
		      <p class="p_2">Copyright©  皖ICP备14013869号-4 乐行云享有限公司   Ldd</p>
		    </div>
		  </div>
		</div>
	</div>
	{% block commonModal %}
	{% endblock %}
	{% include "templates/bootstrap-modals.volt" %}
	{% include "templates/authContainer.volt" %}
	{% if !bSignedIn %}
		<div class="auth_wrap" style="display:none;"><a class="btn_login">登录</a><span></span><a class="btn_register">注册</a></div>
	{% else %}
		<div class="user_info_wrap" style="display:none;">
			<i class="user_headpic"><img style="width:60px;height:60px;" src="/img/frontend/tem_material/sc91.jpg"/></i>
			<label class="user_name_wrap" style="margin-left:20px;">
				<span class="user_name" targetid ="{{ userInfo.uid }}" >{{ userInfo.nick_name }}</span>
				<span id="token" style="display:none;" token="{{ user.user_token }}" ></span>
				<i class="spread_btn"></i>
			</label>
		</div>
	{% endif %}
	<div class="transparent-cover" id="transparent-cover"></div>
</body>

{{ javascript_include("3rdpart/jquery/jquery.min.js") }}
{{ javascript_include("3rdpart/bootstrap/js/bootstrap-modal.js") }}
{#滑动验证#}
{{ javascript_include("3rdpart/simple-slider/dragdealer.js") }}
{{ javascript_include("3rdpart/simple-slider/stringExt.js") }}
{{ javascript_include("js/frontend/login.js") }}
{% block pageJs%}
{% endblock %}
</html>
