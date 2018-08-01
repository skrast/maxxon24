{% for item in navi_items %}
    <li>

        <a href="{{ HOST_NAME }}/{{ item.page_info.page_alias|escape|stripslashes }}">{{ item.item_title|escape|stripslashes }}</a>

        {% if naviitems[item.item_id] %}
            <ul class="list-unstyled">
                {% include 'page/templates/item_show_site.tpl' with {'navi_items': naviitems[item.item_id], 'navi_info': navi_info}  %}
            </ul>
        {% endif %}
    </li>
{% endfor %}
