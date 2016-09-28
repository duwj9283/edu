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
                            <span class="capacity_progress_bottom" readonly></span>
                            <span class="capacity_progress_bar"></span>
                        </div>
                        <div class="capacity_number_wrap">
                            <span class="used_capacity">0.0 GB</span>
                            <span>/</span>
                            <span class="total_capacity">0.0 GB</span>
                            <a class="apply_capacity" apply>提升容量申请</a>
                        </div>
                    </div>
                    <div class="toolbar_wrap clearfix">
                        <div class="simple_btn btn_simple_move blue_font back_dir">
                            <i class="tool_icon icon_blue_move"></i>
                            <label class="tool_btn_desc">返回上一层</label>
                        </div>
                    </div>
                    <div class="detail_body_wrapper trahs-list" id="file-list" level=""  trash="true">

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
    <div id="preview-video" style="display: none"></div>
    <div id="preview-close" style="display: none" class="position-absolute pv-closed"></div>
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

    {#视频播放插件#}
    {{ javascript_include("3rdpart/jwplay/jwplayer.js") }}
    {#artTemplate#}
    {{ javascript_include("/3rdpart/template/template.js") }}
    <script type="text/javascript">
        $(function(){
            //处理模板
            //FILEMODEL.setWrapType($('.col_side_li_2_wrap.my_file_wrap .col_side_list_wrap.btn_icon_visible'));
            $('.col_side_wrap .col_side_list_wrap').removeClass("selected");
            $('.col_side_2_title').removeClass('gly-up');
            $('.col_side_li_2_wrap').hide();
            $('.col_side_list_wrap.btn_icon_trash').addClass("selected");
            FILEMODEL.getTrashFilesByPage(getParentDir());
        });
    </script>
{% endblock %}

