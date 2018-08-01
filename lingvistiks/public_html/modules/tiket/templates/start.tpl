<div class="menubar">
	<div class="page-title">
		<a href="{{ ABS_PATH_ADMIN_LINK }}?do=module" class="bread"><i class="fa fa-angle-double-left"></i></a>

		<h1>{{ lang.module_name_one }}: {{ module_info.module_name|escape|stripslashes }}</h1>

		<div class="clearfix"></div>

		<ul class="page-title-menu list-inline">
            <li>
                <a class="btn-flat gray" href="{{ ABS_PATH_ADMIN_LINK }}?do=module&sub=mod_edit&module_tag=tiket&module_action=tiket_work"><i class="fa fa-plus-circle" aria-hidden="true"></i> {{ lang.tiket_add }}</a>
            </li>
			{% if SESSION.alles or SESSION.user_group in module_info.module_main_access %}
	            <li>
	                <a class="btn-flat gray" href="{{ ABS_PATH_ADMIN_LINK }}?do=module&sub=mod_edit&module_tag=tiket&module_action=tiket_group_work"><i class="fa fa-plus-circle" aria-hidden="true"></i> {{ lang.tiket_group_work }}</a>
	            </li>
			{% endif %}
        </ul>
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
	    <form method="post" action="{{ ABS_PATH_ADMIN_LINK }}?do=module&sub=mod_edit&module_tag=tiket&module_action=tiket_start" class="row">
	        <h2>{{ lang.search_filter }}</h2>
	        <input type="hidden" name="csrf_token" value="{{ csrf_token }}">
	        <input type="hidden" name="filter" value="1">

			<div class="col-lg-4 col-md-6 col-sm-6">
				<div class="form-group">
					<label for="">{{ lang.search_start }}</label>
		            <input type="text" value="{{start}}" class="form-control datepicker" autocomplete="off" name="start" placeholder="{{ lang.search_start }}">
		        </div>
				<div class="form-group">
					<label for="">{{ lang.search_end }}</label>
		            <input type="text" value="{{end}}" class="form-control datepicker" autocomplete="off" name="end" placeholder="{{ lang.search_end }}">
		        </div>
				<div class="form-group">
					<label for="">{{ lang.tiket_id }}</label>
					<input type="text" value="{{REQUEST.tiket_id|stripslashes|escape}}" class="form-control" name="tiket_id" placeholder="{{ lang.tiket_id }}">
				</div>
				<div class="form-group">
					<label for="">{{ lang.tiket_title }}</label>
					<input type="text" value="{{REQUEST.tiket_title|stripslashes|escape}}" class="form-control" name="tiket_title" placeholder="{{ lang.tiket_title }}">
				</div>
			</div>

			<div class="col-lg-4 col-md-6 col-sm-6">
				<div class="form-group">
					<label for="">{{ lang.tiket_tags }}</label>
					<input type="text" value="{{REQUEST.tiket_tags|stripslashes|escape}}" class="form-control" name="tiket_tags" placeholder="{{ lang.tiket_tags }}">
				</div>

				<div class="form-group">
					<label for="">{{ lang.tiket_group }}</label>
					<div class="clearfix"></div>
					<select class="selectpicker" name="tiket_group[]" multiple>
						<option value=""></option>
						{% for group in tiket_group_list %}
							<option value="{{group.tiket_group_id}}" {% if group.tiket_group_id in REQUEST.tiket_group %}selected{% endif %}>{{group.tiket_group_title|escape|stripslashes}}</option>
						{% endfor %}
					</select>
				</div>
			</div>

			<div class="col-lg-4 col-md-6 col-sm-6">
				<div class="form-group">
					<label for="">{{ lang.tiket_status }}</label>
					<ul class="list-unstyled">
						{% for key, value in tiket_status_array %}
							<li>
								<div class="checkbox checkbox-primary">
									<input id="tiket_status_{{ key }}" name="tiket_status[]" value="{{ key }}" type="checkbox" {% if key in REQUEST.tiket_status  %}checked{% endif %} >
									<label for="tiket_status_{{ key }}">
										{{ value }}
									</label>
								</div>
							</li>
						{% endfor %}
					</ul>
				</div>
			</div>

			<div class="clearfix"></div>
	        <div class="actions">
				<input type="submit" class="btn-flat gray" value="{{ lang.search_btn }}">

				{% if REQUEST.filter %}
		            <div class="filter_clear">
		                &nbsp;<a href="{{ ABS_PATH_ADMIN_LINK }}?do=module&sub=mod_edit&module_tag=tiket&module_action=tiket_start"><i class="fa fa-times"></i> {{ lang.search_clear_btn }}</a>
		            </div>
		        {% endif %}
	        </div>
	    </form>
	</div>

    <div class="content-wrapper">
		{% if tiket_list %}
		    <div class="table-responsive">
		        <table class="datatables table-striped">
		            <thead>
		                <tr>
		                    <th class="col-md-1">
		                        &nbsp
		                    </th>
		                    <th class="col-md-2">
		                        {{ lang.tiket_title }}
		                    </th>
		                    <th class="col-md-2">
		                        {{ lang.tiket_tags }}
		                    </th>
		                    <th class="col-md-2">
		                        {{ lang.tiket_group }}
		                    </th>
		                    <th class="col-md-1">
		                        {{ lang.tiket_owner }}
		                    </th>
		                    <th class="col-md-1">
		                        {{ lang.tiket_answer }}
		                    </th>
		                    <th class="col-md-1">
		                        {{ lang.tiket_comment_count }}
		                    </th>
		                    <th class="col-md-1">
		                        {{ lang.tiket_reg }}
		                    </th>
							<th class="col-md-1">
								&nbsp
							</th>
		                </tr>
		            </thead>
		            <tbody>
		                {% for tiket in tiket_list %}
		                    <tr>
								<td>
									{% if tiket.tiket_close == 1 %}
										<i class="fa fa-lock" aria-hidden="true"></i>
									{% else %}
										<i class="fa fa-unlock" aria-hidden="true"></i>
									{% endif %}
								</td>
		                        <td>
		                            <a href="{{ ABS_PATH_ADMIN_LINK }}?do=module&sub=mod_edit&module_tag=tiket&module_action=tiket_open&tiket_id={{tiket.tiket_id|escape|stripslashes}}">{{tiket.tiket_title|escape|stripslashes}}</a>
		                        </td>
								<td>
									{% for key, tags in tiket.tiket_tags_list %}
										{{ tags|escape|stripslashes }}{% if key != tiket.tiket_tags_last %}, {% endif %}
									{% endfor %}
								</td>
								<td>
									{{tiket.tiket_group.tiket_group_title|escape|stripslashes}}
								</td>
								<td>
									{{ tiket.tiket_owner.user_name|default(tiket.tiket_user_name)|escape|stripslashes }}
									{% if tiket.tiket_owner_open == 1 %}<i class="fa fa-envelope-open-o" aria-hidden="true"></i>{% else %}<i class="fa fa-envelope-o" aria-hidden="true"></i>{% endif %}
								</td>
								<td>
									{{tiket.tiket_answer.user_name|escape|stripslashes}}
									{% if tiket.tiket_answer_open == 1 %}<i class="fa fa-envelope-open-o" aria-hidden="true"></i>{% else %}<i class="fa fa-envelope-o" aria-hidden="true"></i>{% endif %}
								</td>
								<td>
									{{tiket.tiket_comment_count}}
								</td>
								<td>
									{{tiket.tiket_add|escape|stripslashes}}
								</td>
								<td>
									<ul class="list-inline">
										{% if SESSION.alles or SESSION.user_group in module_info.module_main_access %}
											{% if tiket.tiket_close != 1 %}
												<li>
													<a href="{{ ABS_PATH_ADMIN_LINK }}?do=module&sub=mod_edit&module_tag=tiket&module_action=tiket_work&tiket_id={{tiket.tiket_id|escape|stripslashes}}"><i class="fa fa-pencil"></i></a>
												</li>
											{% endif %}

											{% if SESSION.alles %}
												<li>
				                                    <a href="{{ ABS_PATH_ADMIN_LINK }}?do=module&sub=mod_edit&module_tag=tiket&module_action=tiket_work&delete_tiket_id={{tiket.tiket_id}}" class="confirm"><i class="fa fa-times"></i></a>
												</li>
											{% endif %}
										{% endif %}
									</ul>

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
		    <p>{{ lang.empty_data }}</p>
		{% endif %}

    </div>
</div>
