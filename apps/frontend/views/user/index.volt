{% extends "template/basic-v2.volt" %}
{% block pageCss %}
    {{ stylesheet_link("css/frontend/less/lessbase.css") }}
    {{ stylesheet_link("css/frontend/col-side.css") }}
    {{ stylesheet_link("js/frontend/webUpload/webuploader.css")  }}

    {{ stylesheet_link("js/frontend/datetimepicker/jquery.datetimepicker.css") }}

    <style>
        .pri-list>li>label {
            padding-top: 10px;
        }
        /**地区下拉**/
        .mod-select>select {
            border: 0 none;
            height: 32px;
            line-height: 32px;
            padding: 0 5px;
            width: 136px;
        }
        /***上传图片*/
        #uploader-demo {
            position: relative;
            height: 126px;
            width: 126px;
            padding: 2px;
            border: 1px solid #e5e5e5;
        }
        #uploader-demo>#filePicker {
            position: absolute;
            width: 120px;
            height: 40px;
            bottom: 0px;
            z-index: 10;
        }
        #uploader-demo>b {
            position: absolute;
            width: 120px;
            height: 40px;
            bottom: 0px;
            background: #000;
            opacity: 0.4;
            filter: alpha(opacity=40);
            display: block;
        }
        #filePicker>.webuploader-pick {
            width: 100%;
            background: none;
            border-radius: 0px;
        }
        #fileList>.thumbnail {
            width: 120px;
            padding: 0px;
            border: 0;
        }
    </style>
{% endblock %}
{% block content %}
    <div class="container_inner">
        <div class="inner_body">
            <div class="container clearfix">
                {% include 'template/col-side.volt' %}
                <div class="body_container">
                    <div class="per-info-tit margin-top-40">
                        <span>个人资料</span>
                    </div>
                    <div class="line margin-top-10"></div>
                    <div class="margin-top-10 private-infor">
                        {#<div class="pri-tab">
                            {% if(user.role_id == 2) %}
                                <span class="active">教务人员</span>
                            {% elseif(user.role_id == 1) %}
                                <span class="active">学生</span>
                            {% endif %}
                        </div>#}
                        <div class="pri-con">

                            <form name="user" class="form-horizontal" onsubmit="return false;">
                                <h3>基础信息</h3>
                                <div class="form-group ">
                                    <label for="inputEmail3" class="col-sm-2 control-label">头像</label>
                                    <div class="col-sm-10">
                                        <div id="uploader-demo">
                                            <div id="fileList" class="uploader-list" >
                                                <img id="filePicker-img"
                                                        {% if userInfo.headpic %} src="/frontend/source/getFrontImageThumb/header/{{ userInfo.uid }}/120/120"
                                                        {% else %} src="/img/frontend/camtasiastudio/default-avatar-small.png"  {% endif %}
                                                     width="120px" height="120px"  />

                                            </div>
                                            <div id="filePicker"></div>
                                            <b></b>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputEmail3" class="col-sm-2 control-label">姓名</label>
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control" value="{{ userInfo.realname }}"  name="realname" placeholder="请输入您的真实姓名" required/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputEmail3" class="col-sm-2 control-label">昵称</label>
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control" value="{{ userInfo.nick_name }}"  name="nick_name" placeholder="请输入昵称" required />
                                    </div>
                                </div>
                                <div class="form-group" id="city" data="{{ userInfo.city }}">
                                    <label for="inputEmail3" class="col-sm-2 control-label">地区</label>
                                    <div class="col-sm-3">
                                        <select class="prov form-control"></select>
                                    </div>
                                    <div class="col-sm-3">
                                        <select class="city form-control"></select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputEmail3" class="col-sm-2 control-label">个人简介</label>
                                    <div class="col-sm-6">
                                        <textarea class="form-control" rows="5" name="desc">{{ userInfo.desc }}</textarea>
                                    </div>
                                </div>
                                <div class="form-group" >
                                    <div class="col-sm-10 col-md-offset-2">真实信息（真实信息不会公开显示，仅用官方活动或项目合作联系）</div>
                                </div>
                                <div class="form-group">
                                    <label for="inputEmail3" class="col-sm-2 control-label">qq</label>
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control" name="qq" value="{{ userInfo.qq }}" required placeholder="请输入qq" onkeyup="this.value=this.value.replace(/\D/g,'')"/>
                                    </div>
                                </div>
                                <!--<div class="form-group">-->
                                    <!--<label for="inputEmail3" class="col-sm-2 control-label">电话</label>-->
                                    <!--<div class="col-sm-6">-->
                                        <!--<input type="text" class="form-control" value="{{ user.phone }}" readonly />-->
                                    <!--</div>-->
                                <!--</div>-->
                                <!--<div class="form-group">-->
                                    <!--<label for="inputEmail3" class="col-sm-2 control-label">邮箱</label>-->
                                    <!--<div class="col-sm-6">-->
                                        <!--<input type="text" class="form-control" name="email" value="{{ userInfo.email }}" required placeholder="请输入邮箱" email="true"/>-->
                                    <!--</div>-->
                                <!--</div>-->
                                {% if(user.role_id == 2) %}
                                    <h3>教务人员信息</h3>
                                    <div class="form-group margin-top-15">
                                        <label for="inputEmail3" class="col-sm-2 control-label">岗位职称</label>
                                        <div class="col-sm-6">
                                            <input type="text" class="form-control" name="job" value="{{ userInfo.job }}" placeholder="请输入岗位职称" required/>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-2 control-label">班级</label>
                                        <div class="col-sm-6">
                                            <select class="form-control" name="class" required>
                                                {% for item in classArr %}
                                                    {% if item ==userInfo.class %}
                                                        <option selected>{{ item }}</option>
                                                    {% else %}
                                                        <option>{{ item }}</option>
                                                    {% endif %}

                                                {% endfor %}
                                            </select>
                                        </div>
                                    </div>
                                {% elseif(user.role_id == 1) %}
                                    <h3>学生信息</h3>
                                    <div class="form-group margin-top-15" >
                                        <label for="inputEmail3" class="col-sm-2 control-label">班级年份</label>
                                        <div class="col-sm-6">
                                            <select class="sub-type form-control" name="class_year" required></select><!--时间插件-年份-->
                                        </div>
                                    </div>
                                    <div class="form-group" >
                                        <label for="inputEmail3" class="col-sm-2 control-label">年级</label>
                                        <div class="col-sm-6">
                                            <input type="text" class="form-control" name="class"   placeholder="年级" readonly/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-2 control-label">班级</label>
                                        <div class="col-sm-6">
                                            <select class="form-control" name="class" required>
                                                {% for item in classArr %}
                                                    {% if item == userInfo.class %}
                                                        <option selected>{{ item }}</option>
                                                    {% else %}
                                                        <option>{{ item }}</option>
                                                    {% endif %}

                                                {% endfor %}
                                            </select>
                                        </div>
                                    </div>


                                {% endif %}
                                {% if PERIOD ==1 %}

                                    <div  id="subject-select">
                                        <div class="form-group"  >
                                            <label for="inputEmail3" class="col-sm-2 control-label">学段</label>
                                            <div class="col-sm-6">
                                                <select class="sub-type form-control"></select>
                                            </div>

                                        </div>
                                        <div class="form-group"  >
                                            <label for="inputEmail3" class="col-sm-2 control-label">学科</label>

                                            <div class="col-sm-6">
                                                <select class="sub-child-type form-control"  name="subject"></select>
                                            </div>
                                        </div>
                                    </div>
                                {% elseif(PERIOD == 2) %}
                                    <div  id="subject-select">
                                        <div class="form-group"  >
                                            <label for="inputEmail3" class="col-sm-2 control-label">学科</label>
                                            <div class="col-sm-6">
                                                <select class="sub-type form-control" name="father_subject"></select>
                                            </div>

                                        </div>
                                        <div class="form-group"  >
                                            <label for="inputEmail3" class="col-sm-2 control-label">专业</label>

                                            <div class="col-sm-6">
                                                <select class="sub-child-type form-control"  name="subject"></select>
                                            </div>
                                        </div>
                                    </div>
                                {% endif %}

                                <div class="row">
                                    <div class="col-sm-6 col-md-offset-2 text-center"><input class="pri-btn pri-btn02" type="submit" value="修改"></div>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <input name="subjectFatherName" value="{{ subjectFatherId }}" type="hidden">
    <input name="subjectName" value="{{ subjectId }}" type="hidden">
{% endblock %}
{% block pageJs %}
    {{ javascript_include("js/frontend/col-side.js") }}
    {{ javascript_include("/js/frontend/cityselect/jquery.cityselect.js") }}
    {{ javascript_include("js/frontend/less2/layer/layer.js") }}
    {{ javascript_include("3rdpart/validate/jquery.validate.js") }}
    {#上传文件插件#}
    {{ javascript_include("js/frontend/webUpload/upload.js") }}
    {{ javascript_include("js/frontend/webUpload/webuploader.min.js") }}
    {#时间选择器#}
    {{ javascript_include("js/frontend/datetimepicker/jquery.datetimepicker.js") }}
    {{ javascript_include("/js/frontend/user/userModel.js") }}
    {{ javascript_include("js/frontend/subjectselect/jq.subselect.js") }}
    {{ javascript_include("/js/frontend/user/user.js") }}

{% endblock %}