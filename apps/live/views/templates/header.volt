<div class="logo_wrap">
    <a class="platform_logo"><img src="/img/frontend/home/logo_lx.png?v=1"/></a>
</div>
<div class="welcome_wrap"><label class="hotline_title">咨询热线 :</label><label class="hotline_phone">010-59301232</label></div>
<div class="welcome_wrap hotline_title">欢迎来到独角兽教育平台!</div>
<div class="search_wrap">
    <img class="search_header"  src="/img/frontend/home/logo_lx.png?v=2"/>
    <input class="search_input" placeholder="标题+文件格式"/>
    <a class="btn_search"></a>
	{% if !bSignedIn %}
	{#{% if bSignedIn %} #}
    <div class="auth_wra    p"><a class="btn_login">登录</a><span></span><a class="btn_register">注册</a></div>
	{% else %}
    <div class="user_info_wrap">
        <i class="user_headpic"><img style="width:60px;height:60px;" src="/img/frontend/tem_material/sc91.jpg"/></i>
        <label class="user_name_wrap" style="margin-left:20px;">
            <span class="user_name" targetid ="{{ userInfo.uid }}" >{{ userInfo.nick_name }}</span>
            <span id="token" style="display:none;" token="{{ user.user_token }}" ></span>
            <i class="spread_btn"></i>
        </label>
    </div>
	{#<div class="auth_wrap"><a href="/user">{{ userInfo.realname}}</a></div>#}
	{% endif %}
    {#<div class="user_info_wrap">
        <i class="user_headpic"><img style="width:60px;height:60px;" src="img/frontend/tem_material/sc91.jpg"/></i>
        <label class="user_name_wrap" style="margin-left:20px;">
            <span class="user_name" targetid ="{{ userInfo.uid }}" >{{ userInfo.nick_name }}</span>
            <span id="token" style="display:none;" token="{{ userInfo.user_token }}" ></span>
            <i class="spread_btn"></i>
        </label>
    </div>#}
</div>
<div class="link_wrap">
    <ul class="link_ul">
        <li class="home_page focus">
            <img class="label_no_focus" src="/img/frontend/home/label_home.png" />
            <img class="label_focus" src="/img/frontend/home/label_home_focus.png" />
        </li>
        <li class="publicResource_page no_focus">
            <img class="label_no_focus" src="/img/frontend/home/label_resource.png" />
            <img class="label_focus" src="/img/frontend/home/label_resource_focus.png"/>
        </li>
        <li class="file_page no_focus">
            <img class="label_no_focus" src="/img/frontend/home/label_file.png" />
            <img class="label_focus" src="/img/frontend/home/label_file_focus.png"/>
        </li>
        <li class="class_page no_focus">
            <img class="label_no_focus" src="/img/frontend/home/label_class.png" />
            <img class="label_focus" src="/img/frontend/home/label_class_focus.png"/>
        </li>
        <li class="im_page no_focus">
            <img class="label_no_focus" src="/img/frontend/home/label_im.png" />
            <img class="label_focus" src="/img/frontend/home/label_im_focus.png"/>
        </li>
    </ul>
</div>