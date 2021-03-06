<?php
$lang = [
    'billing_name' => 'Мои платежи и тарифы',

    'billing_price_free' => 'Бесплатно',

    'billing_my_tariff' => 'Мой тариф',
    'billing_my_tariff_none' => 'Подписка не активирована',
    'billing_my_balance' => 'Мой баланс',
    'billing_tariff_change' => 'Выбор тарифа',

    'billing_history_empty' => 'История платежей отсутствует',
    'billing_history_sber' => 'Банковская карта, счёт BC',
    'billing_history_paypal' => 'PayPal, счёт PP',
    'billing_history_bank' => 'Банковский перевод, счёт BT',

    'billing_pay_type' => [
        1 => 'Карточка',
        2 => 'PayPal',
        3 => 'Банковский перевод',
    ],

    'billing_tariff_access' => [
        3 => [
            'lk_categ' => 'Категории услуг в личном профиле',
            'translate' => 'Возможности для переводчиков',
            'prepod' => 'Возможности для преподавателей',
            'gid' => 'Возможности для гидов и экскурсоводов',
            'owner' => 'Доступ к профилю заказчика',
            'order' => 'Доступ к блоку «Банк заказов»',
            'message' => 'Доступ к блоку «Мои сообщения»',
            'vakant' => 'Доступ к блоку «Банк вакансий»',
            'resume' => 'Доступ к блоку «Банк резюме»',
        ],

        4 => [
            'search_perfomens' => 'Поиск исполнителей',
            'profile_perfomens' => 'Доступ к профилю исполнителя',
            'service' => 'Размещение заказа на услугу',
            'order' => 'Доступ к блоку «Банк заказов»',
            'message' => 'Доступ к блоку «Мои сообщения»',
            'vakant' => 'Доступ к блоку «Банк вакансий»',
            'resume' => 'Доступ к блоку «Банк резюме»',
        ],
    ],

    'billing_tariff_prop' => [
        3 => [
            1 => [
                1 => [
                    'lk_categ' => 'Не более<br><span>1</span> категории услуг',
                    'translate' => 'Не более<br><span>1</span> языковой пары',
                    'prepod' => 'Не более<br><span>1</span> языка преподавания',
                    'gid' => 'Не более<br><span>1</span> языка экскурсий<br> Не более <span>1</span> пары страна/город',
                    'owner' => 'Доступ <span>без ограничений</span>',
                    'order' => 'Доступ согласно выбранной категории услуг',
                    'message' => 'Доступ <span>без ограничений</span>',
                    'vakant' => 'Доступ <span>без ограничений</span>',
                    'resume' => 'Нет',
                ],
                2 => [
                    'lk_categ' => 'Не более <span>2</span> категорий услуг', 
                    'translate' => 'Не более <span>3</span> языковых пар', 
                    'prepod' => 'Не более <span>3</span> языков преподавания', 
                    'gid' => 'Не более <span>3</span> языков экскурсий<br> Не более <span>3</span> пар страна/город', 
                    'owner' => 'Доступ <span>без ограничений</span>', 
                    'order' => 'Доступ согласно выбранным категориям услуг',
                    'message' => 'Доступ <span>без ограничений</span>', 
                    'vakant' => 'Доступ <span>без ограничений</span>', 
                    'resume' => 'Доступ с ограничением (не более 3 одновременно размещаемых резюме)',
                ],
                3 => [
                    'lk_categ' => 'Доступ <span>без ограничений</span>', 
                    'translate' => 'Не более <span>5</span> языковых пар', 
                    'prepod' => 'Не более <span>5</span> языков преподавания', 
                    'gid' => 'Не более <span>5</span> языков экскурсий<br> Не более <span>5</span> пар страна/город', 
                    'owner' => 'Доступ <span>без ограничений</span>', 
                    'order' => 'Доступ <span>без ограничений</span>',
                    'message' => 'Доступ <span>без ограничений</span>', 
                    'vakant' => 'Доступ <span>без ограничений</span>', 
                    'resume' => 'Доступ <span>без ограничений</span>', 
                ],
            ],
            2 => [
                1 => [
                    'lk_categ' => 'Не более<br><span>1</span> категории услуг',
                    'translate' => 'Не более<br><span>5</span> языковых пар',
                    'prepod' => 'Не более<br><span>5</span> языков преподавания',
                    'gid' => 'Не более<br><span>5</span> языков экскурсий<br> Не более <span>5</span> пар страна/город',
                    'owner' => 'Доступ <span>без ограничений</span>',
                    'order' => 'Доступ согласно выбранной категории услуг',
                    'message' => 'Доступ <span>без ограничений</span>',
                    'vakant' => 'Доступ <span>без ограничений</span>',
                    'resume' => 'Нет',
                ],
                2 => [
                    'lk_categ' => 'Не более <span>2</span> категорий услуг', 
                    'translate' => 'Не более <span>10</span> языковых пар', 
                    'prepod' => 'Не более <span>10</span> языков преподавания', 
                    'gid' => 'Не более <span>10</span> языков экскурсий<br> Не более <span>10</span> пар страна/город', 
                    'owner' => 'Доступ <span>без ограничений</span>', 
                    'order' => 'Доступ согласно выбранным категориям услуг',
                    'message' => 'Доступ <span>без ограничений</span>', 
                    'vakant' => 'Доступ <span>без ограничений</span>', 
                    'resume' => 'Доступ с ограничением (не более <span>3</span> одновременно размещаемых резюме)',
                ],
                3 => [
                    'lk_categ' => 'Доступ <span>без ограничений</span>', 
                    'translate' => 'Доступ <span>без ограничений</span>', 
                    'prepod' => 'Доступ <span>без ограничений</span>', 
                    'gid' => 'Доступ <span>без ограничений</span>', 
                    'owner' => 'Доступ <span>без ограничений</span>', 
                    'order' => 'Доступ <span>без ограничений</span>',
                    'message' => 'Доступ <span>без ограничений</span>', 
                    'vakant' => 'Доступ <span>без ограничений</span>', 
                    'resume' => 'Доступ <span>без ограничений</span>', 
                ],
            ],
        ],

        4 => [
            1 => [
                1 => [
                    'search_perfomens' => 'Доступ <span>без ограничений</span>',
                    'profile_perfomens' => 'Доступ <span>без ограничений</span>',
                    'service' => 'Да, не более <span>2</span> заказов в месяц (бесплатно)',
                    'order' => 'Да, получение откликов от исполнителей',
                    'message' => 'Да, с ограничением, не может написать первым',
                    'vakant' => 'Нет',
                    'resume' => 'Нет',
                ],
                2 => [
                    'search_perfomens' => 'Доступ <span>без ограничений</span>',
                    'profile_perfomens' => 'Доступ <span>без ограничений</span>',
                    'service' => 'Доступ с ограничением (не более <span>3</span> одновременно размещаемых заказов)',
                    'order' => 'Да, получение откликов от исполнителей',
                    'message' => 'Доступ <span>без ограничений</span>',
                    'vakant' => 'Доступ с ограничением (не более 1 одновременно размещаемой вакансии)',
                    'resume' => 'Нет',
                ],
                3 => [
                    'search_perfomens' => 'Доступ <span>без ограничений</span>',
                    'profile_perfomens' => 'Доступ <span>без ограничений</span>',
                    'service' => 'Доступ <span>без ограничений</span>',
                    'order' => 'Да, получение откликов от исполнителей',
                    'message' => 'Доступ <span>без ограничений</span>',
                    'vakant' => 'Доступ <span>без ограничений</span>',
                    'resume' => 'Доступ <span>без ограничений</span>',
                ],
            ],
            2 => [
                1 => [
                    'search_perfomens' => 'Доступ <span>без ограничений</span>',
                    'profile_perfomens' => 'Доступ <span>без ограничений</span>',
                    'service' => 'Да, не более <span>2</span> заказов в месяц (бесплатно)',
                    'order' => 'Да, получение откликов от исполнителей',
                    'message' => 'Да, с ограничением, не может написать первым',
                    'vakant' => 'Нет',
                    'resume' => 'Нет',
                ],
                2 => [
                    'search_perfomens' => 'Доступ <span>без ограничений</span>',
                    'profile_perfomens' => 'Доступ <span>без ограничений</span>',
                    'service' => 'Доступ с ограничением (не более <span>3</span> одновременно размещаемых заказов)',
                    'order' => 'Да, получение откликов от исполнителей',
                    'message' => 'Доступ <span>без ограничений</span>',
                    'vakant' => 'Доступ с ограничением (не более 1 одновременно размещаемой вакансии)',
                    'resume' => 'Нет',
                ],
                3 => [
                    'search_perfomens' => 'Доступ <span>без ограничений</span>',
                    'profile_perfomens' => 'Доступ <span>без ограничений</span>',
                    'service' => 'Доступ <span>без ограничений</span>',
                    'order' => 'Да, получение откликов от исполнителей',
                    'message' => 'Доступ <span>без ограничений</span>',
                    'vakant' => 'Доступ <span>без ограничений</span>',
                    'resume' => 'Доступ <span>без ограничений</span>',
                ],
            ],
        ],
    ],

    'billing_tariff_change' => 'Выбрать период подписки',
    'billing_tariff_change_month' => 'месяц',
    'billing_tariff_change_month_disc' => 'экономия',
    'billing_tariff_change_month_pay' => 'к оплате',
    'billing_change_pay_btn' => 'Выбрать и оплатить',
    'billing_change_pay_cp_btn' => 'Сменить тариф',


    'billing_change_pay' => 'Способ оплаты',
    'billing_change_pay_card' => 'Банковская карта',
    'billing_change_pay_paypal' => 'PayPal',
    'billing_change_pay_bank' => 'Банковский перевод',
    'billing_change_pay_tariff' => 'Тариф',
    'billing_change_pay_sum' => 'Сумма к оплате',
    'billing_change_pay_company_name' => 'Полное название организации',
    'billing_change_pay_company_inn' => 'ИНН',
    'billing_change_pay_company_kpp' => 'КПП',
    'billing_change_pay_company_address' => 'Юридический адрес',
    'billing_change_pay_company_address_post' => 'Почтовый адрес',
    'billing_change_pay_company_phone' => 'Телефон',

    'billing_change_pay_btn_1' => 'Оплатить',
    'billing_change_pay_btn_3' => 'Сформировать счет',

    'billing_pay_tariff_error' => 'Тариф выбран неверно',
    'billing_pay_sum_error' => 'Сумма к оплате указана неверно',
    'billing_pay_card_number_error' => 'Номер карты указан неверно',
    'billing_pay_card_owner_error' => 'Владелец указан неверно',
    'billing_pay_card_exp_error' => 'Срок действия указан неверно',
    'billing_pay_cvc_error' => 'CVV2/CVC2 указан неверно',

    'billing_pay_company_name_error' => 'Полное название организации указано неверно',
    'billing_pay_company_inn_error' => 'ИНН указан неверно',
    'billing_pay_company_kpp_error' => 'КПП указан неверно',
    'billing_pay_company_address_error' => 'Юридический адрес указан неверно',


    'pay_card_number_placeholder' => '0000',
    'pay_card_owner_placeholder' => 'Иван Петров',
    'pay_card_exp_placeholder' => '00/00',
    'pay_cvc_placeholder' => '***',


    'billing_activate' => 'Период подписки до',
    'billing_make_pay' => 'Сделать оплату',


    'billing_card_add' => 'Указать новую банковскую карту',


    'billing_card_edit' => 'Редактировать карту',
    'billing_card_number' => 'Номер карты',
    'billing_card_owner' => 'Имя и Фамилия на карте',
    'billing_card_date' => 'Действует',

    'card_number_error' => 'Номер карты указан неверно',
    'card_owner_name_error' => 'Имя и Фамилия на карте короче '.config::app('app_min_strlen').'-х символов',
    'card_date_error' => 'Действует до указано неверно',
    'card_copy_error' => 'Такая карта уже была добавлена',

    
    'billing_change_sum' => 'Сумма',
    'billing_change_card' => 'Выберите банковскую карту',
    'billing_change_card_title' => 'Банковская карта',

    'billing_history' => 'История платежей',
    'billing_tbl_date' => 'Дата',
    'billing_tbl_sum' => 'Сумма',
    'billing_tbl_id' => 'Номер транзакции',
    'billing_tbl_desc' => 'Детали операции',

    'billing_pay_log' => 'Списание по тарифному плану',

    'billing_sum_error' => 'Сумма платежа указана неверно',
    'billing_card_error' => 'Карта указана неверно',
    'billing_currency_error' => 'Валюта платежа неверно',

    'billing_convert' => 'Конвертор валют',
    'billing_convert_from' => 'Отдаем',
    'billing_convert_to' => 'Получаем',
    'billing_convert_from_many' => 'Сколько нужно поменять',
    'billing_convert_to_many' => 'Сколько нужно получить',
    'billing_convert_any' => 'или',
    'billing_convert_btn' => 'Перевести',
    'billing_convert_info' => 'Конвертор несёт информативную задачу. При оплате, суммы могут варьироваться в	зависимости от внутренних курсов Ваших банков-эмитентов',



    'billing_pdf_bank' => 'ПАО СБЕРБАНК Г. МОСКВА',
    'billing_pdf_bank_desc' => 'Банк получателя',
    'billing_pdf_bik' => 'БИК',
    'billing_pdf_rs' => 'Сч. №',
    'billing_pdf_inn' => 'ИНН',
    'billing_pdf_kpp' => 'КПП',
    'billing_pdf_company' => 'ООО "СМАРТ ЛИНГВИСТИКС"',
    'billing_pdf_company_desc' => 'Получатель',
    'billing_pdf_order' => 'Счет на оплату №',
    'billing_pdf_order_from' => 'от',
    'billing_pdf_order_to' => 'Исполнитель: Общество с ограниченной ответственностью "СМАРТ ЛИНГВИСТИКС" 115088 Москва г, Симоновский Вал ул, д. 17, корп. 1, кв. (оф.) 17.',
    'billing_pdf_order_payer' => 'Заказчик:',
    'billing_pdf_order_desc' => 'Основание',
    'billing_pdf_order_title' => 'Товары (работы, услуги)',
    'billing_pdf_order_count' => 'Кол-во',
    'billing_pdf_order_sum' => 'Ед.',
    'billing_pdf_order_price' => 'Цена',
    'billing_pdf_order_price_total' => 'Сумма',
    'billing_pdf_order_title_1' => 'Оплата доступа к платформе Maxxon24 согласно тарифу',
    'billing_pdf_order_type' => 'услуга',
    'billing_pdf_order_total' => 'Итого',
    'billing_pdf_order_nds' => 'В том числе НДС',
    'billing_pdf_order_total_and_nds' => 'Всего к оплате',
    'billing_pdf_order_nds_none' => 'Без НДС',
    'billing_pdf_order_count_num' => 'Всего наименований одно,',
    'billing_pdf_order_warning' => 'Внимание! <br>    
    Оплата данного счета означает согласие с условиями поставки товара.<br> 
    Уведомление об оплате обязательно, в противном случае не гарантируется наличие товара на складе. <br>    
    Товар отпускается по факту прихода денег на р/с Поставщика, самовывозом, при наличии доверенности и паспорта. <br>
    ',
    'billing_pdf_company_owner' => 'Руководитель',
    'billing_pdf_order_owmer_pay' => 'Бухгалтер',

    'billing_tariff_type_1' => 'Для физических лиц',
    'billing_tariff_type_2' => 'Для юридических лиц',
    'billing_tariff_group_1' => 'Заказчик',
    'billing_tariff_group_2' => 'Исполнитель',

    'billing_rules_link' => 'Описание порядка и <a href="http://maxxon24.ru/info/popolnenie-balansa.html" target="_blank">Условия пополнения баланса учётной записи</a>',

    'billing_pdf_dic_num' => [
        -2	=> 'две',
        -1	=> 'одна',
        1	=> 'один',
        2	=> 'два',
        3	=> 'три',
        4	=> 'четыре',
        5	=> 'пять',
        6	=> 'шесть',
        7	=> 'семь',
        8	=> 'восемь',
        9	=> 'девять',
        10	=> 'десять',
        11	=> 'одиннадцать',
        12	=> 'двенадцать',
        13	=> 'тринадцать',
        14	=> 'четырнадцать' ,
        15	=> 'пятнадцать',
        16	=> 'шестнадцать',
        17	=> 'семнадцать',
        18	=> 'восемнадцать',
        19	=> 'девятнадцать',
        20	=> 'двадцать',
        30	=> 'тридцать',
        40	=> 'сорок',
        50	=> 'пятьдесят',
        60	=> 'шестьдесят',
        70	=> 'семьдесят',
        80	=> 'восемьдесят',
        90	=> 'девяносто',
        100	=> 'сто',
        200	=> 'двести',
        300	=> 'триста',
        400	=> 'четыреста',
        500	=> 'пятьсот',
        600	=> 'шестьсот',
        700	=> 'семьсот',
        800	=> 'восемьсот',
        900	=> 'девятьсот'
    ],

    'billing_pdf_dic_num_ext' => array(
        array('', '', ''),
        array('тысяча', 'тысячи', 'тысяч'),
        array('миллион', 'миллиона', 'миллионов'),
        array('миллиард', 'миллиарда', 'миллиардов'),
        array('триллион', 'триллиона', 'триллионов'),
        array('квадриллион', 'квадриллиона', 'квадриллионов'),
        // квинтиллион, секстиллион и т.д.
    ),
];
?>