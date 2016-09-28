/**
 * Created by 20150112 on 2016/8/4.
 */
$('.js-play').on('click',function(){
    if(!$('#js-user-name').size()){
        loginFrame();
        return false;
    }
})