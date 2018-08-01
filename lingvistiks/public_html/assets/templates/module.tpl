<div class="menubar">
    <div class="page-title">
        <h1>{{ lang.module_name }}</h1>
    </div>
</div>

<div class="datatables">
    <div class="content-wrapper">

        {% if module_list %}
			<div class="table-responsive">
				<table class="datatables table-striped">
					<thead>
						<tr>
							<th class="col-md-4">
								{{ lang.module_title }}
							</th>
							<th class="col-md-4">
								{{ lang.module_desc }}
							</th>
							<th class="col-md-2">
								{{ lang.module_status }}
							</th>
							{% if SESSION.alles or SESSION.module_install_access %}
							<th class="col-md-2">
								&nbsp
							</th>
							{% endif %}
						</tr>
					</thead>
					<tbody>
		                {% for module in module_list %}
							<tr>
								<td>
									{% if module.module_status == 1 and module.module_settings == 1 %}
										<a href="{{ ABS_PATH_ADMIN_LINK }}?do=module&sub=mod_edit&module_tag={{ module.module_tag|escape|stripslashes }}&module_action={{ module.module_tag|escape|stripslashes }}_start">
									{% endif %}
										{{ module.module_name|escape|stripslashes }}
									{% if module.module_status == 1 and module.module_settings == 1 %}
										</a>
									{% endif %}
								</td>
								<td>{{ module.module_desc|escape|stripslashes }}</td>
								<td class="{% if module.module_status == 1 %}text-success{% else %}text-danger{% endif %}">{% if module.module_status == 1 %}{{ lang.module_active }}{% else %}{{ lang.module_no_active }}{% endif %}</td>

								{% if SESSION.alles or SESSION.module_install_access %}
									<td>
										<ul class="list-inline">
											{% if module.module_status == 1 and SESSION.alles %}
											<li><a href="" class="get_ajax_form" data-void="{{ module.module_id|escape|stripslashes }}" data-essense="" data-type="module" data-sub="module_settings" data-ajax="1">{{ lang.module_settings }}</a></li>
											{% endif %}

											{% if module.module_status != 1 %}
												<li><a href="{{ ABS_PATH_ADMIN_LINK }}?do=module&sub=module_install&module_tag={{ module.module_tag|escape|stripslashes }}" class="confirm">{{ lang.module_install }}</a></li>
											{% else %}
												<li><a href="{{ ABS_PATH_ADMIN_LINK }}?do=module&sub=module_uninstall&module_tag={{ module.module_tag|escape|stripslashes }}" class="confirm">{{ lang.module_uninstall }}</a></li>
											{% endif %}
										</ul>
									</td>
								{% endif %}
							</tr>
		                {% endfor %}
					</tbody>
				</table>
			</div>
        {% else %}
            <p>{{lang.empty_data}}</p>
        {% endif %}

    </div>
</div>
