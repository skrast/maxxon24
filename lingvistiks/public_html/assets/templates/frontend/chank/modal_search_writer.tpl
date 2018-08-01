<div class="logo-translate text-center">
	<img src="{{ ABS_PATH }}assets/site/template/images/logo-translate.png" alt="" class="img-responsive">
</div>
<form action="{{ HOST_NAME }}{{ link_lang_pref }}/search-writer/" method="post" class="writer_search" enctype="multipart/form-data">
	<input type="hidden" name="csrf_token" value="{{ csrf_token }}">
	<input type="hidden" name="save" value="1">

	<div class="row">
		<div class="col-md-12">
			<h4>{{ lang.search_writer_form_file }}</h4>
		</div>

		<div class="col-md-6">
			<p class="vert-cent">{{ lang.search_writer_form_file_ext }}</p>
			<div class="add_file btn btn-block btn-go-on hidden">{{ lang.search_writer_form_file_add }}</div>
		</div>

		<div class="col-md-6">
			<ul class="list-unstyled file_list">
				<li class="append"></li>
			</ul>
		</div>

		<div class="col-md-12 hidden copy_file">
			<div class="form-group">
				<div class="file-upload">
					<label>
						<input type="file" name="document_file[]">
						<span>{{ lang.search_writer_select_file }}</span>
					</label>
				</div>
				<span class="name_file"></span>
				<button class="close-btn btn-link remove_file hidden"><img src="{{ ABS_PATH }}assets/site/template/images/close-button.png" alt=""></button>
			</div>
		</div>
		<input type="hidden" name="has_file" value="0">

		<div class="col-md-12 hidden error_for_large_file">
			<p class="error-message-color error-message">{{ lang.search_writer_form_file_ext_error }}</p>
			<p class="text">{{ lang.search_writer_form_file_link_desc }}</p>
		</div>

		<div class="col-md-12 hidden error_for_large_file">
			<div class="form-group">
				<input type="text" class="form-control" name="document_file_link" placeholder="{{ lang.search_writer_form_file_link }}">
			</div>
		</div>

		<div class="col-md-12">
			<div class="conf">
				<b>{{ lang.search_writer_hidden_document }} <i class="fa fa-question-circle" aria-hidden="true" aria-hidden="true" tabindex="0" data-toggle="popover" data-title="" data-content="{{ lang.search_writer_hidden_document_desc }}"></i></b>
				<div class="checkbox">
					<label>
						<input class="checkbox-input" type="checkbox" name="document_hidden" value="1">
						<span class="checkbox-custom"></span>
						<span class="label">{{ lang.page_yes }}</span>
					</label>
				</div>
			</div>

			<div class="politika-conf text-center hidden">
				<p><b>{{ lang.search_writer_hidden_document_start }} <a href="{{ HOST_NAME }}{{ link_lang_pref }}/privacy{{ app.URL_SUFF }}">{{ lang.search_writer_hidden_document_link }}</a> <br>{{ lang.search_writer_hidden_document_end }}</b></p>

				<div class="col-md-6 text-right">
					<div class="checkbox">
						<label>
							<input class="checkbox-input" type="radio" name="document_privacy" value="1">
							<span class="checkbox-custom"></span>
							<span class="label">{{ lang.page_yes }}</span>
						</label>
					</div>
				</div>
				<div class="col-md-6 text-left">
					<div class="checkbox">
						<label>
							<input class="checkbox-input" type="radio" name="document_privacy" value="0">
							<span class="checkbox-custom"></span>
							<span class="label">{{ lang.page_no }}</span>
						</label>
					</div>
				</div>
				
			</div>
		</div>

		<div class="col-md-6">
			<p>{{ lang.search_writer_lang_default }}</p>
			<div class="form-group">
				<select class="form-control selectpicker" name="document_from_lang">
					{% for lang in lang_list %}
					<option value="{{ lang.id }}">{{ lang.title|escape|stripslashes }}</option>
					{% endfor %}
				</select>
			</div>
		</div>

		<div class="col-md-6">
			<p>{{ lang.search_writer_lang_dest }}</p>
			<div class="form-group">
				<select class="form-control selectpicker" name="document_to_lang">
					{% for lang in lang_list %}
					<option value="{{ lang.id }}">{{ lang.title|escape|stripslashes }}</option>
					{% endfor %}
				</select>
			</div>
		</div>

		<div class="col-md-12">
			<div class="form-group">
				<select class="form-control selectpicker" name="document_theme">
					<option value="">{{ lang.search_writer_theme }}</option>
					{% for theme in theme_list %}
						<option value="{{ theme.id }}">{{ theme.title|escape|stripslashes }}</option>
					{% endfor %}
				</select>
			</div>
		</div>

		<div class="col-md-12">
			<div class="calendar text-center">
				<p><strong>{{ lang.search_writer_date_get }}</strong></p>
				<div class="input-group calendar-item">
					<div id="writer_search_date"></div>
				</div>

				<input type="hidden" name="document_date" value="">
			</div>
		</div>

		<div class="col-md-12 dover">
			<p>{{ lang.search_writer_document_verif }}</p>
			<ul class="list-inline">
				{% for key, value in lang.search_writer_document_verif_array %}
				<li>
					<div class="checkbox">
						<label>
							<input class="checkbox-input" type="radio" name="document_verif" value="{{ key }}" {% if key == 1 %}checked{% endif %}>
							<span class="checkbox-custom"></span>
							<span class="label">{{ value }} {% if lang.search_writer_document_verif_desc_array[key] %}<i class="fa fa-question-circle" aria-hidden="true" aria-hidden="true" tabindex="0" data-toggle="popover" data-title="" data-content="{{ lang.search_writer_document_verif_desc_array[key] }}"></i>{% endif %}</span>
						</label>
					</div>
				</li>
				{% endfor %}
			</ul>
		</div>

		<div class="col-md-12 sheepment">
			<h4>{{ lang.search_writer_document_way }}</h4>
			<div class="checkbox">
				<label>
					<input class="checkbox-input" type="radio" name="document_way" value="1" checked>
					<span class="checkbox-custom"></span>
					<span class="label">{{ lang.search_writer_document_way_1 }}</span>
				</label>
			</div>
			<div class="checkbox">
				<label>
					<input class="checkbox-input" type="radio" name="document_way" value="2">
					<span class="checkbox-custom"></span>
					<span class="label">{{ lang.search_writer_document_way_2 }}</span>
				</label>
			</div>
		</div>

		<div class="document_way_2 hidden document_way_hidden">
			<div class="col-md-6">
				<div class="form-group">
					<select class="form-control selectpicker load_select" name="document_country_from" data-target="document_city_from" data-live-search="true">
						<option value="">{{ lang.lk_country }}</option>
						{% for country in country_list %}
							<option value="{{ country.id|escape|stripslashes }}">{{ country.title|escape|stripslashes }}</option>
						{% endfor %}
					</select>
				</div>
			</div>

			<div class="col-md-6">
				<div class="form-group">
					<select class="form-control selectpicker" name="document_city_from" data-default="{{ lang.lk_city }}" data-live-search="true">
						<option value="">{{ lang.lk_city }}</option>
					</select>
				</div>
			</div>

			<div class="col-md-12 metro_select_from hidden">
				<div class="form-group">
					<select class="form-control selectpicker" name="document_metro_from">
						<option value="">{{ lang.lk_metro }}</option>
						{% for metro in metro_list %}
							<option value="{{ metro.id|escape|stripslashes }}">{{ metro.title|escape|stripslashes }}</option>
						{% endfor %}
					</select>
				</div>
			</div>

			<div class="col-md-12">
				<div class="form-group">
					<input type="text" class="form-control" placeholder="{{ lang.search_writer_document_address }}" name="document_address_from">
				</div>
			</div>
		</div>

		<div class="col-md-12 sheepment">
			<div class="checkbox">
				<label>
					<input class="checkbox-input" type="radio" name="document_way" value="3">
					<span class="checkbox-custom"></span>
					<span class="label">{{ lang.search_writer_document_way_3 }}</span>
				</label>
			</div>
		</div>

		<div class="document_way_3 hidden document_way_hidden">

			<div class="col-md-6">
				<div class="form-group">
					<select class="form-control selectpicker load_select" name="document_country_to" data-target="document_city_to" data-live-search="true">
						<option value="">{{ lang.lk_country }}</option>
						{% for country in country_list %}
							<option value="{{ country.id|escape|stripslashes }}">{{ country.title|escape|stripslashes }}</option>
						{% endfor %}
					</select>
				</div>
			</div>

			<div class="col-md-6">
				<div class="form-group">
					<select class="form-control selectpicker" name="document_city_to" data-default="{{ lang.lk_city }}" data-live-search="true">
						<option value="">{{ lang.lk_city }}</option>
					</select>
				</div>
			</div>

			<div class="col-md-12 metro_select_to hidden">
				<div class="form-group">
					<select class="form-control" name="document_metro_to">
						<option value="">{{ lang.lk_metro }}</option>
						{% for metro in metro_list %}
							<option value="{{ metro.id|escape|stripslashes }}">{{ metro.title|escape|stripslashes }}</option>
						{% endfor %}
					</select>
				</div>
			</div>

			<div class="col-md-12">
				<div class="form-group">
					<input type="text" class="form-control" name="document_address_to" value="" placeholder="{{ lang.search_writer_document_address }}">
				</div>
			</div>
		</div>

		<div class="col-md-12 sheepment hidden">
			<div class="checkbox">
				<label>
					<input class="checkbox-input" type="radio" name="document_way" value="4">
					<span class="checkbox-custom"></span>
					<span class="label">{{ lang.search_writer_document_way_4 }}</span>
				</label>
			</div>
		</div>

		<div class="document_way_4 hidden document_way_hidden">
			<div class="col-md-6">
				<div class="form-group">
					<select class="form-control selectpicker load_select" name="document_country_offline" data-target="document_city_offline" data-live-search="true">
						<option value="">{{ lang.lk_country }}</option>
						{% for country in country_list %}
							<option value="{{ country.id|escape|stripslashes }}">{{ country.title|escape|stripslashes }}</option>
						{% endfor %}
					</select>
				</div>
			</div>

			<div class="col-md-6">
				<div class="form-group">
					<select class="form-control selectpicker" name="document_city_offline" data-default="{{ lang.lk_city }}" data-live-search="true">
						<option value="">{{ lang.lk_city }}</option>
					</select>
				</div>
			</div>
		</div>

		<div class="col-md-12">
			<div class="form-group">
				<textarea class="form-control-modal" placeholder="{{ lang.search_writer_document_comment }}" rows="4" name="document_comment"></textarea>
			</div>

			<button type="submit" class="btn btn-block btn-go-on">{{ lang.search_writer_document_btn }}</button>

			<p class="error-message-color error-message hidden">{{ lang.search_writer_document_error }}</p>
		</div>
	</div>
