<?php

namespace App\Helpers;

use phpDocumentor\Reflection\Types\Integer;

trait AnketaHelper
{

    function stub()
    {
        return
            '{"anketa":{
            "amount":"490",
            "question":[
                
                {"type":"text",
                "label":"Привет! Как вас зовут?",
                "answer":"Екатерина (это stub)"},
                
                {"type":"choice",
                "label":"Какой стиль вы предпочитаете в выходные?",
                "answer":1,
                "option":[
                    {"text":"Business casual<br><span class=\"hint\">(пиджаки, рубашки, брюки)</span>"},
                    {"text":"Casual<br><span class=\"hint\">(джинсы, топы, уличный стиль)</span>"},
                    {"text":"Спортивный стиль<br><span class=\"hint\">(удобство превыше всего)</span>"}]},
                
                {"type":"choice",
                "label":"Как вы одеваетесь на работу?",
                "answer":1,
                "option":[
                    {"text":"Строгий дресс-код"},{"text":"Нестрогий дресс-код"},
                    {"text":"Casual"}]},
                
                {"type":"choice",
                "label":"Вы хотите, чтобы стилист подобрал что-то отличное от того, к чему вы привыкли, или сохранил ваш стиль?",
                "answer":1,
                "option":[
                    {"text":"Хочу абсолютно новый стиль. Готова к экспериментам"},
                    {"text":"50/50 - действуем аккуратно"},
                    {"text":"Хочу, чтобы мой стиль остался неизменным"}]},
                
                {"type":"check",
                "image":"img/anketa/question_4.png",
                "label":"Расскажите нам, в каком стиле вы предпочитаете носить одежду.<br><br>Что вы думаете про этот образ?",
                "answer":[0],
                "option":[
                    {"text":"Нравится весь образ","only_one":true},
                    {"text":"Нравится пиджак"},
                    {"text":"Нравится футболка"},
                    {"text":"Нравятся джинсы"},
                    {"text":"Нравится сумка"},
                    {"text":"Образ не нравится","only_one":true}]},
                
                {"type":"check",
                "image":"img/anketa/question_5.png",
                "label":"Что вы думаете про этот образ?",
                "answer":[0],
                "option":[
                    {"text":"Нравится весь образ","only_one":true},
                    {"text":"Нравится рубашка"},
                    {"text":"Нравится топ"},
                    {"text":"Нравится сумка"},
                    {"text":"Нравятся брюки"},
                    {"text":"Образ не нравится","only_one":true}]},
                
                {"type":"check",
                "image":"img/anketa/question_6.png",
                "label":"Что вы думаете про этот образ?",
                "answer":[0],
                "option":[
                    {"text":"Нравится весь образ","only_one":true},
                    {"text":"Нравится свитер"},
                    {"text":"Нравятся джинсы"},
                    {"text":"Нравится сумка"},
                    {"text":"Образ не нравится","only_one":true}]},
                
                {"type":"check",
                "image":"img/anketa/question_7.png",
                "label":"Что вы думаете про этот образ?",
                "answer":[1],
                "option":[
                    {"text":"Нравится весь образ","only_one":true},
                    {"text":"Нравится джинсовая рубашка"},
                    {"text":"Нравится платье"},
                    {"text":"Нравится сумка"},
                    {"text":"Образ не нравится","only_one":true}]},
                
                {"type":"check",
                "image":"img/anketa/question_8.png",
                "label":"Что вы думаете про этот образ?",
                "answer":[0],
                "option":[
                    {"text":"Нравится весь образ","only_one":true},
                    {"text":"Нравится кардиган"},
                    {"text":"Нравится юбка"},
                    {"text":"Нравится сумка"},
                    {"text":"Образ не нравится","only_one":true}]},
        
                {"type":"check","image":"img/anketa/question_9.png",
                "label":"Что вы думаете про этот образ?",
                "answer":[0],
                "option":[
                    {"text":"Нравится весь образ","only_one":true},
                    {"text":"Нравится пиджак"},
                    {"text":"Нравится топ"},
                    {"text":"Нравится сумка"},
                    {"text":"Нравятся брюки"},
                    {"text":"Образ не нравится","only_one":true}]},
        
                {"type":"check",
                "image":"img/anketa/question_10.png",
                "label":"Что вы думаете про этот образ?",
                "answer":[0],
                "option":[
                    {"text":"Нравится весь образ","only_one":true},
                    {"text":"Нравится рубашка"},
                    {"text":"Нравятся брюки"},
                    {"text":"Нравится сумка"},
                    {"text":"Образ не нравится","only_one":true}]},
        
                {"type":"check",
                "image":"img/anketa/question_11.png",
                "label":"Что вы думаете про этот образ?",
                "answer":[0],
                "option":[
                    {"text":"Нравится весь образ","only_one":true},
                    {"text":"Нравится пиджак"},
                    {"text":"Нравится топ"},
                    {"text":"Нравятся брюки"},
                    {"text":"Нравится сумка"},
                    {"text":"Образ не нравится","only_one":true}]},
        
                {"type":"check",
                "image":"img/anketa/question_12.png",
                "label":"Что вы думаете про этот образ?",
                "answer":[0],
                "option":[
                    {"text":"Нравится весь образ","only_one":true},
                    {"text":"Нравится рубашка"},
                    {"text":"Нравится юбка"},
                    {"text":"Нравится сумка"},
                    {"text":"Образ не нравится","only_one":true}]},
        
                {"type":"check",
                "image":"img/anketa/question_13.png",
                "label":"Что вы думаете про этот образ?",
                "answer":[3],
                "option":[
                    {"text":"Нравится весь образ","only_one":true},
                    {"text":"Нравится худи"},
                    {"text":"Нравится юбка"},
                    {"text":"Нравится рюкзак"},
                    {"text":"Образ не нравится","only_one":true}]},
        
                {"type":"text","label":"Ваш адрес электронной почты?",
                "answer":"seredneva03@yandex.ru"},
        
                {"type":"text",
                "label":"Укажите ваш номер телефона",
                "answer":"(901) 714-0232"},
        
                {"type":"check",
                "image":"img/anketa/question_16.png",
                "label":"Что вы думаете про этот образ?",
                "answer":[],
                "option":[
                    {"text":"Нравится весь образ","only_one":true},
                    {"text":"Нравится блуза"},
                    {"text":"Нравятся джинсы"},
                    {"text":"Нравится сумка"},
                    {"text":"Образ не нравится","only_one":true}]},
        
                {"type":"check",
                "image":"img/anketa/question_17.png",
                "label":"Что вы думаете про этот образ?",
                "answer":[],
                "option":[
                    {"text":"Нравится весь образ","only_one":true},
                    {"text":"Нравится платье"},
                    {"text":"Нравится сумка"},
                    {"text":"Образ не нравится","only_one":true}]},
        
                {"type":"check",
                "image":"img/anketa/question_18.png",
                "label":"Что вы думаете про этот образ?",
                "answer":[],
                "option":[
                    {"text":"Нравится весь образ","only_one":true},
                    {"text":"Нравится джинсовая куртка"},
                    {"text":"Нравится платье"},
                    {"text":"Образ не нравится","only_one":true}]},
                
                {"type":"check",
                "image":"img/anketa/question_19.png",
                "label":"Что вы думаете про этот образ?",
                "answer":[],
                "option":[
                    {"text":"Нравится весь образ","only_one":true},
                    {"text":"Нравится водолазка"},
                    {"text":"Нравится юбка"},
                    {"text":"Нравится сумка"},
                    {"text":"Образ не нравится","only_one":true}]},
        
                {"type":"check",
                "image":"img/anketa/question_20.png",
                "label":"Что вы думаете про этот образ?",
                "answer":[],
                "option":[
                    {"text":"Нравится весь образ","only_one":true},
                    {"text":"Нравится пиджак"},
                    {"text":"Нравится платье"},
                    {"text":"Нравится сумка"},
                    {"text":"Образ не нравится","only_one":true}]},
        
                {"type":"check",
                "image":"img/anketa/question_21.png",
                "label":"Что вы думаете про этот образ?",
                "answer":[],
                "option":[
                    {"text":"Нравится весь образ","only_one":true},
                    {"text":"Нравится свитер"},
                    {"text":"Нравятся брюки"},
                    {"text":"Нравится сумка"},
                    {"text":"Образ не нравится","only_one":true}]},
        
                {"type":"check",
                "image":"img/anketa/question_22.png",
                "label":"Что вы думаете про этот образ?",
                "answer":[],
                "option":[
                    {"text":"Нравится весь образ","only_one":true},
                    {"text":"Нравится рубашка"},
                    {"text":"Нравится футболка"},
                    {"text":"Нравятся джинсы"},
                    {"text":"Нравится клатч"},
                    {"text":"Образ не нравится","only_one":true}]},
        
                {"type":"check",
                "image":"img/anketa/question_23.png",
                "label":"Что вы думаете про этот образ?",
                "answer":[2],
                "option":[
                    {"text":"Нравится весь образ","only_one":true},
                    {"text":"Нравится кардиган"},
                    {"text":"Нравится водолазка"},
                    {"text":"Нравятся брюки"},
                    {"text":"Нравится сумка"},
                    {"text":"Образ не нравится","only_one":true}]},
        
                {"type":"check",
                "image":"img/anketa/question_24.png",
                "label":"Что вы думаете про этот образ?",
                "answer":[],
                "option":[
                    {"text":"Нравится весь образ","only_one":true},
                    {"text":"Нравится пиджак"},
                    {"text":"Нравится рубашка"},
                    {"text":"Нравится юбка"},
                    {"text":"Нравится сумка"},
                    {"text":"Образ не нравится","only_one":true}]},
        
                {"type":"check","label":"В какой цветовой гамме для вас предпочтительнее получить вещи в коробочке? (можно выбрать несколько)",
                "answer":[1,3,2],
                "option":[
                    
                    {"text":"Серый монохром",
                    "image":"img/anketa/question_25_0.png",
                    "colors":["#FFFFFF","#3B3838","#757070","#000000","#CFCDCD","#F1F1F1","#7F7F7F","#404040","#DADADA"]},
                    
                    {"text":"Бежевый монохром",
                    "image":"img/anketa/question_25_1.png",
                    "colors":["#BCA181","#786058","#F0DECB","#D5CBBD","#E5D1B1","#392D2B","#5B3022","#FDF2E4","#BC9887"]},
        
                    {"text":"Пастельная",
                    "image":"img/anketa/question_25_2.png",
                    "colors":["#D0DAF5","#E5EFDB","#F1D7CF","#FDF2D0","#F7E5D8","#E3E4E6","#EED4EC","#DBD3C7","#E0EBF6"]},
        
                    {"text":"Мягкая",
                    "image":"img/anketa/question_25_3.png",
                    "colors":["#484D67","#D5CBBC","#9C6F73","#D1918E","#5A7F6C","#796F64","#6C394D","#B9B9AC","#456C79"]},
        
                    {"text":"Тёмная",
                    "image":"img/anketa/question_25_4.png",
                    "colors":["#151414","#6C394D","#3D2D2E","#252626","#4F555B","#5B3022","#5C1311","#3F562A","#000E54"]},
        
                    {"text":"Яркая",
                    "image":"img/anketa/question_25_5.png",
                    "colors":["#F2EE50","#3B7D7C","#BB4239","#AA3B80","#312DB5","#94B73D","#448EC7","#72F8D9","#D6722E"]}]},
        
                    {"type":"text",
                    "label":"Ваш рост? (см)",
                    "answer":"158",
                    "placeholder":"161"},
        
                    {"type":"text",
                    "label":"Дата вашего рождения?",
                    "answer":"28.05.1990",
                    "placeholder":"10.09.1995"},
        
                    {"type":"choice",
                    "label":"Ваш род деятельности?",
                    "answer":13,
                    "option":[
                        {"text":"Маркетолог"},{"text":"Менеджер"},
                        {"text":"Финансист"},
                        {"text":"Медиа и коммуникации"},
                        {"text":"Искусство / Творчество"},
                        {"text":"Юрист"},
                        {"text":"Предприниматель"},
                        {"text":"Инженер"},
                        {"text":"Архитектор"},
                        {"text":"Студент"},
                        {"text":"Дизайнер"},
                        {"text":"IT"},
                        {"text":"Консультант"},
                        {"text":"Врач / Медицинский сотрудник"},
                        {"text":"Ресторанный бизнес"},
                        {"text":"Менеджмент"},
                        {"text":"Личный помощник"},
                        {"text":"Продавец"},
                        {"text":"Сейчас не работаю"},
                        {"text":"Другое"}]},
                    
                    {"type":"choice",
                     "label":"Ваш актуальный цвет волос?",
                     "answer":4,
                     "option":[
                        {"text":"Блонд"},
                        {"text":"Брюнет"},
                        {"text":"Шатен"},
                        {"text":"Рыжий"},
                        {"text":"Русый"}]},
        
                    {"type":"check",
                    "label":"Российский размер верха (платья, блузы, жакеты, свитеры и др.)",
                    "answer":[2],
                    "option":[
                        {"text":"40"},
                        {"text":"42"},
                        {"text":"44"},
                        {"text":"46"},
                        {"text":"48"}]},
        
                    {"type":"check",
                    "label":"Российский размер низа (брюки, джинсы, юбки)",
                    "answer":[1,2],
                    "option":[
                        {"text":"40"},
                        {"text":"42"},
                        {"text":"44"},
                        {"text":"46"},
                        {"text":"48"}]},
        
                    {"type":"text",
                    "label":"Ваш объем груди (см) (необязательно)",
                    "answer":"89",
                    "placeholder":"86"},
        
                    {"type":"text",
                    "label":"Ваш объем талии (см) (необязательно)",
                    "answer":"75",
                    "placeholder":"65"},
        
                    {"type":"text",
                    "label":"Ваш объем бедер (см) (необязательно)",
                    "answer":"91",
                    "placeholder":"94"},
        
                    {"type":"check",
                    "label":"Как вам комфортнее носить верхнюю одежду?",
                    "answer":[1],
                    "option":[{"text":"Облегающе"},
                    {"text":"По размеру"},
                    {"text":"Свободно"}]},
        
                    {"type":"check",
                    "label":"Как вам комфортнее носить низ?",
                    "answer":[1,2],
                    "option":[
                        {"text":"Облегающе"},
                        {"text":"По размеру"},
                        {"text":"Свободно"}]},
        
                    {"type":"check",
                    "label":"Какие джинсы / брюки вы предпочитаете?",
                    "answer":[4,1,0],
                    "option":[
                        {"text":"Скинни",
                        "image":"img/anketa/question_37_0.png"},
        
                        {"text":"Прямые",
                        "image":"img/anketa/question_37_1.png"},
        
                        {"text":"Широкие",
                        "image":"img/anketa/question_37_2.png"},
        
                        {"text":"Клеш",
                        "image":"img/anketa/question_37_3.png"},
        
                        {"text":"МОМ",
                        "image":"img/anketa/question_37_4.png"}]},
        
                    {"type":"check",
                    "label":"Посадка джинсов / брюк",
                    "answer":[0],
                    "option":[
                        {"text":"Высокая",
                        "image":"img/anketa/question_38_0.png"},
        
                        {"text":"Средняя",
                        "image":"img/anketa/question_38_1.png"},
        
                        {"text":"Низкая",
                        "image":"img/anketa/question_38_2.png"}]},
        
                    {"type":"check",
                    "label":"Длина джинсов / брюк",
                    "answer":[1,2],
                    "option":[
                        {"text":"Укороченные",
                        "image":"img/anketa/question_39_0.png"},
        
                        {"text":"Стандартные",
                        "image":"img/anketa/question_39_1.png"},
        
                        {"text":"Длинные",
                        "image":"img/anketa/question_39_2.png"}]},
        
                    {"type":"check",
                    "label":"Какие юбки / платья вы предпочитаете? ",
                    "answer":[1,0],
                    "option":[
                        {"text":"Мини",
                        "image":"img/anketa/question_40_0.png"},
        
                        {"text":"Миди",
                        "image":"img/anketa/question_40_1.png"},
        
                        {"text":"Макси",
                        "image":"img/anketa/question_40_2.png"}]},
        
                    {"type":"check",
                    "label":"Что вы обычно предпочитаете скрывать?",
                    "answer":[4,2],
                    "option":[
                        {"text":"Руки"},
                        {"text":"Плечи"},
                        {"text":"Спина"},
                        {"text":"Декольте"},
                        {"text":"Талия"},
                        {"text":"Ноги"},
                        {"text":"Ничего","only_one":true}]},
                    
                    {"type":"choice",
                    "label":"Вы больше любите джинсы с топом или платья?",
                    "answer":2,
                    "option":[
                        {"text":"Больше джинсы / майки / свитера"},
                        {"text":"Больше платья"},
                        {"text":"Примерно 50/50"}]},
        
                    {"type":"check",
                    "label":"Где вы обычно покупаете одежду?",
                    "answer":[15],
                    "option":[{"text":"Zara"},
                        {"text":"H&M"},
                        {"text":"Pull&Bear"},
                        {"text":"Topshop"},
                        {"text":"Mango"},
                        {"text":"Reserved"},
                        {"text":"Massimo Dutti"},
                        {"text":"Calvin Klein"},
                        {"text":"Twin Set"},
                        {"text":"Lamoda"},
                        {"text":"Wildberries"},
                        {"text":"12storeez"},
                        {"text":"Farfetch"},
                        {"text":"Asos"},
                        {"text":"Yoox"},
                        {"text":"Ничего из этого","only_one":true}]},
                    
                    {"type":"choice",
                    "label":"Проколоты ли у вас уши?",
                    "answer":0,
                    "option":[
                        {"text":"Да"},
                        {"text":"Нет"}]},
        
                    {"type":"choice",
                    "label":"Какие ювелирные изделия вы предпочитаете?",
                    "answer":1,
                    "option":[
                        {"text":"Серебро"},
                        {"text":"Золото"},
                        {"text":"50/50"}]},
        
                    {"type":"choice","label":"Готовы ли вы носить бижутерию?",
                    "answer":0,
                    "option":[
                        {"text":"Да"},
                        {"text":"Нет"}]},
        
                    {"type":"check",
                    "label":"Какую одежду вы хотели бы видеть в капсуле?<br>(Сейчас мы не доставляем обувь и верхнюю одежду в наших капсулах, но даем рекомендации в сочетаниях)",
                    "answer":[0,1,2,3,4,5,6,7,8],
                    "option":[
                        {"text":"Майки / рубашки / блузы",
                        "image":"img/anketa/question_47_0.png"},
        
                        {"text":"Свитера / кардиганы / пуловеры",
                        "image":"img/anketa/question_47_1.png"},
        
                        {"text":"Пиджаки / жакеты",
                        "image":"img/anketa/question_47_2.png"},
                        
                        {"text":"Джинсы",
                        "image":"img/anketa/question_47_3.png"},
        
                        {"text":"Брюки",
                        "image":"img/anketa/question_47_4.png"},
        
                        {"text":"Шорты",
                        "image":"img/anketa/question_47_5.png"},
        
                        {"text":"Платья",
                        "image":"img/anketa/question_47_6.png"},
        
                        {"text":"Юбки",
                        "image":"img/anketa/question_47_7.png"},
        
                        {"text":"Готова на все",
                        "image":"img/anketa/question_47_8.png"}]},
        
                    {"type":"check",
                    "label":"Какую одежду вы точно НЕ хотели бы видеть в капсуле?",
                    "answer":[],
                    "option":[
                        {"text":"Майки / рубашки / блузы",
                        "image":"img/anketa/question_47_0.png"},
        
                        {"text":"Свитера / кардиганы / пуловеры",
                        "image":"img/anketa/question_47_1.png"},
        
                        {"text":"Пиджаки / жакеты",
                        "image":"img/anketa/question_47_2.png"},
        
                        {"text":"Джинсы",
                        "image":"img/anketa/question_47_3.png"},
        
                        {"text":"Брюки",
                        "image":"img/anketa/question_47_4.png"},
        
                        {"text":"Шорты",
                        "image":"img/anketa/question_47_5.png"},
        
                        {"text":"Платья",
                        "image":"img/anketa/question_47_6.png"},
        
                        {"text":"Юбки",
                        "image":"img/anketa/question_47_7.png"}]},
        
                    {"type":"check",
                    "label":"Какие аксессуары вы точно НЕ хотели бы видеть в капсуле?",
                    "answer":[],
                    "option":[
                        {"text":"Сумки",
                        "image":"img/anketa/question_49_0.png"},
        
                        {"text":"Шарфы",
                        "image":"img/anketa/question_49_1.png"},
        
                        {"text":"Сережки",
                        "image":"img/anketa/question_49_2.png"},
        
                        {"text":"Колье / аксессуары на шею",
                        "image":"img/anketa/question_49_3.png"},
        
                        {"text":"Ремни",
                        "image":"img/anketa/question_49_4.png"}]},
        
                    {"type":"check",
                    "label":"Цвета, которые вы точно НЕ хотели бы видеть в капсуле?",
                    "answer":[],
                    "option":[
                        {"text":"Чёрный","color":"#090909"},
                        {"text":"Коричневый","color":"#633f13"},
                        {"text":"Бежевый","color":"#d0bd92"},
                        {"text":"Белый","color":"#ffffff"},
                        {"text":"Серый","color":"#a6a6a6"},
                        {"text":"Синий","color":"#0e2076"},
                        {"text":"Голубой","color":"#007bff"},
                        {"text":"Светло-голубой","color":"#cdd9f7"},
                        {"text":"Морской","color":"#177e7c"},
                        {"text":"Зеленый","color":"#88b913"},
                        {"text":"Желтый","color":"#f1ef07"},
                        {"text":"Оранжевый","color":"#ed6e2d"},
                        {"text":"Красный","color":"#d7352c"},
                        {"text":"Бордовый","color":"#a12a14"},
                        {"text":"Фиолетовый","color":"#6f2f9f"},
                        {"text":"Розовый","color":"#f3d1ed"}]},
        
                    {"type":"check",
                    "label":"Принты, которые вы точно НЕ хотели бы видеть в капсуле?",
                    "answer":[0,6],
                    "option":[
                        {"text":"Принты животных (например: змеиная кожа или леопард)"},
                        {"text":"Цветочные принты"},
                        {"text":"Горошек"},
                        {"text":"Полоски"},
                        {"text":"Клетка"},
                        {"text":"Узоры"},
                        {"text":"Печатные принты"}]},
        
                    {"type":"check",
                    "label":"Ткани, которых нам следует избегать?",
                    "answer":[],
                    "option":[
                        {"text":"Искусственный мех"},
                        {"text":"Искусственная кожа"},
                        {"text":"Натуральная кожа"},
                        {"text":"Полиэстер"},
                        {"text":"Шерсть"}]},
        
                    {"type":"text",
                    "label":"Ваши строгие нет:",
                    "answer":"",
                    "placeholder":"Я не ношу джинсы с высокой талией"},
        
                    {"type":"choice","label":"Сколько вы готовы потратить на блузу / рубашку?",
                    "answer":1,
                    "option":[
                        {"text":"1000-2000 рублей"},
                        {"text":"2000-3000 рублей"},
                        {"text":"3000-5000 рублей"},
                        {"text":"свыше 5000 рублей"}]},
        
                    {"type":"choice",
                    "label":"Сколько вы готовы потратить на свитер / джемпер / пуловер?",
                    "answer":0,
                    "option":[
                        {"text":"2000-3000 рублей"},
                        {"text":"3000-5000 рублей"},
                        {"text":"5000-8000 рублей"},
                        {"text":"свыше 8000 рублей"}]},
        
                    {"type":"choice",
                    "label":"Сколько вы готовы потратить на платья / сарафаны?",
                    "answer":0,
                    "option":[
                        {"text":"3000-4000 рублей"},
                        {"text":"4000-6000 рублей"},
                        {"text":"6000-8000 рублей"},
                        {"text":"свыше 8000 рублей"}]},
        
                    {"type":"choice",
                    "label":"Сколько вы готовы потратить на жакет / пиджак?",
                    "answer":0,
                    "option":[
                        {"text":"4000-5000 рублей"},
                        {"text":"5000-7000 рублей"},
                        {"text":"7000-10000 рублей"},
                        {"text":"свыше 10000 рублей"}]},
        
                    {"type":"choice",
                    "label":"Сколько вы готовы потратить на джинсы / брюки / юбки?",
                    "answer":0,
                    "option":[
                        {"text":"2000-3000 рублей"},
                        {"text":"3000-5000 рублей"},
                        {"text":"5000-8000 рублей"},
                        {"text":"свыше 8000 рублей"}]},
        
                    {"type":"choice",
                    "label":"Сколько вы готовы потратить на сумки?",
                    "answer":0,
                    "option":[
                        {"text":"3000-4000 рублей"},
                        {"text":"4000-6000 рублей"},
                        {"text":"6000-8000 рублей"},
                        {"text":"свыше 8000 рублей"}]},
        
                    {"type":"choice",
                    "label":"Сколько вы готовы потратить на другие аксессуары: ремни, шарфы, платки?",
                    "answer":null,
                    "option":[
                        {"text":"1000-2000 рублей"},
                        {"text":"2000-3000 рублей"},
                        {"text":"свыше 3000 рублей"}]},
        
                    {"type":"choice",
                    "label":"Сколько вы готовы потратить на серьги / колье / браслеты?",
                    "answer":1,
                    "option":[
                        {"text":"1000-2000 рублей"},
                        {"text":"2000-3000 рублей"},
                        {"text":"свыше 3000 рублей"}]},
        
                    {"type":"text",
                    "label":"Для какой цели вы хотели бы подборку?",
                    "answer":"",
                    "placeholder":"Прежде всего мне нужны комфортные вещи, но особое внимание нужно обратить на то, что я не люблю V-образный вырез и геометрические принты"},
                    {"type":"text",
                    "label":"Адрес доставки (Город, улица, дом, корпус, подъезд, этаж, номер квартиры)",
                    "answer":"Королёв, мкр. Первомайский, ул. Лермонтова, дом 2, подьезд 3, этаж 4, кв 91",
                    "placeholder":"Подольск, просп. Ленина, 128/24, квартира 1"},
        
                    {"type":"text",
                    "label":"Укажите удобную дату для доставки. Cрок доставки сейчас составляет 7 дней. За день до доставки мы подтвердим вашу готовность принять заказ - вам придет письмо на почту. При желании, вы сможете поменять на удобную дату.",
                    "answer":"03.04.2021"},
        
                    {"type":"check",
                    "label":"Удобное время для доставки",
                    "answer":2,
                    "option":[
                        
                        {"link":0,
                        "text":"10:00 - 14:00"},
        
                        {"link":0,
                        "text":"14:00 - 18:00"},
        
                        {"link":0,
                        "text":"18:00 - 22:00"},
        
                        {"link":1,
                        "text":"12:00 - 18:00"},
        
                        {"link":1,
                        "text":"18:00 - 22:00"}]},
        
                    {"type":"check",
                    "label":"Удобное время для возврата",
                    "answer":2,
                    "option":[
                        
                        {"link":0,
                        "text":"10:00 - 14:00"},
        
                        {"link":0,
                        "text":"14:00 - 18:00"},
        
                        {"link":0,
                        "text":"18:00 - 22:00"},
        
                        {"link":1,
                        "text":"12:00 - 18:00"},
        
                        {"link":1,
                        "text":"18:00 - 22:00"}]},
        
                    {"type":"text",
                    "label":", ваша фамилия? ( нужно для курьерской службы )",
                    "answer":"Середнева"},
        
                    {"type":"text",
                    "label":"Ссылка на социальную сеть ( опционально )",
                    "answer":" VK ekaterinaromanova28 "},
        
                    {"type":"choice",
                    "label":", и последнее, как вы узнали о нас?",
                    "answer":0,
                    "option":[
                        {"text":"Instagram"},
                        {"text":"Совет подруги"},
                        {"text":"Поиск в Google / Yandex"},
                        {"text":"Увидела у блогера"},
                        {"text":"Статья в интернете"},
                        {"text":"Рассылка"}]},
                    {"type":"text","label":"Ваш вес (кг)",
                    "answer":"",
                    "placeholder":"55"},
        
                    {"type":"choice",
                    "label":"Город доставки?",
                    "answer":0,
                    "option":[
                        {"text":"Москва и Московская область"},
                        {"text":"Санкт-Петербург и Ленинградская область"}]},
                    
                    {"type":"choice",
                    "label":"Хотели бы вы загрузить несколько фото для вашего стилиста?",
                    "answer":1,
                    "option":[
                        {"text":"Да, готова прикрепить фото"},
                        {"text":"Моих фото в социальных сетях достаточно"}]},
        
                    {"type":"files",
                    "label":""}],
                    "disclaimer":["<p class=\"font-size-lg\">Сейчас мы доставляем наши капсулы в Москву, Московскую область, Санкт-Петербург и Ленинградскую область.</p><p class=\"font-size-lg\">Заполняя анкету, вы даете согласие на обработку персональных данных: <a href=\"https://thecapsula.ru/privacy\">https://thecapsula.ru/privacy</a> и принимаете оферту <a href=\"https://thecapsula.ru/publicoffer\">https://thecapsula.ru/publicoffer</a></p>","Посмотрите еще образы","","Расскажите нам больше о себе, чтобы стилист сделал подборку персонально под вас","А теперь расскажите, какую одежду вы предпочитаете носить","","",""],
                    "more_lifestyle":false}}';
    }

    function calculateHeight($height) {
        if( $height <= 164 ) return 'petit';
        if( $height > 164 && $height < 170) return 'средняя';
        if( $height >= 170 ) return 'высокая/';
    }

    function calculateFigura($og, $ot, $ob) {

        if (!is_numeric($og) || !is_numeric($ot) || !is_numeric($ob))
            return '';

        if(!empty($og) && !empty($ob)) {
            if($og/$ob >= 1.05) return 'Перевернутый треугольник';
        }

        if(!empty($ot) && !empty($ob)) {
            if($ot/$ob >= 0.75) return 'Прямоугольник';
        }

        if(!empty($og) && !empty($ob)) {
            if($ob/$og >= 1.05) return 'Треугольник (груша)';
        }

        if(!empty($og) && !empty($ob) && !empty($ot)) {
            if($ot/$og <= 0.75 && $ot/$ob <= 0.75) return 'Песочные часы';
        }
        return '';
    }

    function getAge($y, $m, $d) {

        if($m > date('m') || $m == date('m') && $d > date('d'))
            return (date('Y') - $y - 1);

        return (date('Y') - $y);
    }

    function calculateAge($answer_age) {
        $arr_date = str_replace('-','.', $answer_age);
        $arr_date = explode('.', $arr_date);

        if($arr_date[0] > 1900)
            $age = $this->getAge($arr_date[0], $arr_date[1], $arr_date[2]);
        else $age = $this->getAge($arr_date[2], $arr_date[1], $arr_date[0]);

        if($age > 10 && $age < 80) return $age;
        return $answer_age;

    }

    function createFotoPaths($uuid, $answer) {

        $base_path = config('config.ANKETA_URL');

        $base_path = $base_path . "/storage/" . config('config.ANKETA_CLIENT_PHOTO_PATH');
        $dir = "/" . substr($uuid, 0, config('config.ANKETA_DIR_LENGTH') ) . "/";
        $base_filename = $uuid;
        $ext = '.jpg';
        $i = 0;
        $j = 2;

        $path = $base_path . $dir .  $base_filename . '_';

        for ($i; $i <= $j; $i++) {
            //if (@fopen($path . $i . $ext, 'r'))
            if(!empty($answer[$i])) $fotos_url[] = $path . $i . $ext;
        }

        return $fotos_url ?? [];
    }
}