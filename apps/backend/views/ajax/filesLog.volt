<tr>
    <td><input type="checkbox" class="i-checks" name="input[]"></td>
    <td>{{ user_file.file_name }}</td>
    <td>
        <a><i class="fa fa-share-alt"></i></a>
    </td>
    <td>
        <div style="position: absolute">
            <a class="dropdown-toggle" data-toggle="dropdown" href="#"
               aria-expanded="false">
                <i class="fa fa-bars"></i>
            </a>
            <ul class="dropdown-menu animated fadeInRight m-t-xs">
                <li><a href="profile.html"><i class="fa fa-info-circle"></i><span
                                style="margin-left: 10px">详细信息</span></a></li>
                <li><a href="contacts.html"><i class="fa fa-edit"></i><span
                                style="margin-left: 10px">重命名</span></a></li>
                <li><a href="mailbox.html"><i class="fa fa-download"></i><span
                                style="margin-left: 10px">下载</span></a></li>
                <li class="divider"></li>
                <li><a href="/backend/login"><i class="fa fa-trash-o"></i><span
                                style="margin-left: 10px">删除</span></a></li>
            </ul>
        </div>
    </td>
    <td><span class="pie">{{ file.percent }}/100</span></td>
    <td>{{ "%.2f"|format(file.size/1024/1024) }}M</td>
    <td>{{ date('Y-m-d',user_file.addtime) }}</td>
</tr>