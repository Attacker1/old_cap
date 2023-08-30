<?php


namespace App\Http\Classes\DataConversion;


use App\Http\Models\AdminClient\Questionnaire;
use Mockery\Exception;

class AnketaToCsv
{

    private $result = [];
    private $row = [];
    private $rowHeader = [];

    private $headings = true;
    private $step;
    private $page;

    private $total;

    private  $link = '/storage/anketa_whole.csv';

    private $old = [
        0 => [
            "type" => "text",
            "label" => "Привет, меня зовут Даша. Я – менеджер по работе со стилистами Capsula. Подробно расскажите нам о себе, это поможет нам подобрать идеальную капсулу персонально для вас",
            "answer" => "Даша",
            "placeholder" => "Как вас зовут?"
        ],
        1 => [
            "type" => "check",
            "label" => "<br><br>Расскажите, какой стиль вы предпочитаете в выходные?",
            "answer" => [
                1,
                2
            ],
            "appeal" => ", приятно познакомиться!",
            "option" => [
                [
                    "text" => "Business casual",
                    "image" => "img/anketa/question_1_0.jpeg"
                ],
                [
                    "text" => "Casual",
                    "image" => "img/anketa/question_1_1.jpeg"
                ],
                [
                    "text" => "Спортивный стиль",
                    "image" => "img/anketa/question_1_2.jpeg"
                ],
                [
                    "text" => "Стилей же больше! Одеваюсь по-другому",
                    "image" => "",
                    "textThumb" => true
                ]
            ]
        ],
        2 => [
            "type" => "check",
            "label" => "Как вы одеваетесь на работу?",
            "answer" => [
                2
            ],
            "option" => [
                [
                    "text" => "Строгий<br> дресс-код",
                    "image" => "img/anketa/question_2_0.jpg"
                ],
                [
                    "text" => "Нестрогий<br> дресс-код",
                    "image" => "img/anketa/question_2_1.jpeg"
                ],
                [
                    "text" => "Casual",
                    "image" => "img/anketa/question_2_2.jpeg"
                ],
                [
                    "text" => "Стилей же больше! Одеваюсь по-другому",
                    "image" => "",
                    "textThumb" => true
                ]
            ]
        ],
        3 => [
            "type" => "choice",
            "label" => "Поняла.<br><br>Вы хотите, чтобы стилист подобрал что-то отличное от того, к чему вы привыкли, или сохранил ваш стиль?",
            "answer" => 0,
            "option" => [
                [
                    "text" => "Хочу абсолютно новый стиль. Готова к экспериментам"
                ],
                [
                    "text" => "50/50 - действуем аккуратно"
                ],
                [
                    "text" => "Хочу, чтобы мой стиль остался неизменным"
                ]
            ]
        ],
        4 => [
            "type" => "check",
            "image" => "img/anketa/question_4_1.jpg",
            "label" => "Отлично! Далее я покажу несколько образов. Расскажите свое мнение о них, чтобы мы лучше поняли ваши предпочтения",
            "answer" => [
                0,
                1,
                2,
                3,
                4,
                5
            ],
            "option" => [
                [
                    "text" => "Нравится весь образ",
                    "on_values" => [
                        0,
                        1,
                        2,
                        3,
                        4,
                        5
                    ]
                ],
                [],
                [],
                [],
                [],
                [],
                [
                    "text" => "Ничего не понравилось",
                    "only_one" => true
                ]
            ]
        ],
        5 => [
            "type" => "check",
            "image" => "img/anketa/question_5_1.jpg",
            "label" => "Что вы думаете про этот образ?",
            "answer" => [
                2,
                1,
                4,
                3
            ],
            "option" => [
                [
                    "text" => "Нравится весь образ",
                    "on_values" => [
                        0,
                        1,
                        2,
                        3,
                        4,
                        5
                    ]
                ],
                [],
                [],
                [],
                [],
                [],
                [
                    "text" => "Ничего не понравилось",
                    "only_one" => true
                ]
            ]
        ],
        6 => [
            "type" => "check",
            "image" => "img/anketa/question_6_1.jpg",
            "label" => "Что вы думаете про этот образ?",
            "answer" => [
                4,
                3,
                1,
                5
            ],
            "option" => [
                [
                    "text" => "Нравится весь образ",
                    "on_values" => [
                        0,
                        1,
                        2,
                        3,
                        4,
                        5
                    ]
                ],
                [],
                [],
                [],
                [],
                [],
                [
                    "text" => "Ничего не понравилось",
                    "only_one" => true
                ]
            ]
        ],
        7 => [
            "type" => "check",
            "image" => "img/anketa/question_7_1.jpg",
            "label" => "Что вы думаете про этот образ?",
            "answer" => [
                3,
                1
            ],
            "option" => [
                [
                    "text" => "Нравится весь образ",
                    "on_values" => [
                        0,
                        1,
                        2,
                        3,
                        4,
                        5
                    ]
                ],
                [],
                [],
                [],
                [],
                [],
                [
                    "text" => "Ничего не понравилось",
                    "only_one" => true
                ]
            ]
        ],
        8 => [
            "type" => "check",
            "image" => "img/anketa/question_8_1.jpg",
            "label" => "Что вы думаете про этот образ?",
            "answer" => [
                5,
                6
            ],
            "option" => [
                [
                    "text" => "Нравится весь образ",
                    "on_values" => [
                        0,
                        1,
                        2,
                        3,
                        4,
                        5,
                        6
                    ]
                ],
                [],
                [],
                [],
                [],
                [],
                [],
                [
                    "text" => "Ничего не понравилось",
                    "only_one" => true
                ]
            ]
        ],
        9 => [
            "type" => "check",
            "image" => "img/anketa/question_9.png",
            "label" => "Что вы думаете про этот образ?",
            "answer" => [2, 3],
            "option" => [
                [
                    "text" => "Нравится весь образ",
                    "on_values" => [
                        0,
                        1,
                        2,
                        3,
                        4,
                        5
                    ]
                ],
                [],
                [],
                [],
                [],
                [],
                [
                    "text" => "Ничего не понравилось",
                    "only_one" => true
                ]
            ]
        ],
        10 => [
            "type" => "check",
            "image" => "img/anketa/question_10.png",
            "label" => "Что вы думаете про этот образ?",
            "answer" => [0, 1],
            "option" => [
                [
                    "text" => "Нравится весь образ",
                    "on_values" => [
                        0,
                        1,
                        2,
                        3,
                        4
                    ]
                ],
                [],
                [],
                [],
                [],
                [
                    "text" => "Ничего не понравилось",
                    "only_one" => true
                ]
            ]
        ],
        11 => [
            "type" => "check",
            "image" => "img/anketa/question_11.png",
            "label" => "Что вы думаете про этот образ?",
            "answer" => [3, 4],
            "option" => [
                [
                    "text" => "Нравится весь образ",
                    "on_values" => [
                        0,
                        1,
                        2,
                        3,
                        4,
                        5
                    ]
                ],
                [],
                [],
                [],
                [],
                [],
                [
                    "text" => "Ничего не понравилось",
                    "only_one" => true
                ]
            ]
        ],
        12 => [
            "type" => "check",
            "image" => "img/anketa/question_12.png",
            "label" => "Что вы думаете про этот образ?",
            "answer" => [1, 2],
            "option" => [
                [
                    "text" => "Нравится весь образ",
                    "on_values" => [
                        0,
                        1,
                        2,
                        3,
                        4
                    ]
                ],
                [],
                [],
                [],
                [],
                [
                    "text" => "Ничего не понравилось",
                    "only_one" => true
                ]
            ]
        ],
        13 => [
            "type" => "check",
            "image" => "img/anketa/question_13.png",
            "label" => "Что вы думаете про этот образ?",
            "answer" => [0],
            "option" => [
                [
                    "text" => "Нравится весь образ",
                    "on_values" => [
                        0,
                        1,
                        2,
                        3,
                        4
                    ]
                ],
                [],
                [],
                [],
                [],
                [
                    "text" => "Ничего не понравилось",
                    "only_one" => true
                ]
            ]
        ],
        14 => [
            "type" => "text",
            "label" => "Заполните контактные данные - они нужны, чтобы уточнять некоторые детали. Обещаем не надоедать со спамом 🙂",
            "answer" => "Morozova.lady-dasha@mail.ru",
            "appeal" => ", спасибо! "
        ],
        15 => [
            "type" => "text",
            "label" => "Укажите ваш номер телефона",
            "answer" => "(999) 860-31-22"
        ],
        16 => [
            "type" => "check",
            "image" => "img/anketa/question_16_1.jpg",
            "label" => "Что вы думаете про этот образ?",
            "answer" => [
                5
            ],
            "option" => [
                [
                    "text" => "Нравится весь образ",
                    "on_values" => [
                        0,
                        1,
                        2,
                        3,
                        4,
                        5,
                        6
                    ]
                ],
                [],
                [],
                [],
                [],
                [],
                [],
                [
                    "text" => "Ничего не понравилось",
                    "only_one" => true
                ]
            ]
        ],
        17 => [
            "type" => "check",
            "image" => "img/anketa/question_17_1.jpg",
            "label" => "Что вы думаете про этот образ?",
            "answer" => [
                6
            ],
            "option" => [
                [
                    "text" => "Нравится весь образ",
                    "on_values" => [
                        0,
                        1,
                        2,
                        3,
                        4,
                        5
                    ]
                ],
                [],
                [],
                [],
                [],
                [],
                [
                    "text" => "Ничего не понравилось",
                    "only_one" => true
                ]
            ]
        ],
        18 => [
            "type" => "check",
            "image" => "img/anketa/question_18_1.jpg",
            "label" => "Что вы думаете про этот образ?",
            "answer" => [
                1,
                3
            ],
            "option" => [
                [
                    "text" => "Нравится весь образ",
                    "on_values" => [
                        0,
                        1,
                        2,
                        3,
                        4,
                        5,
                        6
                    ]
                ],
                [],
                [],
                [],
                [],
                [],
                [],
                [
                    "text" => "Ничего не понравилось",
                    "only_one" => true
                ]
            ]
        ],
        19 => [
            "type" => "check",
            "image" => "img/anketa/question_19_1.jpg",
            "label" => "Что вы думаете про этот образ?",
            "answer" => [
                3,
                1,
                5
            ],
            "option" => [
                [
                    "text" => "Нравится весь образ",
                    "on_values" => [
                        0,
                        1,
                        2,
                        3,
                        4,
                        5
                    ]
                ],
                [],
                [],
                [],
                [],
                [],
                [
                    "text" => "Ничего не понравилось",
                    "only_one" => true
                ]
            ]
        ],
        20 => [
            "type" => "check",
            "image" => "img/anketa/question_20_1.jpg",
            "label" => "Что вы думаете про этот образ?",
            "answer" => [
                1,
                2,
                5,
                4
            ],
            "option" => [
                [
                    "text" => "Нравится весь образ",
                    "on_values" => [
                        0,
                        1,
                        2,
                        3,
                        4,
                        5
                    ]
                ],
                [],
                [],
                [],
                [],
                [],
                [
                    "text" => "Ничего не понравилось",
                    "only_one" => true
                ]
            ]
        ],
        21 => [
            "type" => "check",
            "image" => "img/anketa/question_21.png",
            "label" => "Что вы думаете про этот образ?",
            "answer" => [1, 4],
            "option" => [
                [
                    "text" => "Нравится весь образ",
                    "on_values" => [
                        0,
                        1,
                        2,
                        3,
                        4
                    ]
                ],
                [],
                [],
                [],
                [],
                [
                    "text" => "Ничего не понравилось",
                    "only_one" => true
                ]
            ]
        ],
        22 => [
            "type" => "check",
            "image" => "img/anketa/question_22.png",
            "label" => "Что вы думаете про этот образ?",
            "answer" => [2, 4],
            "option" => [
                [
                    "text" => "Нравится весь образ",
                    "on_values" => [
                        0,
                        1,
                        2,
                        3,
                        4,
                        5
                    ]
                ],
                [],
                [],
                [],
                [],
                [],
                [
                    "text" => "Ничего не понравилось",
                    "only_one" => true
                ]
            ]
        ],
        23 => [
            "type" => "check",
            "image" => "img/anketa/question_23.png",
            "label" => "Что вы думаете про этот образ?",
            "answer" => [2, 3],
            "option" => [
                [
                    "text" => "Нравится весь образ",
                    "on_values" => [
                        0,
                        1,
                        2,
                        3,
                        4,
                        5
                    ]
                ],
                [],
                [],
                [],
                [],
                [],
                [
                    "text" => "Ничего не понравилось",
                    "only_one" => true
                ]
            ]
        ],
        24 => [
            "type" => "check",
            "image" => "img/anketa/question_24.png",
            "label" => "Что вы думаете про этот образ?",
            "answer" => [1, 3],
            "option" => [
                [
                    "text" => "Нравится весь образ",
                    "on_values" => [
                        0,
                        1,
                        2,
                        3,
                        4,
                        5
                    ]
                ],
                [],
                [],
                [],
                [],
                [],
                [
                    "text" => "Ничего не понравилось",
                    "only_one" => true
                ]
            ]
        ],
        25 => [
            "type" => "check",
            "label" => "Какая цветовая гамма вам ближе? Можно выбрать несколько 🙂",
            "answer" => [
                5,
                3,
                0
            ],
            "option" => [
                [
                    "text" => "Серый монохром",
                    "image" => "img/anketa/question_25_0.png",
                    "colors" => [
                        "#FFFFFF",
                        "#3B3838",
                        "#757070",
                        "#000000",
                        "#CFCDCD",
                        "#F1F1F1",
                        "#7F7F7F",
                        "#404040",
                        "#DADADA"
                    ]
                ],
                [
                    "text" => "Бежевый монохром",
                    "image" => "img/anketa/question_25_1.png",
                    "colors" => [
                        "#BCA181",
                        "#786058",
                        "#F0DECB",
                        "#D5CBBD",
                        "#E5D1B1",
                        "#392D2B",
                        "#5B3022",
                        "#FDF2E4",
                        "#BC9887"
                    ]
                ],
                [
                    "text" => "Пастельная",
                    "image" => "img/anketa/question_25_2.png",
                    "colors" => [
                        "#D0DAF5",
                        "#E5EFDB",
                        "#F1D7CF",
                        "#FDF2D0",
                        "#F7E5D8",
                        "#E3E4E6",
                        "#EED4EC",
                        "#DBD3C7",
                        "#E0EBF6"
                    ]
                ],
                [
                    "text" => "Мягкая",
                    "image" => "img/anketa/question_25_3.png",
                    "colors" => [
                        "#484D67",
                        "#D5CBBC",
                        "#9C6F73",
                        "#D1918E",
                        "#5A7F6C",
                        "#796F64",
                        "#6C394D",
                        "#B9B9AC",
                        "#456C79"
                    ]
                ],
                [
                    "text" => "Тёмная",
                    "image" => "img/anketa/question_25_4.png",
                    "colors" => [
                        "#151414",
                        "#6C394D",
                        "#3D2D2E",
                        "#252626",
                        "#4F555B",
                        "#5B3022",
                        "#5C1311",
                        "#3F562A",
                        "#000E54"
                    ]
                ],
                [
                    "text" => "Яркая",
                    "image" => "img/anketa/question_25_5.png",
                    "colors" => [
                        "#F2EE50",
                        "#3B7D7C",
                        "#BB4239",
                        "#AA3B80",
                        "#312DB5",
                        "#94B73D",
                        "#448EC7",
                        "#72F8D9",
                        "#D6722E"
                    ]
                ]
            ]
        ],
        26 => [
            "type" => "text",
            "label" => "мы уже знаем ваши предпочтения в одежде, теперь немного о вас 🙂",
            "answer" => "170",
            "appeal" => ", ",
            "placeholder" => "Ваш рост (см)"
        ],
        27 => [
            "type" => "text",
            "label" => "Дата вашего рождения?",
            "answer" => "2000-06-04",
            "placeholder" => "10.09.1995"
        ],
        28 => [
            "type" => "choice",
            "label" => "И еще немного=><br><br>Ваш род деятельности?",
            "answer" => "9",
            "option" => [
                "0" => [
                    "text" => "Маркетолог"
                ],
                "1" => [
                    "text" => "Менеджер"
                ],
                "2" => [
                    "text" => "Финансист"
                ],
                "3" => [
                    "text" => "Медиа и коммуникации"
                ],
                "4" => [
                    "text" => "Искусство / Творчество"
                ],
                "5" => [
                    "text" => "Юрист"
                ],
                "6" => [
                    "text" => "Предприниматель"
                ],
                "7" => [
                    "text" => "Инженер"
                ],
                "9" => [
                    "text" => "Студент"
                ],
                "10" => [
                    "text" => "Дизайнер"
                ],
                "11" => [
                    "text" => "IT"
                ],
                "13" => [
                    "text" => "Врач / Медицинский сотрудник"
                ],
                "14" => [
                    "text" => "Ресторанный бизнес"
                ],
                "16" => [
                    "text" => "Личный помощник"
                ],
                "17" => [
                    "text" => "Продавец"
                ],
                "18" => [
                    "text" => "Сейчас не работаю"
                ],
                "19" => [
                    "text" => "Другое"
                ]
            ]
        ],
        29 => [
            "type" => "choice",
            "label" => "Ваш цвет волос?",
            "answer" => 4,
            "option" => [
                [
                    "text" => "Блонд"
                ],
                [
                    "text" => "Брюнет"
                ],
                [
                    "text" => "Шатен"
                ],
                [
                    "text" => "Рыжий"
                ],
                [
                    "text" => "Русый"
                ]
            ]
        ],
        30 => [
            "type" => "check",
            "label" => "Класс, спасибо!<br>Дальше несколько вопросов о ваших размерах<br>Российский размер верха (платья, блузы, жакеты, свитеры и др.)",
            "answer" => [
                3
            ],
            "option" => [
                [
                    "text" => "38 (XXS)"
                ],
                [
                    "text" => "40 (XXS / XS)"
                ],
                [
                    "text" => "42 (XS)"
                ],
                [
                    "text" => "44 (S)"
                ],
                [
                    "text" => "46 (M)"
                ],
                [
                    "text" => "48 (M / L)"
                ],
                [
                    "text" => "50 (L)"
                ],
                [
                    "text" => "52 (XL)"
                ],
                [
                    "text" => "54 (XL)"
                ],
                [
                    "text" => "56 (XXL)"
                ]
            ]
        ],
        31 => [
            "type" => "check",
            "label" => "Российский размер низа (брюки, джинсы, юбки)",
            "answer" => [
                3
            ],
            "option" => [
                [
                    "text" => "38 (XXS)"
                ],
                [
                    "text" => "40 (XXS / XS или 25)"
                ],
                [
                    "text" => "42 (XS или 26, 27)"
                ],
                [
                    "text" => "44 (S или 28)"
                ],
                [
                    "text" => "46 (M или 29, 30)"
                ],
                [
                    "text" => "48 (M / L или 31, 32)"
                ],
                [
                    "text" => "50 (L или 33, 34)"
                ],
                [
                    "text" => "52 (XL или 35, 36)"
                ],
                [
                    "text" => "54 (XL или 38)"
                ],
                [
                    "text" => "56 (XXL или 40)"
                ]
            ]
        ],
        32 => [
            "type" => "text",
            "label" => "Замеры помогут стилисту точнее определить размер. Отвечать необязательно 🙂",
            "answer" => "85",
            "placeholder" => "Объем груди (см)"
        ],
        33 => [
            "type" => "text",
            "label" => "Ваш объем талии (см) (необязательно)",
            "answer" => "64",
            "placeholder" => "Объем талии (см)"
        ],
        34 => [
            "type" => "text",
            "label" => "Ваш объем бедер (см) (необязательно)",
            "answer" => "90",
            "placeholder" => "Объем бедер (см)"
        ],
        35 => [
            "type" => "check",
            "label" => "Вы - молодец, уже прошли больше половины пути. Расскажите теперь немного о вашем стиле.<br><br>Как вам комфортнее носить блузы/рубашки/джемперы?",
            "answer" => [
                1,
                0,
                2
            ],
            "option" => [
                [
                    "text" => "Облегающе"
                ],
                [
                    "text" => "По размеру"
                ],
                [
                    "text" => "Свободно"
                ]
            ]
        ],
        36 => [
            "type" => "check",
            "label" => "Принято! А если говорить о брюках/джинсах?",
            "answer" => [
                0,
                1,
                2
            ],
            "option" => [
                [
                    "text" => "Облегающе"
                ],
                [
                    "text" => "По размеру"
                ],
                [
                    "text" => "Свободно"
                ]
            ]
        ],
        37 => [
            "type" => "check",
            "label" => "какие модели джинсов вы предпочитаете?",
            "answer" => [
                0,
                3,
                2
            ],
            "appeal" => ", ",
            "option" => [
                [
                    "text" => "Скинни",
                    "image" => "img/anketa/question_37_0.png"
                ],
                [
                    "text" => "Прямые",
                    "image" => "img/anketa/question_37_1.png"
                ],
                [
                    "text" => "Широкие",
                    "image" => "img/anketa/question_37_2.png"
                ],
                [
                    "text" => "Клеш",
                    "image" => "img/anketa/question_37_3.png"
                ],
                [
                    "text" => "МОМ",
                    "image" => "img/anketa/question_37_4.png"
                ]
            ]
        ],
        38 => [
            "type" => "check",
            "label" => "А посадку?",
            "answer" => [
                1,
                0
            ],
            "option" => [
                [
                    "text" => "Высокая",
                    "image" => "img/anketa/question_38_0.png"
                ],
                [
                    "text" => "Средняя",
                    "image" => "img/anketa/question_38_1.png"
                ],
                [
                    "text" => "Низкая",
                    "image" => "img/anketa/question_38_2.png"
                ]
            ]
        ],
        39 => [
            "type" => "check",
            "label" => "И наконец длина джинсов / брюк",
            "answer" => [
                2,
                1,
                0
            ],
            "option" => [
                [
                    "text" => "Укороченные",
                    "image" => "img/anketa/question_39_0.png"
                ],
                [
                    "text" => "Стандартные",
                    "image" => "img/anketa/question_39_1.png"
                ],
                [
                    "text" => "Длинные",
                    "image" => "img/anketa/question_39_2.png"
                ]
            ]
        ],
        40 => [
            "type" => "check",
            "label" => "Какие юбки / платья вы предпочитаете?",
            "answer" => [
                1,
                0
            ],
            "option" => [
                [
                    "text" => "Мини",
                    "image" => "img/anketa/question_40_0.png"
                ],
                [
                    "text" => "Миди",
                    "image" => "img/anketa/question_40_1.png"
                ],
                [
                    "text" => "Макси",
                    "image" => "img/anketa/question_40_2.png"
                ]
            ]
        ],
        41 => [
            "type" => "check",
            "label" => "есть части тела, которые вы предпочитаете скрывать?",
            "answer" => [
                3
            ],
            "appeal" => ", ",
            "option" => [
                ["text" => "Руки"],
                ["text" => "Плечи"],
                ["text" => "Спина"],
                ["text" => "Декольте"],
                ["text" => "Талия"],
                ["text" => "Ноги"],
                ["text" => "Ничего не скрываю", "only_one" => true]
            ]
        ],
        42 => [
            "type" => "choice",
            "label" => "Вы больше любите джинсы с топом или платья?",
            "answer" => 0,
            "option" => [
                [
                    "text" => "Больше джинсы / футболки / свитера"
                ],
                [
                    "text" => "Больше платья"
                ],
                [
                    "text" => "Примерно 50/50"
                ]
            ]
        ],
        43 => [
            "type" => "check",
            "label" => "Расскажите, где вы обычно покупаете одежду. Это поможет стилисту точнее понять ваше предпочтения",
            "answer" => [
                2,
                1,
                0,
                5
            ],
            "option" => [
                ["text" => "Zara"],
                ["text" => "H&M"],
                ["text" => "Pull&Bear"],
                ["text" => "Topshop"],
                ["text" => "Mango"],
                ["text" => "Reserved"],
                ["text" => "Massimo Dutti"],
                ["text" => "Calvin Klein"],
                ["text" => "Twin Set"],
                ["text" => "Lamoda"],
                ["text" => "Wildberries"],
                ["text" => "12storeez"],
                ["text" => "Farfetch"],
                ["text" => "Asos"],
                ["text" => "Yoox"],
                ["text" => "Ничего из этого", "only_one" => true]
            ]
        ],
        44 => [
            "type" => "choice",
            "label" => "Проколоты ли у вас уши?",
            "answer" => 0,
            "option" => [
                [
                    "text" => "Да"
                ],
                [
                    "text" => "Нет"
                ]
            ]
        ],
        45 => [
            "type" => "choice",
            "label" => "Какие ювелирные изделия вы предпочитаете?",
            "answer" => 0,
            "option" => [
                [
                    "text" => "Серебро"
                ],
                [
                    "text" => "Золото"
                ],
                [
                    "text" => "50/50"
                ]
            ]
        ],
        46 => [
            "type" => "choice",
            "label" => "А к бижутерии готовы?",
            "answer" => 0,
            "option" => [
                [
                    "text" => "Да"
                ],
                [
                    "text" => "Нет"
                ]
            ]
        ],
        47 => [
            "type" => "check",
            "label" => "Каждая наша капсула собирается персонально для вас. Что вы хотите получить в капсуле прежде всего?",
            "answer" => [
                8
            ],
            "option" => [
                [
                    "text" => "Футболки / рубашки / блузы",
                    "image" => "img/anketa/question_47_0.png"
                ],
                [
                    "text" => "Свитера / кардиганы / пуловеры",
                    "image" => "img/anketa/question_47_1.png"
                ],
                [
                    "text" => "Пиджаки / жакеты",
                    "image" => "img/anketa/question_47_2.png"
                ],
                [
                    "text" => "Джинсы",
                    "image" => "img/anketa/question_47_3.png"
                ],
                [
                    "text" => "Брюки",
                    "image" => "img/anketa/question_47_4.png"
                ],
                [
                    "text" => "Шорты",
                    "image" => "img/anketa/question_47_5.png"
                ],
                [
                    "text" => "Платья",
                    "image" => "img/anketa/question_47_6.png"
                ],
                [
                    "text" => "Юбки",
                    "image" => "img/anketa/question_47_7.png"
                ],
                [
                    "text" => "Готова на все",
                    "image" => "img/anketa/question_47_8.png"
                ]
            ]
        ],
        48 => [
            "type" => "check",
            "label" => "Отлично! А какую одежду вы бы не хотели видеть в капсуле?",
            "answer" => [0, 1, 3],
            "option" => [
                [
                    "text" => "Футболки / рубашки / блузы",
                    "image" => "img/anketa/question_47_0.png"
                ],
                [
                    "text" => "Свитера / кардиганы / пуловеры",
                    "image" => "img/anketa/question_47_1.png"
                ],
                [
                    "text" => "Пиджаки / жакеты",
                    "image" => "img/anketa/question_47_2.png"
                ],
                [
                    "text" => "Джинсы",
                    "image" => "img/anketa/question_47_3.png"
                ],
                [
                    "text" => "Брюки",
                    "image" => "img/anketa/question_47_4.png"
                ],
                [
                    "text" => "Шорты",
                    "image" => "img/anketa/question_47_5.png"
                ],
                [
                    "text" => "Платья",
                    "image" => "img/anketa/question_47_6.png"
                ],
                [
                    "text" => "Юбки",
                    "image" => "img/anketa/question_47_7.png"
                ]
            ]
        ],
        49 => [
            "type" => "check",
            "label" => "какие аксессуары вы точно не хотите получить?",
            "answer" => [
                4
            ],
            "appeal" => ", ",
            "option" => [
                [
                    "text" => "Сумки",
                    "image" => "img/anketa/question_49_0.png"
                ],
                [
                    "text" => "Шарфы",
                    "image" => "img/anketa/question_49_1.png"
                ],
                [
                    "text" => "Серьги",
                    "image" => "img/anketa/question_49_2.png"
                ],
                [
                    "text" => "Колье / аксессуары на шею",
                    "image" => "img/anketa/question_49_3.png"
                ],
                [
                    "text" => "Ремни",
                    "image" => "img/anketa/question_49_4.png"
                ]
            ]
        ],
        50 => [
            "type" => "check",
            "label" => "Осталось совсем немного 😄<br><br>Какие цвета вы бы НЕ хотели видеть в капсуле?",
            "answer" => [1, 2],
            "option" => [
                [
                    "text" => "Белый",
                    "color" => "#F4F9F0"
                ],
                [
                    "text" => "Бежевый",
                    "color" => "#C4AA6D"
                ],
                [
                    "text" => "Лимонный",
                    "color" => "#F4FF52"
                ],
                [
                    "text" => "Голубой",
                    "color" => "#B4C7E7"
                ],
                [
                    "text" => "Лиловый",
                    "color" => "#FFC6FC"
                ],
                [
                    "text" => "Оранжевый",
                    "color" => "#FF8634"
                ],
                [
                    "text" => "Светло-серый",
                    "color" => "#B2AEB0"
                ],
                [
                    "text" => "Желтый",
                    "color" => "#FFD126"
                ],
                [
                    "text" => "Светло-зеленый",
                    "color" => "#99C544"
                ],
                [
                    "text" => "Бирюзовый",
                    "color" => "#1B9790"
                ],
                [
                    "text" => "Розовый",
                    "color" => "#FF9FAE"
                ],
                [
                    "text" => "Коралловый",
                    "color" => "#FF5B61"
                ],
                [
                    "text" => "Темно-серый",
                    "color" => "#474747"
                ],
                [
                    "text" => "Терракотовый",
                    "color" => "#C47C16"
                ],
                [
                    "text" => "Хакки",
                    "color" => "#716D14"
                ],
                [
                    "text" => "Электрик",
                    "color" => "#0014A0"
                ],
                [
                    "text" => "Фуксия",
                    "color" => "#ED0E92"
                ],
                [
                    "text" => "Красный",
                    "color" => "#FF0000"
                ],
                [
                    "text" => "Черный",
                    "color" => "#0D0D0D"
                ],
                [
                    "text" => "Коричневый",
                    "color" => "#5E2F07"
                ],
                [
                    "text" => "Изумрудный",
                    "color" => "#1F3D18"
                ],
                [
                    "text" => "Синий",
                    "color" => "#1F3965"
                ],
                [
                    "text" => "Фиолетовый",
                    "color" => "#632A8F"
                ],
                [
                    "text" => "Бордовый",
                    "color" => "#7F0000"
                ]
            ]
        ],
        51 => [
            "type" => "check",
            "label" => "Принты, которые вы точно НЕ хотели бы видеть в капсуле?",
            "answer" => [2],
            "option" => [
                [
                    "name" => "Анималистичные",
                    "items" => [
                        [
                            "text" => "Далматинца",
                            "image" => "img/anketa/question_51_1_1.png"
                        ],
                        [
                            "text" => "Зебра",
                            "image" => "img/anketa/question_51_1_2.png"
                        ],
                        [
                            "text" => "Змея",
                            "image" => "img/anketa/question_51_1_3.png"
                        ],
                        [
                            "text" => "Леопард",
                            "image" => "img/anketa/question_51_1_4.png"
                        ]
                    ]
                ],
                [
                    "name" => "Цветочные",
                    "items" => [
                        "4" => [
                            "text" => "Абстрактный",
                            "image" => "img/anketa/question_51_2_1.png"
                        ],
                        "5" => [
                            "text" => "Однотонный",
                            "image" => "img/anketa/question_51_2_2.png"
                        ],
                        "6" => [
                            "text" => "Крупный",
                            "image" => "img/anketa/question_51_2_3.png"
                        ],
                        "7" => [
                            "text" => "Мелкий",
                            "image" => "img/anketa/question_51_2_4.png"
                        ]
                    ]
                ],
                [
                    "name" => "Полоска",
                    "items" => [
                        "8" => [
                            "text" => "Вертикальная",
                            "image" => "img/anketa/question_51_3_1.png"
                        ],
                        "9" => [
                            "text" => "Геометричная",
                            "image" => "img/anketa/question_51_3_2.png"
                        ],
                        "10" => [
                            "text" => "Горизонтальная",
                            "image" => "img/anketa/question_51_3_3.png"
                        ],
                        "11" => [
                            "text" => "Диагональная",
                            "image" => "img/anketa/question_51_3_4.png"
                        ]
                    ]
                ],
                [
                    "name" => "Клетка",
                    "items" => [
                        "12" => [
                            "text" => "Мелкая",
                            "image" => "img/anketa/question_51_4_1.png"
                        ],
                        "13" => [
                            "text" => "Шотландская",
                            "image" => "img/anketa/question_51_4_2.png"
                        ],
                        "14" => [
                            "text" => "Гусиная лапка",
                            "image" => "img/anketa/question_51_4_3.png"
                        ],
                        "15" => [
                            "text" => "Средняя",
                            "image" => "img/anketa/question_51_4_4.png"
                        ]
                    ]
                ],
                [
                    "name" => "Горох",
                    "items" => [
                        "16" => [
                            "text" => "Крапинка",
                            "image" => "img/anketa/question_51_5_1.png"
                        ],
                        "17" => [
                            "text" => "Хаотичный",
                            "image" => "img/anketa/question_51_5_2.png"
                        ],
                        "18" => [
                            "text" => "Мелкий",
                            "image" => "img/anketa/question_51_5_3.png"
                        ],
                        "19" => [
                            "text" => "Классический",
                            "image" => "img/anketa/question_51_5_4.png"
                        ]
                    ]
                ],
                [
                    "name" => "Печатные",
                    "items" => [
                        "20" => [
                            "text" => "Фантазийный",
                            "image" => "img/anketa/question_51_6_1.png"
                        ],
                        "21" => [
                            "text" => "Анималистичный",
                            "image" => "img/anketa/question_51_6_2.png"
                        ],
                        "22" => [
                            "text" => "Фруктовый",
                            "image" => "img/anketa/question_51_6_3.png"
                        ],
                        "23" => [
                            "text" => "Абстрактный",
                            "image" => "img/anketa/question_51_6_4.png"
                        ]
                    ]
                ],
                [
                    "name" => "Узоры",
                    "items" => [
                        "24" => [
                            "text" => "Пейсли",
                            "image" => "img/anketa/question_51_7_1.png"
                        ],
                        "25" => [
                            "text" => "Геометричный",
                            "image" => "img/anketa/question_51_7_2.png"
                        ],
                        "26" => [
                            "text" => "Орнамент",
                            "image" => "img/anketa/question_51_7_3.png"
                        ],
                        "27" => [
                            "text" => "Акварель",
                            "image" => "img/anketa/question_51_7_4.png"
                        ]
                    ]
                ]
            ]
        ],
        52 => [
            "type" => "check",
            "label" => "Каких тканей нам следует избежать в подборке?",
            "answer" => [
                2
            ],
            "option" => [
                [
                    "text" => "Искусственный мех"
                ],
                [
                    "text" => "Искусственная кожа"
                ],
                [
                    "text" => "Натуральная кожа"
                ],
                [
                    "text" => "Полиэстер"
                ],
                [
                    "text" => "Шерсть"
                ]
            ]
        ],
        53 => [
            "type" => "text",
            "label" => "Расскажите стилисту в свободной форме дополнительные нюансы. (Например, какой вырез или, возможно, модель одежды вы точно не носите)",
            "answer" => "фывфыв ывфывфы ывфывфыв",
            "placeholder" => "Я не ношу джинсы с высокой талией"
        ],
        54 => [
            "type" => "choice",
            "label" => "Ура, финишная прямая! Поговорим о бюджете. Напомню, что покупать все вещи из подборки необязательно, можно взять даже одну. Сколько вы готовы потратить на блузу / рубашку?",
            "answer" => 0,
            "option" => [
                [
                    "text" => "2000-3000 рублей"
                ],
                [
                    "text" => "3000-4000 рублей"
                ],
                [
                    "text" => "4000-5000 рублей"
                ],
                [
                    "text" => "свыше 5000 рублей"
                ]
            ]
        ],
        55 => [
            "type" => "choice",
            "label" => "Сколько вы готовы потратить на свитер / джемпер / пуловер?",
            "answer" => 0,
            "option" => [
                [
                    "text" => "3000-4000 рублей"
                ],
                [
                    "text" => "4000-5000 рублей"
                ],
                [
                    "text" => "5000-7000 рублей"
                ],
                [
                    "text" => "свыше 7000 рублей"
                ]
            ]
        ],
        56 => [
            "type" => "choice",
            "label" => "Сколько вы готовы потратить на платья / сарафаны?",
            "answer" => 0,
            "option" => [
                [
                    "text" => "3000-4000 рублей"
                ],
                [
                    "text" => "4000-6000 рублей"
                ],
                [
                    "text" => "6000-8000 рублей"
                ],
                [
                    "text" => "свыше 8000 рублей"
                ]
            ]
        ],
        57 => [
            "type" => "choice",
            "label" => "Сколько вы готовы потратить на жакет / пиджак?",
            "answer" => 0,
            "option" => [
                [
                    "text" => "4000-5000 рублей"
                ],
                [
                    "text" => "5000-7000 рублей"
                ],
                [
                    "text" => "7000-10000 рублей"
                ],
                [
                    "text" => "свыше 10000 рублей"
                ]
            ]
        ],
        58 => [
            "type" => "choice",
            "label" => "Сколько вы готовы потратить на джинсы / брюки / юбки?",
            "answer" => 0,
            "option" => [
                [
                    "text" => "3000-4000 рублей"
                ],
                [
                    "text" => "4000-5000 рублей"
                ],
                [
                    "text" => "5000-7000 рублей"
                ],
                [
                    "text" => "свыше 7000 рублей"
                ]
            ]
        ],
        59 => [
            "type" => "choice",
            "label" => "Сколько вы готовы потратить на сумки?",
            "answer" => 0,
            "option" => [
                [
                    "text" => "3000-4000 рублей"
                ],
                [
                    "text" => "4000-6000 рублей"
                ],
                [
                    "text" => "6000-8000 рублей"
                ],
                [
                    "text" => "свыше 8000 рублей"
                ]
            ]
        ],
        60 => [
            "type" => "choice",
            "label" => "Сколько вы готовы потратить на другие аксессуары=> ремни, шарфы, платки?",
            "answer" => 1,
            "option" => [
                [
                    "text" => "1000-2000 рублей"
                ],
                [
                    "text" => "2000-3000 рублей"
                ],
                [
                    "text" => "свыше 3000 рублей"
                ]
            ]
        ],
        61 => [
            "type" => "choice",
            "label" => "Сколько вы готовы потратить на серьги / колье / браслеты?",
            "answer" => 0,
            "option" => [
                [
                    "text" => "1000-2000 рублей"
                ],
                [
                    "text" => "2000-3000 рублей"
                ],
                [
                    "text" => "свыше 3000 рублей"
                ]
            ]
        ],
        62 => [
            "type" => "check",
            "label" => "Для какой цели вы хотели бы подборку?",
            "answer" => [
                9,
                0,
                10
            ],
            "option" => [
                [
                    "text" => "На каждый день"
                ],
                [
                    "text" => "Одежда для дома"
                ],
                [
                    "text" => "Сделать гардероб женственнее"
                ],
                [
                    "text" => "Вечерние образы"
                ],
                [
                    "text" => "На работу и бизнес-встречи"
                ],
                [
                    "text" => "Добавить больше низа (юбки, джинсы, шорты)"
                ],
                [
                    "text" => "Добавить больше верха (футболки, пиджаки, свитшоты)"
                ],
                [
                    "text" => "Для путешествий, отпуска"
                ],
                [
                    "text" => "Хочется больше платьев"
                ],
                [
                    "text" => "Разнообразить гардероб"
                ],
                [
                    "text" => "Собрать новый гардероб"
                ],
                [
                    "text" => "Для прогулок с детьми, семейные вечера"
                ],
                [
                    "text" => "Для встреч с друзьями"
                ],
                [
                    "text" => "",
                    "label" => "Другое"
                ]
            ],
            "placeholder" => "Другое"
        ],
        63 => [
            "type" => "text",
            "label" => "Нашей курьерской службе потребуется ваш адрес=> город, улица, дом и корпус",
            "answer" => "Подольск, проспект Ленина, 128/24 ввв",
            "placeholder" => "Подольск, проспект Ленина, 128/24"
        ],
        64 => [
            "type" => "text",
            "label" => "когда вам удобно было бы получить доставку? Мы соберем подборку за 6 дней",
            "answer" => "2021-10-03",
            "appeal" => ", "
        ],
        65 => [
            "type" => "check",
            "label" => "В какое время вам было бы удобнее получить подборку?",
            "answer" => [0, 1],
            "option" => [
                [
                    "link" => 0,
                    "text" => "10=>00 - 14=>00"
                ],
                [
                    "link" => 0,
                    "text" => "14=>00 - 18=>00"
                ],
                [
                    "link" => 0,
                    "text" => "18=>00 - 22=>00"
                ],
                [
                    "link" => 1,
                    "text" => "12=>00 - 18=>00"
                ],
                [
                    "link" => 1,
                    "text" => "18=>00 - 22=>00"
                ]
            ]
        ],
        66 => [
            "type" => "check",
            "label" => "А во сколько забрать на следующий день?",
            "answer" => [1, 2],
            "option" => [
                [
                    "link" => 0,
                    "text" => "10=>00 - 14=>00"
                ],
                [
                    "link" => 0,
                    "text" => "14=>00 - 18=>00"
                ],
                [
                    "link" => 0,
                    "text" => "18=>00 - 22=>00"
                ],
                [
                    "link" => 1,
                    "text" => "12=>00 - 18=>00"
                ],
                [
                    "link" => 1,
                    "text" => "18=>00 - 22=>00"
                ]
            ]
        ],
        67 => [
            "type" => "text",
            "label" => "Укажите вашу фамилию. Она понадобятся нам для доставки",
            "answer" => "Имя Имя",
            "labelC" => "Укажите ваши имя и фамилию. Они понадобятся нам для доставки",
            "placeholder" => "Имя"
        ],
        68 => [
            "type" => "text",
            "label" => "Стилисту будет проще подобрать для вас одежду по цвету, если вы покажете свои фото. Можно оставить ссылку или ник в соц.сетях или прикрепить фото в следующем вопросе 🙂",
            "answer" => "@the_capsula @the_capsula",
            "placeholder" => "@the_capsula"
        ],
        69 => [
            "type" => "choice",
            "label" => "и последнее. Как вы о нас узнали?",
            "answer" => 1,
            "appeal" => ", ",
            "option" => [
                [
                    "text" => "Instagram"
                ],
                [
                    "text" => "Совет подруги"
                ],
                [
                    "text" => "Поиск в Google / Yandex"
                ],
                [
                    "text" => "Увидела у блогера"
                ],
                [
                    "text" => "Статья в интернете"
                ],
                [
                    "text" => "Рассылка"
                ]
            ]
        ],
        70 => [
            "type" => "text",
            "label" => "Ваш вес (кг)",
            "answer" => "52",
            "placeholder" => "Ваш вес (кг)"
        ],
        71 => [
            "type" => "choice",
            "label" => "остались данные для доставки. Напоминаем=> вы не оплачиваете вещи заранее. Мы оставим их у вас на сутки на примерку, вы оплатите только то, что захотите 🙂<br><br>В какой город доставляем капсулу?",
            "answer" => 0,
            "appeal" => ", ",
            "option" => [
                [
                    "text" => "Москва и Московская область"
                ],
                [
                    "text" => "Санкт-Петербург и Ленинградская область"
                ],
                [
                    "text" => "Другой город"
                ]
            ]
        ],
        72 => [
            "type" => "choice",
            "label" => "Хотели бы вы загрузить несколько фото для вашего стилиста? (Необязательно)",
            "answer" => 1,
            "option" => [
                [
                    "text" => "Да, готова прикрепить фото"
                ],
                [
                    "text" => "Моих фото в социальных сетях достаточно"
                ]
            ]
        ],
        73 => [
            "type" => "files",
            "label" => ""
        ],
        74 => [
            "type" => "text",
            "label" => "Нашей курьерской службе потребуется ваш адрес=> город, улица, дом и корпус",
            "answer" => "г Москва, ул Вавилова, д 18 г Москва, ул Вавилова, д 18",
            "placeholderMO" => "г Москва, ул Вавилова, д 18",
            "placeholderSP" => "г Санкт-Петербург, ул ул. Вавиловых, д 14"
        ],
        75 => [
            "type" => "text",
            "label" => "Нашей курьерской службе потребуется ваш адрес=> город, улица, дом и корпус",
            "answer" => "Квартира №5",
            "placeholder" => "Квартира / номер офиса"
        ],
        76 => [
            "type" => "text",
            "label" => "Нашей курьерской службе потребуется ваш адрес=> город, улица, дом и корпус",
            "answer" => "номер офиса 5",
            "placeholder" => "Квартира / номер офиса"
        ],
        77 => [
            "type" => "text",
            "label" => "Нашей курьерской службе потребуется ваш адрес=> город, улица, дом и корпус",
            "answer" => "Дополнительный комментарий Дополнительный комментарий",
            "placeholder" => "Дополнительный комментарий"
        ],
        78 => [
            "type" => "choice",
            "label" => "Привет, меня зовут Даша, я ваш персональный стилист. Расскажите, с чем Capsula может вам помочь?",
            "answer" => 2,
            "option" => [
                [
                    "text" => "Порадовать себя",
                    "emoji" => "&#127873;"
                ],
                [
                    "text" => "Обновить гардероб",
                    "emoji" => "&#128717;"
                ],
                [
                    "text" => "Получить совет от стилиста",
                    "emoji" => "&#128105;"
                ],
                [
                    "text" => "Сэкономить время",
                    "emoji" => "&#8987;"
                ]
            ]
        ],
        79 => [
            "type" => "text",
            "answer" => "Фамилия Фамилия",
            "placeholder" => "Фамилия"
        ],
        80 => [
            "type" => "choice",
            "label" => "Как вам было бы удобнее получить капсулу?",
            "answer" => 1,
            "appeal" => "",
            "option" => [
                [
                    "text" => "Доставка курьером"
                ],
                [
                    "text" => "Доставка в пункт выдачи"
                ]
            ]
        ],
        81 => [
            "type" => "text",
            "label" => "В каком пункте выдачи вы бы хотели забрать капсулу? Подтвердите свой выбор, нажав на кнопку \"Выбрать отделение\" 🙂",
            "answer" => "50241"
        ],
        82 => [
            "type" => "text",
            "label" => "У нас работает доставка в пункт выдачи. Введите ваш город",
            "answer" => "город город",
            "placeholder" => "Введите город"
        ],
        83 => [
            "type" => "choice",
            "label" => "на какой сезон вы хотите подборку?",
            "answer" => 3,
            "appeal" => ", ",
            "option" => [
                [
                    "text" => "Лето"
                ],
                [
                    "text" => "Осень"
                ],
                [
                    "text" => "Зима"
                ],
                [
                    "text" => "Весна"
                ],
                [
                    "text" => "Демисезон"
                ]
            ]
        ],
        84 => [
            "type" => "text",
            "answer" => "Отчество Отчество",
            "placeholder" => "Отчество"
        ]
    ];

