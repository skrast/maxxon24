<form role="form" enctype="multipart/form-data" method="post" action="{{ ABS_PATH_ADMIN_LINK }}?do=module&sub=mod_edit&module_tag=page&module_action=navi_work_item" class="ajax_form">
	<input type="hidden" name="csrf_token" value="{{ csrf_token }}">
	<input type="hidden" name="save" value="1">
	<input type="hidden" name="navi_id" value="{{ navi_info.navi_id }}">
	<input type="hidden" name="item_id" value="{{ item_info.item_id }}">

	<div class="form-group">
		<label>{{ lang.page_navi_item_title }}</label>
		<input type="text" name="item_title" class="form-control" value="{{ item_info.item_title }}" placeholder="{{ lang.page_navi_item_title }}">
		<input type="hidden" name="item_page" value="">
	</div>

	<div class="form-group">
		<label>{{ lang.page_navi_page_title }}</label>
		<input type="text" id="item_page_edit" class="form-control" value="" placeholder="{{ lang.page_title_desc }}">
		<input type="hidden" name="item_page" value="{{ item_info.item_id }}">
	</div>

	<div class="clearfix"></div>
	<div class="actions">
		<input type="submit" class="btn-flat gray" value="{{ lang.save_data }}">
	</div>
</form>

<script>
	$(function () {

	var item_page_edit = {
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
	$("#item_page_edit").autocomplete(item_page_edit);
});
</script>
