{% extends "template/basic-v2.volt" %}
{% block pageCss %}
    {{ stylesheet_link("css/frontend/less/lessbase.css") }}
    {{ stylesheet_link("css/frontend/col-side.css") }}
    {{ stylesheet_link("js/frontend/datetimepicker/jquery.datetimepicker.css") }}
    {{ stylesheet_link("js/frontend/webUpload/webuploader.css")  }}
    <style>
        .pri-list>li>label{
            color:#515567;
            font-size: 12px;
            width:128px;
        }
        .timt-input{
            border:1px solid #ebebeb;
            height:38px;
        }/***时间input边框***/
        /***上传图片*/
        #uploader-demo {
            position: relative;
            height: 193px;
            width: 344px;
            padding: 2px;
            border: 1px solid #e5e5e5;
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
        #uploader-demo>#filePicker {
            position: absolute;
            width: 340px;
            height: 40px;
            bottom: 0px;
            z-index: 10;
        }
        #filePicker>.webuploader-pick {
            width: 100%;
            background: none;
            border-radius: 0px;

        }
        .thumbnail{
            width: 100%;
            padding: 0px;
            border: 0;
        }
        .icon_add_label {
            display: inline-block;
            width: 34px;
            height: 34px;
            background: url('/img/frontend/home/plus-green.png?v=1') no-repeat 0px 6px;
            cursor: pointer;
            margin-bottom: -12px;
            margin-left: 10px;
        }
        .icon_del_label {
            display: inline-block;
            width: 34px;
            height: 34px;
            background: url('/img/frontend/home/minus-green.png') no-repeat 0px 6px;
            cursor: pointer;
            margin-bottom: -12px;
        }
        .question-other-input{
            margin-left: 128px;    margin-top: 10px;
        }
    </style>
{% endblock %}
{% block content %}
    <div class="inner_body">
        <div class="line"></div>
        <div class="person-con-infor">
            <div class="container clearfix">
                {% include 'template/col-side.volt' %}
                <div class="body_container">
                    <div class="margin-top-40">
                        <a href="/activity/manage"><span class="per-back">返回</span></a>
                    </div>
                    <div class="line margin-top-10"></div>
                    <div class="margin-top-10 private-infor">
                        <div class="pri-con">
                            <input type="hidden" value="{{ detail.id }}" name="activity_id">
                            <input type="hidden" value="{{ detail.type }}" name="type">
                            <input type="hidden" value="{{ detail.group_ids }}" name="group_ids">
                            <form name="acitvity" onsubmit="return false;">
                            <h3 class="margin-top-25">活动信息</h3>
                            <ul class="pri-list">
                                <li class="clearfix">
                                    <label class="fl" >标题</label>
                                    <input name="title" type="text" class="pri-input"  placeholder="请输入标题" required value="{{ detail.title }}"/>
                                </li>
                                <li class="clearfix">
                                    <label class="fl">活动地址</label>
                                    <input name="address" type="text"  placeholder="请输入活动地址" class="pri-input" required value="{{ detail.address }}"/>
                                </li>
                                <li class="clearfix">
                                    <label class="fl">活动时间</label>
                                    <input name="start_time" type="text" class="fl timt-input"  id="date"  required  placeholder="活动开始时间" value="{{ detail.start_time }}"/>
                                    <div class="fl space-line"></div>
                                    <input name="end_time" type="text" class="fl  timt-input" id="date02"   required  placeholder="活动结束时间" value="{{ detail.end_time }}"/>
                                </li>
                                <li class="clearfix">
                                    <label class="fl">活动封面</label>
                                    <div class="fl chage-img-box" id="uploader-demo">

                                        <div id="fileList" class="uploader-list" >
                                            <img id="filePicker-img"  {% if detail.cover_pic %}
                                                src="/source/getFrontImageThumb/activity/{{ detail.id }}/340/189"
                                            {% else%}
                                                src="/img/frontend/camtasiastudio/change_img.png"
                                            {% endif %}  width="340px" height="189"  />

                                        </div>
                                        <div id="filePicker"></div>
                                        <b></b>
                                    </div>
                                    <div class="change-img-text fl">
                                        你可以上传jpg, gif, png格式的文件, </br>
                                        图片建议尺寸至少为480x270。 </br>
                                        文件大小不能超过 209.7MB 。
                                    </div>
                                </li>
                                <li class="clearfix">
                                    <label class="fl">活动描述</label>
                                    <textarea name="description" class="form-textare fl" placeholder="请输入活动描述">{{ detail.description }}</textarea>
                                    <span class="textarea-notic fl">最多500字</span>
                                </li>
                                <li class="clearfix">
                                    <label class="fl">活动征文</label>
                                    <div class="fl cj">
                                        <script id="about-content" name="content"  >{{ detail.content }}</script>
                                    </div>
                                </li>
                                <li class="clearfix">
                                    <label class="fl">发布范围</label>
                                    <div class="fl mod-radio">
                                        <label  {% if detail.type==1 %} class="active" {% endif %}>
                                            <input type="radio" name="type"  value="1" {% if detail.type==1 %} checked {% endif %}>
                                            公开
                                        </label>
                                    </div>
                                    <div class="fl mod-radio">
                                        <label  {% if detail.type==2 %} class="active" {% endif %}>
                                            <input type="radio" name="type"  value="2" {% if detail.type==2 %} checked {% endif %} disabled >
                                            群组
                                            <select name="group_ids">
                                                <option>请先添加群组</option>
                                            </select>
                                        </label>
                                    </div>
                                    <div class="fl mod-radio">
                                        <label  {% if detail.type==3 %} class="active" {% endif %}>
                                            <input type="radio" name="type" value="3" {% if detail.type==3 %} checked {% endif %}>
                                            私有
                                                <input type="text"  name="password" placeholder="私有密码" {% if detail.type!=3 %} style="display:none;" {% endif %} />
                                        </label>
                                    </div>
                                </li>
                                <li class="clearfix" >
                                    <label class="fl">添加附加信息</label>
                                    {% if detail.questions %}
                                        {% for index,item in detail.questions %}
                                            <input name="questions[]" type="text" class="pri-input {% if index>0 %} question-other-input {% endif %}" placeholder="请添加附加信息" required="" value="{{ item.question }}">
                                        {% endfor %}
                                    {% endif %}
                                    <i class="icon_add_label"></i>
                                    <i class="icon_del_label"></i>
                                </li>

                                <li class="clearfix">
                                    <label class="fl">活动费用</label>
                                    <div class="fl mod-radio">
                                        <label  {% if detail.is_pay==0 %} class="active" {% endif %}>
                                            <input type="radio" name="is_pay"  value="0" {% if detail.is_pay==0 %} checked {% endif %}>
                                            免费
                                        </label>
                                    </div>
                                    <div class="fl mod-radio">
                                        <label  {% if detail.is_pay==1 %} class="active" {% endif %}>
                                            <input type="radio" name="is_pay"  value="1" {% if detail.is_pay==1 %} checked {% endif %}>
                                            收费
                                            <input  name="price" type="text" class=" course_cost" value="{{ detail.price }}" {% if detail.is_pay!=1 %} style="display:none;" {% endif %}>

                                        </label>
                                    </div>


                                </li>
                                <li class="clearfix">
                                    <label class="fl">人数限制</label>
                                    <input name="users_limit" type="number" class="pri-input"  placeholder="请输入人数限制" required value="{{ detail.users_limit }}"/>
                                </li>
                                <li class="clearfix">
                                    <label class="fl">是否审核</label>
                                    <div class="fl mod-radio">
                                        <label  {% if detail.verify==1 %} class="active" {% endif %}>
                                            <input type="radio" name="verify"  value="1" {% if detail.verify==1 %} checked {% endif %}>
                                            是
                                        </label>
                                    </div>
                                    <div class="fl mod-radio">
                                        <label  {% if detail.verify==0 %} class="active" {% endif %}>
                                            <input type="radio" name="verify"  value="0" {% if detail.verify==0 %} checked {% endif %}>
                                            否
                                        </label>
                                    </div>
                                </li>
                            </ul>
                            <div class="text-align-center priv-btn-grounp">
                                <button class="pop-btn" type="submit">发布活动</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
{% endblock %}
{% block pageJs %}
    {{ javascript_include("js/frontend/col-side.js") }}
    {{ javascript_include("js/frontend/datetimepicker/jquery.datetimepicker.js") }}
    {#UEditor#}
    {{ javascript_include("3rdpart/ueditor/ueditor.config.js") }}
    {{ javascript_include("3rdpart/ueditor/ueditor.all.js") }}
    {{ javascript_include("js/frontend/less2/layer/layer.js") }}
    {{ javascript_include("3rdpart/validate/jquery.validate.js") }}
    {#上传文件插件#}
    {{ javascript_include("js/frontend/webUpload/upload.js") }}
    {{ javascript_include("js/frontend/webUpload/webuploader.min.js") }}
    {#活动js#}
    {{ javascript_include("js/frontend/activity/createModel.js") }}
    {{ javascript_include("js/frontend/activity/create.js") }}
    <script type="text/javascript">
        $(function () {

            $('#date').datetimepicker({
                lang:"ch"
            });
            $('#date').datetimepicker();
            $('#date02').datetimepicker({
                lang:"ch"
            });
            $('#date02').datetimepicker();

        });
    </script>
{% endblock %}