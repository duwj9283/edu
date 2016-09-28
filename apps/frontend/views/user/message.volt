{% extends "template/basic-v2.volt" %}
{% block pageCss %}
    {{ stylesheet_link("css/frontend/less/lessbase.css") }}
    {{ stylesheet_link("css/frontend/col-side.css") }}
{% endblock %}
{% block content %}
    <div class="inner_body content">
        <div class="person-con-infor">
            <div class="container clearfix">
                {% include 'template/col-side.volt' %}
                <div class="body_container">
                    <div class="message-wrap">
                        <ul class="message-list" id="js-message" data-total="{{ total }}">

                        </ul>
                        <div class="pager-wrap"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script id="templateMsgList" type="text/html">
        <%each messageList as message%>
        <li class="message-item <% if message.view_status === "0" %>no<% /if %>">
                <div class="msg-tit clearfix"  data-id="<% message.message_id %>" data-status="<% message.view_status %>"><span class="msg-time pull-right"><% message.created_at %></span><i></i> <% message.message_info.title %></div>
        <p class="msg-info"><% message.message_info.content %></p>
                </li>
        <%/each%>
    </script>
{% endblock %}
{% block pageJs %}
    {{ javascript_include("/js/frontend/col-side.js") }}
    {#artTemplate#}
    {{ javascript_include("/3rdpart/template/template.js") }}
    {{ javascript_include("/js/frontend/basic.js") }}
    {{ javascript_include("/js/frontend/user/message.js") }}
    <script type="text/javascript">
        $(function () {
           $('.body_side li.btn_icon_message').addClass('selected');
        });
    </script>
{% endblock %}