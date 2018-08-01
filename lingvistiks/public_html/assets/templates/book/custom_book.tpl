<div class="menubar">
    <div class="page-title">
		<a href="{{ ABS_PATH_ADMIN_LINK }}?do=book" class="bread"><i class="fa fa-angle-double-left"></i></a>
        <h1>{{ lang.book_custom }}</h1>

		<div class="clearfix"></div>
        <ul class="page-title-menu list-inline">
            <li>
                <a class="btn-flat gray" href="" data-toggle="modal" data-target="#book_add"><i class="fa fa-plus-circle" aria-hidden="true"></i> {{ lang.book_custom_add }}</a>
            </li>
			{% if book_info %}
			<li>
                <a class="btn-flat gray get_ajax_form" href="" data-void="" data-essense="{{ book_info.id }}" data-desc="1" data-type="fields" data-sub="field_work" data-ajax="1"><i class="fa fa-plus-circle" aria-hidden="true"></i> {{ lang.book_field_add }}</a>
            </li>
			<li>
                <a class="btn-flat gray" href="{{ ABS_PATH_ADMIN_LINK }}?do=book&sub=custom_book_open&book_id={{ book_info.id }}">{{ lang.book_custom_open }}</a>
            </li>
			<li>
                <a class="btn-flat danger confirm" href="{{ ABS_PATH_ADMIN_LINK }}?do=book&sub=custom_book_delete&book_id={{ book_info.id }}" data-toggle="tooltip" data-placement="top" title="{{ lang.save_delete }}"><i class="fa fa-times"></i> {{ lang.book_custom_delete }}</a>
            </li>
			{% endif %}
        </ul>
    </div>
</div>

<div class="datatables">
	<div class="content-wrapper sendmail">

		<div class="row">
			<div class="col-md-3">
				<div class="block-margin-n20">
					<ul class="nav nav-pills nav-stacked nav-sm">
					    {% for key, book in custom_book %}
					        <li {% if REQUEST.book_id == key %}class="active"{% endif %}><a href="{{ ABS_PATH_ADMIN_LINK }}?do=book&sub=custom_book&book_id={{ key }}">{{ book.title }}</a></li>
					    {% endfor %}
					</ul>
				</div>
			</div>

			<div class="col-md-9">
		        {% if book_info %}
		            <form method="post" action="{{ ABS_PATH_ADMIN_LINK }}?do=book&sub=custom_book_edit&book_id={{ book_info.id }}">
		                <input type="hidden" name="save" value="1">
		                <input type="hidden" name="csrf_token" value="{{ csrf_token }}">
		                <input type="hidden" name="type" value="1">

						<div class="form-group">
							<label for="">{{ lang.book_name }}</label>
							<input type="text" name="title" class="form-control" value="{{book_info.title|escape|stripslashes}}" placeholder="{{ lang.book_name }}">
						</div>

						<div class="form-group">
							<div class="block-margin-n20 pse_link toggle-block">
								{{ lang.book_html }}
							</div>
							<div class="hidden">
								<label for="">{{ lang.book_block }} [content]</label>
								<textarea name="html_block" rows="8" id="code{{ book_info.id }}" cols="40">{{book_info.html_block|escape|stripslashes}}</textarea>

								<label for="">{{ lang.book_item }} [title], [field][n], [item_id]</label>
								<textarea name="html_item" rows="8" id="code_item{{ book_info.id }}" cols="40">{{book_info.html_item|escape|stripslashes}}</textarea>
							</div>
						</div>

						{% if fields_list %}
							<h4>{{ lang.book_field_list }}</h4>

			                <div class="dd" id="nestable">
			                    <ul class="dd-list">
			                        {% for field in fields_list %}
			                            <li class="dd-item" data-id="{{field.field_id}}">
			                                <div class="dd-handle dd3-handle"><div class="grippy_large"></div></div>
			                                <div class="dd3-content">
			                                    <table class="datatables">
			                                        <tr>
			                                            <td class="col-md-11">
															{{ field.field_title|escape|stripslashes }}
			                                            </td>
														<td class="col-md-1">
															<ul class="list-inline">
																<li data-toggle="tooltip" data-placement="top" title="{{ lang.save_edit }}">
																	<a class="get_ajax_form" href="" data-void="{{ field.field_id }}" data-essense="{{ book_info.id }}" data-desc="1" data-type="fields" data-sub="field_work" data-ajax="1">
																		<i class="fa fa-pencil"></i>
																	</a>
																</li>
																<li>
																	<a href="{{ ABS_PATH_ADMIN_LINK }}?do=book&sub=book_fields_delete&field_id={{field.field_id}}&book_id={{ book_info.id }}" class="confirm" data-toggle="tooltip" data-placement="top" title="{{ lang.save_delete }}"><i class="fa fa-times"></i></a>
																</li>
															</ul>
			                                            </td>
			                                        </tr>
			                                    </table>
			                                </div>
			                            </li>
			                        {% endfor %}
			                    </ul>
				            </div>
				        {% endif %}

		                <div class="actions">
		                    <input type="submit" class="btn-flat gray" value="{{ lang.save_data }}">
		                </div>
		            </form>
		        {% else %}
		            <p>{{ lang.empty_data }}</p>
		        {% endif %}
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="book_add" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">{{ lang.book_custom_add }}</h4>
			</div>
			<div class="modal-body">
				<form method="post" action="{{ ABS_PATH_ADMIN_LINK }}?do=book&sub=custom_book_add">
	                <input type="hidden" name="add" value="1">
	                <input type="hidden" name="csrf_token" value="{{ csrf_token }}">

	                <div class="form-group">
						<label for="">{{ lang.book_name }}</label>
	                    <input type="text" value="" class="form-control" name="title" placeholder="{{ lang.book_name }}">
	                </div>
					<div class="actions">
						<input type="submit" class="btn-flat gray" value="{{ lang.book_custom_add }}">
					</div>
	            </form>
			</div>
		</div>
	</div>
