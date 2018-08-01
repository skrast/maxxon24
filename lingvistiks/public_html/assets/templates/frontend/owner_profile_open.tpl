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
										<div class="owner">{{ lang.lk_owner }}</div>
										{{ lang.lk_open_total_place }} {{ profile_info.user_work|escape|stripslashes|default(0) }}
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

						{% if profile_info.user_type_form == 2 and company_info.company_title %}
							<div class="clearfix"></div>
							<h3>{{ lang.lk_user_company_info }}</h3>
							<div class="row">
								{% if company_info.company_photo %}
									<div class="col-md-2">
										<img src="{{ ABS_PATH }}?thumb={{ app.app_upload_dir }}/{{ app.app_company_dir }}/{{ company_info.company_photo }}&width=200&height=200" alt="" class="img-responsive">
									</div>

									<div class="col-md-10">
								{% else %}
									<div class="col-md-12">
								{% endif %}								
									<p><strong>{{ company_info.company_title|escape|stripslashes }}</strong></p>
									<p>{{ company_info.company_group|escape|stripslashes }}</p>
									<p>{{ company_info.company_type|escape|stripslashes }}</p>
									<p>{{ company_info.company_site|escape|stripslashes }}</p>
									<p>{{ company_info.company_desc|escape|stripslashes|ntobr }}</p>
								</div>
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
						<h3>{{ lang.lk_otziv_perfomens }}</h3>
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
								{% endfor %}

								<!-- Pagination -->
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
	});
</script>
