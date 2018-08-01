<main class="sidebar-left">
	<div class="container">

		<ol class="breadcrumb">
			<li><a href="{{ HOST_NAME }}">{{ lang.page_breadcrumb }}</a></li>
			<li class="active text-uppercase">{{ lang.service_translations }}</li>
		</ol>

		<h1>{{ lang.service_translations }}</h1>
		<div class="row">

			{{ perfomens_col }}

			<div class="col-md-{% if perfomens_col %}9{% else %}12{% endif %} col-sm-{% if perfomens_col %}9{% else %}12{% endif %}">
				<div class="content-right">
									
					<div class="partners-in">

						<div class="row">
							<div class="partners-block">
								<div class="col-md-3">
									<img src="{{ ABS_PATH }}?thumb={{ app.app_upload_dir }}/users/{{ document_info.document_owner.user_photo }}&width=250&height=300" alt="" class="img-responsive">
								</div>

								<div class="col-md-9 partner-info">

									<div class="pull-right">
										{{ document_info.document_owner.user_rating_tpl }}
									</div>

									<div class="pull-left">
										<ul class="list-inline left-border">
											<li>{{ document_info.document_owner.user_login|escape|stripslashes }}</li>
											<li>{{ document_info.document_owner.full_user_id|escape|stripslashes }}</li>
											<li>
												{% if document_info.document_owner.user_online_status == 1 %}
													<span class="status-p status-on">{{ lang.lk_online }}</span>
												{% else %}
													<span class="status-p status-busy">{{ lang.lk_offline }}</span>
												{% endif %}
											</li>
										</ul>
									</div>

									<div class="row">
										<div class="col-md-9">
											<ul class="list-unstyled">
												<li>
													<strong>{{ lang.search_writer_hidden_document }}</strong> {% if document_info.document_hidden %}{{ lang.page_yes }}{% else %}{{ lang.page_no }}{% endif %}
												</li>
												<li>
													<strong>{{ lang.search_writer_lang_default }}</strong> {{ document_info.document_from_lang.title|escape|stripslashes }}
												</li>
												<li>
													<strong>{{ lang.search_writer_lang_dest }}</strong> {{ document_info.document_to_lang.title|escape|stripslashes }}
												</li>
												<li>
													<strong>{{ lang.search_writer_theme }}</strong> {{ document_info.document_theme.title|escape|stripslashes }}
												</li>
												<li>
													<strong>{{ lang.search_writer_date_get }}</strong> {{ document_info.document_date|escape|stripslashes }}
												</li>
												<li>
													<strong>{{ lang.search_writer_document_verif }}</strong> {{ lang.search_writer_document_verif_array[document_info.document_verif] }}
												</li>
											</ul>
										</div>
										
										<div class="col-md-3">
											<ul class="list-unstyled">
												<li><a href="{{ HOST_NAME }}/message/?message_to={{ document_info.document_owner.id|escape|stripslashes }}" class="btn btn-block btn-go-on">{{ lang.btn_write }}</a></li>
												<li><a href="{{ HOST_NAME }}{{ link_lang_pref }}/profile-{{ document_info.document_owner.id|escape|stripslashes }}/" class="btn btn-block btn-go-on">{{ lang.btn_profile }}</a></li>
											</ul>
											
											
										</div>
									</div>
								</div>

								<div class="clearfix"></div>

								<div class="col-md-3"></div>
								<div class="col-md-9">
									<ul class="list-unstyled">
										<li>
											<strong>{{ lang.search_writer_document_way }}</strong> <br>

											{% if document_info.document_way == 1 %}
												{{ lang.search_writer_document_way_1 }}
											{% endif %}

											{% if document_info.document_way == 2 %}
												{{ lang.search_writer_document_way_2 }} <br>

												{{ lang.lk_country }} {{ document_info.document_country_from.title|escape|stripslashes }} <br>
												{{ lang.lk_city }} {{ document_info.document_city_from|escape|stripslashes }} <br>
												{{ lang.search_writer_document_address }} {{ document_info.document_address_from|escape|stripslashes }} 
											{% endif %}

											{% if document_info.document_way == 3 %}
												{{ lang.search_writer_document_way_3 }} <br>

												{{ lang.lk_country }} {{ document_info.document_country_to.title|escape|stripslashes }} <br>
												{{ lang.lk_city }} {{ document_info.document_city_to.title|escape|stripslashes }} <br>
												{{ lang.search_writer_document_address }} {{ document_info.document_address_to|escape|stripslashes }} 
											{% endif %}

											{% if document_info.document_way == 4 %}
												{{ lang.search_writer_document_way_4 }} <br>

												{{ lang.lk_country }} {{ document_info.document_country_offline.title|escape|stripslashes }} <br>
												{{ lang.lk_city }} {{ document_info.document_city_offline.title|escape|stripslashes }} <br>
											{% endif %}
										</li>

										<li>
											<strong>{{ lang.search_writer_document_comment }}</strong><br> {{ document_info.document_comment|escape|stripslashes|ntobr }}
										</li>

										<li>
											<strong>{{ lang.search_writer_file }}</strong> <br>
											{% for file in document_info.document_file %}
												<a href="{{ ABS_PATH}}translations/document-{{ document_info.document_id }}/attach/{{ file }}">{{ file }}</a><br>
											{% endfor %}
										</li>
									</ul>
								</div>

								<div class="clearfix"></div>
							</div>
						</div>
						
					</div>


					<div class="row">
						{% if SESSION.user_group in [1,2] %}
							<div class="col-md-3 col-sm-3 col-xs-3">
								<p><strong>{{ lang.service_translations_search_perfomens }}</strong></p>
							</div>
							<div class="col-md-9 col-sm-9 col-xs-9">
								<div class="form-group">
									<select class="form-control selectpicker translations_perfomens" name="documents_perfomens[]" data-live-search="true" multiple>
										<option value="">{{ lang.order_page_change_perfomens }}</option>
										{% for perfomens in perfomens_list %}
											<option value="{{ perfomens.id }}">{{ perfomens.user_name|escape|stripslashes }}, ID {{ perfomens.id|escape|stripslashes }}</option>
										{% endfor %}
									</select>
								</div>
								<div class="form-group">
									<button class="btn btn-block btn-go-on translations_search_perfomens" data-document="{{ document_info.document_id }}">{{ lang.service_translations_search_perfomens_btn }}</button>
									<p class="error-message-color error-message hidden"></p>
								</div>
							</div>
						{% endif %}

						{% if SESSION.user_group in [1,2] or SESSION.user_id == document_info.document_owner.id %}
							<div class="col-md-3 col-sm-3 col-xs-3">
								<p><strong>{{ lang.service_translations_status }}</strong></p>
							</div>
							<div class="col-md-9 col-sm-9 col-xs-9">
								<div class="form-group">
									<ul class="list-inline">
										<li>
											<div class="radio">
												<label>
													<input class="radio-input" type="radio" name="document_status" value="1" {% if 1 == document_info.document_status %}checked{% endif %}>
													<span class="radio-custom"></span>
													<span class="label">{{ lang.service_translations_status_active|escape|stripslashes }}</span>
												</label>
											</div>
										</li>
										<li>
											<div class="radio">
												<label>
													<input class="radio-input" type="radio" name="document_status" value="2" {% if 0 == document_info.document_status %}checked{% endif %}>
													<span class="radio-custom"></span>
													<span class="label">{{ lang.service_translations_status_no_active|escape|stripslashes }}</span>
												</label>
											</div>
										</li>
									</ul>	
								</div>
								<div class="form-group">
									<button class="btn btn-block btn-go-on translations_search_status" data-document="{{ document_info.document_id }}">{{ lang.service_translations_status_btn }}</button>
									<p class="error-message-color error-message hidden"></p>
								</div>
							</div>
						{% endif %}


						{% if SESSION.user_group == 3 %}
							<div class="col-md-12">
								<div class="form-group">
									<textarea class="form-control" placeholder="{{ lang.service_translations_respons_add }}" rows="6" name="perfomens_desc">{{ perfomens_respons.perfomens_desc|escape|stripslashes }}</textarea>
								</div>
								<div class="form-group">
									<button class="btn btn-block btn-go-on translations_respons_add" data-document="{{ document_info.document_id }}">{{ lang.service_translations_respons_add }}</button>
									<p class="error-message-color error-message hidden"></p>
								</div>
							</div>
						{% endif %}


						{% if SESSION.user_group in [1,2] or SESSION.user_id == document_info.document_owner.id %}
							<div class="col-md-12">
								<h2>{{ lang.service_translations_respons_list }}</h2>
							</div>
							{% for perfomer in perfomer_list %}
								<div class="col-md-12">
									<div class="perfomers-card normal">
										<div class="row">
											<div class="col-md-3">
												<img src="{{ ABS_PATH }}?thumb={{ app.app_upload_dir }}/users/{{ perfomer.user_photo }}&width=250&height=300" alt="" class="img-responsive">
											</div>
											<div class="col-md-9 perfomers-info">
												<h4>{{ perfomer.user_login|escape|stripslashes }} </h4>

												<div class="clearfix"></div>
												<div class="perfomers-dop-info no_fix_hight">
													<p><strong>
														{% for country in country_list %}
															{% if country.id == perfomer.user_country %}{{ country.title|escape|stripslashes }}{% endif %}
														{% endfor %}
														{% for city in perfomer.city_list %}
															{% if city.id == perfomer.user_city %}, {{ city.title|escape|stripslashes }}{% endif %}
														{% endfor %}
													</strong></p>

													<div class="plate1">{{ lang.lk_lang_default }}
														{% if perfomer.user_default_lang %}{{ perfomer.user_default_lang.title|escape|stripslashes }}{% else %}{{ lang.lk_user_default_lang_empty}}{% endif %}
													</div>

													{% if perfomer.lang_var %}
														<div class="plate2">{{ lang.lk_lang_var }}</div>
														<ul class="plate2-list list-unstyled">
															{% for lang in perfomer.lang_var|slice(0, 3) %}
																<li>
																	{{ lang.serv_lang_from.title|escape|stripslashes }} - {{ lang.serv_lang_to.title|escape|stripslashes }}
																</li>
															{% endfor %}
														</ul>
													{% endif %}

													{% if perfomer.perfomens_desc %}
														<br>
														<p>{{ lang.service_translations_respons_show }} {{ perfomer.perfomens_desc_date|escape|stripslashes }}</p>
														{{ perfomer.perfomens_desc|escape|stripslashes|ntobr }}
													{% endif %}
												</div>
												
											</div>
										</div>

										<div class="row">
											<div class="col-md-3 text-center">
												{{ perfomer.user_rating_tpl }}
											</div>
											<div class="col-md-9">
												<div class="row">
													<div class="col-md-6">
														<a href="{{ HOST_NAME }}/message/?message_to={{ perfomer.id|escape|stripslashes }}" class="btn btn-block btn-go-on">{{ lang.btn_write }}</a>
													</div>
													<div class="col-md-6">
														<a href="{{ HOST_NAME }}{{ link_lang_pref }}/profile-{{ perfomer.id|escape|stripslashes }}/" class="btn btn-block btn-go-on">{{ lang.btn_detail }}</a>
													</div>
												</div>
												
											</div>
										</div>
									</div>
								</div>
							{% else %}
								<div class="col-md-12"><p>{{ lang.empty_data }}</p></div>
							{% endfor %}

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
		App.translations_core();
	  	// init
	});
</script>
