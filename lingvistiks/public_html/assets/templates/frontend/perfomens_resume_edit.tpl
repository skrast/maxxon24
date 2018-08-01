<main class="sidebar-left">
	<div class="container">

		<ol class="breadcrumb">
			<li><a href="{{ HOST_NAME }}">{{ lang.page_breadcrumb }}</a></li>
			<li class="active text-uppercase">{{ lang.resume_title }}</li>
		</ol>

		<h1>{{ lang.resume_title }}</h1>

		<div class="row">
			{{ perfomens_col }}

			<div class="col-md-{% if perfomens_col %}9{% else %}12{% endif %} col-sm-{% if perfomens_col %}9{% else %}12{% endif %}">
				<div class="content-right">
					<div class="profile-block">

						<form action="{{ HOST_NAME }}/resume/" method="post" id="addAppearance" class="ajax_form table_form" enctype="multipart/form-data">
							<input type="hidden" name="csrf_token" value="{{ csrf_token }}">
							<input type="hidden" name="save" value="1">

							<div class="row">
								<div class="col-md-12">
									<h3 class="text-center text-uppercase">{{ lang.resume_my_info }}</h3>
								</div>
								
								<div class="col-md-3 text-center">
									<a href="#" class="crop-image-btn" {% if resume_info.resume_photo and resume_info.resume_photo != 'no-avatar.png' %}data-photo="1"{% endif %} data-img="{{ ABS_PATH }}{{ app.app_upload_dir }}/{{ app.app_resume_orig_dir }}/{{ resume_info.resume_photo }}" data-type="1">
										{% if resume_info.resume_photo %}
											<img src="{{ ABS_PATH }}?thumb={{ app.app_upload_dir }}/{{ app.app_resume_dir }}/{{ resume_info.resume_photo }}&width=250&height=300" alt="" class="img-responsive">
										{% else %}
											<img src="{{ ABS_PATH }}assets/site/template/images/no-photo.png" alt="" class="img-responsive">
										{% endif %}
									</a>
									<div class="file-upload-profile">
										<label>
											<input type="file" name="user_photo" id="upload-photo">
											<span>{{ lang.lk_upload_photo }}</span>
										</label>
									</div>
								</div>

								<div class="col-md-9">

									<div class="row">
										<div class="col-md-3 text-right">
											{{ lang.profile_firstname }}
										</div>
										<div class="col-md-8">
											<div class="form-group">
												<input type="text" class="form-control" name="resume_firstname" placeholder="{{ lang.profile_firstname }}" value="{{ resume_info.resume_firstname|escape|stripslashes }}">
											</div>
										</div>
									</div>

									<div class="row">
										<div class="col-md-3 text-right">
											{{ lang.profile_lastname }}
										</div>
										<div class="col-md-8">
											<div class="form-group">
												<input type="text" class="form-control" name="resume_lastname" placeholder="{{ lang.profile_lastname }}" value="{{ resume_info.resume_lastname|escape|stripslashes }}">
											</div>
										</div>
									</div>

									<div class="row">
										<div class="col-md-3 text-right">
											{{ lang.profile_patronymic }}
										</div>
										<div class="col-md-8">
											<div class="form-group">
												<input type="text" class="form-control" name="resume_patronymic" placeholder="{{ lang.profile_patronymic }}" value="{{ resume_info.resume_patronymic|escape|stripslashes }}">
											</div>
										</div>
									</div>

									<div class="row">
										<div class="col-md-3 text-right">
											{{ lang.profile_birthday }}
										</div>
										<div class="col-md-3">
											<div class="form-group">
												<input type="text" class="form-control datetime date_mask" name="resume_birthday" placeholder="{{ lang.resume_birthday }}" value="{{ resume_info.resume_birthday|escape|stripslashes }}">
											</div>
										</div>
									</div>

									<div class="row">
										<div class="col-md-3 text-right">
											{{ lang.profile_from }}
										</div>
										<div class="col-md-4">
											<div class="form-group">
												<select class="form-control load_select selectpicker" name="resume_country" data-target="resume_city" placeholder="{{ lang.profile_country_default }}" data-live-search="true">
													<option value="">{{ lang.profile_country_default }}</option>
													{% for country in country_list %}
														<option value="{{ country.id|escape|stripslashes }}" {% if country.id == resume_info.resume_country.id %}selected{% endif %}>{{ country.title|escape|stripslashes }}</option>
													{% endfor %}
												</select>
											</div>
										</div>
										<div class="col-md-4">
											<div class="form-group">
												<select class="form-control selectpicker" name="resume_city" data-default="{{ lang.profile_city_default }}" placeholder="{{ lang.profile_city_default }}" data-live-search="true">
													{% for city in city_list %}
														<option value="{{ city.id|escape|stripslashes }}" {% if city.id == resume_info.resume_city.id %}selected{% endif %}>{{ city.title|escape|stripslashes }}</option>
													{% else %}
														<option value="">{{ lang.profile_city_default }}</option>
													{% endfor %}
												</select>
											</div>
										</div>
									</div>

									<div class="row">
										<div class="col-md-3 text-right">
											{{ lang.profile_email }}
										</div>
										<div class="col-md-8">
											<div class="form-group">
												<input type="text" class="form-control" name="resume_email" placeholder="{{ lang.profile_email }}" value="{{ resume_info.resume_email|escape|stripslashes }}">
											</div>
										</div>
									</div>

									<div class="row">
										<div class="col-md-3 text-right">
											{{ lang.profile_phone }}
										</div>
										<div class="col-md-8">
											<div class="form-group">
												<input type="text" class="form-control phone_mask" name="resume_phone" placeholder="{{ lang.profile_phone }}" value="{{ resume_info.resume_phone|escape|stripslashes }}">
											</div>
										</div>
									</div>

									<div class="row">
										<div class="col-md-3 text-right">
											{{ lang.profile_citizenship }}
										</div>
										<div class="col-md-8">
											<div class="form-group">
												<select class="form-control selectpicker" name="resume_citizenship" placeholder="{{ lang.profile_citizenship }}" data-live-search="true">
													<option value="">{{ lang.profile_country_default }}</option>
													{% for country in country_list %}
														<option value="{{ country.id|escape|stripslashes }}" {% if country.id == resume_info.resume_citizenship.id %}selected{% endif %}>{{ country.title|escape|stripslashes }}</option>
													{% endfor %}
												</select>
											</div>
										</div>
									</div>

								</div>
							</div>


							<h3 class="text-center text-uppercase">{{ lang.resume_my_wish_info }}</h3>
							<div class="form-group">
								<div class="row">
									<div class="col-md-4 text-right">
										<label for="">{{ lang.jobs_title }}</label>
									</div>
									<div class="col-md-8">
										<select class="form-control" name="resume_title">
											<option value="">{{ lang.jobs_title }}</option>
											{% for jobs in jobs_title %}
												<option value="{{ jobs.id|escape|stripslashes }}" {% if jobs.id == resume_info.resume_title.id %}selected{% endif %}>{{ jobs.title|escape|stripslashes }}</option>
											{% endfor %}
										</select>
									</div>
								</div>
							</div>

							<div class="form-group">
								<div class="row">
									<div class="col-md-4 text-right">
										<label for="">{{ lang.jobs_coast }}</label>
									</div>
									<div class="col-md-8">
										<div class="row cash">
											<div class="col-md-2 col-sm-6 col-xs-6">
												<div class="form-group">
													<span class="diapazon-min-max">{{ lang.lk_budget_from }} </span>
													<input type="text" class="diapazon" placeholder="100" name="resume_coast_start" value="{{ resume_info.resume_coast_start|escape|stripslashes }}">
												</div>
											</div>
											<div class="col-md-2 col-sm-6 col-xs-6">
												<div class="form-group">
													<span class="diapazon-min-max">{{ lang.lk_budget_to }} </span>
													<input type="text" class="diapazon" placeholder="200" name="resume_coast_end" value="{{ resume_info.resume_coast_end|escape|stripslashes }}">
												</div>
											</div>
											
											<div class="col-md-3 col-sm-6 col-xs-6">
												<div class="form-group">
													<select class="form-control" name="resume_coast_period">
														<option value="1" {% if 1 == resume_info.resume_coast_period %}selected{% endif %}>{{ lang.jobs_coast_month }}</option>
														<option value="2" {% if 2 == resume_info.resume_coast_period %}selected{% endif %}>{{ lang.jobs_coast_year }}</option>
													</select>
												</div>
											</div>

											<div class="col-md-2 col-sm-6 col-xs-6">
												<div class="form-group">
													<select class="form-control" name="resume_coast_currency">
														{% for currency in currency_list %}
															<option value="{{ currency.id }}" {% if currency.id == resume_info.resume_coast_currency %}selected{% endif %}>{{ currency.title|escape|stripslashes }}</option>
														{% endfor %}
													</select>
												</div>
											</div>

											<div class="col-md-2 col-sm-6 col-xs-6">
												<div class="jobs_gross">
													Gross
												</div>
											</div>
				
										</div>
									</div>
								</div>
							</div>

							<div class="form-group">
								<div class="row">
									<div class="col-md-4 text-right">
										<label for="">{{ lang.jobs_terms_employment }}</label>
									</div>
									<div class="col-md-8">
										
										{% for terms in terms_employment %}
											<label class="checkbox">
												<input class="checkbox-input" type="checkbox" name="resume_terms_employment[]" value="{{ terms.id|escape|stripslashes }}" {% if terms.id in resume_info.resume_terms_employment|keys %}checked{% endif %}>
												<span class="checkbox-custom"></span>
												<span class="label">
													{{ terms.title|escape|stripslashes }}
												</span>
											</label>
										{% endfor %}

									</div>
								</div>
							</div>

							<div class="form-group">
								<div class="row">
									<div class="col-md-4 text-right">
										<label for="">{{ lang.jobs_work_place }}</label>
									</div>
									<div class="col-md-8">
										
										{% for place in work_place %}
											<label class="checkbox">
												<input class="checkbox-input" type="checkbox" name="resume_work_place[]" value="{{ place.id|escape|stripslashes }}" {% if place.id in resume_info.resume_work_place|keys %}checked{% endif %}>
												<span class="checkbox-custom"></span>
												<span class="label">
														{{ place.title|escape|stripslashes }}
												</span>
											</label>
										{% endfor %}

									</div>
								</div>
							</div>

							<div class="form-group">
								<div class="row">
									<div class="col-md-4 text-right">
										<label for="">{{ lang.jobs_employment_conditions }}</label>
									</div>
									<div class="col-md-8">
										
										{% for conditions in employment_conditions %}
											<label class="checkbox">
												<input class="checkbox-input" type="checkbox" name="resume_employment_conditions[]" value="{{ conditions.id|escape|stripslashes }}" {% if conditions.id in resume_info.resume_employment_conditions|keys %}checked{% endif %}>
												<span class="checkbox-custom"></span>
												<span class="label">
														{{ conditions.title|escape|stripslashes }}
												</span>
											</label>
										{% endfor %}

									</div>
								</div>
							</div>

							<div class="form-group">
								<div class="row">
									<div class="col-md-4 text-right">
										<label for="">{{ lang.jobs_work_time }}</label>
									</div>
									<div class="col-md-8">
										
										{% for time in work_time %}
											<label class="checkbox">
												<input class="checkbox-input" type="checkbox" name="resume_work_time[]" value="{{ time.id|escape|stripslashes }}" {% if time.id in resume_info.resume_work_time|keys %}checked{% endif %}>
												<span class="checkbox-custom"></span>
												<span class="label">
														{{ time.title|escape|stripslashes }}
												</span>
											</label>
										{% endfor %}

									</div>
								</div>
							</div>

							<div class="form-group">
								<div class="row">
									<div class="col-md-4 text-right">
										<label for="">{{ lang.resume_ready_to_move }}</label>
									</div>
									<div class="col-md-8">
										
										{% for ready_to_move in ready_to_move_list %}
											<label class="checkbox">
												<input class="radio-input" type="radio" name="resume_ready_to_move" value="{{ ready_to_move.id|escape|stripslashes }}" {% if ready_to_move.id == resume_info.resume_ready_to_move %}checked{% endif %}>
												<span class="radio-custom"></span>
												<span class="label">
													{{ ready_to_move.title|escape|stripslashes }}
												</span>
											</label>
										{% endfor %}

									</div>
								</div>
							</div>

							<div class="form-group">
								<div class="row">
									<div class="col-md-4 text-right">
										<label for="">{{ lang.resume_interview_method }}</label>
									</div>
									<div class="col-md-8">
										
										{% for interview_method in interview_method_list %}
											<label class="checkbox">
												<input class="checkbox-input" type="checkbox" name="resume_interview_method[]" value="{{ interview_method.id|escape|stripslashes }}" {% if interview_method.id in resume_info.resume_interview_method|keys %}checked{% endif %}>
												<span class="checkbox-custom"></span>
												<span class="label">
														{{ interview_method.title|escape|stripslashes }}
												</span>
											</label>
										{% endfor %}

									</div>
								</div>
							</div>


 
							<h3 class="text-center text-uppercase">{{ lang.resume_work }}</h3>
							<ul class="list-unstyled work_list">
								{% for work in work_list %}
									<li class="work">
										<div class="form-group">
											<div class="row">
												<div class="col-md-4 text-right">
													<label for="">{{ lang.resume_work_company }}</label>
												</div>
												<div class="col-md-6">
													
													<input type="text" class="form-control" value="{{ work.work_company|escape|stripslashes }}" name="work_company[]" placeholder="{{ lang.resume_work_company }}">

													<label class="checkbox hidden mt-10 work_start_first">
														<input class="checkbox-input" type="checkbox" name="work_start_first[]" value="1" {% if work.work_start_first == 1 %}checked{% endif %}>
														<span class="checkbox-custom"></span>
														<span class="label">
															{{ lang.resume_work_start_first }}
														</span>
													</label>

												</div>
											</div>
										</div>

										<div class="form-group">
											<div class="row">
												<div class="col-md-4 text-right">
													<label for="">{{ lang.resume_work_site }}</label>
												</div>
												<div class="col-md-6">
													
													<input type="text" class="form-control" value="{{ work.work_site|escape|stripslashes }}" name="work_site[]" placeholder="{{ lang.resume_work_site }}">

												</div>
											</div>
										</div>

										<div class="form-group">
											<div class="row">
												<div class="col-md-4 text-right">
													<label for="">{{ lang.lk_country }}</label>
												</div>
												<div class="col-md-3">
													
													<select class="form-control work_country" name="work_country[]">
														<option value="">{{ lang.lk_country }}</option>
														{% for country in country_list %}
															<option value="{{ country.id|escape|stripslashes }}" {% if work.work_country == country.id %}selected{% endif %}>{{ country.title|escape|stripslashes }}</option>
														{% endfor %}
													</select>

												</div>
											</div>
										</div>

										<div class="form-group">
											<div class="row">
												<div class="col-md-4 text-right">
													<label for="">{{ lang.lk_city }}</label>
												</div>
												<div class="col-md-3">
													
													<select class="form-control work_city" name="work_city[]">
														<option value="">{{ lang.lk_city }}</option>
														{% for city in work.city_list %}
															<option value="{{ city.id|escape|stripslashes }}" {% if work.work_city == city.id %}selected{% endif %}>{{ city.title|escape|stripslashes }}</option>
														{% endfor %}
													</select>

												</div>
											</div>
										</div>

										<div class="form-group">
											<div class="row">
												<div class="col-md-4 text-right">
													<label for="">{{ lang.resume_work_group }}</label>
												</div>
												<div class="col-md-6">
													
													<input type="text" class="form-control" value="{{ work.work_group|escape|stripslashes }}" placeholder="{{ lang.resume_work_group }}" name="work_group[]">

												</div>
											</div>
										</div>

										<div class="form-group">
											<div class="row">
												<div class="col-md-4 text-right">
													<label for="">{{ lang.resume_work_desc }}</label>
												</div>
												<div class="col-md-6">
													
													<textarea class="form-control" placeholder="{{ lang.resume_work_desc_ext }}" name="work_desc[]" rows="5">{{ work.work_desc|escape|stripslashes }}</textarea>

												</div>
											</div>
										</div>

										<div class="form-group">
											<div class="row">
												<div class="col-md-4 text-right">
													<label for="">{{ lang.resume_work_result }}</label>
												</div>
												<div class="col-md-6">
													
													<textarea class="form-control" placeholder="{{ lang.resume_work_result_ext }}" name="work_result[]" rows="5">{{ work.work_result|escape|stripslashes }}</textarea>

												</div>
											</div>
										</div>

										<div class="form-group work_start_block">
											<div class="row">
												<div class="col-md-4 text-right">
													<label for="">{{ lang.resume_work_start }}</label>
												</div>
												<div class="col-md-8">
													
													<div class="row">
														<div class="col-md-4">
															<select class="form-control" name="work_month_start[]">
																{% for key, month in lang.resume_work_month %}
																	<option value="{{ key }}" {% if work.work_month_start == key %}selected{% endif %}>{{ month }}</option>
																{% endfor %}
															</select>
														</div>
														<div class="col-md-3">
															<input type="number" class="form-control year_mask" value="{{ work.work_year_start|escape|stripslashes }}" placeholder="{{ lang.resume_work_year }}" name="work_year_start[]" maxlength="4">
														</div>
														<div class="col-md-5">
															<label class="checkbox">
																<input class="checkbox-input in_now_work" type="checkbox" name="work_now[]" value="1" {% if 1 == work.work_now %}checked{% endif %}>
																<span class="checkbox-custom"></span>
																<span class="label">
																	{{ lang.resume_work_now }}
																</span>
															</label>
														</div>
													</div>

												</div>
											</div>
										</div>

										<div class="form-group">
											<div class="row">
												<div class="col-md-4 text-right">
													<label for="">{{ lang.resume_work_end }}</label>
												</div>
												<div class="col-md-8">
													
													<div class="row">
														<div class="col-md-4">
															<select class="form-control" name="work_month_end[]" {% if 1 == work.work_now %}disabled{% endif %}>
																{% for key, month in lang.resume_work_month %}
																	<option value="{{ key }}" {% if work.work_month_end == key %}selected{% endif %}>{{ month }}</option>
																{% endfor %}
															</select>
														</div>
														<div class="col-md-3">
															<input type="number" class="form-control year_mask" value="{{ work.work_year_end|escape|stripslashes }}" placeholder="{{ lang.resume_work_year }}" name="work_year_end[]" maxlength="4" {% if 1 == work.work_now %}disabled{% endif %}>
														</div>
														<div class="col-md-5"></div>
													</div>

												</div>
											</div>
										</div>

										<div class="form-group text-center">
											<a href="" class="text-danger delete_work">{{ lang.btn_delete }}</a>
										</div>
									</li>
								{% endfor %}
							</ul>

							<div class="work_insert"></div>

							<ul class="work_copy list-unstyled">
								<li class="work">
									<div class="form-group">
										<div class="row">
											<div class="col-md-4 text-right">
												<label for="">{{ lang.resume_work_company }}</label>
											</div>
											<div class="col-md-6">
												
												<input type="text" class="form-control" name="work_company[]" placeholder="{{ lang.resume_work_company }}">

												<label class="hidden checkbox mt-10 work_start_first">
													<input class="checkbox-input" type="checkbox" name="work_start_first[]" value="1">
													<span class="checkbox-custom"></span>
													<span class="label">
														{{ lang.resume_work_start_first }}
													</span>
												</label>

											</div>
										</div>
									</div>

									<div class="form-group">
										<div class="row">
											<div class="col-md-4 text-right">
												<label for="">{{ lang.resume_work_site }}</label>
											</div>
											<div class="col-md-6">
												
												<input type="text" class="form-control" name="work_site[]" placeholder="{{ lang.resume_work_site }}">

											</div>
										</div>
									</div>

									<div class="form-group">
										<div class="row">
											<div class="col-md-4 text-right">
												<label for="">{{ lang.lk_country }}</label>
											</div>
											<div class="col-md-3">
												
												<select class="form-control work_country" name="work_country[]">
													<option value="">{{ lang.lk_country }}</option>
													{% for country in country_list %}
														<option value="{{ country.id|escape|stripslashes }}">{{ country.title|escape|stripslashes }}</option>
													{% endfor %}
												</select>

											</div>
										</div>
									</div>

									<div class="form-group">
										<div class="row">
											<div class="col-md-4 text-right">
												<label for="">{{ lang.lk_city }}</label>
											</div>
											<div class="col-md-3">
												
												<select class="form-control work_city" name="work_city[]">
													<option value="">{{ lang.lk_city }}</option>
												</select>

											</div>
										</div>
									</div>

									<div class="form-group">
										<div class="row">
											<div class="col-md-4 text-right">
												<label for="">{{ lang.resume_work_group }}</label>
											</div>
											<div class="col-md-6">
												
												<input type="text" class="form-control" placeholder="{{ lang.resume_work_group }}" name="work_group[]">

											</div>
										</div>
									</div>

									<div class="form-group">
										<div class="row">
											<div class="col-md-4 text-right">
												<label for="">{{ lang.resume_work_desc }}</label>
											</div>
											<div class="col-md-6">
												
												<textarea class="form-control" placeholder="{{ lang.resume_work_desc }}" name="work_desc[]" rows="5"></textarea>

											</div>
										</div>
									</div>

									<div class="form-group">
										<div class="row">
											<div class="col-md-4 text-right">
												<label for="">{{ lang.resume_work_result }}</label>
											</div>
											<div class="col-md-6">
												
												<textarea class="form-control" placeholder="{{ lang.resume_work_result_ext }}" name="work_result[]" rows="5"></textarea>

											</div>
										</div>
									</div>

									<div class="form-group work_start_block">
										<div class="row">
											<div class="col-md-4 text-right">
												<label for="">{{ lang.resume_work_start }}</label>
											</div>
											<div class="col-md-8">
												
												<div class="row">
													<div class="col-md-4">
														<select class="form-control" name="work_month_start[]">
															{% for key, month in lang.resume_work_month %}
																<option value="{{ key }}">{{ month }}</option>
															{% endfor %}
														</select>
													</div>
													<div class="col-md-3">
														<input type="number" class="form-control year_mask" value="" placeholder="{{ lang.resume_work_year }}" name="work_year_start[]" maxlength="4">
													</div>
													<div class="col-md-5">
														<label class="checkbox">
															<input class="checkbox-input in_now_work" type="checkbox" name="work_now[]" value="1">
															<span class="checkbox-custom"></span>
															<span class="label">
																{{ lang.resume_work_now }}
															</span>
														</label>
													</div>
												</div>

											</div>
										</div>
									</div>

									<div class="form-group">
										<div class="row">
											<div class="col-md-4 text-right">
												<label for="">{{ lang.resume_work_end }}</label>
											</div>
											<div class="col-md-8">
												
												<div class="row">
													<div class="col-md-4">
														<select class="form-control" name="work_month_end[]">
															{% for key, month in lang.resume_work_month %}
																<option value="{{ key }}">{{ month }}</option>
															{% endfor %}
														</select>
													</div>
													<div class="col-md-3">
														<input type="number" class="form-control year_mask" value="" placeholder="{{ lang.resume_work_year }}" name="work_year_end[]" maxlength="4">
													</div>
													<div class="col-md-5"></div>
												</div>

											</div>
										</div>
									</div>

									<div class="form-group text-center hidden">
										<a href="" class="text-danger delete_work">{{ lang.btn_delete }}</a>
									</div>
								</li>
							</ul>
							<button type="button" class="btn btn-block btn-link copy_work">{{ lang.resume_block_add }}</button>


							<h3>{{ lang.resume_edu }}</h3>
							<ul class="edu_list">
								{% for education in resume_edu %}
									<li class="edu">
										<div class="form-group no_hidden">
											<div class="row">
												<div class="col-md-4 text-right">
													<label for="">{{ lang.resume_edu_title }}</label>
												</div>
												<div class="col-md-6">
													
													<select class="form-control resume_edu_title" name="resume_edu_title[]">
														<option value="">{{ lang.resume_edu_title }}</option>
														{% for edu in jobs_edu %}
															<option value="{{ edu.id }}" {% if edu.id == education.resume_edu_title %}selected{% endif %}>{{ edu.title|escape|stripslashes }}</option>
														{% endfor %}
													</select>

												</div>
											</div>
										</div>

										<div class="form-group show {% if education.resume_edu_title not in [96, 99] %}hidden{% endif %} show_96 show_99">
											<div class="row">
												<div class="col-md-4 text-right">
													<label for="">{{ lang.resume_edu_org }}</label>
												</div>
												<div class="col-md-6">
													
													<input type="text" class="form-control" value="{{ education.resume_edu_org|escape|stripslashes }}" placeholder="{{ lang.resume_edu_org }}" name="resume_edu_org[]">

												</div>
											</div>
										</div>

										<div class="form-group {% if education.resume_edu_title not in [96, 99] %}hidden{% endif %} show show_96 show_99">
											<div class="row">
												<div class="col-md-4 text-right">
													<label for="">{{ lang.resume_edu_curs }}</label>
												</div>
												<div class="col-md-6">
													
													<input type="text" class="form-control" value="{{ education.resume_edu_curs|escape|stripslashes }}" placeholder="{{ lang.resume_edu_curs }}" name="resume_edu_curs[]">

												</div>
											</div>
										</div>

										<div class="form-group {% if education.resume_edu_title not in [98, 97, 94, 95, 93] %}hidden{% endif %} show show_98 show_97 show_94 show_95 show_93">
											<div class="row">
												<div class="col-md-4 text-right">
													<label for="">{{ lang.resume_edu_univer }}</label>
												</div>
												<div class="col-md-6">
													
													<input type="text" class="form-control" value="{{ education.resume_edu_univer|escape|stripslashes }}" placeholder="{{ lang.resume_edu_univer }}" name="resume_edu_univer[]">

												</div>
											</div>
										</div>

										<div class="form-group {% if education.resume_edu_title not in [97, 94, 95, 93] %}hidden{% endif %} show show_97 show_94 show_95 show_93">
											<div class="row">
												<div class="col-md-4 text-right">
													<label for="">{{ lang.resume_edu_univer_faq }}</label>
												</div>
												<div class="col-md-6">
													
													<input type="text" class="form-control" value="{{ education.resume_edu_univer_faq|escape|stripslashes }}" placeholder="{{ lang.resume_edu_univer_faq }}" name="resume_edu_univer_faq[]">

												</div>
											</div>
										</div>

										<div class="form-group {% if education.resume_edu_title not in [97,94,95,93,96,99] %}hidden{% endif %} show show_97 show_94 show_95 show_93 show_96 show_99">
											<div class="row">
												<div class="col-md-4 text-right">
													<label for="">{{ lang.resume_edu_univer_spec }}</label>
												</div>
												<div class="col-md-6">
													
													<input type="text" class="form-control" value="{{ education.resume_edu_univer_spec|escape|stripslashes }}" placeholder="{{ lang.resume_edu_univer_spec }}" name="resume_edu_univer_spec[]">

												</div>
											</div>
										</div>

										<div class="form-group {% if education.resume_edu_title not in [98,97,94,95,93,96,99] %}hidden{% endif %} show show_98 show_97 show_94 show_95 show_93 show_96 show_99">
											<div class="row">
												<div class="col-md-4 text-right">
													<label for="">{{ lang.lk_country }}</label>
												</div>
												<div class="col-md-6">
													
													<select class="form-control work_country" name="resume_edu_country[]">
														<option value="">{{ lang.lk_country }}</option>
														{% for country in country_list %}
															<option value="{{ country.id|escape|stripslashes }}" {% if education.resume_edu_country == country.id %}selected{% endif %}>{{ country.title|escape|stripslashes }}</option>
														{% endfor %}
													</select>

												</div>
											</div>
										</div>

										<div class="form-group {% if education.resume_edu_title not in [98,97,94,95,93,96,99] %}hidden{% endif %} show show_98 show_97 show_94 show_95 show_93 show_96 show_99">
											<div class="row">
												<div class="col-md-4 text-right">
													<label for="">{{ lang.lk_city }}</label>
												</div>
												<div class="col-md-6">
													
													<select class="form-control work_city" name="resume_edu_city[]">
														<option value="">{{ lang.lk_city }}</option>

														{% for city in education.city_list %}
															<option value="{{ city.id|escape|stripslashes }}" {% if education.resume_edu_city == city.id %}selected{% endif %}>{{ city.title|escape|stripslashes }}</option>
														{% endfor %}
													</select>

												</div>
											</div>
										</div>

										<div class="form-group {% if education.resume_edu_title not in [98,97,94,95,93,96,99] %}hidden{% endif %} show show_98 show_97 show_94 show_95 show_93 show_96 show_99">
											<div class="row">
												<div class="col-md-4 text-right">
													<label for="">{{ lang.resume_work_year }}</label>
												</div>
												<div class="col-md-3">

													<input type="number" class="form-control year_mask" name="resume_edu_year[]" value="{{ education.resume_edu_year|escape|stripslashes }}" maxlength="4">
													
												</div>
											</div>
										</div>

										<div class="form-group no_hidden text-center">
											<a href="" class="text-danger delete_edu">{{ lang.btn_delete }}</a>
										</div>
									</li>
								{% endfor%}
							</ul>

							<div class="edu_insert"></div>

							<ul class="edu_copy">
								<li class="edu">
									<div class="form-group no_hidden">
										<div class="row">
											<div class="col-md-4 text-right">
												<label for="">{{ lang.resume_edu_title }}</label>
											</div>
											<div class="col-md-6">
												
												<select class="form-control resume_edu_title" name="resume_edu_title[]">
													<option value="">{{ lang.resume_edu_title }}</option>
													{% for edu in jobs_edu %}
														<option value="{{ edu.id }}">{{ edu.title|escape|stripslashes }}</option>
													{% endfor %}
												</select>

											</div>
										</div>
									</div>

									<div class="form-group hidden show show_96 show_99">
										<div class="row">
											<div class="col-md-4 text-right">
												<label for="">{{ lang.resume_edu_org }}</label>
											</div>
											<div class="col-md-6">
												
												<input type="text" class="form-control" value="" placeholder="{{ lang.resume_edu_org }}" name="resume_edu_org[]">

											</div>
										</div>
									</div>

									<div class="form-group hidden show show_96 show_99">
										<div class="row">
											<div class="col-md-4 text-right">
												<label for="">{{ lang.resume_edu_curs }}</label>
											</div>
											<div class="col-md-6">
												
												<input type="text" class="form-control" value="" placeholder="{{ lang.resume_edu_curs }}" name="resume_edu_curs[]">

											</div>
										</div>
									</div>

									<div class="form-group hidden show show_98 show_97 show_94 show_95 show_93">
										<div class="row">
											<div class="col-md-4 text-right">
												<label for="">{{ lang.resume_edu_univer }}</label>
											</div>
											<div class="col-md-6">
												
												<input type="text" class="form-control" value="" placeholder="{{ lang.resume_edu_univer }}" name="resume_edu_univer[]">

											</div>
										</div>
									</div>

									<div class="form-group hidden show show_97 show_94 show_95 show_93">
										<div class="row">
											<div class="col-md-4 text-right">
												<label for="">{{ lang.resume_edu_univer_faq }}</label>
											</div>
											<div class="col-md-6">
												
												<input type="text" class="form-control" value="" placeholder="{{ lang.resume_edu_univer_faq }}" name="resume_edu_univer_faq[]">

											</div>
										</div>
									</div>

									<div class="form-group hidden show show_97 show_94 show_95 show_93 show_96 show_99">
										<div class="row">
											<div class="col-md-4 text-right">
												<label for="">{{ lang.resume_edu_univer_spec }}</label>
											</div>
											<div class="col-md-6">
												
												<input type="text" class="form-control" value="" placeholder="{{ lang.resume_edu_univer_spec }}" name="resume_edu_univer_spec[]">

											</div>
										</div>
									</div>

									<div class="form-group hidden show show_98 show_97 show_94 show_95 show_93 show_96 show_99">
										<div class="row">
											<div class="col-md-4 text-right">
												<label for="">{{ lang.lk_country }}</label>
											</div>
											<div class="col-md-6">
												
												<select class="form-control work_country" name="resume_edu_country[]">
													<option value="">{{ lang.lk_country }}</option>
													{% for country in country_list %}
														<option value="{{ country.id|escape|stripslashes }}">{{ country.title|escape|stripslashes }}</option>
													{% endfor %}
												</select>

											</div>
										</div>
									</div>

									<div class="form-group hidden show show_98 show_97 show_94 show_95 show_93 show_96 show_99">
										<div class="row">
											<div class="col-md-4 text-right">
												<label for="">{{ lang.lk_city }}</label>
											</div>
											<div class="col-md-6">
												
												<select class="form-control work_city" name="resume_edu_city[]">
													<option value="">{{ lang.lk_city }}</option>
												</select>

											</div>
										</div>
									</div>

									<div class="form-group hidden show show_98 show_97 show_94 show_95 show_93 show_96 show_99">
										<div class="row">
											<div class="col-md-4 text-right">
												<label for="">{{ lang.resume_work_year }}</label>
											</div>
											<div class="col-md-3">

												<input type="number" class="form-control year_mask" name="resume_edu_year[]" value="" maxlength="4">
												
											</div>
										</div>
									</div>

									<div class="form-group text-center hidden">
										<a href="" class="text-danger delete_edu">{{ lang.btn_delete }}</a>
									</div>
								</li>
							</ul>
							<button type="button" class="btn btn-block btn-link copy_edu">{{ lang.resume_block_add }}</button>



							<h3>{{ lang.resume_dop_info }}</h3>
							<div class="form-group">
								<div class="row">
									<div class="col-md-4 text-right">
										<label for="">{{ lang.resume_dop_info_lang }}</label>
									</div>
									<div class="col-md-8">
										{% for skill in resume_skill_lang %}
											<div class="not_empty_level_list">
												<div class="row mb-10 check_{{ skill.lang_id }}_{{ skill.lang_level }}">
													<div class="col-md-4">
														<select class="form-control selectpicker" name="resume_skill_lang[]">
															<option value="">{{ lang.jobs_skill_lang_change }}</option>
															{% for lang in lang_list %}
																<option value="{{ lang.id|escape|stripslashes }}" {% if lang.id == skill.lang_id %}selected{% endif %}>{{ lang.title|escape|stripslashes }}</option>
															{% endfor %}
														</select>
													</div>
													<div class="col-md-4">
														<select class="form-control selectpicker" name="resume_skill_lang_level[]">
															<option value="">{{ lang.jobs_skill_lang_level }}</option>
															{% for lang in lang_level_list %}
																<option value="{{ lang.id|escape|stripslashes }}" {% if lang.id == skill.lang_level %}selected{% endif %}>{{ lang.title|escape|stripslashes }}</option>
															{% endfor %}
														</select>
													</div>
													<div class="col-md-1">
														<div class="close"><span aria-hidden="true">&times;</span></div>
													</div>
												</div>
											</div>
										{% endfor %}
										
										<div class="empty_level_list">
											<div class="not_empty_level_list">
												<div class="row mb-10">
													<div class="col-md-4">
														<select class="form-control selectpicker resume_skill_lang" name="resume_skill_lang[]">
															<option value="">{{ lang.jobs_skill_lang_change }}</option>
															{% for lang in lang_list %}
																<option value="{{ lang.id|escape|stripslashes }}">{{ lang.title|escape|stripslashes }}</option>
															{% endfor %}
														</select>
													</div>
													<div class="col-md-4">
														<select class="form-control selectpicker resume_skill_lang_level" name="resume_skill_lang_level[]">
															<option value="">{{ lang.jobs_skill_lang_level }}</option>
															{% for lang in lang_level_list %}
																<option value="{{ lang.id|escape|stripslashes }}">{{ lang.title|escape|stripslashes }}</option>
															{% endfor %}
														</select>
													</div>
													<div class="col-md-1">
														<div class="close hidden"><span aria-hidden="true">&times;</span></div>
													</div>
												</div>
											</div>
										</div>
										<div class="add_more_level_list text-center">{{ lang.jobs_skill_lang_add }}</div>
									</div>
								</div>
							</div>
							<div class="form-group">
								<div class="row">
									<div class="col-md-4 text-right">
										<label for="">{{ lang.resume_dop_info_passport }}</label>
									</div>
									<div class="col-md-8">
										<label class="radio">
											<input class="radio-input in_now_work" type="radio" name="resume_dop_info_passport" value="1" {% if resume.resume_dop_info_passport == 1 %}checked{% endif %}>
											<span class="radio-custom"></span>
											<span class="label">
												{{ lang.page_yes }}
											</span>
										</label>
										<label class="radio">
											<input class="radio-input in_now_work" type="radio" name="resume_dop_info_passport" value="0" {% if resume.resume_dop_info_passport != 1 %}checked{% endif %}>
											<span class="radio-custom"></span>
											<span class="label">
												{{ lang.page_no }}
											</span>
										</label>
									</div>
								</div>
							</div>
							<div class="form-group">
								<div class="row">
									<div class="col-md-4 text-right">
										<label for="">{{ lang.resume_dop_info_desc }}</label>
									</div>
									<div class="col-md-6">
										<textarea class="form-control resume_edu_title" placeholder="{{ lang.resume_dop_info_desc }}" name="resume_dop_info_desc" rows="5">{{ resume.resume_dop_info_desc|escape|stripslashes }}</textarea>
									</div>
								</div>
							</div>


							<h3>{{ lang.lk_photoalbum }}</h3>
							<div class="row">
								<div class="col-md-4">

									<div class="filer-uploader minimal-uploader">
										<input type="file" name="file_path">
									</div>

								</div>
								<div class="col-md-8 text-center">
									{% if album_list %}
										<div id="carousel_album" class="owl-carousel album-carousel">
											{% for file in album_list %}
												<div>
													<a href="{{ ABS_PATH }}{{ app.app_upload_dir }}/{{ app.app_album_resume_dir }}/{{ profile_info.id }}/{{ file.file_path|escape|stripslashes }}" data-lightbox="image-2">
														<img src="{{ ABS_PATH }}?thumb={{ app.app_upload_dir }}/{{ app.app_album_resume_dir }}/{{ profile_info.id }}/{{ file.file_path|escape|stripslashes }}&width=100&height=100" style="height:100px;width:100px;" alt="">
													</a>
												</div>
											{% endfor %}
										</div>
									{% else %}
										<div class="album_empty">{{ lang.lk_album_empty }}</div>
									{% endif %}
								</div>
							</div>


							<div class="row">
								<div class="col-md-offset-3 col-md-6">
									<button type="submit" class="btn btn-block btn-search">{{ lang.btn_save }}</button>
									<div class="error-message-color error-message"></div>
								</div>
							</div>

						</form>
						
					</div>
				</div>
			</div>
		</div>

	</div>
