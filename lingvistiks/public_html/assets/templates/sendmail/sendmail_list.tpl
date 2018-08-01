<div class="menubar">
    <div class="page-title">
        <h1>{{ lang.sendmail_name }} <sup>{{ num|default('0') }}</sup></h1>

        <div class="clearfix"></div>

        <ul class="page-title-menu list-inline">
            <li>
                <a class="get_ajax_form btn-flat gray" href="" data-void="" data-type="sendmail" data-sub="compose" data-ajax="1">
                    <i class="fa fa-plus-circle" aria-hidden="true"></i> {{ lang.sendmail_add }}
                </a>
            </li>
            <li>
                <a class="btn-flat gray" href="{{ ABS_PATH_ADMIN_LINK }}?do=sendmail&sub=sendmail_signature">
                    {{ lang.sendmail_signature }}
                </a>
            </li>
        </ul>
    </div>
</div>

<div class="datatables">
    <div class="content-wrapper sendmail">

		<div class="row">
			<div class="col-md-3">
                {{ sendmail_filter }}
			</div>

			<div class="col-md-9">
				{% if mails %}
					{% if REQUEST.folder == 4 %}
						<ul class="list-inline">
							<li>
								<a class="btn-flat gray confirm" href="{{ ABS_PATH_ADMIN_LINK }}?do=sendmail&sub=erase_trash">
									<i class="fa fa-trash" aria-hidden="true"></i> {{ lang.sendmail_erase_trash }}
								</a>
							</li>
						</ul>
					{% endif %}
					<form class="form-inline" role="form" enctype="multipart/form-data" method="post" action="{{ ABS_PATH_ADMIN_LINK }}?do=sendmail&sub=mass_change">
                		<input type="hidden" name="csrf_token" value="{{ csrf_token }}">
                		<input type="hidden" name="folder" value="{{ REQUEST.folder|escape|stripslashes }}">

						<table class="datatables table-striped list-email">
							<thead>
	                            <tr>
	                                <th class="col-md-1">
	                                    &nbsp;
	                                </th>
	                                <th class="col-md-1">
	                                    {{ lang.sendmail_author }}
	                                </th>
	                                <th class="col-md-10">
	                                    {{ lang.sendmail_theme }}
	                                </th>
	                            </tr>
	                        </thead>
							<tbody class="table_mail">
								{% for mail in mails %}
							    <tr>
							    	<td>
							            <div class="checkbox checkbox-primary">
	                                        <input id="checkbox{{mail.mail_id}}" type="checkbox" name="elem_opt[{{mail.mail_id}}]" value="{{mail.mail_id}}">
	                                        <label for="checkbox{{mail.mail_id}}"></label>
	                                    </div>
							        </td>
							        <td class="member">
							        	{% if mail.mail_author %}
								        	<a href="{{ ABS_PATH_ADMIN_LINK }}?do=profile&sub=open&user_id={{ mail.mail_author.id }}">
									            <img class="img-rounded" src="{{ ABS_PATH_ADMIN_LINK }}?thumb=uploads/{{ app.app_users_dir }}/{{ mail.mail_author.user_photo }}&width={{ app.AVATAR_WIDTH }}&height={{ app.AVATAR_HEIGHT }}" alt="">
									        </a>
									    {% else %}
									    	<img class="img-rounded" src="{{ ABS_PATH_ADMIN_LINK }}?thumb=uploads/{{ app.app_users_dir }}/no-avatar.png&width={{ app.AVATAR_WIDTH }}&height={{ app.AVATAR_HEIGHT }}" alt="">
								        {% endif %}
							    	</td>
							        <td>
								        <div class="email-info">
								        	<div class="color-block-left label {{ mail.mail_folder.color }}" title="{{mail.mail_folder.title|escape|stripslashes}}" data-toggle="tooltip" data-placement="right" title="{{mail.mail_folder.title|escape|stripslashes}}">&nbsp</div>

								            <small class="email-time">{{ mail.mail_save }}</small>
								            <div class="email-title">
												{% if mail.mail_open == 0 %}<i class="fa fa-envelope-o" aria-hidden="true"></i>&nbsp{% endif %}

												{% if mail.mail_draft == 1 %}<a href="" data-void="{{ mail.mail_id }}" data-type="sendmail" data-sub="compose" data-ajax="1" class="get_ajax_form">{% else %}<a href="{{ ABS_PATH_ADMIN_LINK }}?do=sendmail&sub=open&mail_id={{ mail.mail_id }}">{% endif %}{{ mail.mail_title|escape|stripslashes }}</a>
								            </div>
								            <p class="email-desc">
								                {{ mail.mail_body|striptags|escape|stripslashes }}
								            </p>
								        </div>
								    </td>
							    </tr>
							    {% endfor %}
							</tbody>
							<tfoot>
								<tr>
									<th colspan="3">
										<a href="#" class="select_all_checkbox" data-target="table_mail">{{ lang.form_select_all }}</a>

										<ul class="list-inline mgtb20">
				                        	<li>
						                        <select class="selectpicker" name="operation">
						                            <option value="">{{ lang.form_select_mass }}</option>
						                            {% if REQUEST.folder == 1 or not REQUEST.folder %}
														<option value="1">{{ lang.sendmail_check_read }}</option>
														<option value="2">{{ lang.sendmail_check_no_read }}</option>
													{% endif %}
													<option value="3">{{ lang.sendmail_delete }}</option>
						                            <option value="4">{{ lang.sendmail_move_to_trash }}</option>
						                        </select>
							                </li>
							            </ul>

					                    <input type="submit" class="btn-flat gray" value="{{ lang.save_data }}">
									</th>
								</tr>
							</tfoot>
						</table>
                    </form>

					{% if page_nav %}
	                    {{page_nav}}
	                {% endif %}
				{% else %}
					{{ lang.empty_data }}
				{% endif %}
			</div>
		</div>
    </div>
</div>