    public function __construct($step = 0, $page = 1)
    {
        $this->page = $page;
        $this->step = $step;
    }

    public function make()
    {

        if ($this->page == 1) {
            $this->headers();
        }


        if($this->step == 0) {
            $questionnaire = Questionnaire::with('client')->get();
        } else {
            $this->total = Questionnaire::count();
            $questionnaire = Questionnaire::with('client')->paginate($this->step, ['*'], '', $this->page);
//            $questionnaire = Questionnaire::with('client')->limit($this->step)->offset(($this->page - 1) * $this->step)->get();
        }

//        $arr = [
//            '82a310ca-17d7-4f2c-bd49-9c99811e0b1b',
//            'b63108fd-9f8f-48ac-8e60-0dbacc345581',
////            'b63108fd-9f8f-48ac-8e60-0dbacc345581',
//            '2335efbf-cf10-47db-82e2-0891fb7cc7bf',
//            '510fabf9-c021-418d-9171-f7aac905c96e',
//            '70183de7-5542-477d-ac80-c7ba990ffcf1',
////            '82a310ca-17d7-4f2c-bd49-9c99811e0b1b'
//        ];
//        $questionnaire = Questionnaire::with('client')->whereIn('uuid', $arr)->get();

        foreach ($questionnaire as $rowNr => $q) {


            try {

                // заголовки
//                if($this->page == 1 && $this->headings) {

//                    array_shift($this->rowHeader) ;
//                    array_shift($this->rowHeader) ;
//                    array_shift($this->rowHeader) ;
//                    array_shift($this->rowHeader) ;
//                    array_shift($this->rowHeader) ;
//                    array_shift($this->rowHeader) ;
//                    $this->answers($q->data, $rowNr);
//                    array_push($this->result, $this->rowHeader);
//                }

                // Тело
                if ($q->data && gettype($q->data) === 'array') {

                    if (array_key_exists('anketa', $q->data)) {
                        $this->answers($q->data, $rowNr);
                    } else {
                        $this->newAnswers($q->data, $rowNr);
                    }

                    array_push($this->result, $this->row);
                    $this->row = [];
                }

            } catch (\Exception $e) {
//                dump($rowNr, $q->uuid, $q->data, $e);
            }

        }


//        $filename = 'anketa_export.csv';
//        header('Content-Type: application/csv');
//        header('Content-Disposition: attachment; filename="'.$filename.'";');
//
//        $f = fopen("php://output", 'w');
//        foreach ($this->result as $line) {
//            fputcsv($f, $line, ';');
//        }
//        fclose($f);


        $this->fill($this->result,'a+');

        return [
//            'current_page' => $questionnaire->currentPage(),
            'current_page' => $this->page,
//            'last_page' => $questionnaire->lastPage(),
            'last_page' => ceil($this->total/$this->step) ,
//            'total' => $questionnaire->total(),
            'total' => $this->total,
//            'perPage' => $questionnaire->perPage(),
            'perPage' => $this->step,
//            'data' => $this->result
        ];


    }


