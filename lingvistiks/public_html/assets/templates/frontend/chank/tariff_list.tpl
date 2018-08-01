<div class="col-md-12 tarif_table">    
    <div class="tariff_title">
        <div class="row access_list">
            <div class="col-md-3 white-color"><div><span>&nbsp;</span></div></div>
            {% for key, tariff in tarif_list %}
                <div class="col-md-3 color"><div><span>{{ tariff.name }}</span></div></div>
            {% endfor %}
        </div>
    </div>

    <div class="tariff_desc">
        {% for access_key, access in lang.billing_tariff_access[user_group] %}
            <div class="row access_list">
                <div class="col-md-3">
                    <div><span>{{ access }}</span></div>
                </div>

                {% for tariff_key, tariff in tarif_list %}
                    <div class="col-md-3 text-black">
                        <div>
                            <span>
                                <i class="fa {{ app.app_tariff_prop_icon[user_group][user_subgroup][tariff_key][access_key]|default('fa fa-check') }}"></i>

                                {{ lang.billing_tariff_prop[user_group][user_subgroup][tariff_key][access_key] }}
                            </span>
                        </div>
                    </div>
                {% endfor %}
            </div>
        {% endfor %}
    </div>

    <div class="form_list">
        <div class="row access_list">
            <div class="col-md-3 white-color">
                <div><span>&nbsp;</span></div>
            </div>
            {% for key, tarif in tarif_list %}
                <div class="col-md-3 text-black">
                    <div>

                        {% if user_group == 4 and key == 1 %}
                            <div class="form-group price_free text-center">
                                <label for="">{{ lang.billing_price_free }}</label>
                            </div>
                        {% else %}
                            <form action="{{ HOST_NAME }}/billing/" method="POST">
                                <input type="hidden" name="csrf_token" value="{{ csrf_token }}">
                                <input type="hidden" name="change_tariff" value="{{ key }}">
                                <input type="hidden" name="change_price" value="1">
                                
                                <div class="form-group">
                                    <label for="">{{ lang.billing_tariff_change }}</label>

                                    <div class="change_price_for_tarif">
                                        <div class="row">
                                            {% for month, price in tarif.price %}
                                                <div class="col-md-6 active_price" data-tarif="{{ key }}" data-disc="{{ month }}" data-price="{{ price.price }}" data-first="{{ tarif.price[1].price }}">{{ price.title }}</div>
                                            {% endfor %}
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group text-right price_calc">
                                    <ul class="list-unstyled text-center">
                                        <li>
                                            <del><span></span> {{ app.site_currency }}</del>
                                        </li>
                                        <li>
                                            <span></span> {{ app.site_currency }}
                                        </li>
                                    </ul>														
                                </div>

                                {% if no_pay_link != 1 %}
                                    <div class="form-group text-center price_btn">
                                        <button type="submit" name="change_type" value="1" class="btn btn-search btn-block">{{ lang.billing_change_pay_btn }}</button>
                                        {{ lang.save_or }}
                                        <button type="submit" name="change_type" value="2" class="btn btn-search btn-block btn-go-on">{{ lang.billing_change_pay_cp_btn }}</button>
                                    </div>
                                {% endif %}
                            </form>
                        {% endif %}

                    </div>
                </div>
            {% endfor %}
        </div>
    </div>
</div>
    







<!--




    <table class="row table_tariff">                   
        <tr class="tariff_list">
            <td class="col-md-3"></td>
            {% for key, tariff in tarif_list %}
                <td class="col-md-3 color"><div><span><img src="{{ ABS_PATH }}assets/site/template/images/{{ key }}.jpg" alt="">{{ tariff.name }}</span></div></td>
            {% endfor %}
        </tr>

        {% for access_key, access in lang.billing_tariff_access[SESSION.user_group] %}
            <tr class="access_list">
                <td class="col-md-3">
                    <div><span>{{ access }}</span></div>
                </td>

                {% for tariff_key, tariff in tarif_list %}
                    <td class="col-md-3 text-black">
                        <div>
                            <span>
                                <i class="fa {{ app.app_tariff_prop_icon[SESSION.user_group][SESSION.user_subgroup][tariff_key][access_key]|default('fa fa-check') }}"></i>

                                {{ lang.billing_tariff_prop[SESSION.user_group][SESSION.user_subgroup][tariff_key][access_key] }}
                            </span>
                        </div>
                    </td>
                {% endfor %}
            </tr>
        {% endfor %}


        <tr class="form_list">
            <td class="col-md-3"></td>
            {% for key, tarif in tarif_list %}

                <td class="col-md-3 text-black">
                    <div>
                        <form action="{{ HOST_NAME }}/billing/" method="POST">
                            <input type="hidden" name="csrf_token" value="{{ csrf_token }}">
                            <input type="hidden" name="change_tariff" value="{{ key }}">
                            
                            <div class="form-group">
                                <label for="">{{ lang.billing_tariff_change }}</label>
                                <select name="change_price" class="form-control">
                                    {% for month, price in tarif.price %}
                                        <option value="{{ month }}" data-disc="{{ price.disc }}" data-price="{{ price.price }}">{{ price.title }}</option>
                                    {% endfor %}
                                </select>
                            </div>

                            <div class="form-group text-right price_calc">
                                <ul class="list-unstyled">
                                    <li>
                                        {{ tarif.price[1].price }} {{ app.site_currency }} / 1 {{ lang.billing_tariff_change_month }}
                                    </li>
                                    <li>
                                        <small>{{ lang.billing_tariff_change_month_disc }} <span>{{ tarif.price.1.disc }}</span> {{ app.site_currency }}</small>
                                    </li>
                                    <li>
                                        {{ lang.billing_tariff_change_month_pay }} <span>{{ tarif.price.1.price }}</span> {{ app.site_currency }}
                                    </li>
                                </ul>														
                            </div>

                            <div class="form-group text-center price_btn">
                                <button type="submit" name="change_type" value="1" class="btn btn-search btn-block">{{ lang.billing_change_pay_btn }}</button>
                                {{ lang.save_or }}
                                <button type="submit" name="change_type" value="2" class="btn btn-search btn-block btn-go-on">{{ lang.billing_change_pay_cp_btn }}</button>
                            </div>
                        </form>

                    </div>
                </td>
            {% endfor %}
        </tr>
    </table>
</div>
-->