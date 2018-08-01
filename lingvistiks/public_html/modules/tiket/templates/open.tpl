<div class="menubar">
	<div class="page-title">
		<a href="{{ ABS_PATH_ADMIN_LINK }}?do=module&sub=mod_edit&module_tag=tiket&module_action=tiket_start" class="bread"><i class="fa fa-angle-double-left"></i></a>

		<h1>{{ lang.module_name_one }}: {{ module_info.module_name|escape|stripslashes }}</h1>
		<div class="clearfix"></div>

		<ul class="page-title-menu list-inline">
			{% if tiket_info.tiket_close != 1 %}
				<li>
					<a href="{{ ABS_PATH_ADMIN_LINK }}?do=module&sub=mod_edit&module_tag=tiket&module_action=tiket_work&tiket_id={{tiket_info.tiket_id|escape|stripslashes}}" class="btn-flat gray"><i class="fa fa-pencil"></i> {{ lang.tiket_edit }}</a>
				</li>
				<li>
					<a href="{{ ABS_PATH_ADMIN_LINK }}?do=module&sub=mod_edit&module_tag=tiket&module_action=tiket_work&close_tiket={{tiket_info.tiket_id|escape|stripslashes}}" class="confirm btn-flat gray"><i class="fa fa-lock"></i> {{ lang.tiket_close }}</a>
				</li>
			{% else %}
				<li>
					<div class="btn-flat success">{{ lang.tiket_status_array[tiket_info.tiket_close] }}</div>
				</li>
			{% endif %}
			{% if SESSION.alles %}
				<li>
					<a href="{{ ABS_PATH_ADMIN_LINK }}?do=module&sub=mod_edit&module_tag=tiket&module_action=tiket_work&delete_tiket_id={{tiket_info.tiket_id}}" class="confirm btn-flat danger"><i class="fa fa-times"></i> {{ lang.tiket_delete }}</a>
				</li>
			{% endif %}
		</ul>
	</div>
</div>

<div class="datatables">
    <div class="content-wrapper container">
		<h1>{{ tiket_info.tiket_title|escape|stripslashes }}</h1>
		<div class="clearfix"></div>
		{{ tiket_info.tiket_text|stripslashes }}
		<hr>
		<p>
			<ul class="list-unstyled">
				{% if tiket_info.tiket_tags_list %}
					<li>
						{{ lang.tiket_tags }}:
						{% for key, tags in tiket_info.tiket_tags_list %}
							<a href="{{ ABS_PATH_ADMIN_LINK }}?do=module&sub=mod_edit&module_tag=tiket&module_action=tiket_start&filter=1&tiket_tags={{ tags|escape|stripslashes }}">{{ tags|escape|stripslashes }}</a>{% if key != tiket_info.tiket_tags_last %}, {% endif %}
						{% endfor %}
					</li>
				{% endif %}
				<li>{{ lang.tiket_owner }}: {{ tiket_info.tiket_owner.user_name|default(tiket_info.tiket_user_name)|escape|stripslashes }}</li>
				<li>
					{{ tiket_info.tiket_user_phone|escape|stripslashes }}
				</li>
				<li>
					{{ tiket_info.tiket_user_email|escape|stripslashes }}
				</li>
				{% if tiket_info.tiket_answer %}<li>{{ lang.tiket_answer }}: {{ tiket_info.tiket_answer.user_name|escape|stripslashes }}</li>{% endif %}
			</ul>
		</p>

		<h1>{{ lang.tiket_comment }}</h1>
		{% if tiket_info.tiket_close != 1 %}
			<form role="form" enctype="multipart/form-data" method="post" action="{{ ABS_PATH_ADMIN_LINK }}?do=module&sub=mod_edit&module_tag=tiket&module_action=tiket_comment_work" class="ajax_form block-margin-n20">
			    <input type="hidden" name="csrf_token" value="{{ csrf_token }}">
			    <input type="hidden" name="save" value="1">
			    <input type="hidden" name="tiket_id" value="{{ tiket_info.tiket_id }}">

				<div class="form-group">
					<label>{{ lang.tiket_comment_text }}</label>
					<textarea name="comment_text" id="" cols="30" rows="10" class="form-control" placeholder="{{ lang.tiket_comment_text }}"></textarea>
				</div>

				<div class="clearfix"></div>

			    <div class="actions">
			        <input type="submit" class="btn-flat gray" value="{{ lang.save_data }}">
			    </div>
			</form>
		{% endif %}

		{% if comment_list %}
			<ul class="list-unstyled timeline">
				{% for comment in comment_list %}
					<li class="timeline-entry history_list">
						<div class="timeline-stat">
							<div class="timeline-icon">
								<img src="{{ ABS_PATH_ADMIN_LINK }}?thumb={{ app.app_upload_dir }}/{{ app.app_users_dir }}/{{comment.comment_owner.user_photo}}&width={{ app.AVATAR_WIDTH }}&height={{ app.AVATAR_HEIGHT }}" alt="">
							</div>
						</div>

						<div class="timeline-label">
							<div class="timeline-label-status">
								<a href="{{ ABS_PATH_ADMIN_LINK }}?do=profile&sub=open&user_id={{comment.comment_owner.id}}">{{ comment.comment_owner.user_name|escape|stripslashes }}</a>
								<div class="timeline-time">{{comment.comment_date}}</div>
								<p>{{comment.comment_text|stripslashes|ntobr}}</p>
							</div>
						</div>
					</li>
				{% endfor %}
			</ul>
		{% endif %}

    </div>
</div>
