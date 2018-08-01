<div class="menubar">
    <div class="page-title">
        <a href="{{ ABS_PATH_ADMIN_LINK }}?do=module&sub=mod_edit&module_tag=page&module_action=navi_start" class="bread"><i class="fa fa-angle-double-left"></i></a>

        <h1>{{ lang.page_navi }} {{ navi_info.navi_title }}</h1>
    </div>
</div>

<div class="datatables">
    <div class="content-wrapper">
		<div class="row">
			<div class="col-lg-3 col-md-4">
				<form role="form" enctype="multipart/form-data" method="post" action="{{ ABS_PATH_ADMIN_LINK }}?do=module&sub=mod_edit&module_tag=page&module_action=navi_work" class="ajax_form">
		            <input type="hidden" name="csrf_token" value="{{ csrf_token }}">
		            <input type="hidden" name="save" value="1">
		            {% if navi_info.navi_id %}
						<input type="hidden" name="navi_id" value="{{ navi_info.navi_id }}">
					{% endif %}

		            <div class="form-group">
		                <label>{{ lang.page_title }}</label>
		                <input type="text" name="navi_title" class="form-control" value="{{ navi_info.navi_title|escape|stripslashes }}" placeholder="{{ lang.page_title }}">
		            </div>

		            <div class="form-group">
		                <label>{{ lang.page_lang }}</label>
		                <div class="clearfix"></div>
		                <select class="selectpicker" name="navi_lang">
		                    {% for lang in lang_array %}
		                        <option value="{{ lang }}" {% if navi_info.navi_lang == lang %}selected{% endif %} >{{ lang }}</option>
		                    {% endfor %}
		                </select>
		            </div>

					<div class="form-group">
                        <label>{{ lang.page_navi_place }}</label>
                        <div class="clearfix"></div>
                        <select class="selectpicker" name="navi_place">
                            {% for key, place in place_array %}
                                <option value="{{ key }}" {% if navi_info.navi_place == key %}selected{% endif %} >{{ place }}</option>
                            {% endfor %}
                        </select>
                    </div>

		            <div class="clearfix"></div>
		            <div class="actions">
		                <input type="submit" class="btn-flat gray" value="{{ lang.save_data }}">
		            </div>
		        </form>
			</div>
			<div class="col-lg-9 col-md-8">
				{% if navi_info.navi_items %}
		            <div class="dd" id="nestable" data-essense="{{ navi_info.navi_id }}">
		                <ol class="dd-list">
							{% set naviitems = navi_info.navi_items %}
		                    {% include 'page/templates/item_show.tpl' with {'navi_items': navi_info.navi_items[0], 'naviitems': naviitems, 'navi_info': navi_info} %}
		                </ol>
		            </div>
				{% else %}
		            <p>{{ lang.empty_data }}</p>
		        {% endif %}

				<hr>
				<h2>{{ lang.page_navi_add }}</h2>
				<form role="form" enctype="multipart/form-data" method="post" action="{{ ABS_PATH_ADMIN_LINK }}?do=module&sub=mod_edit&module_tag=page&module_action=navi_work_item" class="ajax_form">
		            <input type="hidden" name="csrf_token" value="{{ csrf_token }}">
		            <input type="hidden" name="save" value="1">
		            <input type="hidden" name="navi_id" value="{{ navi_info.navi_id }}">

		            <div class="form-group">
		                <label>{{ lang.page_navi_item_title }}</label>
		                <input type="text" name="item_title" class="form-control" value="" placeholder="{{ lang.page_navi_item_title }}">
						<input type="hidden" name="item_page" value="">
		            </div>

		            <div class="form-group">
		                <label>{{ lang.page_navi_page_title }}</label>
		                <input type="text" id="item_page_new" class="form-control" value="" placeholder="{{ lang.page_title_desc }}">
						<input type="hidden" name="item_page" value="">
		            </div>

		            <div class="clearfix"></div>
		            <div class="actions">
		                <input type="submit" class="btn-flat gray" value="{{ lang.save_data }}">
		            </div>
		        </form>

				<script>
					$(function () {

						var updateOutput = function (e) {
						 var list = e.length ? e : $(e.target),
								 output = list.data('output');
						 if (window.JSON) {
							var nest = window.JSON.stringify(list.nestable('serialize'));

							var essense_id = $('#nestable').attr('data-essense');

							$.ajax({
								url: ave_path+'?do=module&sub=mod_edit&module_tag=page&module_action=navi_sort',
								data: {'nest':nest, essense_id: essense_id, csrf_token: csrf_token},
								success: function( data ) {
									//alert('test');

								},
							});
						 }
					};

					$('#nestable').nestable({
						 group: 1,
						 maxDepth: 3,
						 expandBtnHTML: '<button data-action="expand"><i class="fa fa-plus-square-o"></i></button>',
						 collapseBtnHTML: '<button data-action="collapse"><i class="fa fa-minus-square-o"></i></button>',
					}).on('change', updateOutput);

					var item_page_new = {
						serviceUrl: ave_path+'?do=module&sub=mod_edit&module_tag=page&module_action=search_page',
						minChars: 0,
						delimiter: /(,|;)\s*/,
						maxHeight: 400,
						width: 300,
						zIndex: 99999,
						deferRequestBy: 500,
						onSelect: function(suggestion) {
							$(this).next().val(suggestion.data.page_id);
							$(this).parent().prev().find("input").val(suggestion.data.page_title);
						},
					};
					$("#item_page_new").autocomplete(item_page_new);
				});
			</script>

			</div>
		</div>

    </div>
</div>
