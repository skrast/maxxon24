<main class="sidebar-left">
	<div class="container">

		<ol class="breadcrumb">
          <li><a href="{{ HOST_NAME }}">{{ lang.page_breadcrumb }}</a></li>
          <li class="active text-uppercase">{{ lang.message_title }}</li>
        </ol>

		<h1>{{ lang.message_title }}</h1>

		<div class="row">

			{{ perfomens_col }}

			<div class="col-md-9">
				<div class="content-right">
					<div class="row">
						<div class="col-md-4">

							<form action="{{ HOST_NAME }}/message/" method="post" enctype="multipart/form-data">
								<input type="hidden" name="csrf_token" value="{{ csrf_token }}">
								<div class="form-group">
									<input type="search" class="search" name="search_message" value="{{ REQUEST.search_message|escape|stripslashes }}" placeholder="{{ lang.message_search }}">
								</div>
							</form>

							<div id="content-4">
								{% for user in user_message_list %}
									<a href="{{ HOST_NAME }}/message/?message_to={{ user.message_user.id }}" class="contact-item {% if user.message_user.id == REQUEST.message_to %}active{% endif %}">
										<div class="message-img">
											<img src="{{ ABS_PATH }}?thumb={{ app.app_upload_dir }}/users/{{ user.message_user.user_photo }}&width=50&height=50" alt="" class="img-circle">
										</div>
										<div class="message-info">
											<p class="pull-left">
												<strong>{{ user.message_user.user_name|escape|stripslashes }}</strong>
											</p>
											<p class="pull-right message-date">{{ user.message_date|escape|stripslashes }}</p>
											<p class="short-msg">{{ user.message_desc|escape|stripslashes|truncate(110) }}</p>
										</div>
									</a>
								{% endfor %}
							</div>

							<div class="clearfix"></div>

							<a href="{{ HOST_NAME }}/customers-partners/" class="btn btn-block btn-search">{{ lang.message_create }}</a>
						</div>
						<div class="col-md-8">
							<div class="messages-chat">
								<ul class="media-list">
									{% for message in message_list %}

										{% if message.message_document and SESSION.user_group == 2 and message.message_to.id == SESSION.user_id %}
											<li>
												<div class="message-admin">
													<div class="message-title">{{ lang.message_user_writer_check_price }}</div>
													<div class="message-block">
														<p><strong>{{ lang.order_id }}</strong> {{ message.message_document.document_id }}</p>
														<p><strong>{{ lang.profile_firstname }}</strong> {{ message.message_from.user_name|escape|stripslashes }}</p>
														<p><strong>{{ lang.profile_email }}</strong> {{ message.message_from.user_email|escape|stripslashes }}</p>
														<p><strong>{{ lang.profile_phone }}</strong> {{ message.message_from.user_phone|escape|stripslashes }}</p>

														<p><strong>{{ lang.search_writer_hidden_document }}</strong> {% if message.message_document.document_hidden == 1 %}{{ lang.btn_yes }}{% else %}{{ lang.btn_no }}{% endif %}</p>
														<p><strong>{{ lang.search_writer_translate }} {{ lang.lk_lang_from }} </strong> {{ message.message_document.document_from_lang.title|escape|stripslashes }} <strong> {{ lang.lk_lang_to }} </strong>{{ message.message_document.document_to_lang.title|escape|stripslashes }}</p>

														<p><strong>{{ lang.search_writer_theme }}</strong> {{ message.message_document.document_theme.title|escape|stripslashes }}</p>
														<p><strong>{{ lang.search_writer_date_get }}</strong> {{ message.message_document.document_date|escape|stripslashes }}</p>
														<p><strong>{{ lang.search_writer_document_verif }}</strong> {{ lang.search_writer_document_verif_array[message.message_document.document_verif] }}</p>

														{% if message.message_document.document_file %}
															<p>
																<strong>{{ lang.search_writer_file }}</strong>
																<ul class="list-unstyled">
																	{% for message_file in message.message_document.document_file %}
																		<li><a href="{{ HOST_NAME }}/writer-document/{{ message.message_document.document_id }}/{{ message_file|escape|stripslashes }}" target="_blank">{{ message_file|escape|stripslashes }}</a></li>
																	{% endfor %}
																</ul>
															</p>
														{% endif %}

														{% if message.message_document.document_file_link %}
															<p>
																<strong>{{ lang.search_writer_form_file_link }}</strong>&nbsp;<a href="{{ message.message_document.document_file_link|escape|stripslashes }}" target="_blank">{{ message.message_document.document_file_link|escape|stripslashes }}</a>
															</p>
														{% endif %}

														<p><strong>{{ lang.search_writer_document_way }}</strong> {{ lang.search_writer_document_way_array[message.message_document.document_way] }}</p>

														{% if message.message_document.document_way == 2 %}
															<p><strong>{{ lang.lk_country }}</strong> {{ message.message_document.document_country_from.title|escape|stripslashes }}</p>
															<p><strong>{{ lang.lk_city }}</strong> {{ message.message_document.document_city_from.title|escape|stripslashes }}</p>
															<p><strong>{{ lang.search_writer_document_address }}</strong> {{ message.message_document.document_address_from|escape|stripslashes }}</p>
															<p><strong>{{ lang.lk_metro }}</strong> {{ message.message_document.document_metro_from.title|escape|stripslashes }}</p>
														{% endif %}

														{% if message.message_document.document_way == 3 %}
															<p><strong>{{ lang.lk_country }}</strong> {{ message.message_document.document_country_to.title|escape|stripslashes }}</p>
															<p><strong>{{ lang.lk_city }}</strong> {{ message.message_document.document_city_to.title|escape|stripslashes }}</p>
															<p><strong>{{ lang.search_writer_document_address }}</strong> {{ message.message_document.document_address_to|escape|stripslashes }}</p>
															<p><strong>{{ lang.lk_metro }}</strong> {{ message.message_document.document_metro_to.title|escape|stripslashes }}</p>
														{% endif %}

														{% if message.message_document.document_way == 4 %}
															<p><strong>{{ lang.lk_country }}</strong> {{ message.message_document.document_country_offline.title|escape|stripslashes }}</p>
															<p><strong>{{ lang.lk_city }}</strong> {{ message.message_document.document_city_offline.title|escape|stripslashes }}</p>
														{% endif %}

														<p><strong>{{ lang.search_writer_document_comment }}</strong> {{ message.message_document.document_comment|escape|stripslashes }}</p>

													</div>
												</div>

												{% if SESSION.user_group == 2 %}
													<div class="row">
														<div class="col-md-4">
									                    	<a href="" class="btn btn-block btn-adv get_ajax_form" data-type="siteSearch" data-sub="writer_search" data-void="{{ message.message_document.document_id }}">{{ lang.message_writer_search }}</a>
									                    </div>
													</div>
												{% endif %}
											</li>

											{% if SESSION.user_group == 2 and not message.message_document_perfomens %}
											<li>
												<br>