</main>


<link rel="stylesheet" href="{{ ABS_PATH }}vendor/assets/fileuploader/css/jquery-filer.css" type="text/css">
<link rel="stylesheet" href="{{ ABS_PATH }}vendor/assets/fileuploader/css/jquery.filer.css" type="text/css">
<link rel="stylesheet" href="{{ ABS_PATH }}vendor/assets/fileuploader/css/jquery.filer-dragdropbox-theme.css" type="text/css">
<script src="{{ ABS_PATH }}vendor/assets/fileuploader/js/jquery.filer.min.js"></script>
<script type="text/javascript">

	$(document).ready(function() {

	// enable fileuploader plugin
	$('.filer-uploader input[type="file"]').fileuploader({
		extensions: [{% for ext in app_allow_img %}'{{ ext }}',{% endfor %}],
		changeInput: '<div class="fileuploader-input">' +
						  '<div class="fileuploader-input-inner">' +
							  '<img src="{{ ABS_PATH }}vendor/assets/fileuploader/images/fileuploader-dragdrop-icon.png">' +
							  '<h3 class="fileuploader-input-caption"><span>{{ lang.storage_add_desc }}<br>{{ lang.storage_add_desc_allow }} {% for ext in app_allow_img %}{{ ext }} {% endfor %}</span></h3>' +
							  '<div class="fileuploader-input-button"><span>{{ lang.storage_add_desc_select }}</span></div>' +
						  '</div>' +
					  '</div>',
		theme: 'dragdrop',
		upload: {
			url: '{{ HOST_NAME }}/resume/album/',
			data: {csrf_token: csrf_token},
			type: 'POST',
			enctype: 'multipart/form-data',
			start: true,
			synchron: true,
			beforeSend: null,
			onSuccess: function(data, item) {
				/*var data = {};

				try {
					data = JSON.parse(result);
				} catch (e) {
					data.hasWarnings = true;
				}*/

				// if success
				if (data.isSuccess && data.files[0]) {
					item.name = data.files[0].name;
					item.html.find('.column-title > div:first-child').text(data.files[0].name).attr('title', data.files[0].name);
				}

				// if warnings
				if (data.hasWarnings) {

					for (var warning in data.warnings) {
						alert(data.warnings);
					}

					item.html.removeClass('upload-successful').addClass('upload-failed');
					// go out from success function by calling onError function
					// in this case we have a animation there
					// you can also response in PHP with 404
					return this.onError ? this.onError(item) : null;
				}

				item.html.find('.column-actions').append('<a class="fileuploader-action fileuploader-action-remove fileuploader-action-success" title="{{ lang.save_delete }}"><i></i></a>');
				setTimeout(function() {
					item.html.find('.progress-bar2').fadeOut(400);
				}, 400);
			},
			onError: function(item) {
				var progressBar = item.html.find('.progress-bar2');

				if(progressBar.length > 0) {
					progressBar.find('span').html(0 + "%");
					progressBar.find('.fileuploader-progressbar .bar').width(0 + "%");
					item.html.find('.progress-bar2').fadeOut(400);
				}

				item.upload.status != 'cancelled' && item.html.find('.fileuploader-action-retry').length == 0 ? item.html.find('.column-actions').prepend(
					'<a class="fileuploader-action fileuploader-action-retry" title="{{ lang.save_repeat }}"><i></i></a>'
				) : null;
			},
			onProgress: function(data, item) {
				var progressBar = item.html.find('.progress-bar2');

				if(progressBar.length > 0) {
					progressBar.show();
					progressBar.find('span').html(data.percentage + "%");
					progressBar.find('.fileuploader-progressbar .bar').width(data.percentage + "%");
				}
			},
			onComplete: null,
		},
		captions: {
			feedback: '{{ lang.storage_add_desc }}',
			feedback2: '{{ lang.storage_add_desc }}',
			drop: '{{ lang.storage_add_desc }}',
			cancel: '{{ lang.save_reset }}',
			confirm: '{{ lang.save_close }}',
			name: '{{ lang.storage_file_name }}',
			type: '{{ lang.storage_file_type }}',
			size: '{{ lang.storage_file_size }}',
			dimensions: '{{ lang.storage_file_dimensions }}',
			remove: '{{ lang.save_delete }}',
		},
	});

});
</script>



