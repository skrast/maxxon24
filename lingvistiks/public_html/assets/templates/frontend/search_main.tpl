<main class="no-sidebar">
	<div class="container">

		<ol class="breadcrumb">
			<li><a href="{{ HOST_NAME }}">{{ lang.page_breadcrumb }}</a></li>
			<li class="active text-uppercase">{{ lang.search_main_title }}</li>
		</ol>

		<h1>{{ lang.search_main_title|escape|stripslashes }}</h1>

		<div class="row">
			{{ perfomens_col }}

			<div class="col-md-{% if perfomens_col %}9{% else %}12{% endif %} col-sm-{% if perfomens_col %}9{% else %}12{% endif %}">
				<div class="content-right">
					<div class="profile-block">

						<div class="order-nav">
							<ul class="list-inline order_change">
								{% for key, value in search_main_array_type %}
									<li>
										<a href="{{ ABS_PATH }}search{% if key != 1 %}/type-{{ key }}/{% endif %}{% if REQUEST.search %}?search={{ REQUEST.search|escape|stripslashes }}{% endif %}" {% if key == search_type_active %}class="active"{% endif %}>{{ value|escape|stripslashes }}</a>
									</li>
								{% endfor %}
							</ul>
						</div>

						<form action="{{ ABS_PATH }}search{% if search_type_active %}/type-{{ search_type_active }}/{% endif %}" method="post" class="" enctype="multipart/form-data">
							<input type="hidden" name="csrf_token" value="{{ csrf_token }}">
							<input type="hidden" name="search_type" value="{{ search_type_active }}">
							
							<div class="row">
								<div class="col-md-9">
									<input type="search" name="search" class="form-control" value="{{ REQUEST.search|escape|stripslashes }}" placeholder="{{ lang.search_main_title_placeholder }}">
								</div>
								<div class="col-md-3 pull-right">
									<button type="submit" class="btn btn-block btn-go-on">{{ lang.btn_search }}</button>
								</div>
							</div>
						</form>

						<div class="row">
							<div class="performers-review search-resilt">

								{% if users_list %}
									<div class="col-md-12"><h2>{{ lang.search_main_array_type[2] }}</h2></div>
									<div class="clearfix"></div>

									{% for perfomer in users_list %}
									<div class="col-md-6">
										<div class="perfomers-card {% if SESSION.user_id %}normal{% else %}guest{% endif %}">
											<div class="row">
												<div class="col-md-{% if SESSION.user_id %}5{% else %}4{% endif %}">
													<img src="{{ ABS_PATH }}?thumb={{ app.app_upload_dir }}/users/{{ perfomer.user_photo }}&width=250&height=300" alt="" class="img-responsive">
												</div>
												<div class="col-md-{% if SESSION.user_id %}7{% else %}8{% endif %} perfomers-info">
													{% if not SESSION.user_id %}<div class="pull-right">{{ perfomer.user_rating_small_tpl }}</div>{% endif %}
													<h4>{{ perfomer.user_login|escape|stripslashes }} </h4>

													<div class="clearfix"></div>
													<div class="perfomers-dop-info">
														<p><strong>
															{% for country in country_list %}
																{% if country.id == perfomer.user_country %}{{ country.title|escape|stripslashes }}{% endif %}
															{% endfor %}
															{% for city in perfomer.city_list %}
																{% if city.id == perfomer.user_city %}, {{ city.title|escape|stripslashes }}{% endif %}
															{% endfor %}
														</strong></p>

														<div class="plate1">{{ lang.lk_lang_default }}
															{% if perfomer.user_default_lang %}{{ perfomer.user_default_lang.title|escape|stripslashes }}{% else %}{{ lang.lk_user_default_lang_empty}}{% endif %}
														</div>

														{% if perfomer.lang_var %}
															<div class="plate2">{{ lang.lk_lang_var }}</div>
															<ul class="plate2-list list-unstyled">
																{% for lang in perfomer.lang_var|slice(0, 3) %}
																	<li>
																		{{ lang.serv_lang_from.title|escape|stripslashes }} - {{ lang.serv_lang_to.title|escape|stripslashes }}
																	</li>
																{% endfor %}
															</ul>
														{% endif %}

													</div>

													{% if not SESSION.user_id %}<a href="{{ HOST_NAME }}{{ link_lang_pref }}/profile-{{ perfomer.id|escape|stripslashes }}/" class="btn btn-block btn-go-on">{{ lang.btn_detail }}</a>{% endif %}

												</div>
											</div>

											{% if SESSION.user_id %}
												<div class="row">
													<div class="col-md-{% if SESSION.user_id %}5{% else %}4{% endif %}">
														{{ perfomer.user_rating_tpl }}
													</div>
													<div class="col-md-{% if SESSION.user_id %}7{% else %}8{% endif %}">
														<a href="{{ HOST_NAME }}{{ link_lang_pref }}/profile-{{ perfomer.id|escape|stripslashes }}/" class="btn btn-block btn-go-on">{{ lang.btn_detail }}</a>
													</div>
												</div>
											{% endif %}
										</div>
									</div>
									{% endfor %}

									{% if search_type_active == 1 %}
										<div class="col-md-offset-8 col-md-4">
											<div class="clearfix"></div>
											<a href="{{ ABS_PATH }}search/type-2/{% if REQUEST.search %}?search={{ REQUEST.search|escape|stripslashes }}{% endif %}" class="btn btn-block btn-go-on">{{ lang.search_main_array_btn[2] }} </a>	
										</div>
									{% endif %}	
					
									{% if page_nav %}
										{{ page_nav }}
									{% endif %}
								{% endif %}


								{% if jobs_list %}
									<div class="col-md-12"><h2>{{ lang.search_main_array_type[3] }}</h2></div>
									<div class="clearfix"></div>

									<div class="list-job">
										{% for jobs_info in jobs_list %}
											<div class="list-job-item">

												<div class="row bg">
													<div class="col-md-6">
														<h3>{{ jobs_info.jobs_title.title|escape|stripslashes }}</h3>
													</div>
													<div class="col-md-6 text-right jobs_coast_color">
														{% if jobs_info.jobs_coast_start %} {{ lang.lk_budget_from }} {{ jobs_info.jobs_coast_start|escape|stripslashes }} {% endif %}
														{% if jobs_info.jobs_coast_end %} {{ lang.lk_budget_to }} {{ jobs_info.jobs_coast_end|escape|stripslashes }} {% endif %}

														{{ jobs_info.jobs_coast_currency.title|escape|stripslashes }}/{% if jobs_info.jobs_coast_period == 1 %}{{ lang.jobs_coast_month }}{% else %}{{ lang.jobs_coast_year }}{% endif %}

														<span class="jobs_gross">Gross</span>
													</div>
												</div>

												<div class="row">
													<div class="col-md-4">
														<p><strong>{{ jobs_info.jobs_company_title|escape|stripslashes }}<br> {{ jobs_info.jobs_country.title|escape|stripslashes }}, {{ jobs_info.jobs_city.title|escape|stripslashes }}</strong></p>
													</div>

													<div class="col-md-8">
														<div class="row">
															<div class="col-md-6 text-right">
																<strong>{{ lang.jobs_default_lang }}</strong>
															</div>
															<div class="col-md-6 color">
																{{ jobs_info.jobs_default_lang.title|escape|stripslashes }} 
															</div>
															<div class="clearfix"></div>
															<div class="col-md-6 text-right">
																<strong>{{ lang.jobs_skill_lang }}</strong>
															</div>
															<div class="col-md-6 color">
																{{ jobs_info.jobs_skill_lang_title|implode_array(",") }}
															</div>
														</div>
													</div>

													<div class="clearfix"></div>

													<div class="col-md-8">
														<p class="date">
															{% if jobs_info.jobs_status == 1 %}
																{{ lang.jobs_update }} {% if jobs_info.jobs_edit %}{{ jobs_info.jobs_edit }}{% else %}{{ jobs_info.jobs_add }}{% endif %}
															{% else %}
																{{ lang.jobs_draft }}
															{% endif %}
														</p>
													</div>
													<div class="col-md-4">
														<a href="{{ HOST_NAME }}/jobs-{{ jobs_info.jobs_id }}/" class="btn btn-go-on" style="width:120px;">{{ lang.btn_detail }}</a>
													</div>
													
												</div>
											</div>
										{% endfor %}
									</div>

									{% if search_type_active == 1 %}
										<div class="col-md-offset-8 col-md-4">
											<div class="clearfix"></div>
											<a href="{{ ABS_PATH }}search/type-3/{% if REQUEST.search %}?search={{ REQUEST.search|escape|stripslashes }}{% endif %}" class="btn btn-block btn-go-on">{{ lang.search_main_array_btn[3] }} </a>	
										</div>
									{% endif %}	

									{% if page_nav %}
										{{ page_nav }}
									{% endif %}
								{% endif %}


								{% if resume_list %}
									<div class="col-md-12"><h2>{{ lang.search_main_array_type[4] }}</h2></div>
									<div class="clearfix"></div>

									<div class="list-job">
										{% for resume_info in resume_list %}
											<div class="list-job-item">
			
												<div class="row bg">
													<div class="col-md-6">
														<h3>{{ resume_info.resume_title.title|escape|stripslashes }}</h3>
													</div>
													<div class="col-md-6 text-right jobs_coast_color">
														{% if resume_info.resume_coast_start %} {{ lang.lk_budget_from }} {{ resume_info.resume_coast_start|escape|stripslashes }} {% endif %}
														{% if resume_info.resume_coast_end %} {{ lang.lk_budget_to }} {{ resume_info.resume_coast_end|escape|stripslashes }} {% endif %}
			
														{{ resume_info.resume_coast_currency.title|escape|stripslashes }}/{% if resume_info.resume_coast_period == 1 %}{{ lang.jobs_coast_month }}{% else %}{{ lang.resume_coast_year }}{% endif %}
			
														<span class="jobs_gross">Gross</span>
													</div>
												</div>
			
												<div class="row">
													<div class="col-md-3 text-center">
														{% if resume_info.resume_photo %}
															<img src="{{ ABS_PATH }}?thumb={{ app.app_upload_dir }}/{{ app.app_resume_dir }}/{{ resume_info.resume_photo }}&width=250&height=300" alt="" class="img-responsive">
														{% else %}
															{% if resume_info.user_photo %}
																<img src="{{ ABS_PATH }}?thumb={{ app.app_upload_dir }}/{{ app.app_users_dir }}/{{ resume_info.user_photo }}&width=250&height=300" alt="" class="img-responsive">
															{% else %}
																<img src="{{ ABS_PATH }}assets/site/template/images/no-photo.png" alt="" class="img-responsive">
															{% endif %}
														{% endif %}
													</div>
													
													<div class="col-md-9">
														<h2>{{ resume_info.resume_lastname|escape|stripslashes }} {{ resume_info.resume_firstname|escape|stripslashes }} {{ resume_info.resume_patronymic|escape|stripslashes }}</h2>
			
														<p><strong>{{ resume_info.resume_birthday|format_date }}</strong></p>
			
														<div class="row">
															<div class="col-md-6 text-black">
																<strong>{{ lang.profile_from }}</strong>
															</div>
															<div class="col-md-6 color">
																{{ resume_info.resume_country.title|escape|stripslashes }}, {{ resume_info.resume_city.title|escape|stripslashes }}
															</div>
														</div>
														<div class="row">
															<div class="col-md-6 text-black">
																<strong>{{ lang.profile_citizenship }}</strong>
															</div>
															<div class="col-md-6 color">
																{{ resume_info.resume_citizenship.title|escape|stripslashes }}
															</div>
														</div>
			
														<div class="row">
															<div class="col-md-6">
																<strong>{{ lang.jobs_default_lang }}</strong>
															</div>
															<div class="col-md-6 color">
																{{ resume_info.user_lang_default.title|escape|stripslashes }} 
															</div>
														</div>
														
														<div class="row">
															<div class="col-md-6">
																<strong>{{ lang.resume_dop_info_lang }}</strong>
															</div>
															<div class="col-md-6 color">
																{{ resume_info.resume_skill_lang_title|implode_array(",") }}
															</div>
														</div>
			
														<div class="row">
															<div class="col-md-6">
																<p class="date">
																	{% if resume_info.resume_status == 1 %}
																		{{ lang.jobs_update }} {{ resume_info.resume_update|format_date }}
																	{% else %}
																		{{ lang.jobs_draft }}
																	{% endif %}
																</p>
															</div>
															<div class="col-md-6 color">
																<a href="{{ HOST_NAME }}/resume-{{ resume_info.resume_owner }}/" class="btn btn-go-on" style="width:120px;">{{ lang.btn_detail }}</a>
															</div>
														</div>
			
													</div>										
												</div>
											</div>
										{% endfor %}
									</div>

									{% if search_type_active == 1 %}
										<div class="col-md-offset-8 col-md-4">
											<div class="clearfix"></div>
											<a href="{{ ABS_PATH }}search/type-4/{% if REQUEST.search %}?search={{ REQUEST.search|escape|stripslashes }}{% endif %}" class="btn btn-block btn-go-on">{{ lang.search_main_array_btn[4] }} </a>	
										</div>
									{% endif %}	

									{% if page_nav %}
										{{ page_nav }}
									{% endif %}
								{% endif %}

								{% if news_list %}
									<div class="col-md-12"><h2>{{ lang.search_main_array_type[5] }}</h2></div>
									<div class="clearfix"></div>
									
									{% for news in news_list %}
										<div class="col-md-12">	
											<div class="news-in-item">
												<div class="media">
													<p class="news-in-date">{{ news.page_add }}</p>
													<a class="pull-left" href="{{ HOST_NAME }}/{{ news.page_alias|escape|stripslashes }}">
														<img class="media-object" src="{{ ABS_PATH }}?thumb={{news.page_preview_site}}&width=214&height=143">
													</a>
													<div class="media-body">
														<h4 class="media-heading"><a href="{{ HOST_NAME }}/{{ news.page_alias|escape|stripslashes }}">{{ news.page_title|escape|stripslashes|truncate(100) }}</a></h4>
														<p>{{ news.page_text|striptags|truncate(400) }}</p>
													</div>
												</div>
											</div>
										</div>
									{% endfor %}

									{% if search_type_active == 1 %}
										<div class="col-md-offset-8 col-md-4">
											<div class="clearfix"></div>
											<a href="{{ ABS_PATH }}search/type-5/{% if REQUEST.search %}?search={{ REQUEST.search|escape|stripslashes }}{% endif %}" class="btn btn-block btn-go-on">{{ lang.search_main_array_btn[5] }} </a>	
										</div>
									{% endif %}								
					
									{% if page_nav %}
										{{ page_nav }}										
									{% endif %}
								{% endif %}

							</div>
						</div>

					</div>
				</div>
			</div>

		</div>
	</div>
</main>
