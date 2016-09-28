{% extends "template/basic-v2.volt" %}
{% block pageCss %}
    {{ stylesheet_link("css/frontend/less/lessbase.css") }}
    {{ stylesheet_link("css/frontend/col-side.css") }}
    <style>
        .w1120{
            width: 60%;
            min-width: 1150px;
            margin: 0 auto;
        }
        .pri-list>li>label {
            padding-top: 10px;
        }
        .person-con.fr .activity-infor-list.person-activity-list{
            font-size: 12px;
        }
        .per-info-tit .header-search input[type="text"]{
            width:140px;
        }
    </style>
{% endblock %}
{% block content %}
    <div class="inner_body content">
        <div class="person-con-infor">
            <div class="container clearfix">
                {% include 'template/col-side.volt' %}
                <div class="body_container">
                    <div class="per-info-tit per-info-tit-blue margin-top-40 clearfix">
                        <span class="fl">活动管理</span>
                        <select class="pri-input fl">
                            <option>创建时间</option>
                            <option>创建时间</option>
                            <option>创建时间</option>
                        </select>
                        {#<a href="javascirpt:void(0)" class="fb-activity fr">活动发布</a>#}
                        <div class="fr header-search">
                            <button></button>
                            <input type="text" placeholder="标题+文件格式" name="title">
                        </div>
                    </div>
                    <div class="line margin-top-10"></div>
                    <ul class="activity-infor-list clearfix live-list person-activity-list">

                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- list-template-->
    <script id="templateList" type="text/html">
        <li>
            <div class="">
                <a href="/activity/create" class="create-img"><img src="/img/frontend/camtasiastudio/create_img.png"  /></a>
                <p class="create-text">创建活动</p>
            </div>
        </li>
        <%each list as value%>
        <li data-id="<%value.id%>">
            <div class="">
                <a href="/activity/create/<%value.id%>" class="live-img-box">
                    <img src="/source/getFrontImageThumb/activity/<%value.id%>/260/120">
                    <b></b>
                </a>
                <a href="" class="live-tit"><%value.title%></a>
                <div class=" activity-text">
                    <p class="clearfix"><span class="fl time-ico inner-ico"><%value.start_time_s%>  </span> <span class="fr"><%value.start_time_e%> </span></p>
                    <p class="clearfix"><span class="fl time-ico inner-ico"><%value.end_time_s%>  </span> <span class="fr"><%value.end_time_e%></span></p>
                    <p><span class="loction-ico  inner-ico"><%value.address%></span></p>
                </div>
                <div class="activity-pk activity-pk02 clearfix">
                    <a href="/activity/create/<%value.id%>" class="fl "><span class="tab-ico editor-btn">编辑</span></a>
                    <a href="javacript:void(0);" class="fl"><span class="tab-ico del-btn">删除</span></a>
                    <a href="javacript:void(0);" class="fl" data-id="<%value.id%>"><span class="tab-ico ba-man">报名管理</span></a>
                </div>
            </div>
        </li>
        <%/each%>
        <!-- 循环体 item end -->

    </script>
	<div class="activity-manage-popLayer" style="display:none;" >
      <table>
        <thead>
          <tr>
            <th>编号</th>
            <th>姓名</th>
            <th>电话</th>
            <th>报名时间</th>
            <th>是否缴费</th>
            <th>报名审核</th>
            <th>填表信息</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>1</td>
            <td>gfhdfhfg</td>
            <td>1222222222</td>
            <td>1:36:00</td>
            <td>否</td>
            <td class="checked-no"></td>
            <td class="arrow-btn" data-id="1"></td>
          </tr>
          <tr class="info info-1">
             <td colspan="7">身高：10000  服装尺码：xxl</td>
          </tr>
          <tr>
            <td>2</td>
            <td>gfhdfhfg</td>
            <td>1222222222</td>
            <td>1:36:00</td>
            <td>否</td>
            <td class="checked-no"></td>
            <td class="arrow-btn" data-id="2"></td>
          </tr>
          <tr class="info info-2">
             <td colspan="7">身高：10000  服装尺码：xxl</td>
          </tr>

        </tbody>
      </table>
    </div>
{% endblock %}
{% block pageJs %}
    {{ javascript_include("js/frontend/col-side.js") }}
    {{ javascript_include("js/frontend/less2/layer/layer.js") }}
    {{ javascript_include("js/frontend/basic.js") }}
    {{ javascript_include("3rdpart/template/template.js") }}
    {#活动js#}
    {{ javascript_include("js/frontend/activity/createModel.js") }}
    {{ javascript_include("js/frontend/activity/manage.js") }}

{% endblock %}