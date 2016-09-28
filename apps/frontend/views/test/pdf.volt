{% extends "template/basic.volt" %}
{% block pageCss %}
{% endblock %}
{% block content %}
    <div id="pdf-container" style="height:300px;"></div>
    <button id="ex">切换</button>
    <iframe id="iframe-pdf" src="/3rdpart/generic/web/viewer.html?file=/api/file/filePreview/40" width="50%" height="200"></iframe>

{% endblock %}
{% block pageJs %}
    {{ javascript_include("/3rdpart/jquery-ui/jquery-ui.js") }}

    {{ javascript_include("/3rdpart/pdf-view/pdfobject.js") }}
    <script type="text/javascript">
        $('#ex').on('click', function () {
            if($('#iframe-pdf').is(':visible')){
                $('#iframe-pdf').hide();
                $('#iframe-pdf').attr('src','');
            }else if($('#iframe-pdf').is(':hidden')){
                $('#iframe-pdf').attr('src','/3rdpart/generic/web/viewer.html?file=/api/file/filePreview/18');
                $('#iframe-pdf').show();
            }
            //$('#iframe-pdf').attr('src','/3rdpart/generic/web/viewer.html?file=/api/file/filePreview/18');
        });

    </script>
{% endblock %}