<div class="menubar">
    <div class="page-title">
		<a href="{{ ABS_PATH_ADMIN_LINK }}?do=module&sub=mod_edit&module_tag=page&module_action=page_start" class="bread"><i class="fa fa-angle-double-left"></i></a>

        <h1>{{ lang.page_navi }}</h1>
    </div>
</div>

<div class="datatables">
    <div class="content-wrapper">
		<div class="row">
            <div class="col-md-4">
                <form method="post" action="{{ ABS_PATH_ADMIN_LINK }}?do=module&sub=mod_edit&module_tag=page&module_action=navi_work" class="ajax_form block-margin-n20">
                    <input type="hidden" name="csrf_token" value="{{ csrf_token }}">
                    <input type="hidden" name="save" value="1">

                    <div class="form-group">
						<label for="">{{ lang.page_title }}</label>
                        <input type="text" value="" class="form-control" name="navi_title" placeholder="{{ lang.page_title }}">
                    </div>

					<div class="form-group">
                        <label>{{ lang.page_lang }}</label>
                        <div class="clearfix"></div>
                        <select class="selectpicker" name="navi_lang">
                            {% for lang in lang_array %}
                                <option value="{{ lang }}">{{ lang }}</option>
                            {% endfor %}
                        </select>
                    </div>

					<div class="form-group">
                        <label>{{ lang.page_navi_place }}</label>
                        <div class="clearfix"></div>
                        <select class="selectpicker" name="navi_place">
                            {% for key, place in place_array %}
                                <option value="{{ key }}">{{ place }}</option>
                            {% endfor %}
                        </select>
                    </div>

                    <button class="btn-flat gray">{{ lang.page_folder_add }}</button>
                </form>
            </div>

			<div class="col-md-8">
		        {% if navi %}
		            <table class="datatables table-striped">
		                <thead>
		                    <tr>
		                        <th class="col-md-9">
		                            {{ lang.page_title }}
		                        </th>
		                        <th class="col-md-1">
		                            {{ lang.page_navi_place }}
		                        </th>
		                        <th class="col-md-1">
		                            {{ lang.page_lang }}
		                        </th>
		                        <th class="col-md-1">
		                            &nbsp
		                        </th>
		                    </tr>
		                </thead>
		                <tbody>
		                    {% for nav in navi %}
		                        <tr>
		                            <td>
		                                <a href="{{ ABS_PATH_ADMIN_LINK }}?do=module&sub=mod_edit&module_tag=page&module_action=navi_work&navi_id={{nav.navi_id}}">{{nav.navi_title|escape|stripslashes}}</a>
		                            </td>

		                            <td>
		                                {{ place_array[nav.navi_place]|escape|stripslashes }}
		                            </td>

		                            <td>
		                                {{nav.navi_lang|escape|stripslashes}}
		                            </td>

		                            <td>
		                                <a href="{{ ABS_PATH_ADMIN_LINK }}?do=module&sub=mod_edit&module_tag=page&module_action=navi_delete&navi_id={{nav.navi_id}}" class="confirm" data-toggle="tooltip" data-placement="top" title="{{ lang.save_delete }}"><i class="fa fa-times"></i></a>
		                            </td>
		                        </tr>
		                    {% endfor %}
		                </tbody>
		            </table>

		        {% else %}
		            <p>{{ lang.empty_data }}</p>
		        {% endif %}
			</div>
		</div>

    </div>
</div>
