<form action="{{ HOST_NAME }}/jobs/respons/" method="post" class="ajax_form" enctype="multipart/form-data">
	<input type="hidden" name="csrf_token" value="{{ csrf_token }}">
	<input type="hidden" name="save" value="1">
	<input type="hidden" name="void_id" value="{{ jobs_info.jobs_id }}">

	<div class="row">
		<div class="col-md-4 col-sm-4 col-xs-4">
			<p><strong>{{ lang.resume_group }}</strong></p>
			<p>{{ resume.resume_group|escape|stripslashes }}</p>
		</div>
		<div class="col-md-4 col-sm-4 col-xs-4">
			<p><strong>{{ lang.resume_price }}</strong></p>
			<p>{{ resume.resume_money_start|escape|stripslashes }} {{ resume.resume_money_currency.title|escape|stripslashes }}</p>
		</div>
		<div class="col-md-4 col-sm-4 col-xs-4">
			<p><strong>{{ lang.resume_work }}</strong></p>
			{% set work_stage = 0 %}
			<p>{% for work in work_list %} {% set work_stage = work_stage + work.work_stage %} {% endfor %} {{ work_stage|escape|stripslashes }} {{ lang.resume_work_stage }}</p>
		</div>
		<div class="clearfix"></div>
	</div>

	<h3>{{ jobs_info.jobs_title|escape|stripslashes }}</h3>
	<div class="form-group">
		<textarea class="form-control" placeholder="{{ lang.jobs_respons_desc }}" name="response_desc" rows="5"></textarea>
	</div>

	<button type="submit" class="btn btn-block btn-go-on">{{ lang.btn_send }}</button>
</form>
