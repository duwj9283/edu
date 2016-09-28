{% extends "template/basic-v2.volt" %}
{% block pageCss %}
    {{ stylesheet_link("css/frontend/less/lessbase.css") }}
    {{ stylesheet_link("css/frontend/col-side.css")  }}
    {{ stylesheet_link("js/frontend/webUpload/webuploader.css")  }}
    <style>
        .w1120{
            width: 60%;
            min-width: 1150px;
            margin: 0 auto;
        }
        .widthauto.grounp-block{
            box-sizing: content-box;
        }
        /***上传图片*/
        .webuploader-pick{
            width: 100%;
            height: 100%;
            background: #000;
            opacity: 0.3;
            filter: alpha(opacity=30);
        }
        .thumbnail{
            width: 100%;
        }
    </style>
{% endblock %}
{% block content %}
    <div class="inner_body content">
        <div class="person-con-infor">
            <div class="container clearfix">
                {% include 'template/col-side.volt' %}
                <div class="body_container">
                    <div class="per-info-tit per-info-tit03 margin-top-40">
                        <span>我的群组</span>
                    </div>
                    <div class="line margin-top-10"></div>
                    <div class="margin-top-10 private-infor">
                        <div class="pri-con">
                            <form name="group" onsubmit="return false;">
                                <h3 class="margin-top-25">创建群组</h3>
                                <ul class="pri-list pri-list-radio">
                                    <li class="clearfix">
                                        <label class="fl">群类别</label>
                                        <div class="fl mod-radio">
                                            <label>
                                                <input type="radio" name="type" value="1" required>
                                                班级组
                                            </label>
                                        </div>
                                        <div class="fl mod-radio">
                                            <label>
                                                <input type="radio" name="type" value="2" required>
                                                交流组

                                            </label>
                                        </div>
                                        <div class="fl mod-radio">
                                            <label>
                                                <input type="radio" name="type" value="3" required>
                                                工作组
                                            </label>
                                        </div>
                                    </li>
                                    <li class="clearfix">
                                        <label class="fl" >群名称</label>
                                        <input name="name"  type="text" class="pri-input"  placeholder="请输入群名称" maxlength="20" required/>
                                        <span  class="notic-message">最多20个字</span>
                                    </li>
                                    <li class="clearfix">
                                        <input type="hidden" name="headpic" value="">
                                        <label class="fl">群logo</label>
                                        <div class="fl chage-img-box">
                                            <div id="fileList" class="uploader-list" >
                                               <img id="filePicker-img" src="/img/frontend/camtasiastudio/change_img.png" width="340px" height="189"  />

                                            </div>
                                            {#<span >
                                                <b></b>
                                                <span>点击更换封面</span>
                                            </span>#}
                                            <span id="filePicker">
                                                <b></b>
                                                <span>点击更换封面</span>
                                            </span>
                                        </div>
                                        <div class="change-img-text fl">
                                            你可以上传jpg, gif, png格式的文件, </br>
                                            图片建议尺寸至少为480x270。 </br>
                                            文件大小不能超过 209.7MB 。
                                        </div>
                                    </li>
                                    <li class="clearfix">
                                        <label class="fl">群介绍</label>
                                        <textarea class="form-textare fl" name="intro" required></textarea>
                                        <span class="textarea-notic fl">最多500字</span>
                                    </li>

                                    <li class="clearfix">
                                        <label class="fl">群公开度</label>
                                        <div class="fl mod-radio">
                                            <label>
                                                <input type="radio" name="open" value="2" required>
                                                私密
                                            </label>
                                        </div>
                                        <div class="fl mod-radio">
                                            <label>
                                                <input type="radio"   name="open" value="1" required>
                                                公开

                                            </label>
                                        </div>
                                    </li>
                                    <li class="clearfix">
                                        <label class="fl">验证方式</label>
                                        <div class="fl mod-radio">
                                            <label>
                                                <input type="radio" name="validate" value="1" required>
                                                无需验证
                                            </label>
                                        </div>
                                        <div class="fl mod-radio">
                                            <label>
                                                <input type="radio" name="validate" value="2" required>
                                                需要验证
                                            </label>
                                        </div>
                                        <div class="fl mod-radio">
                                            <label>
                                                <input type="radio" name="validate" value="3" required>
                                                不允许加入
                                            </label>
                                        </div>
                                    </li>
                                </ul>

                                <div class="priv-btn-grounp text-align-center">
                                    <button class="pri-btn pri-btn02 orange-btn" type="submit">确认创建</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
{% endblock %}
{% block commonModal %}
{% endblock %}
{% block pageJs %}
    {{ javascript_include("js/frontend/col-side.js") }}
    {{ javascript_include("js/frontend/less2/layer/layer.js") }}
    {{ javascript_include("3rdpart/validate/jquery.validate.js") }}
    {#上传文件插件#}
    {{ javascript_include("js/frontend/webUpload/upload.js") }}
    {{ javascript_include("js/frontend/webUpload/webuploader.min.js") }}
    {{ javascript_include("js/frontend/group/create.js") }}
    {{ javascript_include("js/frontend/group/createModal.js") }}
    <script type="text/javascript">
        $(function(){
        })
    </script>
{% endblock %}