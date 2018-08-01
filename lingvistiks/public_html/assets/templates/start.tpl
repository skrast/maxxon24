<div class="menubar">
	<div class="page-title">
		<h1>{{ lang.start_name }}</h1>
	</div>
</div>

<div class="datatables">
	<div class="content-wrapper">

		{% if SESSION.alles %}
		<div class="row">
			<div class="col-md-6">
				<div class="panel panel-inverse">
					<div class="panel-heading">
						<h4 class="panel-title">{{ lang.start_stat_crm }}</h4>
					</div>
					<div class="panel-body cloud_stat">
						{{ bild_spinner }}
					</div>
				</div>
			</div>
		</div>
		{% endif %}


		<div class="row">
			{% for hook_value in hook_start_bild %}
				{{ hook_value.bild }}
			{% endfor %}
		</div>
	</div>
</div>

{% if SESSION.alles %}
<script>
$(function () {
	$.ajax({
		url: ave_path+'?do=start&sub=get_cloud_stat',
		data: {csrf_token: csrf_token},
		success: function( data ) {
			$(".cloud_stat").html(data.html);
			init_refresh_script();
		}
	});
});
</script>
{% endif %}
