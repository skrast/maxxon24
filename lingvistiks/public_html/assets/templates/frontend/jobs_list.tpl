<main class="sidebar-left">
	<div class="container">

		<ol class="breadcrumb">
			<li><a href="{{ HOST_NAME }}">{{ lang.page_breadcrumb }}</a></li>
			<li class="active text-uppercase">{{ lang.jobs_list|escape|stripslashes }}</li>
		</ol>

		<h1>{{ lang.jobs_list }}</h1>

		<div class="row">
			{{ perfomens_col }}

			<div class="col-md-{% if perfomens_col %}9{% else %}12{% endif %} col-sm-{% if perfomens_col %}9{% else %}12{% endif %}">
				<div class="content-right">
					<div class="profile-block">

						<div class="order-nav">
							<ul class="list-inline order_change">
								<li>
									<a href="{{ HOST_NAME }}/bank_vakansiy/" {% if not REQUEST.filter %}class="active"{% endif %}>{{ lang.jobs_all_list }}</a>
								</li>
								{% if REQUEST.filter %}
									<li>
										<a href="{{ HOST_NAME }}/bank_vakansiy/" {% if REQUEST.filter %}class="active"{% endif %}>{{ lang.jobs_all_search }}</a>
									</li>
								{% endif %}
								{% if SESSION.user_group == '4' %}
									<li>
										<a href="{{ HOST_NAME }}/bank_vakansiy/?jobs_owner={{ SESSION.user_id|escape|stripslashes }}&filter=1" {% if REQUEST.jobs_owner == SESSION.user_id %}class="active"{% endif %}>{{ lang.jobs_my_list }}</a>
									</li>
									<li>
										<a href="{{ HOST_NAME }}/bank_vakansiy/?jobs_draft=1&filter=1" {% if REQUEST.jobs_draft == '1' %}class="active"{% endif %}>{{ lang.jobs_my_draft }}</a>
									</li>
								{% endif %}
							</ul>
						</div>

						<form action="{{ HOST_NAME }}/bank_vakansiy/" method="post" class="jobs_search" enctype="multipart/form-data">
							<input type="hidden" name="csrf_token" value="{{ csrf_token }}">
							<input type="hidden" name="filter" value="1">
							<input type="hidden" name="jobs_owner" value="{{ REQUEST.jobs_owner|escape|stripslashes }}">

							<div class="row filter">
								<div class="col-md-3 text-right">
									<label for="">{{ lang.jobs_title }}</label>
								</div>
								<div class="col-md-3">
									<select class="form-control selectpicker" name="jobs_title">
										<option value="">{{ lang.jobs_title }}</option>
										{% for jobs in jobs_title %}
											<option value="{{ jobs.id|escape|stripslashes }}" {% if jobs.id == REQUEST.jobs_title %}selected{% endif %}>{{ jobs.title|escape|stripslashes }}</option>
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
									<label for="">{{ lang.jobs_default_lang }}</label>
								</div>
								<div class="col-md-2 pr-0">
									<select class="form-control selectpicker" name="jobs_default_lang" data-live-search="true">
										<option value="">{{ lang.jobs_default_lang }}</option>
										{% for lang in lang_list %}
											<option value="{{ lang.id|escape|stripslashes }}" {% if lang.id == REQUEST.jobs_default_lang %}selected{% endif %}>{{ lang.title|escape|stripslashes }}</option>
										{% endfor %}
									</select>
								</div>
								<div class="col-md-4 text-right">
									<label for="">{{ lang.jobs_skill_lang }}</label>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<select class="form-control selectpicker" name="jobs_skill_lang" data-live-search="true">
											<option value="">{{ lang.jobs_skill_lang_change }}</option>
											{% for lang in lang_list %}
												<option value="{{ lang.id|escape|stripslashes }}" {% if lang.id == REQUEST.jobs_skill_lang %}selected{% endif %}>{{ lang.title|escape|stripslashes }}</option>
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
												<input type="text" class="form-control" placeholder="{{ lang.lk_budget_from }}" name="jobs_coast_start" value="{{ REQUEST.jobs_coast_start|escape|stripslashes }}">
											</div>
										</div>

										<div class="col-md-3 col-sm-6 col-xs-6">
											<div class="form-group">
												<input type="text" class="form-control" placeholder="{{ lang.lk_budget_to }}" name="jobs_coast_end" value="{{ REQUEST.jobs_coast_end|escape|stripslashes }}">
											</div>
										</div>
										
										<div class="col-md-3 col-sm-6 col-xs-6">
											<div class="form-group">
												<select class="form-control selectpicker" name="jobs_coast_period">
													<option value="1" {% if 1 == REQUEST.jobs_coast_period %}selected{% endif %}>{{ lang.jobs_coast_month }}</option>
													<option value="2" {% if 2 == REQUEST.jobs_coast_period %}selected{% endif %}>{{ lang.jobs_coast_year }}</option>
												</select>
											</div>
										</div>

										<div class="col-md-3 col-sm-6 col-xs-6">
											<div class="form-group">
												<select class="form-control selectpicker" name="jobs_coast_currency">
													{% for currency in currency_list %}
														<option value="{{ currency.id }}" {% if currency.id == REQUEST.jobs_coast_currency %}selected{% endif %}>{{ currency.title|escape|stripslashes }}</option>
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

						{% if page_nav %}
							{{ page_nav }}
						{% endif %}

					</div>
				</div>
			</div>
		</div>

	</div>
</main>

<script>
$(function () {
	// init
	App.search_jobs_core();
	// init
});
</script>
