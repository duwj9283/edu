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
{#选择课件或资料弹框#}
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

