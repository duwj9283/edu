var $form=$('form[name="form-login"]');
//登录提交之后
var loginResult = function (serverData) {
    if (serverData.code === 0) {
        if(location.search.indexOf('?backurl=')>=0){
            var href=location.search.split('?backurl=')['1'];
            console.log(href);
            window.location.href = href;
            //window.location.href = decodeURI(href);
        }
        else{
            window.location.href = '/file';
        }
    } else {
        $form.find(':submit').prop('disabled',false);
        showTip($("input[name='password']"),'show',serverData.msg);

    }
};
var showTip=function($this,type,msg){

    if(type=='show'){
        if($this.parents(".field").find('.field-msg').length<=0){
            $('.field-msg').remove();
            $this.parents(".field").append('<div class="field-msg msg-warning">'+msg+'</div>');

        }
    }

};
$form.submit(function () {
    var $username = $("input[name='username']"),
        $password = $("input[name='password']");
    var myreg = /^([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+@([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+\.[a-zA-Z]{2,3}$/;
    if (!$username.val()) {
        showTip($username,'show','请输入用户名');
        return false;
    }
    if (!$password.val()) {
        showTip($password,'show','请输入密码');
        return false;
    }
    $form.find(':submit').prop('disabled',true);
    var postData = {'username': $username.val(), 'password': $password.val()};
    $.post('/api/login/byUsername', postData, loginResult, 'json');
});
$("input[type=text],input[type=password],input[type=tel]").focus(function(){
        $(this).parents(".field").addClass("field-focus");
    }
);
$("input[type=text],input[type=password],input[type=tel]").blur(function(){
        $(this).parents(".field").removeClass("field-focus");
    }
);


