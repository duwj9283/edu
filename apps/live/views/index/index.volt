{% extends "templates/basic-v3.volt" %}
{% block pageCss %}
{{ stylesheet_link("/css/frontend/web/list.css") }}
{% endblock %}

{% block content %}
<div class="banner-wrap">
	<div class="container">
		<div id="slide" class="carousel slide" data-ride="carousel">
			<!-- Indicators -->
			<ol class="carousel-indicators">
				{% for index, image in images %}
				<li data-target="#slide" data-slide-to="{{index}}" {%if index == 0%} class="active" {%endif%}></li>
				{% endfor %}
			</ol>
			<!-- Wrapper for slides -->
			<div class="carousel-inner" role="listbox">
				{% for index, image in images %}
				<div class="item {%if index == 0%}active{%endif%}">
					<img src="/{{ image }}" width="1210" height="500" />
				</div>
				{% endfor %}
			</div>
			<!-- Controls -->
			<a class="left carousel-control" href="#slide" role="button" data-slide="prev">
				<span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
				<span class="sr-only">Previous</span>
			</a>
			<a class="right carousel-control" href="#slide" role="button" data-slide="next">
				<span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
				<span class="sr-only">Next</span>
			</a>
		</div>
	</div>
</div>
<div class="content-wrap live-wrap">
	<div class="content-top">
		<div class="container clearfix">
			<div class="search pull-right">
				<div class="s-btn js-search-live"></div>
				<input type="text" class="keyword" name="live-keyword" placeholder="模糊查询">
			</div>
		</div>
	</div>
	<div class="content-body">
		<div class="container clearfix">
			<div class="sidebar pull-left">
				<div class="menu">
					<ul class="js-menu">
						<li class="active clearfix" data-status="0"><a href="javascript:;" rel="nofollow">全部直播</a></li>
						<li class="clearfix" data-status="1"><a href="javascript:;" rel="nofollow">即将开始</a></li>
						<li class="clearfix" data-status="2"><a href="javascript:;" rel="nofollow">正在直播</a></li>
						<li class="clearfix" data-status="3"><a href="javascript:;" rel="nofollow">已结束</a></li>
					</ul>
				</div>
			</div>
			<div class="content">
				<div class="title live-title clearfix">
					<h2>直播列表</h2>
				</div>
				<div class="list-wrap">
					<div class="live-list clearfix">
						<div class="row" id="js-list">

						</div>

					</div>
					<!-- 直播列表 end -->
					<div class="page-wrap"></div>
					<!-- 分页 -->
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/html" id="template-live">
	<%each publicLiveList as item  %>
	<div class="col-sm-4">
		<div class="item live-item">
			<a href="/live/index/detail/<% item.id %>">
				<div class="item-pic">
					<i class="icon icon-wait"></i>
					<% if item.live_status==0 %>
					<i class="icon icon-wait"></i>
					<% /if %>
					<% if item.live_status==1 %>
					<i class="icon icon-ing"></i>
					<% /if %>
					<% if item.live_status==2 %>
					<i class="icon icon-end"></i>
					<% /if %>
					<img src="/frontend/source/getFrontImageThumb/live/<%item.id%>/334/186" width="334" height="186" class="item-pic" alt="">
				</div>
				<div class="item-tit">
					<% item.name %>
				</div>
				<div class="item-b clearfix">
					<span class="time pull-right"><% item.start_time %>开始</span>
					<span class="author">BY <% item.userInfo.nick_name %></span>
				</div>
			</a>
		</div>
	</div>
	<%/each%>
</script>
{% endblock %}
{% block pageJs%}
	{{ javascript_include("/js/frontend/basic.js") }}
	{{ javascript_include('js/frontend/less2/layer/layer.js') }}
	{{ javascript_include("/3rdpart/template/template.js") }}
	{{ javascript_include("/js/frontend/live/liveModel.js") }}
	{{ javascript_include("/js/frontend/live/live.js") }}
{% endblock %}