<div class="menubar">
    <div class="page-title">
    	<a href="{{ ABS_PATH_ADMIN_LINK }}?do=sendmail" class="bread"><i class="fa fa-angle-double-left"></i></a>
        <h1>{{ lang.sendmail_signature }}</h1>

		<div class="clearfix"></div>

        <ul class="page-title-menu list-inline">
            <li>
                <a class="get_ajax_form btn-flat gray" href="" data-void="" data-type="sendmail" data-sub="sendmail_signature_work" data-ajax="1">
                    <i class="fa fa-plus-circle" aria-hidden="true"></i> {{ lang.sendmail_signature_add }}
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
				{% if signature_list %}
					<div id="masonry" class="row project-block">
						{% for signature in signature_list %}

							<div class="module-wrapper search-item masonry-item col-lg-4 col-md-6 col-sm-6 col-xs-12">
								<section class="module project-module">
									<div class="module-inner">
										<div class="module-heading text-center">
											<h3 class="module-title">{{ signature.signature_title|escape|stripslashes }}</h3>
										</div>
										<div class="module-content">
											<div class="module-content-inner">
												<div class="project-intro">
													<p>{{ signature.signature_desc|ntobr|stripslashes}}</p>
												</div>
											</div>
										</div>
									</div>
									<div class="module-footer text-center">
										<ul class="utilities list-inline">
											<li data-toggle="tooltip" data-placement="top" title="{{ lang.save_edit }}">
												<a class="get_ajax_form" href="" data-void="{{ signature.signature_id }}" data-type="sendmail" data-sub="sendmail_signature_work" data-ajax="1"><i class="fa fa-pencil"></i></a>
											</li>
											<li>
												<a href="{{ ABS_PATH_ADMIN_LINK }}?do=sendmail&sub=sendmail_signature_delete&signature_id={{ signature.signature_id }}" class="confirm" data-toggle="tooltip" data-placement="top" title="{{ lang.save_delete }}"><i class="fa fa-times"></i></a>
											</li>
										</ul>
									</div>
								</section>
							</div>

						{% endfor %}
					</div>

					<div class="clearfix"></div>
					{% if page_nav %}
					    {{page_nav}}
					{% endif %}

					<script src='{{ ABS_PATH }}assets/js/masonry.min.js'></script>
					<script>
						$(document).ready(function() {
							$('#masonry').masonry({
							  itemSelector: '.masonry-item',
							});
						});
					</script>

				{% else %}
				    <p>{{ lang.empty_data }}</p>
				{% endif %}
			</div>
		</div>
	</div>
</div>
