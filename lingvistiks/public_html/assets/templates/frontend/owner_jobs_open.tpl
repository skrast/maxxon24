<main class="sidebar-right">
	<div class="container">

		<ol class="breadcrumb">
			<li><a href="{{ HOST_NAME }}">{{ lang.page_breadcrumb }}</a></li>
			<li><a href="{{ HOST_NAME }}/bank_vakansiy/">{{ lang.jobs_list }}</a></li>
			<li class="active text-uppercase">{{ jobs_info.jobs_title|escape|stripslashes }}</li>
        </ol>

		<h1>{{ lang.jobs_page }}# {{ jobs_info.jobs_id }}</h1>

		<div class="row">
			{{ perfomens_col }}

			<div class="col-md-{% if perfomens_col %}9{% else %}12{% endif %} col-sm-{% if perfomens_col %}9{% else %}12{% endif %}">
				<div class="content-right">
					<div class="profile-block">
						<div class="row">
							<div class="col-md-8">
								<div class="content-left jobs_page">

									<div class="row">
										<div class="col-md-6">
											<h2>{{ jobs_info.jobs_title.title|escape|stripslashes }}</h2>
											<p><strong>{{ jobs_info.jobs_company_title|escape|stripslashes }}, {{ jobs_info.jobs_country.title|escape|stripslashes }}, {{ jobs_info.jobs_city.title|escape|stripslashes }}</strong></p>

											<p class="date">
												{% if jobs_info.jobs_status == 1 %}
													{{ lang.jobs_update }} {% if jobs_info.jobs_edit %}{{ jobs_info.jobs_edit }}{% else %}{{ jobs_info.jobs_add }}{% endif %}
												{% else %}
													{{ lang.jobs_draft }}
												{% endif %}
											</p>
										</div>
										<div class="col-md-6 text-right jobs_coast_color">
											{% if jobs_info.jobs_coast_start %} {{ lang.lk_budget_from }} {{ jobs_info.jobs_coast_start|escape|stripslashes }} {% endif %}
											{% if jobs_info.jobs_coast_end %} {{ lang.lk_budget_to }} {{ jobs_info.jobs_coast_end|escape|stripslashes }} {% endif %}

											{{ jobs_info.jobs_coast_currency.title|escape|stripslashes }}/{% if jobs_info.jobs_coast_period == 1 %}{{ lang.jobs_coast_month }}{% else %}{{ lang.jobs_coast_year }}{% endif %}
											<br>
											<div class="jobs_gross">Gross</div>
										</div>
									</div>

									
									<br><p>{{ jobs_info.jobs_company_desc|escape|stripslashes|ntobr }}</p><br>
									<div class="clearfix"></div>

									<h3>{{ lang.jobs_add_title }}</h3>

									<div class="row colored">
										<div class="col-md-6">
											{{ lang.jobs_stage }}
										</div>
										<div class="col-md-6">
											{{ jobs_info.jobs_stage|escape|stripslashes }} {{ lang.jobs_stage_year }}
										</div>
									</div>

									<div class="row colored">
										<div class="col-md-6">
											{{ lang.jobs_level_education }}
										</div>
										<div class="col-md-6">
											{% if jobs_info.jobs_level_education_ext %}
												{{ jobs_info.jobs_level_education_ext|escape|stripslashes }}
											{% else %}
												{{ jobs_info.jobs_level_education.title|escape|stripslashes }}
											{% endif %}
										</div>
									</div>

									<div class="row colored">
										<div class="col-md-6">
											{{ lang.jobs_default_lang }}
										</div>
										<div class="col-md-6">
											{{ jobs_info.jobs_default_lang.title|escape|stripslashes }} 
										</div>
									</div>

									<div class="row colored">
										<div class="col-md-6">
											{{ lang.jobs_skill_lang }}
										</div>
										<div class="col-md-6">
											<ul class="list-unstyled">
												{% for jobs_skill_lang in jobs_info.jobs_skill_lang %}
													<li>{{ lang_list[jobs_skill_lang.lang_id].title|escape|stripslashes }} - {{ lang_level_list[jobs_skill_lang.lang_level].title|escape|stripslashes }}</li>
												{% endfor %}
											</ul>
										</div>
									</div>


									<div class="row colored">
										<div class="col-md-6">
											{{ lang.jobs_terms_employment }}
										</div>
										<div class="col-md-6">
											<ul class="list-unstyled">
												{% for lang in jobs_info.jobs_terms_employment %}
													<li>{{ lang.title|escape|stripslashes }}</li>
												{% endfor %}
											</ul>
										</div>
									</div>

									<div class="row colored">
										<div class="col-md-6">
											{{ lang.jobs_work_place }}
										</div>
										<div class="col-md-6">
											<ul class="list-unstyled">
												{% for lang in jobs_info.jobs_work_place %}
													<li>{{ lang.title|escape|stripslashes }}</li>
												{% endfor %}
											</ul>
										</div>
									</div>

									<div class="row colored">
										<div class="col-md-6">
											{{ lang.jobs_employment_conditions }}
										</div>
										<div class="col-md-6">
											<ul class="list-unstyled">
												{% for lang in jobs_info.jobs_employment_conditions %}
													<li>{{ lang.title|escape|stripslashes }}</li>
												{% endfor %}
											</ul>
										</div>
									</div>

									<div class="row colored">
										<div class="col-md-6">
											{{ lang.jobs_work_time }}
										</div>
										<div class="col-md-6">
											<ul class="list-unstyled">
												{% for lang in jobs_info.jobs_work_time %}
													<li>{{ lang.title|escape|stripslashes }}</li>
												{% endfor %}
											</ul>
										</div>
									</div>

									<div class="row colored">
										<div class="col-md-6">
											{{ lang.jobs_desc }}
										</div>
										<div class="col-md-6">
											<p>{{ jobs_info.jobs_desc|escape|stripslashes|ntobr }}</p>
										</div>
									</div>

									<div class="row colored">
										<div class="col-md-6">
											{{ lang.jobs_responsibilities }}
										</div>
										<div class="col-md-6">
											<p>{{ jobs_info.jobs_responsibilities|escape|stripslashes|ntobr }}</p>
										</div>
									</div>

									<div class="row colored">
										<div class="col-md-6">
											{{ lang.jobs_requirements }}
										</div>
										<div class="col-md-6">
											<p>{{ jobs_info.jobs_requirements|escape|stripslashes|ntobr }}</p>
										</div>
									</div>

									<div class="row colored">
										<div class="col-md-6">
											{{ lang.jobs_terms }}
										</div>
										<div class="col-md-6">
											<p>{{ jobs_info.jobs_terms|escape|stripslashes|ntobr }}</p>
										</div>
									</div>

									<div class="row colored hidden">
										<div class="col-md-6">
											{{ lang.jobs_link }}
										</div>
										<div class="col-md-6">
											<input type="text" class="form-control" value="{{ HOST_NAME }}/jobs-{{ jobs_info.jobs_id }}/" readonly>
										</div>
									</div>

									<div class="row">
										{% if SESSION.user_id != jobs_info.jobs_owner and SESSION.user_group == 3 and jobs_info.jobs_status == 1 %}
											<div class="col-md-6">
												<a href="" class="btn btn-block btn-search perfomens_submit get_ajax_form" data-ajax="1" data-type="siteJobs" data-sub="jobs_access" data-void="{{ jobs_info.jobs_id }}" data-close="1">{{ lang.jobs_perfomens_submit }}</a>
											</div>
											<div class="col-md-6">
												<a href="{{ HOST_NAME }}/jobs/print-{{ jobs_info.jobs_id }}/" class="btn btn-block btn-search perfomens_submit hidden">{{ lang.lk_print }}</a>
											</div>
										{% elseif SESSION.user_id == jobs_info.jobs_owner %}
											<div class="col-md-3">
												<a href="{{ HOST_NAME }}/jobs/update-{{ jobs_info.jobs_id }}/" class="btn btn-block btn-search perfomens_submit">{{ lang.jobs_update_lnk }}</a>
											</div>
											<div class="col-md-3">
												<a href="{{ HOST_NAME }}/jobs/edit-{{ jobs_info.jobs_id }}/" class="btn btn-block btn-search perfomens_submit">{{ lang.jobs_edit }}</a>
											</div>
											<div class="col-md-3">
												{% if jobs_info.jobs_status == 1 %}
													<a href="{{ HOST_NAME }}/jobs-{{ jobs_info.jobs_id }}/close/" class="btn btn-block btn-search perfomens_submit">{{ lang.jobs_close }}</a>
												{% else %}
													<a href="{{ HOST_NAME }}/jobs-{{ jobs_info.jobs_id }}/open/" class="btn btn-block btn-search perfomens_submit">{{ lang.jobs_open }}</a>
												{% endif %}
											</div>

											<div class="col-md-3">
												{% if jobs_info.jobs_status != 1 %}
													<a href="{{ HOST_NAME }}/jobs/delete-{{ jobs_info.jobs_id }}/" class="btn btn-block btn-search perfomens_submit">{{ lang.jobs_delete }}</a>
												{% else  %}
													<a href="{{ HOST_NAME }}/jobs/print-{{ jobs_info.jobs_id }}/" class="btn btn-block btn-search perfomens_submit hidden">{{ lang.lk_print }}</a>
												{% endif %}
											</div>
										{% endif %}
									</div>
								</div>
							</div>
				
							<div class="col-md-4">
								<div class="sidebar">
				
									{% if SESSION.user_id %}<a href="{{ HOST_NAME }}/profile-{{ jobs_info.jobs_owner }}/">{% endif %}
										<img src="{{ ABS_PATH }}?thumb={{ app.app_upload_dir }}/users/{{ jobs_info.user_photo }}&width=100&height=100" alt="" class="avatar img-circle">
									{% if SESSION.user_id %}</a>{% endif %}
									<p>{{ jobs_info.user_name|escape|stripslashes }}<br>{{ jobs_info.full_user_id }}</p>
				
									{% if jobs_list %}
										<div class="sidebar-block sidebar-block-1">
											<h3>{{ lang.jobs_owner_list }}</h3>
											<ul class="list-unstyled">
												{% for jobs in jobs_list %}
													<li>
														<h4><a href="{{ HOST_NAME }}/jobs-{{ jobs.jobs_id }}/">{{ jobs.jobs_title.title|escape|stripslashes }}</a></h4>
														<span class="date">{{ jobs.jobs_add }}</span>
													</li>
												{% endfor %}
											</ul>
											<a href="{{ HOST_NAME }}/bank_vakansiy/?jobs_owner={{ jobs_info.jobs_owner }}" class="btn btn-block btn-go-on">{{ lang.jobs_all_list }}</a>
										</div>
									{% endif %}
				
									{% if jobs_more_list %}
										<div class="sidebar-block sidebar-block-1 sidebar-block-2">
											<h3>{{ lang.jobs_owner_more_list }}</h3>
											<ul class="list-unstyled">
												{% for jobs in jobs_more_list %}
													<li>
														<h4><a href="{{ HOST_NAME }}/jobs-{{ jobs.jobs_id }}/">{{ jobs.jobs_title.title|escape|stripslashes }}</a></h4>
														<span class="date">{{ jobs.jobs_add }}</span>
													</li>
												{% endfor %}
											</ul>
											<a href="{{ HOST_NAME }}/bank_vakansiy/" class="btn btn-block btn-go-on">{{ lang.jobs_all_list }}</a>
										</div>
									{% endif %}
								</div>
							</div>
						</div>

					</div>
				</div>
			</div>
		</div>

	</div>
</main>
