$(function () {
    var $form = $('form[name="register-form"]');
    var pageType = $form.find('input[name="type"]').val();
    var $username = $('input[name="username"]'), $pass = $('input[name="password"]'), $pass1 = $('input[name="password_repeat"]');

    var checkUsername = function(username){
        $.post('/api/signup/checkUsername',{username:username}, function(data){
            if(data.code == 0){
                showTip($username,'hide','用户名可用');
                $form.find('input[type="submit"]').attr("disabled",false);
            }
            else if(data.code == '1'){
                showTip($username,'show',data.msg);
                $form.find('input[type="submit"]').attr("disabled","disabled");
            }
        }, 'json');
    }
    var showTip=function($this,type,msg){
        if(type=='show'){
            if($this.parents(".field").find('.field-msg').length<=0){
                $this.parents(".field").find('.field-msg').remove();
                $this.parents(".field").append('<div class="field-msg msg-warning">'+msg+'</div>');

            }else{
                $this.parents(".field").find('.field-msg').html(msg);

            }
        }
        else{
            $this.parents(".field").find('.field-msg').remove();
        }

    };
    //注册提交之后
    var registerResult = function (serverData) {
        if (serverData.code === 0) {
            if(pageType=='forget'){
                window.location.href = '/login/login';return false;

            }

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
        if (!$username.val()) {
            showTip($username,'show','请输入用户名');
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
        var url='/api/signup/byUsername';
        $.post(url, $form.serialize(), registerResult, 'json');

        return false;
    });
    $username.blur(function(){
        checkUsername($username.val());
    });
    $pass1.blur(function(){
        if ($pass.val()!=$pass1.val()) {
            showTip($pass1,'show','两次密码输入不一致');
        }
        else{
            showTip($pass1,'hide','两次密码输入正确');
        }
    })
    $("input[type=text],input[type=password],input[type=tel]").focus(function(){
            $(this).parents(".field").addClass("field-focus");
        }
    );
    $("input[type=text],input[type=password],input[type=tel]").blur(function(){
            $(this).parents(".field").removeClass("field-focus");
        }
    );


})

