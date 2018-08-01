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
		<div class="content-left jobs_page">

			<table style="width:100%;">
				<tr>
					<td style="width:50%;vertical-align:top;">
						<h2 style="    font-size: 32px;
						color: #005f73;
						font-weight: bold;
						margin-top: 0px;
						margin-bottom: 0px;">{{ jobs_info.jobs_title.title|escape|stripslashes }}</h2>
						<p><strong>{{ jobs_info.jobs_company_title|escape|stripslashes }}, {{ jobs_info.jobs_country.title|escape|stripslashes }}, {{ jobs_info.jobs_city.title|escape|stripslashes }}</strong></p>
	
						<p class="date" style="font-size: 12px;
						color: #b3bbc3;">
							{% if jobs_info.jobs_status == 1 %}
								{{ lang.jobs_update }} {% if jobs_info.jobs_edit %}{{ jobs_info.jobs_edit }}{% else %}{{ jobs_info.jobs_add }}{% endif %}
							{% else %}
								{{ lang.jobs_draft }}
							{% endif %}
						</p>
					</td>
					<td style="width:50%;text-align:right;padding:0px;vertical-align:top;">
						<div class="text-right jobs_coast_color" style="color: #d07721;
						font-size: 20px;
						font-weight: bold;">
							{% if jobs_info.jobs_coast_start %} {{ lang.lk_budget_from }} {{ jobs_info.jobs_coast_start|escape|stripslashes }} {% endif %}
							{% if jobs_info.jobs_coast_end %} {{ lang.lk_budget_to }} {{ jobs_info.jobs_coast_end|escape|stripslashes }} {% endif %}
		
							{{ jobs_info.jobs_coast_currency.title|escape|stripslashes }}/{% if jobs_info.jobs_coast_period == 1 %}{{ lang.jobs_coast_month }}{% else %}{{ lang.jobs_coast_year }}{% endif %}
							<br>
							<div class="jobs_gross" style="font-size: 16px;
							color: #006074;">Gross</div>
						</div>
					</td>
				</tr>
			</table>

			<br><p>{{ jobs_info.jobs_company_desc|escape|stripslashes|ntobr }}</p><br>
			<div class="clearfix"></div>

			<h3 style="color: #016074;
			font-weight: 700;
			margin: 0px;
			margin-bottom: 15px;
			margin-top: 10px;
			font-size: 16px;
			background: #dde2e5;
			text-align: center;
			padding: 5px 0;
			text-transform: uppercase;">{{ lang.jobs_add_title }}</h3>

			<table style="width:100%;">
				<tr>
					<td style="width:45%;vertical-align:top; font-weight: 16px;
					font-weight: bold;
					color: #000;
					margin-bottom: 20px;
					text-align: right;">
						{{ lang.jobs_stage }}
					</td>
					<td style="width:10%;"></td>
					<td style="width:45%;text-align:left;padding:0px;vertical-align:top;font-weight: 16px;
					font-weight: normal;
					color: #006074;
					margin-bottom: 20px;">
						{{ jobs_info.jobs_stage|escape|stripslashes }} {{ lang.jobs_stage_year }}
					</td>
				</tr>

				<tr>
					<td style="width:45%;vertical-align:top;font-weight: 16px;
					font-weight: bold;
					color: #000;
					margin-bottom: 20px;
					text-align: right;">
						{{ lang.jobs_level_education }}
					</td>
					<td style="width:10%;"></td>
					<td style="width:45%;text-align:left;padding:0px;vertical-align:top;font-weight: 16px;
					font-weight: normal;
					color: #006074;
					margin-bottom: 20px;">
						{% if jobs_info.jobs_level_education_ext %}
							{{ jobs_info.jobs_level_education_ext|escape|stripslashes }}
						{% else %}
							{{ jobs_info.jobs_level_education.title|escape|stripslashes }}
						{% endif %}
					</td>
				</tr>

				<tr>
					<td style="width:45%;vertical-align:top;font-weight: 16px;
					font-weight: bold;
					color: #000;
					margin-bottom: 20px;
					text-align: right;">
						{{ lang.jobs_default_lang }}
					</td>
					<td style="width:10%;"></td>
					<td style="width:45%;text-align:left;padding:0px;vertical-align:top;font-weight: 16px;
					font-weight: normal;
					color: #006074;
					margin-bottom: 20px;">
						{{ jobs_info.jobs_default_lang.title|escape|stripslashes }} 
					</td>
				</tr>

				<tr>
					<td style="width:45%;vertical-align:top;font-weight: 16px;
					font-weight: bold;
					color: #000;
					margin-bottom: 20px;
					text-align: right;">
						{{ lang.jobs_skill_lang }}
					</td>
					<td style="width:10%;"></td>
					<td style="width:45%;text-align:left;padding:0px;vertical-align:top;font-weight: 16px;
					font-weight: normal;
					color: #006074;
					margin-bottom: 20px;">
							{% for jobs_skill_lang in jobs_info.jobs_skill_lang %}
								{{ lang_list[jobs_skill_lang.lang_id].title|escape|stripslashes }} - {{ lang_level_list[jobs_skill_lang.lang_level].title|escape|stripslashes }}<br>
							{% endfor %}
					</td>
				</tr>

				<tr>
					<td style="width:45%;vertical-align:top;font-weight: 16px;
					font-weight: bold;
					color: #000;
					margin-bottom: 20px;
					text-align: right;">
						{{ lang.jobs_terms_employment }}
					</td>
					<td style="width:10%;"></td>
					<td style="width:45%;text-align:left;padding:0px;vertical-align:top;font-weight: 16px;
					font-weight: normal;
					color: #006074;
					margin-bottom: 20px;">
							{% for lang in jobs_info.jobs_terms_employment %}
								{{ lang.title|escape|stripslashes }}<br>
							{% endfor %}
					</td>
				</tr>

				<tr>
					<td style="width:45%;vertical-align:top;font-weight: 16px;
					font-weight: bold;
					color: #000;
					margin-bottom: 20px;
					text-align: right;">
						{{ lang.jobs_work_place }}
					</td>
					<td style="width:10%;"></td>
					<td style="width:45%;text-align:left;padding:0px;vertical-align:top;font-weight: 16px;
					font-weight: normal;
					color: #006074;
					margin-bottom: 20px;">
							{% for lang in jobs_info.jobs_work_place %}
								{{ lang.title|escape|stripslashes }}<br>
							{% endfor %}
					</td>
				</tr>

				<tr>
					<td style="width:45%;vertical-align:top;font-weight: 16px;
					font-weight: bold;
					color: #000;
					margin-bottom: 20px;
					text-align: right;">
						{{ lang.jobs_employment_conditions }}
					</td>
					<td style="width:10%;"></td>
					<td style="width:45%;text-align:left;padding:0px;vertical-align:top;font-weight: 16px;
					font-weight: normal;
					color: #006074;
					margin-bottom: 20px;">
							{% for lang in jobs_info.jobs_employment_conditions %}
								{{ lang.title|escape|stripslashes }}<br>
							{% endfor %}
					</td>
				</tr>

				<tr>
					<td style="width:45%;vertical-align:top;font-weight: 16px;
					font-weight: bold;
					color: #000;
					margin-bottom: 20px;
					text-align: right;">
						{{ lang.jobs_work_time }}
					</td>
					<td style="width:10%;"></td>
					<td style="width:45%;text-align:left;padding:0px;vertical-align:top;font-weight: 16px;
					font-weight: normal;
					color: #006074;
					margin-bottom: 20px;">
							{% for lang in jobs_info.jobs_work_time %}
								{{ lang.title|escape|stripslashes }}<br>
							{% endfor %}
					</td>
				</tr>

				<tr>
					<td style="width:45%;vertical-align:top;font-weight: 16px;
					font-weight: bold;
					color: #000;
					margin-bottom: 20px;
					text-align: right;">
						{{ lang.jobs_desc }}
					</td>
					<td style="width:10%;"></td>
					<td style="width:45%;text-align:left;padding:0px;vertical-align:top;font-weight: 16px;
					font-weight: normal;
					color: #006074;
					margin-bottom: 20px;">
						<p>{{ jobs_info.jobs_desc|escape|stripslashes|ntobr }}</p>
					</td>
				</tr>

				<tr>
					<td style="width:45%;vertical-align:top;font-weight: 16px;
					font-weight: bold;
					color: #000;
					margin-bottom: 20px;
					text-align: right;">
						{{ lang.jobs_responsibilities }}
					</td>
					<td style="width:10%;"></td>
					<td style="width:45%;text-align:left;padding:0px;vertical-align:top;font-weight: 16px;
					font-weight: normal;
					color: #006074;
					margin-bottom: 20px;">
						<p>{{ jobs_info.jobs_responsibilities|escape|stripslashes|ntobr }}</p>
					</td>
				</tr>

				<tr>
					<td style="width:45%;vertical-align:top;font-weight: 16px;
					font-weight: bold;
					color: #000;
					margin-bottom: 20px;
					text-align: right;">
						{{ lang.jobs_requirements }}
					</td>
					<td style="width:10%;"></td>
					<td style="width:45%;text-align:left;padding:0px;vertical-align:top;font-weight: 16px;
					font-weight: normal;
					color: #006074;
					margin-bottom: 20px;">
						<p>{{ jobs_info.jobs_requirements|escape|stripslashes|ntobr }}</p>
					</td>
				</tr>

				<tr>
					<td style="width:45%;vertical-align:top;font-weight: 16px;
					font-weight: bold;
					color: #000;
					margin-bottom: 20px;
					text-align: right;">
						{{ lang.jobs_terms }}
					</td>
					<td style="width:10%;"></td>
					<td style="width:45%;text-align:left;padding:0px;vertical-align:top;font-weight: 16px;
					font-weight: normal;
					color: #006074;
					margin-bottom: 20px;">
						<p>{{ jobs_info.jobs_terms|escape|stripslashes|ntobr }}</p>
					</td>
				</tr>
			</table>

		</div>
	</div>
</div>
</body>
</html>
{% endspaceless %}


