<!-- 添加习题弹窗-->
<script id="templateExamEdit" type="text/html">
    <div class="editCon" id="taskForm" style="display:block">
        <div class="tabTit labelBox selectBox">

            <label  class="checked"  > <i class="fa-select"></i> 判断题</label>
            <label  > <i class="fa-select"></i> 单项选择题</label>
            <label  > <i class="fa-select"></i> 多项选择题</label>

        </div>

        <div class="tabCon" style="display:block" >
            <form class="exam-form-1 form" onsubmit="return false;">
                <input type="hidden" value="0" name="id">
                <div class="row cl">
                    <div class="col col-l"> 标题 </div>
                    <div class="col col-r">
                        <input name="title" type="text" placeholder="请输入标题" class="txt" required />
                    </div>
                </div>
                <div class="row cl">
                    <div class="col col-l"> 题干 </div>
                    <div class="col col-r">
                        <div id="about-content" name="content"></div>
                    </div>
                </div>
                <div class="row cl">
                    <div class="col col-l"> 选项 </div>
                    <div class="col col-r">
                        <div class="labelBox judgeBox">
                            <label class="checked">
                                <input type="hidden" name="items[0][id]"  value="0" >
                                <input type="hidden" name="items[0][type]"  value="radio" >
                                <input type="hidden" name="items[0][title]"  value="对" class="input-title">
                                <input type="radio" name="answer_arr[]"  checked="checked"  value="对" id="RadioGroup2_0">
                                对 <i class="fa-judge"></i> </label>
                            <br />
                            <label>
                                <input type="hidden" name="items[1][id]"  value="0" >
                                <input type="hidden" name="items[1][type]"  value="radio" >
                                <input type="hidden" name="items[1][title]"  value="错" class="input-title">
                                <input type="radio" name="answer_arr[]"    value="错" id="RadioGroup2_1">
                                错 <i class="fa-judge"></i> </label>
                        </div>
                    </div>
                </div>
                <div class="row cl">
                    <div class="col col-l"> 难易度 </div>
                    <div class="labelBox selectBox col col-r">
                        <%each level as lev i%>
                        <label  <%if i==0%> class="checked" <%/if%>> <i class="fa-select"></i>
                        <input type="radio" name="difficulty"  value="<%lev.id%>"  <%if i==0%>  checked <%/if%> >
                        <%lev.value%></label>
                        <%/each%>

                    </div>
                </div>
                <div class="row cl">
                    <div class="col col-l"> 知识点 </div>
                    <div class="col col-r">
                        <input name="knowledge_point" type="text" placeholder="请输入知识点" class="txt" required />
                    </div>
                </div>
                <div class="row cl">
                    <div class="col col-l"> 解析 </div>
                    <div class="col col-r">
                        <textarea name="analysis" placeholder="请输入解析" class="txt" required></textarea>
                    </div>
                </div>
                <div class="row">
                    <input class="submit" type="submit" value="添加">
                </div>
            </form>
        </div>
        <div class="tabCon"  >
            <form class="exam-form-2 form"  onsubmit="return false;">
                <input type="hidden" value="0" name="id">
                <div class="row cl">
                    <div class="col col-l"> 标题 </div>
                    <div class="col col-r">
                        <input name="title" type="text" placeholder="请输入标题" class="txt" required />
                    </div>
                </div>
                <div class="row cl">
                    <div class="col col-l"> 题干 </div>
                    <div class="col col-r">
                        <div id="about-content2" name="content" type="text/plain"></div>
                    </div>
                </div>
                <div class="row cl row-select">
                    <div class="col col-l"> 选项 </div>
                    <div class="col col-r">
                        <div class="labelBox selectBox">

                            <label class="checked" >
                                <input type="hidden" name="items[0][id]"  value="0" >
                                <input type="hidden" name="items[0][type]"  value="radio" data-name="type" >
                                <input type="hidden" name="items[0][title]"  value="A" data-name="title" class="title">
                                <input type="radio" name="answer_arr[]"  value="A"  checked="checked" id="CheckboxGroup4_0">
                                <span>A</span>  <i class="fa-select"></i>
                            </label>
                            <br />
                            <label  >
                                <input type="hidden" name="items[1][id]"  value="0" >
                                <input type="hidden" name="items[1][type]"  value="radio" data-name="type" >
                                <input type="hidden" name="items[1][title]"  value="B" data-name="title" class="title">
                                <input type="radio" name="answer_arr[]"  value="B"  checked="checked" id="CheckboxGroup4_0">
                                <span>B</span>  <i class="fa-select"></i>
                            </label>
                            <br />
                        </div>
                        <!--如果总共有6个选项，添加选项则隐藏-->
                        <a href="javascript:;" class="add-select" data-type="checkbox" ></a>


                    </div>
                </div>
                <div class="row cl">
                    <div class="col col-l"> 难易度 </div>
                    <div class="labelBox selectBox col col-r">
                        <%each level as lev i%>
                        <label  <%if i==0%> class="checked" <%/if%>> <i class="fa-select"></i>
                        <input type="radio" name="difficulty"  value="<%lev.id%>" <%if i==0%> checked <%/if%> >
                        <%lev.value%></label>
                        <%/each%>
                    </div>
                </div>
                <div class="row cl">
                    <div class="col col-l"> 知识点 </div>
                    <div class="col col-r">
                        <input name="knowledge_point" type="text" placeholder="请输入知识点" class="txt" required value=""/>
                    </div>
                </div>
                <div class="row cl">
                    <div class="col col-l"> 解析 </div>
                    <div class="col col-r">
                        <textarea name="analysis"  placeholder="请输入解析" class="txt" required></textarea>
                    </div>
                </div>
                <div class="row">
                    <input class="submit" type="submit" value="添加">
                </div>
            </form>
        </div>
        <div class="tabCon"   >
            <form class="exam-form-3 form"  onsubmit="return false;">
                <input type="hidden" value="0" name="id">
                <div class="row cl">
                    <div class="col col-l"> 标题 </div>
                    <div class="col col-r">
                        <input type="text" name="title" placeholder="请输入标题" class="txt" required />
                    </div>
                </div>
                <div class="row cl">
                    <div class="col col-l"> 题干 </div>
                    <div class="col col-r">
                        <div id="about-content3" name="content" type="text/plain"></div>
                    </div>
                </div>
                <div class="row cl row-select">
                    <div class="col col-l"> 选项 </div>

                    <div class="col col-r">
                        <div class="labelBox judgeBox">

                            <label  class="checked" >
                                <input type="hidden" name="items[0][id]"  value="0" data-name="id" >
                                <input type="hidden" name="items[0][type]"  value="checkbox" data-name="type" >
                                <input type="hidden" name="items[0][title]"  value="A" data-name="title" class="title">
                                <input type="checkbox" name="answer_arr[]"  value="A"  checked="checked" id="CheckboxGroup4_0">
                                <span>A</span> <i class="fa-judge"></i>
                            </label>
                            <br />
                            <label  >
                                <input type="hidden" name="items[0][id]"  value="0" data-name="id" >
                                <input type="hidden" name="items[0][type]"  value="checkbox" data-name="type" >
                                <input type="hidden" name="items[0][title]"  value="B" data-name="title" class="title">
                                <input type="checkbox" name="answer_arr[]"  value="B"   id="CheckboxGroup4_0">
                                <span>B</span> <i class="fa-judge"></i>
                            </label>

                        </div>
                        <!--如果总共有6个选项，添加选项则隐藏-->
                        <a href="javascript:;" class="add-select" data-type="checkbox" ></a>
                    </div>
                </div>
                <div class="row cl">
                    <div class="col col-l"> 难易度 </div>
                    <div class="labelBox selectBox col col-r">
                        <%each level as lev i%>
                        <label  <%if i==0%> class="checked" <%/if%>> <i class="fa-select"></i>
                        <input type="radio" name="difficulty"  value="<%lev.id%>" <%if i==0%> checked <%/if%> >
                        <%lev.value%></label>
                        <%/each%>
                    </div>
                </div>
                <div class="row cl">
                    <div class="col col-l"> 知识点 </div>
                    <div class="col col-r">
                        <input name="knowledge_point" type="text" placeholder="请输入知识点" class="txt" required />
                    </div>
                </div>
                <div class="row cl">
                    <div class="col col-l"> 解析 </div>
                    <div class="col col-r">
                        <textarea name="analysis" placeholder="请输入解析" class="txt" required></textarea>
                    </div>
                </div>
                <div class="row">
                    <input class="submit" type="submit" value="添加">
                </div>
            </form>
        </div>
    </div>
</script>