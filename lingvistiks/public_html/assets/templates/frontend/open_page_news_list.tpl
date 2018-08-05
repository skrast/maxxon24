<main class="no-sidebar">
	<div class="container">

		<ol class="breadcrumb">
			<li><a href="{{ HOST_NAME }}">{{ lang.page_breadcrumb }}</a></li>
			<li class="active text-uppercase">{{ page_info.page_title|escape|stripslashes }}</li>
		</ol>

		<h1>{{ lang.page_news }}</h1>
		<div class="row news-list">
			<div class="col-md-9 col-sm-9">

				{% for news in news_list %}
					<div class="news-in-item">
					<!-- div style="display:none"> {{ news.page_preview_site }}</div -->
						<div class="media">
							<p class="news-in-date">{{ news.page_add }}</p>
							<a class="pull-left" href="{{ HOST_NAME }}/{{ news.page_alias|escape|stripslashes }}">
								<!--img class="media-object" src="{{ ABS_PATH }}?thumb={{news.page_preview_site}}&width=214&height=143"-->
								<img class="media-object" src="{{ ABS_PATH }}?thumb=uploads/no-preview-available.png&width=214&height=143">
							</a>
							<div class="media-body">
								<h4 class="media-heading"><a href="{{ HOST_NAME }}/{{ news.page_alias|escape|stripslashes }}">{{ news.page_title|escape|stripslashes|truncate(100) }}</a></h4>
								<!--p>{{ news.page_text|striptags|truncate(400) }}</p-->
							</div>
						</div>
					</div>
				{% endfor %}

				{% if page_nav %}
				    {{ page_nav }}
				{% endif %}

			</div>
			<div class="col-md-3 col-sm-3 filter-theme">

				<h3>{{ lang.page_news_filter }}</h3>
				<form action="{{ HOST_NAME }}/news{{ app.URL_SUFF }}" method="post">
					<input type="hidden" name="csrf_token" value="{{ csrf_token }}">
					<div class="checkbox">
						{% for tags in tags_view %}
							<label for="tags{{ tags.tags_title|escape|stripslashes }}">
								<input id="tags{{ tags.tags_title|escape|stripslashes }}" type="checkbox" name="tags_title[]" value="{{ tags.tags_title|escape|stripslashes }}" {% if tags.tags_title in REQUEST.tags_title %}checked{% endif %}> {{ tags.tags_title|escape|stripslashes }} ({{ tags.count_tags }})
							</label>
						{% endfor %}
					</div>
					<button type="submit" class="btn btn-block btn-adv">{{ lang.btn_filter }}</button>
				</form>

			</div>
		</div>
	</div>
</main>
