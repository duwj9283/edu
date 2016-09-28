{% extends "template/basic-v2.volt" %}
{% block pageCss %}
    {{ stylesheet_link("css/frontend/course.css")  }}
    {{ stylesheet_link("css/frontend/basic.css")  }}
    {{ stylesheet_link("css/frontend/less2/edit.css") }}
    {{ stylesheet_link("css/frontend/col-side.css")  }}
    {{ stylesheet_link("js/frontend/webUpload/webuploader.css")  }}
    {{ stylesheet_link("css/frontend/label-pop.css")  }}
    {{ stylesheet_link("css/frontend/custompart/file-select-popover.css")  }}
{% endblock %}
{% block content %}
    {#{{ image(lesson.getFrontTmp(),"class","hover_controller", "alt": "not authorized") }}#}
<div class="inner_body">
    <input name="subjectFatherName" value="{{subject_father_id}}" type="hidden">
    <input name="subjectName" value="{{lesson['subject_id']}}" type="hidden">
    <div class="center_wrap">
        {% include 'template/col-side.volt' %}
        <div class="body_container">
            <ul class="nav nav-tabs create_nav_tabs"  style="margin-bottom:20px;">
                <li class="active" data-toggle="course-info">
                    <a href="javascript:;" >基本信息</a>
                </li>
                <!--<li class="forbidden li_intro">-->
                <!--<a href="#course-intro"  data-toggle="tab">课程大纲</a>-->
                <!--</li>-->
                <li class="forbidden li_lm" data-toggle="lesson-manage">
                    <a href="javascript:;" >章节设计</a>
                </li>
            </ul>
            <div  class="tab-content">
                <input type="hidden" name="lesson_id"  value="{{ lesson['id'] }}">

                <div class="tab-pane in active modify_content_wrap" id="course-info">
                    <div class="editCon" id="course-container" course = ''>
                        <div class="edit-title">
                            基本信息
                        </div>
                        <div class="edit-body">
                            <div class="form-group">
                                <label  class="col-sm-2 control-label">课程名称</label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control course_name" value="{{ lesson['title'] }}"  name="course_name" >
                                </div>
                                <div class="col-sm-4">
                                    <p class="form-control-static msg">最多40字</p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label  class="col-sm-2 control-label">课程副标题</label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control  course_title"   value="{{ lesson['subtitle'] }}" name="course_title">
                                </div>
                                <div class="col-sm-4">
                                    <p class="form-control-static msg">最多500字</p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label  class="col-sm-2 control-label">课程描述</label>
                                <div class="col-sm-6">
                                    <textarea class="form-control course_desc"rows="5" placeholder="请输入课程描述">{{ lesson['desc'] }}</textarea>
                                </div>
                                <div class="col-sm-4">
                                    <p class="form-control-static msg">最多500字</p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label  class="col-sm-2 control-label">封面</label>
                                <div class="col-sm-6">
                                    <div id="uploader-demo">
                                        <!--用来存放item-->
                                        <div id="fileList" class="uploader-list" data-picsrc="{{ lesson['pic'] }}">
                                            <img id="filePicker-img" {% if lesson['pic']%} src="/frontend/source/getFrontImageThumb/lesson/{{ lesson['id'] }}/340/190"  {% else %} src="/img/frontend/camtasiastudio/change_img.png" {% endif %}  width="340px" height="189"  />

                                        </div>
                                        <div id="filePicker"></div>
                                        <b></b>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <p class="form-control-static msg">你可以上传jpg, gif, png格式的文件, 图片建议尺寸至少为480x270。 文件大小不能超过 209.7MB</p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label  class="col-sm-2 control-label">课件介绍</label>
                                <div class="col-sm-10">
                                    <script type="text/html" id="ue-container" name="ue-container">{{ lesson['description'] }}</script>
                                </div>
                            </div>
                            <!--<div class="form-group">-->
                                <!--<label  class="col-sm-2 control-label">课程费用</label>-->
                                <!--<div class="col-sm-9">-->
                                    <!--<label class="radio-inline {% if lesson['price']==0 %}has-select  {% endif %}" for="course-free">-->
                                        <!--<i class="btn_check"></i>-->
                                        <!--<input type="radio" name="radioOption" id="course-free" value="0" {% if lesson['price']==0 %}checked  {% endif %} />免费-->
                                    <!--</label>-->
                                    <!--<label class="radio-inline {% if lesson['price']>0 %}has-select  {% endif %}" for="course-pay">-->
                                        <!--<i class="btn_check"></i>-->
                                        <!--<input type="radio" name="radioOption" id="course-pay" value="1" {% if lesson['price']>0 %}checked  {% endif %}/>收费-->
                                    <!--</label>-->
                                    <!--<label id="cost-input" {% if lesson['price']==0 %} style="display:none;"  {% endif %} >-->
                                    <!--<input  style="width:100px;display:inherit;" class="form-control  course_cost" value="{{ lesson['price'] }}">-->
                                    <!--<span class="input-note" style="margin-left: 10px;">填写费用</span>-->
                                    <!--</label>-->
                                <!--</div>-->
                            <!--</div>-->
                            <!--<div class="form-group" style="margin-bottom: 0px;">-->
                                <!--<label  class="col-sm-2 control-label">标签</label>-->
                                <!--<div class="col-sm-8 label_list_wrap">-->
                                    <!--<ul class="course_label_list">-->


                                        <!--{% for item in lesson['label'] %}-->
                                        <!--<li><span>{{ item }}</span><a>✕</a></li>-->

                                        <!--{% endfor %}-->

                                    <!--</ul>-->
                                    <!--<i class="icon_add_label"></i>-->
                                <!--</div>-->
                            <!--</div>-->
                            <div  id="subject-select">
                                <div class="form-group"  >
                                    <label class="col-sm-2 control-label">学科</label>
                                    <div class="col-sm-6">
                                        <select class="sub-type form-control" class="" name="father_subject_id"></select>
                                    </div>
                                </div>
                                <div class="form-group"  >
                                    <label  class="col-sm-2 control-label">专业</label>

                                    <div class="col-sm-6">
                                        <select class="sub-child-type form-control"  name="subject_id"></select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label  class="col-sm-2 control-label">课程状态</label>
                                <div class="col-sm-10">
                                    <label class="radio-inline {% if lesson['type']==1 %} has-select  {% endif %}" for="course-no-serialize">
                                        <i class="btn_check"></i>
                                        <input type="radio" name="statusRadioOption" id="course-no-serialize" value="1" {% if lesson['type']==1 %} checked  {% endif %}  />非连载课程
                                    </label>

                                    <label class="radio-inline {% if lesson['type']==2 %} has-select  {% endif %}" for="course-serialize-ing">
                                        <i class="btn_check"></i>
                                        <input type="radio" name="statusRadioOption" id="course-serialize-ing" value="2"  {% if lesson['type']==2%} checked  {% endif %}  />连载中
                                    </label>
                                    <label class="radio-inline {% if lesson['type']==3 %} has-select  {% endif %}" for="course-serialized">
                                        <i class="btn_check"></i>
                                        <input type="radio" name="statusRadioOption" id="course-serialized" value="3"  {% if lesson['type']==3 %} checked  {% endif %}  />完结课程
                                    </label>
                                </div>
                            </div>

                            <button  class="custom-btn bg-green btn_new_course">下一步</button>
                        </div>
                    </div>

                </div>
                <!--<div class="tab-pane" id="course-intro">-->
                    <!--<div class="course_tab_container" id="lesson-edit-container" course = ''>-->
                        <!--<div class="body_container_header">-->
                            <!--<div class="header_title">课程介绍</div>-->
                        <!--</div>-->
                        <!--<div class="course_detail_container">-->
                            <!--<script id="ue-container" name="ue-container" style="margin:30px 50px;">{{ lesson['description'] }}</script>-->
                            <!--<button  class="custom-btn bg-green btn_edit_course_desc">保存</button>-->
                        <!--</div>-->
                    <!--</div>-->
                <!--</div>-->
                <div class="tab-pane" id="lesson-manage">
                    <div class="course_tab_container" id="lesson-edit-container" course = ''>
                        <div class="body_container_header">
                            <div class="header_title">课时管理</div>
                        </div>
                        <div class="course_detail_container">
                            <div class="ele_center manage_lesson_wrap">
                                <ul class="chapter_ul" id="sort-chapter">
                                    {#默认章节#}
                                    {% if !lessonList %}
                                    <li class="chapter_wrapper chapter_add_wrap" chapter="0">
                                        <div class="chapter_header">
                                            <div class="title_wrap drag-chapter">
                                                <span class="chapter_sort">第1章</span>
                                                <input class="chapter_name input-no-editor" placeholder="输入章节名"></div><label class="orange add_lesson"><span class="glyphicon glyphicon-plus"></span>课时</label>
<!--                                            <label class="orange add_exam"><span class="glyphicon glyphicon-plus"></span>习题</label>-->
                                        </div>
                                        <ul class="lesson_area sort-lesson ui-sortable">
                                            <li class="lesson_wrap title_wrap w_l drag-lesson selected" lesson="0" data-files_kj="" data-files_zl="" data-exam="" >
                                                <div class="course_ac_icons" ><div class="course-icon-bg">
                                                        <label class="choose-label" title="删除"><i class="icon btn_del"></i></label>
                                                        <label class="choose-label" title="上传习题"><i class="icon btn_edit"></i></label>
                                                        <label class="choose-label for-zl"  title="上传资料"><i class="icon btn_file_zl"></i></label>
                                                        <label class="choose-label for-kj" title="上传课件"><i class="icon btn_file_kj"></i></label>
                                                    </div>
                                                </div>
                                                <i class="c_l"></i>
                                                <span class="lesson_sort">课时1</span>
                                                <input class="lesson_name input-no-editor" placeholder="输入课时名"></li>
                                        </ul>
                                    </li>
                                    {% else %}

                                    {%  for lessons in lessonList %}
                                        <li class="chapter_wrapper " chapter={{ lessons['lesson']['id'] }}>
                                            <div class="chapter_header">
                                                <div class="title_wrap drag-chapter">
                                                    <span class="chapter_sort">第{{ lessons['lesson']['sort'] }}章</span>
                                                    <input class="chapter_name input-no-editor" value='{{ lessons['lesson']['name'] }}' readonly />
                                                </div>
                                                <label class="orange add_lesson"><span class="glyphicon glyphicon-plus"></span> 课时</label>
<!--                                                <label class="orange add_exam"><span class="glyphicon glyphicon-plus"></span> 习题</label>-->
                                            </div>
                                            <ul class="lesson_area sort-lesson">

                                                {% for child in lessons['child_list'] %}
                                                    <li class="lesson_wrap title_wrap w_l  drag-lesson clearfix" lesson={{ child['id'] }} data-files_zl='{{ child['file_ids'] }}' data-files_kj="{{ child['file'] }}" data-exam="{{ child['question_ids'] }}">
                                                        <div class="course_ac_icons" ><div class="course-icon-bg">

                                                                <label class="choose-label" title="删除"><i class="icon btn_del"></i></label>
                                                                <label class="choose-label for-xt" title="上传习题"><i class="icon btn_edit {% if child['question_ids'] != '' %}btn_edit_active{% endif %}"></i></label>
                                                                <label class="choose-label for-zl"  title="上传资料"><i class="icon btn_file_zl {% if child['file_ids'] != '' %}btn_file_zl_active{% endif %}"></i></label>
                                                                <label class="choose-label for-kj" title="上传课件"><i class="icon btn_file_kj {% if child['file'] != '' %}btn_file_kj_active{% endif %}"></i></label>
                                                            </div>
                                                        </div>
                                                        <i class="c_l"></i>
                                                        <span class="lesson_sort">课时{{ child['sort'] }}</span>
                                                        <input class=" lesson_name input-no-editor"  value="{{ child['name'] }}" readonly >
                                                    </li>
                                                {% endfor %}
                                            </ul>
                                        </li>
                                    {% endfor %}
                                    {% endif %}
                                </ul>
                                <label class="green add_chapter"><span class="glyphicon glyphicon-plus"></span> 章/节</label>
                                <button type="button" class="custom-btn orange lr btn_cancel_create_lesson ">取消</button>
                                <button type="button" class="custom-btn green lr btn_create_lesson ">保存</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{#upload#}
<div class="small-chat-box fadeInRight animated ">
    <div class="heading" draggable="true">
        <small class="chat-date pull-right count_chat_file">
            <span>0</span>个文件
        </small>
        文件上传进度
    </div>
    <div class="slimScrollDiv slim_scroll_div_wrap" >
        <div class="row" style="margin: 0;">
            <div class="col-lg-12">
                <div class="slimScrollDiv slim_scroll_div" style="">
                    <div class="sidebar-container" style="overflow: hidden; width: auto; height: 100%;">
                        <ul id="file_uploading" class="sidebar-list"></ul>
                    </div>
                    <div class="slimScrollBar slim_scroll_bar"></div>
                    <div class="slimScrollRail slim_scroll_rail"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="slimScrollBar slim_scroll_bar_v2" ></div>
    <div class="slimScrollRail slim_scroll_rail" ></div>
</div>
<div style="display:none;" id="small-chat">
    <span class="badge badge-warning pull-right">0</span>
    <a class="open-small-chat">
        <i class="fa fa-folder"></i>
    </a>
</div>
{#course action container#}
<!--<div class="course_ac_icons" id="course-choose-icons">-->
<!--    <div class="course-icon-bg">-->
<!--        <b></b>-->
<!--        <i class="icon btn_del" title="删除"></i>-->
<!--        <i class="icon btn_edit" title="编辑"></i>-->
<!--        <i class="icon btn_hide" title="不可见"></i>-->
<!--        <i class="icon btn_view" title="预览"></i>-->
<!--        <label class="choose-label for-zl" >-->
<!--            <i class="icon btn_file_zl" title="上传资料"></i>-->
<!--        </label>-->
<!--        <label class="choose-label for-kj">-->
<!--            <i class="icon btn_file_kj" title="上传课件"></i>-->
<!--        </label>-->
<!--    </div>-->
<!--</div>-->
    <!-- 添加习题弹窗-->
{% include "course/course-exam.volt" %}
{% endblock %}
{% block commonModal %}
    {#标签弹框#}
    {% include 'template/label-popover.volt' %}
    <!-- 选择课件或资料弹框-->
    {% include "course/course-file-popover.volt" %}
    <!-- 选择课件或资料/习题弹框-->
    {% include "course/course-exam-popover.volt" %}
{% endblock %}
{% block pageJs %}
    {{ javascript_include("js/frontend/basic.js") }}
    {{ javascript_include("js/frontend/col-side.js") }}
    {{ javascript_include("js/frontend/subjectselect/jq.subselect.js") }}
    {{ javascript_include("js/frontend/course/update.js") }}

<!--    {{ javascript_include("js/frontend/course/socketUpload.js") }}-->
    {{ javascript_include("3rdpart/jquery-ui/jquery-ui.js") }}
<!--    {{ javascript_include("js/frontend/custompart/label-pop.js") }}-->
    <!-- Peity -->
<!--    {{ javascript_include("3rdpart/peity/jquery.peity.min.js") }}-->
<!--    {{ javascript_include("js/backend/peity-demo.js") }}-->
<!--    {{ javascript_include("js/backend/socket.io.js") }}-->
<!--    {{ javascript_include("js/backend/md5.js") }}-->
<!--    {{ javascript_include("js/backend/spark-md5.js") }}-->
    {#模版#}
    {{ javascript_include("3rdpart/template/template.js") }}
    {#弹窗#}
    {{ javascript_include("js/frontend/less2/layer/layer.js") }}
    {#上传文件插件#}
    {{ javascript_include("js/frontend/webUpload/upload.js") }}
    {{ javascript_include("js/frontend/webUpload/webuploader.min.js") }}
    {#UEditor#}
    {{ javascript_include("3rdpart/ueditor/ueditor.config.js") }}
    {{ javascript_include("3rdpart/ueditor/ueditor.all.js") }}
{% endblock %}