<script src="{{ ABS_PATH }}assets/site/assets/jquery.imgareaselect-0.9.10/scripts/jquery.imgareaselect.js"></script>
<link rel="stylesheet" href="{{ ABS_PATH }}assets/site/assets/jquery.imgareaselect-0.9.10/css/imgareaselect-default.css" />

<div class="modal fade" id="crop-image" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-close">
				<button type="button" class="close pull-right" data-dismiss="modal" aria-hidden="true">{{ lang.btn_close }} x</button>
			</div>
			<p class="img-title">{{ lang.lk_crop_photo }}</p>

			<div class="align_center">
				<div class="align_center_to_left">
					<div class="crop_container align_center_to_right">
						<span id="thumbnail" style="display: inline-block;">
							<img src="{{ ABS_PATH }}{{ app.app_upload_dir }}/{{ app.app_resume_orig_dir }}/{{ resume_info.resume_photo }}" alt="" id="crop_photo" >
						</span>
					</div>
				</div>
			</div>

			<form action="{{ HOST_NAME }}/resume/" method="post">
				<input type="hidden" name="csrf_token" value="{{ csrf_token }}">
				<input type="hidden" name="crop_photo" value="1" id="crop_photo_type">
				<input id="x1" type="hidden" name="x1" value="0">
				<input id="y1" type="hidden" name="y1" value="0">
				<input id="x2" type="hidden" name="x2" value="200">
				<input id="y2" type="hidden" name="y2" value="250">

				<div class="form-group text-center">
					<div class="col-md-6">
						<input type="submit" class="btn btn-block btn-search" name="save" value="{{ lang.btn_save_and_work }}">
					</div>
					<div class="col-md-6">
						<a href="{{ HOST_NAME }}/resume/" class="btn btn-block btn-search">{{ lang.btn_return }}</a>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>




