<div id="dragMove" class="draggable ui-widget-content dialog  move_wrapper"  >
    <div class="dialog-header dialog-drag handle_wrap">
        <h3><span class="dialog-header-title"><em class="select-text">移动到</em></span></h3>
        <div class="dialog-control"><span class="dialog-icon dialog-close">✕</span></div>
    </div>
    <div class="dialog-body">
        <div class="file-tree-container">
            <ul class="treeview treeview-content _disk_id_1">
                <li>
                    <div class="treeview-node  treeview-node-on treeview-root _minus">
                        <span class="treeview-node-handler">
                            <em class="b-in-blk plus icon-operate minus"></em>
                            <dfn class="b-in-blk treeview-ic"></dfn>
                            <span class="treeview-txt" node-path="/" node-target="0">全部文件</span>
                        </span>
                    </div>
                    <ul class="treeview treeview-root-content treeview-content" _pl="15">
                        {#<li>
                            <div class="treeview-node treenode-empty" style="padding-left:15px">
                                <span class="treeview-node-handler">
                                    <em class="b-in-blk plus icon-operate"></em>
                                    <dfn class="b-in-blk treeview-ic"></dfn>
                                    <span class="treeview-txt" node-path="/th">th</span>
                                </span>
                            </div>
                            <ul class="treeview  treeview-content treeview-collapse"></ul>
                        </li>
                        <li>
                            <div class="treeview-node" style="padding-left:15px; ">
                                <span class="treeview-node-handler">
                                    <em class="b-in-blk plus icon-operate"></em>
                                    <dfn class="b-in-blk treeview-ic"></dfn>
                                    <span class="treeview-txt" node-path="/我的资源">我的资源</span>
                                </span>
                            </div>
                            <ul class="treeview  treeview-content treeview-collapse" pl="30">
                               <li>
                                   <div class="treeview-node treenode-empty" _pl="30" style="padding-left:30px">
                                       <span class="treeview-node-handler">
                                           <em class="b-in-blk plus icon-operate"></em>
                                           <dfn class="b-in-blk treeview-ic"></dfn>
                                           <span class="treeview-txt" node-path="/我的资源/新建文件夹">新建文件夹</span>
                                       </span>
                                   </div>
                                   <ul class="treeview  treeview-content treeview-collapse" _pl="45"></ul>
                               </li>
                            </ul>
                        </li>#}
                    </ul>
                </li>
            </ul>
        </div>
    </div>
    <div class="dialog-footer g-clearfix">
        <a class="g-button g-button-large g-button-cancel" style="float: right;">
            <span class="g-button-right">取消</span>
        </a>
        <a class="g-button g-button-blue-large g-button-move" style="float: right;">
            <span class="g-button-right">确定</span>
        </a>
    </div>
</div>
<div class="module-canvas"></div>
