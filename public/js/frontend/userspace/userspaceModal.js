/**
 * Created by 20150112 on 2016/4/15.
 */
var SPACE ={
    toggleList: function ($t) {
        if(!$t.hasClass('active')){
            var id=$t.attr('id');
            var $p= $t.parent();
            $p.children('.active').removeClass('active');
            $t.addClass('active');
            switch (id){
                case 'space-source':
                    window.location.href='/user/resource';
                    break;
                case 'space-dynamic':
                    window.location.href='/user/person';
                    break;
                case 'space-article':
                    break;
                case 'space-live':
                    window.location.href='/user/live';
                    break;
                case 'space-mico':
                    window.location.href='/user/mico';
                    break;
                case 'space-course':
                    window.location.href='/user/course';
                    break;
                case 'space-active':
                    window.location.href='/user/activity';
                    break;
                default :
                    break;
            }
        }
    },

};