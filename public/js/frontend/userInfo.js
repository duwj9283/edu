/**
 * Created by 20150112 on 2016/3/3.
 */
$(function(){
    /*修改昵称*/
    $('.btn_show_modify').on('click',function(){
        $('.row_input.input_readonly.input_name').removeAttr('readonly');
        $('.row_input.input_readonly.input_name').removeClass('input_readonly');
        $(this).hide();
        $('.row_btn.btn_modify_name').show();
    });
    $('.row_btn.btn_modify_name').on('click', function () {
        $('.row_input.input_name').attr('readonly','readonly');
        $('.row_input.input_name').addClass('input_readonly');
        $(this).hide();
        $('.row_btn.btn_show_modify').show();
    });

    var city=$('#city').attr('data');
    var cityArray = city.split(',');
    /*
    * 省市二级联动
    * ie select问题
    * */
    $("#city").citySelect({
        prov:cityArray[0], //省份
        city:cityArray[1], //城市
        nodata:"none" //当子集无数据时，隐藏select
    });
    /*性别*/
    var sex= $('.sex_wrap').attr('data');
    $('input[name="sexRadios"]').each(function(){
        var thisSex = $(this).val();
        if(thisSex == sex){
            $(this).attr('checked',true);
            //alert($('input[name="sexRadios"]:checked').val());
            return false;
        }
    });
    /*模态框*/
    $("#modal_pop_wrap").modal({show:false});
    /*关闭模态框*/
    $('#modal_pop_wrap').on('hidden.bs.modal', function () {
        if($('#modal_pop_wrap .btn.close_modal_wrap').data('code') == 0){
            window.location.reload();
        }
    });
    /*提交修改个人资料*/
    $('.btn_commit').on('click',function(){
        var nickName = $('.input_name').val();
        var province = $('#city .prov').val();
        var city = $('#city .city').val();
        var area = province+","+city;
        var labels = $('.input_sig').val();
        var qq = $('.input_qq').val();
        var sex = $('input[name="sexRadios"]:checked').val();
        var data={'url':'/api/user/updateInfo','nick_name':nickName,'sex':sex,'city':area,'qq':qq,'labels':labels};
        remote_interface_by_api(data, function (serverData) {
            if(serverData.code === 0){
                $('#modal_pop_wrap .pop_content').text('修改成功');
                $('#modal_pop_wrap').modal('show');
                $('#modal_pop_wrap .btn.close_modal_wrap').data('code',serverData.code);
            }else{
                $('#modal_pop_wrap .pop_content').text(serverData.msg);
                $('#modal_pop_wrap').modal('show');
                $('#modal_pop_wrap .btn.close_modal_wrap').data('code',serverData.code);
            }

        });

    });


    /*左边导航栏*/
    $('.col_side_wrap .col_side_list_wrap').on('click',function(){
        $('.col_side_wrap .col_side_list_wrap').removeClass('selected');
        $(this).addClass('selected');
        $('.body_container .body_inner').hide();
        if($(this).hasClass('btn_user_detail')){
            $('.body_container .body_inner.user_detail_edit_wrap').fadeIn();
        }else if($(this).hasClass('btn_user_collect')){
            $('.body_container .body_inner.user_collect_wrap').fadeIn();
        }else if($(this).hasClass('btn_user_channel')){
            $('.body_container .body_inner.user_channel_wrap').fadeIn();
        }
    });

    /*我的群组的清除搜索内容按钮*/
    $('.btn_clear_search_input').on('click',function(){
        $('.input_search').val('');
    });

    function remote_interface_by_api(data,cb)
    {
        $.ajax({
            'url':data.url,
            'type':"post",
            'data':data,
            'dataType':'json'
        }).done(cb);
    }


});
