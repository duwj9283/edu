<div class="auth_container">
    <div class="overlay"></div>
    <div class="auth_lay_out">✕</div>
    <div class="auth_lay_content">
        <div class="auth_layer_header">
            <a class="auth_login_a">登录</a>
            <a class="auth_register_a">注册</a>
        </div>
        <div class="auth_lay_body">
            <div class="auth_login_wrap active">
                <div class="auth_control_group" style="position:relative;">
                    <input class="auth_username" placeholder="请填写用户名,4到18位"/>
                    <div class="auth_error_wrap">
                        <div class="arrow"></div>
                        <div class="error_desc">请填写此字段</div>
                    </div>
                </div>
                <div class="auth_control_group" style="position:relative;">
                    <input class="auth_password" style="border-top: 0;" type="password" placeholder="请填写密码，6到18位"/>
                    <div class="auth_error_wrap">
                        <div class="arrow"></div>
                        <div class="error_desc">请填写此字段</div>
                    </div>
                </div>
                {#滑动解锁#}
                <div class="simple_slider_wrap">
                    <div id="simple-slider" class="scale dragdealer">
                        <div class="scale-in" style="width: 31%;">
                            <span class="scale-bar handle" style="perspective: 1000px; backface-visibility: hidden; transform: translateX(115px);"></span>
                        </div>
                        <div class="scale-text">
                            <span class="scale-textin">请按住滑块，拖动到最右边</span>
                        </div>
                    </div>
                </div>
                {#滑动结束#}
                <div class="post_error"></div>
                <button class="auth_login_btn">登录</button>
            </div>
            {#<div class="auth_register_wrap">
                <div class="auth_control_group" style="position:relative;">
                    <input class="auth_reg_username" placeholder="请输入手机号或邮箱"/>
                    <div class="auth_error_wrap">
                        <div class="arrow"></div>
                        <div class="error_desc">请填写此字段</div>
                    </div>
                </div>
                <div class="simple_slider_wrap">
                    <div id="simple-slider" class="scale dragdealer">
                        <div class="scale-in" style="width: 31%;">
                            <span class="scale-bar handle" style="perspective: 1000px; backface-visibility: hidden; transform: translateX(115px);"></span>
                        </div>
                        <div class="scale-text">
                            <span class="scale-textin">请按住滑块，拖动到最右边</span>
                        </div>
                    </div>
                </div>
                <div class="auth_control_group auth_yzm_wrap" style="position:relative;margin-top:16px;display:none;">
                    <input class="auth_reg_yzm" placeholder="请输入验证码"/>
                    <div class="auth_error_wrap">
                        <div class="arrow"></div>
                        <div class="error_desc">请填写此字段</div>
                    </div>
                </div>
                <div class="post_error"></div>
                <button class="auth_reg_btn">注册</button>
            </div>#}
        </div>
    </div>
</div>
