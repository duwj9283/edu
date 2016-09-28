<ul class="col_side_wrap body_side">
    {#用户信息#}
    <li id="author-info" class="author_wrap">
        <i class="user_headpic" style="float:left">
            <img id="filePicker-img"
                 {% if userInfo.headpic %} src="/frontend/source/getFrontImageThumb/header/{{ userInfo.uid }}/120/120"
                 {% else %} src="/img/frontend/camtasiastudio/default-avatar-small.png"  {% endif %} width="60"  height="60"  />
        </i>
        <label class="user_name ellipsis">{{ userInfo.nick_name }}</label>
    </li>
    {#我的文件二级菜单#}
    <li class="col_side_2_title my_file"  data-event="toggle">
        <i class="col_side_icon icon_all"></i>
        <label class="col_side_desc gly">我的文件</label>
    </li>
    <li class="col_side_li_2_wrap my_file_wrap">
        <ul class="col_side_ul_2">
            <li class="col_side_list_wrap btn_icon_all" data-type="0">
                <a href="/file">
                    <i class="col_side_icon icon_all"></i>
                    <label class="col_side_desc">全部文件</label>
                </a>
            </li>
            <li class="col_side_list_wrap btn_icon_collect">
                <a href="/file/getcollect">
                    <i class="col_side_icon icon_collect"></i>
                    <label class="col_side_desc">收藏</label>
                </a>
            </li>
            <li class="col_side_list_wrap btn_icon_video" data-type="2">
                <a href="/file?type=2">
                    <i class="col_side_icon icon_video"></i>
                    <label class="col_side_desc">视频</label>
                </a>
            </li>
            <li class="col_side_list_wrap btn_icon_pic" data-type="3">
                <a href="/file?type=3">
                    <i class="col_side_icon icon_pic"></i>
                    <label class="col_side_desc">图片</label>
                </a>
            </li>
            <li class="col_side_list_wrap btn_icon_audio" data-type="4">
                <a href="/file?type=4">
                    <i class="col_side_icon icon_audio"></i>
                    <label class="col_side_desc">音频</label>
                </a>
            </li>
            <li class="col_side_list_wrap btn_icon_doc" data-type="5">
                <a href="/file?type=5">
                    <i class="col_side_icon5 icon_doc"></i>
                    <label class="col_side_desc">文档</label>
                </a>
            </li>
            <li class="col_side_list_wrap btn_icon_other" data-type="6">
                <a href="/file?type=6">
                    <i class="col_side_icon5 icon_other"></i>
                    <label class="col_side_desc">其他</label>
                </a>
            </li>
            <li class="col_side_list_wrap btn_icon_video">
                <a href="/file/getlive">
                    <i class="col_side_icon icon_live"></i>
                    <label class="col_side_desc">录播视频</label>
                </a>
            </li>
            <li class="col_side_list_wrap btn_icon_visible">
                <a href="/file/getvisible">
                    <i class="col_side_icon5 icon_eye"></i>
                    <label class="col_side_desc">空间可见</label>
                </a>
            </li>
        </ul>
    </li>
    {% if userInfo.role_id == 1 %}
    <li class="col_side_2_title my_mico">
        <a href="/course/study">
            <i class="col_side_icon3 icon_my_course"></i>
            <label class="col_side_desc">我的课程</label>
        </a>

    </li>
    {% else %}
    <li class="col_side_2_title my_course"  data-event="toggle">
        <i class="col_side_icon3 icon_my_course"></i>
        <label class="col_side_desc gly">我的课程</label>
    </li>
    <li class="col_side_li_2_wrap my_course_wrap" style="display:none;">
        <ul class="col_side_ul_2">
            <li class="col_side_list_wrap btn_manege_course">
                <a href="/course/manage">
                    <i class="col_side_icon4 icon_manege_course"></i>
                    <label class="col_side_desc">课程管理</label>
                </a>
            </li>
            <li class="col_side_list_wrap btn_exam">
                <a href="/course/exam">
                    <i class="col_side_icon4 icon_exam"></i>
                    <label class="col_side_desc">我的题库</label>
                </a>
            </li>
            <li class="col_side_list_wrap btn_study_course">
                <a href="/course/study">
                    <i class="col_side_icon4 icon_study_course"></i>
                    <label class="col_side_desc">课程学习</label>
                </a>
            </li>
        </ul>
    </li>
    {% endif%}
    {#课程管理二级菜单#}
    {% if userInfo.role_id == 1 %}
    <li class="col_side_list_wrap btn_my_live">
        <a href="/live/index/list">
            <i class="col_side_icon2 icon_my_live"></i>
            <label class="col_side_desc">我的直播</label>
        </a>
    </li>
    {% else %}
    <li class="col_side_2_title btn_my_live" data-event="toggle">
        <i class="col_side_icon2 icon_my_live"></i>
        <label class="col_side_desc gly">我的直播</label>
    </li>
    <li class="col_side_li_2_wrap my_mico_wrap">
        <ul class="col_side_ul_2">
            <li class="col_side_list_wrap btn_mico ">
                <a href="/live/index/manage">
                    <i class="col_side_icon3 icon_my_mico"></i>
                    <label class="col_side_desc">直播管理</label>
                </a>
            </li>
            <li class="col_side_list_wrap btn_mico_study">
                <a href="/live/index/list">
                    <i class="col_side_icon3 icon_mico_study"></i>
                    <label class="col_side_desc">我的直播</label>
                </a>
            </li>
        </ul>
    </li>
    {% endif%}

    {% if userInfo.role_id == 1 %}
    <li class="col_side_2_title my_mico">
        <a href="/mico/study">
            <i class="col_side_icon2 icon_mico"></i>
            <label class="col_side_desc">我的微课</label>
        </a>

    </li>
    {% else %}

    <li class="col_side_2_title my_mico"  data-event="toggle">
        <i class="col_side_icon2 icon_mico"></i>
        <label class="col_side_desc gly">我的微课</label>
    </li>
    <li class="col_side_li_2_wrap my_mico_wrap">
        <ul class="col_side_ul_2">
            <li class="col_side_list_wrap btn_mico ">
                <a href="/mico">
                    <i class="col_side_icon3 icon_my_mico"></i>
                    <label class="col_side_desc">微课管理</label>
                </a>
            </li>
            <li class="col_side_list_wrap btn_mico_study">
                <a href="/mico/study">
                    <i class="col_side_icon3 icon_mico_study"></i>
                    <label class="col_side_desc">微课学习</label>
                </a>
            </li>
            <li class="col_side_list_wrap btn_mico_video">
                <a href="/mico/video">
                    <i class="col_side_icon3  icon_mico_video"></i>
                    <label class="col_side_desc">微课件</label>
                </a>
            </li>
        </ul>
    </li>
    {% endif%}
    {#其他#}
    <li class="col_side_list_wrap btn_person_info" style="display:none;margin-top: 10px;">
        <a href="/user">
            <i class="col_side_icon2 icon_person_info"></i>
            <label class="col_side_desc">个人资料</label>
        </a>
    </li>
    {% if userInfo.role_id == 2 %}
    <li class="col_side_list_wrap btn_person">
        <a href="/user/news">
            <i class="col_side_icon2 icon_person_zone"></i>
            <label class="col_side_desc">我的文章</label>
        </a>
    </li>
    {% endif %}
    <li class="col_side_list_wrap btn_icon_share" style="margin-top: 10px;">
        <a href="/user/share">
            <i class="col_side_icon icon_share"></i>
            <label class="col_side_desc">我的分享</label>
        </a>
    </li>
    <li class="col_side_list_wrap btn_icon_message" style="margin-top: 10px;">
        <a href="/user/message">
            <i class="col_side_icon icon_message"></i>
            <label class="col_side_desc">我的消息</label>
        </a>
    </li>
    <li class="col_side_list_wrap btn_icon_follow" style="margin-top: 10px;">
        <a href="/user/follow">
            <i class="col_side_icon icon_follow"></i>
            <label class="col_side_desc">我的关注</label>
        </a>
    </li>
    <li class="col_side_list_wrap btn_icon_trash" style="margin-top: 10px;">
        <a href="/file/trash">
            <i class="col_side_icon icon_trash"></i>
            <label class="col_side_desc">回收站</label>
        </a>
    </li>
    {#设置#}
    <li class="col_side_2_title set_up"  data-event="toggle">
        <i class="col_side_icon5 icon_setup"></i>
        <label class="col_side_desc gly">设置</label>
    </li>
    {#设置-二级菜单#}
    <li class="col_side_li_2_wrap set_up_wrap" style="display:none;">
        <ul class="col_side_ul_2">
            <li class="col_side_list_wrap  btn_modify_psw">
                <a href="/user/modifypsw">
                    <i class="col_side_icon5 icon_psw"></i>
                    <label class="col_side_desc">修改密码</label>
                </a>
            </li>
            <li class="col_side_list_wrap btn_logout ">
                <a href="/login/signout">
                    <i class="col_side_icon5 icon_logout"></i>
                    <label class="col_side_desc">退出</label>
                </a>
            </li>
        </ul>
    </li>
</ul>