<div class="row">
        <div class="col-md-6">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <select class="form-control selectpicker load_select" name="user_country" data-target="user_city" data-live-search="true">
                            <option value="">{{ lang.lk_country }}</option>
                            {% for country in country_list %}
                                <option value="{{ country.id|escape|stripslashes }}" {% if country.id == REQUEST.user_country %}selected{% endif %}>{{ country.title|escape|stripslashes }}</option>
                            {% endfor %}
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <select class="form-control selectpicker" name="user_city" data-default="{{ lang.lk_city }}" data-live-search="true">
                            <option value="">{{ lang.lk_city }}</option>
                            {% for city in city_list %}
                                <option value="{{ city.id|escape|stripslashes }}" {% if city.id == REQUEST.user_city %}selected{% endif %}>{{ city.title|escape|stripslashes }}</option>
                            {% endfor %}
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="row">
                <div class="col-md-4">
                    <p><strong>{{ lang.lk_lang_var }}</strong></p>
                </div>
                <div class="col-md-4 left-pad-0">
                    <div class="form-group">
                        <select class="form-control selectpicker" name="lang_from_temp">
                            <option value="">{{ lang.lk_lang_1 }}</option>
                            {% for lang in lang_list %}
                                <option value="{{ lang.id }}" {% if lang.id == REQUEST.lang_from_temp or lang.id == REQUEST.lang_from_temp2 %}selected{% endif %}>{{ lang.title|escape|stripslashes }}</option>
                            {% endfor %}
                        </select>
                    </div>
                </div>
                <div class="col-md-4 left-pad-0">
                    <div class="form-group">
                        <select class="form-control selectpicker" name="lang_to_temp">
                            <option value="">{{ lang.lk_lang_2 }}</option>
                            {% for lang in lang_list %}
                                <option value="{{ lang.id }}" {% if lang.id == REQUEST.lang_to_temp or lang.id == REQUEST.lang_to_temp2 %}selected{% endif %}>{{ lang.title|escape|stripslashes }}</option>
                            {% endfor %}
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {% if not 0 == REQUEST.user_type %}
    <div class="collapse" id="demo">
        <div class="row">

            <div class="col-md-6">
                <div class="form-group">
                    <ul class="list-inline text-center">
                        {% for service in service_list %}
                            <li>
                                <div class="radio">
                                    <label>
                                        <input class="radio-input" type="radio" name="serv_type_service" value="{{ service.id }}" {% if service.id == REQUEST.serv_type_service %}checked{% endif %}>
                                        <span class="radio-custom"></span>
                                        <span class="label">{{ service.title|escape|stripslashes }}</span>
                                    </label>
                                </div>
                            </li>
                        {% endfor %}
                    </ul>
                </div>

                <div class="row type-translate load_communication {% if 49 in REQUEST.serv_type_service or 56 in REQUEST.serv_type_service %}{% else %}hidden{% endif %}">
                    <div class="col-md-3">
                        <p><strong>{{ lang.lk_communication }}</strong></p>
                    </div>

                    {% for communication in communication_list %}
                        <div class="col-md-3">
                            <label>
                                <input class="checkbox-input" type="checkbox" name="serv_communication[]" value="{{ communication.id }}" {% if communication.id in REQUEST.serv_communication %}checked{% endif %}>
                                <span class="checkbox-custom"></span>
                                <span class="label">{{ communication.title|escape|stripslashes }}</span>
                            </label>
                        </div>
                    {% endfor %}
                </div>

                <div class="row type-translate load_place {% if 47 in REQUEST.serv_type_service or 48 in REQUEST.serv_type_service %}{% else %}hidden{% endif %}">
                    <div class="col-md-3">
                        <p><strong>{{ lang.lk_place }}</strong></p>
                    </div>

                    {% for place in place_list %}
                        <div class="col-md-3">
                            <label>
                                <input class="checkbox-input" type="checkbox" name="serv_place[]" value="{{ place.id }}" {% if place.id in REQUEST.serv_place %}checked{% endif %}>
                                <span class="checkbox-custom"></span>
                                <span class="label">{{ place.title|escape|stripslashes }}</span>
                            </label>
                        </div>
                    {% endfor %}
                </div>
            </div>


            <div class="col-md-4">
                <div class="type-translate {% if REQUEST.user_type == 1 %}{% else %}hidden{% endif %}">
                    <select class="form-control selectpicker" name="serv_theme">
                        <option value="">{{ lang.lk_info_theme }}</option>
                        {% for theme in theme_list %}
                            <option value="{{ theme.id }}" {% if theme.id == REQUEST.serv_theme %}selected{% endif %}>{{ theme.title|escape|stripslashes }}</option>
                        {% endfor %}
                    </select>
                </div>
            </div>

            <div class="col-md-2">
                <input type="text" class="form-control lk_graph" placeholder="{{ lang.search_form_date_search }}">
                <input type="hidden" name="graph_start" value="">
                <input type="hidden" name="graph_end" value="">
            </div>

            <div class="col-md-6">
                {% if 3 != REQUEST.user_type %}
                    <div class="row cash">
                        <div class="col-md-12 budget">
    
                            <div class="col-md-3 left-pad-0">
                                <div class="form-group">
                                    <span class="diapazon-min-max">{{ lang.lk_budget_from }} </span>
                                    <input type="text" class="diapazon" placeholder="100" name="budget_start" value="{{ REQUEST.budget_start|escape|stripslashes }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <span class="diapazon-min-max">{{ lang.lk_budget_to }} </span>
                                    <input type="text" class="diapazon" placeholder="100" name="budget_end" value="{{ REQUEST.budget_end|escape|stripslashes }}">
                                </div>
                            </div>
                            <div class="col-md-3 left-pad-0">
                                <div class="form-group">
                                    <select class="form-control" name="budget_currency">
                                        {% for currency in currency_list %}
                                            <option value="{{ currency.id }}" {% if currency.id == REQUEST.budget_currency %}selected{% endif %}>{{ currency.title|escape|stripslashes }}</option>
                                        {% endfor %}
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3 left-pad-0">
                                <div class="form-group">
                                    <select class="form-control" name="budget_time">
                                        {% for time in time_list %}
                                            {% if time.id != 70 and time.id != 71 %}
                                                <option value="{{ time.id }}" {% if time.id == REQUEST.budget_time %}selected{% endif %}>{{ time.title|escape|stripslashes }}</option>
                                            {% endif %}
                                        {% endfor %}
                                    </select>
                                </div>
                            </div>
                                
                        </div>
                    </div>
                {% endif %}
            </div>
        </div>
    </div>
    <hr>
    {% endif %}