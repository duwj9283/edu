/**
 * Created by 20150112 on 2016/3/7.
 */
$(function(){
    /*初始化滑块验证*/
    var slider_valid = 0;
    /**
     * 登录按钮
     * */
    $('.btn_login').on('click',function(){
        $('.auth_container').show();
        $('body').addClass('forbid_scroll');
        onLoadLogin();
        init_slider();
    });
    /**
     * 注册按钮
     * */
    $('.btn_register').on('click',function(){
        $('.auth_container').show();
        $('body').addClass('forbid_scroll');
        onLoadRegisiter();
        init_slider();
    });

    /*弹出层相关*/
    $('.auth_container .overlay').on('click',function(){
        $('.auth_container').hide();
        $('body').removeClass('forbid_scroll');
    });
    $('.auth_container .auth_lay_out').on('click',function(){
        $('.auth_container .overlay').trigger('click');
    });

    $('.auth_layer_header a').on('click',function(){
        $('.auth_layer_header a').removeClass('active');
        $(this).addClass('active');

        if($(this).hasClass('auth_login_a')){
            $('.auth_lay_body .auth_register_wrap').removeClass('active');
            $('.auth_lay_body .auth_login_wrap').addClass('active');
            onLoadLogin();
            init_slider();
        }else if($(this).hasClass('auth_register_a')){
            $('.auth_lay_body .auth_login_wrap').removeClass('active');
            $('.auth_lay_body .auth_register_wrap').addClass('active');
            onLoadRegisiter();
            init_slider();
        }
    });

    function onLoadLogin(){
        $('.auth_layer_header .auth_register_a').removeClass('active');
        $('.auth_layer_header .auth_login_a').addClass('active');
        $('.auth_lay_body').empty();
        var htmlStr = '<div class="auth_login_wrap active"><div class="auth_control_group" style="position:relative;"><input class="auth_username" placeholder="请填写用户名,4到18位"/><div class="auth_error_wrap"><div class="arrow"></div><div class="error_desc">请填写此字段</div></div></div><div class="auth_control_group" style="position:relative;"><input class="auth_password" style="border-top: 0;" type="password" placeholder="请填写密码，6到18位"/><div class="auth_error_wrap"><div class="arrow"></div><div class="error_desc">请填写此字段</div></div></div><div class="simple_slider_wrap"><div id="simple-slider" class="scale dragdealer"><div class="scale-in" style="width: 31%;"><span class="scale-bar handle" style="perspective: 1000px; backface-visibility: hidden; transform: translateX(115px);"></span></div><div class="scale-text"><span class="scale-textin">请按住滑块，拖动到最右边</span></div></div></div><div class="post_error"></div><button class="auth_login_btn">登录</button></div>';
        $('.auth_lay_body').html(htmlStr);
        /*登录按钮*/
        $('.auth_login_btn').on('click',function(){
            if(slider_valid){
                var  userName = $(".auth_login_wrap .auth_username").val(),passWord = $('.auth_login_wrap .auth_password').val();
                var postData={'url':'/api/login/byUsername','username':userName,'password':passWord};
                remote_interface_by_api(postData,function(serverData){
                    console.log(serverData);
                    if(serverData.code ===0){
                        window.location.href= '/user';
                    }else{
                        $('.auth_login_wrap .post_error').text(serverData.msg);
                        $('.auth_login_wrap .post_error').show();
                    }

                    $(".auth_login_wrap input").prop("readonly", false);
                    init_slider();
                });
            }else{
                $('.auth_login_wrap .post_error').text('请先按住滑块，移动到最右端');
                $('.auth_login_wrap .post_error').show();
            }

        });
    }

    function onLoadRegisiter(){
        $('.auth_layer_header .auth_login_a').removeClass('active');
        $('.auth_layer_header .auth_register_a').addClass('active');
        $('.auth_lay_body').empty();
        var htmlStr = '<div class="auth_register_wrap"><div class="auth_control_group" style="position:relative;"><input class="auth_reg_username" placeholder="请输入手机号或邮箱"/><div class="auth_error_wrap"><div class="arrow"></div><div class="error_desc">请填写此字段</div></div></div><div class="simple_slider_wrap"><div id="simple-slider" class="scale dragdealer"><div class="scale-in" style="width: 31%;"><span class="scale-bar handle" style="perspective: 1000px; backface-visibility: hidden; transform: translateX(115px);"></span></div><div class="scale-text"><span class="scale-textin">请按住滑块，拖动到最右边</span></div></div></div><div class="auth_control_group auth_yzm_wrap" style="position:relative;margin-top:16px;display:none;"><input class="auth_reg_yzm" placeholder="请输入发送的密码"/><div class="auth_error_wrap"><div class="arrow"></div><div class="error_desc">请填写此字段</div></div></div><div class="post_error"></div><button class="auth_reg_btn">注册</button></div>';
        $('.auth_lay_body').html(htmlStr);
        $('.auth_reg_btn').on('click',function(){
            var userName = $.trim($(".auth_register_wrap .auth_reg_username").val());
            var passWord = $.trim($('.auth_register_wrap .auth_reg_yzm').val());
            if($('.auth_reg_btn').data('regType') == 'byPhone'){
                var postData={url:'/api/signup/byPhone','phone':userName,'password':passWord};
                remote_interface_by_api(postData, function (serverData) {
                    if(serverData.code === 0){
                        $('.post_error').text('注册成功，快去登录吧');
                        $('.post_error').show();
                    }else{
                        $('.post_error').text(serverData.msg);
                        $('.post_error').show();
                    }
                });
            }else if($('.auth_reg_btn').data('regType') == 'byEmail'){

            }else{
                $('.post_error').text('请先验证之后再注册');
                $('.post_error').show();
            }

        });
    }

    /*滑动验证*/
    function init_slider(){
        slider_valid = 0;
        $('.auth_lay_body input').prop("readonly", false);
        new Dragdealer('simple-slider', {
            animationCallback: function(x, y) {
                var percent = Math.round(x * 100);
                $(".scale-in").css("width", percent+"%");
            },
            dragStopCallback: function(x, y){
                var that = this;
                if($('.auth_lay_body .auth_login_wrap').length>0){
                    /*登录流程*/
                    var userName = $(".auth_login_wrap .auth_username").val(),passWord = $('.auth_login_wrap .auth_password').val();
                    if(!userName || !passWord || userName.length < 4 || userName.length >18 || passWord.length <6 || passWord.length >18){
                        if(userName.length < 4 || userName.length> 18){
                            $('.auth_login_wrap .auth_username').val('');
                            $('.auth_login_wrap .auth_username').focus();
                        }else if(passWord.length < 6 || passWord.length > 18){
                            $('.auth_login_wrap .auth_password').val('');
                            $('.auth_login_wrap .auth_password').focus();
                        }
                        this.setValue(0, 0);
                        return false;
                    }
                    slider_valid = Math.floor(x);
                    if (slider_valid) {
                        that.disable();
                        $(".auth_login_wrap input").prop("readonly", true);
                        $(".scale-textin").addClass("textin-none").text("验证通过，请点击登录按钮");
                        $('.post_error').hide();
                    }
                }else if($('.auth_lay_body .auth_register_wrap').length >0){
                    /*注册流程*/
                    var userName = $(".auth_register_wrap .auth_reg_username").val();
                    if(userName.isMobile()){
                        /*手机注册*/
                        //alert("手机注册");
                        var data = {'url':'/api/signup/authCode','code_type':'SIGNUP','phone':userName};
                        remote_interface_by_api(data,function(serverData){
                            if(serverData.code !== 0){
                                $('.post_error').text(serverData.msg);
                                $('.post_error').show();
                                that.setValue(0,0);
                                return false;
                            }else{
                                slider_valid = Math.floor(x);
                                if (slider_valid) {
                                    that.disable();
                                    $(".auth_register_wrap .auth_reg_username").prop("readonly", true);
                                    $(".scale-textin").addClass("textin-none").text("发送成功，请注意查看");
                                    $('.post_error').hide();
                                    $('.auth_reg_btn').data('regType','byPhone');
                                    $('.auth_register_wrap .auth_yzm_wrap').fadeIn();

                                }
                            }
                        });
                    }else{
                        if(userName.isEmail()){
                            /*邮箱注册*/
                            $('.auth_reg_btn').data('regType','byEmail');
                            alert("邮箱注册");
                        }else{
                            $('.post_error').text('请输入正确的手机号或者邮箱');
                            $('.post_error').show();
                            this.setValue(0, 0);
                            return false;
                        }
                    }

                }

            }
        });
    }
    /*调用自己的api*/

    function remote_interface_by_api(data,cb){
        $.ajax({
            'url': data.url,
            'type':'post',
            'data':data,
            'dataType':'json'
        }).done(cb);
    }

});
