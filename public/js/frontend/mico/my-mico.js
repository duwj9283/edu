/**
 * Created by 20150112 on 2016/4/11.
 */
$(function () {
    /*标头选中*/
    MYMICO.headActive($('#navbar-collapse-basic ul li.active'),$('#navbar-collapse-basic ul .camtasiastudio'));
    /**/
    MYMICO.getMyMlessons(1);
    //删除
    $('.classList').delegate('.js-del','click',function(){
        var $item=$(this).parents('.item');
        var id=$item.data('id');
        layer.confirm('确定删除么？', {
            time: 0 //不自动关闭
            ,btn: ['确定', '取消']
            ,yes: function(index){
                MYMICO.deleteDetail({m_lesson_id:id},function(data){
                    console.log(id);
                    if(data.code==0){
                        $item.remove();
                        layer.close(index);
                        layer.msg('成功删除');
                        return false;
                    }
                    layer.msg(data.msg);
                });
            }
        });
    });
});