    private function headers()
    {
        $rt = [];
        $rt[0] = 'coupon';
        $rt[1] = 'g_step';
        $rt[2] = 'anketa_id';
        $rt[3] = 'anketa_code';
        $rt[4] = 'coupon_result';
        $rt[5] = 'anketa_amount';

        foreach ($this->old as $keyOld => $old) {
            $rt[$keyOld + 6] = $old['label'] ?? "";
        }
        $this->fill([$rt],'w+');

//        array_push($this->result, $rt);

//        dump($rt);

    }

    private function answers($data, $rowNr)
    {

        $topItems = [
            ['label' => 'coupon', 'answer' => $data['coupon'] ?? ""],
            ['label' => 'g_step', 'answer' => $data['g_step'] ?? ""],
            ['label' => 'anketa_id', 'answer' => $data['anketa_id'] ?? ""],
            ['label' => 'anketa_code', 'answer' => $data['anketa_code'] ?? ""],
            ['label' => 'coupon_result', 'answer' => $data['coupon_result'] ?? ""],
            ['label' => 'anketa_amount', 'answer' => $data['anketa']['amount'] ?? ""],
        ];

        foreach ($topItems as $items) {
//            $this->headings
//                ? array_push($this->rowHeader, $items['label'])
//                :
            array_push($this->row, $items['answer']);
        }

        foreach ($data['anketa']['question'] as $k => $question) {
            if ($question['type'] == 'text') {
                $this->typeText($question, $rowNr, $k);
            } elseif ($question['type'] == 'choice') {
                $this->typeChoice($question, $rowNr, $k);
            } elseif ($question['type'] == 'check') {
                $this->typeCheck($question, $rowNr, $k);
            }
        }

        // заколовки только один раз
        $this->headings = false;
    }

