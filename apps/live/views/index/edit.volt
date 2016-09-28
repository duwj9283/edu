{% extends "templates/basic-v2.volt" %}
{% block pageCss %}
    {{ stylesheet_link("css/frontend/less2/common.css") }}
    {{ stylesheet_link("css/frontend/less2/home.css") }}
    {{ stylesheet_link("css/frontend/less2/edit.css") }}
    {{ stylesheet_link("css/frontend/col-side.css")  }}
    {{ stylesheet_link("css/frontend/basic.css")  }}
    {{ stylesheet_link("css/frontend/label-pop.css")  }}
    {{ stylesheet_link("css/frontend/custompart/file-select-popover.css")  }}
    {{ stylesheet_link("js/frontend/datetimepicker/jquery.datetimepicker.css") }}
    {{ stylesheet_link("js/frontend/webUpload/webuploader.css")  }}

{% endblock %}
{% block content %}
    <div class="inner_body">
        <div class="center_wrap container">
            {% include 'templates/col-side.volt' %}
            <div class="body_container">
                <div class="title title-2"> <a href="/live/index/list" class="back">返回</a> </div>
                <form name="live-form" onsubmit="return false;" class="form-horizontal">
                    <input type="hidden" value="{{ detail.id }}" name="id">
                    <div class="editCon" id="live">
                    <div class="edit-title">{% if detail.id %} 编辑直播 {% else %}创建直播{% endif %} </div>
                    <div class="edit-body">
                        <div class="form-group  cl">
                            <label class="col-sm-2 control-label">直播名称</label>
                            <div class="col-sm-6">
                                <input type="text" placeholder="请输入直播名称" class="form-control" name="name" required value="{{ detail.name}}"/>
                            </div>
                            <div class="col-sm-4 msg">
                                <p class="form-control-static msg">最多40字</p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">直播简介</label>
                            <div class="col-sm-6">
                                <textarea class="form-control" rows="5" placeholder="请输入直播简介" class="txt" name="intro" required>{{ detail.intro }}</textarea>

                            </div>
                            <div class="col-sm-4 msg">
                                <p class="form-control-static msg">最多500字</p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">直播介绍</label>
                            <div class="col-sm-10">
                                <script id="about-content" name="content" type="text/plain" required>{{ detail.content }}</script>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">直播封面</label>
                            <div class="col-sm-6">
                                <div id="uploader-demo">
                                    <div id="fileList" class="uploader-list" >
                                        <img id="filePicker-img" {% if detail.cover_pic %} src="/frontend/source/getFrontImageThumb/live/{{ detail.id }}/340/190" {% else %} src="/img/frontend/camtasiastudio/change_img.png"  {% endif %}width="340px" height="189"  />

                                    </div>
                                    <div id="filePicker"></div>
                                    <b></b>
                                </div>
                            </div>
                            <div class="col-sm-4 ">
                                <p class="form-control-static msg">你可以上传jpg, gif, png格式的文件, 图片建议尺寸至少为480x270。 文件大小不能超过 209.7MB </p></div>
                        </div>
                        <!--<div class="form-group">-->
                            <!--<label class="col-sm-2 control-label">添加标签</label>-->
                            <!--<div class="col-sm-10" id="tags">-->
                                <!--<ul class="tags_list">-->
                                    <!--{% for item in detail.tags %}-->
                                    <!--<li class="form-control"><span>{{ item }}</span><a>✕</a></li>-->

                                    <!--{% endfor %}-->
                                <!--</ul>-->
                                <!--<a class="add-btn" title="添加" id="addTag"></a>-->
                            <!--</div>-->
                        <!--</div>-->
                        <div class="form-group" id="date">
                            <label class="col-sm-2 control-label">开放时间</label>
                            <div class="col-sm-3">
                                <input type="text" class="form-control"  id="startDate" name="start_time" placeholder="请输入开始时间" required value="{{ detail.start_time }}" />
                            </div>
                            <div class="col-sm-3">
                                <input type="text" class="form-control" id="endDate"   name="end_time" placeholder="请输入结束时间" required value="{{ detail.end_time}}"/>
                            </div>
                        </div>
                        <div  id="subject-select">
                            <div class="form-group"  >
                                <label class="col-sm-2 control-label">学科</label>
                                <div class="col-sm-6">
                                    <select class="sub-type form-control" class=""></select>
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
                            <label class="col-sm-2 control-label">关联资料</label>
                            <div class="col-sm-6">
                                <input type="text" placeholder="请从右侧选择关联资料" class="form-control" name="zl"  disabled required />
                            </div>
                            <div class="col-sm-4 msg"><a href="javascript:void(0);" class="add-btn" data-files="{{detail.file_ids}}" title="添加关联资料" id="addFiles" data-type="zl"></a></div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">录播教室</label>
                            <div class="col-sm-3 ">
                                <select class="sub-child-type form-control"  name="class_room_id">
                                    {%  for room in class_room %}
                                    <option value="{{ room['id'] }}">{{ room['title'] }}</option>
                                    {% endfor %}
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">发布范围</label>
                            <div class="col-sm-10 form-inline">
                                <div class="radioBox">
                                    <label class="radio-inline {% if !detail.publish_type or detail.publish_type %}active{% endif %}">
                                        <input type="radio" name="publish_type"  value="1"  required {% if !detail.id or detail.publish_type==1 %} checked {% endif %} >
                                        公开
                                    </label>
                                </div>
                                <div class="radioBox">
                                    <label class="radio-inline  {% if detail.publish_type==3 %}active{% endif %}">
                                        <input type="radio" name="publish_type"  value="3" required {% if detail.publish_type==3 %} checked {% endif %}>
                                        私有
                                    </label>
                                    <input type="text"  class="form-control" placeholder="私有密码"{% if detail.publish_type !=3 %} style="display:none;" {% endif %} name="visit_pass" value="{{ detail.visit_pass }}"/>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">聊天设置</label>
                            <div class="col-sm-10">
                                <div class="radioBox">
                                    <label  class="radio-inline  {% if !detail.id or detail.allow_chat==0 %} active {% endif %} ">
                                        <input type="radio" name="allow_chat"  value="0" {% if !detail.id or detail.allow_chat==0 %} checked {% endif %} required>
                                        允许
                                    </label>
                                </div>
                                <div class="radioBox">
                                    <label  class="radio-inline  {% if detail.allow_chat==1 %} active {% endif %}" >
                                        <input type="radio" name="allow_chat"  value="1" required {% if detail.allow_chat==1 %} checked {% endif %}>
                                        不允许
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">人数限制</label>
                            <div class="col-sm-3 ">
                                <input type="text" name="users_limit" class="form-control" value="{% if !detail.users_limit  %}500{% endif %}{{ detail.users_limit }}"  placeholder="人数限制"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <input class="submit" value="保存直播" type="submit">
                        </div>
                    </div>
                </div>
                </form>
                <input name="subjectFatherName" value="{{ father_id }}" type="hidden">
                <input name="subjectName" value="{{ detail.subject_id }}" type="hidden">
                <input name="clasRoom" value="{{ detail.class_room_id }}" type="hidden">
                <!-- 编辑  clasList end -->
            </div>
            <!-- 主体内容 content end -->
        </div>
    </div>
