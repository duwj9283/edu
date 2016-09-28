/**
 * Created by 20150112 on 2016/8/2.
 */
var LIVEDETAILMODEL= {
    remote_interface_api: function (data, cb) {
        $.ajax({
            url: data.url,
            type: 'post',
            data: data,
            dataType: 'json'
        }).done(cb);
    },
}
$(function(){
    $("#js-play-live").on('click',function(){
        if(!$('#js-user-name').size()){
            loginFrame();
            return false;
        }
        var data={url:'/api/live/inLive',live_id:live_id};
        LIVEDETAILMODEL.remote_interface_api(data, function (serverData) {
            console.log(serverData);
        });
    })
});