<div class="menubar">
    <div class="page-title">
		{% if lang_default %}
			<a href="{{ ABS_PATH_ADMIN_LINK }}?do=module&sub=mod_edit&module_tag=import&module_action=import_start" class="bread"><i class="fa fa-angle-double-left"></i></a>
		{% else %}
			<a href="{{ ABS_PATH_ADMIN_LINK }}?do=module" class="bread"><i class="fa fa-angle-double-left"></i></a>
		{% endif %}

        <h1>{{ lang.import_name }}</h1>

		<div class="clearfix"></div>
    </div>
</div>

<div class="datatables">
    <div class="content-wrapper sendmail">

		<div class="row">
			<div class="col-lg-3 col-md-4">
				<div class="block-margin-n20">
					<h1>{{ lang.import_xlsx_file }}</h1>
					<form role="form" enctype="multipart/form-data" method="post" action="{{ ABS_PATH_ADMIN_LINK }}?do=module&sub=mod_edit&module_tag=import&module_action=import_start_parser" data-ajax="1">
						<input type="hidden" name="save" value="1">
						<input type="hidden" name="csrf_token" value="{{ csrf_token }}">

						<ul class="list-unstyled">
							<li>
								<div class="fileinput-button">
									<div class="btn-flat btn-file">
										<i class="fa fa-fw fa-upload"></i>&nbsp
										<span>{{ lang.select_file }}</span>
										<input name="file_name" type="file">
									</div>
									<span class="file_name"></span>
								</div>
							</li>
							<li>
								<input type="submit" class="btn-flat gray" value="{{ lang.save_data }}">
							</li>
						</ul>
					</form>
				</div>

				<div class="block-margin-n20">
					<h1>{{ lang.import_xlsx_to_file }}</h1>
					<br>
					<a href="{{ ABS_PATH_ADMIN_LINK }}?do=module&sub=mod_edit&module_tag=import&module_action=import_start_export" class="btn-flat gray">
						{{ lang.import_xlsx_to_file_download }}
					</a>
				</div>
			</div>

			<div class="col-lg-9 col-md-8">

				<ul class="list-inline">
					{% if REQUEST.country_id %}
						<li>
							<a href="{{ ABS_PATH_ADMIN_LINK }}?do=module&sub=mod_edit&module_tag=import&module_action=import_start" class="bread">{{ lang.import_country }}</a>
						</li>
					{% endif %}

					{% if country_info %}
						<li>
							<a href="{{ ABS_PATH_ADMIN_LINK }}?do=module&sub=mod_edit&module_tag=import&module_action=import_start&country_id={{ country_info.country_id }}" class="bread">{{ country_info.country_title_short|escape|stripslashes }}</a>
						</li>
					{% endif %}

					{% if city_info %}
						<li>
							<a href="{{ ABS_PATH_ADMIN_LINK }}?do=module&sub=mod_edit&module_tag=import&module_action=import_start&country_id={{ country_info.country_id }}&city_id={{ city.city_id }}" class="bread">{{ city_info.city_title|escape|stripslashes }}</a>
						</li>
					{% endif %}
				</ul>

				
				{% if country_list %}
					<h1>{{ lang.import_country }}</h1>
					<div class="table-responsive">
						<table class="datatables table-striped">
							<tbody>
								{% for country in country_list %}
									<tr>
										<td>
											{{ country.country_id }}
										</td>
										<td>
											{{ country.country_abbr }}
										</td>
										<td>
											<a href="{{ ABS_PATH_ADMIN_LINK }}?do=module&sub=mod_edit&module_tag=import&module_action=import_start&country_id={{ country.country_id }}">{{ country.country_title_short|escape|stripslashes }}</a>
										</td>
										<td>
											<ul class="list-inline">
												{% if SESSION.alles %}
													<li>
														<a href="{{ ABS_PATH_ADMIN_LINK }}?do=module&sub=mod_edit&module_tag=import&module_action=delete_country&country_id={{ country.country_id }}" class="confirm"><i class="fa fa-times"></i></a>
													</li>
												{% endif %}
											</ul>
		
										</td>
									</tr>
								{% endfor %}
							</tbody>
						</table>
					</div>
				{% endif %}		

				{% if city_list %}
					<h1>{{ lang.import_city }}</h1>
					<div class="table-responsive">
						<table class="datatables table-striped">
							<tbody>
								{% for city in city_list %}
									<tr>
										<td>
											{{ city.city_id }}
										</td>
										<td>
											<a href="{{ ABS_PATH_ADMIN_LINK }}?do=module&sub=mod_edit&module_tag=import&module_action=import_start&country_id={{ city.city_country }}&city_id={{ city.city_id }}">{{ city.city_title|escape|stripslashes }}</a>
										</td>
										<td>
											<ul class="list-inline">
												{% if SESSION.alles %}
													<li>
														<a href="{{ ABS_PATH_ADMIN_LINK }}?do=module&sub=mod_edit&module_tag=import&module_action=delete_city&city_id={{ city.city_id }}&country_id={{ city.city_country }}" class="confirm"><i class="fa fa-times"></i></a>
													</li>
												{% endif %}
											</ul>
		
										</td>
									</tr>
								{% endfor %}
							</tbody>
						</table>
					</div>
				{% endif %}


				{% if metro_list %}
					<h1>{{ lang.import_metro }}</h1>
					<div class="table-responsive">
						<table class="datatables table-striped">
							<tbody>
								{% for metro in metro_list %}
									<tr>
										<td>
											{{ metro.metro_id }}
										</td>
										<td>
											{{ metro.metro_title|escape|stripslashes }}
										</td>
										<td>
											<ul class="list-inline">
												{% if SESSION.alles %}
													<li>
														<a href="{{ ABS_PATH_ADMIN_LINK }}?do=module&sub=mod_edit&module_tag=import&module_action=delete_metro&metro_id={{ metro.metro_id }}&country_id={{ country_info.country_id }}&city_id={{ city_info.city_id }}" class="confirm"><i class="fa fa-times"></i></a>
													</li>
												{% endif %}
											</ul>
		
										</td>
									</tr>
								{% endfor %}
							</tbody>
						</table>
					</div>
				{% endif %}

			</div>
		</div>
	</div>
</div>
