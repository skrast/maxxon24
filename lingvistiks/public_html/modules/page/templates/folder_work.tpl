<div class="menubar">
    <div class="page-title">
        <a href="{{ ABS_PATH_ADMIN_LINK }}?do=module&sub=mod_edit&module_tag=page&module_action=page_start" class="bread"><i class="fa fa-angle-double-left"></i></a>

        <h1>{{ lang.page_folder }}</h1>
    </div>
</div>

<div class="datatables">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-md-4">
                <form method="post" action="{{ ABS_PATH_ADMIN_LINK }}?do=module&sub=mod_edit&module_tag=page&module_action=folder_work" class="block-margin-n20">
                    <input type="hidden" name="csrf_token" value="{{ csrf_token }}">

                    <div class="form-group">
						<label for="">{{ lang.page_folder_title }}</label>
                        <input type="text" value="" class="form-control" name="folder_title_new" placeholder="{{ lang.page_folder_title }}">
                    </div>
                    <button class="btn-flat gray">{{ lang.page_folder_add }}</button>
                </form>
            </div>

            <div class="col-md-8">
                {% if folders %}
                    <form method="post" action="{{ ABS_PATH_ADMIN_LINK }}?do=module&sub=mod_edit&module_tag=page&module_action=folder_work">
                        <input type="hidden" name="csrf_token" value="{{ csrf_token }}">

                        <div class="table-responsive">
                            <table class="datatables table-striped">
                                <thead>
                                    <tr>
                                        <th class="col-md-11">
                                            {{ lang.page_folder_title }}
                                        </th>
                                        <th class="col-md-1">
                                                &nbsp
                                        </th>
                                    </tr>
                                </thead>
                                {% for folder in folders %}
                                    <tr>
                                        <td>
                                            <input type="text" placeholder="{{ lang.page_folder_title }}" value="{{ folder.folder_title|escape|stripslashes }}" class="form-control" name="folder_title[{{folder.folder_id}}]">

                                        </td>
                                        <td>
                                            {% if SESSION.alles or folder.folder_add_author == SESSION.user_id %}
                                                <a href="{{ ABS_PATH_ADMIN_LINK }}?do=module&sub=mod_edit&module_tag=page&module_action=folder_delete&folder_id={{folder.folder_id}}" class="confirm" data-toggle="tooltip" data-placement="top" title="{{ lang.save_delete }}"><i class="fa fa-times"></i></a>
                                            {% endif %}
                                        </td>
                                    </tr>
                                {% endfor %}
                            </table>
                        </div>

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