<script src="{{ ABS_PATH }}vendor/assets/moment-with-locales.js"></script>
<script type="text/javascript" src="{{ ABS_PATH }}vendor/assets/flatpickr/dist/flatpickr.js"></script>
<link rel="stylesheet" type="text/css" href="{{ ABS_PATH }}vendor/assets/flatpickr/dist/themes/airbnb.css" />
<script src="{{ ABS_PATH }}vendor/assets/flatpickr/dist/l10n/{{ SESSION.user_lang|default(app.app_lang) }}.js" charset="UTF-8"></script>

												<form action="{{ HOST_NAME }}/message/?message_to={{ send_message_to }}" id="message" method="post">
													<input type="hidden" name="csrf_token" value="{{ csrf_token }}">
													<input type="hidden" name="accept_perfomens_document" value="1">
													<input type="hidden" name="document_perfomens" value="16">
													<input type="hidden" name="message_id" value="{{ message.message_id }}">

													<div class="message-admin">
														<div class="message-title">{{ lang.message_writer_find_title }}</div>
														<div class="message-block">
															<div class="row">
																<div class="col-md-4">
																	<p><strong>{{ lang.message_writer_find_order_id }}</strong></p>
																</div>
																<div class="col-md-4">
																	<div class="form-group">
																		<input type="text" class="form-control" placeholder="" name="document_id" value="{{ message.message_document.document_id }}">
																	</div>
																</div>
																<div class="col-md-4">
																	<p>{{ lang.message_writer_find_perfomens_id }}</p>
																</div>
															</div>
															<div class="row">
																<div class="col-md-4">
																	<p><strong>{{ lang.message_writer_find_coast }}</strong></p>
																</div>
																<div class="col-md-4">
																	<div class="form-group">
																		<input type="text" class="form-control" placeholder="" name="coast" value="">
																	</div>
																</div>
																<div class="col-md-3">
																	<div class="form-group">
																		<select class="form-control-cur" name="currency">
																			{% for currency in currency_list %}
																				<option value="{{ currency.id }}">{{ currency.title|escape|stripslashes }}</option>
																			{% endfor %}
																		</select>
																	</div>
																</div>
															</div>
															<div class="row">
																<div class="col-md-4">
																	<p><strong>{{ lang.message_writer_find_date }}</strong></p>
																</div>
																<div class="col-md-7">
																	<div class="form-group">
																		<input type="text" class="form-control datepicker" placeholder="" name="date">
																	</div>
																</div>
															</div>
															<div class="row">
																<div class="col-md-4">
																	<p><strong>{{ lang.message_writer_find_get }}</strong></p>
																</div>
																<div class="col-md-8">
																	<div class="form-group">
																		<input type="text" class="form-control" placeholder="" name="get">
																	</div>
																</div>
																<div class="col-md-12">
																	<div class="form-group">
																		<textarea class="form-control" name="comment" id="" rows="3" placeholder="{{ lang.message_writer_find_comment }}"></textarea>
																	</div>
																</div>
															</div>
														</div>
													</div>
													<div class="form-group pull-right">
														<button type="submit" class="btn btn-block btn-search">{{ lang.btn_send }}</button>
													</div>
													<div class="clearfix"></div>
												</form>

												<script>
												$(function () {

													$("input[name=date].datepicker").flatpickr({
														locale: '{{ SESSION.user_lang|default(app.app_lang) }}',
														enableTime: true,
														dateFormat: 'd.m.Y H:i',
														allowInput: true,
														time_24hr: true,
													});
												});
												</script>
											</li>
											{% endif %}
										{% else %}

											{% if message.message_document_perfomens and SESSION.user_group != 2 and message.message_to.id == SESSION.user_id %}
												<li>
													<form action="{{ HOST_NAME }}/message/?message_to={{ send_message_to }}" id="message" method="post">
														<input type="hidden" name="csrf_token" value="{{ csrf_token }}">
														<input type="hidden" name="accept_perfomens" value="1">
														<input type="hidden" name="document_perfomens" value="{{ message.message_document_perfomens }}">
														<input type="hidden" name="message_document" value="{{ message.message_document_accept.accept_document_id }}">
														<input type="hidden" name="message_id" value="{{ message.message_id }}">
														<input type="hidden" name="accept_type" value="1">

														<div class="message-admin">
															<div class="message-title">{{ lang.message_writer_find_title }}</div>
															<div class="message-block">
																<p><strong>{{ lang.order_id }}</strong> {{ message.message_document.document_id }} {{ lang.message_writer_find_perfomens_id }}</p>
																<p><strong>{{ lang.message_writer_find_coast }}  </strong>{{ message.message_document_accept.accept_coast|escape|stripslashes }} {{ message.message_document_accept.accept_currency.title|escape|stripslashes }}</p>
																<p><strong>{{ lang.message_writer_find_date }} </strong> {{ message.message_document_accept.accept_date|escape|stripslashes }}</p>
																<p><strong>{{ lang.message_writer_find_get }} </strong> {{ message.message_document_accept.accept_get|escape|stripslashes }}</p>
																<p><strong>{{ lang.message_writer_find_comment }} </strong> {{ message.message_document_accept.accept_comment|escape|stripslashes }}</p>
															</div>
														</div>
														<div class="row set_accept">
															<div class="col-md-4">
																<a href="" class="btn btn-block btn-adv active" data-type="1">{{ lang.btn_pay }}</a>
															</div>
															<div class="col-md-4">
																<a href="" class="btn btn-block btn-adv" data-type="2">{{ lang.btn_deny }}</a>
															</div>
															<div class="col-md-4">
																<a href="" class="btn btn-block btn-adv" data-type="3">{{ lang.btn_search_other }}</a>
															</div>
															<div class="col-md-12">
																<div class="form-group">
																	<textarea class="form-control" name="comment" id="" rows="3" placeholder="{{ lang.message_writer_find_comment }}"></textarea>
																</div>
															</div>
														</div>
														<div class="form-group pull-right">
															<button type="submit" class="btn btn-block btn-search">{{ lang.btn_send }}</button>
														</div>
														<div class="clearfix"></div>

														<script>
															$(function () {
																$('.set_accept .btn-adv').on('click',function(){
																	var type = $(this).attr("data-type");
																	$("input[name=accept_type]").val(type);
																	$('.set_accept .btn-adv').removeClass("active");
																	$(this).addClass("active");
																	return false;
																});
															});
														</script>
													</form>
												</li>
											{% endif %}
										{% endif %}


										{{ message.message_html_block }}
									{% else %}
										<li><div class="album_empty message_empty">{{ lang.message_empty }}</div></li>
									{% endfor %}
								</ul>

								{% if order_info %}
									<form action="{{ HOST_NAME }}/message/?message_to={{ send_message_to }}" method="post" enctype="multipart/form-data" class="ajax_form message_otzive">
										<input type="hidden" name="csrf_token" value="{{ csrf_token }}">
										<input type="hidden" name="otziv" value="1">
										<input type="hidden" name="order_id" value="{{ order_info.order_id }}">

										<div class="message-admin">
											<div class="message-title">{{ lang.message_order_otzive }}</div>
											<div class="message-block">
												<div class="row">
													<div class="col-md-6 col-sm-6">
														<p>
															{% if SESSION.user_group == 4 %}
																{{ lang.message_order_for_perfomens }}
																<input type="hidden" name="otziv_to_id" value="{{ order_info.order_perfomens }}">
															{% elseif SESSION.user_group == 3 %}
																{{ lang.message_order_for_owner }}
																<input type="hidden" name="otziv_to_id" value="{{ order_info.order_owner }}">
															{% endif %}
														</p>
													</div>
													<div class="col-md-6 col-sm-6">

														<div class="star-rating pull-right">
															<span>
																{% if SESSION.user_group == 4 %}
																	{{ lang.message_order_for_perfomens_vote }}
																{% elseif SESSION.user_group == 3 %}
																	{{ lang.message_order_for_owner_vote }}
																{% endif %}
															</span>

															<ul class="unit-rating" style="width: {{ user_profile.full_width }}px;">
																<li class="current-rating bar" style="width: 0px;"></li>
																<li><a href="#" class="r1-unit rater" rel="nofollow" title="1">1</a></li>
																<li><a href="#" class="r2-unit rater" rel="nofollow" title="2">2</a></li>
																<li><a href="#" class="r3-unit rater" rel="nofollow" title="3">3</a></li>
																<li><a href="#" class="r4-unit rater" rel="nofollow" title="4">4</a></li>
																<li><a href="#" class="r5-unit rater" rel="nofollow" title="5">5</a></li>
															</ul>
														</div>

														<input type="hidden" name="otziv_star" value="5">
													</div>
													<div class="col-md-12">
														<div class="form-group">
															<textarea class="form-control" name="otziv_text" id="" rows="3" placeholder="{{ lang.message_order_otzive_desc }}"></textarea>
														</div>
													</div>

													{% if SESSION.user_group == 4 %}
														<div class="col-md-6 col-sm-6">
															<button type="button" class="btn btn-block btn-adv otziv_type" data-value="1">{{ lang.btn_accept }}</button>
														</div>
														<div class="col-md-6 col-sm-6">
															<button type="button" class="btn btn-block btn-adv otziv_type" data-value="0">{{ lang.btn_deny }}</button>
														</div>
														<input type="hidden" name="otziv_type" value="1">
													{% endif %}
												</div>
											</div>
										</div>
										<div class="form-group pull-right">
											<button type="submit" class="btn btn-block btn-search">{{ lang.btn_send }}</button>
										</div>
									</form>
								{% else %}
									{% if send_message_to %}
										<form action="{{ HOST_NAME }}/message/?message_to={{ send_message_to }}" method="post" enctype="multipart/form-data" class="ajax_form_message" data-reset="1">
											<input type="hidden" name="csrf_token" value="{{ csrf_token }}">
											<input type="hidden" name="save" value="1">

											<div class="form-group">
												<textarea class="form-control" name="message_desc" id="" rows="1" placeholder="{{ lang.message_desc }}"></textarea>
											</div>
											<div class="form-group pull-right">
												<label class="upload-photo">
								                  	<input type="file" name="photo" value="" id="upload-photo" data-to="{{ send_message_to }}">
								                </label>

												<button type="submit" class="btn btn-block btn-send btn-go-on">{{ lang.btn_send }}</button>
											</div>
										</form>
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


<link rel="stylesheet" href="{{ ABS_PATH }}assets/site/assets/malihu-custom-scrollbar-plugin-master/jquery.mCustomScrollbar.css">
<script src="{{ ABS_PATH }}assets/site/assets/malihu-custom-scrollbar-plugin-master/jquery.mCustomScrollbar.concat.min.js"></script>
<link rel="stylesheet" href="{{ ABS_PATH }}assets/site/assets/lightbox/css/lightbox.css" />
<script src="{{ ABS_PATH }}assets/site/assets/lightbox/js/lightbox.min.js"></script>
<script src='{{ABS_PATH}}assets/js/autogrow.js'></script>

<script>
$(function () {
	// init
	App.message_core();
	// init
});
</script>
