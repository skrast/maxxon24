{% for item in navi_items %}
    <li class="dd-item" data-id="{{item.item_id}}">
        <div class="dd-handle dd3-handle"><div class="grippy_large"></div></div>

        <div class="dd3-content">
            <table class="datatables">
                <tr>
                    <td class="col-md-10">
                    	{{ item.item_title|stripslashes|striptags }} ID {{ item.item_page|stripslashes|striptags }}
                    </td>
                    <td class="col-md-2">
                        <ul class="list-inline pull-right">
                            <li class="get_ajax_form" data-void="{{item.item_id}}" data-essense="{{navi_info.navi_id}}" data-type="page" data-sub="edit_item" data-module="1">
                                <a href="#item"><i class="fa fa-pencil"></i></a>
                            </li>
                            <li>
                                <a href="{{ ABS_PATH_ADMIN_LINK }}?do=module&sub=mod_edit&module_tag=page&module_action=item_delete&item_id={{item.item_id}}" class="confirm" data-toggle="tooltip" data-placement="top" title="{{ lang.save_delete }}"><i class="fa fa-times"></i></a>
                            </li>
                        </ul>
                    </td>
                </tr>
            </table>
        </div>

        {% if naviitems[item.item_id] %}
            <ol class="dd-list">
                {% include 'page/templates/item_show.tpl' with {'navi_items': naviitems[item.item_id], 'navi_info': navi_info}  %}
            </ol>
        {% endif %}
    </li>
{% endfor %}
