<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>独角兽教育平台</title>
    {{ stylesheet_link("3rdpart/bootstrap/css/bootstrap.min.css") }}
    {{ stylesheet_link("css/frontend/home.css") }}
    {{ stylesheet_link("css/frontend/file.css") }}
    {{ stylesheet_link("css/frontend/pop-modal.css") }}
    {{ stylesheet_link("css/frontend/draggable.css") }}
    <style>
    </style>
</head>
<body>
<div class="container_header">
    {% include "template/header.volt" %}
</div>

<div class="container_inner">
<div class="center_wrap">
<div class="ci_header col_side_list_wrap selected">
<i class="col_side_icon  icon_header"></i>
<label class="col_side_desc">我的文件</label>
</div>
</div>
<div class="inner_body">
<div class="center_wrap">
<div class="col_side_wrap body_side">
<div class="col_side_list_wrap btn_icon_all selected">
<i class="col_side_icon icon_all"></i>
<label class="col_side_desc">全部文件</label>
</div>
<div class="col_side_list_wrap btn_icon_collect">
<i class="col_side_icon icon_collect"></i>
<label class="col_side_desc">收藏</label>
</div>
<div class="col_side_list_wrap btn_icon_video">
<i class="col_side_icon icon_video"></i>
<label class="col_side_desc">视频</label>
</div>
<div class="col_side_list_wrap btn_icon_pic">
<i class="col_side_icon icon_pic"></i>
<label class="col_side_desc">图片</label>
</div>
<div class="col_side_list_wrap btn_icon_audio">
<i class="col_side_icon icon_audio"></i>
<label class="col_side_desc">音频</label>
</div>
<div class="col_side_list_wrap btn_icon_share" style="margin-top: 10px;">
<i class="col_side_icon icon_share"></i>
<label class="col_side_desc">分享管理</label>
</div>
<div class="col_side_list_wrap btn_icon_trash" style="margin-top: 10px;">
<i class="col_side_icon icon_trash"></i>
<label class="col_side_desc">回收站</label>
</div>
<div class="col_side_list_wrap btn_icon_android" style="margin-top: 10px;">
<i class="col_side_icon icon_android"></i>
<label class="col_side_desc">Android</label>
</div>
<div class="col_side_list_wrap btn_icon_iphone">
<i class="col_side_icon icon_iphone"></i>
<label class="col_side_desc">iPhone</label>
</div>
<div class="col_side_list_wrap btn_icon_ipad">
<i class="col_side_icon icon_ipad"></i>
<label class="col_side_desc">iPad</label>
</div>
</div>
<div class="detail_wrapper">
<div class="toolbar_wrap">
<label for="choose-file" class="btn btn_upload">
{#<label for="create_folder" class="btn btn_upload">#}
<i class="tool_icon icon_upload"></i>
<div class="tool_btn_desc">上传</div>
<input type="file" multiple="multiple" id="choose-file" style="display: none;">
</label>
<div class="btn btn_new">
<i class="tool_icon icon_new"></i>
<label class="tool_btn_desc">新建文件夹</label>
</div>
<div class="simple_btn btn_simple_share blue_font">
<i class="tool_icon icon_blue_share"></i>
<label class="tool_btn_desc">分享</label>
</div>
<div class="simple_btn btn_simple_download blue_font">
<i class="tool_icon icon_blue_download"></i>
<label class="tool_btn_desc">下载</label>
</div>
<div class="simple_btn btn_simple_del blue_font">
<i class="tool_icon icon_blue_del"></i>
<label class="tool_btn_desc">删除</label>
</div>
<div class="simple_btn btn_simple_move blue_font">
<i class="tool_icon icon_blue_move"></i>
<label class="tool_btn_desc">移动到</label>
</div>
<div class="simple_btn btn_simple_move blue_font back_dir">
<i class="tool_icon icon_blue_move"></i>
<label class="tool_btn_desc">返回上一层</label>
</div>
</div>
<div class="detail_body_wrapper" level="" >
{% for  userFile in userFiles  %}
{% if userFile.file_type ==1 %}
<div class="item" level="" folder="{{ userFile.id }}">
<div class="item_detail folder_item">
<div class="item_detail_img_wrap item_click_area folder_click">
<img class="folder" src="img/frontend/home/folder.png?v=1">
</div>
{% elseif userFile.file_type ==2 %}
<div class="item" level="" folder="{{ userFile.id }}">
<div class="item_detail">
<div class="item_detail_img_wrap item_click_area">
{{ image(userFile.getPathTmp(),"class","hover_controller", "alt": "not authorized") }}
<div class="cover_layer_wrap">
<div class="opacity_layer"></div>
<div class="cover_btn"></div>
</div>
</div>
{% elseif userFile.file_type ==3 %}
<div class="item" level="" folder="{{ userFile.id }}">
<div class="item_detail">
<div class="item_click_area item_detail_img_wrap">
{{ image(userFile.getPathTmp(), "alt": "not authorized") }}
</div>
{% elseif userFile.file_type ==4 %}
<div class="item" level="" folder="{{ userFile.id }}">
<div class="item_detail">
<div class="item_detail_img_wrap item_click_area">
<img class="hover_controller" src="img/frontend/tem_material/audio.png?v=1">
<div class="cover_layer_wrap">
<div class="opacity_layer"></div>
<div class="cover_btn"></div>
</div>
</div>
{% elseif userFile.file_type ==5 %}
<div class="item" level="" folder="{{ userFile.id }}">
<div class="item_detail item_doc">
<div class="doc_wrap item_click_area">
<i class="hover_controller doc_pdf"></i>
<p class="doc_name">{{ userFile.file_name }}</p>
</div>
{% elseif userFile.file_type ==6 %}
<div class="item" level="" folder="{{ userFile.id }}">
<div class="item_detail item_doc">
<div class="doc_wrap item_click_area">
<i class="hover_controller archive_rar"></i>
<p class="doc_name">{{ userFile.file_name }}</p>
</div>
{% elseif userFile['file_type'] ==7 %}
<div class="item" level="" folder="{{ userFile['id'] }}">
<div class="item_detail">
<div class="item_click_area item_detail_img_wrap">
<img class="hover_controller" src="img/frontend/tem_material/sc8.jpg?v=1">
</div>
{% endif %}
<div class="item_info_wrap">
<div class="item_info_detail">
    <label class="item_name ellipsis">{{ userFile.file_name }}</label>
    <label class="author_name ellipsis"><span>by</span><sapn>{{ userInfo.nick_name }}</sapn></label>
</div>
<div class="download_info">
    <div class="simple_btn btn_simple_share blue_font">
        <i class="tool_icon icon_blue_share"></i>
        <label class="tool_btn_desc">分享</label>
    </div>
    <div class="simple_btn btn_simple_download blue_font">
        <i class="tool_icon icon_blue_download"></i>
        <label class="tool_btn_desc">下载</label>
    </div>
    <div class="simple_btn btn_simple_del blue_font">
        <i class="tool_icon icon_blue_del"></i>
        <label class="tool_btn_desc">删除</label>
    </div>
    <div class="simple_btn btn_simple_move blue_font">
        <i class="tool_icon icon_blue_move"></i>
        <label class="tool_btn_desc">移动到</label>
    </div>
</div>
</div>
</div>
</div>
{% endfor %}

</div>
</div>
</div>
</div>
</div>
<ul id="contextMenu" class="dropdown-menu right_menu">
<li class="icon_download_li">
<i class="tool_icon yellow_tool_icon icon_yellow_download"></i>
<label class="tool_btn_desc">下载</label>
</li>
<li class="icon_collect_li">
<i class="tool_icon yellow_tool_icon icon_yellow_collect"></i>
<label class="tool_btn_desc">收藏</label>
</li>
<li class="icon_rename_li">
<i class="tool_icon yellow_tool_icon icon_yellow_rename"></i>
<label class="tool_btn_desc">重命名</label>
</li>
<li class="icon_move_li">
<i class="tool_icon yellow_tool_icon icon_yellow_move"></i>
<label class="tool_btn_desc">移动到</label>
</li>
<li class="icon_share_li">
<i class="tool_icon yellow_tool_icon icon_yellow_share"></i>
<label class="tool_btn_desc">分享</label>
</li>
{#<li class="icon_hide_li">
<i class="tool_icon yellow_tool_icon icon_yellow_hide"></i>
<label class="tool_btn_desc">隐藏</label>
</li>#}
<li class="icon_del_li">
<i class="tool_icon yellow_tool_icon icon_yellow_del"></i>
<label class="tool_btn_desc">删除</label>
</li>
</ul>
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
{% include "template/draggable.volt" %}
{% include "template/bootstrap-modals.volt" %}
{{ javascript_include("3rdpart/jquery/jquery.min.js") }}
{{ javascript_include("3rdpart/jquery-ui/jquery-ui.js") }}
{{ javascript_include("3rdpart/bootstrap/js/bootstrap.min.js") }}
{{ javascript_include("3rdpart/bootstrap/js/bootstrap-modal.js") }}
{{ javascript_include("js/frontend/home.js") }}
{{ javascript_include("js/frontend/constants.js") }}
{{ javascript_include("js/frontend/file.js") }}
{{ javascript_include("js/frontend/fileJs/menuAction.js") }}
{{ javascript_include("js/frontend/socketUpload.js") }}
<!-- Peity -->
{{ javascript_include("3rdpart/peity/jquery.peity.min.js") }}
{{ javascript_include("js/backend/peity-demo.js") }}
{{ javascript_include("3rdpart/iCheck/icheck.min.js") }}
{{ javascript_include("js/backend/socket.io.js") }}
{{ javascript_include("js/backend/md5.js") }}
{{ javascript_include("js/backend/spark-md5.js") }}
<script type="text/javascript">
    $(function(){
        //选择默认页面
        $('.file_page').addClass('current_page');
        //处理模板
        $('.search_wrap').css('padding-left','0px');
        $('.container_header .logo_wrap').hide();
        $('.container_header .welcome_wrap').hide();
        $('.search_wrap .search_header').show();
    });
</script>

