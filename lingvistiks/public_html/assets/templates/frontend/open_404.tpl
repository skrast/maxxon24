<main class="no-sidebar">
	<div class="container">

		{{ breadcrumb }}

		<div class="row">
			<div class="col-md-12">
				<div class="news-item">
					{% if page_info.page_preview %}
						<img src="{{ ABS_PATH }}?thumb={{ page_info.page_preview_site }}&width=400&height=400" alt="" class="pull-right">
					{% endif %}

					<h1>{{ page_info.page_title|escape|stripslashes }}</h1>

					{{ page_info.page_text|stripslashes }}
				</div>
			</div>
		</div>
	</div>
</main>