</form>

<script src="{{ ABS_PATH }}vendor/assets/moment-with-locales.js"></script>
<script type="text/javascript" src="{{ ABS_PATH }}vendor/assets/flatpickr/dist/flatpickr.js"></script>
<link rel="stylesheet" type="text/css" href="{{ ABS_PATH }}vendor/assets/flatpickr/dist/themes/airbnb.css" />
<script src="{{ ABS_PATH }}vendor/assets/flatpickr/dist/l10n/{{ SESSION.user_lang|default(app.app_lang) }}.js" charset="UTF-8"></script>

<script>

	  $(function () {

		  $('.selectpicker').selectpicker({'style':'btn-default', 'size': 5});

		// init
	  	App.writer_core();
	  	// init

		$('[data-toggle="tooltip"]').tooltip();

		$("#writer_search_date").flatpickr({
			locale: '{{ SESSION.user_lang|default(app.app_lang) }}',
			inline: true,
			enableTime: true,
			dateFormat: 'd.m.Y H:i',
			allowInput: true,
			time_24hr: true,
			onOpen: [
				function(selectedDates, dateStr, instance){
					instance;
					if(!dateStr) {
						instance.setDate(new Date());
					}
				}
			],
			onChange: function(selectedDates, dateStr, instance) {
				$("input[name=document_date]").val(dateStr);
			},
		});

		$('.fa-question-circle').popover({
			placement: 'bottom',
			trigger: "hover",
			container: "body",
			html: "true",
			animation: "true",
		});

		$(".writer_search select[name=document_city_from]").on('change',function() {
			var city_id = $(this).val();
			$(".writer_search .metro_select_from").addClass("hidden");

			$(".writer_search select[name=document_metro_from]").text("");

			$.ajax({
				url: ave_host+'/metro-load/',
				data: {city_id: city_id, csrf_token: csrf_token},
				success: function( data ) {

					if(data.respons) {
						var ln = 0;
						$.each(data.respons, function( index, value ) {
							ln++;
							$(".writer_search select[name=document_metro_from]").append('<option value="'+value.id+'">'+value.title+'</option>');
						});

						$(".writer_search select[name=document_metro_from]").selectpicker('refresh');

						if(ln >= 1) {
							$(".writer_search .metro_select_from").removeClass("hidden");
						}
					}
				}
			});
		});

		$(".writer_search select[name=document_city_to]").on('change',function() {
			var city_id = $(this).val();
			$(".writer_search .metro_select_to").addClass("hidden");

			$(".writer_search select[name=document_metro_to]").text("");

			$.ajax({
				url: ave_host+'/metro-load/',
				data: {city_id: city_id, csrf_token: csrf_token},
				success: function( data ) {

					if(data.respons) {
						var ln = 0;
						$.each(data.respons, function( index, value ) {
							ln++;
							$(".writer_search select[name=document_metro_to]").append('<option value="'+value.id+'">'+value.title+'</option>');
						});

						$(".writer_search select[name=document_metro_to]").selectpicker('refresh');

						if(ln >= 1) {
							$(".writer_search .metro_select_to").removeClass("hidden");
						}
					}
				}
			});
		});

		$("input[name=document_way]").on('change',function() {
			var is_stay = $(this).val();
			$(".document_way_hidden").addClass("hidden");
			$(".document_way_"+is_stay).removeClass("hidden");
		});

		$("input[name=document_hidden]").on('change',function() {
			var is_stay = $("input[name=document_hidden]:checked").val();
			$(".politika-conf").addClass("hidden");
			if(is_stay == 1) {
				$(".politika-conf").removeClass("hidden");
			}
		});

		$(".add_file").on('click',function() {
			var file_html = $(".copy_file").clone().removeClass("hidden").removeClass("copy_file");
			$(".file_list").prepend(file_html);

			$(".file_list .col-md-12 .file-upload").addClass("hidden");
			$(".file_list .col-md-12").eq(0).find(".file-upload").removeClass("hidden");

			$('.file_list input').bind('change', function(event) {
				event.stopPropagation();

				if($(this).parent().parent().next().text() == '') {
					$(".add_file").click();
				}

				if(this.files[0].name) {
					$(this).parent().parent().next().text(this.files[0].name);
					$(this).parent().parent().next().next().removeClass("hidden");
					$("input[name=has_file]").val($(".file_list .remove_file").length);
				}

				total_size_file();

			});

			$(".remove_file").on('click',function(event) {
				event.stopPropagation();
				$("input[name=has_file]").val($(".file_list .remove_file").length);
				$(this).parent().parent().remove();

				total_size_file();
			});
		});
		$(".add_file").click();



		function total_size_file() {
			$(".error_for_large_file").addClass("hidden");

			var total_size = 0;
			$(".file_list input[type='file']").each(function( index ) {
				if($(".file_list input[type='file']").get(index).files[0]) {
					total_size = total_size+($(".file_list input[type='file']").get(index).files[0].size);
					console.log( total_size );
				}
			});

			if(total_size>=(10*1024*1024)) {
				$(".error_for_large_file").removeClass("hidden");
			}
		}


	});
</script>
