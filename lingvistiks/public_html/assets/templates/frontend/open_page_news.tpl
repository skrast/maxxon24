<main class="no-sidebar">
	<div class="container">

		<ol class="breadcrumb">
			<li><a href="{{ HOST_NAME }}">{{ lang.page_breadcrumb }}</a></li>
			<li><a href="{{ HOST_NAME }}{{ link_lang_pref }}/news{{ app.URL_SUFF }}">{{ lang.page_news }}</a></li>
			<li class="active text-uppercase">{{ page_info.page_title|escape|stripslashes }}</li>
		</ol>

		<div class="row">
			<div class="col-md-12">
				<div class="news-item">
					{% if page_info.page_preview %}
					<img src="{{ ABS_PATH }}?thumb={{ page_info.page_preview_site }}&width=600&height=450" alt="" class="pull-right">
					{% endif %}

					<h1>{{ page_info.page_title|escape|stripslashes }} <span class="pull-right">{{ page_info.page_add }}</span></h1>

					{{ page_info.page_text|stripslashes }}
				</div>
			</div>
		</div>

		<div class="row">
			<div class="{% if photos %}col-md-3{% else %}text-center col-md-12{% endif %}">
				{% if page_info.page_youtube %}
					<a href="#" class="youtube-min" {% if photos %}style="width:263px;height:200px;overflow:hidden;"{% endif %}>
						<iframe {% if photos %}width="263" height="200"{% else %}width="560" height="315"{% endif %} src="{{ page_info.page_youtube|stripslashes }}" frameborder="0" allowfullscreen></iframe>
					</a>
				{% endif %}
			</div>

			<div class="{% if page_info.page_youtube %}col-md-9{% else %}text-center col-md-12{% endif %}">
				{% if photos %}
					<div class="accordian">
						<ul class="list-unstyled">
							{% for photo in photos %}
								<li>
									<a href="{{ ABS_PATH }}{{ app.app_upload_dir }}/{{ app.app_module_dir }}/{{ module_info.module_tag }}/photos/{{ page_info.page_id }}/{{ photo.file_path }}" data-lightbox="image-1"><img src="{{ ABS_PATH }}?thumb={{ app.app_upload_dir }}/{{ app.app_module_dir }}/{{ module_info.module_tag }}/photos/{{ page_info.page_id }}/{{ photo.file_path }}&width=299&height=200" style="width:299px;height:200px" alt=""></a>
								</li>
							{% endfor %}
						</ul>
					</div>
				{% endif %}
			</div>

		</div>
	</div>
</main>

<link rel="stylesheet" href="{{ ABS_PATH }}assets/site/template/styles/accordion.css">
<link rel="stylesheet" href="{{ ABS_PATH }}assets/site/assets/lightbox/css/lightbox.css" />
<script src="{{ ABS_PATH }}assets/site/assets/lightbox/js/lightbox.min.js"></script>
<script>
$(document).ready(function(){

  $('#carouselThree').owlCarousel({
	  items:7,
	  autoplay:true,
	  nav:true,
	  autoWidth:true,
	  margin:0,
	  loop:true,
	  navText:["<img src='{{ ABS_PATH }}assets/site/template/images/prev.png'>","<img src='{{ ABS_PATH }}assets/site/template/images/next.png'>"]
	});
  });
  </script>
