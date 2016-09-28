// JavaScript Document

//edit
/*发布范围*/
$(".radioBox").on("click",function(){
    $(this).addClass("check").siblings().removeClass("check");
});

$('body').on("click","#tags .label",function(){
    $(this).remove();
});

/*添加资料*/
$("#addFile").on("click",function(){
    var layerFile= layer.open({
        type: 1,
        title: false,
        closeBtn: 0,
        shadeClose: true,
        content: $('#checkFile')
    });
});


