/**
 * Created by 20150112 on 2016/8/2.
 */

$(function(){
    //初始化flash
    var path = $('input[name="path"]').val();
    // For version detection, set to min. required Flash Player version, or 0 (or 0.0.0), for no version detection.
    var swfVersionStr = "20.0.0";
    // To use express install, set to playerProductInstall.swf, otherwise the empty string.
    var xiSwfUrlStr = "playerProductInstall.swf";
    var flashvars = {};
    //流地址
    flashvars.streamUrl = config.rtmp;
    //流名称
    flashvars.streamName = path;
    //类型，1-直播，2-录播
    flashvars.type = 2;
    //发送已播放时间间隔,录播才需要,单位-秒
    flashvars.sendTimeInterval = 5;
    var params = {};
    params.quality = "high";
    params.bgcolor = "#ffffff";
    params.allowscriptaccess = "sameDomain";
    params.allowfullscreen = "true";
    var attributes = {};
    attributes.id = "liveTopic";
    attributes.name = "liveTopic";
    attributes.align = "middle";
    swfobject.embedSWF(
        "/js/frontend/live/liveTopic.swf", "flashContent",
        "100%", "100%",
        swfVersionStr, xiSwfUrlStr,
        flashvars, params, attributes);
    // JavaScript enabled so display the flashContent div in case it is not replaced with a swf object.
    swfobject.createCSS("#flashContent", "display:block;text-align:left;");
    //创建
    $('.js-add-topic').off('click');
    $('.js-add-topic').on('click',function(){
        if($.trim(oName.val()) == ''){
            layer.msg('请输入名称');
            return false;
        }
        addLiveVideoInfo()

    });
    //tab切换
    $('#js-tabs li').off('click');
    $('#js-tabs li').on('click',function(){
        var name=$(this).find('a:first').attr('href').split('#')['1'];
        if(name=='b'){
            $('.end-time').show();
            iType=2
        }
        else{
            $('.end-time').hide();
            $('input[name="end"]').attr('checked',false);
            iType=1
        }
    });
    getLiveVideoInfoList(iType);
    //删除
    $('body').off('click','.js-del');
    $('body').on('click','.js-del',function(){
        var id=$(this).data('id');
        layer.confirm('确定删除？', {
            btn: ['确定','取消'] //按钮
        },function(){
            delLiveVideoInfo(id);
        })
    });
    //修改
    $('body').off('click','.js-edit');
    $('body').on('click','.js-edit',function(){
        var id=$(this).data('id');
        var name=$('#'+id).find('td:first').text();
        var index=layer.confirm('<input value="'+name+'" class="form-control" name="edit-name">', {
            btn: ['确定','取消'], //按钮
            title:'修改'
        },function(){
            var name= $.trim($('input[name="edit-name"]').val());
            editLiveVideoInfo(id,name);
            layer.close(index);
        })
    });
    //    重置表单
    $('.js-reset').off('click');
    $('.js-reset').on('click',function(){
        document.getElementById("topic-form").reset();
    })
    oEndDate.on('focus');
    oEndDate.on('focus',function(){
        $('input[name="end"]').attr('checked','checked');
    });
    oStarDate.on('focus');
    oStarDate.on('focus',function(){
        $('input[name="star"]').attr('checked','checked');
    });
});
var oName = $('input[name="name"]'),
    oStarDate = $('input[name="star-date"'),
    oEndDate = $('input[name="end-date"'),
    iLiveId = $('input[name="id"]').val();
    iStarTime ='',
    iEndTime='',
    iType=1;

function formatSeconds(value) {
    var s = parseInt(value);// 秒
    var m = '00';// 分
    var h = '00';// 小时
    if(s > 60) {
        m = parseInt(s/60);
        s = parseInt(s%60);
        if(m > 60) {
            h = parseInt(m/60);
            m = parseInt(m%60);
            if(h<10){
                h='0'+ h.toString();
            }
        }
        if(m<10){
            m='0'+ m.toString();
        }
    }
    if(s<10){
        s='0'+ s.toString();
    }
    var result = h+":"+m+":"+s;
    return result;

}
function getPlayedTime(time)
{
    if($('input[name="star"]').is(':checked') && !$('input[name="end"]').is(':checked')){
        iStarTime = time;
        oStarDate.val(formatSeconds(time));

        if(time>iEndTime && oEndDate.val()!=''){
            layer.msg('开始时间不能大于结束时间');
            oStarDate.val(oEndDate.val());
        }
    }
    if($('input[name="end"]').is(':checked')){
        iEndTime = time;
        oEndDate.val(formatSeconds(time));
        if(time<=iStarTime){
            layer.msg('结束时间不能小于开始时间');
            oEndDate.val(oStarDate.val());
        }
    }
};

function addLiveVideoInfo(){
    var name=oName.val();
    var data = {'url':'/api/file/addLiveVideoInfo',live_id:iLiveId,start_time:iStarTime,end_time:iEndTime,name:name,type:iType}
    remote_interface_api(data,function(serverData){
        if(serverData.code==0){
            layer.msg('成功创建');
            getLiveVideoInfoList(iType);
        }
    })
}
function getLiveVideoInfoList(type){
    var data = {'url':'/api/file/getLiveVideoInfoList',live_id:iLiveId}
    remote_interface_api(data,function(serverData){
        if(serverData.code==0){
            var knowlegeList=serverData.data.knowlegeList;
            var qiepianList=serverData.data.qiepianList;
            for(var i=0;i<knowlegeList.length;i++){
                knowlegeList[i]["start_time"] = formatSeconds(knowlegeList[i].start_time);
            }
            for(var i=0;i<qiepianList.length;i++){
                qiepianList[i]['start_time'] = formatSeconds(qiepianList[i].start_time);
                qiepianList[i]['end_time'] = formatSeconds(qiepianList[i].end_time);
            }
            $('#js-tabs li').one('click',function(){
                var name=$(this).find('a:first').attr('href').split('#')['1'];
                if(name=='a'){
                    $('.js-a').html(template("zsd",{list:knowlegeList}))
                }
                if(name=='b'){
                    $('.js-b').html(template("qp",{list:qiepianList}))
                }
            });
            if(type==1){
                $('#js-tabs li:first').trigger('click');
            }
            else{
                $('#js-tabs li:eq(1)').trigger('click');
            }
        }
    })
}
function delLiveVideoInfo(id){
    var data = {'url':'/api/file/delLiveVideoInfo',id:id}
    remote_interface_api(data,function(serverData){
        if(serverData.code==0){
            layer.msg('成功删除');
            $('#'+id).remove();
        }
    })
}
function editLiveVideoInfo(id,name){
    var data = {'url':'/api/file/editLiveVideoInfo',id:id,name:name}
    remote_interface_api(data,function(serverData){
        if(serverData.code==0){
            layer.msg('修改成功');
            $('#'+id).find('td:first').text(name);
        }
    })
}
