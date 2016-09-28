{% extends "template/basic-v2.volt" %}
{% block pageCss %}
    {{ stylesheet_link("css/frontend/less2/common.css") }}
    {{ stylesheet_link("css/frontend/less2/home.css") }}
    {{ stylesheet_link("css/frontend/less2/edit.css") }}
    {{ stylesheet_link("css/frontend/col-side.css")  }}
    {{ stylesheet_link("css/frontend/basic.css")  }}
    {{ stylesheet_link("css/frontend/label-pop.css")  }}
    {{ stylesheet_link("css/frontend/custompart/file-select-popover.css")  }}
    {{ stylesheet_link("js/frontend/webUpload/webuploader.css")  }}
    <style>
        .tags_list {
            max-width: 90%;
            float: left;
            padding-right: 20px;
        }
        ul.tags_list li{
            float: left;
            padding: 6px 10px 2px;
            border: 1px solid grey;
            border-radius: 2px;
            margin-left: 10px;
            margin-bottom: 10px;
        }
        ul.tags_list li a {
            text-align: center;
            display: inline-block;
            width: 15px;
            height: 20px;
            color: #222;
            margin-left: 10px;
            cursor: pointer;
            font-size: 12px;
        }
        /***选择群组***/
        .row select {
            height: 31px;
            box-shadow: 0 5px 6px #eee inset;
            height: 30px;
            line-height: 30px;
            width: 140px;
            border-radius: 3px;
            border: 1px solid #ebebeb;
            margin-left: 10px;
        }
        /***上传图片*/
        #uploader-demo {
            position: relative;
            height: 193px;
            width: 344px;
            padding: 2px;
            border: 1px solid #e5e5e5;
        }
        #uploader-demo>#filePicker {
            position: absolute;
            width: 340px;
            height: 40px;
            bottom: 0px;
            z-index: 10;
        }
        #uploader-demo>b {
            position: absolute;
            width: 340px;
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
            width:100%;
            padding: 0px;
            border: 0;
        }
    </style>
{% endblock %}
{% block content %}
    <div class="inner_body">
        <div class="center_wrap container">
            {% include 'template/col-side.volt' %}
            <div class="body_container">
                <div class="title title-2">
                    <a href="/mico" class="back">返回</a>
                </div>
                <div class="editCon">

                </div>
                <!-- 编辑  clasList end -->
            </div>
            <!-- 主体内容 content end -->
        </div>
    </div>
    <input name="m_lesson_id" value="{{ id }}" type="hidden">
    <!-- 选择课件或资料弹框-->
    {% include "course/course-file-popover.volt" %}
    <script id="templateEdit" type="text/html">
        <form name="mlesson-form" onsubmit="return false" class="form-horizontal">
            <div class="edit-title">
                微课编辑
            </div>
            <div class="edit-body">
                <div class="form-group">
                    <label class="col-sm-2 control-label">微课名称</label>
                    <div class="col-sm-6">
                        <input type="text" name="title" placeholder="请输入微课名称" class="form-control" maxlength="40" required value="<%title%>"/>
                    </div>
                    <div class="col-sm-4 msg">
                        <p class="form-control-static msg">最多40字</p>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">微课封面</label>
                    <div class="col-sm-6">
                        <div id="uploader-demo">
                            <div id="fileList" class="uploader-list" >
                                <img id="filePicker-img" <%if pic%>src="/frontend/source/getFrontImageThumb/mlesson/<%id%>/340/190" <%else%> src="/img/frontend/camtasiastudio/change_img.png"  <%/if%>width="340px" height="189"  />

                            </div>
                            <div id="filePicker"></div>
                            <b></b>
                        </div>
                    </div>
                    <div class="col-sm-4 msg">
                        <p class="form-control-static msg">你可以上传jpg, gif, png格式的文件, 图片建议尺寸至少为480x270。 文件大小不能超过 209.7MB 。</p>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">微课简介</label>
                    <div class="col-sm-6">
                        <textarea placeholder="请输入微课简介" class="form-control" rows="5" maxlength="500" name="desc" ><%desc%></textarea>
                    </div>
                    <div class="col-sm-4 msg">
                        <p class="form-control-static msg">最多500字</p>
                    </div>
                </div>
                <!--<div class="row cl">-->
                    <!--<div class="col col-l">-->
                        <!--标签修改-->
                    <!--</div>-->
                    <!--<div class="col col-r" id="tags">-->
                        <!--<ul class="tags_list">-->
                            <!--<%each label as lab%>-->
                            <!--<li><span><%lab%></span><a>✕</a></li>-->
                            <!--<%/each%>-->
                        <!--</ul>-->
                        <!--<a class="add-btn" title="添加" id="addTag"></a>-->
                    <!--</div>-->
                <!--</div>-->
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
                    <label class="col-sm-2 control-label">添加关联课件</label>
                    <div class="col-sm-6">
                        <input type="text" placeholder="请从右侧选择关联课件" class="form-control" name="kj" disabled required/>
                    </div>
                    <div class="col-sm-4 msg"><a href="javascript:void(0);" class="add-btn" title="添加关联课件" id="addFiles" data-type="kj" ></a></div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">添加关联资料</label>
                    <div class="col-sm-6">
                        <input type="text" placeholder="请从右侧选择关联资料" class="form-control" name="zl" disabled required />
                    </div>
                    <div class="col-sm-4 msg"><a href="javascript:void(0);" class="add-btn" title="添加关联资料" id="addFiles" data-type="zl"></a></div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">发布范围</label>

                    <div class="col-sm-10  form-inline">
                        <div class="radioBox  ">
                            <label  class="radio-inline <%if !type || type==1%>active<%/if%>">
                                <input type="radio" name="type"  value="1"  required  <%if !type || type==1%> checked <%/if%> >
                                公开
                            </label>
                        </div>
                        <div class="radioBox  ">
                            <label  class="radio-inline  <%if type==3%> active<%/if%>">
                                <input type="radio" name="type"  value="3" required <%if type==3%> checked <%/if%> >
                                私有
                            </label>
                            <input type="text"  class="form-control" placeholder="私有密码" <%if type!=3%> style="display:none;" <%/if%> name="password" value="<%password%>"/>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <input class="submit" type="submit" value="发布微课">
                </div>
            </div>
        </form>
        <input name="subjectFatherName" value="<% subject_father_id %>" type="hidden">
        <input name="subjectName" value="<% subject_id %>" type="hidden">
    </script>
{% endblock %}
{% block commonModal %}
{% endblock %}
{% block pageJs %}
    {{ javascript_include("js/frontend/basic.js") }}
    {{ javascript_include("js/frontend/col-side.js") }}
    <!--{{ javascript_include("3rdpart/iCheck/icheck.min.js") }}-->
    <!--{{ javascript_include("js/frontend/course/socketUpload.js") }}-->
    <!--{{ javascript_include("3rdpart/jquery-ui/jquery-ui.js") }}-->
    <!--{{ javascript_include("js/frontend/custompart/label-pop.js") }}-->
    <!-- Peity -->
    <!--{{ javascript_include("3rdpart/peity/jquery.peity.min.js") }}-->
    <!--{{ javascript_include("js/backend/peity-demo.js") }}-->
    <!--{{ javascript_include("js/backend/socket.io.js") }}-->
    <!--{{ javascript_include("js/backend/md5.js") }}-->
    <!--{{ javascript_include("js/backend/spark-md5.js") }}-->
    {{ javascript_include("3rdpart/validate/jquery.validate.js") }}
    {{ javascript_include("js/frontend/subjectselect/jq.subselect.js") }}
    {#模版#}
    {{ javascript_include("3rdpart/template/template.js") }}
    {#弹窗#}
    {{ javascript_include("js/frontend/less2/layer/layer.js") }}
    {#上传文件插件#}
    {{ javascript_include("js/frontend/webUpload/upload.js") }}
    {{ javascript_include("js/frontend/webUpload/webuploader.min.js") }}
    {#上传文件插件#}
    {{ javascript_include("js/frontend/mico/myMicoModal.js") }}
    {{ javascript_include("js/frontend/mico/edit.js") }}
{% endblock %}