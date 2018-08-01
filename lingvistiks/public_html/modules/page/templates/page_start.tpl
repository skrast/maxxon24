<div class="menubar">
    <div class="page-title">
		<a href="{{ ABS_PATH_ADMIN_LINK }}?do=module" class="bread"><i class="fa fa-angle-double-left"></i></a>

        <h1>{{ lang.page_name }} <sup>{{ num|default('0') }}</sup></h1>

        <div class="clearfix"></div>
        <ul class="page-title-menu list-inline">
            <li>
                <a class="btn-flat gray" href="{{ ABS_PATH_ADMIN_LINK }}?do=module&sub=mod_edit&module_tag=page&module_action=page_work"><i class="fa fa-plus-circle" aria-hidden="true"></i> {{ lang.page_add }}</a>
            </li>
            <li>
                <a class="btn-flat gray" href="{{ ABS_PATH_ADMIN_LINK }}?do=module&sub=mod_edit&module_tag=page&module_action=folder_work">{{ lang.page_folder }}</a>
            </li>
            <li>
                <a class="btn-flat gray" href="{{ ABS_PATH_ADMIN_LINK }}?do=module&sub=mod_edit&module_tag=page&module_action=navi_start">{{ lang.page_navi }}</a>
            </li>
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
	    <form method="post" action="{{ ABS_PATH_ADMIN_LINK }}?do=module&sub=mod_edit&module_tag=page&module_action=page_start" class="row">
			<h2>{{ lang.search_filter }}</h2>

	        <input type="hidden" name="filter" value="1">
	        <input type="hidden" name="csrf_token" value="{{ csrf_token }}">

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
					<label for="">{{ lang.search_title }}</label>
					<input type="text" value="{{REQUEST.page_title|stripslashes|escape}}" class="form-control" name="page_title" placeholder="{{ lang.search_title }}">
				</div>
			</div>

			<div class="col-lg-4 col-md-6 col-sm-6">
				<div class="form-group">
					<label for="">{{ lang.search_status }}</label>
					<div class="clearfix"></div>
					<select class="selectpicker" name="page_status">
						<option value=""></option>
						{% for status, title in page_status %}
							<option value="{{status}}" {% if REQUEST.page_status == status %}selected{% endif %} >{{title}}</option>
						{% endfor %}
					</select>
				</div>

				{% if folders %}
					<div class="form-group">
						<label for="">{{ lang.page_folder_name }}</label>
						<div class="clearfix"></div>
						<select class="selectpicker" name="page_folder">
							<option value=""></option>
							{% for folder in folders %}
								<option value="{{folder.folder_id}}" {% if REQUEST.page_folder == folder.folder_id %}selected{% endif %}>{{folder.folder_title}}</option>
							{% endfor %}
						</select>
					</div>
				{% endif %}

				<div class="form-group">
					<label for="">{{ lang.search_owner }}</label>
					<div class="clearfix"></div>
					<select class="selectpicker" name="page_owner_id">
						<option value=""></option>
						{% for main_user in main_users %}
							<option value="{{main_user.id}}" {% if main_user.id==REQUEST.page_owner_id %}selected{% endif %} >{{main_user.user_name}}</option>
						{% endfor %}
					</select>
				</div>
			</div>

			<div class="col-lg-4 col-md-12 col-sm-12">

			</div>

			<div class="clearfix"></div>
	        <div class="actions">
				<input type="submit" class="btn-flat gray" value="{{ lang.search_btn }}">

				{% if REQUEST.filter %}
		            <div class="filter_clear">
		                &nbsp;<a href="{{ ABS_PATH_ADMIN_LINK }}?do=module&sub=mod_edit&module_tag=page&module_action=page_start"><i class="fa fa-times"></i> {{ lang.search_clear_btn }}</a>
		            </div>
		        {% endif %}
	        </div>
	    </form>
	</div>

    <div class="content-wrapper">
        {% if pages %}
            <form class="form-inline" role="form" enctype="multipart/form-data" method="post" action="{{ ABS_PATH_ADMIN_LINK }}?do=module&sub=mod_edit&module_tag=page&module_action=mass_change">
                <input type="hidden" name="csrf_token" value="{{ csrf_token }}">

                <table class="datatables table-striped">
                    <thead>
                        <tr>
                            <th class="col-md-1">
                                &nbsp;
                            </th>
                            <th class="col-md-2">
                                {{ lang.page_title }}
                            </th>
                            <th class="col-md-2">
                                {{ lang.page_lang }}
                            </th>
                            <th class="col-md-2">
                                {{ lang.page_folder_name }}
                            </th>
                            <th class="col-md-2">
                                {{ lang.page_reg }}
                            </th>
                            <th class="col-md-2">
                                {{ lang.page_owner }}
                            </th>
                            <th class="col-md-1">
                                &nbsp
                            </th>
                        </tr>
                    </thead>
                    <tbody class="table_page">
                        {% for page in pages %}
                            <tr>
                                <td>
                                    <div class="checkbox checkbox-primary">
                                        <input id="checkbox{{page.page_id}}" type="checkbox" name="elem_opt[{{page.page_id}}]" value="{{page.page_id}}">
                                        <label for="checkbox{{page.page_id}}"></label>
                                    </div>
                                </td>

                                <td>
                                    <a href="{{ ABS_PATH_ADMIN_LINK }}?do=module&sub=mod_edit&module_tag=page&module_action=page_work&page_id={{page.page_id}}">{{page.page_title|escape|stripslashes}}</a>
									<br>
									{% if page.page_index == 1 %}<i class="fa fa-star" aria-hidden="true"></i>&nbsp{% endif %}
									<i class="fa fa-external-link" aria-hidden="true"></i>&nbsp<a href="{{ HOST_NAME }}/{{page.page_alias|escape|stripslashes}}" target="_blank">{{ HOST_NAME }}/{{page.page_alias|escape|stripslashes}}</a>
                                </td>

                                <td>
                                    {{page.page_lang|escape|stripslashes}}
                                </td>

                                <td>
                                    {{page.page_folder.folder_title|escape|stripslashes}}
                                </td>

                                <td>
                                    {{page.page_add}}
                                </td>

                                <td>
                                    <a href="{{ ABS_PATH_ADMIN_LINK }}?do=profile&sub=open&user_id={{page.page_owner.id}}">{{page.page_owner.user_name}}</a>
                                </td>

                                <td>
                                    {% if SESSION.alles or page.page_owner == SESSION.user_id %}
                                        <a href="{{ ABS_PATH_ADMIN_LINK }}?do=module&sub=mod_edit&module_tag=page&module_action=page_delete&page_id={{page.page_id}}" class="confirm" data-toggle="tooltip" data-placement="top" title="{{ lang.save_delete }}"><i class="fa fa-times"></i></a>
                                    {% endif %}
                                </td>
                            </tr>
                        {% endfor %}
                    </tbody>
					<tfoot>
						<tr>
							<th colspan="7">
								<a href="#" class="select_all_checkbox" data-target="table_page">{{ lang.form_select_all }}</a>

								<ul class="list-inline mgtb20">
				                    <li>
				                        <select class="selectpicker" name="operation">
				                            <option value="">{{ lang.form_select_mass }}</option>
				                            <option value="1">{{ lang.page_delete }}</option>
				                            <option value="2">{{ lang.page_status_active }}</option>
				                            <option value="3">{{ lang.page_status_no_active }}</option>
				                            <option value="5" data-show="change_group">{{ lang.page_change_folder }}</option>
				                        </select>
				                    </li>
				                    <li>
				                        <div class="show_field change_group">
				                            <select class="selectpicker" name="page_folder">
				                                <option value=""></option>
				                                {% for folder in folders %}
				                                    <option value="{{folder.folder_id}}">{{folder.folder_title}}</option>
				                                {% endfor %}
				                            </select>
				                        </div>
				                    </li>
				                </ul>

				                <input type="submit" class="btn-flat gray" value="{{ lang.save_data }}">
							</th>
						</tr>
					</tfoot>
                </table>
            </form>

            {% if page_nav %}
                {{page_nav}}
            {% endif %}
        {% else %}
            <p>{{ lang.empty_data }}</p>
        {% endif %}
    </div>
</div>
