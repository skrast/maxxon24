<main class="sidebar-left">
	<div class="container">

		<ol class="breadcrumb">
			<li><a href="{{ HOST_NAME }}">{{ lang.page_breadcrumb }}</a></li>
			<li class="active text-uppercase">{{ lang.jobs_add }}</li>
        </ol>

		<h1>{{ lang.jobs_add }}</h1>

		<div class="row">
			{{ perfomens_col }}

			<div class="col-md-{% if perfomens_col %}9{% else %}12{% endif %} col-sm-{% if perfomens_col %}9{% else %}12{% endif %}">
				<div class="content-right">
					<div class="profile-block">

						<h3>{{ lang.jobs_add_title }}</h3>

						<form id="addVacansiya" action="{{ HOST_NAME }}/jobs/" method="post" class="ajax_form table_form" enctype="multipart/form-data">
							<input type="hidden" name="csrf_token" value="{{ csrf_token }}">
							<input type="hidden" name="save" value="1">
							<input type="hidden" name="jobs_id" value="{{ jobs_info.jobs_id }}">
			
							<div class="form-group">
								<div class="row">
									<div class="col-md-4 text-right">
										<label for="">{{ lang.jobs_title }}</label>
									</div>
									<div class="col-md-6">
										<select class="form-control" name="jobs_title">
											<option value="">{{ lang.jobs_title }}</option>
											{% for jobs in jobs_title %}
												<option value="{{ jobs.id|escape|stripslashes }}" {% if jobs.id == jobs_info.jobs_title.id %}selected{% endif %}>{{ jobs.title|escape|stripslashes }}</option>
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
													<input type="text" class="diapazon" placeholder="100" name="jobs_coast_start" value="{{ jobs_info.jobs_coast_start|escape|stripslashes }}">
												</div>
											</div>
											<div class="col-md-2 col-sm-6 col-xs-6">
												<div class="form-group">
													<span class="diapazon-min-max">{{ lang.lk_budget_to }} </span>
													<input type="text" class="diapazon" placeholder="200" name="jobs_coast_end" value="{{ jobs_info.jobs_coast_end|escape|stripslashes }}">
												</div>
											</div>
											
											<div class="col-md-3 col-sm-6 col-xs-6">
												<div class="form-group">
													<select class="form-control" name="jobs_coast_period">
														<option value="1" {% if 1 == jobs_info.jobs_coast_period %}selected{% endif %}>{{ lang.jobs_coast_month }}</option>
														<option value="2" {% if 2 == jobs_info.jobs_coast_period %}selected{% endif %}>{{ lang.jobs_coast_year }}</option>
													</select>
												</div>
											</div>

											<div class="col-md-2 col-sm-6 col-xs-6">
												<div class="form-group">
													<select class="form-control" name="jobs_coast_currency">
														{% for currency in currency_list %}
															<option value="{{ currency.id }}" {% if currency.id == jobs_info.jobs_coast_currency %}selected{% endif %}>{{ currency.title|escape|stripslashes }}</option>
														{% endfor %}
													</select>
												</div>
											</div>
											<div class="col-md-2">
												<div class="jobs_gross">Gross</div>		
											</div>
				
										</div>
									</div>
									
								</div>
							</div>

							<div class="form-group">
								<div class="row">
									<div class="col-md-4 text-right">
										<label for="">{{ lang.jobs_company_title }}</label>
									</div>
									<div class="col-md-6">
										<input type="text" class="form-control" placeholder="{{ lang.jobs_company_title }}" name="jobs_company_title" value="{{ jobs_info.jobs_company_title|escape|stripslashes }}">
									</div>
								</div>
							</div>

							<div class="form-group">
								<div class="row">
									<div class="col-md-4 text-right">
										<label for="">{{ lang.jobs_company_desc }}</label>
									</div>
									<div class="col-md-6">
										<textarea class="form-control" placeholder="{{ lang.jobs_company_desc }}" name="jobs_company_desc" rows="6">{{ jobs_info.jobs_company_desc|escape|stripslashes }}</textarea>
									</div>
								</div>
							</div>

							<div class="form-group">
								<div class="row">
									<div class="col-md-4 text-right">
										<label for="">{{ lang.jobs_country }}</label>
									</div>
									<div class="col-md-3">
										<select class="form-control selectpicker load_select" name="jobs_country" data-target="jobs_city" data-live-search="true">
											<option value="">{{ lang.jobs_country }}</option>
											{% for country in country_list %}
												<option value="{{ country.id|escape|stripslashes }}" {% if country.id == jobs_info.jobs_country.id %}selected{% endif %}>{{ country.title|escape|stripslashes }}</option>
											{% endfor %}
										</select>
									</div>
								</div>
							</div>

							<div class="form-group">
								<div class="row">
									<div class="col-md-4 text-right">
										<label for="">{{ lang.jobs_city }}</label>
									</div>
									<div class="col-md-3">
										<select class="form-control selectpicker" name="jobs_city" data-default="{{ lang.lk_city }}" data-live-search="true">
											<option value="">{{ lang.jobs_city }}</option>
											{% for city in city_list %}
												<option value="{{ city.id|escape|stripslashes }}" {% if city.id == jobs_info.jobs_city.id %}selected{% endif %}>{{ city.title|escape|stripslashes }}</option>
											{% endfor %}
										</select>
									</div>
								</div>
							</div>

							<div class="form-group">
								<div class="row">
									<div class="col-md-4 text-right">
										<label for="">{{ lang.jobs_stage }}</label>
									</div>
									<div class="col-md-8">
										<div class="row cash">
											<div class="col-md-2 col-sm-6 col-xs-6">
												<span class="diapazon-min-max">{{ lang.lk_budget_from }} </span>
												<input type="text" class="diapazon" placeholder="" name="jobs_stage" value="{{ jobs_info.jobs_stage|escape|stripslashes }}">
											</div>
											<div class="col-md-2 col-sm-6 col-xs-6">
												<label for="">{{ lang.jobs_stage_year }}</label>
											</div>
										</div>
									</div>
								</div>
							</div>

							<div class="form-group">
								<div class="row">
									<div class="col-md-4 text-right">
										<label for="">{{ lang.jobs_level_education }}</label>
									</div>
									<div class="col-md-6">
										<select class="form-control" name="jobs_level_education">
											<option value="">{{ lang.jobs_level_education }}</option>
											{% for education in jobs_level_education %}
												<option value="{{ education.id|escape|stripslashes }}" {% if education.id == jobs_info.jobs_level_education.id %}selected{% endif %}>{{ education.title|escape|stripslashes }}</option>
											{% endfor %}
										</select>

										<input type="text" class="form-control mt-10 {% if not jobs_info.jobs_level_education_ext %}hidden{% endif %} jobs_level_education_ext" placeholder="{{ lang.jobs_level_education_ext }}" name="jobs_level_education_ext" value="{{ jobs_info.jobs_level_education_ext|escape|stripslashes }}">
									</div>
								</div>
							</div>

							<div class="form-group">
								<div class="row">
									<div class="col-md-4 text-right">
										<label for="">{{ lang.jobs_default_lang }}</label>
									</div>
									<div class="col-md-3">
										<select class="form-control selectpicker" name="jobs_default_lang" data-live-search="true">
											<option value="">{{ lang.jobs_default_lang }}</option>
											{% for lang in lang_list %}
												<option value="{{ lang.id|escape|stripslashes }}" {% if lang.id == jobs_info.jobs_default_lang.id %}selected{% endif %}>{{ lang.title|escape|stripslashes }}</option>
											{% endfor %}
										</select>
									</div>
								</div>
							</div>

							<div class="form-group job_lang_level_list">
								<div class="row">
									<div class="col-md-4 text-right">
										<label for="">{{ lang.jobs_skill_lang }}</label>
									</div>
									<div class="col-md-7">

										{% for skill in jobs_info.jobs_skill_lang %}
											<div class="not_empty_level_list">
												<div class="row mb-10">
													<div class="col-md-5">
														<select class="form-control selectpicker" name="jobs_skill_lang[]">
															<option value="">{{ lang.jobs_skill_lang_change }}</option>
															{% for lang in lang_list %}
																<option value="{{ lang.id|escape|stripslashes }}" {% if lang.id == skill.lang_id %}selected{% endif %}>{{ lang.title|escape|stripslashes }}</option>
															{% endfor %}
														</select>
													</div>
													<div class="col-md-5">
														<select class="form-control selectpicker" name="jobs_skill_lang_level[]">
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
													<div class="col-md-5">
														<select class="form-control selectpicker" name="jobs_skill_lang[]">
															<option value="">{{ lang.jobs_skill_lang_change }}</option>
															{% for lang in lang_list %}
																<option value="{{ lang.id|escape|stripslashes }}">{{ lang.title|escape|stripslashes }}</option>
															{% endfor %}
														</select>
													</div>
													<div class="col-md-5">
														<select class="form-control selectpicker" name="jobs_skill_lang_level[]">
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
										<label for="">{{ lang.jobs_terms_employment }}</label>
									</div>
									<div class="col-md-8">
										
										{% for terms in terms_employment %}
											<label class="main_variant">
												<input class="checkbox-input" type="checkbox" name="jobs_terms_employment[]" value="{{ terms.id|escape|stripslashes }}" {% if terms.id in jobs_info.jobs_terms_employment|keys %}checked{% endif %}>
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
												<input class="checkbox-input" type="checkbox" name="jobs_work_place[]" value="{{ place.id|escape|stripslashes }}" {% if place.id in jobs_info.jobs_work_place|keys %}checked{% endif %}>
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
									<div class="col-md-6">
										
										{% for conditions in employment_conditions %}
											<label class="checkbox">
												<input class="checkbox-input" type="checkbox" name="jobs_employment_conditions[]" value="{{ conditions.id|escape|stripslashes }}" {% if conditions.id in jobs_info.jobs_employment_conditions|keys %}checked{% endif %}>
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
									<div class="col-md-6">
										
										{% for time in work_time %}
											<label class="checkbox">
												<input class="checkbox-input" type="checkbox" name="jobs_work_time[]" value="{{ time.id|escape|stripslashes }}" {% if time.id in jobs_info.jobs_work_time|keys %}checked{% endif %}>
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
										<label for="">{{ lang.jobs_desc }}</label>
									</div>
									<div class="col-md-6">
										<textarea class="form-control" placeholder="{{ lang.jobs_desc }}" name="jobs_desc" rows="5">{{ jobs_info.jobs_desc|escape|stripslashes }}</textarea>
									</div>
								</div>
							</div>

							<div class="form-group">
								<div class="row">
									<div class="col-md-4 text-right">
										<label for="">{{ lang.jobs_responsibilities }}</label>
									</div>
									<div class="col-md-6">
										<textarea class="form-control" placeholder="{{ lang.jobs_responsibilities }}" name="jobs_responsibilities" rows="6">{{ jobs_info.jobs_responsibilities|escape|stripslashes }}</textarea>
									</div>
								</div>
							</div>

							<div class="form-group">
								<div class="row">
									<div class="col-md-4 text-right">
										<label for="">{{ lang.jobs_requirements }}</label>
									</div>
									<div class="col-md-6">
										<textarea class="form-control" placeholder="{{ lang.jobs_requirements }}" name="jobs_requirements" rows="6">{{ jobs_info.jobs_requirements|escape|stripslashes }}</textarea>
									</div>
								</div>
							</div>

							<div class="form-group">
								<div class="row">
									<div class="col-md-4 text-right">
										<label for="">{{ lang.jobs_terms }}</label>
									</div>
									<div class="col-md-6">
										<textarea class="form-control" placeholder="{{ lang.jobs_terms }}" name="jobs_terms" rows="6">{{ jobs_info.jobs_terms|escape|stripslashes }}</textarea>
									</div>
								</div>
							</div>	
							
							<div class="row">
								<div class="col-md-2"></div>
								<div class="col-md-8">
									<button class="btn btn-block btn-search" type="submit">{{ lang.jobs_btn_place }}</button>
								</div>
							</div>

							<p class="error-message-color error-message"></p>
						</form>

					</div>
				</div>
		</div>

	</div>
</main>

<script>
$(function () {
	// init
	App.jobs_core();
	// init
});
</script>