<div class="file-select-popover " id="file-select-popover" style="display: none;">
    <b></b>
    <div class="file-popover-container">
        <div class="file-popover-content">
            <div class="popover-content-header">
                <a class="title">我的网盘</a>
                <label class="choose-file" for="choose-kj">
                    <!--<sapn>来自本地</sapn>-->
                    <input type="file"  id="choose-kj" accept=".mp4,.avi,.rmvb" class="choose_btn hide">
                </label>
            </div>
            <div class="popover-content-body">
                <ul class="file_list">
                    {#<li class="ellipsis folder" file="157">
                        <div class="treeview-node">
                            <dfn class="b-in-blk treeview-ic"></dfn>
                            <span>aaa</span>
                        </div>
                        <ul folder="/343">
                            <li class="ellipsis folder" file="157">
                                <div class="treeview-node">
                                    <dfn class="b-in-blk treeview-ic"></dfn>
                                    <span>aaa</span>
                                </div>
                                <ul folder="/343/11"></ul>
                            </li>
                        </ul>
                    </li>#}
                </ul>
            </div>
        </div>
        <div class="move-wrapper">
            <label class="move-r"><i></i></label>
        </div>
        <div class="file-popover-content content-r">
            <div class="popover-content-header">
                <a class="title">最多选1个</a>
            </div>
            <div class="popover-content-body">
                <ul class="file_r_list">
                    {# <li class="ellipsis"><sapn>学习XXXXXXXXXX.mp4</sapn><i></i></li>#}
                </ul>
            </div>
            <div class="popover-content-foot">
                <div class="custom-btn gray close-btn">关闭</div>
                <button type="button" class="custom-btn green lr save-file">保存</button>
            </div>
        </div>
    </div>
</div>
{% endblock %}
{% block commonModal %}
    {#标签弹框#}
    {% include 'templates/label-popover.volt' %}
{% endblock %}
{% block pageJs %}
    {{ javascript_include("3rdpart/bootstrap/js/bootstrap.min.js") }}
    {{ javascript_include("js/frontend/col-side.js") }}
    {{ javascript_include("js/frontend/less2/edit.js") }}
    {{ javascript_include("js/frontend/less2/layer/layer.js") }}
    {{ javascript_include("3rdpart/validate/jquery.validate.js") }}
    {#百度编辑器#}
    {{ javascript_include("3rdpart/ueditor/ueditor.config.js") }}
    {{ javascript_include("3rdpart/ueditor/ueditor.all.js") }}
    {#上传文件插件#}
    {{ javascript_include("js/frontend/webUpload/upload.js") }}
    {{ javascript_include("js/frontend/webUpload/webuploader.min.js") }}
    {#时间选择器#}
    {{ javascript_include("js/frontend/datetimepicker/jquery.datetimepicker.js") }}
    {#标签添加页#}
    {{ javascript_include("js/frontend/subjectselect/jq.subselect.js") }}
    {{ javascript_include("3rdpart/jquery-ui/jquery-ui.js") }}
    {{ javascript_include("js/frontend/custompart/label-pop.js") }}
    {{ javascript_include("js/frontend/live/createModel.js") }}
    {{ javascript_include("js/frontend/live/create.js") }}

{% endblock %}