var USER = {};

/*------------检查登陆用户名------------*/
USER.checkUserName = function(username,username_alert){
    if (!username.val()) {
        username.focus();
        USER.showLoginMsg('用户名不能为空',1,username_alert);
        USER.refreshCode();
        return false;
    }
    var filter = /^[0-9a-zA-Z]{6,}$/i;
    var returnval = filter.test(username.val());
    if (!returnval) {
        username.focus();
        USER.showLoginMsg('用户名最少6位，数字或字母',1,username_alert);
        USER.refreshCode();
        return false;
    }
    return true;
};

/*------------检查登陆密码------------*/
USER.checkPassword = function(password,password_alert){
    if (!password.val()) {
        password.focus();
        USER.showLoginMsg('密码不能为空',1,password_alert);
        USER.refreshCode();
        return false;
    }
    var filter = /^[0-9a-zA-Z~!@#$%^&*()_+]{6,}$/i;
    var returnval = filter.test(password.val());
    if (!returnval) {
        password.focus();
        USER.showLoginMsg('密码长度最少6位，数字、字母、特殊字符~!@#$%^&*()_+',1,password_alert);
        USER.refreshCode();
        return false;
    }
    return true;
};

/*------------检查验证码------------*/
USER.checkAuthCode = function(authcode,authcode_alert){
    if (!authcode.val()) {
        authcode.focus();
        USER.showLoginMsg('请按住滑块，拖动到最右边',1,authcode_alert);
        USER.refreshCode();
        return false;
    }
    if(authcode.val()<6){
        authcode.focus();
        USER.showLoginMsg('请将滑块拖动到最右边',1,authcode_alert);
        USER.refreshCode();
        return false;
    }
    var bool = true;
    var _datas = {authcode:authcode.val()};
    var _urls = '/backend/ajax/checkCode';
    $.ajax({
        type:'GET',
        url: _urls,
        async: false,
        dataType:'json',
        data:_datas,
        success:function(data){
            if(data.status)
            {
                bool = false;
            }else{
                USER.showLoginMsg(data.msg,1,authcode_alert);
                USER.refreshCode();
            }
        },
        error:function(xhr,status,error){
            console.log(error);
        }
    });
    if (bool) {
        return false;
    }
    return true;
};

/*------------显示提示------------*/
USER.ajaxLogin = function(username,password,authcode,backurl){
    USER.showLoginMsg(null,0,$('#username_alert'));
    USER.showLoginMsg(null,0,$('#password_alert'));
    USER.showLoginMsg(null,0,$('#authcode_alert'));
    USER.showLoginMsg('登陆中...',1,$('#submit_alert'));
    var _datas = {username:username,password:password,authcode:authcode};
    var _urls = '/backend/login/doLogin';
    $.ajax({
        type:'GET',
        url: _urls,
        dataType:'json',
        data:_datas,
        success:function(data){
            if(data.success)
            {
                $('#submit_alert').removeClass('alert-danger').addClass('alert-success');
                USER.showLoginMsg('登陆成功',1,$('#submit_alert'));
                if(backurl){
                    window.location = backurl;
                    return false;
                }
                else
                {
                    window.location = '/backend';
                    return false;
                }
            }
            else
            {
                $('#submit_alert').removeClass('alert-success').addClass('alert-danger');
                USER.showLoginMsg(data.error,1,$('#submit_alert'));
                USER.refreshCode();
                return false;
            }
        },
        error:function(xhr,status,error){
            console.log(error);
        }
    });
};

/*------------显示提示------------*/
USER.showLoginMsg = function(text,state,obj){
    if(state==1){
        obj.show().children('span').text(text);
    }else{
        obj.hide();
    }
};

/*------------刷新验证码------------*/
USER.refreshCode = function(){
    $(".randImage").attr("src", "/backend/captcha?t=" + Math.random());
};

$(function () {
    /*验证码*/
    USER.refreshCode();
    $(document).on("click",".randImage",function() {
        USER.refreshCode();
    });

    $("#login_but").click(function () {
        USER.showLoginMsg(null,0,$('#username_alert'));
        USER.showLoginMsg(null,0,$('#password_alert'));
        USER.showLoginMsg(null,0,$('#authcode_alert'));
        var username = $("#username");
        var password = $("#password");
        var authcode = $("#authcode");
        if (!USER.checkUserName(username,$("#username_alert"))) {
            return false;
        }
        if (!USER.checkPassword(password,$("#password_alert"))) {
            return false;
        }
        if (!USER.checkAuthCode(authcode,$("#authcode_alert"))) {
            return false;
        }
        USER.ajaxLogin(username.val(),password.val(),authcode.val(),'');
        return false;
    });
});
