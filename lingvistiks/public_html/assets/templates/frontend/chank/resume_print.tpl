{% spaceless %}
<!DOCTYPE html>
<html lang="ru">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

	<!-- Bootstrap styles -->
	<link rel="stylesheet" href="{{ ABS_PATH }}assets/site/assets/bootstrap-v-3.3.2/css/bootstrap.min.css">

	<!-- Main styles -->
	<link rel="stylesheet" href="{{ ABS_PATH }}assets/site/template/styles/style.css">

</head>

<body>
<div class="container content-right">
	<div class="profile-block">
		<div class="content-left resume_page">

			<h3 class="text-center text-uppercase" style="color: #016074;
			font-weight: 700;
			margin: 0px;
			margin-bottom: 15px;
			margin-top: 10px;
			font-size: 16px;
			background: #dde2e5;
			text-align: center;
			padding: 5px 0;
			text-transform: uppercase;">{{ lang.resume_my_info }}</h3>


			<h2>{{ resume_info.resume_lastname|escape|stripslashes }} {{ resume_info.resume_firstname|escape|stripslashes }} {{ resume_info.resume_patronymic|escape|stripslashes }}</h2>
			

			<table style="width:100%;">
				<tr>
					<td style="width:40%;text-align:left;vertical-align:top;">
							<strong>{{ resume_info.resume_birthday|escape|stripslashes|format_date }}</strong>
					</td>
					<td style="width:20%;"></td>
					<td style="width:50%;text-align:left;padding:0px;vertical-align:top;">
						
					</td>
				</tr>

				<tr>
					<td style="width:40%;text-align:left;vertical-align:top;">
						<strong>{{ lang.profile_from }}</strong>
					</td>
					<td style="width:10%;"></td>
					<td style="width:50%;text-align:left;padding:0px;vertical-align:top;">
						{{ resume_info.resume_country.title|escape|stripslashes }}, {{ resume_info.resume_city.title|escape|stripslashes }}
					</td>
				</tr>

				<tr>
					<td style="width:40%;text-align:left;vertical-align:top;">
						<strong>{{ lang.profile_citizenship }}</strong>
					</td>
					<td style="width:10%;"></td>
					<td style="width:50%;text-align:left;padding:0px;vertical-align:top;">
							{{ resume_info.resume_citizenship.title|escape|stripslashes }}
					</td>
				</tr>

				<tr>
					<td style="width:40%;text-align:left;vertical-align:top;">
						<strong>{{ lang.profile_email }}</strong>
					</td>
					<td style="width:10%;"></td>
					<td style="width:50%;text-align:left;padding:0px;vertical-align:top;">
							{{ resume_info.resume_email|escape|stripslashes }}
					</td>
				</tr>

				<tr>
					<td style="width:40%;text-align:left;vertical-align:top;">
						<strong>{{ lang.profile_phone }}</strong>
					</td>
					<td style="width:10%;"></td>
					<td style="width:50%;text-align:left;padding:0px;vertical-align:top;">
							{{ resume_info.resume_phone|escape|stripslashes }}
					</td>
				</tr>

			</table>
			<strong>{{ lang.resume_update }} {{ resume_info.resume_update|format_date }}</strong>

			<h3 class="text-center text-uppercase" style="color: #016074;
			font-weight: 700;
			margin: 0px;
			margin-bottom: 15px;
			margin-top: 10px;
			font-size: 16px;
			background: #dde2e5;
			text-align: center;
			padding: 5px 0;
			text-transform: uppercase;">{{ lang.resume_my_wish_info }}</h3>

			<h4>{{ resume_info.resume_title.title|escape|stripslashes }}</h4>
			<div class="col-md-6 text-right jobs_coast_color" style="color: #d07721;
			font-size: 20px;
			font-weight: bold;">
				{% if resume_info.resume_coast_start %} {{ lang.lk_budget_from }} {{ resume_info.resume_coast_start|escape|stripslashes }} {% endif %}
				{% if resume_info.resume_coast_end %} {{ lang.lk_budget_to }} {{ resume_info.resume_coast_end|escape|stripslashes }} {% endif %}

				{{ resume_info.resume_coast_currency.title|escape|stripslashes }}/{% if resume_info.resume_coast_period == 1 %}{{ lang.jobs_coast_month }}{% else %}{{ lang.jobs_coast_year }}{% endif %}
			
				<span class="jobs_gross" style="font-size: 16px;
				color: #006074;">Gross</span>
			</div>

			<div class="row mt-10 colored">
				<div class="col-md-3">
					{{ lang.jobs_terms_employment }}
				</div>
				<div class="col-md-9">
						{% for terms in resume_info.resume_terms_employment %}
							{{ terms.title|escape|stripslashes }}<br>
						{% endfor %}
				</div>
			</div>

			<div class="row colored">
				<div class="col-md-3">
					{{ lang.jobs_work_place }}
				</div>
				<div class="col-md-9">
						{% for place in resume_info.resume_work_place %}
							{{ place.title|escape|stripslashes }}<br>
						{% endfor %}
				</div>
			</div>

			<div class="row colored">
				<div class="col-md-3">
					{{ lang.jobs_employment_conditions }}
				</div>
				<div class="col-md-9">
						{% for conditions in resume_info.resume_employment_conditions %}
							{{ conditions.title|escape|stripslashes }}<br>
						{% endfor %}
				</div>
			</div>

			<div class="row colored">
				<div class="col-md-3">
					{{ lang.jobs_work_time }}
				</div>
				<div class="col-md-9">
						{% for time in resume_info.resume_work_time %}
							{{ time.title|escape|stripslashes }}<br>
						{% endfor %}
				</div>
			</div>

			<div class="row colored">
				<div class="col-md-3">
					{{ lang.resume_ready_to_move }}
				</div>
				<div class="col-md-9">
						{% for ready_to_move in ready_to_move_list %}
							{% if ready_to_move.id == resume_info.resume_ready_to_move %}{{ ready_to_move.title|escape|stripslashes }}{% endif %}<br>
						{% endfor %}
				</div>
			</div>

			<div class="row colored">
				<div class="col-md-3">
					{{ lang.resume_interview_method }}
				</div>
				<div class="col-md-9">
						{% for interview_method in resume_info.resume_interview_method %}
							{{ interview_method.title|escape|stripslashes }}<br>
						{% endfor %}
				</div>
			</div>

			{% if work_list %}
				<h3 class="text-center text-uppercase">{{ lang.resume_work }}</h3>
					{% for work in work_list %}
							<div class="form-group">
								<div class="row">
									<div class="col-md-3 work_stage text-right">
										{{ lang.resume_work }} {{ work.stage }}
									</div>
									<div class="col-md-9 company_title">
										
										{{ work.work_company|escape|stripslashes }}

									</div>
								</div>
							</div>
							<div class="form-group">
								<div class="row">
									<div class="col-md-3 work_stage2 text-right">
										{% if work.work_month_start and work.work_year_start %}{{ lang.resume_work_month[work.work_month_start] }} {{ work.work_year_start }}{% endif %}
										{% if work.work_month_end and work.work_year_end %} {{ lang.resume_work_month[work.work_month_end] }} {{ work.work_year_end }}{% endif %}
									</div>
									<div class="col-md-9 text-black">
										
										<strong>{{ work.work_company|escape|stripslashes }}</strong>

									</div>
								</div>
							</div>

							<div class="form-group">
								<div class="row colored">
									<div class="col-md-3 text-right">
										<strong>{{ lang.resume_work_site }}</strong>
									</div>
									<div class="col-md-9">
										
										{{ work.work_site|escape|stripslashes }}

									</div>
								</div>
							</div>

							<div class="form-group">
								<div class="row colored">
									<div class="col-md-3 text-right">
										<strong>{{ lang.resume_work_desc }}</strong>
									</div>
									<div class="col-md-9">
										
										{{ work.work_desc|escape|stripslashes|ntobr }}

									</div>
								</div>
							</div>

							<div class="form-group">
								<div class="row colored">
									<div class="col-md-3 text-right">
										<strong>{{ lang.resume_work_result }}</strong>
									</div>
									<div class="col-md-9">
										
										{{ work.work_result|escape|stripslashes|ntobr }}

									</div>
								</div>
							</div>
							<br>
					{% endfor %}
			{% endif %}

			{% if resume_edu %}
				<h3 style="color: #016074;
				font-weight: 700;
				margin: 0px;
				margin-bottom: 15px;
				margin-top: 10px;
				font-size: 16px;
				background: #dde2e5;
				text-align: center;
				padding: 5px 0;
				text-transform: uppercase;">{{ lang.resume_edu }}</h3>
					{% for education in resume_edu %}

							<div class="form-group">
								<div class="row colored">
									<div class="col-md-3 text-right">
										<strong>{{ lang.resume_edu_title }}</strong>
									</div>
									<div class="col-md-9">
										
										{% for edu in jobs_edu %}
											{% if edu.id == education.resume_edu_title %}{{ edu.title|escape|stripslashes }}{% endif %}
										{% endfor %}

									</div>
								</div>
							</div>

							<div class="form-group {% if education.resume_edu_title not in [96, 99] %}hidden{% endif %}">
								<div class="row colored">
									<div class="col-md-3 text-right">
										<strong>{{ lang.resume_edu_org }}</strong>
									</div>
									<div class="col-md-9">
										
										{{ education.resume_edu_org|escape|stripslashes }}

									</div>
								</div>
							</div>

							<div class="form-group {% if education.resume_edu_title not in [96, 99] %}hidden{% endif %}">
								<div class="row colored">
									<div class="col-md-3 text-right">
										<strong>{{ lang.resume_edu_curs }}</strong>
									</div>
									<div class="col-md-9">
										
										{{ education.resume_edu_curs|escape|stripslashes }}

									</div>
								</div>
							</div>

							<div class="form-group {% if education.resume_edu_title not in [98, 97, 94, 95, 93] %}hidden{% endif %}">
								<div class="row colored">
									<div class="col-md-3 text-right">
										<strong>{{ lang.resume_edu_univer }}</strong>
									</div>
									<div class="col-md-9">
										
										{{ education.resume_edu_univer|escape|stripslashes }}

									</div>
								</div>
							</div>

							<div class="form-group {% if education.resume_edu_title not in [97, 94, 95, 93] %}hidden{% endif %}">
								<div class="row colored">
									<div class="col-md-3 text-right">
										<strong>{{ lang.resume_edu_univer_faq }}</strong>
									</div>
									<div class="col-md-9">
										
										{{ education.resume_edu_univer_faq|escape|stripslashes }}

									</div>
								</div>
							</div>

							<div class="form-group {% if education.resume_edu_title not in [97,94,95,93,96,99] %}hidden{% endif %}">
								<div class="row colored">
									<div class="col-md-3 text-right">
										<strong>{{ lang.resume_edu_univer_spec }}</strong>
									</div>
									<div class="col-md-9">
										
										{{ education.resume_edu_univer_spec|escape|stripslashes }}

									</div>
								</div>
							</div>

							<div class="form-group {% if education.resume_edu_title not in [98,97,94,95,93,96,99] %}hidden{% endif %}">
								<div class="row colored">
									<div class="col-md-3 text-right">
										<strong>{{ lang.lk_country }}</strong>
									</div>
									<div class="col-md-9">
										
										{% for country in country_list %}
											{% if education.resume_edu_country == country.id %}{{ country.title|escape|stripslashes }}{% endif %}
										{% endfor %}

									</div>
								</div>
							</div>

							<div class="form-group {% if education.resume_edu_title not in [98,97,94,95,93,96,99] %}hidden{% endif %}">
								<div class="row colored">
									<div class="col-md-3 text-right">
										<strong>{{ lang.lk_city }}</strong>
									</div>
									<div class="col-md-9">
										
										{% for city in education.city_list %}
											{% if education.resume_edu_city == city.id %}{{ city.title|escape|stripslashes }}{% endif %}
										{% endfor %}

									</div>
								</div>
							</div>

							<div class="form-group {% if education.resume_edu_title not in [98,97,94,95,93,96,99] %}hidden{% endif %}">
								<div class="row colored">
									<div class="col-md-3 text-right">
										<strong>{{ lang.resume_work_year }}</strong>
									</div>
									<div class="col-md-9">
										
											{{ education.resume_edu_year|escape|stripslashes }}

									</div>
								</div>
							</div>

							<br>
					{% endfor %}
			{% endif %}


			<h3 style="color: #016074;
			font-weight: 700;
			margin: 0px;
			margin-bottom: 15px;
			margin-top: 10px;
			font-size: 16px;
			background: #dde2e5;
			text-align: center;
			padding: 5px 0;
			text-transform: uppercase;">{{ lang.resume_dop_info }}</h3>
			<div class="form-group">
				<div class="row colored">
					<div class="col-md-3 text-right">
						<strong>{{ lang.resume_dop_info_lang }}</strong>
					</div>
					<div class="col-md-9">
							{% for skill in resume_skill_lang %}
									{{ skill.lang_id.title }} - {{ skill.lang_level.title }}<br>
							{% endfor %}
					</div>
				</div>
			</div>

			<div class="form-group">
				<div class="row colored">
					<div class="col-md-3 text-right">
						<strong>{{ lang.resume_dop_info_passport }}</strong>
					</div>
					<div class="col-md-9">
						{% if resume_info.resume_dop_info_passport == 1 %}{{ lang.page_yes }}{% else %}{{ lang.page_no }}{% endif %}
					</div>
				</div>
			</div>

			<div class="form-group">
				<div class="row colored">
					<div class="col-md-3 text-right">
						<strong>{{ lang.resume_dop_info_desc }}</strong>
					</div>
					<div class="col-md-9">
							{{ resume_info.resume_dop_info_desc|escape|stripslashes|ntobr }}
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
</body>
</html>
{% endspaceless %}


