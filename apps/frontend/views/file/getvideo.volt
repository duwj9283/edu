{% extends "template/basic-v2.volt" %}
{% block pageCss %}
    {{ stylesheet_link("css/frontend/home.css") }}
    {{ stylesheet_link("css/frontend/file.css") }}
    {{ stylesheet_link("css/frontend/pop-modal.css") }}
    {{ stylesheet_link("css/frontend/draggable.css") }}
    {{ stylesheet_link("css/frontend/col-side.css") }}
    {{ stylesheet_link("js/frontend/webUpload/webuploader.css")  }}
{% endblock %}
{% block content %}
    <div class="container_inner">
        <div class="inner_body">
            <div class="center_wrap container">
                {% include 'template/col-side.volt' %}
                <div id="detail-wrapper" class="detail_wrapper">
                    <div class="capacity_wrap">
                        <div class="capacity_progress_wrap">
                            <input class="capacity_progress_bottom" readonly>
                            <span class="capacity_progress_bar"></span>
                        </div>
                        <div class="capacity_number_wrap">
                            <span class="used_capacity">3.0 GB</span>
                            <span>/</span>
                            <span class="total_capacity">10.0 GB</span>
                            <a class="apply_capacity"></a>
                        </div>
                    </div>
                    <div class="toolbar_wrap clearfix">
                        <div class="search_container pull-right">
                            <div class="s_box"><i></i><input placeholder="搜索全部文件" class="bor_1"></div>
                        </div>
                        <label for="choose-file" class="btn btn_upload">
                            {#<label for="create_folder" class="btn btn_upload">#}
                            <i class="tool_icon icon_upload"></i>
                            <div class="tool_btn_desc">上传</div>
                            <input type="file" multiple="multiple" id="choose-file" style="display: none;">
                        </label>
                        <div class="simple_btn btn_multiple_push blue_font">
                            <i class="tool_icon icon_blue_share"></i>
                            <label class="tool_btn_desc">发布</label>
                        </div>
                        <div class="simple_btn btn_multiple_Download blue_font">
                            <i class="tool_icon icon_blue_download"></i>
                            <label class="tool_btn_desc">下载</label>
                        </div>
                        <div class="simple_btn btn_multiple_del blue_font">
                            <i class="tool_icon icon_blue_del"></i>
                            <label class="tool_btn_desc">删除</label>
                        </div>

                    </div>
                    <div class="detail_body_wrapper" level="" data-type="2">
                        {% for  userFile in userFiles  %}
                            {% if userFile['file_type'] ==2 %}
                                <div class="item" level="" folder="{{ userFile['id'] }}">
                                    <div class="item_detail">
                                        <div class="item_detail_img_wrap item_click_area ">
                                            {{ image('/api/source/getImageThumb/'~userFile['id']~'/260/260',"class","hover_controller", "alt": "not authorized") }}
                                            <i class="icon-7 btn-status"></i>
                                            <div class="cover_layer_wrap">
                                                <div class="cover_btn btn-pv-play"></div>
                                            </div>
                                        </div>
                                        <div class="item_info_wrap">
                                            <div class="item_info_detail">
                                                <div class="item_name ellipsis text-center"><div class="file-name">{{ userFile['file_name'] }}</div></div>
                                                {#<label class="item_name ">#}
                                                    {#<i class="icon-6 img-icon"></i>#}
                                                    {#<label class="file-name ellipsis">{{ userFile['file_name'] }}</label>#}
                                                {#</label>#}
                                                {#<label class="pl-40 ellipsis author_name">#}
                                                    {#<span>文件大小：</span><sapn>{{ userFile['fileSize'] }}</sapn>#}
                                                {#</label>#}
                                            </div>
                                            {#<div class="download_info item-action-wrapper">#}
                                                {#<div class="simple_btn btn_simple_share ">#}
                                                    {#<i class="tool_icon icon_blue_share"></i>#}
                                                    {#<label class="tool_btn_desc">发布</label>#}
                                                {#</div>#}
                                                {#<div class="simple_btn btn_simple_download ">#}
                                                    {#<i class="tool_icon icon_blue_download"></i>#}
                                                    {#<label class="tool_btn_desc">下载</label>#}
                                                {#</div>#}
                                                {#<div class="simple_btn btn_simple_rename ">#}
                                                    {#<i class="tool_icon_1 icon_rename"></i>#}
                                                    {#<label class="tool_btn_desc">重命名</label>#}
                                                {#</div>#}
                                            {#</div>#}
                                        </div>
                                    </div>
                                </div>
                            {% endif %}
                        {% endfor %}
                    </div>
                    <div class="detail-add"></div>
                </div>

            </div>
        </div>
    </div>
{% endblock %}
{% block commonModal%}
    {% include 'template/context-meau.volt' %}
    {#upload#}
    <div class="small-chat-box fadeInRight animated ">
        <div class="heading" draggable="true"><div class="count_chat_close count_chat_btn pull-right">X</div><div class="count_chat_min pull-right count_chat_btn">-</div><span class="countState">上传中</span><span class="count_chat_file">0</span>/<span class="countFile">0</span> 
        </div>
        <div class="slimScrollDiv slim_scroll_div_wrap" >
            <div class="slimScrollDiv slim_scroll_div" style="">
                <div class="sidebar-container" style="overflow: hidden; width: auto; height: 100%;">
                    <div class="sidebar-tit"><p><span class="col-1">文件名</span><span class="col-2">大小</span><span class="col-3">状态</span></p></div>
                    <ul id="file_uploading" class="sidebar-list">
                      
                    </ul>
                </div>
                <div class="slimScrollBar slim_scroll_bar"></div>
                <div class="slimScrollRail slim_scroll_rail"></div>
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
    {% include "template/draggable.volt" %}
    {#预览弹窗的容器#}
    <div id="preview-popover" class="preview-pop">
        <b></b>
        <div class="pv-header"><a class="position-absolute pv-closed"></a></div>
        <div class="preview-container" id="preview-container">
            <div id="preview-pdf"></div>
            <div id="preview-video"></div>
            <div id="preview-img"></div>
        </div>
    </div>
    {#单个发布文件#}
    <div id="push-file-popover" class="push-file-popover">
        <div class="push-body">
            <div class="push-row">
                <label class="row-desc">学科</label>
                <label class="row-content">
                    <div id="subject-select">
                        <select class="sub-type"></select>
                        <select class="sub-child-type"></select>
                    </div>
                </label>
            </div>
            <div class="push-row">
                <label class="row-desc">应用类型</label>
                <label class="row-content">
                    <select class="use-type">
                        {% for applicationType in applicationTypes %}
                            <option value="{{ applicationType['id'] }}">{{ applicationType['name'] }}</option>
                        {% endfor %}
                    </select>
                </label>
            </div>
            <div class="push-row">
                <label class="row-desc">知识点</label>
                <label class="row-content">
                    <input class=" kld-point"/>
                </label>
            </div>
            <div class="push-row">
                <label class="row-desc">语言</label>
                <label class="row-content" style="padding: 6px 0px;">
                    {% for fileLanguage in fileLanguages %}
                        {% if(fileLanguage['id'] === 0) %}
                            <label class="radio-inline has-select" for="lan-{{ fileLanguage['type'] }}">
                                <i></i>
                                <input type="radio" id="lan-{{ fileLanguage['type'] }}" name="lan-radio-option" value="{{ fileLanguage['id']  }}" checked>
                                <span>{{ fileLanguage['name'] }}</span>
                            </label>
                        {% else %}
                            <label class="radio-inline" for="lan-{{ fileLanguage['type'] }}">
                                <i></i>
                                <input type="radio" id="lan-{{ fileLanguage['type'] }}" name="lan-radio-option" value="{{ fileLanguage['id']  }}" >
                                <span>{{ fileLanguage['name'] }}</span>
                            </label>
                        {% endif %}
                    {% endfor %}
                </label>
            </div>
            <div class="push-row">
                <label class="row-desc">简介</label>
                <label class="row-content">
                    <textarea class="push-file-intro" rows="5"></textarea>
                </label>
            </div>
            <div class="push-row">
                <span class="btn-push-file">发布</span>
            </div>
        </div>
    </div>
    {#批量发布文件#}
    <div id="mul-push-file-pop" class="push-file-popover">
        <div class="push-body">
            <div class="push-row" >
                <a id="upload-push-config"></a>
                <a id="download-push-config">下载配置</a>
            </div>
            <div class="push-row">
                <span class="btn-push-mul-file">发布</span>
            </div>
        </div>
    </div>
{% endblock %}
{% block pageJs %}
    {{ javascript_include("/js/frontend/basic.js") }}
    {{ javascript_include("js/frontend/file.js") }}
    {#侧边栏#}
    {{ javascript_include("js/frontend/col-side.js") }}
    {#右键菜单#}
    {{ javascript_include("js/frontend/fileJs/menuAction.js") }}
    {#提示插件#}
    {{ javascript_include("js/frontend/less2/layer/layer.js") }}
    {#滚动条#}
    {{ javascript_include("3rdpart/slimscroll/jquery.slimscroll.min.js") }}
    {#复制组件#}
    {{ javascript_include("3rdpart/zclip/jquery.zclip.js") }}
    {#发布文件#}
    {{ javascript_include("js/frontend/subjectselect/jq.subselect.js") }}
    {#上传#}
    {{ javascript_include("js/backend/socket.io.js") }}
    {{ javascript_include("js/backend/md5.js") }}
    {{ javascript_include("js/backend/spark-md5.js") }}
    {{ javascript_include("js/frontend/socketUpload.js") }}
    {#视频播放插件#}
    {{ javascript_include("3rdpart/jwplay/jwplayer.js") }}
    {#artTemplate#}
    {{ javascript_include("/3rdpart/template/template.js") }}

{% endblock %}

