/**
 * Created by Administrator on 2016/4/21 0021.
 */
$(function () {
    $('#navbar-collapse-basic .header_option.live').addClass('active');
    $('.col_side_2_title.my_course').addClass('gly-up');
    $('.col_side_li_2_wrap.my_course_wrap').show();
    $('.col_side_list_wrap.btn_exam').addClass('selected');
    var question_list={};//json list
    var question_cur={addtime: '',
        analysis: "",
        answer: "",
        content: "",
        difficulty: "",
        id: 0,
        item:[
            {id: 0,question_id: 0,sort: 0,title: "A"},
            {id: 0,question_id: 0,sort: 0,title: "B"}
        ],
        knowledge_point: "",
        sort: "",
        status: "",
        title: "",
        type: 1,
        uid: ""};//当前选择习题数据
    var question_level=[{id:1,value:'简单'},{id:2,value:'普通'},{id:3,value:'困难'}];//习题难易程度
    /*编辑器*/
    var ue=new Array();//編輯器組
    var ue_config={
        initialFrameWidth : '100%',
        initialFrameHeight: 100,
        elementPathEnabled:false,
        enableAutoSave:false,
        toolbars: [
            ['fullscreen', 'source', 'undo', 'redo', 'bold','simpleupload']
        ]
    };
    /*********************list-S***************************/
    var filter={title:''};
    //list 处理
    var listResult=function(data){
        var typeArr={1:'判断题',2:'单项选择题',3:'多项选择题'};
        question_list=data.data.question_list;

        for(var item in question_list){
            question_list[item].addtime=TimeStamp(question_list[item].addtime).Format("yyyy年M月d日");
            var content = question_list[item].content;
            var img =[];
            question_list[item]['con']= htmldecode(question_list[item].content);
            content.replace(/<img [^>]*src=['"]([^'"]+)[^>]*>/gi, function (match, capture) {
                img.push(capture);
                question_list[item]['img'] = img;
            });
        }
        console.log(question_list);
        $('.classList').html(template('templateList',{list:question_list}));
    };

    examModel.getList(filter,listResult);
    //标题搜索
    $('input[name="title"]').keydown(function(event){
        var title=$(this).val();
        if(event.keyCode==13&&title){
            filter.title=title;
            examModel.getList(filter,listResult);
        }
    });
    /*********************list-E***************************/
    /*********************新增+编辑-S***************************/
    /*新增+编辑弹窗*/
    $(".body_container").delegate('.edit-item',"click",function(){
        var id=$(this).parents('.item').data('id');

        var title='新增习题';
        if(id){
            title='编辑习题';
            question_cur=question_list[id];
        }
        var layer_exam=layer.open({
            type: 1,
            title:title,
            shadeClose: true,
            move: false,
            area:['680px','90%'],
            content: template('templateEdit',{detail:question_cur,level:question_level}),
            end:function(){
                ue[1].destroy();//销毁实例化的编辑器
                ue[2].destroy();
                ue[3].destroy();
            },
            success:function(){
                ue[2] = UE.getEditor('about-content2',ue_config);
                ue[3] = UE.getEditor('about-content3',ue_config);
                ue[1] = UE.getEditor('about-content',ue_config);
                if(question_cur.type){//初始化文本编辑器内容
                    ue[question_cur.type].addListener("ready", function () {
                        ue[question_cur.type].setContent(question_cur.content);
                    });
                }
                /*新增习题类型切换*/
                $("#taskForm .tabTit label").on("click",function(){
                    $(this).addClass("checked").siblings("label").removeClass("checked");
                    $("#taskForm .tabCon").eq($("#taskForm .tabTit label").index($(this))).show().siblings(".tabCon").hide();
                });
                /*选项*/
                $("#taskForm .tabCon .labelBox label").on("click",function(){
                    _this=$(this).parent().find("input");
                    //alert(_this.find("input").is(":checked"));
                    _this.each(function(index, element) {
                        if($(this).is(":checked")){

                            $(this).parent().addClass("checked");
                        }
                        else{
                            $(this).parent().removeClass("checked");
                        }
                    });

                });
                $("#taskForm").slimscroll({
                    height: '100%',
                    size:'5px',
                    color:'#ddd',
                    distance:'5px',
                });
                /*************************选项操作-S by sherry********************************/
                var letter=['A','B','C','D','E','F'];//选项内容
                /*添加选项*/
                $(".add-select").on("click",function(){
                    var $labelBox=$(this).prev('.labelBox');
                    var $label=$labelBox.children('label:last').clone(true);
                    var size=$labelBox.find('label').size();
                    $label.removeClass('checked');//移除选中class
                    $label.find('span').html(letter[size]);//替换选项值
                    $label=replay_letter_name(size,$label);//替换item 中 name
                    if($label.find('.delete-select').length>0){//如果复制的label中含有删除按钮,则删除之前都删除按钮
                        $labelBox.find('.delete-select').remove();//如果之前的选项有-号，则删除
                    }else{//如果复制的label中不含有删除按钮,则在复制都label中添加删除按钮
                        $label.append('<a href="javascript:;" class="delete-select" >-</a>');//后面加上删除选项按钮
                    }
                    $labelBox.append('<br>');
                    $labelBox.append($label);
                    if(size==5){//最多限制6个选项
                        $(this).hide();
                    }
                });
                //复制选项后，替换选项中item隐藏中的input
                var replay_letter_name=function(size,$label){
                    $label.find('input[type="hidden"]').each(function(){
                        $(this).prop('name','items['+size+']['+$(this).data('name')+']');//替换item 中 name
                    });
                    $label.find('.title').val(letter[size]);//替换item 中 title的value
                    $label.find('input[name="answer_arr[]"]').val(letter[size]);//替换item 中 answer的value
                    $label.find('input[name="answer_arr[]"]').removeAttr('checked');//移除选中属性
                    return $label;
                };
                /*删除选项*/
                $(".tabCon").delegate('.delete-select','click',function(){
                    var size=$(this).parents('.labelBox').children('label').size();
                    if(size<=6){//最多限制6个选项
                        $(this).parents('.labelBox').siblings('.add-select').show();
                    }
                    if(size>3){//包括本选项，最少保持三个选项,如果大于两个选择，则在this label上一个label上加上-删除
                        $(this).parents('.labelBox').find('label:eq('+(size-2)+')').append('<a href="javascript:;" class="delete-select" >-</a>');
                    }
                    $(this).parent('label').prev().remove();//删除前面的<br>
                    $(this).parent('label').remove();

                });
                /*************************选项操作-E********************************************/
                //习题提交
                var $submit=$('.submit');
                $submit.click(function(){
                    $(this).prop('disabled',true);
                    var $form;
                    var examType=($('.editCon .tabTit .checked').index())+1;//习题类型
                    $form=$('.exam-form-'+examType);
                    //$form.submit(function(){
                    var answer=[];
                    $form.find('input[name="answer_arr[]"]:checked').each(function(){
                        answer.push($(this).val());
                    });
                    var data=$form.serialize()+'&'+$.param({'answer':(answer.join(',')),'type':examType,'content':(ue[examType].getContent())});
                    examModel.addDetail(data,dealResult);
                    //});*/
                });
                //习题提交添加完成后结果处理
                var dealResult=function(data){
                    $submit.prop('disabled',false);
                    if(data.code==0){
                        window.location.reload();
                        return false;
                    }
                    layer.alert(data.msg);

                };
            }
        });

    });

    /*********************新增+编辑-E***************************/

    //删除
    $('.classList').delegate('.del-item','click',function(){
        var $item=$(this).parents('.item');
        var id=$item.data('id');
        layer.confirm('确定删除么？', {
            time: 0 //不自动关闭
            ,btn: ['确定', '取消']
            ,yes: function(index){
                examModel.deleteDetail({question_id:id},function(data){
                    if(data.code==0){
                        $item.remove();
                        layer.close(index);
                        return false;
                    }
                    layer.alert(data.msg);


                });
            }
        });

    })

});
function htmldecode(str) {
    //str = str.replace(/<img(?:.|\s)*?>/g,"");
    //console.log(str);
    str = str.replace(/<[^>]+>/g,"");
    str = str.replace(/&nbsp;/gi, ' ');
    str = str.replace(/&quot;/gi, '"');
    str = str.replace(/&#39;/g, "'");
    str = str.replace(/&lt;/gi, '<');
    str = str.replace(/&gt;/gi, '>');
    str = str.replace(/<br[^>]*>(?:(rn)|r|n)?/gi, 'n');
    console.log(str);
    return str;
}
