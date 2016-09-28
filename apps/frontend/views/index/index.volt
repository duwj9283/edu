{% extends "template/basic-v3.volt" %}

{% block pageCss %}
	{{ stylesheet_link("css/frontend/web/index.css") }}
{% endblock %}

{% block content %}
{% include "template/banner.volt" %}
<div class="content-wrap">
   <div class="container">
      <div class="column-wrap notice">
         <div class="column-title">
            <div class="row">
               <div class="col-sm-1"><h2 class="column-name"><i class="icon icon-notice"></i>公告</h2></div>
               <div class="col-sm-9">
                  <ul class="notice-list" id="js-notice">
                     {% for index, list in data['newsList'] %}
                     {% if index%2==0 %}
                     <li>
                        <div class="row">
                           {% if data['newsList'][index] is defined %}
                           <div class="col-sm-4"><a href="/news#{{ data['newsList'][index]['id']}}" target="_blank">{{ data['newsList'][index]['title'] }}</a></div>
                           {% endif %}
                           {% if data['newsList'][index+1] is defined %}
                           <div class="col-sm-4"><a href="/news#{{ data['newsList'][index+1]['id']}}" target="_blank">{{ data['newsList'][index+1]['title'] }}</a></div>
                           {% endif %}
                           {% if data['newsList'][index+2] is defined %}
                           <div class="col-sm-4"><a href="/news#{{ data['newsList'][index+2]['id']}}" target="_blank">{{ data['newsList'][index+2]['title'] }}</a></div>
                           {% endif %}
                        </div>
                     </li>
                     {% endif %}
                     {% endfor %}
                  </ul>
               </div>
               <div class="col-sm-2 text-right"><a href="/news" class="more">更多</a></div>
            </div>
         </div>
      </div>
      <!-- 公告 end -->
      <div class="column-wrap live">
         <div class="column-title">
            <div class="row">
               <div class="col-sm-2"><h2 class="column-name"><i class="icon icon-live"></i>直播课堂</h2></div>
               <div class="col-sm-10 text-right"><a href="/live" class="more">更多</a></div>
            </div>
         </div>
         <div class="column-content">
            <div class="row">
               {% for index, list in data['liveList'] %}
               <div class="col-sm-3">
                  <div class="item live-item">
                     <a href="/live/index/detail/{{ list['id']}}">
                        <div  class="item-pic">
                           {% if list['live_status'] == 0%}
                           <i class="icon icon-wait"></i>
                           {% elseif list['live_status'] == 1%}
                           <i class="icon icon-ing"></i>
                           {% elseif list['live_status'] == 2%}
                           <i class="icon icon-end"></i>
                           {% endif %}
                           <img src="/frontend/source/getFrontImageThumb/live/{{ list['id']}}/290/162" width="290" height="162" class="item-pic" alt="">
                        </div>
                        <div class="item-tit ellipsis">
                           {{ list['name']}}
                        </div>
                        <div class="item-b clearfix">
                           <span class="time pull-right">{{ list['start_time']}} 开始</span>
                           <span class="author">BY {% if list['userInfo']['nick_name'] %}{{ list['userInfo']['nick_name']}}{% else %}{{ list['userInfo']['realname']}}{% endif %}</span>
                        </div>
                     </a>
                  </div>
               </div>
               {% endfor %}
            </div>
         </div>
      </div>
      <!-- 直播 end -->
      <div class="column-wrap file-wrap">
         <div class="column-title">
            <div class="row">
               <div class="col-sm-2"><h2 class="column-name"><i class="icon icon-file"></i>资源中心</h2></div>
               <div class="col-sm-10 text-right"><a href="/resource" class="more">更多</a></div>
            </div>
         </div>
         <div class="column-content clearfix">
            <div class="sidebar pull-right">
               <div class="tab-wrap" >
                  <div class="tab-tit">
                     <ul class="clearfix">
                        <li class="active"><a href="#a" data-toggle="tab">资源贡献榜</a></li>
                        <li><a href="#b" data-toggle="tab">热门资源</a></li>
                     </ul>
                  </div>
                  <div class="tab-cont tab-content">
                     <ul class="tab-pane active" id="a">
                        {% for index, list in data['maxPushUserList'] %}
                        <li><a href="/user/zone/{{ list['uid']}}" target="_blank" class="clearfix"><b class="pull-left">{{index+1}}</b><span  class="name ellipsis pull-left">{{list['nick_name']}}</span><span class="count pull-right">{{list['push_file_count']}}</span></a></li>
                        {% endfor %}
                     </ul>
                     <ul class="tab-pane" id="b">
                        {% for index, list in data['maxDownloadFiles'] %}
                        <li><a href="/file/detail/{{ list['file_id']}}" class="clearfix"><b class="pull-left">{{index+1}}</b><span  class="name ellipsis pull-left">{{list['file_name']}}</span><span class="count pull-right">{{list['download_count']}}</span></a></li>
                        {% endfor %}
                     </ul>
                  </div>

               </div>
            </div>
            <div class="content">
               <div class="row file-list">
                  {% for index, list in data['fileList'] %}
                  <div class="col-sm-3">
                     <div class="item file-item">
                        <a href="/file/detail/{{ list['file_id']}}">
                           <div class="item-pic" >
                              {% if list['file_type'] == 2 %}
                              <div class="icon-play"></div>
                              <img src="/api/source/getImageThumb/{{ list['file_id']}}/224/124" width="224" height="124" alt="">
                              {% elseif list['file_type'] == 3 %}
                              <img src="/api/source/getImageThumb/{{ list['file_id']}}/224/124" width="224" height="124" alt="">
                              {% elseif list['file_type'] == 4 %}
                              <div class="icon icon-mp3"></div>
                              {% elseif list['file_type'] >= 5 %}
                              <div class="icon icon-{{ list['ext']}}"></div>
                              {% endif %}
                           </div>
                           <div class="item-tit ellipsis">
                              {{ list['push_file_name']}}
                           </div>
                           <div class="item-b clearfix">
                              <span class="time pull-right">{{ list['subject_name']}}</span>
                              <span class="author">BY {% if list['userInfo']['nick_name'] %}{{ list['userInfo']['nick_name']}}{% else %}{{ list['userInfo']['realname']}}{% endif %}</span>
                           </div>
                        </a>
                     </div>
                  </div>
                  {% endfor %}
               </div>
            </div>
         </div>
      </div>
      <!-- 资源中心 end -->
      <div class="column-wrap mico">
         <div class="column-title">
            <div class="row">
               <div class="col-sm-2"><h2 class="column-name"><i class="icon icon-mico"></i>精品微课</h2></div>
               <div class="col-sm-10 text-right"><a href="/mico/publist" class="more">更多</a></div>
            </div>
         </div>
         <div class="column-content">
            <div class="row">
               {% for index, list in data['mlessonList'] %}
               <div class="col-sm-3">
                  <div class="item mico-item">
                     <a href="/mico/detail/{{list['id']}}">
                        <div  class="item-pic">
                           <i class="icon icon-play"></i>
                           <img src="/frontend/source/getFrontImageThumb/mlesson/{{ list['id']}}/290/162" width="290" height="162" class="item-pic" alt="">
                        </div>
                        <div class="item-tit clearfix">
                           <span class="subject pull-right">{{ list['subject_name']}}</span>
                           <span class="tit">{{ list['title']}}</span>
                        </div>
                        <div class="item-b clearfix">
                           <span class="pull-right">{{ list['studyCount']}}人已学</span>
                           <span class="author">BY {% if list['userInfo']['nick_name'] %}{{ list['userInfo']['nick_name']}}{% else %}{{ list['userInfo']['realname']}}{% endif %}</span>
                        </div>
                     </a>
                  </div>
               </div>
               {% endfor %}

            </div>
         </div>
      </div>
      <!-- 微课 end -->
      <div class="column-wrap course">
         <div class="column-title">
            <div class="row">
               <div class="col-sm-2"><h2 class="column-name"><i class="icon icon-course"></i>在线课程</h2></div>
               <div class="col-sm-10 text-right"><a href="/course" class="more">更多</a></div>
            </div>
         </div>
         <div class="column-content">
            <div class="row">
               {% for index, list in data['lessonList'] %}
               <div class="col-sm-3">
                  <div class="item course-item">
                     <a href="/course/detail/{{ list['id']}}">
                        <div  class="item-pic">
                           <img src="/frontend/source/getFrontImageThumb/lesson/{{ list['id']}}/290/162" width="290" height="162"  class="item-pic" alt="">
                        </div>
                        <div class="item-tit clearfix">
                           <span class="subject pull-right">{{ list['subject_name']}}</span>
                           <span class="tit">{{ list['title']}}</span>
                        </div>
                        <div class="item-b clearfix">
                           <span class="pull-right">{{ list['study_count']}}人已学</span>
                           <span class="author">BY {% if list['userInfo']['nick_name'] %}{{ list['userInfo']['nick_name']}}{% else %}{{ list['userInfo']['realname']}}{% endif %}</span>
                        </div>
                     </a>
                  </div>
               </div>
               {% endfor %}
            </div>
         </div>
      </div>
      <!-- 课程 end -->
      <div class="column-wrap teacher">
         <div class="column-title">
            <div class="row">
               <div class="col-sm-2"><h2 class="column-name"><i class="icon icon-teacher"></i>名师空间</h2></div>
               <div class="col-sm-10 text-right"><a href="/zone" class="more">更多</a></div>
            </div>
         </div>
         <div class="column-content">
            <div class="row">
               {% for index, list in data['userZoneList'] %}
               <div class="col-sm-3">
                  <div class="teacher-item clearfix">
                     <a href="/user/zone/{{ list['userInfo']['uid']}}" >
                        <img src="{% if list['userInfo']['headpic'] %}/frontend/source/getFrontImageThumb/header/{{ list['userInfo']['uid']}}/165/165" {% else %}/img/frontend/camtasiastudio/default-avatar.png{% endif %}" width="165" height="165" class="pull-left">
                        <div class="info">
                           <p class="name">{% if list['userInfo']['nick_name'] %}{{ list['userInfo']['nick_name']}}{% else %}{{ list['userInfo']['realname']}}{% endif %}</p>
                           <p>{{ list['userInfo']['job']}}</p>
                           <p>主讲课程：{{ list['subject_name']}}</p>
                           <p>{{ list['userInfo']['follow_count']}}人已关注</p>
                        </div>
                     </a>
                  </div>
               </div>
               {% endfor %}
            </div>
         </div>
      </div>
      <!-- 教师 end -->
   </div>
</div>
{% endblock %}
{% block pageJs %}
	{{ javascript_include("js/frontend/less2/layer/layer.js") }}
   <script type="text/javascript">
      $(function(){
         var _notice = $('#js-notice');
         var _height = _notice.find('li:first').outerHeight(true);
         var _timer = 4000;
         var _noticeTimer = setInterval(scrollTop,_timer);
         function scrollTop () {
            _notice.animate({"marginTop": -_height}, function () {
               _notice.css({'marginTop':0});
               _notice.find('li:first').appendTo(_notice);
            });
         };
         _notice.on('mouseenter',function(){
            clearInterval(_noticeTimer)
         })
         _notice.on('mouseleave',function(){
            _noticeTimer = setInterval(scrollTop,_timer);
         })
      });
   </script>
{% endblock %}

