var $G = function(id){
    return document.getElementById(id);
};

var required_check = function(str){
    if(str.replace(/(^\s*)|(\s*$)/g, '') === ''){
        return false;
    }else{
        return true;
    };
};

var failure = function(data){
    var str = typeof(data) == 'string' ? data : data.responseJSON;
    dialog({
        content: '<i class="fa fa-info-circle"></i> ' + str,
        ok: true
    }).showModal();
};

var getGetArgs = function(key){
    var args = {};
    var query = location.search.substring(1);
    var pairs = query.split('&');
    for(var i = 0; i < pairs.length; i++) {
        var pos = pairs[i].indexOf('=');
        if (pos == -1) continue;
        var argname = pairs[i].substring(0,pos);
        var value = pairs[i].substring(pos+1);
        value = decodeURIComponent(value);
        args[argname] = value;
    }
    return key !== undefined ? args[key] : args;
};

var getRealSize = function(s){
    var size = parseInt(s);
    var n, kb = 1024, mb = kb * 1024, gb = mb * 1024, tb = gb * 1024;
    if(size < kb) return size + " B";
    if(size < mb) return (size / kb).toFixed(2).replace(/(\.?0*$)/g, '') + " KB";
    if(size < gb) return (size / mb).toFixed(2).replace(/(\.?0*$)/g, '') + " MB";
    if(size < tb) return (size / gb).toFixed(2).replace(/(\.?0*$)/g, '') + " GB";
    return (size / tb).toFixed(2).replace(/(\.?0*$)/g, '') + " TB";
};



$(function(){
    $(window).bind('load resize', function(){
        $('#page-wrapper').css('min-height', $(window).height());
    });

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
});