
var manageModel={
    getList: function (data,cb) {
        $.post('/api/group/getMyGroup',data,cb,'json');
    }

};