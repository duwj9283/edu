<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>独角兽教育平台-个人资料</title>
    {{ stylesheet_link("3rdpart/bootstrap/css/bootstrap.min.css") }}
    {{ stylesheet_link("css/frontend/home.css") }}
    {{ stylesheet_link("css/frontend/user.css") }}
    {{ stylesheet_link("css/frontend/pop-modal.css") }}
    <style></style>
</head>
<body>

<div class="container_header">
    {% include "template/header.volt" %}
</div>
<div class="container_inner">
    <div class="center_wrap">
        <div class="ci_header col_side_list_wrap selected">
            <i class="user_headpic" style="float:left"><img style="width:60px;height:60px;" src="img/frontend/tem_material/sc91.jpg"/></i>
            <label class="user_name ellipsis" >乐行云享</label>
        </div>
        <div class="capacity_wrap">
            <div class="capacity_progress_wrap">
                <input class="capacity_progress_bottom" readonly>
                <span class="capacity_progress_bar"></span>
            </div>
            <div class="capacity_number_wrap">
                <span class="used_capacity">3.0 GB</span>
                <span>/</span>
                <span class="total_capacity">10.0 GB</span>
                <a class="apply_capacity">提升容量申请</a>
            </div>
        </div>
    </div>
    <div class="inner_body">
        <div class="center_wrap">
            <div class="col_side_wrap body_side">
                <div class="col_side_list_wrap btn_user_detail selected">
                    <i class="col_side_icon icon_header"></i>
                    <label class="col_side_desc">个人资料</label>
                </div>
                <div class="col_side_list_wrap btn_user_collect">
                    <i class="col_side_icon icon_collect"></i>
                    <label class="col_side_desc">收藏夹</label>
                </div>
                <div class="col_side_list_wrap btn_user_channel">
                    <i class="col_side_icon icon_channel"></i>
                    <label class="col_side_desc">我的群组</label>
                </div>
                <div class="col_side_list_wrap btn_user_integral">
                    <i class="col_side_icon icon_integral"></i>
                    <label class="col_side_desc">积分中心</label>
                </div>
                <div class="col_side_list_wrap btn_logout">
                    <i class="col_side_icon icon_logout"></i>
                    <label class="col_side_desc">退出</label>
                </div>
            </div>
            <div class="body_container">
                <div class="body_inner user_detail_edit_wrap" style="display:block;">
                    <ul id="userInfoTab" class="nav nav-tabs">
                        <li class="active">
                            <a href="#modify_info" data-toggle="tab">资料修改</a>
                        </li>
                        <li>
                            <a href="#headPic" data-toggle="tab">头像修改</a>
                        </li>
                    </ul>
                    <div id="userInfoContent" class="tab-content">
                        <div class="tab-pane fade in active modify_content_wrap" id="modify_info">
                            <div class="row_wrap">
                                <label class="row_desc">昵称</label>
                                <input class="row_input input_readonly input_name" value='{{ userInfo.nick_name }}' readonly/>
                                <label class="row_btn btn_show_modify">修改</label>
                                <label class="row_btn btn_modify_name">确定</label>
                            </div>
                            <div class="row_wrap">
                                <label class="row_desc">地区</label>
                                <label class="province_wrap">
                                    <div id="city" data={{ userInfo.city }} class="province_wrap">
                                        <select class="prov province_link" ></select>
                                        <select class="city province_link" disabled="disabled"></select>
                                    </div>
                                </label>
                            </div>

                            <label style="font-weight:normal;margin-top:10px;margin-left:100px;">100字以内</label>
                            <div style="margin:10px;">真实信息（真实信息不会公开显示，仅用官方活动或项目合作联系）</div>
                            <div class="row_wrap">
                                <label class="row_desc">姓名</label>
                                <input class="row_input input_realname" value="{{ userInfo.realname }}" disabled/>
                            </div>
                            <div class="row_wrap">
                                <label class="row_desc">QQ</label>
                                <input class="row_input input_qq" value="{{ userInfo.qq }}"/>
                            </div>
                            <div class="row_wrap">
                                <label class="row_desc">电话</label>
                                <input class="row_input input_phone" value="{{ user.phone }}" disabled/>
                            </div>
                            <div class="row_wrap">
                                <label class="row_desc">性别</label>
                                <label class="row_input sex_wrap" data="{{ userInfo.sex }}">
                                    <label class="sex_radio"><input type="radio" name="sexRadios" id="secret" value="保密" checked>保密</label>
                                    <label class="sex_radio"><input type="radio" name="sexRadios" id="man" value="男" >男</label>
                                    <label class="sex_radio"><input type="radio" name="sexRadios" id="female" value="女">女</label>
                                </label>
                            </div>
                            <div class="row_wrap" >
                                <label class="row_btn btn_commit" style="margin-left: 200px;margin-top:30px;">保存</label>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="headPic" style="min-height:580px;">
                            <p>暂未开放</p>
                        </div>
                    </div>
                </div>
                <div class="body_inner user_collect_wrap">
                    <table class="collect_table">
                        <tr>
                            <td>创意海报</td><td>.jpg</td><td>BY 南郑男子</td><td><i class="tool_icon icon_blue_download"></i><span style="margin-left:10px;">下载：</span><span>56</span></td>
                        </tr>
                        <tr>
                            <td>创意海报</td><td>.jpg</td><td>BY 南郑男子</td><td><i class="tool_icon icon_blue_download"></i><span style="margin-left:10px;">下载：</span><span>56</span></td>
                        </tr>
                        <tr>
                            <td>创意海报</td><td>.jpg</td><td>BY 南郑男子</td><td><i class="tool_icon icon_blue_download"></i><span style="margin-left:10px;">下载：</span><span>56</span></td>
                        </tr>
                        <tr>
                            <td>创意海报</td><td>.jpg</td><td>BY 南郑男子</td><td><i class="tool_icon icon_blue_download"></i><span style="margin-left:10px;">下载：</span><span>56</span></td>
                        </tr>

                    </table>
                </div>
                <div class="body_inner user_channel_wrap" >
                    <div class="channel_wrap">
                        <div class="channel_side_wrap">
                            <div class="channel_search_wrap">
                                <input class="input_search" placeholder="搜索"/>
                                <label class="btn_clear_search_input">×</label>
                            </div>
                            <ul class="channel_list">
                                <li class="selected">
                                    <label class="channel_headpic"><img style="width:50px;height:50px;" src="img/frontend/tem_material/sc91.jpg"/></label>
                                    <label class="channel_name ellipsis">群组1</label>
                                </li>
                                <li>
                                    <label class="channel_headpic"><img style="width:50px;height:50px;" src="img/frontend/tem_material/sc91.jpg"/></label>
                                    <label class="channel_name ellipsis">群组2</label>
                                </li>
                                <li>
                                    <label class="channel_headpic"><img style="width:50px;height:50px;" src="img/frontend/tem_material/sc91.jpg"/></label>
                                    <label class="channel_name ellipsis">群组3</label>
                                </li>
                                <li>
                                    <label class="channel_headpic"><img style="width:50px;height:50px;" src="img/frontend/tem_material/sc91.jpg"/></label>
                                    <label class="channel_name ellipsis">群组4</label>
                                </li>
                                <li>
                                    <label class="channel_headpic"><img style="width:50px;height:50px;" src="img/frontend/tem_material/sc91.jpg"/></label>
                                    <label class="channel_name ellipsis">群组5</label>
                                </li>
                                <li>
                                    <label class="channel_headpic"><img style="width:50px;height:50px;" src="img/frontend/tem_material/sc91.jpg"/></label>
                                    <label class="channel_name ellipsis">群组5</label>
                                </li>

                            </ul>
                        </div>
                        <div class="channel_action_wrap">
                            <ul id="userChannelTab" class="nav nav-tabs">
                                <li class="active">
                                    <i style="margin-top: 5px;" class="channel_tab_side col_side_icon icon_channel_file"></i>
                                    <img class="channel_header" src="img/frontend/tem_material/sc91.jpg"/>
                                    <a href="#chanel_files" data-toggle="tab">群文件</a>
                                </li>
                                <li>
                                    <i style="margin-top: 3px;" class="channel_tab_side col_side_icon icon_channel_members"></i>
                                    <img class="channel_header" src="img/frontend/tem_material/sc91.jpg"/>
                                    <a href="#channel_members" data-toggle="tab">群人员</a>
                                </li>
                                <li>
                                    <i class="channel_tab_side col_side_icon icon_channel_chat"></i>
                                    <img class="channel_header" src="img/frontend/tem_material/sc91.jpg"/>
                                    <a href="#chat" data-toggle="tab">聊天</a>
                                </li>
                            </ul>
                            <div class="tab-content channel_tab_container">
                                <div class="tab-pane fade in active channel_files_wrap" id="chanel_files">
                                    <div class="item">
                                        <div class="item_detail">
                                            <div class="item_detail_img_wrap">
                                                <img class="hover_controller" src="img/frontend/tem_material/sc8.jpg?v=1">
                                            </div>
                                            <div class="item_info_wrap">
                                                <div class="item_info_detail">
                                                    <label class="item_name ellipsis">创意海报创意海报</label>
                                                    <label class="author_name ellipsis"><span>BY</span><sapn>周吴郑王</sapn></label>
                                                </div>
                                                <div class="download_info">
                                                    <div class="simple_btn btn_simple_download blue_font">
                                                        <i class="tool_icon icon_blue_download"></i>
                                                        <label class="tool_btn_desc">下载:56</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="item">
                                        <div class="item_detail">
                                            <div class="item_detail_img_wrap">
                                                <img class="hover_controller" src="img/frontend/tem_material/sc8.jpg?v=1">
                                            </div>
                                            <div class="item_info_wrap">
                                                <div class="item_info_detail">
                                                    <label class="item_name ellipsis">创意海报创意海报</label>
                                                    <label class="author_name ellipsis"><span>BY</span><sapn>周吴郑王</sapn></label>
                                                </div>
                                                <div class="download_info">
                                                    <div class="simple_btn btn_simple_download blue_font">
                                                        <i class="tool_icon icon_blue_download"></i>
                                                        <label class="tool_btn_desc">下载:56</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="item">
                                        <div class="item_detail">
                                            <div class="item_detail_img_wrap">
                                                <img class="hover_controller" src="img/frontend/tem_material/sc8.jpg?v=1">
                                            </div>
                                            <div class="item_info_wrap">
                                                <div class="item_info_detail">
                                                    <label class="item_name ellipsis">创意海报创意海报</label>
                                                    <label class="author_name ellipsis"><span>BY</span><sapn>周吴郑王</sapn></label>
                                                </div>
                                                <div class="download_info">
                                                    <div class="simple_btn btn_simple_download blue_font">
                                                        <i class="tool_icon icon_blue_download"></i>
                                                        <label class="tool_btn_desc">下载:56</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="item">
                                        <div class="item_detail">
                                            <div class="item_detail_img_wrap">
                                                <img class="hover_controller" src="/img/frontend/tem_material/sc8.jpg?v=1">
                                            </div>
                                            <div class="item_info_wrap">
                                                <div class="item_info_detail">
                                                    <label class="item_name ellipsis">创意海报创意海报</label>
                                                    <label class="author_name ellipsis"><span>BY</span><sapn>周吴郑王</sapn></label>
                                                </div>
                                                <div class="download_info">
                                                    <div class="simple_btn btn_simple_download blue_font">
                                                        <i class="tool_icon icon_blue_download"></i>
                                                        <label class="tool_btn_desc">下载:56</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="item">
                                        <div class="item_detail">
                                            <div class="item_detail_img_wrap">
                                                <img class="hover_controller" src="/img/frontend/tem_material/sc8.jpg?v=1">
                                            </div>
                                            <div class="item_info_wrap">
                                                <div class="item_info_detail">
                                                    <label class="item_name ellipsis">创意海报创意海报</label>
                                                    <label class="author_name ellipsis"><span>BY</span><sapn>周吴郑王</sapn></label>
                                                </div>
                                                <div class="download_info">
                                                    <div class="simple_btn btn_simple_download blue_font">
                                                        <i class="tool_icon icon_blue_download"></i>
                                                        <label class="tool_btn_desc">下载:56</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="item">
                                        <div class="item_detail">
                                            <div class="item_detail_img_wrap">
                                                <img class="hover_controller" src="/img/frontend/tem_material/sc8.jpg?v=1">
                                            </div>
                                            <div class="item_info_wrap">
                                                <div class="item_info_detail">
                                                    <label class="item_name ellipsis">创意海报创意海报</label>
                                                    <label class="author_name ellipsis"><span>BY</span><sapn>周吴郑王</sapn></label>
                                                </div>
                                                <div class="download_info">
                                                    <div class="simple_btn btn_simple_download blue_font">
                                                        <i class="tool_icon icon_blue_download"></i>
                                                        <label class="tool_btn_desc">下载:56</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="item">
                                        <div class="item_detail">
                                            <div class="item_detail_img_wrap">
                                                <img class="hover_controller" src="/img/frontend/tem_material/sc8.jpg?v=1">
                                            </div>
                                            <div class="item_info_wrap">
                                                <div class="item_info_detail">
                                                    <label class="item_name ellipsis">创意海报创意海报</label>
                                                    <label class="author_name ellipsis"><span>BY</span><sapn>周吴郑王</sapn></label>
                                                </div>
                                                <div class="download_info">
                                                    <div class="simple_btn btn_simple_download blue_font">
                                                        <i class="tool_icon icon_blue_download"></i>
                                                        <label class="tool_btn_desc">下载:56</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="item">
                                        <div class="item_detail">
                                            <div class="item_detail_img_wrap">
                                                <img class="hover_controller" src="/img/frontend/tem_material/sc8.jpg?v=1">
                                            </div>
                                            <div class="item_info_wrap">
                                                <div class="item_info_detail">
                                                    <label class="item_name ellipsis">创意海报创意海报</label>
                                                    <label class="author_name ellipsis"><span>BY</span><sapn>周吴郑王</sapn></label>
                                                </div>
                                                <div class="download_info">
                                                    <div class="simple_btn btn_simple_download blue_font">
                                                        <i class="tool_icon icon_blue_download"></i>
                                                        <label class="tool_btn_desc">下载:56</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="item">
                                        <div class="item_detail">
                                            <div class="item_detail_img_wrap">
                                                <img class="hover_controller" src="/img/frontend/tem_material/sc8.jpg?v=1">
                                            </div>
                                            <div class="item_info_wrap">
                                                <div class="item_info_detail">
                                                    <label class="item_name ellipsis">创意海报创意海报</label>
                                                    <label class="author_name ellipsis"><span>BY</span><sapn>周吴郑王</sapn></label>
                                                </div>
                                                <div class="download_info">
                                                    <div class="simple_btn btn_simple_download blue_font">
                                                        <i class="tool_icon icon_blue_download"></i>
                                                        <label class="tool_btn_desc">下载:56</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="channel_members">
                                    <ul class="channel_user_list">
                                        {% set x=[1,1,2,1,2,1,1,2,1,1,1,1,1,1,1,1,1,1,1,1] %}
                                        {% for a in x %}
                                            {% if(a == '1') %}
                                                <li><img class="user_list_header" src="img/frontend/tem_material/sc8.jpg?v=1"><label class="user_list_name ellipsis">成员1</label></li>
                                                {% else %}
                                                    <li><img class="user_list_header" src="img/frontend/tem_material/sc8.jpg?v=1"><label class="user_list_name ellipsis">成员2</label></li>
                                            {% endif %}

                                        {% endfor %}
                                    </ul>
                                </div>
                                <div class="tab-pane fade" id="chat" >
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{% include "template/bootstrap-modals.volt" %}

{{ javascript_include("/3rdpart/jquery/jquery.min.js") }}
{{ javascript_include("/3rdpart/bootstrap/js/bootstrap.min.js") }}
{{ javascript_include("/3rdpart/bootstrap/js/bootstrap-modal.js") }}
{{ javascript_include("/js/frontend/cityselect/jquery.cityselect.js") }}

{{ javascript_include("/js/frontend/home.js") }}
{{ javascript_include("/js/frontend/userInfo.js") }}
<script type="text/javascript">
    $(function(){
        //处理模板
        $('.container_header .logo_wrap').hide();
        $('.container_header .welcome_wrap').hide();
        $('.search_wrap .search_header').show();
    });
</script>
</body>
</html>