    private function newAnswers($data, $rowNr)
    {

        try {
            $topItems = [
                ['label' => 'coupon', 'answer' => $data['coupon'] ?? ""],
                ['label' => 'g_step', 'answer' => $data['g_step'] ?? ""],
                ['label' => 'anketa_id', 'answer' => $data['anketa_id'] ?? ""],
                ['label' => 'anketa_code', 'answer' => $data['anketa_code'] ?? ""],
                ['label' => 'coupon_result', 'answer' => $data['coupon_result'] ?? ""],
                ['label' => 'anketa_amount', 'answer' => $data['amount'] ?? ""],
            ];

            foreach ($topItems as $k => $items) {
//                $this->row[(int)$k] = $items['answer'];
                array_push($this->row, $items['answer']);
            }
            foreach ($data as $k => $ans) if (is_numeric($k)) {
                try {

                    if (is_array($ans)) {
                        if (count($ans)) {

                            if ($this->old[$k]['type'] == 'choice') {
                                $this->row[(int)$k + 6] = $this->old[$k]['option'][$ans[0]]['text'];
                            }

                            if ($this->old[$k]['type'] == 'check') {
                                $arrOldAns = [];
                                foreach ($ans as $v) {
                                    if (array_key_exists('text', $this->old[$k]['option'][$v])) {
                                        $arrOldAns[] = $this->old[$k]['option'][$v]['text']; //
                                    } elseif (array_key_exists('name', $this->old[$k]['option'][$v])) {
                                        $arrOldAns[] = $this->old[$k]['option'][$v]['name'];
                                    } else {
                                        $arrOldAns[] = $v;
                                    }
                                }
                                $this->row[(int)$k + 6] = implode(PHP_EOL, $arrOldAns) ?? "";
                            }

                        } else {
                            $this->row[(int)$k + 6] = "";
                        }


                    } else {
                        $this->row[(int)$k + 6] = $ans;
                    }


                } catch (Exception $e) {
//                    dump($e);
                }
            }

            // заколовки только один раз
            $this->headings = false;
        } catch (\Exception $e) {
//            dd($e);
        }


    }

