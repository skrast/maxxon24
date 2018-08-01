<form action="{{ HOST_NAME }}{{ link_lang_pref }}/perfomens/" method="post" class="modal_search">
	<input type="hidden" name="csrf_token" value="{{ csrf_token }}">
	<input type="hidden" name="user_type" value="{{ void_id|escape|stripslashes }}">

	<div class="row">
		<div class="change_type_search">
			<div class="col-md-4 pr-5">
				<div class="form-group">
					<button type="button" class="btn btn-block btn-adv {% if void_id == 1 %}active-adv{% endif %}" data-id="1">{{ lang.search_form_translate }}</button>
				</div>
			</div>
			<div class="col-md-4 p-5">
				<div class="form-group">
					<button type="button" class="btn btn-block btn-adv {% if void_id == 2 %}active-adv{% endif %}" data-id="2">{{ lang.search_form_lang }}</button>
				</div>
			</div>
			<div class="col-md-4 pl-5">
				<div class="form-group">
					<button type="button" class="btn btn-block btn-adv {% if void_id == 3 %}active-adv{% endif %}" data-id="3">{{ lang.search_form_gid }}</button>
				</div>
			</div>
		</div>

		<div class="col-md-6">
			<div class="form-group">
				<select class="form-control load_select selectpicker" name="user_country" data-target="user_city" data-live-search="true">
					<option value="">{{ lang.profile_country_default }}</option>
					{% for country in country_list %}
						<option value="{{ country.id|escape|stripslashes }}">{{ country.title|escape|stripslashes }}</option>
					{% endfor %}
				</select>
			</div>
		</div>
		<div class="col-md-6">
			<div class="form-group">
				<select class="form-control selectpicker" name="user_city" data-default="{{ lang.lk_city }}">
					<option value="">{{ lang.profile_city_default }}</option>
				</select>
			</div>
		</div>
		<div class="col-md-6">
			<div class="form-group metro_select hidden">
				<select class="form-control selectpicker" name="user_metro">
					<option value="">{{ lang.lk_metro }}</option>
					{% for metro in metro_list %}
						<option value="{{ metro.id|escape|stripslashes }}">{{ metro.title|escape|stripslashes }}</option>
					{% endfor %}
				</select>
			</div>
		</div>
		<div class="col-md-12">
			<div class="checkbox load_service text-center select_type_dop">
				<ul class="list-inline">
				{% for service_id, service_value in service_list %}
					{% for key, value in service_value %}
						<li class="show_or_hide_li show{{ service_id }} {% if service_id != void_id %}hidden{% endif %}">
							<div class="checkbox">
								<label class="show_or_hide show{{ service_id }} {% if value.id == 55 %}show_writer_search{% endif %}">
									<input class="checkbox-input" type="radio" name="serv_type_service[]" value="{{ value.id }}">
									<span class="checkbox-custom"></span>
									<span class="label">{{ value.title|escape|stripslashes }}</span>
								</label>
							</div>
						</li>
					{% endfor %}
				{% endfor %}
				</ul>
			</div>

			<div class="checkbox load_communication hidden">
				<span>{{ lang.lk_communication }}</span>
				<ul class="list-inline">
					{% for communication in communication_list %}
						<li>
							<div class="checkbox">
								<label>
									<input class="checkbox-input" type="checkbox" name="serv_communication[]" value="{{ communication.id }}" {% if communication.id in REQUEST.serv_communication %}checked{% endif %}>
									<span class="checkbox-custom"></span>
									<span class="label">{{ communication.title|escape|stripslashes }}</span>
								</label>
							</div>
						</li>
					{% endfor %}
				</ul>
			</div>
		</div>

		<div class="clearfix"></div>

		<div class="col-md-12">

			<div class="row budzhet show_or_hide show1 show3 {% if service_id != void_id %}hidden{% endif %}">
				<div class="col-md-4">
					<p>{{ lang.lk_lang_var }}</p>
				</div>
				<div class="col-md-4 left-pad-0">
					<select class="form-control selectpicker" name="lang_from_temp">
						{% for lang in lang_list %}
							<option value="{{ lang.id }}">{{ lang.title|escape|stripslashes }}</option>
						{% endfor %}
					</select>
				</div>
				<div class="col-md-4 left-pad-0">
					<select class="form-control selectpicker" name="lang_to_temp">
						{% for lang in lang_list %}
							<option value="{{ lang.id }}">{{ lang.title|escape|stripslashes }}</option>
						{% endfor %}
					</select>
				</div>
			</div>

			<div class="row budzhet show_or_hide show2 {% if service_id != void_id %}hidden{% endif %}">

				<div class="col-md-4 text-center">
					<span>{{ lang.lk_place }}</span>
				</div>
				<div class="col-md-8">
					<ul class="list-unstyled text-left">
						{% for place in place_list %}
							<li>
								<div class="checkbox">
									<label>
										<input class="checkbox-input" type="checkbox" name="serv_place[]" value="{{ place.id }}" {% if place.id in REQUEST.serv_place %}checked{% endif %}>
										<span class="checkbox-custom"></span>
										<span class="label">{{ place.title|escape|stripslashes }}</span>
									</label>
								</div>
							</li>
						{% endfor %}
					</ul>
				</div>

				<div class="clearfix"></div>

				<div class="col-md-6">
					<select class="form-control selectpicker" name="lang_from_temp2">
						<option value="">{{ lang.search_form_field_lang2 }}</option>
						{% for lang in lang_list %}
							<option value="{{ lang.id }}">{{ lang.title|escape|stripslashes }}</option>
						{% endfor %}
					</select>
				</div>
				<div class="col-md-6">
					<select class="form-control selectpicker" name="lang_to_temp2">
						<option value="">{{ lang.search_form_field_lang2_to }}</option>
						{% for lang in lang_list %}
							<option value="{{ lang.id }}">{{ lang.title|escape|stripslashes }}</option>
						{% endfor %}
					</select>
				</div>
			</div>

			<div class="form-group show_or_hide show1">
				<select class="form-control selectpicker" name="serv_theme">
					<option value="">{{ lang.lk_info_theme }}</option>
					{% for theme in theme_list %}
						<option value="{{ theme.id }}">{{ theme.title|escape|stripslashes }}</option>
					{% endfor %}
				</select>
			</div>

			<div class="form-group show_or_hide show2">
				<select class="form-control selectpicker" name="serv_level">
					<option value="">{{ lang.lk_lang_level }}</option>
					{% for level in level_list %}
						<option value="{{ level.id }}">{{ level.title|escape|stripslashes }}</option>
					{% endfor %}
				</select>
			</div>
		</div>

		<div class="col-md-12 date_ranger show_or_hide show1 show3">
			<label class="text-center">{{ lang.search_form_date }}</label>

			<input type="text" class="form-control lk_graph">
			<input type="hidden" name="graph_start" value="">
			<input type="hidden" name="graph_end" value="">
		</div>


		<div class="col-md-12 budget_ranger">
			<label>{{ lang.lk_budget }}</label>

			<div class="row budzhet">
				<div class="col-md-3">
					<input type="text" class="form-control" placeholder="{{ lang.lk_budget_from }}" name="budget_start" value="">
				</div>
				<div class="col-md-3">
					<input type="text" class="form-control" placeholder="{{ lang.lk_budget_to }}" name="budget_end" value="">
				</div>
				<div class="col-md-3">
					<select class="form-control" name="budget_currency">
						{% for currency in currency_list %}
							<option value="{{ currency.id }}">{{ currency.title|escape|stripslashes }}</option>
						{% endfor %}
					</select>
				</div>
				<div class="col-md-3">
					<select class="form-control-time" name="budget_time">
						{% for time in time_list %}
							{% if time.id != 70 and time.id != 71 %}
								<option value="{{ time.id }}" class="show_time_{{ time.id }}">{{ time.title|escape|stripslashes }}</option>
							{% endif %}
						{% endfor %}
					</select>
				</div>
			</div>

		</div>
		<div class="col-md-12">
			<button type="submit" class="btn btn-block btn-go-on">{{ lang.btn_search }}</button>
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

		  $(".load_service input[type=radio]").on("change", function() {
	  		$(".load_communication").addClass("hidden");
	  		$(".load_place").addClass("hidden");

	  		$(".load_service input[type=radio]:checked").each(function( index ) {
	  			var check = $(this).val();
	  			if(check == 49 || check == 56) {
	  				$(".load_communication").removeClass("hidden");
	  			}

	  			if(check == 47 || check == 48) {
	  				$(".load_place").removeClass("hidden");
	  			}

				if(check == 55) {
					App.search_writer_core();
					return false;
				}
	  		});
	  	});


		var start = moment();
		var end = moment().add(1, 'days');
		$("input[name=graph_start]").val(moment(start).format(date_format_js));
		$("input[name=graph_end]").val(moment(end).format(date_format_js));

		$(".lk_graph").flatpickr({
			locale: '{{ SESSION.user_lang|default(app.app_lang) }}',
			inline: true,
			enableTime: false,
			dateFormat: 'd.m.Y',
			allowInput: true,
			time_24hr: true,
			mode: "range",
			minDate: "today",
			onOpen: [
				function(selectedDates, dateStr, instance){
					instance;
					if(!dateStr) {
						instance.setDate(new Date());
					}
				}
			],
			onChange: function(selectedDates, dateStr, instance) {
				var date_str = dateStr.split(" ");
				if(date_str[0] && date_str[2]) {
					$("input[name=graph_start]").val(date_str[0]+" 00:00");
					$("input[name=graph_end]").val(date_str[2]+" 23:55");
				}
			},
		});

		$(".modal_search .change_type_search button").on('click',function() {
			var type_search = $(this).attr("data-id");
			$("input[name=user_type]").val(type_search);

			$(".modal_search .show_or_hide").addClass("hidden");
			$(".modal_search .show"+type_search).removeClass("hidden");

			$(".modal_search .show_or_hide_li").addClass("hidden");
			$(".modal_search .show_or_hide_li.show"+type_search).removeClass("hidden");

			$(".modal_search .change_type_search button").removeClass("active-adv");
			$(this).addClass("active-adv");

			$("select[name=lang_from_temp2]").val($("select[name=lang_from_temp2] option:first").val());
			$("select[name=lang_to_temp2]").val($("select[name=lang_to_temp2] option:first").val());
			$("select[name=serv_level]").val($("select[name=serv_level] option:first").val());
			$("select[name=serv_theme]").val($("select[name=serv_theme] option:first").val());
			$("select[name=lang_from_temp]").val($("select[name=lang_from_temp] option:first").val());
			$("select[name=lang_to_temp]").val($("select[name=lang_to_temp] option:first").val());

			$("input[name=graph_start]").val("");
			$("input[name=graph_end]").val("");

			$(".budzhet input[type=text]").val("");
			$(".budzhet select").each(function(){
				$(this).val($(this).find("option:first").val());
			});

			$("input:checkbox").removeAttr('checked');
			$("input:radio").removeAttr('checked');

			if(type_search == 2) {
				$(".show_time_26").addClass("hidden");
			} else {
				$(".show_time_26").removeClass("hidden");
			}
		});
		$(".modal_search .change_type_search button.active-adv").click();

		$(".modal_search select[name=user_city]").on('change',function() {
			var city_id = $(this).val();
			$(".modal_search .metro_select").addClass("hidden");

			$(".modal_search select[name=user_metro]").text("");

			$.ajax({
				url: ave_host+'/metro-load/',
				data: {city_id: city_id, csrf_token: csrf_token},
				success: function( data ) {

					if(data.respons) {
						var ln = 0;
						$.each(data.respons, function( index, value ) {
							ln++;
							$(".modal_search select[name=user_metro]").append('<option value="'+value.id+'">'+value.title+'</option>');
						});

						$('.modal_search select[name=user_metro]').selectpicker('refresh');

						if(ln >= 1) {
							$(".modal_search .metro_select").removeClass("hidden");
						}
					}
				}
			});

		});
		
	  });
</script>
