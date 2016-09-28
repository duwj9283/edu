<div class="select-subject-wrap">
    <div class="container clearfix">
        <div class="subject-top clearfix">
            <h3 class="pull-left" id="js-open-select">全部学科</h3>
            <div class="subject_check_wrap pull-left" id="js-subject-check" >
            </div>
        </div>
        <div class="subject-content">
            <div class="select_label_wrapper" id="subject_father_list">
                <div class="container ">
                    <div class="subject_list_wrap clearfix">
                        {% for index, subject in subjects %}
                        {% if index<=6 %}
                        <a href="javascript:;" rel="nofollow"  class="select-name" data-id="{{ subject.id }}">{{ subject.subject_name }}</a>
                        {% endif %}
                        {% endfor %}
                        <a href="javascript:;" rel="nofollow" class="open-subject" id="js-open-subject">展开</a>
                    </div>
                    <div class="subject_more_wrap" id="js-subject-more">
                        <div class="subject_search_wrap">
                            <input type="text" placeholder="搜索学科" name="subject-kewords" class="subject_search_input form-control">
                        </div>
                        <div class="subject_list_wrap clearfix">
                            {% for index, subject in subjects %}
                            {% if index>6 %}
                            <a href="javascript:;" rel="nofollow"  class="select-name" data-id="{{ subject.id }}">{{ subject.subject_name }}</a>
                            {% endif %}
                            {% endfor %}
                        </div>
                    </div>
                </div>
            </div>
            <div class="select_label_wrapper" style="display: none;">
                <div class="container">
                    <div class="subject_list_wrap clearfix" id="subject_sub_list">

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
