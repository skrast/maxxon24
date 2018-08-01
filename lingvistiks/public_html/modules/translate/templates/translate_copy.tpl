<form role="form" enctype="multipart/form-data" method="post" action="{{ ABS_PATH_ADMIN_LINK }}?do=module&sub=mod_edit&module_tag=translate&module_action=translate_copy" data-ajax="1">
    <input type="hidden" name="csrf_token" value="{{ csrf_token }}">
    <input type="hidden" name="save" value="1">
    <input type="hidden" name="void_id" value="{{ void_id }}">

    <div class="form-group">
        <label>{{ lang.translate_new_lang }}</label>
        <input type="text" name="new_lang" class="form-control" value="" placeholder="{{ lang.translate_new_lang_desc }}" required pattern=".{2,}" maxlength="2">
    </div>

    <div class="clearfix"></div>

    <div class="actions">
        <input type="submit" class="btn-flat gray" value="{{ lang.save_data }}">
    </div>
</form>
