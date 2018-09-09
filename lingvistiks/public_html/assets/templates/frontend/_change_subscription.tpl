<main class="sidebar-left">
	<div class="container">

		<ol class="breadcrumb">
			<li><a href="{{ HOST_NAME }}">{{ lang.page_breadcrumb }}</a></li>
			<li class="active text-uppercase">{{ lang.order_list }}</li>
		</ol>

		<h1>{{ lang.order_list }}</h1>

		<div class="row">
			{{ perfomens_col }}
			<div class="col-md-9 col-sm-9">
			<div class="content-right">
				<div class="col-md-12 album_empty medium_font"><p>{{ message }}<br></p>
				<a href="{{ HOST_NAME }}/billing/">{{ lang.search_orders_empty_change_subscription }}</a>
				</div>
			</div>
			</div>
		</div>

	</div>
</main>


<script src="{{ ABS_PATH }}vendor/assets/moment-with-locales.js"></script>
<script type="text/javascript" src="{{ ABS_PATH }}vendor/assets/flatpickr/dist/flatpickr.js"></script>
<link rel="stylesheet" type="text/css" href="{{ ABS_PATH }}vendor/assets/flatpickr/dist/themes/airbnb.css" />
<script src="{{ ABS_PATH }}vendor/assets/flatpickr/dist/l10n/{{ SESSION.user_lang|default(app.app_lang) }}.js" charset="UTF-8"></script>


<script>
$(function () {

	$('.collapse').on('show.bs.collapse', function () {
	$(this).siblings('.row').addClass('active');
  });

  $('.collapse').on('hide.bs.collapse', function () {
	$(this).siblings('.row').removeClass('active');
  });

	// init
	App.search_order_core();
	// init

	$("input[name=order_start].datepicker, input[name=order_end].datepicker").flatpickr({
		locale: '{{ SESSION.user_lang|default(app.app_lang) }}',
		enableTime: true,
		dateFormat: 'd.m.Y H:i',
		allowInput: true,
		time_24hr: true,
		minDate: "today",
	});

});
</script>
