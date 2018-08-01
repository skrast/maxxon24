<main class="no-sidebar">
	<div class="container">

		<ol class="breadcrumb">
			<li><a href="{{ HOST_NAME }}">{{ lang.page_breadcrumb }}</a></li>
			<li class="active text-uppercase">{{ profile_info.user_group_name|escape|stripslashes }}</li>
		</ol>

		<h1>
			{{ profile_info.user_group_name|escape|stripslashes }}
		</h1>
		<div class="row">

			<div class="pull-right order-nav">
				<ul class="list-inline hidden">
					{% for key, skill in lang.lk_skill_array %}
						<li>
							<a href="#" data-type="{{ key }}" {% if key in skill_list|keys %}class="active"{% endif %}>{{ skill|escape|stripslashes }}</a>
						</li>
					{% endfor %}
				</ul>
			</div>

			{{ perfomens_col }}

			<div class="col-md-{% if perfomens_col %}9{% else %}12{% endif %} col-sm-{% if perfomens_col %}9{% else %}12{% endif %}">
				<div class="content-right">
					<div class="profile-block">

						<div class="row">
							<div class="col-md-3">
								<img src="{{ ABS_PATH }}?thumb={{ app.app_upload_dir }}/users/{{ profile_info.user_photo }}&width=250&height=300" alt="" class="img-responsive">
								{{ profile_info.user_rating_tpl }}

								<div class="date_reg_user_profile">
									{{ lang.lk_open_reg }}<br>{{ profile_info.user_regtime|escape|stripslashes }}
								</div>
							</div>

							<div class="col-md-9">
								<div class="profile-feedback">

									<div class="lk_height_fix">
										<div class="pull-left">
											<ul class="list-inline left-border">
												<li>{{ profile_info.user_login|escape|stripslashes }}</li>
												<li>{{ profile_info.full_user_id }}</li>
												<li>
													{% if profile_info.user_online_status == 1 %}
														<span class="status-p status-on">{{ lang.lk_online }}</span>
													{% else %}
														<span class="status-p status-busy">{{ lang.lk_offline }}</span>
													{% endif %}
												</li>
											</ul>

											<div class="user_country"><strong>
												{% for country in country_list %}{% if country.id == profile_info.user_country %}{{ country.title|escape|stripslashes }}{% endif %}{% endfor %}{% for city in city_list %}{% if city.id == profile_info.user_city %}, {{ city.title|escape|stripslashes }}{% endif %}{% endfor %}
											</strong></div>
										</div>

										<div class="pull-right text-right right-orange-info">
											<div class="owner">
												{% for skill in skill_list %}
													{{ lang.lk_skill_lang_array[profile_info.user_type_form][skill.skill_type]|escape|stripslashes }}<br>
												{% endfor %}
											</div>
											{{ lang.lk_open_total_work }} {{ profile_info.user_work|escape|stripslashes|default(0) }}
										</div>
									</div>

									<div class="clearfix"></div>

									<div class="plate1">{{ lang.lk_lang_default }}
										{% if profile_info.user_default_lang %}{{ profile_info.user_default_lang.title|escape|stripslashes }}{% else %}{{ lang.lk_user_default_lang_empty}}{% endif %}
									</div>

									<div class="row">
										<div class="col-md-4">
											{% if serv_lang %}
												<div class="plate2">{{ lang.lk_lang_var }}</div>
												<div id="content-1" class="content mCustomScrollbar">
													<ul class="list-unstyled">
														{% for lang in serv_lang %}
															<li>
																{{ lang.serv_lang_from.title|escape|stripslashes }} - {{ lang.serv_lang_to.title|escape|stripslashes }}
															</li>
														{% endfor %}
													</ul>
												</div>
											{% endif %}
										</div>
										<div class="col-md-8">
											{% if serv_list %}
												<div id="carouselSix" class="owl-carousel">
													<div><ul>
													{% for serv in serv_list %}

														<li>
															<strong>
																{{ serv.serv_type_service.title|escape|stripslashes }}
															</strong><br>
															{{ serv.serv_coast }} {{ serv.serv_currency.title|escape|stripslashes }} / {{ serv.serv_time.title|escape|stripslashes }}<br>
															{{ serv.serv_lang_from.title|escape|stripslashes }} - {{ serv.serv_lang_to.title|escape|stripslashes }}
														</li>

														{% if loop.index is even %}</ul></div><div><ul>{% endif %}
													{% endfor %}
													</ul></div>
												</div>
											{% endif %}
										</div>
									</div>

								</div>
							</div>
						</div>


						{% if album_list and SESSION.user_id %}
							<div class="clearfix"></div>
							<div id="carousel_album" class="owl-carousel album-carousel">
								{% for file in album_list %}
									<div>
										<a href="{{ ABS_PATH }}{{ app.app_upload_dir }}/{{ app.app_album_dir }}/{{ profile_info.id }}/{{ file.file_path|escape|stripslashes }}" data-lightbox="image-2">
											<img src="{{ ABS_PATH }}?thumb={{ app.app_upload_dir }}/{{ app.app_album_dir }}/{{ profile_info.id }}/{{ file.file_path|escape|stripslashes }}&width=100&height=100" style="height:100px;width:100px;" alt="">
										</a>
									</div>
								{% endfor %}
							</div>
						{% endif %}

						{% if profile_info.id != SESSION.user_id and SESSION.user_id %}
							<div class="row">
								<div class="col-md-6">
									<a href="{{ HOST_NAME }}/message/?message_to={{ profile_info.id }}" class="btn btn-block btn-go-on">{{ lang.btn_write_message }}</a>
								</div>
								<div class="col-md-6">
									<button type="button" class="btn btn-block btn-go-on lets_be_friends" data-id="{{ profile_info.id }}" data-yes="{{ lang.friends_delete_friends }}" data-no="{{ lang.friends_add_friends }}">
										{% if is_friend %}{{ lang.friends_delete_friends }}{% else %}{{ lang.friends_add_friends }}{% endif %}
									</button>
								</div>
							</div>
						{% endif %}

						{% if profile_info.user_type_form == 2 %}
							<div class="clearfix"></div>
							<h3>{{ lang.lk_user_company_info }}</h3>
							<div class="row">
								<div class="col-md-2">
									{% if company_info.company_photo %}
										<img src="{{ ABS_PATH }}?thumb={{ app.app_upload_dir }}/{{ app.app_company_dir }}/{{ company_info.company_photo }}&width=200&height=200" alt="" class="img-responsive">
									{% else %}
										<img src="" alt="" class="img-responsive hidden">
										<div class="company_photo_empty">{{ lang.lk_user_company_photo_empty }}</div>
									{% endif %}
								</div>

								<div class="col-md-10">
									<p><strong>{{ company_info.company_title|escape|stripslashes }}</strong></p>
									<p>{{ company_info.company_group|escape|stripslashes }}</p>
									<p>{{ company_info.company_type|escape|stripslashes }}</p>
									<p>{{ company_info.company_site|escape|stripslashes }}</p>
									<p>{{ company_info.company_desc|escape|stripslashes|ntobr }}</p>
								</div>
							</div>
						{% endif %}

						{% if 
							(1 in skill_list|keys and profile_info.user_experience_1 <= "now"|date("Y") or profile_info.user_theme) or 
							(2 in skill_list|keys and profile_info.user_experience_2 <= "now"|date("Y") or profile_info.user_level_2 or profile_info.user_age_2 or profile_info.user_place_work) or 
							(3 in skill_list|keys and profile_info.user_experience_3 <= "now"|date("Y") or profile_info.user_service_3 or place_var ) 
						%}
							<h3>{{ lang.lk_info }}</h3>
							<div class="skill_info_in_profile_block">
								{% if skill_list %}

									{% if 1 in skill_list|keys and profile_info.user_experience_1 %}
										<div class="skill_info_in_profile">
											{% if profile_info.user_experience_1 %}
												<div class="row">
													<div class="col-md-3">
														<label>{{ lang.lk_info_experience_1 }}</label>
													</div>
													<div class="col-md-9">
														{{ ("now"|date("Y")-profile_info.user_experience_1)|format_year }}
													</div>
												</div>
											{% endif %}

											{% if profile_info.user_theme %}
												<div class="row">
													<div class="col-md-3">
														<label>{{ lang.lk_info_theme }}</label>
													</div>
													<div class="col-md-9">
														{% for theme in theme_list %}{% if theme.id in profile_info.user_theme %}{{ theme.title|escape|stripslashes }}; {% endif %}{% endfor %}
													</div>
												</div>
											{% endif %}
										</div>
									{% endif %}

									{% if 2 in skill_list|keys and profile_info.user_experience_2 %}
										<div class="skill_info_in_profile">
											{% if profile_info.user_experience_2 %}
												<div class="row">
													<div class="col-md-3">
														<label>{{ lang.lk_info_experience_2 }}</label>
													</div>
													<div class="col-md-9">
														{{ ("now"|date("Y")-profile_info.user_experience_2)|format_year }}
													</div>
												</div>
											{% endif %}
											
											<div class="row">
												<div class="col-md-6">
													{% if profile_info.user_level_2 %}
														<div class="row">
															<div class="col-md-6">
																<label>{{ lang.lk_info_level_2 }}</label>
															</div>
															<div class="col-md-6">
																<ul class="list-unstyled">
																	{% for level in lang_level %}
																		{% if level.id in profile_info.user_level_2 %}
																			<li>
																				{{ level.title|escape|stripslashes }}
																			</li>
																		{% endif %}
																	{% endfor %}
																</ul>
															</div>
														</div>
													{% endif %}
												</div>
												<div class="col-md-6">
													{% if profile_info.user_age_2 %}
														<div class="row">
															<div class="col-md-6">
																<label>{{ lang.lk_info_student_ready }}</label>
															</div>
															<div class="col-md-6">
																<ul class="list-unstyled">
																	{% for age in lang_age %}
																		{% if age.id in profile_info.user_age_2 %}
																			<li>
																				{{ age.title|escape|stripslashes }}
																			</li>
																		{% endif %}
																	{% endfor %}
																</ul>
															</div>
														</div>
													{% endif %}
												</div>
											</div>

											{% if profile_info.user_place_work %}
												<div class="row">
													<div class="col-md-3">
														<label>{{ lang.lk_info_place_work }}</label>
													</div>
													<div class="col-md-9">
														<ul class="list-unstyled">
															{% for place in place_work_list %}
																{% if place.id in profile_info.user_place_work %}
																	<li>
																		{{ place.title|escape|stripslashes }}
																	</li>
																{% endif %}
															{% endfor %}
														</ul>
													</div>
												</div>
											{% endif %}
										</div>
									{% endif %}

									{% if 3 in skill_list|keys and profile_info.user_experience_3 %}
										<div class="skill_info_in_profile">
											{% if profile_info.user_experience_3 %}
												<div class="row">
													<div class="col-md-3">
														<label>{{ lang.lk_info_experience_3 }}</label>
													</div>
													<div class="col-md-9">
														{{ ("now"|date("Y")-profile_info.user_experience_3)|format_year }}
													</div>
												</div>
											{% endif %}

											{% if profile_info.user_service_3 %}
												<div class="row">
													<div class="col-md-3">
														<label>{{ lang.lk_info_level_3 }}</label>
													</div>
													<div class="col-md-9">
														<ul class="list-unstyled">
															{% for service in lang_service %}
																{% if service.id in profile_info.user_service_3 %}
																	<li>
																		{{ service.title|escape|stripslashes }}
																	</li>
																{% endif %}
															{% endfor %}
														</ul>
													</div>
												</div>
											{% endif %}

											{% if place_var %}
												<div class="row">
													<div class="col-md-3">
														<label>{{ lang.lk_country_and_city_place_3 }}</label>
													</div>
													<div class="col-md-9">
														<ul class="list-unstyled">
															{% for place in place_var %}
																<li>
																	<strong>{% for country in country_list %}
																		{% if country.id == place.country_id %}{{ country.title|escape|stripslashes }}, {% endif %}
																	{% endfor %}
																	{% for city in place.city %}
																		{% if city.id == place.city_id %}{{ city.title|escape|stripslashes }}.{% endif %}
																	{% endfor %}</strong>
																	{% for ptext in place.place_text %}{% if ptext %}{{ ptext|escape|stripslashes }}; {% endif %}{% endfor %}
																</li>
															{% endfor %}
														</ul>
													</div>
												</div>
											{% endif %}
										</div>
									{% endif %}
								{% else %}
									<div class="album_empty serv_var_empty">{{ lang.lk_serv_list_empty }}</div>
								{% endif %}
							</div>
						{% endif %}

						<div class="clearfix"></div>
						<h3>{{ lang.lk_pay }}</h3>
						{% if profile_info.user_pays %}
							<div class="row text-center pay_variant_show">
								<ul class="list-inline">
									{% if 1 in profile_info.user_pays %}
										<li>
											<img src="{{ ABS_PATH }}assets/site/template/images/card-{{ SESSION.user_lang|default(app.app_lang) }}.png" alt="" class="">
										</li>
									{% endif %}
									{% if 2 in profile_info.user_pays %}
										<li>
											<img src="{{ ABS_PATH }}assets/site/template/images/bank-{{ SESSION.user_lang|default(app.app_lang) }}.png" alt="" class="">
										</li>
									{% endif %}
									{% if 3 in profile_info.user_pays %}
										<li class="hidden">
											<img src="{{ ABS_PATH }}assets/site/template/images/nal-{{ SESSION.user_lang|default(app.app_lang) }}.png" alt="" class="">
										</li>
									{% endif %}
									{% if 4 in profile_info.user_pays %}
										<li>
											<img src="{{ ABS_PATH }}assets/site/template/images/ymoney.png" alt="" class="">
										</li>
									{% endif %}
									{% if 5 in profile_info.user_pays %}
										<li>
											<img src="{{ ABS_PATH }}assets/site/template/images/paypal.png" alt="" class="">
										</li>
									{% endif %}
								</ul>
							</div>
						{% else %}
							<div class="album_empty">{{ lang.lk_pays_empty }}</div>
						{% endif %}

						<div class="clearfix"></div>
						<h3>{{ lang.lk_otziv_owner }}</h3>
						{% if otziv_list %}
							<div class="row reviews">
								{% for otziv in otziv_list %}
									<div class="col-md-2 left-section text-center">
										<img src="{{ ABS_PATH }}?thumb={{ app.app_upload_dir }}/users/{{ otziv.otziv_owner.user_photo }}&width=250&height=300" alt="" class="img-responsive">
										<p>{{ otziv.otziv_owner.user_login|escape|stripslashes }}<br>{{ otziv.otziv_owner.full_user_id|escape|stripslashes }}</p>
									</div>

									<div class="col-md-10 right-section">
										<div class="review-info">
											<p class="date pull-left">{{ otziv.otziv_date }}</p>
											<div class="profile-raiting pull-right">
												<ul class="unit-rating-small" style="width: 80px;">
													<li class="current-rating bar" style="width: {{ otziv.otziv_star*16 }}px;"></li>
													<li><a href="" class="r1-unit rater" rel="nofollow" title="1">1</a></li>
													<li><a href="" class="r2-unit rater" rel="nofollow" title="2">2</a></li>
													<li><a href="" class="r3-unit rater" rel="nofollow" title="3">3</a></li>
													<li><a href="" class="r4-unit rater" rel="nofollow" title="4">4</a></li>
													<li><a href="" class="r5-unit rater" rel="nofollow" title="5">5</a></li>
												</ul>
											</div>

											<div class="clearfix"></div>
											<h4>{{ otziv.order_title }}</h4>
											<p>{{ otziv.otziv_text|escape|stripslashes|ntobr }}</p>
										</div>
									</div>
									<div class="clearfix"></div>
								{% endfor %}

								<div class="col-md-12 text-center">
									{% if page_nav %}
										{{page_nav}}
									{% endif %}
								</div>
							</div>
						{% else %}
							<div class="album_empty">{{ lang.lk_otziv_empty }}</div>
						{% endif %}

					</div>
				</div>
			</div>
		</div>
	</div>
