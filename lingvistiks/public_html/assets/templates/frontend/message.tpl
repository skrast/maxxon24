<main class="sidebar-left">
	<div class="container">

		<ol class="breadcrumb">
          <li><a href="{{ HOST_NAME }}">{{ lang.page_breadcrumb }}</a></li>
          <li class="active text-uppercase">{{ lang.message_title }}</li>
        </ol>

		<h1>{{ lang.message_title }}</h1>

		<div class="row">

			{{ perfomens_col }}

			<div class="col-md-9">
				<div class="content-right chat-wrapper">

					<div class="js-chat-management">
						<div class="chat-container">
							<div class="chat-management__flex">
								<div class="chat-management__part">
									<div class="chat-offers">
										<div class="notice-offers">


											<div class="row">
												<div class="col-md-4">

													<form action="{{ HOST_NAME }}/message/" method="post" enctype="multipart/form-data">
														<input type="hidden" name="csrf_token" value="{{ csrf_token }}">
														<div class="form-group">
															<input type="search" class="search" name="search_message" value="{{ REQUEST.search_message|escape|stripslashes }}" placeholder="{{ lang.message_search }}">
														</div>
													</form>

													<div id="content-4" class="chat-message-owner" data-max="{{ max_owner_on_chat }}">
														{% for user in user_message_list %}
															{{ user.message_html }}
														{% endfor %}
													</div>

												</div>
												<div class="col-md-8">

													<div class="messages-chat chat-offers-body">

														{% if from_info.user_group != 5 %}

														<form method="post" action="{{ HOST_NAME }}/message/upload/" enctype="multipart/form-data" novalidate class="box">
															<input type="hidden" name="csrf_token" value="{{ csrf_token }}">
															<input type="hidden" name="message_to" value="{{ send_message_to }}">

															<div class="box-to-d2d">
																<div class="box__input">
																	<img src="{{ ABS_PATH }}assets/site/template/images/upload-photo-big.png" alt="" class="non-drag">
																	<img src="{{ ABS_PATH }}assets/site/template/images/upload_photo_big_active.png" alt="" class="drag hidden">
																	<input type="file" name="upload_file[]" id="file" class="box__file" data-multiple-caption="{count} files selected" multiple />
																	<label for="file"><span class="box__dragndrop">{{ lang.message_upload_drag }}</span></label>
																</div>
																<div class="box__uploading">{{ lang.message_upload }}&hellip;</div>
																<div class="box__success">{{ lang.message_upload_success }} <a href="" class="box__restart" role="button">{{ lang.message_upload_more }}</a></div>
																<div class="box__error">{{lang.message_upload_error }}<span></span>. <a href="" class="box__restart" role="button">{{ lang.message_upload_reload }}</a></div>
															</div>
														{% endif %}

															<div id="content-4-1" class="mess_list">
																<div class="list-message-block">
																	{% if count_all > message_list|length %}<a href="#" class="load_more_message">{{ lang.message_load_more }}</a>{% endif %}
																	<ul class="media-list " data-all="{{ count_all }}" data-max="{{ max_message_on_chat }}" data-to="{{ send_message_to }}" data-last="">
																		{% for message in message_list %}
																			{{ message.message_html_block }}
																		{% else %}
																			<li><div class="album_empty message_empty">{{ lang.message_empty }}</div></li>
																		{% endfor %}
																	</ul>
																</div>
															</div>

														{% if from_info.user_group != 5 %}
														</form>

														<div class="message_option text-center">
															<ul class="list-inline">
																<li><a href="" data-type="1">{{ lang.message_copy }}</a></li>
																<li><a href="" data-type="2">{{ lang.message_answer }}</a></li>
																<li><a href="" data-type="3">{{ lang.message_resend }}</a></li>
																<li><a href="#" class="close_option">x</a></li>
															</ul>
														</div>
														{% endif %}

													</div>

													<div class="clearfix"></div>

													{% if send_message_to and from_info.user_group != 5 %}
														<form action="{{ HOST_NAME }}/message/?message_to={{ send_message_to }}" data-default="{{ HOST_NAME }}/message/?message_to={{ send_message_to }}" method="post" enctype="multipart/form-data" class="ajax_form_message" id="ajax_form_message" data-reset="1">

															<input type="hidden" name="csrf_token" value="{{ csrf_token }}">
															<input type="hidden" name="save" value="1">
															<input type="hidden" name="parent_id" value="">

															<div class="chat-offers__footer">
																<div class="message_answer">
																	{{ lang.message_is_answer }} <span></span> <a href="#">x</a>
																</div>
																<div class="chat-offers__input">
																	<div class="chat-offers__input-faux">
																		<textarea class="chat-offers__input-real" name="message_desc" id="" rows="1" placeholder="{{ lang.message_desc }}"></textarea>
																	</div>
																</div>
															</div>

															<div class="form-group">
																<div class="pull-right" style="margin:0 5px 0 0;">

																	<button class="upload-photo get_ajax_form" data-type="siteMessage" data-sub="upload_file_form" data-void="{{ send_message_to }}" data-reset="1">&nbsp;</button>

																	<button type="submit" class="btn btn-block btn-send btn-go-on">{{ lang.btn_send }}</button>
																</div>
																<div class="clearfix"></div>
															</div>
														</form>
													{% endif %}

												</div>
											</div>


										</div>
									</div>
								</div>
							</div>
						</div>
					</div>

				</div>
			</div>
		</div>
	</div>
</main>

<style>
	.partners, footer {display:none;}
</style>

<link rel="stylesheet" href="{{ ABS_PATH }}assets/site/assets/malihu-custom-scrollbar-plugin-master/jquery.mCustomScrollbar.css">
<script src="{{ ABS_PATH }}assets/site/assets/malihu-custom-scrollbar-plugin-master/jquery.mCustomScrollbar.concat.min.js"></script>
<link rel="stylesheet" href="{{ ABS_PATH }}assets/site/assets/lightbox/css/lightbox.css" />
<script src="{{ ABS_PATH }}assets/site/assets/lightbox/js/lightbox.min.js"></script>
<script src='{{ABS_PATH}}assets/js/autosize.min.js'></script>

<!-- <script src="{{ ABS_PATH }}assets/site/assets/lib/draganddrop.js"></script> -->


<link rel="stylesheet" href="{{ ABS_PATH }}vendor/assets/fileuploader/css/jquery.filer.css" type="text/css">
<link rel="stylesheet" href="{{ ABS_PATH }}vendor/assets/fileuploader/css/jquery.fileuploader-theme-thumbnails.css" type="text/css">
<script src="{{ ABS_PATH }}vendor/assets/fileuploader/js/jquery.filer.min.js"></script>

<script>
$(document).ready(function() {
	// init
	App.message_core();
	// init	
});
</script>