</div>

{% if book_info %}
<link rel="stylesheet" href="{{ ABS_PATH }}assets/build/css/codemirror.min.css" type="text/css">
<script src="{{ ABS_PATH }}assets/build/js/codemirror.min.js" type="text/javascript"></script>
<style>
.CodeMirror {
    border: 1px solid #eee;
    height: 100%;
}
</style>

<script>
	var code = document.getElementById("code{{ book_info.id }}");
	var editor = CodeMirror.fromTextArea(code, {
		lineNumbers: true,
		styleActiveLine: true,
		matchBrackets: true,
		theme: 'neo',
		viewportMargin: 50,
		tabMode: 'shift',
	});

	var code_item = document.getElementById("code_item{{ book_info.id }}");
	var editor_item = CodeMirror.fromTextArea(code_item, {
		lineNumbers: true,
		styleActiveLine: true,
		matchBrackets: true,
		theme: 'neo',
		viewportMargin: 50,
		tabMode: 'shift',
	});

    $(function(){

        var updateOutput = function (e) {
             var list = e.length ? e : $(e.target),
                     output = list.data('output');
             if (window.JSON) {
                var nest = window.JSON.stringify(list.nestable('serialize'));

                $.ajax({
                    url: ave_path+'?do=book&sub=custom_book_edit',
                    data: {'nest':nest, book_id: '{{ book_info.id }}', csrf_token: csrf_token},
                    success: function( data ) {
                        //alert('test');
                    },
                });
             }
        };

        $('#nestable').nestable({
             group: 1,
             expandBtnHTML: '',
             collapseBtnHTML: '',
             listNodeName: 'ul',
        }).on('change', updateOutput);

		$(".toggle-block").on('click',function() {
			editor.refresh();
			editor_item.refresh();
		});

    });
</script>
{% endif %}
