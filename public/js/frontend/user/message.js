/* 展开我的消息内容 */
var MESSAGEMODEL={
    /*获得我的站内信*/
    getMyMessage:function(data,cb){
        $.post('/api/message/getMyMessage',data,cb,'json');
    },
    /*点击查看我的消息*/
    viewMyMessage:function(data,cb){
        $.post('/api/message/viewMyMessage',data,cb,'json');
    },
    /*站内信列表*/
    messageList: function(startPage){
        MESSAGEMODEL.getMyMessage({page:startPage},function(serverData){
            if(serverData.code === 0){
                var messageList=serverData.data.messageList;
                $('#js-message').html(template('templateMsgList',{messageList:messageList}));
                MESSAGEMODEL.pagination(startPage);
                /*点击查看我的消息*/
                $("#js-message .message-item .msg-tit").on("click",function(){
                    var _this=$(this);
                    if(_this.data("status") == 0){
                        MESSAGEMODEL.viewMyMessage({message_id:_this.data("id")},function(serverData){
                            if(serverData.code === 0) {
                                _this.data("status","1");
                                _this.parent().removeClass('no'); /*设置阅读后状态*/
                            }
                            else{
                                layer.msg(serverData.msg);
                            }
                        });
                    }
                    _this.next(".msg-info").toggle();
                });
            }
            else{
                layer.msg(serverData.msg);
            }
        });
    },
    /*加载分页*/
    pagination: function  (startPage) {
        /*
         * allCount:需要分页的总数
         * eCount：每页显示数目
         * startPage:起始页
         * */
        var allCount = $('#js-message').data('total');
        var eCount = 12;
        var pageCount = Math.ceil(allCount / eCount);
        var pageHtml =  page(pageCount,eCount,startPage);
        $('.pager-wrap').empty();
        if (pageHtml !== ''){
            $('.pager-wrap').html(pageHtml);
            MESSAGEMODEL.paginationBind();
        }
    },
    /*分页跳转函数*/
    paginationBind: function  () {
        $('.pager-wrap ul li a').on('click', function () {
            var startPage = parseInt($(this).attr('rel'));
            MESSAGEMODEL.messageList(startPage);
        });
    },
}
$(function(){
    MESSAGEMODEL.messageList(1);
});

