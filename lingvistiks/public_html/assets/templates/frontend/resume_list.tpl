<main class="sidebar-left">
	<div class="container">

		<ol class="breadcrumb">
			<li><a href="{{ HOST_NAME }}">{{ lang.page_breadcrumb }}</a></li>
			<li class="active text-uppercase">{{ lang.resume_list|escape|stripslashes }}</li>
		</ol>

		<h1>{{ lang.resume_list }}</h1>

		<div class="row">
			{{ perfomens_col }}

			<div class="col-md-{% if perfomens_col %}9{% else %}12{% endif %} col-sm-{% if perfomens_col %}9{% else %}12{% endif %}">
				<div class="content-right">
					<div class="profile-block">

						<div class="order-nav">
							<ul class="list-inline order_change">
								<li>
									<a href="{{ HOST_NAME }}/bank_resume/" {% if not REQUEST.filter %}class="active"{% endif %}>{{ lang.resume_all_list }}</a>
								</li>
								{% if REQUEST.filter %}
									<li>
										<a href="{{ HOST_NAME }}/bank_resume/" {% if REQUEST.filter %}class="active"{% endif %}>{{ lang.resume_all_search }}</a>
									</li>
								{% endif %}
								{% if SESSION.user_group == '3' %}
									<li>
										<a href="{{ HOST_NAME }}/bank_resume/?resume_owner={{ SESSION.user_id|escape|stripslashes }}&filter=1" {% if REQUEST.resume_owner == SESSION.user_id %}class="active"{% endif %}>{{ lang.resume_my_list }}</a>
									</li>
									<li>
										<a href="{{ HOST_NAME }}/bank_resume/?resume_draft=1&filter=1" {% if REQUEST.resume_draft == '1' %}class="active"{% endif %}>{{ lang.resume_my_draft }}</a>
									</li>
								{% endif %}
							</ul>
						</div>


						<form action="{{ HOST_NAME }}/bank_resume/" method="post" class="resume_search" enctype="multipart/form-data">
							<input type="hidden" name="csrf_token" value="{{ csrf_token }}">
							<input type="hidden" name="filter" value="1">
							<input type="hidden" name="resume_owner" value="{{ REQUEST.resume_owner|escape|stripslashes }}">

							<div class="row filter">
								<div class="col-md-3 text-right">
									<label for="">{{ lang.jobs_title }}</label>
								</div>
								<div class="col-md-3">
									<select class="form-control" name="resume_title">
										<option value="">{{ lang.jobs_title }}</option>
										{% for jobs in jobs_title %}
											<option value="{{ jobs.id|escape|stripslashes }}" {% if jobs.id == REQUEST.resume_title %}selected{% endif %}>{{ jobs.title|escape|stripslashes }}</option>
										{% endfor %}
									</select>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<select class="form-control selectpicker load_select" name="user_country" data-target="user_city" data-live-search="true">
											<option value="">{{ lang.lk_country }}</option>
											{% for country in country_list %}
												<option value="{{ country.id|escape|stripslashes }}" {% if country.id == REQUEST.user_country %}selected{% endif %}>{{ country.title|escape|stripslashes }}</option>
											{% endfor %}
										</select>
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<select class="form-control selectpicker" name="user_city" data-default="{{ lang.lk_city }}" data-live-search="true">
											<option value="">{{ lang.lk_city }}</option>
											{% for city in city_list %}
												<option value="{{ city.id|escape|stripslashes }}" {% if city.id == REQUEST.user_city %}selected{% endif %}>{{ city.title|escape|stripslashes }}</option>
											{% endfor %}
										</select>
									</div>
								</div>

								<div class="clearfix"></div>

								<div class="col-md-3 text-right">
									<label for="">{{ lang.lk_lang_default }}</label>
								</div>
								<div class="col-md-2 pr-0">
									<select class="form-control selectpicker" name="user_lang_default" data-live-search="true">
										<option value="">{{ lang.lk_lang_default }}</option>
										{% for lang in lang_list %}
											<option value="{{ lang.id|escape|stripslashes }}" {% if lang.id == REQUEST.user_lang_default %}selected{% endif %}>{{ lang.title|escape|stripslashes }}</option>
										{% endfor %}
									</select>
								</div>
								<div class="col-md-4 text-right">
									<label for="">{{ lang.jobs_skill_lang }}</label>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<select class="form-control selectpicker" name="resume_skill_lang" data-live-search="true">
											<option value="">{{ lang.jobs_skill_lang_change }}</option>
											{% for lang in lang_list %}
												<option value="{{ lang.id|escape|stripslashes }}" {% if lang.id == REQUEST.resume_skill_lang %}selected{% endif %}>{{ lang.title|escape|stripslashes }}</option>
											{% endfor %}
										</select>
									</div>
								</div>

								<div class="clearfix"></div>

								<div class="col-md-3 text-right">
									<label for="">{{ lang.jobs_coast }}</label>
								</div>
								<div class="col-md-6">
									<div class="row cash-search">
										<div class="col-md-3 col-sm-6 col-xs-6">
											<div class="form-group">
												<input type="text" class="form-control" placeholder="{{ lang.lk_budget_from }}" name="resume_coast_start" value="{{ REQUEST.resume_coast_start|escape|stripslashes }}">
											</div>
										</div>

										<div class="col-md-3 col-sm-6 col-xs-6">
											<div class="form-group">
												<input type="text" class="form-control" placeholder="{{ lang.lk_budget_to }}" name="resume_coast_end" value="{{ REQUEST.resume_coast_end|escape|stripslashes }}">
											</div>
										</div>
										
										<div class="col-md-3 col-sm-6 col-xs-6">
											<div class="form-group">
												<select class="form-control" name="resume_coast_period">
													<option value="1" {% if 1 == REQUEST.resume_coast_period %}selected{% endif %}>{{ lang.jobs_coast_month }}</option>
													<option value="2" {% if 2 == REQUEST.resume_coast_period %}selected{% endif %}>{{ lang.jobs_coast_year }}</option>
												</select>
											</div>
										</div>

										<div class="col-md-3 col-sm-6 col-xs-6">
											<div class="form-group">
												<select class="form-control" name="resume_coast_currency">
													{% for currency in currency_list %}
														<option value="{{ currency.id }}" {% if currency.id == REQUEST.resume_coast_currency %}selected{% endif %}>{{ currency.title|escape|stripslashes }}</option>
													{% endfor %}
												</select>
											</div>
										</div>
									</div>
								</div>
								<div class="col-md-2">
									<button class="btn btn-block btn-go-on">{{ lang.btn_search }}</button>
								</div>

							</div>
						</form>

						
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

					</div>
				</div>
			</div>
		</div>

		{% if page_nav %}
			{{ page_nav }}
		{% endif %}
	</div>
</main>

<script>
$(function () {
	// init
	App.search_resume_core();
	// init
});
</script>