<script src="{{ ABS_PATH }}vendor/assets/moment-with-locales.js"></script>
<script type="text/javascript" src="{{ ABS_PATH }}vendor/assets/flatpickr/dist/flatpickr.js"></script>
<link rel="stylesheet" type="text/css" href="{{ ABS_PATH }}vendor/assets/flatpickr/dist/themes/airbnb.css" />
<script src="{{ ABS_PATH }}vendor/assets/flatpickr/dist/l10n/{{ SESSION.user_lang|default(app.app_lang) }}.js" charset="UTF-8"></script>

<link rel="stylesheet" href="{{ ABS_PATH }}assets/site/assets/lightbox/css/lightbox.css" />
<script src="{{ ABS_PATH }}assets/site/assets/lightbox/js/lightbox.min.js"></script>

<link rel="stylesheet" href="{{ ABS_PATH }}assets/site/assets/malihu-custom-scrollbar-plugin-master/jquery.mCustomScrollbar.css">
<script src="{{ ABS_PATH }}assets/site/assets/malihu-custom-scrollbar-plugin-master/jquery.mCustomScrollbar.concat.min.js"></script>

<script>
  $(document).ready(function(){

	  var owl_album = $('#carousel_album');
		owl_album.owlCarousel({
			items:3,
			autoplay:true,
			nav:true,
			autoWidth:true,
			margin:0,
			loop:false,
			responsive:{
						0:{
							items:1
						},
						600:{
							items:2
						},
						1000:{
							items:4
						}
						},
			navText:["<img src='{{ ABS_PATH }}assets/site/template/images/prev.png'>","<img src='{{ ABS_PATH }}assets/site/template/images/next.png'>"]
		});
		owl_album.on('mousewheel', '.owl-stage', function (e) {
			if (e.deltaY>0) {
				owl_album.trigger('next.owl');
			} else {
				owl_album.trigger('prev.owl');
			}
			e.preventDefault();
		});
		

	  $(".datetime").flatpickr({
		locale: '{{ SESSION.user_lang|default(app.app_lang) }}',
		enableTime: false,
		dateFormat: 'd.m.Y',
		allowInput: true,
		time_24hr: true,
	});

	$('.date_mask').mask('##.##.####', {
		byPassKeys: '',
		translation: {
			'#': {pattern: /[0-9]/}
		}
	});

	$('.year_mask').mask('####', {
		byPassKeys: '',
		translation: {
			'#': {pattern: /[0-9]/}
		}
	});

	$('.phone_mask').mask('+7##################', {
		byPassKeys: '',
		translation: {
			'#': {pattern: /[0-9]/}
		}
	});

	  $("#content-1").mCustomScrollbar({
            scrollButtons:{enable:true},
            theme:"dark",
            axis:"y",
            setHeight: 180,
            scrollbarPosition: "outside"
          });

	$('#carouselTwo').owlCarousel({
	  items:3,
	  autoplay:true,
	  nav:true,
	  autoWidth:false,
	  margin:45,
	  loop:true,
	  navText:["<img src='{{ ABS_PATH }}assets/site/template/images/prev.png'>","<img src='{{ ABS_PATH }}assets/site/template/images/next.png'>"]
	});

	$('#carouselThree').owlCarousel({
	  items:7,
	  autoplay:true,
	  nav:true,
	  autoWidth:true,
	  margin:0,
	  loop:true,
	  navText:["<img src='{{ ABS_PATH }}assets/site/template/images/prev.png'>","<img src='{{ ABS_PATH }}assets/site/template/images/next.png'>"]
	});

    $('#carouselFour').owlCarousel({
        items:4,
        autoplay:false,
        nav:true,
        autoWidth:true,
        margin:50,
        loop:true,
        responsive:{
                      0:{
                          items:1
                      },
                      600:{
                          items:2
                      },
                      1000:{
                          items:4
                      }
                    },
        navText:["<img src='{{ ABS_PATH }}assets/site/template/images/prev.png'>","<img src='{{ ABS_PATH }}assets/site/template/images/next.png'>"]
      });

    $('#carouselFive').owlCarousel({
        items:8,
        autoplay:true,
        nav:true,
        autoWidth:true,
        margin:5,
        loop:true,
        navText:["<img src='{{ ABS_PATH }}assets/site/template/images/prev.png'>","<img src='{{ ABS_PATH }}assets/site/template/images/next.png'>"]
      });

  });
</script>

<script>
$(function () {
	// init
	App.resume_core();
	// init
});
</script>
