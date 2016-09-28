{% extends "template/basic-v2.volt" %}
{% block pageCss %}
{{ stylesheet_link("css/frontend/basic.css")  }}
{{ stylesheet_link("css/frontend/col-side.css")  }}
{{ stylesheet_link("css/frontend/less2/common.css")  }}
{{ stylesheet_link("css/frontend/less2/home.css")  }}
{{ stylesheet_link("css/frontend/less2/edit.css")  }}
<style>
    /*****发布图标******/
    #classList .item .operate .fa-push {
        width: 18px;
        height: 18px;
        margin-right: 3px;
        display: inline-block;
        vertical-align: middle;
    }

    .operate .fa-push {
        background: url(/img/frontend/home/5.png) no-repeat;
        display: block;
    }
</style>
{% endblock %}
{% block content %}
<div class="inner_body">
    <div class="center_wrap container">
        {% include 'template/col-side.volt' %}
        <div class="body_container">
            <div class="title clearfix"><a href="/course/create" class="post pull-right"><i class="fa-post"></i><span>创建课程</span></a>

                <h2 class="pull-left borc1 mr40">课程管理</h2>
            </div>
            <div id="classList">
                <div class="list cl">
                </div>
                <div class="page-wrap"></div>
            </div>
            <!-- 列表结束  clasList end -->
        </div>
        <!-- 主体内容 content end -->
    </div>
</div>
<div class="popLayer">
    <div class="content js-poplayer-content" style=""></div>
</div>
<!-- list-template /img/frontend/less2/pic.jpg-->
<script id="templateList" type="text/html">
    <%each list as item%>
    <div class="item clearfix bm c_1" data-id="<%item.id%>">
        <div class="pic">
            <img src="/frontend/source/getFrontImageThumb/lesson/<%item.id%>/320/180" width="320" height="180" alt="">
            <a href="/course/detail/<%item.id%>" target="_blank" class="fa-play"></a>
        </div>
        <div class="info">
            <h2 class="c_1"><%item.title%></h2>

            <div class="p1 cl"><span class="fr">发布时间：<%item.addtime%></span>
                <%if item.type==1%><span class="c_2">非连载课程<span><%/if%>
                <%if item.type==2%><span class="c_2">连载中</span><%/if%>
                <%if item.type==3%><span class="c_2">已完结</span><%/if%>
            </div>

            <div class="p2">最新章节：<% item.last_list.name%></div>
            <a href="javascript:;" class="js-study btn-study btn" rel="nofollow"><i class="icon-xqjk"></i>学情监控</a>
            <ul class="output cl">
                <li  data-id=""><span>学习人数：</span><span class="c_2"><%item.study_count%></span></li>
<!--                <li><span class="c_2 borc1"><%item.share_count%></span><span>分享</span></li>-->
                <li class="js-ask"><span>提问：</span><span class="c_2"><%item.ask_count%></span></li>
<!--                <li data-layer='{"type":"zy"}'><span class="c_2"><%item.question_count%></span><span>作业</span></li>-->
            </ul>
            <!--<div class="p3 cl"><b class="price fl  c_2 mr20 f16"><%if item.price>0%>￥<%item.price%><%else%>免费<%/if%></b><div class="state fl f14"><%if item.status ==1%><span class="yes c_2">已发布</span><%else%><span class="no c_5">未发布</span><%/if%></div></div>-->
            <div class="operate">
                {#<a href="#" class="mr10 "><i class="fa-share"></i>分享</a>#}
                <%if item.status ==1%>
                <a href="javascript:void(0);" class="mr10 js-push" data-type="2"><i class="fa-push"></i>取消发布</a>
                <%else%>
                <a href="javascript:void(0);" class="mr10 js-push" data-type="1"><i class="fa-push"></i>发布</a>
                <%/if%>
                <a href="/course/update/<%item.id%>"><i class="fa-edit"></i>编辑</a>
            </div>
        </div>
    </div>
    <%/each%>
    <!-- 循环体 item end -->
</script>
<script id="templateQuestion" type="text/html">
    <div id="question">
        <%each list as ask %>
            <% each ask as item index %>
                <% if index == 0%>
                <div class="item cl" id="js-<%item.id%>">
                    <div class="faceBar fl">
                        <img src="/frontend/source/getFrontImageThumb/header/<%item.uid%>/34/34" width="34" height="34" class="face"></div>
                    <div class="info">
                        <div class="t cl">
                            <span class="name"><a href="/user/zone/<%item.uid%>" ><%item.userName%></a></span></div>
                        <p class="msg"><%item.content%></p>
                        <p class="time">时间：<%item.create_time%></p>
                        <!--回复信息-->
                        <div class="replyCon cl" id="js-reply-<%item.id%>" style="display: none;">
                            <p style="color: #ff0000">老师回复:</p>
                        </div>
                        <!--回复信息--></div>
                    <a href="javascript:;" rel="nofollow" class="reply btn bc1">回复</a>
                    <div class="replayForm">
                        <textarea name="replay-content"></textarea>
                        <a href="javascript:;" rel="nofollow" data-refuid="<%item.uid%>" data-refid="<%item.id%>" class="btn bc1 js-replay-ask">回复</a></div>
                </div>
                <% /if %>
            <% /each%>
        <%/each%>
    </div>
</script>
<script id="templateStudent" type="text/html">
    <div id="student">
        <table>
            <thead>
            <tr>
                <th>学员</th>
                <th>开始时间</th>
                <th>完成课节</th>
                <th>完成习题</th>
                <th>笔记数</th>
                <th>提问</th>
                <th>总体进度%</th>
            </tr>
            </thead>
            <tbody>
            <%each list as item%>
            <tr>
                <td><% item.userInfo.nick_name%></td>
                <td><% item.start_time%></td>
                <td><% item.node%>节</td>
                <td>错:<% item.answer_false%>/答:<% item.answer_all%>/总:<% item.all%></td>
                <td><% item.note_count%></td>
                <td><% item.ask_count%></td>
                <td><% item.process%></td>
            </tr>
            <%/each%>
            </tbody>
        </table>
    </div>
</script>
{% endblock %}
{% block commonModal %}
{% endblock %}
{% block pageJs %}
{{ javascript_include("js/frontend/less2/layer/layer.js") }}
{{ javascript_include("js/frontend/col-side.js") }}
{{ javascript_include("js/frontend/basic.js") }}
{{ javascript_include("3rdpart/template/template.js") }}
{{ javascript_include("js/frontend/course/manageModal.js") }}
{{ javascript_include("js/frontend/course/manage.js") }}
<script type="text/javascript">

</script>

{% endblock %}