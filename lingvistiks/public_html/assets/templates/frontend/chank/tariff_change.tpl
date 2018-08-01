<div class="col-md-12">
    <h3>{{ lang.billing_change_pay }}</h3>
</div>

<div class="col-md-12">

    <ul class="list-inline text-center billing_pay_type">
        <li data-type="1" class="active">
            <img src="{{ ABS_PATH }}assets/site/template/images/card-1.png" alt=""><br>
            {{ lang.billing_change_pay_card }}
        </li>
        <li data-type="2">
            <img src="{{ ABS_PATH }}assets/site/template/images/paypal-1.png" alt=""><br>
            {{ lang.billing_change_pay_paypal }}
        </li>
        <li data-type="3">
            <img src="{{ ABS_PATH }}assets/site/template/images/bank-1.png" alt=""><br>
            {{ lang.billing_change_pay_bank }}
        </li>
    </ul>

    <form action="{{ HOST_NAME }}/billing/pay/" method="POST" class="change_form_pay ajax_form">
        <input type="hidden" name="csrf_token" value="{{ csrf_token }}">
        <input type="hidden" name="change_pay_type" value="1">
        <input type="hidden" name="change_tariff" value="{{ REQUEST.change_tariff|escape|stripslashes }}">
        <input type="hidden" name="change_price" value="{{ REQUEST.change_price|escape|stripslashes }}">
        <input type="hidden" name="change_type" value="{{ REQUEST.change_type|escape|stripslashes }}">

        
        <div class="show_pay_type show_pay_type_1 text-center">

            <h2>{{ lang.billing_change_pay_tariff }} <span>{{ tariff.name }} ({{ tariff_price.title }})</span></h2>

            <div class="form-group">
                <div class="row">
                    <div class="col-md-offset-3 col-md-3 text-right">
                        <label for="">{{ lang.billing_change_pay_sum }}</label>
                    </div>
                    <div class="col-md-3">
                        <div class="input-group">
                            <input type="text" class="form-control" name="pay_sum_1" value="{{ tariff_price.price }}" readonly>
                            <div class="input-group-addon">{{ app.site_currency }}</div>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="text-center">
                <div class="row">
                    <div class="col-md-offset-3 col-md-6">
                        <div class="form-group">
                            <img src="{{ ABS_PATH }}assets/site/template/images/sber_py_btn.png" alt="">
                        </div>

                        <div class="form-group">
                            {{ lang.billing_rules_link }}
                        </div>
                        
                        <div class="form-group">
                            <button type="submit" class="btn btn-search btn-block">{{ lang.billing_change_pay_btn_1 }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="hidden show_pay_type show_pay_type_2 text-center">
            <h2>{{ lang.billing_change_pay_tariff }} <span>{{ tariff.name }} ({{ tariff_price.title }})</span></h2>

            <div class="form-group">
                <div class="row">
                    <div class="col-md-offset-3 col-md-3 text-right">
                        <label for="">{{ lang.billing_change_pay_sum }}</label>
                    </div>
                    <div class="col-md-3">
                        <div class="input-group">
                            <input type="text" class="form-control" name="pay_sum_2" value="{{ tariff_price.price }}" readonly>
                            <div class="input-group-addon">{{ app.site_currency }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <button type="submit">
                    <img src="{{ ABS_PATH }}assets/site/template/images/paypal_pay.png" alt="">
                </button>
            </div>
        </div>

        <div class="hidden show_pay_type show_pay_type_3 text-center">
            
            <h2>{{ lang.billing_change_pay_tariff }} <span>{{ tariff.name }} ({{ tariff_price.title }})</span></h2>

            <div class="form-group">
                <div class="row">

                    <div class="col-md-4 text-right">
                        {{ lang.billing_change_pay_company_name }}
                    </div>
                    <div class="col-md-8">
                        <input type="text" class="form-control" name="pay_company_name" value="">
                    </div>

                </div>
                <div class="row">

                    <div class="col-md-4 text-right">
                        {{ lang.billing_change_pay_company_inn }}
                    </div>
                    <div class="col-md-8">
                        <div class="col-md-5">
                            <input type="text" class="form-control" name="pay_company_inn" value="">
                        </div>
                        <div class="col-md-2 text-right">
                            {{ lang.billing_change_pay_company_kpp }}
                        </div>
                        <div class="col-md-5">
                            <input type="text" class="form-control" name="pay_company_kpp" value="">
                        </div>
                    </div>
                    

                </div>
                <div class="row">

                    <div class="col-md-4 text-right">
                        {{ lang.billing_change_pay_company_address }}
                    </div>
                    <div class="col-md-8">
                        <input type="text" class="form-control" name="pay_company_address" value="">
                    </div>

                </div>
                <div class="row">

                    <div class="col-md-4 text-right">
                        {{ lang.billing_change_pay_company_address_post }}
                    </div>
                    <div class="col-md-8">
                        <input type="text" class="form-control" name="pay_company_address_post" value="">
                    </div>

                </div>
                <div class="row">

                    <div class="col-md-4 text-right">
                        {{ lang.billing_change_pay_company_phone }}
                    </div>
                    <div class="col-md-8">
                        <input type="text" class="form-control" name="pay_company_phone" value="">
                    </div>

                </div>
                <div class="row">
                    <div class="col-md-6 text-right">
                        <label for="">{{ lang.billing_change_pay_sum }}</label>
                    </div>
                    <div class="col-md-2">
                        <div class="input-group">
                            <input type="text" class="form-control" name="pay_sum_3" value="{{ tariff_price.price }}" readonly>
                            <div class="input-group-addon">{{ app.site_currency }}</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-search btn-block">{{ lang.billing_change_pay_btn_3 }}</button>
                    </div>

                </div>
            </div>

        </div>
    </form>
    
</div>

<script>
    $(document).ready(function(){
        $(".billing_pay_type li").on('click',function() {
            var this_type = $(this).attr("data-type");
            $(".billing_pay_type li").removeClass("active");
            $(this).addClass("active");
            $(".change_form_pay input[name=change_pay_type]").val(this_type);

            $(".change_form_pay .show_pay_type").addClass("hidden");
            $(".change_form_pay .show_pay_type_"+this_type).removeClass("hidden");
        });

        $('input[name=pay_card_exp]').mask('##/##', {
            byPassKeys: '',
            translation: {
                '#': {pattern: /[0-9]/}
            }
        });
        $('input[name=pay_cvc]').mask('###', {
            byPassKeys: '',
            translation: {
                '#': {pattern: /[0-9]/}
            }
        });
        $('input.pay_card_number').mask('####', {
            byPassKeys: '',
            translation: {
                '#': {pattern: /[0-9]/}
            }
        });

        $(".pay_card_number").on('keyup',function() {
            var section = parseInt($(this).attr("data-section"));
            if(($(this).val()).length == 4 && section != 4) {
                $(".pay_card_number[data-section="+(section+1)+"]").focus();
            } else {
                return false;
            }
        });

    });
</script>