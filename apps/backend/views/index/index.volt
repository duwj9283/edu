{% extends "templates/basic.volt" %}
{% block pageCss %}
    {{ stylesheet_link("3rdpart/iCheck/custom.css") }}
{% endblock %}
{% block content %}
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-10">
            <h2>我的文件</h2>
            <ol class="breadcrumb">
                <li>
                    <a href="index.html"><i class="fa fa-home"></i></a>
                </li>
                <li>
                    <a href="index.html">账号管理</a>
                </li>
                <li class="active">
                    <strong>教师账号管理</strong>
                </li>
            </ol>
        </div>
    </div>
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <div class="btn-group">
                            <button data-toggle="dropdown" aria-expanded="false"
                                    class="btn btn-white btn-bitbucket">
                                <i class="fa fa-plus"></i>
                            </button>
                            <ul class="dropdown-menu">
                                <li>
                                    <a>
                                        <label for="choose-file" style="width: 100%;height: 100%;cursor: pointer;margin: 0;">
                                            <i class="fa fa-upload"></i><span style="margin-left: 10px">上传</span>
                                        </label>
                                    </a>
                                    <input type="file" multiple="multiple" id="choose-file" style="display: none;" />
                                </li>
                                <li>
                                    <a><i class="fa fa-folder"></i><span style="margin-left: 10px">文件夹</span></a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="ibox-content">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th><input type="checkbox" class="i-checks" name="input[]"></th>
                                    <th width="80%">名称</th>
                                    <th width="2%"></th>
                                    <th width="2%"></th>
                                    <th>进度</th>
                                    <th>大小</th>
                                    <th>修改日期</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr style="display: none" id="upload_files"><td colspan="7"></td></tr>
                                {% for data in userFiles %}
                                    <tr>
                                        <td><input type="checkbox" class="i-checks" name="input[]"></td>
                                        <td>{{ data.file_name}}</td>
                                        <td>
                                            <a style="color: #999"><i class="fa fa-share-alt"></i></a>
                                        </td>
                                        <td>
                                            <div style="position: absolute">
                                                <a style="color: #999" class="dropdown-toggle" data-toggle="dropdown" href="#"
                                                   aria-expanded="false">
                                                    <i class="fa fa-bars"></i>
                                                </a>
                                                <ul class="dropdown-menu animated fadeInRight m-t-xs">
                                                    <li><a href="profile.html"><i class="fa fa-info-circle"></i><span
                                                                    style="margin-left: 10px">详细信息</span></a></li>
                                                    <li><a href="contacts.html"><i class="fa fa-edit"></i><span
                                                                    style="margin-left: 10px">重命名</span></a></li>
                                                    <li><a href="http://www.socket.ccc/tmp/{{ data.file_name }}"><i class="fa fa-download"></i><span
                                                                    style="margin-left: 10px">下载</span></a></li>
                                                    <li class="divider"></li>
                                                    <li><a href="/backend/login"><i class="fa fa-trash-o"></i><span
                                                                    style="margin-left: 10px">删除</span></a></li>
                                                </ul>
                                            </div>
                                        </td>
                                        <td><span class="pie">{{ data.percent }}/100</span></td>
                                        <td>{{ "%.2f"|format(data.file_size/1024/1024) }}M</td>
                                        <td>{{ date('Y-m-d',data.addtime) }}</td>
                                    </tr>
                                {% endfor %}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div style="width: 500px;" class="small-chat-box fadeInRight animated">
        <div class="heading" draggable="true">
            <small class="chat-date pull-right">
                <span>0</span>个文件
            </small>
            文件上传进度
        </div>
        <div class="content" style="padding: 0;">
            <div class="row" style="margin: 0;">
                <div class="col-lg-12">
                    <div class="sidebar-container">
                        <ul id="file_uploading" class="sidebar-list">

                        </ul>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <div id="small-chat">
        <span class="badge badge-warning pull-right">0</span>
        <a class="open-small-chat">
            <i class="fa fa-folder"></i>
        </a>
    </div>
{% endblock %}