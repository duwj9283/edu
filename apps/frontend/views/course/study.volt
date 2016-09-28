{% extends "template/basic-v2.volt" %}
{% block pageCss %}
    {{ stylesheet_link("css/frontend/basic.css")  }}
    {{ stylesheet_link("css/frontend/col-side.css")  }}
    {{ stylesheet_link("css/frontend/basic.css")  }}
    {{ stylesheet_link("css/frontend/col-side.css")  }}
    {{ stylesheet_link("css/frontend/less2/common.css")  }}
    {{ stylesheet_link("css/frontend/less2/home.css")  }}
    {{ stylesheet_link("css/frontend/less2/edit.css")  }}

{% endblock %}
{% block content %}
    <div class="inner_body">
        <div class="center_wrap container">
            {% include 'template/col-side.volt' %}
            <div class="body_container">
                <div class="title cl">
                    <!--<div class="task-search fr">-->
                        <!--<input type="text" class="s-word txt2" placeholder="标题+文件格式">-->
                    <!--</div>-->
                    <h2 class="fl borc4 mr40">课程学习</h2>
                    <!--<select class="select-group fl">-->
                        <!--<option>创建时间</option>-->
                        <!--<option>创建时间</option>-->
                        <!--<option>创建时间</option>-->
                    <!--</select>-->
                </div>
                <div id="classList">
                    <div class="list cl">

                    </div>
                    <div class="page-wrap"></div>
                </div>
                <!-- 列表结束  clasList end -->
            </div>
        </div>
    </div>

<script id="templateList" type="text/html">
    <%each list as item%>
    <div class="item clearfix bm c_1" data-id="<%item.lesson.id%>">
        <div class="pic">
            <img src="/frontend/source/getFrontImageThumb/lesson/<%item.lesson.id%>/320/180" width="320" height="180" alt="">
            <a href="/course/detail/<%item.lesson.id%>" target="_blank" class="fa-play"></a>
        </div>
        <div class="info">
            <h2 class="c_1"><%item.lesson.title%></h2>
            <p class="p1 cl"><span class="fr"><%item.lesson.addtime%></span>
                <%if item.type==1%><span class="c_2">非连载课程<span><%/if%>
                <%if item.type==2%><span class="c_2">连载中</span><%/if%>
                <%if item.type==3%><span class="c_2">已完结</span><%/if%>
            </p>
            <p>时长：<% item.timeLone%></p>
            <p>讲师：<a href="/user/zone/<% item.userinfo.uid%>" target="_blank"><% item.userinfo.nick_name%></a></p>
            <p><%item.lesson.desc%></p>
            <!--<p class="p2">最新章节：常见的分页实现方式</p>-->
            <!--<ul class="output cl">-->
                <!--<li data-layer='{"type":"xs"}' data-id=""><span class="c_2"><%item.study_count%></span><span>人学习</span></li><li><span class="c_2 borc1"><%item.share_count%></span><span>分享</span></li><li  data-layer='{"type":"tw"}'><span class="c_2"><%item.ask_count%></span><span>提问</span></li><li data-layer='{"type":"zy"}'><span class="c_2"><%item.question_count%></span><span>作业</span></li>-->

            <!--</ul>-->
            <!--<div class="p3 cl"><b class="price fl  c_2 mr20 f16"><%if item.price>0%>￥<%item.price%><%else%>免费<%/if%></b><div class="state fl f14"><%if item.status ==1%><span class="yes c_2">已发布</span><%else%><span class="no c_5">未发布</span><%/if%></div></div>-->

        </div>
    </div>
    <%/each%>
    <!-- 循环体 item end -->
</script>
{% endblock %}
{% block commonModal %}
    <div class="popLayer"><div class="content" style=""></div></div>
{% endblock %}
{% block pageJs %}
    {{ javascript_include("js/frontend/basic.js") }}
    {{ javascript_include("3rdpart/template/template.js") }}
    {{ javascript_include("js/frontend/less2/mCustomScrollbar/jquery.mCustomScrollbar.js") }}
    {{ javascript_include("js/frontend/less2/scrollbar.js") }}
    {{ javascript_include("js/frontend/less2/layer/layer.js") }}
    {{ javascript_include("js/frontend/col-side.js") }}
    {{ javascript_include("js/frontend/course/studyModal.js") }}
    {{ javascript_include("js/frontend/course/study.js") }}
    <script type="text/javascript">

        $('#classList [data-layer]').on("click",function(){
            var iType=$(this).data("layer").type,oCon=null,oTit=null,iWidth=['600px',"90%"];

            //提问
            if(iType=='tw'){
                oTit="提问";
                iWidth=['740px',"90%"];
                oCon='<div id="question"><!--数据内容--><!--数据内容--><div class="item cl"><div class="faceBar fl"><img src="/img/frontend/less2/user.png"width="34"height="34"class="face"></div><div class="info"><div class="t cl"><a href="#"class="name">用户姓名</a></div><p class="msg">用户评论信息</p><p class="time">时间：两天前</p><!--回复信息--><div class="replyCon cl"><span class="fl">回复：</span><div class="info"><a href="#"class="name">用户姓名</a><p class="msg">用户评论信息</p><p class="time">时间：两天前</p></div></div><!--回复信息--></div><a href="javascript:;" class="reply btn bc1">回复</a><div class="replayForm"><textarea></textarea><a href="#" class="btn bc1">回复</a></div></div><!--数据内容--></div>';
            }
            //笔记
            if(iType=='bj'){
                oTit="笔记";
                iWidth=['740px',"90%"];
                oCon='<div class="notes"><div class="item cl"><div class="mark fr">第一章  课时1</div><div class="num fl">1</div><div class="info fl"><p>这一章比较难懂，知识点在于语法的区分。</p><p class="time c_5">时间：2016.02.10</p></div></div><!-- 数据获取循环体 item end --></div>';

            }
            $(".popLayer .content").html(oCon);
            layer.open({
                type: 1,
                title: oTit,
                shadeClose: true,
                move: false,
                area:iWidth,
                content: $(".popLayer"),

            });
        });
        //提问回复
        $('body').on("click",".reply",function(){
            $(this).hide();
            $(this).next().show();
        });
    </script>
{% endblock %}