    private function typeText($question, $rowNr, $k)
    {
        try {
//            $this->headings
//                ? array_push($this->rowHeader, $question['label'] ?? "")
//                :
            array_push($this->row, $question['answer'] ?? "");
        } catch (Exception $e) {
//            dump($e);
        }
    }


    private function typeChoice($question, $rowNr, $k)
    {
        try {
//            $this->headings
//                ? array_push($this->rowHeader, $question['label'] ?? "")
//                :
            array_push($this->row, $question['option'][$question['answer']]['text'] ?? "");
        } catch (Exception $e) {
//            dump($e);
        }
    }

    private function typeCheck($question, $rowNr, $k)
    {

        try {
//            if($this->headings){
//                array_push($this->rowHeader, $question['label'] ?? "");
//            } else {
            $answer = [];

            try {
                if (gettype($question['answer']) === 'array') {
                    foreach ($question['answer'] as $check) {
                        if (isset($question['option'][$check]['text'])) {

                            $answer[] = $question['option'][$check]['text'];
                        }
                    }
                    array_push($this->row, implode(PHP_EOL, $answer) ?? "");
                } else {
                    array_push($this->row, $question['option'][$question['answer']]['text'] ?? "");
                }

            } catch (\Exception $e) {

//                    dump($rowNr, $k, $question['answer'], gettype($question['answer']), $e);
            }

//            }
        } catch (Exception $e) {
//            dump($e);
        }
    }


    private function  fill($arr,$mode) {
        $f = fopen($_SERVER['DOCUMENT_ROOT'] . $this->link, $mode);
        try {
            foreach ($arr as $line) {
                fputcsv($f, $line);
            }
        } catch (\Exception $e) {
//            Log::error($arr,[$e]);
        }

    }
}

