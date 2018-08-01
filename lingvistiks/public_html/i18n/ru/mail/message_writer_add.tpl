<p>Вам поступил заказ №{{ document_info.document_id }}</p>
<p><strong>Перевод: с </strong> {{ document_info.document_from_lang.title|escape|stripslashes }} <strong> на </strong>{{ document_info.document_to_lang.title|escape|stripslashes }}</p>
Тематика документа: {{ document_info.document_theme.title|escape|stripslashes }}
Дата сдачи: {{ document_info.document_date|escape|stripslashes }}
Заверение: {{ lang.search_writer_document_verif_array[document_info.document_verif] }}

{% if document_info.document_hidden != 1 %}
	{% if document_info.document_file %}
		<p>
			<strong>{{ lang.search_writer_file }}</strong>
			<ul class="list-unstyled">
				{% for message_file in document_info.document_file %}
					<li><a href="{{ HOST_NAME }}/writer-document/{{ document_info.document_id }}/{{ message_file|escape|stripslashes }}" target="_blank">{{ message_file|escape|stripslashes }}</a></li>
				{% endfor %}
			</ul>
		</p>
	{% endif %}
	{% if document_info.document_file_link %}
		<p>
			<strong>{{ lang.search_writer_form_file_link }}</strong>&nbsp<a href="{{ document_info.document_file_link|escape|stripslashes }}" target="_blank">{{ document_info.document_file_link|escape|stripslashes }}</a>
		</p>
	{% endif %}
{% endif %}

Комментарий: {{ writer_comment|escape|stripslashes }}