</main>

<link rel="stylesheet" href="{{ ABS_PATH }}assets/site/assets/malihu-custom-scrollbar-plugin-master/jquery.mCustomScrollbar.css">
<script src="{{ ABS_PATH }}assets/site/assets/malihu-custom-scrollbar-plugin-master/jquery.mCustomScrollbar.concat.min.js"></script>

<link rel="stylesheet" href="{{ ABS_PATH }}assets/site/assets/lightbox/css/lightbox.css" />
<script src="{{ ABS_PATH }}assets/site/assets/lightbox/js/lightbox.min.js"></script>

<script src="{{ ABS_PATH }}assets/site/assets/jquery-mousewheel/jquery.mousewheel.min.js"></script>
<script type="text/javascript">
	$(function () {
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
  $(document).ready(function(){
	// init
  	App.profile_open_core();
  	// init

	$('#carouselTwo').owlCarousel({
		items:3,
  	  autoplay:true,
  	  nav:true,
  	  autoWidth:false,
  	  margin:30,
  	  loop:true,
	  navText:["<img src='{{ ABS_PATH }}assets/site/template/images/prev.png'>","<img src='{{ ABS_PATH }}assets/site/template/images/next.png'>"]
	});

	$('#carouselThree').owlCarousel({
	  items:7,
	  autoplay:true,
	  nav:true,
	  autoWidth:true,
	  margin:0,
	  loop:false,
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
        loop:false,
        navText:["<img src='{{ ABS_PATH }}assets/site/template/images/prev.png'>","<img src='{{ ABS_PATH }}assets/site/template/images/next.png'>"]
      });

	  $("#content-1").mCustomScrollbar({
          scrollButtons:{enable:true},
          theme:"dark",
          axis:"y",
          setHeight: 180,
          scrollbarPosition: "outside"
        });

	$('#carouselSix').owlCarousel({
        items:2,
        autoplay:true,
        nav:true,
        autoWidth:false,
        margin:20,
        loop:false,
        navText:["<img src='{{ ABS_PATH }}assets/site/template/images/prev.png'>","<img src='{{ ABS_PATH }}assets/site/template/images/next.png'>"]
      });

  });
</script>
