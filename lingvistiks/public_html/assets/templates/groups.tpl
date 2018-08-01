<div class="menubar">
    <div class="page-title">
        <h1>{{ lang.group_name }} <sup>{{ num|default('0') }}</sup></h1>

        <div class="clearfix"></div>
        <ul class="page-title-menu list-inline">
            <li>
                <a class="btn-flat gray" href="{{ ABS_PATH_ADMIN_LINK }}?do=group&sub=work_group"><i class="fa fa-plus-circle" aria-hidden="true"></i> {{ lang.group_add }}</a>
            </li>
        </ul>
    </div>
</div>

<div class="datatables">
    <div class="content-wrapper">
        {% if groups %}
            <div class="table-responsive">
                <table class="datatables table-striped">
                    <thead>
                        <tr>
                            <th class="col-md-1">
                                {{ lang.group_id }}
                            </th>
                            <th class="col-md-5">
                                {{ lang.group_title }}
                            </th>
                            <th class="col-md-4">
                                {{ lang.group_users }}
                            </th>
                            <th class="col-md-1">
                               &nbsp
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        {% for group in groups %}
                            <tr>
                                <td>
                                    {{ group.user_group }}
                                </td>
                                <td>
                                    <a href="{{ ABS_PATH_ADMIN_LINK }}?do=group&sub=work_group&group_id={{ group.user_group }}">{{ group.user_group_name|escape|stripslashes }}</a>
                                </td>
                                <td>
                                    {{ group.count|default('0') }}
                                </td>
                                <td>
                                    <ul class="list-inline">
                                        <li><a href="{{ ABS_PATH_ADMIN_LINK }}?do=group&sub=work_group&group_id={{ group.user_group }}" data-toggle="tooltip" data-placement="top" title="{{ lang.save_edit }}"><i class="fa fa-pencil"></i></a></li>
                                        {% if group.user_group != 1 and group.count == 0 %}
                                            <li>
                                                <a href="{{ ABS_PATH_ADMIN_LINK }}?do=group&sub=work_group&group_id={{ group.user_group }}&delete=1" class="confirm" data-toggle="tooltip" data-placement="top" title="{{ lang.save_delete }}"><i class="fa fa-times"></i></a>
                                            </li>
                                        {% endif %}
                                    </ul>
                                </td>
                            </tr>
                        {% endfor %}
                    </tbody>
                </table>
            </div>
        {% else %}
            <p>{{ lang.empty_data }}</p>
        {% endif %}
    </div>
</div>
