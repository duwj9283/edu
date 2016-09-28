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
                        <div class="search_container pull-right search-file">
                            <div class="s_box"><i></i><input placeholder="搜索全部文件" name="file-name" class="bor_1"></div>
                        </div>
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
                        <div class="simple_btn btn_multiple_move blue_font">
                            <i class="tool_icon icon_blue_move"></i>
                            <label class="tool_btn_desc">移动到</label>
                        </div>
                        <div class="simple_btn btn_simple_move blue_font back_dir">
                            <i class="tool_icon icon_blue_move"></i>
                            <label class="tool_btn_desc">返回上一层</label>
                        </div>
                    </div>
                    <div class="detail_body_wrapper" level="" id="file-list" data-type="{% if type %}{{ type }}{% else %}0{% endif %}">

                        {#{% for  userFile in userFiles  %}#}
                            {#{% if userFile['file_type'] ==1 %}#}
                            {#<div class="item" level="" folder="{{ userFile['id'] }}"  data-type="{{ userFile['file_type'] }}">#}
                                {#<div class="item_detail folder_item">#}
                                    {#<div class="item_detail_img_wrap item_click_area folder_click">#}
                                        {#<img class="folder" src="/img/frontend/home/folder.png?v=1">#}
                                    {#</div>#}
                                    {#<div class="item_info_wrap">#}
                                        {#<div class="item_info_detail">#}
                                            {#<div class="item_name ellipsis text-center">{{ userFile['file_name'] }}</div>#}
                                            {#&#123;&#35;<label class="padding-10 fs-b item_name ellipsis">{{ userFile['file_name'] }}</label>&#35;&#125;#}
                                        {#</div>#}
                                    {#</div>#}
                                {#</div>#}
                            {#</div>#}
                            {#{% elseif userFile['file_type'] ==2 %}#}
                            {#<div class="item" level="" folder="{{ userFile['id'] }}"  data-type="{{ userFile['file_type'] }}">#}
                                {#<div class="item_detail">#}
                                    {#<div class="item_detail_img_wrap item_click_area ">#}
                                        {#{{ image('/api/source/getImageThumb/'~userFile['id']~'/260/260',"class","hover_controller", "alt": "not authorized") }}#}
                                        {#<i class="icon-7 btn-status"></i>#}
                                        {#<i class="icon-eye"></i>#}
                                        {#<div class="cover_layer_wrap">#}
                                            {#<div class="cover_btn btn-pv-play"></div>#}
                                        {#</div>#}
                                    {#</div>#}
                                    {#<div class="item_info_wrap">#}
                                        {#<div class="item_info_detail">#}
                                            {#<div class="item_name ellipsis text-center"><div class="file-name">{{ userFile['file_name'] }}</div></div>#}
                                            {#&#123;&#35;<label class="item_name ">&#35;&#125;#}
                                                {#&#123;&#35;<i class="icon-6 img-icon"></i>&#35;&#125;#}
                                                {#&#123;&#35;<label class="file-name ellipsis">{{ userFile['file_name'] }}</label>&#35;&#125;#}
                                            {#&#123;&#35;</label>&#35;&#125;#}
                                            {#&#123;&#35;<label class="pl-40 ellipsis author_name">&#35;&#125;#}
                                                {#&#123;&#35;<span>文件大小：</span><sapn>{{ userFile['fileSize'] }}</sapn>&#35;&#125;#}
                                            {#&#123;&#35;</label>&#35;&#125;#}
                                        {#</div>#}
                                        {#&#123;&#35;<div class="download_info item-action-wrapper">&#35;&#125;#}
                                            {#&#123;&#35;<div class="simple_btn btn_simple_share ">&#35;&#125;#}
                                                {#&#123;&#35;<i class="tool_icon icon_blue_share"></i>&#35;&#125;#}
                                                {#&#123;&#35;<label class="tool_btn_desc">发布</label>&#35;&#125;#}
                                            {#&#123;&#35;</div>&#35;&#125;#}
                                            {#&#123;&#35;<div class="simple_btn btn_simple_download ">&#35;&#125;#}
                                                {#&#123;&#35;<i class="tool_icon icon_blue_download"></i>&#35;&#125;#}
                                                {#&#123;&#35;<label class="tool_btn_desc">下载</label>&#35;&#125;#}
                                            {#&#123;&#35;</div>&#35;&#125;#}
                                            {#&#123;&#35;<div class="simple_btn btn_simple_rename ">&#35;&#125;#}
                                                {#&#123;&#35;<i class="tool_icon_1 icon_rename"></i>&#35;&#125;#}
                                                {#&#123;&#35;<label class="tool_btn_desc">重命名</label>&#35;&#125;#}
                                            {#&#123;&#35;</div>&#35;&#125;#}
                                            {#&#123;&#35;<div class="simple_btn btn_simple_move">&#35;&#125;#}
                                                {#&#123;&#35;<i class="tool_icon icon_blue_move"></i>&#35;&#125;#}
                                                {#&#123;&#35;<label class="tool_btn_desc">移动到</label>&#35;&#125;#}
                                            {#&#123;&#35;</div>&#35;&#125;#}
                                        {#&#123;&#35;</div>&#35;&#125;#}
                                    {#</div>#}
                                {#</div>#}
                            {#</div>#}
                            {#{% elseif userFile['file_type'] ==3 %}#}
                            {#<div class="item" level="" folder="{{ userFile['id'] }}"  data-type="{{ userFile['file_type'] }}">#}
                                {#<div class="item_detail">#}
                                    {#<div class="item_click_area item_detail_img_wrap btn-img-preview">#}
                                        {#{{ image('/api/source/getImageThumb/'~userFile['id']~'/260/260',"class","hover_controll", "alt": "not authorized") }}#}
                                        {#<i class="icon-7 btn-status"></i>#}
                                    {#</div>#}
                                    {#<div class="item_info_wrap">#}
                                        {#<div class="item_info_detail">#}
                                            {#<div class="item_name ellipsis text-center"><div class="file-name">{{ userFile['file_name'] }}</div></div>#}
                                            {#&#123;&#35;<label class="item_name ellipsis">&#35;&#125;#}
                                                {#&#123;&#35;<i class="icon-6 img-icon"></i>&#35;&#125;#}
                                                {#&#123;&#35;<label class="file-name ellipsis">{{ userFile['file_name'] }}</label>&#35;&#125;#}
                                            {#&#123;&#35;</label>&#35;&#125;#}
                                            {#&#123;&#35;<label class="pl-40 ellipsis author_name">&#35;&#125;#}
                                                {#&#123;&#35;<span>文件大小：</span><sapn>{{ userFile['fileSize'] }}</sapn>&#35;&#125;#}
                                            {#&#123;&#35;</label>&#35;&#125;#}
                                        {#</div>#}
                                        {#&#123;&#35;<div class="download_info item-action-wrapper">&#35;&#125;#}
                                            {#&#123;&#35;<div class="simple_btn btn_simple_share ">&#35;&#125;#}
                                                {#&#123;&#35;<i class="tool_icon icon_blue_share"></i>&#35;&#125;#}
                                                {#&#123;&#35;<label class="tool_btn_desc">发布</label>&#35;&#125;#}
                                            {#&#123;&#35;</div>&#35;&#125;#}
                                            {#&#123;&#35;<div class="simple_btn btn_simple_download ">&#35;&#125;#}
                                                {#&#123;&#35;<i class="tool_icon icon_blue_download"></i>&#35;&#125;#}
                                                {#&#123;&#35;<label class="tool_btn_desc">下载</label>&#35;&#125;#}
                                            {#&#123;&#35;</div>&#35;&#125;#}
                                            {#&#123;&#35;<div class="simple_btn btn_simple_rename ">&#35;&#125;#}
                                                {#&#123;&#35;<i class="tool_icon_1 icon_rename"></i>&#35;&#125;#}
                                                {#&#123;&#35;<label class="tool_btn_desc">重命名</label>&#35;&#125;#}
                                            {#&#123;&#35;</div>&#35;&#125;#}
                                            {#&#123;&#35;<div class="simple_btn btn_simple_move">&#35;&#125;#}
                                                {#&#123;&#35;<i class="tool_icon icon_blue_move"></i>&#35;&#125;#}
                                                {#&#123;&#35;<label class="tool_btn_desc">移动到</label>&#35;&#125;#}
                                            {#&#123;&#35;</div>&#35;&#125;#}
                                        {#&#123;&#35;</div>&#35;&#125;#}
                                    {#</div>#}
                                {#</div>#}
                            {#</div>#}
                            {#{% elseif userFile['file_type'] ==4 %}#}
                            {#<div class="item" level="" folder="{{ userFile['id'] }}"  data-type="{{ userFile['file_type'] }}">#}
                                {#<div class="item_detail">#}
                                    {#<div class="item_detail_img_wrap item_click_area">#}
                                        {#<img class="hover_controller" src="/img/frontend/tem_material/audio.png?v=1">#}
                                        {#<i class="icon-7 btn-status"></i>#}
                                        {#<div class="cover_layer_wrap">#}
                                            {#<div class="cover_btn  btn-pv-play"></div>#}
                                        {#</div>#}
                                    {#</div>#}
                                    {#<div class="item_info_wrap">#}
                                        {#<div class="item_info_detail">#}
                                            {#<div class="item_name ellipsis text-center"><div class="file-name">{{ userFile['file_name'] }}</div></div>#}
                                            {#&#123;&#35;<label class="item_name ">&#35;&#125;#}
                                                {#&#123;&#35;<i class="icon-6 img-icon"></i>&#35;&#125;#}
                                                {#&#123;&#35;<label class="file-name ellipsis">{{ userFile['file_name'] }}</label>&#35;&#125;#}
                                            {#&#123;&#35;</label>&#35;&#125;#}
                                            {#&#123;&#35;<label class="pl-40 ellipsis author_name">&#35;&#125;#}
                                                {#&#123;&#35;<span>文件大小：</span><sapn>{{ userFile['fileSize'] }}</sapn>&#35;&#125;#}
                                            {#&#123;&#35;</label>&#35;&#125;#}
                                        {#</div>#}
                                        {#&#123;&#35;<div class="download_info item-action-wrapper">&#35;&#125;#}
                                            {#&#123;&#35;<div class="simple_btn btn_simple_share ">&#35;&#125;#}
                                                {#&#123;&#35;<i class="tool_icon icon_blue_share"></i>&#35;&#125;#}
                                                {#&#123;&#35;<label class="tool_btn_desc">发布</label>&#35;&#125;#}
                                            {#&#123;&#35;</div>&#35;&#125;#}
                                            {#&#123;&#35;<div class="simple_btn btn_simple_download ">&#35;&#125;#}
                                                {#&#123;&#35;<i class="tool_icon icon_blue_download"></i>&#35;&#125;#}
                                                {#&#123;&#35;<label class="tool_btn_desc">下载</label>&#35;&#125;#}
                                            {#&#123;&#35;</div>&#35;&#125;#}
                                            {#&#123;&#35;<div class="simple_btn btn_simple_rename ">&#35;&#125;#}
                                                {#&#123;&#35;<i class="tool_icon_1 icon_rename"></i>&#35;&#125;#}
                                                {#&#123;&#35;<label class="tool_btn_desc">重命名</label>&#35;&#125;#}
                                            {#&#123;&#35;</div>&#35;&#125;#}
                                            {#&#123;&#35;<div class="simple_btn btn_simple_move">&#35;&#125;#}
                                                {#&#123;&#35;<i class="tool_icon icon_blue_move"></i>&#35;&#125;#}
                                                {#&#123;&#35;<label class="tool_btn_desc">移动到</label>&#35;&#125;#}
                                            {#&#123;&#35;</div>&#35;&#125;#}
                                        {#&#123;&#35;</div>&#35;&#125;#}
                                    {#</div>#}
                                {#</div>#}
                            {#</div>#}
                            {#{% elseif userFile['file_type'] ==5 %}#}
                            {#<div class="item" level="" folder="{{ userFile['id'] }}"  data-type="{{ userFile['file_type'] }}">#}
                                {#<div class="item_detail item_doc">#}
                                    {#<div class="doc_wrap item_click_area">#}
                                        {#<i class="hover_controller doc_pdf btn-pv-pdf"></i>#}
                                        {#<i class="icon-7 btn-status"></i>#}
                                    {#</div>#}
                                    {#<div class="item_info_wrap">#}
                                        {#<div class="item_info_detail">#}
                                            {#<div class="item_name ellipsis text-center"><div class="file-name">{{ userFile['file_name'] }}</div></div>#}
                                            {#&#123;&#35;<label class="item_name ">&#35;&#125;#}
                                                {#&#123;&#35;<i class="icon-6 pdf-icon"></i>&#35;&#125;#}
                                                {#&#123;&#35;<label class="file-name ellipsis">{{ userFile['file_name'] }}</label>&#35;&#125;#}
                                            {#&#123;&#35;</label>&#35;&#125;#}
                                            {#&#123;&#35;<label class="pl-40 ellipsis author_name">&#35;&#125;#}
                                                {#&#123;&#35;<span>文件大小：</span><span>{{ userFile['fileSize'] }}</span>&#35;&#125;#}
                                            {#&#123;&#35;</label>&#35;&#125;#}
                                        {#</div>#}
                                        {#&#123;&#35;<div class="download_info item-action-wrapper">&#35;&#125;#}
                                            {#&#123;&#35;<div class="simple_btn btn_simple_share ">&#35;&#125;#}
                                                {#&#123;&#35;<i class="tool_icon icon_blue_share"></i>&#35;&#125;#}
                                                {#&#123;&#35;<label class="tool_btn_desc">发布</label>&#35;&#125;#}
                                            {#&#123;&#35;</div>&#35;&#125;#}
                                            {#&#123;&#35;<div class="simple_btn btn_simple_download ">&#35;&#125;#}
                                                {#&#123;&#35;<i class="tool_icon icon_blue_download"></i>&#35;&#125;#}
                                                {#&#123;&#35;<label class="tool_btn_desc">下载</label>&#35;&#125;#}
                                            {#&#123;&#35;</div>&#35;&#125;#}
                                            {#&#123;&#35;<div class="simple_btn btn_simple_rename ">&#35;&#125;#}
                                                {#&#123;&#35;<i class="tool_icon_1 icon_rename"></i>&#35;&#125;#}
                                                {#&#123;&#35;<label class="tool_btn_desc">重命名</label>&#35;&#125;#}
                                            {#&#123;&#35;</div>&#35;&#125;#}
                                            {#&#123;&#35;<div class="simple_btn btn_simple_move">&#35;&#125;#}
                                                {#&#123;&#35;<i class="tool_icon icon_blue_move"></i>&#35;&#125;#}
                                                {#&#123;&#35;<label class="tool_btn_desc">移动到</label>&#35;&#125;#}
                                            {#&#123;&#35;</div>&#35;&#125;#}
                                        {#&#123;&#35;</div>&#35;&#125;#}
                                    {#</div>#}
                                {#</div>#}
                            {#</div>#}
                            {#{% elseif userFile['file_type'] ==6 %}#}
                            {#<div class="item" level="" folder="{{ userFile['id'] }}" data-type="{{ userFile['file_type'] }}">#}
                                {#<div class="item_detail item_doc">#}
                                    {#<div class="doc_wrap item_click_area">#}
                                        {#<i class="hover_controller archive_rar"></i>#}
                                        {#<i class="icon-7 btn-status"></i>#}
                                    {#</div>#}
                                    {#<div class="item_info_wrap">#}
                                        {#<div class="item_info_detail">#}
                                            {#<div class="item_name ellipsis text-center"><div class="file-name">{{ userFile['file_name'] }}</div></div>#}
                                            {#&#123;&#35;<label class="item_name ">&#35;&#125;#}
                                                {#&#123;&#35;<i class="icon-6 ysb-icon"></i>&#35;&#125;#}
                                                {#&#123;&#35;<label class="file-name ellipsis">{{ userFile['file_name'] }}</label>&#35;&#125;#}
                                            {#&#123;&#35;</label>&#35;&#125;#}
                                            {#&#123;&#35;<label class="pl-40 ellipsis author_name">&#35;&#125;#}
                                                {#&#123;&#35;<span>文件大小：</span><span>{{ userFile['fileSize'] }}</span>&#35;&#125;#}
                                            {#&#123;&#35;</label>&#35;&#125;#}
                                        {#</div>#}
                                        {#&#123;&#35;<div class="download_info item-action-wrapper">&#35;&#125;#}
                                            {#&#123;&#35;<div class="simple_btn btn_simple_share ">&#35;&#125;#}
                                                {#&#123;&#35;<i class="tool_icon icon_blue_share"></i>&#35;&#125;#}
                                                {#&#123;&#35;<label class="tool_btn_desc">发布</label>&#35;&#125;#}
                                            {#&#123;&#35;</div>&#35;&#125;#}
                                            {#&#123;&#35;<div class="simple_btn btn_simple_download ">&#35;&#125;#}
                                                {#&#123;&#35;<i class="tool_icon icon_blue_download"></i>&#35;&#125;#}
                                                {#&#123;&#35;<label class="tool_btn_desc">下载</label>&#35;&#125;#}
                                            {#&#123;&#35;</div>&#35;&#125;#}
                                            {#&#123;&#35;<div class="simple_btn btn_simple_rename ">&#35;&#125;#}
                                                {#&#123;&#35;<i class="tool_icon_1 icon_rename"></i>&#35;&#125;#}
                                                {#&#123;&#35;<label class="tool_btn_desc">重命名</label>&#35;&#125;#}
                                            {#&#123;&#35;</div>&#35;&#125;#}
                                            {#&#123;&#35;<div class="simple_btn btn_simple_move">&#35;&#125;#}
                                                {#&#123;&#35;<i class="tool_icon icon_blue_move"></i>&#35;&#125;#}
                                                {#&#123;&#35;<label class="tool_btn_desc">移动到</label>&#35;&#125;#}
                                            {#&#123;&#35;</div>&#35;&#125;#}
                                        {#&#123;&#35;</div>&#35;&#125;#}
                                    {#</div>#}
                                {#</div>#}
                            {#</div>#}
                            {#{% elseif userFile['file_type'] >=7 %}#}
                            {#<div class="item" level="" folder="{{ userFile['id'] }}"  data-type="{{ userFile['file_type'] }}">#}
                                {#<div class="item_detail item_doc">#}
                                    {#<div class="doc_wrap item_click_area">#}
                                        {#<i class="hover_controller archive_rar"></i>#}
                                        {#<i class="icon-7 btn-status"></i>#}
                                    {#</div>#}
                                    {#<div class="item_info_wrap">#}
                                        {#<div class="item_info_detail">#}
                                            {#<div class="item_name ellipsis text-center"><div class="file-name">{{ userFile['file_name'] }}</div></div>#}
                                            {#&#123;&#35;<label class="item_name ">&#35;&#125;#}
                                                {#&#123;&#35;<i class="icon-6 ysb-icon"></i>&#35;&#125;#}
                                                {#&#123;&#35;<label class="file-name ellipsis">{{ userFile['file_name'] }}</label>&#35;&#125;#}
                                            {#&#123;&#35;</label>&#35;&#125;#}
                                            {#&#123;&#35;<label class="pl-40 ellipsis author_name">&#35;&#125;#}
                                                {#&#123;&#35;<span>文件大小：</span><sapn>{{ userFile['fileSize'] }}</sapn>&#35;&#125;#}
                                            {#&#123;&#35;</label>&#35;&#125;#}
                                        {#</div>#}
                                        {#&#123;&#35;<div class="download_info item-action-wrapper">&#35;&#125;#}
                                            {#&#123;&#35;<div class="simple_btn btn_simple_share ">&#35;&#125;#}
                                                {#&#123;&#35;<i class="tool_icon icon_blue_share"></i>&#35;&#125;#}
                                                {#&#123;&#35;<label class="tool_btn_desc">发布</label>&#35;&#125;#}
                                            {#&#123;&#35;</div>&#35;&#125;#}
                                            {#&#123;&#35;<div class="simple_btn btn_simple_download ">&#35;&#125;#}
                                                {#&#123;&#35;<i class="tool_icon icon_blue_download"></i>&#35;&#125;#}
                                                {#&#123;&#35;<label class="tool_btn_desc">下载</label>&#35;&#125;#}
                                            {#&#123;&#35;</div>&#35;&#125;#}
                                            {#&#123;&#35;<div class="simple_btn btn_simple_rename">&#35;&#125;#}
                                                {#&#123;&#35;<i class="tool_icon_1 icon_rename"></i>&#35;&#125;#}
                                                {#&#123;&#35;<label class="tool_btn_desc">重命名</label>&#35;&#125;#}
                                            {#&#123;&#35;</div>&#35;&#125;#}
                                            {#&#123;&#35;<div class="simple_btn btn_simple_move">&#35;&#125;#}
                                                {#&#123;&#35;<i class="tool_icon icon_blue_move"></i>&#35;&#125;#}
                                                {#&#123;&#35;<label class="tool_btn_desc">移动到</label>&#35;&#125;#}
                                            {#&#123;&#35;</div>&#35;&#125;#}
                                        {#&#123;&#35;</div>&#35;&#125;#}
                                    {#</div>#}
                                {#</div>#}
                            {#</div>#}
                            {#{% endif %}#}
                        {#{% endfor %}#}
                    </div>
                    <div class="detail-add"></div>
                </div>

            </div>
        </div>
    </div>
{% endblock %}
{% block commonModal %}
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
    {#<div id="preview-popover" class="preview-pop">#}
        {#<b></b>#}
        {#<div class="pv-header"><a class="position-absolute pv-closed"></a></div>#}
        {#<div class="preview-container" id="preview-container">#}
            {#<div id="preview-pdf"></div>#}
            {#<div id="preview-video"></div>#}
            {#<div id="preview-img"></div>#}
            {#&#123;&#35;<div id="preview-audio">&#35;&#125;#}
                {#&#123;&#35;<audio src="" controls="controls">&#35;&#125;#}
                    {#&#123;&#35;您的浏览器不支持播放预览&#35;&#125;#}
                {#&#123;&#35;</audio>&#35;&#125;#}
            {#&#123;&#35;</div>&#35;&#125;#}
            {#<div id="preview-img"></div>#}
        {#</div>#}
    {#</div>#}
    {#单个发布文件#}
    <div id="push-file-popover" class="push-file-popover">
        <div class="push-body form-horizontal">
            <div class="form-group" id="subject-select">
                <label for="inputEmail3" class="col-sm-2 control-label">学科</label>
                <div class="col-sm-5">
                    <select class="sub-type form-control"></select>
                </div>
                <div class="col-sm-5">
                    <select class="sub-child-type form-control"></select>
                </div>

            </div>
            <div class="form-group" id="subject-select">
                <label for="inputEmail3" class="col-sm-2 control-label">应用类型</label>
                <div class="col-sm-5">
                    <select class="use-type form-control">
                        {% for applicationType in applicationTypes %}
                            <option value="{{ applicationType['id'] }}">{{ applicationType['name'] }}</option>
                        {% endfor %}
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">知识点</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control kld-point" value=""  >
                </div>
            </div>
            <div class="form-group form-group-language">
                <label for="inputEmail3" class="col-sm-2 control-label">语言</label>
                <div class="col-sm-10">
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
                </div>
            </div>
            <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">简介</label>
                <div class="col-sm-10">
                    <textarea class="form-control push-file-intro" row="5"></textarea>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10 text-center"><span class="btn-push-file btn">发布</span></div>
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


    <div id="preview-video" style="display: none"></div>
    <div id="preview-close" style="display: none" class="position-absolute pv-closed"></div>
    <div class="ppt-wrap js-ppt" style="display: none" >
        <div class="ppt-con">
                <div class="loading"></div>
        </div>
        <div class="ppt-bar">
            <a href="javascript:;" class="pre"></a>
            <span><input id="txtPictureIndex" type="text" value="1"> / <em class="js-total"></em></span>
            <a href="javascript:;" class="next"></a>
        </div>
    </div>
{% endblock %}
{% block pageJs %}
    {#{{ javascript_include("3rdpart/bootstrap/js/bootstrap.min.js") }}#}
    {#{{ javascript_include("/3rdpart/jquery-ui/jquery-ui.js") }}#}
    {#{{ javascript_include("js/frontend/home.js") }}#}
    {#{{ javascript_include("js/frontend/constants.js") }}#}
    {#{{ javascript_include("js/frontend/webUpload/upload.js") }}#}
    {#{{ javascript_include("js/frontend/webUpload/webuploader.min.js") }}#}
    {#{{ javascript_include("3rdpart/peity/jquery.peity.min.js") }}#}
    {#{{ javascript_include("js/backend/peity-demo.js") }}#}
    {#{{ javascript_include("3rdpart/iCheck/icheck.min.js") }}#}

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

