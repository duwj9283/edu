{% extends 'template/basic-v2.volt' %}
{% block pageCss %}
    {{ stylesheet_link("css/frontend/less/lessbase.css") }}
    {{ stylesheet_link("css/frontend/col-side.css") }}
{% endblock %}
{% block content %}
    <div class="inner_body content">
    <div class="person-con-infor">
        <div class="container clearfix">
            {% include 'template/col-side.volt' %}
            <div class="body_container">
                <div class="per-info-tit per-info-tit02 margin-top-30">
                    <span>修改密码</span>
                </div>
                <div class="line margin-top-10"></div>
                <div class="person-index-block02 person-index-block widthauto margin-top-10">
                    <ul class="set-person-infor">
                        <li>
                            <label class=" old-password">
                                <i class="tab-ico"></i>
                                <input type="text" onblur="onBlur(this);" onfocus="onFocus(this);" type="text" placeholder="请输入旧密码"  />
                            </label>
                        </li>
                        <li>
                            <label class=" new-password">
                                <i class="tab-ico"></i>
                                <input type="text" onblur="onBlur(this);" onfocus="onFocus(this);" type="text"  placeholder="请输入新密码"   />
                            </label>
                        </li>
                        <li>
                            <label class=" new-password">
                                <i class="tab-ico"></i>
                                <input type="text" onblur="onBlur(this);" onfocus="onFocus(this);" type="text"  placeholder="请输入旧密码"   />
                            </label>
                        </li>
                    </ul>
                    <div class="text-align-center set-btn-box" >
                        <span class="send-btn ">确认</span>
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
    {{ javascript_include("js/frontend/setup/psw.js") }}
    {{ javascript_include("js/frontend/setup/pswModal.js") }}
{% endblock %}
