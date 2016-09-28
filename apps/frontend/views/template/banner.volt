{% if images['0'] != ''%}
<div class="banner-wrap">
    <div class="container">
        <div id="slide" class="carousel slide" data-ride="carousel">
            <!-- Indicators -->
            <ol class="carousel-indicators">
                {% for index, image in images %}
                <li data-target="#slide" data-slide-to="{{index}}" {%if index == 0%} class="active" {%endif%}></li>
                {% endfor %}
            </ol>
            <!-- Wrapper for slides -->
            <div class="carousel-inner" role="listbox">
                {% for index, image in images %}
                <div class="item {%if index == 0%}active{%endif%}">
                    <img src="/{{ image }}" width="1210" height="500" />
                </div>
                {% endfor %}
            </div>
            <!-- Controls -->
            <a class="left carousel-control" href="#slide" role="button" data-slide="prev">
                <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="right carousel-control" href="#slide" role="button" data-slide="next">
                <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>
        </div>
    </div>
</div>
{% endif %}