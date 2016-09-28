$(function () {
    var $form = $('form[name="register-form"]');
    var $mobile = $('input[name="phone"]'),
        SysSecond=60;
    //发送验证码之后
    var codeResult=function (serverData) {
        if (serverData.code === 0) {
            $('.btn-send').addClass('readonly');
            $('.btn-send').val('60秒');
            InterValObj=window.setInterval(SetCodeTime,1000);
        } else {

        }

    };
    function SetCodeTime(){
        if(SysSecond>0){
            SysSecond=SysSecond-1;
            $('.btn-send').val(SysSecond+'秒');

        }else{
            $('.btn-send').removeClass('readonly');
            $('.btn-send').val('发送验证码');
            window.clearInterval(InterValObj);
        }
    }
    //发送验证码
    $('.btn-send').click(function(){
        if(!$(this).hasClass('disabled')){
            if (!$mobile.val()) {
                showTip($mobile,'show','请输入手机号');
                return false;
            }else if ((($mobile.val().length!=11) || !/^1[3|4|5|7|8][0-9]\d{4,8}$/.test($mobile.val())) ) {
                showTip($mobile,'show','请输入正确的手机号');
                return false;
            }
            $('.field-msg').remove();
            $.post('/api/signup/authCode', {phone:$mobile.val(),code_type:'SIGNUP'}, codeResult, 'json');
        }
    })
    var showTip=function($this,type,msg){
        if(type=='show'){
            if($this.parents(".field").find('.field-msg').length<=0){
                $('.field-msg').remove();
                $this.parents(".field").append('<div class="field-msg msg-warning">'+msg+'</div>');

            }else{
                $this.parents(".field").find('.field-msg').html(msg);

            }
        }

    };
    //注册提交之后
    var registerResult = function (serverData) {
        if (serverData.code === 0) {
            window.location.href = '/login/registerNext';
        } else {
            $form.find(':submit').prop('disabled',false);
            showTip($("input[name='password_repeat']"),'show',serverData.msg);

        }

    };
    //msg-success 成功
    //msg-warning 提醒
    /*$("input[type=text],input[type=password]").change(function () {
     var msg = '提示信息';
     $(".form-wrap .field-msg").remove();
     $(this).parents(".field").append('<div class="field-msg msg-success">' + msg + '</div>');
     $(this).parents(".field").append('<div class="field-msg msg-warning">' + msg + '</div>');
     });*/
    $form.submit(function () {
        var  $code = $('input[name="code"]'),$pass = $('input[name="password"]'), $pass1 = $('input[name="password_repeat"]');
        if (!$mobile.val()) {
            showTip($mobile,'show','请输入手机号');
            return false;
        }else if ((($mobile.val().length!=11) || !/^1[3|4|5|7|8][0-9]\d{4,8}$/.test($mobile.val())) ) {
            showTip($mobile,'show','请输入正确的手机号');
            return false;
        }else if (!$code.val()) {
            showTip($code,'show','请输入验证码');
            return false;
        }else if (!$pass.val()) {
            showTip($pass,'show','请输入密码');
            return false;
        }else if (!$pass1.val()) {
            showTip($pass1,'show','请再次输入密码');
            return false;
        }else if ($pass.val()!=$pass1.val()) {
            showTip($pass1,'show','两次密码输入不一致');
            return false;
        }
        $form.find(':submit').prop('disabled',true);

        $.post('/api/signup/byPhone', $form.serialize(), registerResult, 'json');

        return false;
    })


})

