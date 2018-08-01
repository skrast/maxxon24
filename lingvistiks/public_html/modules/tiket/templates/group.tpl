<div class="menubar">
	<div class="page-title">
		<a href="{{ ABS_PATH_ADMIN_LINK }}?do=module&sub=mod_edit&module_tag=tiket&module_action=tiket_start" class="bread"><i class="fa fa-angle-double-left"></i></a>

		<h1>{{ lang.module_name_one }}: {{ module_info.module_name|escape|stripslashes }}: {{ lang.tiket_group_work }}</h1>
	</div>
</div>

<div class="datatables">
	<div class="content-wrapper">
		<div class="row">
			<div class="col-lg-3 col-md-4">
				<div class="block-margin-n20">

					<form method="post" action="{{ ABS_PATH_ADMIN_LINK }}?do=module&sub=mod_edit&module_tag=tiket&module_action=tiket_group_work" enctype="multipart/form-data">
					    <input type="hidden" name="csrf_token" value="{{ csrf_token }}">
					    <input type="hidden" name="save" value="1">

						<div class="form-group">
							<label for="">{{ lang.tiket_group_title }}</label>
				            <textarea id="" cols="30" rows="10" class="form-control" name="group_title_wall" placeholder="{{ lang.tiket_group_title_desc }}"></textarea>
				        </div>

					    <div class="actions">
					        <input class="btn-flat gray" value="{{ lang.save_data }}" type="submit">
					    </div>
					</form>


				</div>
			</div>
			<div class="col-lg-9 col-md-8">
				{% if tiket_group %}
					<div class="dd model_list_stage" id="nestable">
						<ul class="dd-list">

							{% for group in tiket_group %}
							    <li class="dd-item" data-id="{{group.tiket_group_id}}">

							        <div class="dd3-content">
										<table class="datatables">
											<tr>
												<td class="col-md-10">
													{{ group.tiket_group_title|escape|stripslashes }}
												</td>
												<td class="col-md-2">
													<ul class="list-inline pull-right">
														<li>
															<a href="#" data-toggle="modal" data-target="#edit_group_{{group.tiket_group_id}}"><i class="fa fa-pencil"></i></a>
														</li>
														<li>
															<a href="{{ ABS_PATH_ADMIN_LINK }}?do=module&sub=mod_edit&module_tag=tiket&module_action=tiket_group_work&delete_group_id={{group.tiket_group_id}}" class="confirm"><i class="fa fa-times"></i></a>
														</li>
													</ul>
												</td>
											</tr>
										</table>
							        </div>

									<div class="modal fade" id="edit_group_{{group.tiket_group_id}}" role="dialog">
										<div class="modal-dialog">
											<div class="modal-content">
												<div class="modal-header">
													<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
													<h4 class="modal-title">{{ lang.tiket_group_edit }}</h4>
												</div>
												<div class="modal-body">
													<form method="post" action="{{ ABS_PATH_ADMIN_LINK }}?do=module&sub=mod_edit&module_tag=tiket&module_action=tiket_group_work">
										                <input type="hidden" name="csrf_token" value="{{ csrf_token }}">
										                <input type="hidden" name="save" value="1">
										                <input type="hidden" name="tiket_group_id" value="{{group.tiket_group_id}}">

										                <div class="form-group">
															<label for="">{{ lang.tiket_group_title }}</label>
										                    <input type="text" value="{{ group.tiket_group_title|escape|stripslashes }}" class="form-control" name="tiket_group_title" placeholder="{{ lang.tiket_group_title }}">
										                </div>

														<div class="actions">
															<input type="submit" class="btn-flat gray" value="{{ lang.save_data }}">
														</div>
										            </form>
												</div>
											</div>
										</div>
									</div>


							    </li>
							{% endfor %}


						</ul>
					</div>
					{% else %}
					<p>{{ lang.empty_data }}</p>
				{% endif %}
			</div>

		</div>
	</div>
</div>
