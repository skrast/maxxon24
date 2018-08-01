<main class="sidebar-left">
	<div class="container">

		<ol class="breadcrumb">
			<li><a href="{{ HOST_NAME }}">{{ lang.page_breadcrumb }}</a></li>
			<li><a href="{{ HOST_NAME }}/bank_resume/">{{ lang.resume_list }}</a></li>
			<li class="active text-uppercase">{{ lang.resume_page }}</li>
		</ol>

		<h1>{{ lang.resume_page }} #{{ resume_info.resume_owner }}</h1>

		<div class="row">
			{{ perfomens_col }}

			<div class="col-md-{% if perfomens_col %}9{% else %}12{% endif %} col-sm-{% if perfomens_col %}9{% else %}12{% endif %}">
				<div class="content-right">
					<div class="profile-block">
						<div class="content-left resume_page">

							<h3 class="text-center text-uppercase">{{ lang.resume_my_info }}</h3>
							<div class="row">

								<div class="col-md-4 text-center">
									{% if resume_info.resume_photo %}
										<img src="{{ ABS_PATH }}?thumb={{ app.app_upload_dir }}/{{ app.app_resume_dir }}/{{ resume_info.resume_photo }}&width=250&height=300" alt="" class="img-responsive">
									{% else %}
										{% if profile_info.user_photo %}
											<img src="{{ ABS_PATH }}?thumb={{ app.app_upload_dir }}/{{ app.app_users_dir }}/{{ profile_info.user_photo }}&width=250&height=300" alt="" class="img-responsive">
										{% else %}
											<img src="{{ ABS_PATH }}assets/site/template/images/no-photo.png" alt="" class="img-responsive">
										{% endif %}
									{% endif %}
								</div>

								<div class="col-md-8">
									<h2>{{ resume_info.resume_lastname|escape|stripslashes }} {{ resume_info.resume_firstname|escape|stripslashes }} {{ resume_info.resume_patronymic|escape|stripslashes }}</h2>

									<div class="row">
										<div class="col-md-12 text-black">
											<strong>{{ resume_info.resume_birthday|escape|stripslashes|format_date }}</strong>
										</div>
									</div>

									<div class="row mt-10">
										<div class="col-md-3 text-black">
											<strong>{{ lang.profile_from }}</strong>
										</div>
										<div class="col-md-9">
											{{ resume_info.resume_country.title|escape|stripslashes }}, {{ resume_info.resume_city.title|escape|stripslashes }}
										</div>
										<div class="col-md-3 text-black">
											<strong>{{ lang.profile_citizenship }}</strong>
										</div>
										<div class="col-md-9">
											{{ resume_info.resume_citizenship.title|escape|stripslashes }}
										</div>
									</div>

									<div class="row mt-10">
										<div class="col-md-3 text-black">
											<strong>{{ lang.profile_email }}</strong>
										</div>
										<div class="col-md-9">
											{{ resume_info.resume_email|escape|stripslashes }}
										</div>
										<div class="col-md-3 text-black">
											<strong>{{ lang.profile_phone }}</strong>
										</div>
										<div class="col-md-9">
											{{ resume_info.resume_phone|escape|stripslashes }}
										</div>
									</div>

									<div class="row mt-10">
										<div class="col-md-12 text-gray">
											<strong>{{ lang.resume_update }} {{ resume_info.resume_update|format_date }}</strong>
										</div>
									</div>

								</div>
							</div>

							<h3 class="text-center text-uppercase">{{ lang.resume_my_wish_info }}</h3>

							<div class="row">
								<div class="col-md-6">
									<h4>{{ resume_info.resume_title.title|escape|stripslashes }}</h4>
								</div>
								<div class="col-md-6 text-right jobs_coast_color">
									{% if resume_info.resume_coast_start %} {{ lang.lk_budget_from }} {{ resume_info.resume_coast_start|escape|stripslashes }} {% endif %}
									{% if resume_info.resume_coast_end %} {{ lang.lk_budget_to }} {{ resume_info.resume_coast_end|escape|stripslashes }} {% endif %}

									{{ resume_info.resume_coast_currency.title|escape|stripslashes }}/{% if resume_info.resume_coast_period == 1 %}{{ lang.jobs_coast_month }}{% else %}{{ lang.jobs_coast_year }}{% endif %}
									<span class="jobs_gross">Gross</span>
								</div>
							</div>

							<div class="row mt-10 colored">
								<div class="col-md-4">
									{{ lang.jobs_terms_employment }}
								</div>
								<div class="col-md-8">
									<ul class="list-unstyled">
										{% for terms in resume_info.resume_terms_employment %}
											<li>{{ terms.title|escape|stripslashes }}</li>
										{% endfor %}
									</ul>
								</div>
							</div>

							<div class="row colored">
								<div class="col-md-4">
									{{ lang.jobs_work_place }}
								</div>
								<div class="col-md-8">
									<ul class="list-unstyled">
										{% for place in resume_info.resume_work_place %}
											<li>{{ place.title|escape|stripslashes }}</li>
										{% endfor %}
									</ul>
								</div>
							</div>

							<div class="row colored">
								<div class="col-md-4">
									{{ lang.jobs_employment_conditions }}
								</div>
								<div class="col-md-8">
									<ul class="list-unstyled">
										{% for conditions in resume_info.resume_employment_conditions %}
											<li>{{ conditions.title|escape|stripslashes }}</li>
										{% endfor %}
									</ul>
								</div>
							</div>

							<div class="row colored">
								<div class="col-md-4">
									{{ lang.jobs_work_time }}
								</div>
								<div class="col-md-8">
									<ul class="list-unstyled">
										{% for time in resume_info.resume_work_time %}
											<li>{{ time.title|escape|stripslashes }}</li>
										{% endfor %}
									</ul>
								</div>
							</div>

							<div class="row colored">
								<div class="col-md-4">
									{{ lang.resume_ready_to_move }}
								</div>
								<div class="col-md-8">
									<ul class="list-unstyled">
										{% for ready_to_move in ready_to_move_list %}
											<li>{% if ready_to_move.id == resume_info.resume_ready_to_move %}{{ ready_to_move.title|escape|stripslashes }}{% endif %}</li>
										{% endfor %}
									</ul>
								</div>
							</div>

							<div class="row colored">
								<div class="col-md-4">
									{{ lang.resume_interview_method }}
								</div>
								<div class="col-md-8">
									<ul class="list-unstyled">
										{% for interview_method in resume_info.resume_interview_method %}
											<li>{{ interview_method.title|escape|stripslashes }}</li>
										{% endfor %}
									</ul>
								</div>
							</div>

							{% if work_list %}
								<h3 class="text-center text-uppercase">{{ lang.resume_work }}</h3>
								<ul class="list-unstyled work_list">
									{% for work in work_list %}
										<li>
											<div class="form-group">
												<div class="row">
													<div class="col-md-4 work_stage text-right">
														{{ lang.resume_work }} {{ work.stage }}

														<div class="work_stage2 text-right">
															{% if work.work_month_start and work.work_year_start %}{{ lang.resume_work_month[work.work_month_start] }} {{ work.work_year_start }}{% endif %}
															{% if work.work_month_end and work.work_year_end %} {{ lang.resume_work_month[work.work_month_end] }} {{ work.work_year_end }}{% endif %}
														</div>
													</div>
													<div class="col-md-8 company_title">
														
														{{ work.work_company|escape|stripslashes }}

														<div class="text-black">
															<strong>{{ work.work_company|escape|stripslashes }}</strong>
														</div>
													</div>
												</div>
											</div>

											<div class="form-group">
												<div class="row colored">
													<div class="col-md-4 text-right">
														<strong>{{ lang.resume_work_site }}</strong>
													</div>
													<div class="col-md-8">
														
														{{ work.work_site|escape|stripslashes }}

													</div>
												</div>
											</div>

											<div class="form-group">
												<div class="row colored">
													<div class="col-md-4 text-right">
														<strong>{{ lang.resume_work_desc }}</strong>
													</div>
													<div class="col-md-8">
														
														{{ work.work_desc|escape|stripslashes|ntobr }}

													</div>
												</div>
											</div>

											<div class="form-group">
												<div class="row colored">
													<div class="col-md-4 text-right">
														<strong>{{ lang.resume_work_result }}</strong>
													</div>
													<div class="col-md-8">
														
														{{ work.work_result|escape|stripslashes|ntobr }}

													</div>
												</div>
											</div>
										</li>
									{% endfor %}
								</ul>
							{% endif %}

							{% if resume_edu %}
								<h3>{{ lang.resume_edu }}</h3>
								<ul class="edu_list">
									{% for education in resume_edu %}
										<li>

											<div class="form-group">
												<div class="row colored">
													<div class="col-md-4 text-right">
														<strong>{{ lang.resume_edu_title }}</strong>
													</div>
													<div class="col-md-8">
														
														{% for edu in jobs_edu %}
															{% if edu.id == education.resume_edu_title %}{{ edu.title|escape|stripslashes }}{% endif %}
														{% endfor %}

													</div>
												</div>
											</div>

											<div class="form-group {% if education.resume_edu_title not in [96, 99] %}hidden{% endif %}">
												<div class="row colored">
													<div class="col-md-4 text-right">
														<strong>{{ lang.resume_edu_org }}</strong>
													</div>
													<div class="col-md-8">
														
														{{ education.resume_edu_org|escape|stripslashes }}

													</div>
												</div>
											</div>

											<div class="form-group {% if education.resume_edu_title not in [96, 99] %}hidden{% endif %}">
												<div class="row colored">
													<div class="col-md-4 text-right">
														<strong>{{ lang.resume_edu_curs }}</strong>
													</div>
													<div class="col-md-8">
														
														{{ education.resume_edu_curs|escape|stripslashes }}

													</div>
												</div>
											</div>

											<div class="form-group {% if education.resume_edu_title not in [98, 97, 94, 95, 93] %}hidden{% endif %}">
												<div class="row colored">
													<div class="col-md-4 text-right">
														<strong>{{ lang.resume_edu_univer }}</strong>
													</div>
													<div class="col-md-8">
														
														{{ education.resume_edu_univer|escape|stripslashes }}

													</div>
												</div>
											</div>

											<div class="form-group {% if education.resume_edu_title not in [97, 94, 95, 93] %}hidden{% endif %}">
												<div class="row colored">
													<div class="col-md-4 text-right">
														<strong>{{ lang.resume_edu_univer_faq }}</strong>
													</div>
													<div class="col-md-8">
														
														{{ education.resume_edu_univer_faq|escape|stripslashes }}

													</div>
												</div>
											</div>

											<div class="form-group {% if education.resume_edu_title not in [97,94,95,93,96,99] %}hidden{% endif %}">
												<div class="row colored">
													<div class="col-md-4 text-right">
														<strong>{{ lang.resume_edu_univer_spec }}</strong>
													</div>
													<div class="col-md-8">
														
														{{ education.resume_edu_univer_spec|escape|stripslashes }}

													</div>
												</div>
											</div>

											<div class="form-group {% if education.resume_edu_title not in [98,97,94,95,93,96,99] %}hidden{% endif %}">
												<div class="row colored">
													<div class="col-md-4 text-right">
														<strong>{{ lang.lk_country }}</strong>
													</div>
													<div class="col-md-8">
														
														{% for country in country_list %}
															{% if education.resume_edu_country == country.id %}{{ country.title|escape|stripslashes }}{% endif %}
														{% endfor %}

													</div>
												</div>
											</div>

											<div class="form-group {% if education.resume_edu_title not in [98,97,94,95,93,96,99] %}hidden{% endif %}">
												<div class="row colored">
													<div class="col-md-4 text-right">
														<strong>{{ lang.lk_city }}</strong>
													</div>
													<div class="col-md-8">
														
														{% for city in education.city_list %}
															{% if education.resume_edu_city == city.id %}{{ city.title|escape|stripslashes }}{% endif %}
														{% endfor %}

													</div>
												</div>
											</div>

											<div class="form-group {% if education.resume_edu_title not in [98,97,94,95,93,96,99] %}hidden{% endif %}">
												<div class="row colored">
													<div class="col-md-4 text-right">
														<strong>{{ lang.resume_work_year }}</strong>
													</div>
													<div class="col-md-8">
														
															{{ education.resume_edu_year|escape|stripslashes }}

													</div>
												</div>
											</div>

										</li>
									{% endfor %}
								</ul>
							{% endif %}

							<h3>{{ lang.resume_dop_info }}</h3>
							<div class="form-group">
								<div class="row colored">
									<div class="col-md-4 text-right">
										<strong>{{ lang.resume_dop_info_lang }}</strong>
									</div>
									<div class="col-md-8">
										<ul class="list-unstyled">
											{% for skill in resume_skill_lang %}
												<li>
													{{ skill.lang_id.title }} - {{ skill.lang_level.title }}
												</li>
											{% endfor %}
										</ul>
									</div>
								</div>
							</div>

							<div class="form-group">
								<div class="row colored">
									<div class="col-md-4 text-right">
										<strong>{{ lang.resume_dop_info_passport }}</strong>
									</div>
									<div class="col-md-8">
										{% if resume_info.resume_dop_info_passport == 1 %}{{ lang.page_yes }}{% else %}{{ lang.page_no }}{% endif %}
									</div>
								</div>
							</div>

							<div class="form-group">
								<div class="row colored">
									<div class="col-md-4 text-right">
										<strong>{{ lang.resume_dop_info_desc }}</strong>
									</div>
									<div class="col-md-8">
											{{ resume_info.resume_dop_info_desc|escape|stripslashes|ntobr }}
									</div>
								</div>
							</div>

							
							<h3>{{ lang.lk_photoalbum }}</h3>
							{% if album_list %}
								<div id="carousel_album" class="owl-carousel album-carousel">
									{% for file in album_list %}
										<div>
											<a href="{{ ABS_PATH }}{{ app.app_upload_dir }}/{{ app.app_album_resume_dir }}/{{ resume_info.resume_owner }}/{{ file.file_path|escape|stripslashes }}" data-lightbox="image-2">
												<img src="{{ ABS_PATH }}?thumb={{ app.app_upload_dir }}/{{ app.app_album_resume_dir }}/{{ resume_info.resume_owner }}/{{ file.file_path|escape|stripslashes }}&width=100&height=100" style="height:100px;width:100px;" alt="">
											</a>
										</div>
									{% endfor %}
								</div>
							{% else %}
								<div class="album_empty">{{ lang.lk_album_empty }}</div>
							{% endif %}

							<div class="row colored hidden">
								<div class="col-md-4">
									{{ lang.resume_link }}
								</div>
								<div class="col-md-8">
									<input type="text" class="form-control" value="{{ HOST_NAME }}/resume-{{ resume_info.resume_owner }}/" readonly>
								</div>
							</div>

							<div class="row">
								{% if SESSION.user_id != resume_info.resume_owner and SESSION.user_group == 4 and resume_info.resume_status == 1 %}
									<div class="col-md-6">
										<a href="" class="btn btn-block btn-search perfomens_submit get_ajax_form" data-ajax="1" data-type="siteResume" data-sub="resume_access" data-void="{{ resume_info.resume_owner }}" data-close="1">{{ lang.jobs_perfomens_submit }}</a>
									</div>
									<div class="col-md-6">
										<a href="{{ HOST_NAME }}/resume/print-{{ resume_info.resume_owner }}/" class="btn btn-block btn-search perfomens_submit hidden">{{ lang.lk_print }}</a>
									</div>
								{% elseif SESSION.user_id == resume_info.resume_owner %}
									<div class="col-md-3">
										<a href="{{ HOST_NAME }}/resume/update-{{ resume_info.resume_owner }}/" class="btn btn-block btn-search perfomens_submit">{{ lang.jobs_update_lnk }}</a>
									</div>
									<div class="col-md-3">
										<a href="{{ HOST_NAME }}/resume/" class="btn btn-block btn-search perfomens_submit">{{ lang.jobs_edit }}</a>
									</div>
									<div class="col-md-3">
										{% if resume_info.resume_status == 1 %}
											<a href="{{ HOST_NAME }}/resume-{{ resume_info.resume_owner }}/close/" class="btn btn-block btn-search perfomens_submit">{{ lang.jobs_close }}</a>
										{% else %}
											<a href="{{ HOST_NAME }}/resume-{{ resume_info.resume_owner }}/open/" class="btn btn-block btn-search perfomens_submit">{{ lang.jobs_open }}</a>
										{% endif %}
									</div>

									<div class="col-md-3">
										{% if resume_info.resume_status != 1 %}
											<a href="{{ HOST_NAME }}/resume-{{ resume_info.resume_owner }}/delete/" class="btn btn-block btn-search perfomens_submit">{{ lang.jobs_delete }}</a>
										{% else  %}
											<a href="{{ HOST_NAME }}/resume/print-{{ resume_info.resume_owner }}/" class="btn btn-block btn-search perfomens_submit hidden">{{ lang.lk_print }}</a>
										{% endif %}
									</div>
								{% endif %}
							</div>
							
						</div>
					</div>
				</div>
			</div>
		</div>

	</div>
</main>


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

  });
</script>

<script>
$(function () {
	// init
	App.resume_core();
	App.profile_open_core();
	// init
});
</script>
