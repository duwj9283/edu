/**
 * Created by 20150112 on 2016/3/22.
 */
//基础配置
var config = {
    url:'http://edu.iguangj.com',
    rtmp:'rtmp://edu.iguangj.com:1935/vod/',
    upload:'http://edu.iguangj.com:3000',
    daobo:'http://edu.iguangj.com:81', //导播台地址
};
//登录弹出层
var loginFrame = function(){
    $("#js-login-poper").remove();
    $('body').append(template('loginTemplate',{}));
    $('form[name="form-login"]').submit(function () {
        var $username = $("input[name='username']"),
            $password = $("input[name='password']");
        if($('.login-msg').size()>=1){
            $('.login-msg').remove();
        }
        if (!$username.val()) {
            $('.login-username').after('<div class="form-group login-msg text-warning">请输入用户名</div>');
            return false;
        }
        if (!$password.val()) {
            $('.login-password').after('<div class="form-group login-msg text-warning">请输入密码</div>');
            return false;
        }
        $('form[name="form-login"]').find(':submit').prop('disabled',true);
        var postData = {'username': $username.val(), 'password': $password.val()};
        $.post('/api/login/byUsername', postData, function(serverData){
            if(serverData.code == 0){
                location.reload();
            }
            if(serverData.code == 2){
                $('.login-password').after('<div class="form-group login-msg text-warning">密码长度不足</div>');
                $('form[name="form-login"]').find(':submit').prop('disabled',false);
            }
            else{
                $('.login-password').after('<div class="form-group login-msg text-warning">'+serverData.msg+'</div>');
                $('form[name="form-login"]').find(':submit').prop('disabled',false);
            }
        }, 'json');
    });
    $(".js-close-login").off();
    $(".js-close-login").on('click',function(){
        $("#js-login-poper").remove();
    })
};
//ajax
var remote_interface_api = function (data,cb,be) {
    $.ajax({
        url:data.url,
        type:'post',
        data:data,
        dataType:'json',
    }).done(cb);
};
//分页js
var page = function(page_count, total_rows, page_no){
    if(page_count < 2) return '';
    page_no = parseInt(page_no);
    var page_str = '<ul class="pagination">';
    if(page_no > 1){
        page_str += '<li><a href="javascript:;" rel="'+ (page_no - 1) +'">« 上一页</a></li>';
    }else{
        page_str += '<li class="prev"><span>« 上一页</a></span>';
    }
    var more = 4;
    var m = (page_no - Math.ceil(more / 2) > 0) ? (page_no - Math.ceil(more / 2)) : 1; //起始页码
    var n = (m + more < page_count) ? (m + more) : page_count; //终止页码
    m = ((n - m) < more) ? (n - more) : m;
    m = (m > 0) ? m : 1;
    if(m > 1){
        page_str += '<li><a href="javascript:;" rel="1">1</a></li>';
        if(m > 2){
            page_str += '<li><span>…</span></li>';
        }
    }
    for (i=m; i<=n; i++){
        if(i == page_no){
            page_str += '<li class="active"><span>'+ i +'</span></li>';
        }else{
            page_str += '<li><a href="javascript:;" rel="'+ i +'">'+ i +'</a></li>';
        }
    }
    if(i <= page_count){
        if(i != page_count){
            page_str += '<li><span>…</span></li>';
        }
        page_str += '<li><a href="javascript:;" rel="'+ page_count +'">'+ page_count +'</a></li>';
    }
    if(page_no < page_count){
        page_str += '<li><a href="javascript:;" rel="'+ (page_no + 1) +'">下一页 »</a></li>';
    }else{
        page_str += '<li class="next"><span>下一页 »</span></li>';
    }
    return page_str + '</ul>';
};

// 对Date的扩展，将 Date 转化为指定格式的String
// 月(M)、日(d)、小时(h)、分(m)、秒(s)、季度(q) 可以用 1-2 个占位符，
// 年(y)可以用 1-4 个占位符，毫秒(S)只能用 1 个占位符(是 1-3 位的数字)
// 例子：
// (new Date()).Format("yyyy-MM-dd hh:mm:ss.S") ==> 2006-07-02 08:09:04.423
// (new Date()).Format("yyyy-M-d h:m:s.S")      ==> 2006-7-2 8:9:4.18
// (new Date()).Format("yyyy年M月d日");      ==> 2006年7月2日
Date.prototype.Format = function(fmt)
{
    var o = {
        "M+" : this.getMonth()+1,                 //月份
        "d+" : this.getDate(),                    //日
        "h+" : this.getHours(),                   //小时
        "m+" : this.getMinutes(),                 //分
        "s+" : this.getSeconds(),                 //秒
        "q+" : Math.floor((this.getMonth()+3)/3), //季度
        "S"  : this.getMilliseconds()             //毫秒
    };
    if(/(y+)/.test(fmt))
        fmt=fmt.replace(RegExp.$1, (this.getFullYear()+"").substr(4 - RegExp.$1.length));
    for(var k in o)
        if(new RegExp("("+ k +")").test(fmt))
            fmt = fmt.replace(RegExp.$1, (RegExp.$1.length==1) ? (o[k]) : (("00"+ o[k]).substr((""+ o[k]).length)));
    return fmt;
};
// 将 'Y-m-d H:i:s' 转化为指时间对象
// (new Date()).Format("yyyy-MM-dd hh:mm:ss.S") ==> 2006-07-02 08:09:04.423
var TimeStamp = function(time)
{
    var oDate, f, d,t;
    f = time.split(' ', 2);
    d = (f[0] ? f[0] : '').split('-', 3);
    t = (f[1] ? f[1] : '').split(':', 3);
    oDate = (new Date(
        parseInt(d[0], 10) || null,
        (parseInt(d[1], 10) || 1) - 1,
        parseInt(d[2], 10) || null,
        parseInt(t[0], 10) || null,
        parseInt(t[1], 10) || null,
        parseInt(t[2], 10) || null
    ));
    return oDate;
};