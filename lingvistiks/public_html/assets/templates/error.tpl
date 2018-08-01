<script src="{{ ABS_PATH }}assets/build/js/chart.min.js"></script>
<script type="text/javascript">
$(function () {
    var data = {
      labels: {{period}},
      series: [{{sum}},]
    };

    app_chart.simple_chart_line(data);
});
</script>

<div class="menubar">
    <ul class="list-inline pull-right dropdown-header-right">
        <li class="dropdown">
            <a aria-expanded="false" href="#" data-toggle="dropdown" class="dropdown-toggle">
                <i class="fa fa-cog"></i>
            </a>
            <ul class="dropdown-menu dropdown-notification pull-right">
                <li class="notification-item">
                    <a href="{{ ABS_PATH_ADMIN_LINK }}?do=logs">{{lang.log_title}}</a>
                </li>
                <li class="notification-item">
                    <a href="{{ ABS_PATH_ADMIN_LINK }}?do=logs&sub=error">{{lang.log_title2}}</a>
                </li>

				<li class="divider"></li>
				<li class="notification-item">
                    <a href="{{ ABS_PATH_ADMIN_LINK }}?do=impex{{ page_link }}&sub=export_log">{{lang.log_export}}</a>
                </li>
				<li class="notification-item">
                    <a href="{{ ABS_PATH_ADMIN_LINK }}?do=impex{{ page_link }}&sub=export_error">{{lang.log_error_export}}</a>
                </li>

				{% if app_log_delete or app_error_delete %}<li class="divider"></li>{% endif %}
                {% if app_log_delete %}
                    <li class="notification-item">
                        <a class="confirm" href="{{ ABS_PATH_ADMIN_LINK }}?do=logs&sub=clear">{{lang.log_delete}}</a>
                    </li>
                {% endif %}
                {% if app_error_delete %}
                    <li class="notification-item">
                        <a class="confirm" href="{{ ABS_PATH_ADMIN_LINK }}?do=logs&sub=clear_error">{{lang.log_delete2}}</a>
                    </li>
                {% endif %}
            </ul>
        </li>
    </ul>

    <div class="page-title">
        <h1>{{lang.log_title2}} <sup>{{ num|default('0') }}</sup></h1>
    </div>
</div>

<div class="datatables">
    <div class="content-wrapper">
        <div class="filters">
            <ul class="list-inline links">
                <li>
                    <div class="show-filter label label-success"><i class="fa fa-filter"></i></div>
                </li>
            </ul>
        </div>
    </div>

	<div class="content-wrapper search-section">
	    <form method="post" action="{{ ABS_PATH_ADMIN_LINK }}?do=logs&sub=error" class="row">
			<h2>{{lang.search_filter}}</h2>

	        <input type="hidden" name="csrf_token" value="{{ csrf_token }}">
			<input type="hidden" name="filter" value="1">

			<div class="col-lg-4 col-md-6 col-sm-6">
				<div class="form-group">
					<label for="">{{ lang.search_start }}</label>
					<input type="text" value="{{start}}" class="form-control datepicker" autocomplete="off" name="start" placeholder="{{lang.search_start}}">
				</div>
				<div class="form-group">
					<label for="">{{ lang.search_end }}</label>
					<input type="text" value="{{end}}" class="form-control datepicker" autocomplete="off" name="end" placeholder="{{lang.search_end}}">
				</div>
			</div>

			<div class="col-lg-4 col-md-6 col-sm-6">
				<div class="form-group">
					<label for="">{{ lang.search_owner }}</label>
					<div class="clearfix"></div>
					<select class="selectpicker" name="owner_id">
						<option value=""></option>
						{% for main_user in main_users %}
							<option value="{{main_user.id}}" {% if main_user.id==REQUEST.owner_id %}selected{% endif %} >{{main_user.user_name}}</option>
						{% endfor %}
					</select>
				</div>
			</div>

			<div class="col-lg-4 col-md-12 col-sm-12">
				<div class="form-group">
					<label for="">{{ lang.search_group_ip }}</label>
					<input type="text" value="{{ REQUEST.search_ip|escape|stripslashes }}" class="form-control" name="search_ip" placeholder="{{lang.search_group_ip}}">
				</div>
			</div>

			<div class="clearfix"></div>
	        <div class="actions">
				<input type="submit" class="btn-flat gray" value="{{ lang.search_btn }}">

				{% if REQUEST.filter %}
		            <div class="filter_clear">
		                &nbsp;<a href="{{ ABS_PATH_ADMIN_LINK }}?do=logs&sub=error"><i class="fa fa-times"></i> {{ lang.search_clear_btn }}</a>
		            </div>
		        {% endif %}
	        </div>
	    </form>
	</div>

    <div class="content-wrapper">
        <h2>{{lang.log_graph}}</h2>
        {% if sum %}
            <div class="ct-chart"></div>
        {% else %}
            <p>{{lang.empty_data}}</p>
        {% endif %}
    </div>

    <div class="content-wrapper">
        {% if logs %}
                <table class="datatables table-striped">
                    <thead>
                        <tr>
                           {% if SESSION.alles %}
                                <th class="col-md-2">
                                    {{lang.log_list_ip}}
                                </th>

                                <th class="col-md-2">
                                    {{lang.log_list_user}}
                                </th>
                            {% endif %}
                            <th class="col-md-{% if SESSION.alles %}6{% else %}10{% endif %}">
                                {{lang.log_list_desc}}
                            </th>
                            <th class="col-md-2">
                                {{lang.log_list_data}}
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        {% for log in logs %}
                            <tr>
                                {% if SESSION.alles %}
                                    <td>
                                        {{log.log_ip|escape|stripslashes}}
                                    </td>
                                    <td>
                                        {% if log.log_user_id %}
                                            <a href="{{ ABS_PATH_ADMIN_LINK }}?do=profile&sub=edit&user_id={{log.log_user_id}}">{{log.log_user.user_name}}</a>
                                        {% else %}
                                            {{ lang.log_list_noname }}
                                        {% endif %}
                                    </td>
                                {% endif %}
                                <td>
                                    {{log.log_text|stripslashes}}
                                    {% if log.log_url %}
                                        <br>
                                        <a href="{{ ABS_PATH_ADMIN_LINK }}?{{log.log_url}}">{{log.log_url|truncate('70')}}</a>
                                    {% endif %}
                                </td>
                                <td>
                                    {{log.log_time}}
                                </td>
                            </tr>
                        {% endfor %}
                    </tbody>
                </table>
            </div>
            {% if page_nav %}
                {{page_nav}}
            {% endif %}
        {% else %}
            <p>{{lang.empty_data}}</p>
        {% endif %}
    </div>
</div>
