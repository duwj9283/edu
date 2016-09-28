{% extends "template/basic-v2.volt" %}
{% block pageCss %}
    {{ stylesheet_link("css/frontend/less/lessbase.css") }}
    {{ stylesheet_link("css/frontend/col-side.css") }}
    {{ stylesheet_link("js/frontend/webUpload/webuploader.css")  }}
    <style>
        .w1120{
            width: 60%;
            min-width: 1150px;
            margin: 0 auto;
        }
        .pri-list>li>label {
            padding-top: 10px;
        }
        /***上传图片*/
        #uploader-demo {
            position: relative;
            height: 271px;
            width: 235px;
        }
        #uploader-demo>#filePicker {
            position: absolute;
            width: 235px;
            height: 40px;
            bottom: 0px;
            z-index: 10;
            background-color:rgba(0,0,0,0.5)
        }
        #filePicker>.webuploader-pick {
            width: 100%;
            background: none;
            border-radius: 0px;
        }
        #fileList>.thumbnail {
            width: 235px;
            padding: 0px;
            border: 0;
        }
    </style>
{% endblock %}
{% block content %}
    <div class="inner_body content">
        <div class="person-con-infor">
            <div class="container clearfix">
                {% include 'template/col-side.volt' %}
                <div class="body_container">
                    <div class="per-info-tit per-info-tit02 margin-top-40">
                        <span>个人空间</span>
                    </div>
                    <div class="line margin-top-10"></div>
                    <div class="person-index-block02 person-index-block widthauto margin-top-10">
                        <div class="clearfix space-person-infor">
                            <div class="person_edit">编辑</div>
                            <div class="fl person_pic-box">
                                <div id="uploader-demo">
                                                <div id="fileList" class="uploader-list" >
                                                    <img id="filePicker-img" src="/img/frontend/camtasiastudio/person_pic.jpg" width="235" height="271">
                                                </div>
                                                <div id="filePicker"></div>
                                            </div>

                                <span > 51关注 / 125学生</span>
                            </div>
                            <div class="person-index-text fl" id="userInfo">

                                <div class="clearfix per-infor-text">
                                    <div class="person-name fl">田甜甜 <span>教师</span><p>安徽  合肥</p></div>

                                </div>
                                <p>简介：Dream Offer，致力于为在校大学生提供专业的名企求职辅导与职业规划<br>咨询服务</p>
                                <p><span>学科：英语</span><span>三年级二班</span></p>
                                <p>
                                    <span>qq：22343434</span>
                                </p>
                                <p><span>电话：13567854456</span></p>
                                <p><span>邮箱：135678@126.com</span></p>

                            </div>
                            <div class="person-index-text fl" id="userInfo_edit">

                                 <div class="clearfix"><input type="text" class="pri-input" value="田甜甜" name="realname" placeholder="请输入姓名" required=""><span>教师</span>
                                 </div>
                                  <div class="clearfix margin-top-10" id="city" data="安徽,合肥">
                                    <div  class="mod-select  fl" >
                                            <select class="prov province_link" required></select>
                                        </div>
                                        <div   class="mod-select fl" >
                                            <select class="city province_link"   ></select>
                                        </div>
                                    </div>
                                <p><textarea name="about-content"  placeholder="个人简介" class="about-content">Dream Offer，致力于为在校大学生提供专业的名企求职辅导与职业规划咨询服务</textarea></p>
                                <div class="clearfix margin-top-10"  id="subject-select">
        
                                    <div class="mod-select fl">
                                        <select class="sub-type" required></select>

                                    </div>
                                    <div class="mod-select fl">
                                        <select class="sub-child-type" name="subject"></select>
                                    </div>
                                </div>
                                <p>
                                    <input type="text" placeholder="qq" class="pri-input" name="qq" value="135678" />
                                </p>
                                <p><input type="text" class="pri-input"  value="{{ user.phone }}" placeholder="tel" required disabled/></p>
                                <p><input type="email" class="pri-input" name="email" value="{{ userInfo.email }}" required placeholder="请输入邮箱" email="true"/></p>
                                <div class="margin-top-10">
                                    <input class="pri-btn pri-btn02" type="submit" value="修改">
                                    <input class="pri-btn pri-btn02 pri-cancel" type="submit"  value="取消">
                                </div>

                            </div>
                        </div>
                        <div class="line margin-top-20"></div>
                        <div class="dt-list dt-list02 dt-center">
                            <a id="space-source" >
                                <i class="dt-ico dt-resource"> </i>
                                <span>资源</span>
                            </a>
                            <a id="space-dynamic"   class="active">
                                <i class="dt-ico dt-actiing"></i>
                                <span>动态</span>
                            </a>
                            <a id="space-article" >
                                <i class="dt-ico dt-circle"></i>
                                <span>文章</span>
                            </a>
                            <a id="space-live" >
                                <i class="dt-ico dt-living"></i>
                                <span>直播</span>
                            </a>
                            <a id="space-mico" >
                                <i class="dt-ico dt-mir"></i>
                                <span>微课</span>
                            </a>
                            <a id="space-course" >
                                <i class="dt-ico dt-class"></i>
                                <span>课程</span>
                            </a>
                            <a id="space-active" >
                                <i  class="dt-ico dt-active"></i>
                                <span>活动</span>
                            </a>
                        </div>
                        <div class="text-align-right dt-text pt-right-value">
                            <span><span >34</span><span>群组</span></span>
                            <span><span >34</span><span>资源</span></span>
                            <span><span >34</span><span>点赞</span></span>
                        </div>
                        <div class="text-align-right dt-send-btn  pt-right-value">
                            <span class="send-btn" id="update-news-btn">发布动态/文章</span>
                        </div>
                        <div class="line margin-top-30"></div>
                        <div id="space-content-list">
                            <div class="td-static">
                                <h2>个人动态</h2>
                                <ul class="td-list">
                                    <li>
                                        <label>2016年04月01日<span>14:21</span></label>
                                        <b class="active"></b>
                                        <div class="dt-list-box">
                                            <div class="dt-list-infor clearfix">
                                                <div class="clearfix dt-list-img fl">
                                                    <b></b>
                                                    <img src="/img/frontend/camtasiastudio/small_pic.png" width="33" height="33"  />
                                                </div>
                                                <div class="dt-list-text fl">
                                                    <h3>dotdog</h3>
                                                    <p>第二章课程的学习资料将在晚上上传。</p>
                                                    <div class="fj">
                                                        <img src="/img/frontend/camtasiastudio/fj.png"  />
                                                        <a href=""></a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="text-align-right dt-text">
                                                <span><span>34</span><span>点赞</span></span>
                                                <span><span>34</span><span>评论</span></span>
                                            </div>
                                        </div>
                                        <ul class="dt-detail-list">
                                            <li class="clearfix">
                                                <div class="clearfix dt-list-img fl">
                                                    <b></b>
                                                    <img src="/img/frontend/camtasiastudio/small_pic.png" width="33" height="33"  />
                                                </div>
                                                <div class="fl">
                                                    <h4>序言</h4>
                                                    <p>基本界面是如何操作的</p>
                                                </div>
                                            </li>
                                            <li class="clearfix">
                                                <div class="clearfix dt-list-img fl">
                                                    <b></b>
                                                    <img src="/img/frontend/camtasiastudio/small_pic.png" width="33" height="33"  />
                                                </div>
                                                <div class="fl">
                                                    <h4>序言</h4>
                                                    <p>基本界面是如何操作的</p>
                                                </div>
                                            </li>
                                            <li class="clearfix">
                                                <div class="clearfix dt-list-img fl">
                                                    <b></b>
                                                    <img src="/img/frontend/camtasiastudio/small_pic.png" width="33" height="33"  />
                                                </div>
                                                <div class="fl">
                                                    <h4>序言</h4>
                                                    <p>基本界面是如何操作的</p>
                                                </div>
                                            </li>
                                        </ul>
                                    </li>
                                    <li>
                                        <label>2016年04月01日<span>14:21</span></label>
                                        <b ></b>
                                        <div class="dt-list-infor clearfix">
                                            <div class="clearfix dt-list-img fl">
                                                <b></b>
                                                <img src="/img/frontend/camtasiastudio/small_pic.png" width="33" height="33"  />
                                            </div>
                                            <div class="dt-list-text fl">
                                                <h3>dotdog</h3>
                                                <p>第二章课程的学习资料将在晚上上传。</p>
                                                <div class="fj">
                                                    <img src="/img/frontend/camtasiastudio/fj.png"  />
                                                    <a href=""></a>
                                                </div>
                                            </div>

                                        </div>
                                        <ul class="dt-detail-list">
                                            <li class="clearfix">
                                                <div class="clearfix dt-list-img fl">
                                                    <b></b>
                                                    <img src="/img/frontend/camtasiastudio/small_pic.png" width="33" height="33"  />
                                                </div>
                                                <div class="fl">
                                                    <h4>序言</h4>
                                                    <p>基本界面是如何操作的</p>
                                                </div>
                                            </li>
                                            <li class="clearfix">
                                                <div class="clearfix dt-list-img fl">
                                                    <b></b>
                                                    <img src="/img/frontend/camtasiastudio/small_pic.png" width="33" height="33"  />
                                                </div>
                                                <div class="fl">
                                                    <h4>序言</h4>
                                                    <p>基本界面是如何操作的</p>
                                                </div>
                                            </li>
                                            <li class="clearfix">
                                                <div class="clearfix dt-list-img fl">
                                                    <b></b>
                                                    <img src="/img/frontend/camtasiastudio/small_pic.png" width="33" height="33"  />
                                                </div>
                                                <div class="fl">
                                                    <h4>序言</h4>
                                                    <p>基本界面是如何操作的</p>
                                                </div>
                                            </li>
                                        </ul>
                                    </li>

                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="update-news-popLayer">
        <div class="row cl">
            <div class="col-l">内容</div>
            <div class="col-r">
                <textarea class="form-textare"></textarea>
            </div>
        </div>
        <div class="row cl">
            <div class="col-l">添加附件</div>
            <div class="col-r">
                <div class="add-file-btn"></div>
                <div class="file-con">
                    <ul>
                       <li><a href="#">文件.jpg</a></li>
                       <li><a href="#">文件.jpg</a></li>
                       <li><a href="#">文件.jpg</a></li>
                    </ul>
                </div>
                <p class="msg">最多添加6个附件，仅限于JPG、RAR、ZIP格式</p>
            </div>
        </div>
        <div class="send-btn">提交</div>

    </div>
{% endblock %}
{% block pageJs %}
    {{ javascript_include("js/frontend/col-side.js") }}
    {{ javascript_include("/js/frontend/cityselect/jquery.cityselect.js") }}
    {{ javascript_include("js/frontend/less2/layer/layer.js") }}
    {{ javascript_include("js/frontend/subjectselect/jq.subselect.js") }}
    {#上传文件插件#}
    {{ javascript_include("js/frontend/webUpload/upload.js") }}
    {{ javascript_include("js/frontend/webUpload/webuploader.min.js") }}
    {{ javascript_include("js/frontend/userspace/userspace.js") }}
    {{ javascript_include("js/frontend/userspace/userspaceModal.js") }}

    <script type="text/javascript">
        $(function () {
            $('.body_side li.btn_person').addClass('selected');
            /*城市联动*/
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
            subObject.init({
                pt:$('input[name="subjectFatherName"]').val(),
                ct:$('input[name="subjectName"]').val()
            });
            /*编辑个人资料*/
            $(".person_edit").on("click",function(){
               $("#userInfo_edit,#filePicker").show();
               $("#userInfo").hide();
               $(this).hide();
            });
            $("#userInfo_edit .pri-cancel").on("click",function(){
               $("#userInfo_edit,#filePicker").hide();
               $("#userInfo,.person_edit").show();
            });
            /*更换头像*/
            webUploader.init({
                label:'点击更换封面',
                pick:'#filePicker',
                thumbSize:{
                    width:235,
                    height:271
                }
            });
            /*发布动态*/
            $('#update-news-btn').on("click",function(){
                   layer.open({
                    type:1,
                    title:"发布动态",
                    area: ['600px', 'auto'],
                    content: $(".update-news-popLayer")
                   });

            });
            $(".update-news-popLayer").delegate(".add-file-btn","click",function(){
                $(".update-news-popLayer .file-con").toggle();
            });
        });
    </script>
{% endblock %}