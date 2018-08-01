<table>
    <tr>
        <td rowspan="2" colspan="2" style="width:310px;">
            {{ lang.billing_pdf_bank }}
            <br>
            {{ lang.billing_pdf_bank_desc }}
        </td>
        <td style="width:70px">
            {{ lang.billing_pdf_bik }}
        </td>
        <td style="width:250px;">
            44525225 
        </td>
    </tr>
    <tr>
        <td style="width:70px;">
            {{ lang.billing_pdf_rs }}
        </td>
        <td style="width:310px;">
            30101810400000000225  
        </td>
    </tr>

    <tr>
        <td colspan="4"></td>
    </tr>

    <tr>
        <td style="width:150px;">
            {{lang.billing_pdf_inn }} 9701020562
        </td>
        <td style="width:150px;">
            {{ lang.billing_pdf_kpp }} 772301001
        </td>
        <td rowspan="2" style="width:70px;">
            {{ lang.billing_pdf_rs }}
        </td>
        <td rowspan="2" style="width:310px;">
            40702810738000000000
        </td>
    </tr>

    <tr>
        <td colspan="4"></td>
    </tr>

    <tr>
        <td colspan="2" style="width:270px;">
            {{ lang.billing_pdf_company }}
            <br>
            {{ lang.billing_pdf_company_desc }}
        </td>
    </tr>
</table>

<h1>{{ lang.billing_pdf_order }}{{ history_id+1 }} {{ lang.billing_pdf_order_from }} {{ date }}</h1>

<p>{{ lang.billing_pdf_order_to }}</p>
<p>{{ lang.billing_pdf_order_payer }} {{ REQUEST.pay_company_name|escape|stripslashes }} {{lang.billing_pdf_inn }} {{ REQUEST.pay_company_inn|escape|stripslashes }} {{ lang.billing_pdf_kpp }} {{ REQUEST.pay_company_kpp|escape|stripslashes }} {{ REQUEST.pay_company_address|escape|stripslashes }} {{ REQUEST.pay_company_phone|escape|stripslashes }}</p>

<p>{{ lang.billing_pdf_order_desc }}</p>
<table>
    <tr>
        <td style="width:50px;">
            №
        </td>
        <td style="width:250px;">
            {{ lang.billing_pdf_order_title }}
        </td>
        <td style="width:70px;">
            {{ lang.billing_pdf_order_count }} 
        </td>
        <td style="width:70px;">
            {{ lang.billing_pdf_order_sum }}
        </td>
        <td style="width:90px;">
            {{ lang.billing_pdf_order_price }}, {{ app.site_currency }}
        </td>
        <td style="width:100px;">
            {{ lang.billing_pdf_order_price_total }}, {{ app.site_currency }} 
        </td>
    </tr>
    <tr>
        <td style="width:50px;">
            1
        </td>
        <td style="width:250px;">
            {{lang.billing_pdf_order_title_1 }}<br>
            {{ tariff.name }} ({{ tariff_price.title }})
        </td>
        <td style="width:70px;">
            1
        </td>
        <td style="width:70px;">
            {{ lang.billing_pdf_order_type }}
        </td>
        <td style="width:90px;">
            {{ tariff_price.price }}
        </td>
        <td style="width:100px;">
            {{ tariff_price.price }}
        </td>
    </tr>
</table>

<p style="text-align:right">{{ lang.billing_pdf_order_total }} {{ tariff_price.price }} {{ app.site_currency }}</p>
<p style="text-align:right">{{ lang.billing_pdf_order_nds }} {{ lang.billing_pdf_order_nds_none }}</p>
<p style="text-align:right">{{ lang.billing_pdf_order_total_and_nds }} {{ tariff_price.price }} {{ app.site_currency }}</p>

<p>{{ lang.billing_pdf_order_count_num }}<br> {{ tariff_price.price|convert_int_to_str }} {{ app.site_currency }}</p>

<p>{{ lang.billing_pdf_order_warning }}</p>

<p>{{ lang.billing_pdf_company_owner }}_______________________________</p>
<p>{{ lang.billing_pdf_order_owmer_pay }}_______________________________</p>

<style>
table, th, td
{
  border-collapse:collapse;
  border: 1px solid black;
  text-align:left;
}
th, td {
    width:auto;
}
</style>