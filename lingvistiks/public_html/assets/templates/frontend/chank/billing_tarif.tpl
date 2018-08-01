<div class="row change-cart">
    <div class="col-md-6">
        <h5>{{ lang.billing_my_tariff }}

            <small>
                {% if profile_info.user_billing %}
                    {{ app.app_tariff[SESSION.user_group][SESSION.user_subgroup][profile_info.user_billing].name }}
                {% else %}
                    {{ lang.billing_my_tariff_none }}
                {% endif %}
            </small>
        </h5>
    </div>
    <div class="col-md-6 text-right">
        <h5>
            {{ lang.billing_my_balance }} 
            
            <small>
                {{ profile_info.user_balance|format_number }} {{ app.site_currency }}
        
                {% if profile_info.user_billing_date and profile_info.user_balance %}
                    ({{ profile_info.user_billing_date|escape|stripslashes }})
                {% endif %}
            </small>
        </h5>
    </div>

    <div class="clearfix"></div>
    
    {{ tariff_block }}
</div>