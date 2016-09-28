{% extends "template/basic-v2.volt" %}
{% block pageCss %}
    {{ stylesheet_link("css/frontend/basic.css")  }}
    {{ stylesheet_link("css/frontend/col-side.css")  }}
    {{ stylesheet_link("css/frontend/course-manage.css")  }}
    {{ stylesheet_link("css/frontend/less2/common.css")  }}
    {{ stylesheet_link("css/frontend/less2/home.css")  }}
    {{ stylesheet_link("css/frontend/less2/edit.css")  }}
{% endblock %}
{% block content %}
    <div class="inner_body">
        <div class="center_wrap container clearfix">
            {% include 'template/col-side.volt' %}
            <div class="body_container">
                <div class="title clearfix"> <a href="javascript:;" class="post fr edit-item"><i class="fa-post"></i><span>新增习题</span></a>
                    <!--<div class="task-search fr">-->
                        <!--<input type="text" class="s-word txt2" placeholder="标题" name="title">-->
                    <!--</div>-->
                    <h2 class="task-h2 fl mr40">我的题库</h2>

                    <!--<select class="select-group fl">-->
                        <!--<option>创建时间</option>-->
                        <!--<option>创建时间</option>-->
                        <!--<option>创建时间</option>-->
                    <!--</select>-->
                </div>
                <div class="classList" id="taskList">

                </div>
                <!-- 列表结束  clasList end -->
            </div>
        </div>
    </div>
{% endblock %}
{% block commonModal %}
    <!-- 从题库添加-->

    <!-- list-template-->
    <script id="templateList" type="text/html">

        <%each list as value%>
        <div class="item" data-id="<%value.id%>">
            <% if value.type == 1%>
            <div class="type typeJudge">判断题</div>
            <% /if %>
            <% if value.type == 2%>
            <div class="type typeRadio">单选题</div>
            <% /if %>
            <% if value.type == 3%>
            <div class="type typeCheck">多选题</div>
            <% /if %>

            <!--


              -->
            <div class="tit cl"><span class="time fr"><%value.addtime%></span>
                <h1 class="c_1"><%value.title%></h1>
            </div>
            <p class="desc"><%value.con%>
            <% if value.img %>
            <div class="list-img">
                <%each value.img as item %>
                <img src="<%item%>" width="500" height="">
                <% /each %>
            </div>
            <% /if %>
            </p>
            <div class="ans cl">
                <%if value.type==1%>
                <!--判断题-->
                <div class="labelBox judgeBox">
                    <%each value.item as item%>
                    <label <%if value.answer == item.title%> class="checked"  <%/if%> > <i class="fa-judge"></i>
                    <input type="radio" name="RadioGroup5" disabled="disabled"
                           value="<%item.title%>" id="RadioGroup5_0">
                    <%item.title%></label>
                    <%/each%>

                </div>
                <%else if value.type==2%>
                <!--单选题-->
                <div class="desc labelBox ">
                    <p>
                        <%each value.item as item%>
                        <span <%if value.answer==item.title%> class="checked"  <%/if%> ><i class="fa-select "></i> <%item.title%></span>
                        <%/each%>
                    </p>
                    <img src=""/> </div>

                <%else if value.type==3%>
                <!--多项选择题-->
                <div class="labelBox judgeBox">
                    <%each value.item as item%>
                    <label <%if (value.answer).indexOf(item.title)>=0%> class="checked"  <%/if%>><i class="fa-judge"></i> <%item.title%></label>
                    <%/each%>
                </div>
                <%/if%>

            </div>
            <div class="operate"> <a href="javascript:void(0);" class="del-item"><i class="fa-del"></i>删除</a> <a href="javascript:void(0);" class="edit-item"><i class="fa-edit"></i>编辑</a> </div>
        </div>
        <%/each%>
        <!-- 循环体 item end -->
    </script>
    <!-- edit-template-->
    <script id="templateEdit" type="text/html">
        <div class="editCon" id="taskForm" style="display:block">
            <div class="tabTit labelBox selectBox">

                <label  <%if detail.type==1%> class="checked" <%/if%> > <i class="fa-select"></i> 判断题</label>
                <label <%if detail.type==2%> class="checked" <%/if%>> <i class="fa-select"></i> 单项选择题</label>
                <label <%if detail.type==3%> class="checked" <%/if%>> <i class="fa-select"></i> 多项选择题</label>

            </div>

            <div class="tabCon" <%if !detail.id||detail.type==1%> style="display:block" <%/if%> >
                <form class="exam-form-1 form form-horizontal" onsubmit="return false;">
                    <input type="hidden" name="id"  value="<%detail.id%>" >
                    <div class="form-group">
                        <label class="col-sm-2 control-label">标题</label>
                        <div class="col-sm-10">
                            <input name="title" type="text" placeholder="请输入标题" class="form-control" required value="<%detail.title%>"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">题干</label>
                        <div class="col-sm-10">
                            <div id="about-content" name="content" ></div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">选项</label>
                        <div class="col-sm-10">
                            <div class="labelBox judgeBox">
                                <label class="checked">
                                    <input type="hidden" name="items[0][id]"  value="0" >
                                    <input type="hidden" name="items[0][type]"  value="radio" >
                                    <input type="hidden" name="items[0][title]"  value="对" class="input-title">
                                    <input type="radio" name="answer_arr[]"  <%if !detail.answer||detail.answer=='对'%> checked="checked" <%/if%>  value="对" id="RadioGroup2_0">
                                    对 <i class="fa-judge"></i> </label>
                                <br />
                                <label>
                                    <input type="hidden" name="items[1][id]"  value="0" >
                                    <input type="hidden" name="items[1][type]"  value="radio" >
                                    <input type="hidden" name="items[1][title]"  value="错" class="input-title">
                                    <input type="radio" name="answer_arr[]"   <%if detail.answer=='错'%> checked="checked" <%/if%>  value="错" id="RadioGroup2_1">
                                    错 <i class="fa-judge"></i> </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">难易度</label>
                        <div class="col-sm-10">
                            <div class="labelBox selectBox">
                                <%each level as lev%>
                                <label  <%if detail.difficulty==lev.id%> class="checked" <%/if%>> <i class="fa-select"></i>
                                <input type="radio" name="difficulty"  value="<%lev.id%>" <%if detail.difficulty==lev.id%> checked="checked" <%/if%> id="CheckboxGroup1_0">
                                <%lev.value%></label>
                                <%/each%>

                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">知识点</label>
                        <div class="col-sm-10">
                            <input name="knowledge_point" type="text" placeholder="请输入知识点" class="form-control" required value="<%=detail.knowledge_point%>"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">解析</label>
                        <div class="col-sm-10">
                            <textarea name="analysis" placeholder="请输入解析" rows="5" class="form-control" required><%detail.analysis%></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <button class="submit" type="submit">添加</button>
                    </div>
                </form>
            </div>
            <div class="tabCon" <%if detail.type==2%> style="display:block" <%/if%>>
                <form class="exam-form-2 form form-horizontal"  onsubmit="return false;">
                    <input type="hidden" name="id"  value="<%detail.id%>" >
                    <div class="form-group">
                        <label class="col-sm-2 control-label">标题</label>
                        <div class="col-sm-10">
                            <input name="title" type="text" placeholder="请输入标题" class="form-control" required value="<%detail.title%>"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">题干</label>
                        <div class="col-sm-10">
                            <div id="about-content2" name="content" type="text/plain"></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">选项</label>
                        <div class="col-sm-10">
                            <div class="labelBox selectBox">
                                <%each detail.item as item j%>
                                <label <%if (detail.answer).indexOf(item.title) > -1 %> class="checked" <%/if%>>
                                <input type="hidden" name="items[<%j%>][id]"  value="<%item.id%>" data-name="id" >
                                <input type="hidden" name="items[<%j%>][type]"  value="<%if item.type%><%item.type%><%else%>radio<%/if%>" data-name="type" >
                                <input type="hidden" name="items[<%j%>][title]"  value="<%item.title%>" data-name="title" class="title">
                                <input type="radio" name="answer_arr[]"  value="<%item.title%>" <%if (detail.answer).indexOf(item.title)>-1  %> checked="checked"<%/if%> id="CheckboxGroup4_0">
                                <span><%item.title%></span>  <i class="fa-select"></i>
                                </label>
                                <%if j<(detail.item.length-1) %> <br /><%/if%>
                                <%/each%>

                            </div>
                            <!--如果总共有6个选项，添加选项则隐藏-->
                            <a href="javascript:;" class="add-select" data-type="checkbox" <%if (detail.item.length)>=6 %> style="display:none" <%/if%> ></a>


                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">难易度</label>
                        <div class="col-sm-10">
                            <div class="labelBox selectBox col col-r">
                                <%each level as lev%>
                                <label  <%if detail.difficulty==lev.id%> class="checked" <%/if%>> <i class="fa-select"></i>
                                <input type="radio" name="difficulty"  value="<%lev.id%>" <%if detail.difficulty==lev.id%> checked="checked" <%/if%> id="CheckboxGroup1_0">
                                <%lev.value%></label>
                                <%/each%>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">知识点</label>
                        <div class="col-sm-10">
                            <input name="knowledge_point" type="text" placeholder="请输入知识点" class="form-control" required value="<%detail.knowledge_point%>"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">解析</label>
                        <div class="col-sm-10">
                            <textarea name="analysis"  placeholder="请输入解析" rows="5" class="form-control" required><%detail.analysis%></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <button class="submit" type="submit">添加</button>
                    </div>
                </form>

            </div>
            <div class="tabCon" <%if detail.type==3%> style="display:block" <%/if%>>
                <form class="exam-form-3 form form-horizontal"  onsubmit="return false;">
                    <input type="hidden" name="id"  value="<%detail.id%>" >
                    <div class="form-group">
                        <label class="col-sm-2 control-label">标题</label>
                        <div class="col-sm-10">
                            <input type="text" name="title" placeholder="请输入标题" class="form-control" required value="<%detail.title%>"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">题干</label>
                        <div class="col-sm-10">
                            <div id="about-content3" name="content" type="text/plain"></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">选项</label>
                        <div class="col-sm-10">
                            <div class="labelBox judgeBox">
                                <%each detail.item as item j%>
                                <label <%if (detail.answer).indexOf(item.title) > -1 %> class="checked" <%/if%>>
                                <input type="hidden" name="items[<%j%>][id]"  value="<%item.id%>" data-name="id" >
                                <input type="hidden" name="items[<%j%>][type]"  value="<%if item.type%><%item.type%><%else%>checkbox<%/if%>" data-name="type" >
                                <input type="hidden" name="items[<%j%>][title]"  value="<%item.title%>" data-name="title" class="title">
                                <input type="checkbox" name="answer_arr[]"  value="<%item.title%>" <%if (detail.answer).indexOf(item.title)>-1  %> checked="checked"<%/if%> id="CheckboxGroup4_0">
                                <span><%item.title%></span> <i class="fa-judge"></i>
                                <%if (detail.item.length)==6&&j==(detail.item.length-1) %><!--如果总共有6个选项，在最后一个选项后面+delete--><a href="javascript:;" class="delete-select" >-</a><%/if%>
                                </label>
                                <%if j<(detail.item.length-1) %> <br /><%/if%>
                                <%/each%>


                            </div>
                            <!--如果总共有6个选项，添加选项则隐藏-->
                            <a href="javascript:;" class="add-select" data-type="checkbox" <%if (detail.item.length)>=6 %> style="display:none" <%/if%> ></a>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">难易度</label>
                        <div class="col-sm-10">
                            <div class="labelBox selectBox col col-r">
                                <%each level as lev%>
                                <label  <%if detail.difficulty==lev.id%> class="checked" <%/if%>> <i class="fa-select"></i>
                                <input type="radio" name="difficulty"  value="<%lev.id%>" <%if detail.difficulty==lev.id%> checked="checked" <%/if%> id="CheckboxGroup1_0">
                                <%lev.value%></label>
                                <%/each%>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">知识点</label>
                        <div class="col-sm-10">
                            <input name="knowledge_point" type="text" placeholder="请输入知识点" class="form-control" required value="<%detail.knowledge_point%>"//>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">解析</label>
                        <div class="col-sm-10">
                            <textarea name="analysis" placeholder="请输入解析" rows="5" class="form-control" required><%detail.analysis%></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <button class="submit" type="submit">添加</button>
                    </div>
                </form>
            </div>
        </div>
    </script>
{% endblock %}
{% block pageJs %}
    {{ javascript_include("js/frontend/basic.js") }}
    {{ javascript_include("3rdpart/template/template.js") }}
    {{ javascript_include("js/frontend/col-side.js") }}
    {{ javascript_include("js/frontend/less2/layer/layer.js") }}
    {#滚动条#}
    {{ javascript_include("3rdpart/slimscroll/jquery.slimscroll.min.js") }}
    {#UEditor#}
    {{ javascript_include("3rdpart/ueditor/ueditor.config.js") }}
    {{ javascript_include("3rdpart/ueditor/ueditor.all.js") }}
    {#题库js#}
    {{ javascript_include("js/frontend/course/examModel.js") }}
    {{ javascript_include("js/frontend/course/exam.js") }}
{% endblock %}