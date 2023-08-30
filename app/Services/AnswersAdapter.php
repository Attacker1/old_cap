<?php

namespace App\Services;

use Illuminate\Support\Collection;

class AnswersAdapter
{
    private array $answers;
    private array $questions;

    public function __construct($source = 'default')
    {
        switch ($source) {
            case 'default': self::setQuestions();
                break;

            case 'sber': self::setOldQuestions();
                break;

            case 'reanketa': self::setReanketaQuestions();

            default: self::setQuestions();
        }
    }

    public function get($questionnaire){

        // сюдой общую реализацию

    }

    public function setSber($array){

        $item = (object)[];
        foreach ($this->answers as $k1=>$v1) {
            foreach ($array as $k => $v) {
                if ($v['id'] == $k1) {
                    $item->{(string) $v['id']} = $v['answer'];
                    $this->answers[(string) $v['id']] = $v['answer'] ?? [] ;
                }
                elseif (empty($item->{(string) $k1}))
                    $item->{(string) $k1} = $v1;

            }
        }

        return $item;

    }

    //используется в Офис- показ анкеты и в ЛК клиента показ анкеты
    public function setAnketaShow($array) {

        for ($i = 0; $i < count($this->questions); $i++) {
            if (isset($array[$i])) {
                $this->questions[$i]['answer'] = $array[$i];
            }
            else {
                if (isset($this->answers[$i]))
                    $this->questions[$i]['answer'] = $this->answers[$i];
                else $this->questions[$i]['answer'] = NULL;
            }
        }
        return $this->questions;
    }

    public function setReanketaQuestions() {
        $this->answers = [

            //начальные вопросы
            'wishToStylist' => '',
            'capsulaFirstOfAll' => [],
            'capsulaNotFirstOfAll' => [],
            'capsulaNotWantAccessories' => [],

            //что сменить
            'changeElse' => 0,
            'whatChangeElse' => [],

            //размеры
            'bioChest' => '',
            'bioWaist' => '',
            'bioHips' => '',
            'sizeTop' => [],
            'sizeBottom' => [],

            //цены на вещи
            'howMuchToSpendOnBlouseShirt' => NULL,
            'howMuchToSpendOnSweaterJumperPullover' => NULL,
            'howMuchToSpendOnDressesSundresses' => NULL,
            'howMuchToSpendOnJacket' => NULL,
            'howMuchToSpendOnJeansTrousersSkirts' => NULL,
            'howMuchToSpendOnBags' => NULL,
            'howMuchToSpendOnBeltsScarvesShawls' => NULL,
            'howMuchToSpendOnEarringsNecklacesBracelets' => NULL,

            //цветовая гамма
            'noColor' => [],
            'choosingPalletes25' => [],

            //образы
            'choosingStyle4' => [],
            'choosingStyle5' => [],
            'choosingStyle6' => [],
            'choosingStyle7' => [],
            'choosingStyle8' => [],
            'choosingStyle16' => [],
            'choosingStyle17' => [],
            'choosingStyle18' => [],
            'choosingStyle19' => [],
            'choosingStyle20' => [],


            //принты
            'printsDislike' => [],

            //
            'contacts' => '',
            'contactsPhone' => '',

            'chooseCity' => 'NULL',
            'boxberryCityOther' => 'NULL',
            'deliveryType' => NULL,
            'boxberryPoint' => NULL,
            'boxberryAddress' => '',
            'boxberryCity' =>  '',

            'deliveryDate' => date('Y-m-d', strtotime('+6 days')),
            'deliveryTime' => '',
            'deliveryBackTime' => '',

            'nameForDelivery'=> '',
            'secondNameForDelivery' => '',
            'patronomicsForDelivery' => '',

            //не используется в повторной

            'addressCityStreet' => '',

            'addressFlatNumber' => '',

            'addressComments' => '',

            'paymentAmount' => 990
        ];
        $this->questions = [

            'disclaimer' => [0 => '<p class="font-size-lg">С 20 августа мы начнем доставлять во все города-миллионники.</p><p class="font-size-lg">Заполняя анкету, вы даете согласие на обработку персональных данных: <a href="https://thecapsula.ru/privacy">https://thecapsula.ru/privacy</a> и принимаете оферту <a href="https://thecapsula.ru/publicoffer">https://thecapsula.ru/publicoffer</a></p>',
                1 => 'Посмотрите еще образы',
                2 => '',
                3 => 'Расскажите нам больше о себе, чтобы стилист сделал подборку персонально под вас',
                4 => 'А теперь расскажите, какую одежду вы предпочитаете носить',
                5 => '',
                6 => 'Вам не нужно вносить предоплату за все вещи, вы заплатите только за то, что вам подойдет',
                7 => '',
                8 => 'Для получения капсулы в пункте выдачи вам понадобится паспорт, не забудьте взять его с собой.',
                9 => '<p class="font-size-lg">Мы собираем капсулу только для размерной сетки от 40 до 48.<br> Если ваш размер меньше 40 или больше 48, вы также можете заполнить анкету и мы напишем вам первой, когда начнем работать с новой линейкой.</p>',
            ],

            'question' => [

                //начальные вопросы
                'wishToStylist' => [
                    'type' => 'text',
                    'label' => 'Пожалуйста, напишите пожелания по новому заказу для вашего стилиста. Расскажите что вы хотели бы видеть в подборе, что вы ждете от стилиста, или чего бы хотели избежать.',
                    'placeholder' => 'Прежде всего мне нужны комфортные вещи, но особое внимание нужно обратить на то, что я не люблю V-образный вырез и геометрические принты',
                ],
                'capsulaFirstOfAll' => [
                    'type' => 'check',
                    'label' => 'Каждая наша капсула собирается персонально для вас. Что вы хотите получить в капсуле прежде всего?',
                    'option' => [
                        0 => ['text' => 'Футболки / рубашки / блузы', 'image' => 'img/anketa/question_47_0.png'],
                        1 => ['text' => 'Свитера / кардиганы / пуловеры', 'image' => 'img/anketa/question_47_1.png'],
                        2 => ['text' => 'Пиджаки / жакеты', 'image' => 'img/anketa/question_47_2.png'],
                        3 => ['text' => 'Джинсы', 'image' => 'img/anketa/question_47_3.png'],
                        4 => ['text' => 'Брюки', 'image' => 'img/anketa/question_47_4.png'],
                        5 => ['text' => 'Шорты', 'image' => 'img/anketa/question_47_5.png'],
                        6 => ['text' => 'Платья', 'image' => 'img/anketa/question_47_6.png'],
                        7 => ['text' => 'Юбки', 'image' => 'img/anketa/question_47_7.png'],
                        8 => ['text' => 'Готова на все', 'image' => 'img/anketa/question_47_8.png'],
                    ]
                ],
                'capsulaNotFirstOfAll' => [
                    'type' => 'check',
                    'label' => 'Отлично! А какую одежду вы бы не хотели видеть в капсуле?',
                    'option' => [
                        0 => ['text' => 'Футболки / рубашки / блузы', 'image' => 'img/anketa/question_47_0.png'],
                        1 => ['text' => 'Свитера / кардиганы / пуловеры', 'image' => 'img/anketa/question_47_1.png'],
                        2 => ['text' => 'Пиджаки / жакеты', 'image' => 'img/anketa/question_47_2.png'],
                        3 => ['text' => 'Джинсы', 'image' => 'img/anketa/question_47_3.png'],
                        4 => ['text' => 'Брюки', 'image' => 'img/anketa/question_47_4.png'],
                        5 => ['text' => 'Шорты', 'image' => 'img/anketa/question_47_5.png'],
                        6 => ['text' => 'Платья', 'image' => 'img/anketa/question_47_6.png'],
                        7 => ['text' => 'Юбки', 'image' => 'img/anketa/question_47_7.png'],
                    ],
                    'key' => 48
                ],
                'accessoriesDoNotWant' => [
                    'type' => 'check',
                    'appeal' => ', ',
                    'label' => 'какие аксессуары вы точно не хотите получить?',
                    'option' => [	0 => ['text' => 'Сумки', 'image' => 'img/anketa/question_49_0.png'],
                        1 => ['text' => 'Шарфы', 'image' => 'img/anketa/question_49_1.png'],
                        2 => ['text' => 'Серьги', 'image' => 'img/anketa/question_49_2.png'],
                        3 => ['text' => 'Колье / аксессуары на шею', 'image' => 'img/anketa/question_49_3.png'],
                        4 => ['text' => 'Ремни', 'image' => 'img/anketa/question_49_4.png'],
                    ],
                    'key' => 49
                ],

                //что сменить
                'changeElse' => [
                    'type' => 'choice',
                    'label' => 'Хотели бы вы что-то еще изменить в своей анкете?',
                    'option' => [
                        0 => ['text' => 'Да'],
                        1 => ['text' => 'Нет'],
                    ],
                ],
                'whatChangeElse' => [
                    'type' => 'check',
                    'label' => 'Что хотите поменять? Можно выбрать несколько пунктов',
                    'option' => [
                        0 => ['text' => 'Размеры'],
                        1 => ['text' => 'Цены на вещи'],
                        2 => ['text' => 'Цветовые гаммы'],
                        3 => ['text' => 'Стили(образы)'],
                        4 => ['text' => 'Принты']
                    ]
                ],

                //размеры
                'chest' => [
                    'type' => 'text',
                    'label' => 'Замеры помогут стилисту точнее определить размер. Отвечать необязательно 🙂',
                    'placeholder' => 'Объем груди (см)',
                    'slug' => 'chestWaistHips',
                    'slug_order' => 0,
                    'key' => 32
                ],
                'waist' => [
                    'type' => 'text',
                    'label' => 'Ваш объем талии (см) (необязательно)',
                    'placeholder' => 'Объем талии (см)',
                    'slug' => 'chestWaistHips',
                    'slug_order' => 1,
                    'key' => 33
                ],
                'hips' => [
                    'type' => 'text',
                    'label' => 'Ваш объем бедер (см) (необязательно)',
                    'placeholder' => 'Объем бедер (см)',
                    'slug' => 'chestWaistHips',
                    'slug_order' => 2,
                    'key' => 34
                ],
                'sizeTop' => [
                    'type' => 'check',
                    'label' => 'Класс, спасибо!<br>Дальше несколько вопросов о ваших размерах<br>Российский размер верха (платья, блузы, жакеты, свитеры и др.)',
                    'option' => [
                        0 => ['text' => '38 (XXS)'],
                        1 => ['text' => '40 (XXS / XS)'],
                        2 => ['text' => '42 (XS)'],
                        3 => ['text' => '44 (S)'],
                        4 => ['text' => '46 (M)'],
                        5 => ['text' => '48 (M / L)'],
                        6 => ['text' => '50 (L)'],
                        7 => ['text' => '52 (XL)'],
                        8 => ['text' => '54 (XL)'],
                        9 => ['text' => '56 (XXL)'],
                    ],
                    'key' => 30
                ],
                'sizeBottom' => [
                    'type' => 'check',
                    'label' => 'Российский размер низа (брюки, джинсы, юбки)',
                    'option' => [
                        0 => ['text' => '38 (XXS)'],
                        1 => ['text' => '40 (XXS / XS или 25)'],
                        2 => ['text' => '42 (XS или 26, 27)'],
                        3 => ['text' => '44 (S или 28)'],
                        4 => ['text' => '46 (M или 29, 30)'],
                        5 => ['text' => '48 (M / L или 31, 32)'],
                        6 => ['text' => '50 (L или 33, 34)'],
                        7 => ['text' => '52 (XL или 35, 36)'],
                        8 => ['text' => '54 (XL или 38)'],
                        9 => ['text' => '56 (XXL или 40)'],
                    ],
                    'key' => 31
                ],

                //цены на вещи
                'howMuchToSpendOnBlouseShirt' => [
                    'type' => 'choice',
                    'label' => 'Ура, финишная прямая! Поговорим о бюджете. Напомню, что покупать все вещи из подборки необязательно, можно взять даже одну. Сколько вы готовы потратить на блузу / рубашку?',
                    'option' => [
                        0 => ['text' => '2000-3000 рублей'],
                        1 => ['text' => '3000-4000 рублей'],
                        2 => ['text' => '4000-5000 рублей'],
                        3 => ['text' => 'свыше 5000 рублей'],
                    ],
                    'key' => 54
                ],
                'howMuchToSpendOnSweaterJumperPullover' => [
                    'type' => 'choice',
                    'label' => 'Сколько вы готовы потратить на свитер / джемпер / пуловер?',
                    'option' => [
                        0 => ['text' => '3000-4000 рублей'],
                        1 => ['text' => '4000-5000 рублей'],
                        2 => ['text' => '5000-7000 рублей'],
                        3 => ['text' => 'свыше 7000 рублей'],
                    ],
                    'key' => 55
                ],
                'howMuchToSpendOnDressesSundresses' => [
                    'type' => 'choice',
                    'label' => 'Сколько вы готовы потратить на платья / сарафаны?',
                    'option' => [
                        0 => ['text' => '3000-4000 рублей'],
                        1 => ['text' => '4000-6000 рублей'],
                        2 => ['text' => '6000-8000 рублей'],
                        3 => ['text' => 'свыше 8000 рублей'],
                    ],
                    'key' => 56
                ],
                'howMuchToSpendOnJacket' => [
                    'type' => 'choice',
                    'label' => 'Сколько вы готовы потратить на жакет / пиджак?',
                    'option' => [
                        0 => ['text' => '4000-5000 рублей'],
                        1 => ['text' => '5000-7000 рублей'],
                        2 => ['text' => '7000-10000 рублей'],
                        3 => ['text' => 'свыше 10000 рублей'],
                    ],
                    'key' => 57
                ],
                'howMuchToSpendOnJeansTrousersSkirts' => [
                    'type' => 'choice',
                    'label' => 'Сколько вы готовы потратить на джинсы / брюки / юбки?',
                    'option' => [
                        0 => ['text' => '3000-4000 рублей'],
                        1 => ['text' => '4000-5000 рублей'],
                        2 => ['text' => '5000-7000 рублей'],
                        3 => ['text' => 'свыше 7000 рублей'],
                    ],
                    'key' => 58
                ],
                'howMuchToSpendOnBags' => [
                    'type' => 'choice',
                    'label' => 'Сколько вы готовы потратить на сумки?',
                    'option' => [
                        0 => ['text' => '3000-4000 рублей'],
                        1 => ['text' => '4000-6000 рублей'],
                        2 => ['text' => '6000-8000 рублей'],
                        3 => ['text' => 'свыше 8000 рублей'],
                    ],
                    'key' => 59
                ],
                'howMuchToSpendOnBeltsScarvesShawls' => [
                    'type' => 'choice',
                    'label' => 'Сколько вы готовы потратить на другие аксессуары: ремни, шарфы, платки?',
                    'option' => [	0 => ['text' => '1000-2000 рублей'],
                        1 => ['text' => '2000-3000 рублей'],
                        2 => ['text' => 'свыше 3000 рублей'],
                    ],
                    'key' => 60
                ],
                'howMuchToSpendOnEarringsNecklacesBracelets' => [
                    'type' => 'choice',
                    'label' => 'Сколько вы готовы потратить на серьги / колье / браслеты?',
                    'option' => [
                        0 => ['text' => '1000-2000 рублей'],
                        1 => ['text' => '2000-3000 рублей'],
                        2 => ['text' => 'свыше 3000 рублей'],
                    ],
                    'key' => 61
                ],

                //цветовая гамма
                'noColor' => [
                    'type' => 'check',
                    'label' => 'Осталось совсем немного 😄<br><br>Какие цвета вы бы НЕ хотели видеть в капсуле?',
                    'option' => [	0 => ['text' => 'Белый','color' => '#F4F9F0'],
                        1 => ['text' => 'Бежевый','color' => '#C4AA6D'],
                        2 => ['text' => 'Лимонный','color' => '#F4FF52'],
                        3 => ['text' => 'Голубой','color' => '#B4C7E7'],
                        4 => ['text' => 'Лиловый','color' => '#FFC6FC'],
                        5 => ['text' => 'Оранжевый','color' => '#FF8634'],
                        6 => ['text' => 'Светло-серый','color' => '#B2AEB0'],
                        7 => ['text' => 'Желтый','color' => '#FFD126'],
                        8 => ['text' => 'Светло-зеленый','color' => '#99C544'],
                        9 => ['text' => 'Бирюзовый','color' => '#1B9790'],
                        10 => ['text' => 'Розовый','color' => '#FF9FAE'],
                        11 => ['text' => 'Коралловый','color' => '#FF5B61'],
                        12 => ['text' => 'Темно-серый','color' => '#474747'],
                        13 => ['text' => 'Терракотовый','color' => '#C47C16'],
                        14 => ['text' => 'Хакки','color' => '#716D14'],
                        15 => ['text' => 'Электрик','color' => '#0014A0'],
                        16 => ['text' => 'Фуксия','color' => '#ED0E92'],
                        17 => ['text' => 'Красный','color' => '#FF0000'],
                        18 => ['text' => 'Черный','color' => '#0D0D0D'],
                        19 => ['text' => 'Коричневый','color' => '#5E2F07'],
                        20 => ['text' => 'Изумрудный','color' => '#1F3D18'],
                        21 => ['text' => 'Синий','color' => '#1F3965'],
                        22 => ['text' => 'Фиолетовый','color' => '#632A8F'],
                        23 => ['text' => 'Бордовый','color' => '#7F0000'],
                    ],
                    'key' => 50
                ],
                'choosingPalletes25' => [
                    'type' => 'check',
                    'label' => 'Какая цветовая гамма вам ближе? Можно выбрать несколько 🙂',
                    'option' => [
                        0 => ['text' => 'Серый монохром', 'image' => 'img/anketa/question_25_0.png',
                            'colors' => ['#FFFFFF', '#3B3838', '#757070', '#000000', '#CFCDCD', '#F1F1F1', '#7F7F7F', '#404040', '#DADADA'] ],
                        1 => ['text' => 'Бежевый монохром', 'image' => 'img/anketa/question_25_1.png',
                            'colors' => ['#BCA181', '#786058', '#F0DECB', '#D5CBBD', '#E5D1B1', '#392D2B', '#5B3022', '#FDF2E4', '#BC9887', ]],
                        2 => ['text' => 'Пастельная', 'image' => 'img/anketa/question_25_2.png',
                            'colors' => ['#D0DAF5', '#E5EFDB', '#F1D7CF', '#FDF2D0', '#F7E5D8', '#E3E4E6', '#EED4EC', '#DBD3C7', '#E0EBF6', ]],
                        3 => ['text' => 'Мягкая', 'image' => 'img/anketa/question_25_3.png',
                            'colors' => ['#484D67', '#D5CBBC', '#9C6F73', '#D1918E', '#5A7F6C', '#796F64', '#6C394D', '#B9B9AC', '#456C79',  ]],
                        4 => ['text' => 'Тёмная', 'image' => 'img/anketa/question_25_4.png',
                            'colors' => ['#151414', '#6C394D', '#3D2D2E', '#252626', '#4F555B', '#5B3022', '#5C1311', '#3F562A', '#000E54',  ]],
                        5 => ['text' => 'Яркая', 'image' => 'img/anketa/question_25_5.png',
                            'colors' => ['#F2EE50', '#3B7D7C', '#BB4239', '#AA3B80', '#312DB5', '#94B73D', '#448EC7', '#72F8D9', '#D6722E',  ]],
                    ],
                    'key' => 25
                ],

                //образы
                'choosingStyle4' => [
                    'type' => 'check',
                    'label' => 'Отлично! Далее я покажу несколько образов. Расскажите свое мнение о них, чтобы мы лучше поняли ваши предпочтения',
                    'image' => 'img/anketa/question_4_1.jpg',
                    'option' => [	0 => ['text' => 'Нравится весь образ', 'on_values' => [0, 1, 2, 3, 4, 5]],
                        1 => [],
                        2 => [],
                        3 => [],
                        4 => [],
                        5 => [],
                        6 => ['text' => 'Ничего не понравилось', 'only_one' => true],
                    ],
                    'key' => 4
                ],
                'choosingStyle5' => [
                    'type' => 'check',
                    'label' => 'Что вы думаете про этот образ?',
                    'image' => 'img/anketa/question_5_1.jpg',
                    'option' => [
                        0 => ['text' => 'Нравится весь образ', 'on_values' => [0, 1, 2, 3, 4, 5]],
                        1 => [],
                        2 => [],
                        3 => [],
                        4 => [],
                        5 => [],
                        6 => ['text' => 'Ничего не понравилось', 'only_one' => true],
                    ],
                    'key' => 5
                ],
                'choosingStyle6' => [
                    'type' => 'check',
                    'label' => 'Что вы думаете про этот образ?',
                    'image' => 'img/anketa/question_6_1.jpg',
                    'option' => [
                        0 => ['text' => 'Нравится весь образ', 'on_values' => [0, 1, 2, 3, 4, 5]],
                        1 => [],
                        2 => [],
                        3 => [],
                        4 => [],
                        5 => [],
                        6 => ['text' => 'Ничего не понравилось', 'only_one' => true],
                    ],
                    'key' => 6
                ],
                'choosingStyle7' => [
                    'type' => 'check',
                    'label' => 'Что вы думаете про этот образ?',
                    'image' => 'img/anketa/question_7_1.jpg',
                    'option' => [
                        0 => ['text' => 'Нравится весь образ', 'on_values' => [0, 1, 2, 3, 4, 5]],
                        1 => [],
                        2 => [],
                        3 => [],
                        4 => [],
                        5 => [],
                        6 => ['text' => 'Ничего не понравилось', 'only_one' => true],
                    ],
                    'key' => 7
                ],
                'choosingStyle8' => [
                    'type' => 'check',
                    'label' => 'Что вы думаете про этот образ?',
                    'image' => 'img/anketa/question_8_1.jpg',
                    'option' => [
                        0 => ['text' => 'Нравится весь образ', 'on_values' => [0, 1, 2, 3, 4, 5, 6]],
                        1 => [],
                        2 => [],
                        3 => [],
                        4 => [],
                        5 => [],
                        6 => [],
                        7 => ['text' => 'Ничего не понравилось', 'only_one' => true],
                    ],
                    'key' => 8
                ],
                'choosingStyle16' => [
                    'type' => 'check',
                    'label' => 'Что вы думаете про этот образ?',
                    'image' => 'img/anketa/question_16_1.jpg',
                    'option' => [
                        0 => ['text' => 'Нравится весь образ', 'on_values' => [0, 1, 2, 3, 4, 5, 6]],
                        1 => [],
                        2 => [],
                        3 => [],
                        4 => [],
                        5 => [],
                        6 => [],
                        7 => ['text' => 'Ничего не понравилось', 'only_one' => true],
                    ],
                    'key' => 16
                ],
                'choosingStyle17' => [
                    'type' => 'check',
                    'label' => 'Что вы думаете про этот образ?',
                    'image' => 'img/anketa/question_17_1.jpg',
                    'option' => [
                        0 => ['text' => 'Нравится весь образ', 'on_values' => [0, 1, 2, 3, 4, 5]],
                        1 => [],
                        2 => [],
                        3 => [],
                        4 => [],
                        5 => [],
                        6 => ['text' => 'Ничего не понравилось', 'only_one' => true],
                    ],
                    'key' => 17
                ],
                'choosingStyle18' => [
                    'type' => 'check',
                    'label' => 'Что вы думаете про этот образ?',
                    'image' => 'img/anketa/question_18_1.jpg',
                    'option' => [
                        0 => ['text' => 'Нравится весь образ', 'on_values' => [0, 1, 2, 3, 4, 5, 6]],
                        1 => [],
                        2 => [],
                        3 => [],
                        4 => [],
                        5 => [],
                        6 => [],
                        7 => ['text' => 'Ничего не понравилось', 'only_one' => true],
                    ],
                    'key' => 18
                ],
                'choosingStyle19' => [
                    'type' => 'check',
                    'label' => 'Что вы думаете про этот образ?',
                    'image' => 'img/anketa/question_19_1.jpg',
                    'option' => [
                        0 => ['text' => 'Нравится весь образ', 'on_values' => [0, 1, 2, 3, 4, 5]],
                        1 => [],
                        2 => [],
                        3 => [],
                        4 => [],
                        5 => [],
                        6 => ['text' => 'Ничего не понравилось', 'only_one' => true],
                    ],
                    'key' => 19
                ],

                'choosingStyle20' => [
                    'type' => 'check',
                    'label' => 'Что вы думаете про этот образ?',
                    'image' => 'img/anketa/question_20_1.jpg',
                    'option' => [	0 => ['text' => 'Нравится весь образ', 'on_values' => [0, 1, 2, 3, 4, 5]],
                        1 => [],
                        2 => [],
                        3 => [],
                        4 => [],
                        5 => [],
                        6 => ['text' => 'Ничего не понравилось', 'only_one' => true],
                    ],
                    'key' => 20
                ],

                //принты
                'printsDislike' => [
                    'type' => 'check',
                    'label' => 'Принты, которые вы точно НЕ хотели бы видеть в капсуле?',
                    'option' => [
                        [
                            'name' => 'Анималистичные',
                            'items' => [
                                0 => ['text' => 'Далматинца', 'image' => 'img/anketa/question_51_1_1.png'],
                                1 => ['text' => 'Зебра', 'image' => 'img/anketa/question_51_1_2.png'],
                                2 => ['text' => 'Змея', 'image' => 'img/anketa/question_51_1_3.png'],
                                3 => ['text' => 'Леопард', 'image' => 'img/anketa/question_51_1_4.png']
                            ]
                        ],
                        [
                            'name' => 'Цветочные',
                            'items' => [
                                4 => ['text' => 'Абстрактный', 'image' => 'img/anketa/question_51_2_1.png'],
                                5 => ['text' => 'Однотонный', 'image' => 'img/anketa/question_51_2_2.png'],
                                6 => ['text' => 'Крупный', 'image' => 'img/anketa/question_51_2_3.png'],
                                7 => ['text' => 'Мелкий', 'image' => 'img/anketa/question_51_2_4.png']
                            ]
                        ],
                        [
                            'name' => 'Полоска',
                            'items' => [
                                8 => ['text' => 'Вертикальная', 'image' => 'img/anketa/question_51_3_1.png'],
                                9 => ['text' => 'Геометричная', 'image' => 'img/anketa/question_51_3_2.png'],
                                10 => ['text' => 'Горизонтальная', 'image' => 'img/anketa/question_51_3_3.png'],
                                11 => ['text' => 'Диагональная', 'image' => 'img/anketa/question_51_3_4.png']
                            ]
                        ],
                        [
                            'name' => 'Клетка',
                            'items' => [
                                12 => ['text' => 'Мелкая', 'image' => 'img/anketa/question_51_4_1.png'],
                                13 => ['text' => 'Шотландская', 'image' => 'img/anketa/question_51_4_2.png'],
                                14 => ['text' => 'Гусиная лапка', 'image' => 'img/anketa/question_51_4_3.png'],
                                15 => ['text' => 'Средняя', 'image' => 'img/anketa/question_51_4_4.png']
                            ]
                        ],
                        [
                            'name' => 'Горох',
                            'items' => [
                                16 => ['text' => 'Крапинка', 'image' => 'img/anketa/question_51_5_1.png'],
                                17 => ['text' => 'Хаотичный', 'image' => 'img/anketa/question_51_5_2.png'],
                                18 => ['text' => 'Мелкий', 'image' => 'img/anketa/question_51_5_3.png'],
                                19 => ['text' => 'Классический', 'image' => 'img/anketa/question_51_5_4.png']
                            ]
                        ],
                        [
                            'name' => 'Печатные',
                            'items' => [
                                20 => ['text' => 'Фантазийный', 'image' => 'img/anketa/question_51_6_1.png'],
                                21 => ['text' => 'Анималистичный', 'image' => 'img/anketa/question_51_6_2.png'],
                                22 => ['text' => 'Фруктовый', 'image' => 'img/anketa/question_51_6_3.png'],
                                23 => ['text' => 'Абстрактный', 'image' => 'img/anketa/question_51_6_4.png']
                            ]
                        ],
                        [
                            'name' => 'Узоры',
                            'items' => [
                                24 => ['text' => 'Пейсли', 'image' => 'img/anketa/question_51_7_1.png'],
                                25 => ['text' => 'Геометричный', 'image' => 'img/anketa/question_51_7_2.png'],
                                26 => ['text' => 'Орнамент', 'image' => 'img/anketa/question_51_7_3.png'],
                                27 => ['text' => 'Акварель', 'image' => 'img/anketa/question_51_7_4.png']
                            ]
                        ],
                    ],
                    'key' => 51
                ],

                //доставка

                'chooseCity' => [
                    'type' => 'choice',
                    'appeal' => ', ',
                    'label' => 'остались данные для доставки. Напоминаем: вы не оплачиваете вещи заранее. Мы оставим их у вас на сутки на примерку, вы оплатите только то, что захотите 🙂<br><br>В какой город доставляем капсулу?',
                    'option' => [	0 => ['text' => 'Москва и Московская область'],
                        1 => ['text' => 'Санкт-Петербург и Ленинградская область'],
                        2 => ['text' => 'Другой город'],
                    ],
                    'placeholder' => 'Введите город',
                    'key' => 71
                ],
                'deliveryType' => [
                    'type' => 'choice',
                    'appeal' => '',
                    'label' => 'Как вам было бы удобнее получить капсулу?',
                    'option' => [
                        0 => ['text' => 'Доставка курьером'],
                        1 => ['text' => 'Доставка в пункт выдачи'],
                    ],
                    'key' => 80
                ],

                'boxberryPoint' => [
                    'type' => 'text',
                    'label' => 'В каком пункте выдачи вы бы хотели забрать капсулу? Подтвердите свой выбор, нажав на кнопку "Выбрать отделение" 🙂',
                    'key' => 81
                ],

                'boxberryAddress' => [
                    'type' => 'text'
                ],

                'boxberryCityOther' => [
                    'type' => 'text',
                    'label' => 'У нас работает доставка в пункт выдачи. Введите ваш город',
                    'placeholder' => 'Введите город',
                    'key' => 82
                ],

                'addressCityStreet' => [
                    'type' => 'text',
                    'label' => 'Нашей курьерской службе потребуется ваш адрес: город, улица, дом и корпус',
                    'placeholder' => 'Подольск, проспект Ленина, 128/24',
                    'key' => 74
                ],

                'addressFlatNumber' => [
                    'type' => 'text',
                    'label' => 'Нашей курьерской службе потребуется ваш адрес: город, улица, дом и корпус',
                    'placeholder' => 'Квартира / номер офиса',
                    'key' => 76
                ],

                'addressComments' => [
                    'type' => 'text',
                    'label' => 'Нашей курьерской службе потребуется ваш адрес: город, улица, дом и корпус',
                    'placeholder' => 'Дополнительный комментарий',
                    'key' => 77
                ],

                'deliveryDate' => [
                    'type' => 'text',
                    'appeal' => ', ',
                    'label' => 'когда вам удобно было бы получить доставку? Мы соберем подборку за 6 дней',
                    'key' => 64
                ],

                'deliveryTime' => [
                    'type' => 'check',
                    'label' => 'В какое время вам было бы удобнее получить подборку?',
                    'option' => [
                        0 => ['text' => '10:00 - 14:00', 'link' => 0],
                        1 => ['text' => '14:00 - 18:00', 'link' => 0],
                        2 => ['text' => '18:00 - 22:00', 'link' => 0]
                    ],
                    'key' => 65
                ],

                'deliveryBackTime' => [
                    'type' => 'check',
                    'label' => 'А во сколько забрать на следующий день?',
                    'option' => [
                        0 => ['text' => '10:00 - 14:00', 'link' => 0],
                        1 => ['text' => '14:00 - 18:00', 'link' => 0],
                        2 => ['text' => '18:00 - 22:00', 'link' => 0]
                    ],
                    'key' => 66
                ],

                'nameForDelivery' => [
                    'type' => 'text',
                    'label' => 'Укажите ваши имя и фамилию. Они понадобятся нам для доставки',
                    'placeholder' => 'Имя',
                    'key' => 0
                ],
                'secondNameForDelivery' => [
                    'type' => 'text',
                    'placeholder' => 'Фамилия',
                    'key' => 79
                ],

                'patronomicsForDelivery' => [
                    'type' => 'text',
                    'placeholder' => 'Отчество',
                    'key' => 84
                ],

                'paymentAmount' => [
                    'type' => 'text'
                ]//payAmount
            ],

        ];
    }

    private function setQuestions(){
        $this->answers =
            [
                0 => '',
                1 => [],
                2 => [],
                3 => NULL,
                4 => [],
                5 => [],
                6 => [],
                7 => [],
                8 => [],
                9 => [],
                10 => [],
                11 => [],
                12 => [],
                13 => [],
                14 => '',
                15 => '',
                16 => [],
                17 => [],
                18 => [],
                19 => [],
                20 => [],
                21 => [],
                22 => [],
                23 => [],
                24 => [],
                25 => [],
                26 => '',
                27 => '',
                28 => NULL,
                29 => NULL,
                30 => [],
                31 => [],
                32 => '',
                33 => '',
                34 => '',
                35 => [],
                36 => [],
                37 => [],
                38 => [],
                39 => [],
                40 => [],
                41 => [],
                42 => NULL,
                43 => [],
                44 => NULL,
                45 => NULL,
                46 => NULL,
                47 => [],
                48 => [],
                49 => [],
                50 => [],
                /*51 =>[]*/
                51 => [],
                52 => [],
                53 => '',
                54 => NULL,
                55 => NULL,
                56 => NULL,
                57 => NULL,
                58 => NULL,
                59 => NULL,
                60 => NULL,
                61 => NULL,
                62 => [],
                63 => '',
                64 => date('Y-m-d', strtotime('+6 days')),
                65 => [],
                66 => [],
                67 => '',
                68 => '',
                69 => NULL,
                70 => '',

                71 => NULL,
                72 => 1,
                73 => '',
                74 => '',
                75 => '',
                76 => '',
                77 => '',
                78 => NULL,
                79 => '',
                80 => NULL,
                81 => NULL,
                82 =>  '',
                83 => NULL,
                84 => '',
                85 => [],
                86 => [],
                87 => [],
                88 => [],
                89 => [],
                90 => [],
                91 => [],
                92 => [],
                93 => [],
                94 => [],
            ];

        $this->questions = [
            0 => [
                'type' => 'text',
                'label' => 'Привет, меня зовут Даша. Я – менеджер по работе со стилистами Capsula. Подробно расскажите нам о себе, это поможет нам подобрать идеальную капсулу персонально для вас',
                'placeholder' => 'Как вас зовут?'
            ],
            1 => [
                'type' => 'check',
                'appeal' => ', приятно познакомиться!',
                'label' => '<br><br>Расскажите, какой стиль вы предпочитаете в выходные?',
                'option' => [
                    0 => ['text' => 'Business casual', 'image' => 'img/anketa/question_1_0.jpeg'],
                    1 => ['text' => 'Casual', 'image' => 'img/anketa/question_1_1.jpeg'],
                    2 => ['text' => 'Спортивный стиль', 'image' => 'img/anketa/question_1_2.jpeg'],
                    3 => ['text' => 'Стилей же больше! Одеваюсь по-другому', 'textThumb' => true, 'image' => ''],
                ]
            ],
            2 => [
                'type' => 'check',
                'label' => 'Как вы одеваетесь на работу?',
                'option' => [
                    0 => ['text' => 'Строгий<br> дресс-код', 'image' => 'img/anketa/question_2_0.jpg'],
                    1 => ['text' => 'Нестрогий<br> дресс-код', 'image' => 'img/anketa/question_2_1.jpeg'],
                    2 => ['text' => 'Casual', 'image' => 'img/anketa/question_2_2.jpeg'],
                    3 => ['text' => 'Стилей же больше! Одеваюсь по-другому', 'textThumb' => true, 'image' => ''],
                ]
            ],
            3 => [
                'type' => 'choice',
                'label' => 'Поняла.<br><br>Вы хотите, чтобы стилист подобрал что-то отличное от того, к чему вы привыкли, или сохранил ваш стиль?',
                'option' => [0 => ['text' => 'Хочу абсолютно новый стиль. Готова к экспериментам'],
                    1 => ['text' => '50/50 - действуем аккуратно'],
                    2 => ['text' => 'Хочу, чтобы мой стиль остался неизменным'],
                ]
            ],
            4 => [
                'type' => 'check',
                'label' => 'Отлично! Далее я покажу несколько образов. Расскажите свое мнение о них, чтобы мы лучше поняли ваши предпочтения',
                'image' => 'img/anketa/question_4_1.jpg',
                'option' => [0 => ['text' => 'Нравится весь образ', 'on_values' => [0, 1, 2, 3, 4, 5]],
                    1 => [],
                    2 => [],
                    3 => [],
                    4 => [],
                    5 => [],
                    6 => ['text' => 'Ничего не понравилось', 'only_one' => true],
                ]
            ],
            5 => [
                'type' => 'check',
                'label' => 'Что вы думаете про этот образ?',
                'image' => 'img/anketa/question_5_1.jpg',
                'option' => [0 => ['text' => 'Нравится весь образ', 'on_values' => [0, 1, 2, 3, 4, 5]],
                    1 => [],
                    2 => [],
                    3 => [],
                    4 => [],
                    5 => [],
                    6 => ['text' => 'Ничего не понравилось', 'only_one' => true],
                ]
            ],
            6 => [
                'type' => 'check',
                'label' => 'Что вы думаете про этот образ?',
                'image' => 'img/anketa/question_6_1.jpg',
                'option' => [0 => ['text' => 'Нравится весь образ', 'on_values' => [0, 1, 2, 3, 4, 5]],
                    1 => [],
                    2 => [],
                    3 => [],
                    4 => [],
                    5 => [],
                    6 => ['text' => 'Ничего не понравилось', 'only_one' => true],
                ]
            ],
            7 => [
                'type' => 'check',
                'label' => 'Что вы думаете про этот образ?',
                'image' => 'img/anketa/question_7_1.jpg',
                'option' => [0 => ['text' => 'Нравится весь образ', 'on_values' => [0, 1, 2, 3, 4, 5]],
                    1 => [],
                    2 => [],
                    3 => [],
                    4 => [],
                    5 => [],
                    6 => ['text' => 'Ничего не понравилось', 'only_one' => true],
                ]
            ],
            8 => [
                'type' => 'check',
                'label' => 'Что вы думаете про этот образ?',
                'image' => 'img/anketa/question_8_1.jpg',
                'option' => [0 => ['text' => 'Нравится весь образ', 'on_values' => [0, 1, 2, 3, 4, 5, 6]],
                    1 => [],
                    2 => [],
                    3 => [],
                    4 => [],
                    5 => [],
                    6 => [],
                    7 => ['text' => 'Ничего не понравилось', 'only_one' => true],
                ]
            ],
            9 => [
                'type' => 'check',
                'label' => 'Что вы думаете про этот образ?',
                'image' => 'img/anketa/question_9.png',
                'option' => [0 => ['text' => 'Нравится весь образ', 'on_values' => [0, 1, 2, 3, 4, 5]],
                    1 => [],
                    2 => [],
                    3 => [],
                    4 => [],
                    5 => [],
                    6 => ['text' => 'Ничего не понравилось', 'only_one' => true],
                ]
            ],
            10 => [
                'type' => 'check',
                'label' => 'Что вы думаете про этот образ?',
                'image' => 'img/anketa/question_10.png',
                'option' => [0 => ['text' => 'Нравится весь образ', 'on_values' => [0, 1, 2, 3, 4]],
                    1 => [],
                    2 => [],
                    3 => [],
                    4 => [],
                    5 => ['text' => 'Ничего не понравилось', 'only_one' => true],
                ]
            ],
            11 => [
                'type' => 'check',
                'label' => 'Что вы думаете про этот образ?',
                'image' => 'img/anketa/question_11.png',
                'option' => [0 => ['text' => 'Нравится весь образ', 'on_values' => [0, 1, 2, 3, 4, 5]],
                    1 => [],
                    2 => [],
                    3 => [],
                    4 => [],
                    5 => [],
                    6 => ['text' => 'Ничего не понравилось', 'only_one' => true],
                ]
            ],
            12 => [
                'type' => 'check',
                'label' => 'Что вы думаете про этот образ?',
                'image' => 'img/anketa/question_12.png',
                'option' => [0 => ['text' => 'Нравится весь образ', 'on_values' => [0, 1, 2, 3, 4]],
                    1 => [],
                    2 => [],
                    3 => [],
                    4 => [],
                    5 => ['text' => 'Ничего не понравилось', 'only_one' => true],
                ]
            ],
            13 => [
                'type' => 'check',
                'label' => 'Что вы думаете про этот образ?',
                'image' => 'img/anketa/question_13.png',
                'option' => [0 => ['text' => 'Нравится весь образ', 'on_values' => [0, 1, 2, 3, 4]],
                    1 => [],
                    2 => [],
                    3 => [],
                    4 => [],
                    5 => ['text' => 'Ничего не понравилось', 'only_one' => true],
                ]
            ],
            14 => [
                'type' => 'text',
                'appeal' => ', спасибо! ',
                'label' => 'Заполните контактные данные - они нужны, чтобы уточнять некоторые детали. Обещаем не надоедать со спамом 🙂'
            ],
            15 => [
                'type' => 'text',
                'label' => 'Укажите ваш номер телефона'
            ],
            16 => [
                'type' => 'check',
                'label' => 'Что вы думаете про этот образ?',
                'image' => 'img/anketa/question_16_1.jpg',
                'option' => [
                    0 => ['text' => 'Нравится весь образ', 'on_values' => [0, 1, 2, 3, 4, 5, 6]],
                    1 => [],
                    2 => [],
                    3 => [],
                    4 => [],
                    5 => [],
                    6 => [],
                    7 => ['text' => 'Ничего не понравилось', 'only_one' => true],
                ]
            ],
            17 => [
                'type' => 'check',
                'label' => 'Что вы думаете про этот образ?',
                'image' => 'img/anketa/question_17_1.jpg',
                'option' => [0 => ['text' => 'Нравится весь образ', 'on_values' => [0, 1, 2, 3, 4, 5]],
                    1 => [],
                    2 => [],
                    3 => [],
                    4 => [],
                    5 => [],
                    6 => ['text' => 'Ничего не понравилось', 'only_one' => true],
                ]
            ],
            18 => [
                'type' => 'check',
                'label' => 'Что вы думаете про этот образ?',
                'image' => 'img/anketa/question_18_1.jpg',
                'option' => [0 => ['text' => 'Нравится весь образ', 'on_values' => [0, 1, 2, 3, 4, 5, 6]],
                    1 => [],
                    2 => [],
                    3 => [],
                    4 => [],
                    5 => [],
                    6 => [],
                    7 => ['text' => 'Ничего не понравилось', 'only_one' => true],
                ]
            ],
            19 => [
                'type' => 'check',
                'label' => 'Что вы думаете про этот образ?',
                'image' => 'img/anketa/question_19_1.jpg',
                'option' => [0 => ['text' => 'Нравится весь образ', 'on_values' => [0, 1, 2, 3, 4, 5]],
                    1 => [],
                    2 => [],
                    3 => [],
                    4 => [],
                    5 => [],
                    6 => ['text' => 'Ничего не понравилось', 'only_one' => true],
                ]
            ],
            20 => [
                'type' => 'check',
                'label' => 'Что вы думаете про этот образ?',
                'image' => 'img/anketa/question_20_1.jpg',
                'option' => [0 => ['text' => 'Нравится весь образ', 'on_values' => [0, 1, 2, 3, 4, 5]],
                    1 => [],
                    2 => [],
                    3 => [],
                    4 => [],
                    5 => [],
                    6 => ['text' => 'Ничего не понравилось', 'only_one' => true],
                ]
            ],
            21 => [
                'type' => 'check',
                'label' => 'Что вы думаете про этот образ?',
                'image' => 'img/anketa/question_21.png',
                'option' => [0 => ['text' => 'Нравится весь образ', 'on_values' => [0, 1, 2, 3, 4]],
                    1 => [],
                    2 => [],
                    3 => [],
                    4 => [],
                    5 => ['text' => 'Ничего не понравилось', 'only_one' => true],
                ]
            ],
            22 => [
                'type' => 'check',
                'label' => 'Что вы думаете про этот образ?',
                'image' => 'img/anketa/question_22.png',
                'option' => [0 => ['text' => 'Нравится весь образ', 'on_values' => [0, 1, 2, 3, 4, 5]],
                    1 => [],
                    2 => [],
                    3 => [],
                    4 => [],
                    5 => [],
                    6 => ['text' => 'Ничего не понравилось', 'only_one' => true],
                ]
            ],
            23 => [
                'type' => 'check',
                'label' => 'Что вы думаете про этот образ?',
                'image' => 'img/anketa/question_23.png',
                'option' => [0 => ['text' => 'Нравится весь образ', 'on_values' => [0, 1, 2, 3, 4, 5]],
                    1 => [],
                    2 => [],
                    3 => [],
                    4 => [],
                    5 => [],
                    6 => ['text' => 'Ничего не понравилось', 'only_one' => true],
                ]
            ],
            24 => [
                'type' => 'check',
                'label' => 'Что вы думаете про этот образ?',
                'image' => 'img/anketa/question_24.png',
                'option' => [0 => ['text' => 'Нравится весь образ', 'on_values' => [0, 1, 2, 3, 4, 5]],
                    1 => [],
                    2 => [],
                    3 => [],
                    4 => [],
                    5 => [],
                    6 => ['text' => 'Ничего не понравилось', 'only_one' => true],
                ]
            ],
            25 => [
                'type' => 'check',
                'label' => 'Какая цветовая гамма вам ближе? Можно выбрать несколько 🙂',
                'option' => [0 => ['text' => 'Серый монохром', 'image' => 'img/anketa/question_25_0.png',
                    'colors' => ['#FFFFFF', '#3B3838', '#757070', '#000000', '#CFCDCD', '#F1F1F1', '#7F7F7F', '#404040', '#DADADA']],
                    1 => ['text' => 'Бежевый монохром', 'image' => 'img/anketa/question_25_1.png',
                        'colors' => ['#BCA181', '#786058', '#F0DECB', '#D5CBBD', '#E5D1B1', '#392D2B', '#5B3022', '#FDF2E4', '#BC9887',]],
                    2 => ['text' => 'Пастельная', 'image' => 'img/anketa/question_25_2.png',
                        'colors' => ['#D0DAF5', '#E5EFDB', '#F1D7CF', '#FDF2D0', '#F7E5D8', '#E3E4E6', '#EED4EC', '#DBD3C7', '#E0EBF6',]],
                    3 => ['text' => 'Мягкая', 'image' => 'img/anketa/question_25_3.png',
                        'colors' => ['#484D67', '#D5CBBC', '#9C6F73', '#D1918E', '#5A7F6C', '#796F64', '#6C394D', '#B9B9AC', '#456C79',]],
                    4 => ['text' => 'Тёмная', 'image' => 'img/anketa/question_25_4.png',
                        'colors' => ['#151414', '#6C394D', '#3D2D2E', '#252626', '#4F555B', '#5B3022', '#5C1311', '#3F562A', '#000E54',]],
                    5 => ['text' => 'Яркая', 'image' => 'img/anketa/question_25_5.png',
                        'colors' => ['#F2EE50', '#3B7D7C', '#BB4239', '#AA3B80', '#312DB5', '#94B73D', '#448EC7', '#72F8D9', '#D6722E',]],
                ]
            ],
            26 => [
                'type' => 'text',
                'appeal' => ', ',
                'label' => 'мы уже знаем ваши предпочтения в одежде, теперь немного о вас 🙂',
                'placeholder' => 'Ваш рост (см)'
            ],
            27 => [
                'type' => 'text',
                'label' => 'Дата вашего рождения?',
                'placeholder' => '10.09.1995'
            ],
            28 => [
                'type' => 'choice',
                'label' => 'И еще немного:<br><br>Ваш род деятельности?',
                'option' => [0 => ['text' => 'Маркетолог'],
                    1 => ['text' => 'Менеджер'],
                    2 => ['text' => 'Финансист'],
                    3 => ['text' => 'Медиа и коммуникации'],
                    4 => ['text' => 'Искусство / Творчество'],
                    5 => ['text' => 'Юрист'],
                    6 => ['text' => 'Предприниматель'],
                    7 => ['text' => 'Инженер'],
                    //												8 => ['text' => 'Архитектор'],
                    9 => ['text' => 'Студент'],
                    10 => ['text' => 'Дизайнер'],
                    11 => ['text' => 'IT'],
                    //												12 => ['text' => 'Консультант'],
                    13 => ['text' => 'Врач / Медицинский сотрудник'],
                    14 => ['text' => 'Ресторанный бизнес'],
                    //												15 => ['text' => 'Менеджмент'],
                    16 => ['text' => 'Личный помощник'],
                    17 => ['text' => 'Продавец'],
                    18 => ['text' => 'Сейчас не работаю'],
                    19 => ['text' => 'Другое'],
                ]
            ],
            29 => [
                'type' => 'choice',
                'label' => 'Ваш цвет волос?',
                'option' => [0 => ['text' => 'Блонд'],
                    1 => ['text' => 'Брюнет'],
                    2 => ['text' => 'Шатен'],
                    3 => ['text' => 'Рыжий'],
                    4 => ['text' => 'Русый'],
                ]
            ],
            30 => [
                'type' => 'check',
                'label' => 'Класс, спасибо!<br>Дальше несколько вопросов о ваших размерах<br>Российский размер верха (платья, блузы, жакеты, свитеры и др.)',
                'option' => [
                    0 => ['text' => '38 (XXS)'],
                    1 => ['text' => '40 (XXS / XS)'],
                    2 => ['text' => '42 (XS)'],
                    3 => ['text' => '44 (S)'],
                    4 => ['text' => '46 (M)'],
                    5 => ['text' => '48 (M / L)'],
                    6 => ['text' => '50 (L)'],
                    7 => ['text' => '52 (XL)'],
                    8 => ['text' => '54 (XL)'],
                    9 => ['text' => '56 (XXL)'],
                ]
            ],
            31 => [
                'type' => 'check',
                'label' => 'Российский размер низа (брюки, джинсы, юбки)',
                'option' => [
                    0 => ['text' => '38 (XXS)'],
                    1 => ['text' => '40 (XXS / XS или 25)'],
                    2 => ['text' => '42 (XS или 26, 27)'],
                    3 => ['text' => '44 (S или 28)'],
                    4 => ['text' => '46 (M или 29, 30)'],
                    5 => ['text' => '48 (M / L или 31, 32)'],
                    6 => ['text' => '50 (L или 33, 34)'],
                    7 => ['text' => '52 (XL или 35, 36)'],
                    8 => ['text' => '54 (XL или 38)'],
                    9 => ['text' => '56 (XXL или 40)'],
                ]
            ],
            32 => [
                'type' => 'text',
                'label' => 'Замеры помогут стилисту точнее определить размер. Отвечать необязательно 🙂',
                'placeholder' => 'Объем груди (см)'
            ],
            33 => [
                'type' => 'text',
                'label' => 'Ваш объем талии (см) (необязательно)',
                'placeholder' => 'Объем талии (см)'
            ],
            34 => [
                'type' => 'text',
                'label' => 'Ваш объем бедер (см) (необязательно)',
                'placeholder' => 'Объем бедер (см)'
            ],
            35 => [
                'type' => 'check',
                'label' => 'Вы - молодец, уже прошли больше половины пути. Расскажите теперь немного о вашем стиле.<br><br>Как вам комфортнее носить блузы/рубашки/джемперы?',
                'option' => [0 => ['text' => 'Облегающе'],
                    1 => ['text' => 'По размеру'],
                    2 => ['text' => 'Свободно'],
                ]
            ],
            36 => [
                'type' => 'check',
                'label' => 'Принято! А если говорить о брюках/джинсах?',
                'option' => [0 => ['text' => 'Облегающе'],
                    1 => ['text' => 'По размеру'],
                    2 => ['text' => 'Свободно'],
                ]
            ],
            37 => [
                'type' => 'check',
                'appeal' => ', ',
                'label' => 'какие модели джинсов вы предпочитаете?',
                'option' => [0 => ['text' => 'Скинни', 'image' => 'img/anketa/question_37_0.png'],
                    1 => ['text' => 'Прямые', 'image' => 'img/anketa/question_37_1.png'],
                    2 => ['text' => 'Широкие', 'image' => 'img/anketa/question_37_2.png'],
                    3 => ['text' => 'Клеш', 'image' => 'img/anketa/question_37_3.png'],
                    4 => ['text' => 'МОМ', 'image' => 'img/anketa/question_37_4.png'],
                ]
            ],
            38 => [
                'type' => 'check',
                'label' => 'А посадку?',
                'option' => [0 => ['text' => 'Высокая', 'image' => 'img/anketa/question_38_0.png'],
                    1 => ['text' => 'Средняя', 'image' => 'img/anketa/question_38_1.png'],
                    2 => ['text' => 'Низкая', 'image' => 'img/anketa/question_38_2.png'],
                ]
            ],
            39 => [
                'type' => 'check',
                'label' => 'И наконец длина джинсов / брюк',
                'option' => [0 => ['text' => 'Укороченные', 'image' => 'img/anketa/question_39_0.png'],
                    1 => ['text' => 'Стандартные', 'image' => 'img/anketa/question_39_1.png'],
                    2 => ['text' => 'Длинные', 'image' => 'img/anketa/question_39_2.png'],
                ]
            ],
            40 => [
                'type' => 'check',
                'label' => 'Какие юбки / платья вы предпочитаете?',
                'option' => [0 => ['text' => 'Мини', 'image' => 'img/anketa/question_40_0.png'],
                    1 => ['text' => 'Миди', 'image' => 'img/anketa/question_40_1.png'],
                    2 => ['text' => 'Макси', 'image' => 'img/anketa/question_40_2.png'],
                ]
            ],
            41 => [
                'type' => 'check',
                'appeal' => ', ',
                'label' => 'есть части тела, которые вы предпочитаете скрывать?',
                'option' => [0 => ['text' => 'Руки'],
                    1 => ['text' => 'Плечи'],
                    2 => ['text' => 'Спина'],
                    3 => ['text' => 'Декольте'],
                    4 => ['text' => 'Талия'],
                    5 => ['text' => 'Ноги'],
                    6 => ['text' => 'Ничего не скрываю', 'only_one' => true],
                ]
            ],
            42 => [
                'type' => 'choice',
                'label' => 'Вы больше любите джинсы с топом или платья?',
                'option' => [0 => ['text' => 'Больше джинсы / футболки / свитера'],
                    1 => ['text' => 'Больше платья'],
                    2 => ['text' => 'Примерно 50/50'],
                ]
            ],
            43 => [
                'type' => 'check',
                'label' => 'Расскажите, где вы обычно покупаете одежду. Это поможет стилисту точнее понять ваше предпочтения',
                'option' => [0 => ['text' => 'Zara'],
                    1 => ['text' => 'H&M'],
                    2 => ['text' => 'Pull&Bear'],
                    3 => ['text' => 'Topshop'],
                    4 => ['text' => 'Mango'],
                    5 => ['text' => 'Reserved'],
                    6 => ['text' => 'Massimo Dutti'],
                    7 => ['text' => 'Calvin Klein'],
                    8 => ['text' => 'Twin Set'],
                    9 => ['text' => 'Lamoda'],
                    10 => ['text' => 'Wildberries'],
                    11 => ['text' => '12storeez'],
                    12 => ['text' => 'Farfetch'],
                    13 => ['text' => 'Asos'],
                    14 => ['text' => 'Yoox'],
                    15 => ['text' => 'Ничего из этого', 'only_one' => true],
                ]
            ],
            44 => [
                'type' => 'choice',
                'label' => 'Проколоты ли у вас уши?',
                'option' => [0 => ['text' => 'Да'],
                    1 => ['text' => 'Нет'],
                ]
            ],

            45 => [
                'type' => 'choice',
                'label' => 'Какие ювелирные изделия вы предпочитаете?',
                'option' => [0 => ['text' => 'Серебро'],
                    1 => ['text' => 'Золото'],
                    2 => ['text' => '50/50'],
                ]
            ],
            46 => [
                'type' => 'choice',
                'label' => 'А к бижутерии готовы?',
                'option' => [
                    0 => ['text' => 'Да'],
                    1 => ['text' => 'Нет'],
                ]
            ],
            47 => [
                'type' => 'check',
                'label' => 'Каждая наша капсула собирается персонально для вас. Что вы хотите получить в капсуле прежде всего?',
                'option' => [0 => ['text' => 'Футболки / рубашки / блузы', 'image' => 'img/anketa/question_47_0.png'],
                    1 => ['text' => 'Свитера / кардиганы / пуловеры', 'image' => 'img/anketa/question_47_1.png'],
                    2 => ['text' => 'Пиджаки / жакеты', 'image' => 'img/anketa/question_47_2.png'],
                    3 => ['text' => 'Джинсы', 'image' => 'img/anketa/question_47_3.png'],
                    4 => ['text' => 'Брюки', 'image' => 'img/anketa/question_47_4.png'],
                    5 => ['text' => 'Шорты', 'image' => 'img/anketa/question_47_5.png'],
                    6 => ['text' => 'Платья', 'image' => 'img/anketa/question_47_6.png'],
                    7 => ['text' => 'Юбки', 'image' => 'img/anketa/question_47_7.png'],
                    8 => ['text' => 'Готова на все', 'image' => 'img/anketa/question_47_8.png'],
                ]
            ],
            48 => [
                'type' => 'check',
                'label' => 'Отлично! А какую одежду вы бы не хотели видеть в капсуле?',
                'option' => [0 => ['text' => 'Футболки / рубашки / блузы', 'image' => 'img/anketa/question_47_0.png'],
                    1 => ['text' => 'Свитера / кардиганы / пуловеры', 'image' => 'img/anketa/question_47_1.png'],
                    2 => ['text' => 'Пиджаки / жакеты', 'image' => 'img/anketa/question_47_2.png'],
                    3 => ['text' => 'Джинсы', 'image' => 'img/anketa/question_47_3.png'],
                    4 => ['text' => 'Брюки', 'image' => 'img/anketa/question_47_4.png'],
                    5 => ['text' => 'Шорты', 'image' => 'img/anketa/question_47_5.png'],
                    6 => ['text' => 'Платья', 'image' => 'img/anketa/question_47_6.png'],
                    7 => ['text' => 'Юбки', 'image' => 'img/anketa/question_47_7.png'],
                ]
            ],
            49 => [
                'type' => 'check',
                'appeal' => ', ',
                'label' => 'какие аксессуары вы точно не хотите получить?',
                'option' => [0 => ['text' => 'Сумки', 'image' => 'img/anketa/question_49_0.png'],
                    1 => ['text' => 'Шарфы', 'image' => 'img/anketa/question_49_1.png'],
                    2 => ['text' => 'Серьги', 'image' => 'img/anketa/question_49_2.png'],
                    3 => ['text' => 'Колье / аксессуары на шею', 'image' => 'img/anketa/question_49_3.png'],
                    4 => ['text' => 'Ремни', 'image' => 'img/anketa/question_49_4.png'],
                ]
            ],
            50 => [
                'type' => 'check',
                'label' => 'Осталось совсем немного 😄<br><br>Какие цвета вы бы НЕ хотели видеть в капсуле?',
                'option' => [0 => ['text' => 'Белый', 'color' => '#F4F9F0'],
                    1 => ['text' => 'Бежевый', 'color' => '#C4AA6D'],
                    2 => ['text' => 'Лимонный', 'color' => '#F4FF52'],
                    3 => ['text' => 'Голубой', 'color' => '#B4C7E7'],
                    4 => ['text' => 'Лиловый', 'color' => '#FFC6FC'],
                    5 => ['text' => 'Оранжевый', 'color' => '#FF8634'],
                    6 => ['text' => 'Светло-серый', 'color' => '#B2AEB0'],
                    7 => ['text' => 'Желтый', 'color' => '#FFD126'],
                    8 => ['text' => 'Светло-зеленый', 'color' => '#99C544'],
                    9 => ['text' => 'Бирюзовый', 'color' => '#1B9790'],
                    10 => ['text' => 'Розовый', 'color' => '#FF9FAE'],
                    11 => ['text' => 'Коралловый', 'color' => '#FF5B61'],
                    12 => ['text' => 'Темно-серый', 'color' => '#474747'],
                    13 => ['text' => 'Терракотовый', 'color' => '#C47C16'],
                    14 => ['text' => 'Хакки', 'color' => '#716D14'],
                    15 => ['text' => 'Электрик', 'color' => '#0014A0'],
                    16 => ['text' => 'Фуксия', 'color' => '#ED0E92'],
                    17 => ['text' => 'Красный', 'color' => '#FF0000'],
                    18 => ['text' => 'Черный', 'color' => '#0D0D0D'],
                    19 => ['text' => 'Коричневый', 'color' => '#5E2F07'],
                    20 => ['text' => 'Изумрудный', 'color' => '#1F3D18'],
                    21 => ['text' => 'Синий', 'color' => '#1F3965'],
                    22 => ['text' => 'Фиолетовый', 'color' => '#632A8F'],
                    23 => ['text' => 'Бордовый', 'color' => '#7F0000'],
                ]
            ],
            /*51 => [	'type' => 'check',
                'label' => 'Принты, которые вы точно НЕ хотели бы видеть в капсуле?',
                'option' => [	0 => ['text' => '', 'image' => 'img/anketa/question_51_1.jpg'],
                    1 => ['text' => 'Цветочные принты', 'image' => 'img/anketa/question_51_2.jpg'],
                    2 => ['text' => 'Горошек', 'image' => 'img/anketa/question_51_3.png'],
                    3 => ['text' => 'Полоски', 'image' => 'img/anketa/question_51_4.jpeg'],
                    4 => ['text' => 'Клетка', 'image' => 'img/anketa/question_51_5.jpg'],
                    5 => ['text' => 'Узоры', 'image' => 'img/anketa/question_51_6.jpg'],
                    6 => ['text' => 'Печатные принты', 'image' => 'img/anketa/question_51_7.png'],
                ],
                'answer' => [],
            ],*/
            51 => [
                'type' => 'check',
                'label' => 'Принты, которые вы точно НЕ хотели бы видеть в капсуле?',
                'option' => [
                    [
                        'name' => 'Анималистичные',
                        'items' => [
                            0 => ['text' => 'Далматинца', 'image' => 'img/anketa/question_51_1_1.png'],
                            1 => ['text' => 'Зебра', 'image' => 'img/anketa/question_51_1_2.png'],
                            2 => ['text' => 'Змея', 'image' => 'img/anketa/question_51_1_3.png'],
                            3 => ['text' => 'Леопард', 'image' => 'img/anketa/question_51_1_4.png']
                        ]
                    ],
                    [
                        'name' => 'Цветочные',
                        'items' => [
                            4 => ['text' => 'Абстрактный', 'image' => 'img/anketa/question_51_2_1.png'],
                            5 => ['text' => 'Однотонный', 'image' => 'img/anketa/question_51_2_2.png'],
                            6 => ['text' => 'Крупный', 'image' => 'img/anketa/question_51_2_3.png'],
                            7 => ['text' => 'Мелкий', 'image' => 'img/anketa/question_51_2_4.png']
                        ]
                    ],
                    [
                        'name' => 'Полоска',
                        'items' => [
                            8 => ['text' => 'Вертикальная', 'image' => 'img/anketa/question_51_3_1.png'],
                            9 => ['text' => 'Геометричная', 'image' => 'img/anketa/question_51_3_2.png'],
                            10 => ['text' => 'Горизонтальная', 'image' => 'img/anketa/question_51_3_3.png'],
                            11 => ['text' => 'Диагональная', 'image' => 'img/anketa/question_51_3_4.png']
                        ]
                    ],
                    [
                        'name' => 'Клетка',
                        'items' => [
                            12 => ['text' => 'Мелкая', 'image' => 'img/anketa/question_51_4_1.png'],
                            13 => ['text' => 'Шотландская', 'image' => 'img/anketa/question_51_4_2.png'],
                            14 => ['text' => 'Гусиная лапка', 'image' => 'img/anketa/question_51_4_3.png'],
                            15 => ['text' => 'Средняя', 'image' => 'img/anketa/question_51_4_4.png']
                        ]
                    ],
                    [
                        'name' => 'Горох',
                        'items' => [
                            16 => ['text' => 'Крапинка', 'image' => 'img/anketa/question_51_5_1.png'],
                            17 => ['text' => 'Хаотичный', 'image' => 'img/anketa/question_51_5_2.png'],
                            18 => ['text' => 'Мелкий', 'image' => 'img/anketa/question_51_5_3.png'],
                            19 => ['text' => 'Классический', 'image' => 'img/anketa/question_51_5_4.png']
                        ]
                    ],
                    [
                        'name' => 'Печатные',
                        'items' => [
                            20 => ['text' => 'Фантазийный', 'image' => 'img/anketa/question_51_6_1.png'],
                            21 => ['text' => 'Анималистичный', 'image' => 'img/anketa/question_51_6_2.png'],
                            22 => ['text' => 'Фруктовый', 'image' => 'img/anketa/question_51_6_3.png'],
                            23 => ['text' => 'Абстрактный', 'image' => 'img/anketa/question_51_6_4.png']
                        ]
                    ],
                    [
                        'name' => 'Узоры',
                        'items' => [
                            24 => ['text' => 'Пейсли', 'image' => 'img/anketa/question_51_7_1.png'],
                            25 => ['text' => 'Геометричный', 'image' => 'img/anketa/question_51_7_2.png'],
                            26 => ['text' => 'Орнамент', 'image' => 'img/anketa/question_51_7_3.png'],
                            27 => ['text' => 'Акварель', 'image' => 'img/anketa/question_51_7_4.png']
                        ]
                    ],
                ]
            ],
            52 => [
                'type' => 'check',
                'label' => 'Каких тканей нам следует избежать в подборке?',
                'option' => [0 => ['text' => 'Искусственный мех'],
                    1 => ['text' => 'Искусственная кожа'],
                    2 => ['text' => 'Натуральная кожа'],
                    3 => ['text' => 'Полиэстер'],
                    4 => ['text' => 'Шерсть'],
                ]
            ],
            53 => [
                'type' => 'text',
                'label' => 'Расскажите стилисту в свободной форме дополнительные нюансы. (Например, какой вырез или, возможно, модель одежды вы точно не носите)',
                'placeholder' => 'Я не ношу джинсы с высокой талией'
            ],

            54 => [
                'type' => 'choice',
                'label' => 'Ура, финишная прямая! Поговорим о бюджете. Напомню, что покупать все вещи из подборки необязательно, можно взять даже одну. Сколько вы готовы потратить на блузу / рубашку?',
                'option' => [0 => ['text' => '2000-3000 рублей'],
                    1 => ['text' => '3000-4000 рублей'],
                    2 => ['text' => '4000-5000 рублей'],
                    3 => ['text' => 'свыше 5000 рублей'],
                ]
            ],
            55 => [
                'type' => 'choice',
                'label' => 'Сколько вы готовы потратить на свитер / джемпер / пуловер?',
                'option' => [0 => ['text' => '3000-4000 рублей'],
                    1 => ['text' => '4000-5000 рублей'],
                    2 => ['text' => '5000-7000 рублей'],
                    3 => ['text' => 'свыше 7000 рублей'],
                ]
            ],
            56 => [
                'type' => 'choice',
                'label' => 'Сколько вы готовы потратить на платья / сарафаны?',
                'option' => [0 => ['text' => '3000-4000 рублей'],
                    1 => ['text' => '4000-6000 рублей'],
                    2 => ['text' => '6000-8000 рублей'],
                    3 => ['text' => 'свыше 8000 рублей'],
                ]
            ],
            57 => [
                'type' => 'choice',
                'label' => 'Сколько вы готовы потратить на жакет / пиджак?',
                'option' => [0 => ['text' => '4000-5000 рублей'],
                    1 => ['text' => '5000-7000 рублей'],
                    2 => ['text' => '7000-10000 рублей'],
                    3 => ['text' => 'свыше 10000 рублей'],
                ]
            ],
            58 => [
                'type' => 'choice',
                'label' => 'Сколько вы готовы потратить на джинсы / брюки / юбки?',
                'option' => [0 => ['text' => '3000-4000 рублей'],
                    1 => ['text' => '4000-5000 рублей'],
                    2 => ['text' => '5000-7000 рублей'],
                    3 => ['text' => 'свыше 7000 рублей'],
                ]
            ],
            59 => [
                'type' => 'choice',
                'label' => 'Сколько вы готовы потратить на сумки?',
                'option' => [0 => ['text' => '3000-4000 рублей'],
                    1 => ['text' => '4000-6000 рублей'],
                    2 => ['text' => '6000-8000 рублей'],
                    3 => ['text' => 'свыше 8000 рублей'],
                ]
            ],
            60 => [
                'type' => 'choice',
                'label' => 'Сколько вы готовы потратить на другие аксессуары: ремни, шарфы, платки?',
                'option' => [0 => ['text' => '1000-2000 рублей'],
                    1 => ['text' => '2000-3000 рублей'],
                    2 => ['text' => 'свыше 3000 рублей'],
                ]
            ],
            61 => [
                'type' => 'choice',
                'label' => 'Сколько вы готовы потратить на серьги / колье / браслеты?',
                'option' => [0 => ['text' => '1000-2000 рублей'],
                    1 => ['text' => '2000-3000 рублей'],
                    2 => ['text' => 'свыше 3000 рублей'],
                ]
            ],
            62 => [
                'type' => 'check',
                'label' => 'Для какой цели вы хотели бы подборку?',
                'option' => [
                    0 => ['text' => 'На каждый день'],
                    1 => ['text' => 'Одежда для дома'],
                    2 => ['text' => 'Сделать гардероб женственнее'],
                    3 => ['text' => 'Вечерние образы'],
                    4 => ['text' => 'На работу и бизнес-встречи'],
                    5 => ['text' => 'Добавить больше низа (юбки, джинсы, шорты)'],
                    6 => ['text' => 'Добавить больше верха (футболки, пиджаки, свитшоты)'],
                    7 => ['text' => 'Для путешествий, отпуска'],
                    8 => ['text' => 'Хочется больше платьев'],
                    9 => ['text' => 'Разнообразить гардероб'],
                    10 => ['text' => 'Собрать новый гардероб'],
                    11 => ['text' => 'Для прогулок с детьми, семейные вечера'],
                    12 => ['text' => 'Для встреч с друзьями'],
                    13 => ['text' => '', 'label' => 'Другое'],
                    14 => ['text' => 'Обновить рабочий гардероб'], // Под сбер!
                ],
                'placeholder' => 'Другое'

            ],
            63 => [
                'type' => 'text',
                'label' => 'Нашей курьерской службе потребуется ваш адрес: город, улица, дом и корпус',
                'placeholder' => 'Подольск, проспект Ленина, 128/24'
            ],

            64 => [
                'type' => 'text',
                'appeal' => ', ',
                'label' => 'когда вам удобно было бы получить доставку? Мы соберем подборку за 6 дней'
            ],

            65 => [
                'type' => 'check',
                'label' => 'В какое время вам было бы удобнее получить подборку?',
                'option' => [0 => ['text' => '10:00 - 14:00', 'link' => 0],
                    1 => ['text' => '14:00 - 18:00', 'link' => 0],
                    2 => ['text' => '18:00 - 22:00', 'link' => 0],
                    3 => ['text' => '12:00 - 18:00', 'link' => 1],
                    4 => ['text' => '18:00 - 22:00', 'link' => 1],
                ]
            ],
            66 => [
                'type' => 'check',
                'label' => 'А во сколько забрать на следующий день?',
                'option' => [0 => ['text' => '10:00 - 14:00', 'link' => 0],
                    1 => ['text' => '14:00 - 18:00', 'link' => 0],
                    2 => ['text' => '18:00 - 22:00', 'link' => 0],
                    3 => ['text' => '12:00 - 18:00', 'link' => 1],
                    4 => ['text' => '18:00 - 22:00', 'link' => 1],
                ]
            ],
            67 => [
                'type' => 'text',
                'label' => 'Укажите вашу фамилию. Она понадобятся нам для доставки',
                'labelC' => 'Укажите ваши имя и фамилию. Они понадобятся нам для доставки',
                'placeholder' => 'Имя'
            ],
            68 => [
                'type' => 'text',
                'label' => 'Стилисту будет проще подобрать для вас одежду по цвету, если вы покажете свои фото. Можно оставить ссылку или ник в соц.сетях или прикрепить фото в следующем вопросе 🙂',
                'placeholder' => '@the_capsula'
            ],
            69 => [
                'type' => 'choice',
                'appeal' => ', ',
                'label' => 'и последнее. Как вы о нас узнали?',
                'option' => [0 => ['text' => 'Instagram'],
                    1 => ['text' => 'Совет подруги'],
                    2 => ['text' => 'Поиск в Google / Yandex'],
                    3 => ['text' => 'Увидела у блогера'],
                    4 => ['text' => 'Статья в интернете'],
                    5 => ['text' => 'Рассылка'],
                ]
            ],
            70 => [
                'type' => 'text',
                'label' => 'Ваш вес (кг)',
                'placeholder' => 'Ваш вес (кг)'
            ],

            71 => [
                'type' => 'choice',
                'appeal' => ', ',
                'label' => 'остались данные для доставки. Напоминаем: вы не оплачиваете вещи заранее. Мы оставим их у вас на сутки на примерку, вы оплатите только то, что захотите 🙂<br><br>В какой город доставляем капсулу?',
                'option' => [0 => ['text' => 'Москва и Московская область'],
                    1 => ['text' => 'Санкт-Петербург и Ленинградская область'],
                    2 => ['text' => 'Другой город'],
                ]
            ],
            72 => [
                'type' => 'choice',
                'label' => 'Хотели бы вы загрузить несколько фото для вашего стилиста? (Необязательно)',
                'option' => [0 => ['text' => 'Да, готова прикрепить фото'],
                    1 => ['text' => 'Моих фото в социальных сетях достаточно'],
                ]
            ],
            73 => [
                'type' => 'files',
                'label' => '',
            ],
            74 => [
                'type' => 'text',
                'label' => 'Нашей курьерской службе потребуется ваш адрес: город, улица, дом и корпус',
                'placeholderMO' => 'г Москва, ул Вавилова, д 18',
                'placeholderSP' => 'г Санкт-Петербург, ул ул. Вавиловых, д 14'
            ],
            75 => [
                'type' => 'text',
                'label' => 'Нашей курьерской службе потребуется ваш адрес: город, улица, дом и корпус',
                'placeholder' => 'Квартира / номер офиса'
            ],
            76 => [
                'type' => 'text',
                'label' => 'Нашей курьерской службе потребуется ваш адрес: город, улица, дом и корпус',
                'placeholder' => 'Квартира / номер офиса'
            ],
            77 => [
                'type' => 'text',
                'label' => 'Нашей курьерской службе потребуется ваш адрес: город, улица, дом и корпус',
                'placeholder' => 'Дополнительный комментарий'
            ],
            78 => [
                'type' => 'choice',
                'label' => 'Привет, меня зовут Даша, я ваш персональный стилист. Расскажите, с чем Capsula может вам помочь?',
                'option' => [
                    0 => ['text' => 'Порадовать себя', 'emoji' => '&#127873;'],
                    1 => ['text' => 'Обновить гардероб', 'emoji' => '&#128717;'],
                    2 => ['text' => 'Получить совет от стилиста', 'emoji' => '&#128105;'],
                    3 => ['text' => 'Сэкономить время', 'emoji' => '&#8987;'],
                ]
            ],
            79 => [
                'type' => 'text',
                'placeholder' => 'Фамилия'
            ],
            80 => ['type' => 'choice',
                'appeal' => '',
                'label' => 'Как вам было бы удобнее получить капсулу?',
                'option' => [
                    0 => ['text' => 'Доставка курьером'],
                    1 => ['text' => 'Доставка в пункт выдачи'],
                ]
            ],
            81 => [
                'type' => 'text',
                'label' => 'В каком пункте выдачи вы бы хотели забрать капсулу? Подтвердите свой выбор, нажав на кнопку "Выбрать отделение" 🙂'
            ],
            82 => [
                'type' => 'text',
                'label' => 'У нас работает доставка в пункт выдачи. Введите ваш город',
                'placeholder' => 'Введите город'
            ],
            83 => [
                'type' => 'choice',
                'appeal' => ', ',
                'label' => 'на какой сезон вы хотите подборку?',
                'option' => [0 => ['text' => 'Лето'],
                    1 => ['text' => 'Осень'],
                    2 => ['text' => 'Зима'],
                    3 => ['text' => 'Весна'],
                    4 => ['text' => 'Демисезон'],
                ]
            ],
            84 => [
                'type' => 'text',
                'placeholder' => 'Отчество'
            ],
            85 => [ // choosingStyle25 81
                'type' => 'check',
                'label' => 'Что вы думаете про этот образ?',
                'image' => 'settings/anketa/styles/question_85.jpg',
                'option' => [0 => ['text' => 'Нравится весь образ', 'on_values' => [0, 1, 2, 3, 4, 5, 6]],
                    1 => [],
                    2 => [],
                    3 => [],
                    4 => [],
                    5 => [],
                    6 => [],
                    7 => ['text' => 'Ничего не понравилось', 'only_one' => true],
                ]
            ],
            86 => [ // choosingStyle26 82
                'type' => 'check',
                'label' => 'Что вы думаете про этот образ?',
                'image' => 'settings/anketa/styles/question_86.jpg',
                'option' => [0 => ['text' => 'Нравится весь образ', 'on_values' => [0, 1, 2, 3, 4, 5, 6]],
                    1 => [],
                    2 => [],
                    3 => [],
                    4 => [],
                    5 => [],
                    6 => [],
                    7 => ['text' => 'Ничего не понравилось', 'only_one' => true],
                ]
            ],
            87 => [  // choosingStyle27 83
                'type' => 'check',
                'label' => 'Что вы думаете про этот образ?',
                'image' => 'settings/anketa/styles/question_87.jpg',
                'option' => [0 => ['text' => 'Нравится весь образ', 'on_values' => [0, 1, 2, 3, 4, 5, 6]],
                    1 => [],
                    2 => [],
                    3 => [],
                    4 => [],
                    5 => [],
                    6 => [],
                    7 => ['text' => 'Ничего не понравилось', 'only_one' => true],
                ]
            ],
            88 => [  // choosingStyle28 84
                'type' => 'check',
                'label' => 'Что вы думаете про этот образ?',
                'image' => 'settings/anketa/styles/question_88.jpg',
                'option' => [0 => ['text' => 'Нравится весь образ', 'on_values' => [0, 1, 2, 3, 4, 5]],
                    1 => [],
                    2 => [],
                    3 => [],
                    4 => [],
                    5 => [],
                    6 => ['text' => 'Ничего не понравилось', 'only_one' => true],
                ]
            ],
            89 => [  // choosingStyle29 85
                'type' => 'check',
                'label' => 'Что вы думаете про этот образ?',
                'image' => 'settings/anketa/styles/question_89.jpg',
                'option' => [0 => ['text' => 'Нравится весь образ', 'on_values' => [0, 1, 2, 3, 4, 5]],
                    1 => [],
                    2 => [],
                    3 => [],
                    4 => [],
                    5 => [],
                    6 => ['text' => 'Ничего не понравилось', 'only_one' => true],
                ]
            ],
            90 => [  //choosingStyle30 86
                'type' => 'check',
                'label' => 'Что вы думаете про этот образ?',
                'image' => 'settings/anketa/styles/question_90.jpg',
                'option' => [0 => ['text' => 'Нравится весь образ', 'on_values' => [0, 1, 2, 3, 4, 5]],
                    1 => [],
                    2 => [],
                    3 => [],
                    4 => [],
                    5 => [],
                    6 => ['text' => 'Ничего не понравилось', 'only_one' => true],
                ]
            ],
            91 => [  // choosingStyle31 87
                'type' => 'check',
                'label' => 'Что вы думаете про этот образ?',
                'image' => 'settings/anketa/styles/question_91.jpg',
                'option' => [0 => ['text' => 'Нравится весь образ', 'on_values' => [0, 1, 2, 3, 4, 5]],
                    1 => [],
                    2 => [],
                    3 => [],
                    4 => [],
                    5 => [],
                    6 => ['ext' => 'Ничего не понравилось', 'only_one' => true],
                ]
            ],
            92 => [  // choosingStyle32 88
                'type' => 'check',
                'label' => 'Что вы думаете про этот образ?',
                'image' => 'settings/anketa/styles/question_92.jpg',
                'option' => [0 => ['text' => 'Нравится весь образ', 'on_values' => [0, 1, 2, 3, 4, 5, 6]],
                    1 => [],
                    2 => [],
                    3 => [],
                    4 => [],
                    5 => [],
                    6 => [],
                    7 => ['text' => 'Ничего не понравилось', 'only_one' => true],
                ]
            ],
            93 => [  // choosingStyle33 89
                'type' => 'check',
                'label' => 'Что вы думаете про этот образ?',
                'image' => 'settings/anketa/styles/question_93.jpg',
                'option' => [0 => ['text' => 'Нравится весь образ', 'on_values' => [0, 1, 2, 3, 4, 5]],
                    1 => [],
                    2 => [],
                    3 => [],
                    4 => [],
                    5 => [],
                    6 => ['text' => 'Ничего не понравилось', 'only_one' => true],
                ]
            ],
            94 => [  // choosingStyle34 90
                'type' => 'check',
                'label' => 'Что вы думаете про этот образ?',
                'image' => 'settings/anketa/styles/question_94.jpg',
                'option' => [0 => ['text' => 'Нравится весь образ', 'on_values' => [0, 1, 2, 3, 4, 5]],
                    1 => [],
                    2 => [],
                    3 => [],
                    4 => [],
                    5 => [],
                    6 => ['text' => 'Ничего не понравилось', 'only_one' => true],
                ]
            ],
        ];

    }

    private function setOldQuestions(){

        $this->answers =
            [
                0 => '',
                1 => [],
                2 => [],
                3 => NULL,
                4 => [],
                5 => [],
                6 => [],
                7 => [],
                8 => [],
                9 => [],
                10 => [],
                11 => [],
                12 => [],
                13 => [],
                14 => '',
                15 => '',
                16 => [],
                17 => [],
                18 => [],
                19 => [],
                20 => [],
                21 => [],
                22 => [],
                23 => [],
                24 => [],
                25 => [],
                26 => '',
                27 => '',
                28 => NULL,
                29 => NULL,
                30 => [],
                31 => [],
                32 => '',
                33 => '',
                34 => '',
                35 => [],
                36 => [],
                37 => [],
                38 => [],
                39 => [],
                40 => [],
                41 => [],
                42 => NULL,
                43 => [],
                44 => NULL,
                45 => NULL,
                46 => NULL,
                47 => [],
                48 => [],
                49 => [],
                50 => [],
                /*51 =>[]*/
                51 => [],
                52 => [],
                53 => '',
                54 => NULL,
                55 => NULL,
                56 => NULL,
                57 => NULL,
                58 => NULL,
                59 => NULL,
                60 => NULL,
                61 => NULL,
                62 => [],
                63 => '',
                64 => date('Y-m-d', strtotime('+6 days')),
                65 => [],
                66 => [],
                67 => '',
                68 => '',
                69 => NULL,
                70 => '',

                71 => NULL,
                72 => 1,
                73 => '',
                74 => '',
                75 => '',
                76 => '',
                77 => '',
                78 => NULL,
                79 => '',
                80 => NULL,
                81 => NULL,
                82 =>  '',
                83 => NULL,
                84 => '',

                85 => [],
                86 => [],
                87 => [],
                88 => [],
                89 => [],
                90 => [],
                91 => [],
                92 => [],
                93 => [],
                94 => [],
            ];

        //до 73 вопр вкл старые с 74- новые
        $this->questions = [
            0 => [	'type' => 'text',
                'label' => 'Привет, меня зовут Даша. Я – менеджер по работе со стилистами Capsula. Подробно расскажите нам о себе, это поможет нам подобрать идеальную капсулу персонально для вас',
                'answer' => '',
                'placeholder' => 'Как вас зовут?'
            ],
            1 => [	'type' => 'choice',
                'label' => ', приятно познакомиться!<br><br>Расскажите, какой стиль вы предпочитаете в выходные?',
                'option' => [	0 => ['text' => 'Business casual<br><span class="hint">пиджаки, рубашки, брюки</span>'],
                    1 => ['text' => 'Casual<br><span class="hint">джинсы, топы, уличный стиль</span>'],
                    2 => ['text' => 'Спортивный стиль<br><span class="hint">удобство превыше всего</span>'],
                ],
                'answer' => NULL,
            ],
            2 => [	'type' => 'choice',
                'label' => 'Как вы одеваетесь на работу?',
                'option' => [	0 => ['text' => 'Строгий дресс-код'],
                    1 => ['text' => 'Нестрогий дресс-код'],
                    2 => ['text' => 'Casual'],
                ],
                'answer' => NULL,
            ],
            3 => [	'type' => 'choice',
                'label' => 'Поняла.<br><br>Вы хотите, чтобы стилист подобрал что-то отличное от того, к чему вы привыкли, или сохранил ваш стиль?',
                'option' => [	0 => ['text' => 'Хочу абсолютно новый стиль. Готова к экспериментам'],
                    1 => ['text' => '50/50 - действуем аккуратно'],
                    2 => ['text' => 'Хочу, чтобы мой стиль остался неизменным'],
                ],
                'answer' => NULL,
            ],
            4 => [	'type' => 'check',
                'label' => 'Отлично! Далее я покажу несколько образов. Расскажите свое мнение о них, чтобы мы лучше поняли ваши предпочтения',
                'image' => 'img/anketa/question_4.png',
                'option' => [	0 => ['text' => 'Нравится весь образ', 'on_values' => [0, 1, 2, 3, 4, 5]],
                    1 => [],
                    2 => [],
                    3 => [],
                    4 => [],
                    5 => [],
                    6 => ['text' => 'Ничего не понравилось', 'only_one' => true],
                ],
                'answer' => [],
            ],
            5 => [	'type' => 'check',
                'label' => 'Что вы думаете про этот образ?',
                'image' => 'img/anketa/question_5.png',
                'option' => [	0 => ['text' => 'Нравится весь образ', 'on_values' => [0, 1, 2, 3, 4, 5]],
                    1 => [],
                    2 => [],
                    3 => [],
                    4 => [],
                    5 => [],
                    6 => ['text' => 'Ничего не понравилось', 'only_one' => true],
                ],
                'answer' => [],
            ],
            6 => [	'type' => 'check',
                'label' => 'Что вы думаете про этот образ?',
                'image' => 'img/anketa/question_6.png',
                'option' => [	0 => ['text' => 'Нравится весь образ', 'on_values' => [0, 1, 2, 3, 4]],
                    1 => [],
                    2 => [],
                    3 => [],
                    4 => [],
                    5 => ['text' => 'Ничего не понравилось', 'only_one' => true],
                ],
                'answer' => [],
            ],
            7 => [	'type' => 'check',
                'label' => 'Что вы думаете про этот образ?',
                'image' => 'img/anketa/question_7.png',
                'option' => [	0 => ['text' => 'Нравится весь образ', 'on_values' => [0, 1, 2, 3, 4]],
                    1 => [],
                    2 => [],
                    3 => [],
                    4 => [],
                    5 => ['text' => 'Ничего не понравилось', 'only_one' => true],
                ],
                'answer' => [],
            ],
            8 => [	'type' => 'check',
                'label' => 'Что вы думаете про этот образ?',
                'image' => 'img/anketa/question_8.png',
                'option' => [	0 => ['text' => 'Нравится весь образ', 'on_values' => [0, 1, 2, 3, 4]],
                    1 => [],
                    2 => [],
                    3 => [],
                    4 => [],
                    5 => ['text' => 'Ничего не понравилось', 'only_one' => true],
                ],
                'answer' => [],
            ],
            9 => [	'type' => 'check',
                'label' => 'Что вы думаете про этот образ?',
                'image' => 'img/anketa/question_9.png',
                'option' => [	0 => ['text' => 'Нравится весь образ', 'on_values' => [0, 1, 2, 3, 4, 5]],
                    1 => [],
                    2 => [],
                    3 => [],
                    4 => [],
                    5 => [],
                    6 => ['text' => 'Ничего не понравилось', 'only_one' => true],
                ],
                'answer' => [],
            ],
            10 => [	'type' => 'check',
                'label' => 'Что вы думаете про этот образ?',
                'image' => 'img/anketa/question_10.png',
                'option' => [	0 => ['text' => 'Нравится весь образ', 'on_values' => [0, 1, 2, 3, 4]],
                    1 => [],
                    2 => [],
                    3 => [],
                    4 => [],
                    5 => ['text' => 'Ничего не понравилось', 'only_one' => true],
                ],
                'answer' => [],
            ],
            11 => [	'type' => 'check',
                'label' => 'Что вы думаете про этот образ?',
                'image' => 'img/anketa/question_11.png',
                'option' => [	0 => ['text' => 'Нравится весь образ', 'on_values' => [0, 1, 2, 3, 4, 5]],
                    1 => [],
                    2 => [],
                    3 => [],
                    4 => [],
                    5 => [],
                    6 => ['text' => 'Ничего не понравилось', 'only_one' => true],
                ],
                'answer' => [],
            ],
            12 => [	'type' => 'check',
                'label' => 'Что вы думаете про этот образ?',
                'image' => 'img/anketa/question_12.png',
                'option' => [	0 => ['text' => 'Нравится весь образ', 'on_values' => [0, 1, 2, 3, 4]],
                    1 => [],
                    2 => [],
                    3 => [],
                    4 => [],
                    5 => ['text' => 'Ничего не понравилось', 'only_one' => true],
                ],
                'answer' => [],
            ],
            13 => [	'type' => 'check',
                'label' => 'Что вы думаете про этот образ?',
                'image' => 'img/anketa/question_13.png',
                'option' => [	0 => ['text' => 'Нравится весь образ', 'on_values' => [0, 1, 2, 3, 4]],
                    1 => [],
                    2 => [],
                    3 => [],
                    4 => [],
                    5 => ['text' => 'Ничего не понравилось', 'only_one' => true],
                ],
                'answer' => [],
            ],
            14 => [	'type' => 'text',
                'label' => ', спасибо! Заполните контактные данные - они нужны, чтобы уточнять некоторые детали. Обещаем не надоедать со спамом 🙂',
                'answer' => ''
            ],
            15 => [	'type' => 'text',
                'label' => 'Укажите ваш номер телефона',
                'answer' => ''
            ],
            16 => [	'type' => 'check',
                'label' => 'Что вы думаете про этот образ?',
                'image' => 'img/anketa/question_16.png',
                'option' => [	0 => ['text' => 'Нравится весь образ', 'on_values' => [0, 1, 2, 3, 4]],
                    1 => [],
                    2 => [],
                    3 => [],
                    4 => [],
                    5 => ['text' => 'Ничего не понравилось', 'only_one' => true],
                ],
                'answer' => [],
            ],
            17 => [	'type' => 'check',
                'label' => 'Что вы думаете про этот образ?',
                'image' => 'img/anketa/question_17.png',
                'option' => [	0 => ['text' => 'Нравится весь образ', 'on_values' => [0, 1, 2, 3]],
                    1 => [],
                    2 => [],
                    3 => [],
                    4 => ['text' => 'Ничего не понравилось', 'only_one' => true],
                ],
                'answer' => [],
            ],
            18 => [	'type' => 'check',
                'label' => 'Что вы думаете про этот образ?',
                'image' => 'img/anketa/question_18.png',
                'option' => [	0 => ['text' => 'Нравится весь образ', 'on_values' => [0, 1, 2, 3]],
                    1 => [],
                    2 => [],
                    3 => [],
                    4 => ['text' => 'Ничего не понравилось', 'only_one' => true],
                ],
                'answer' => [],
            ],
            19 => [	'type' => 'check',
                'label' => 'Что вы думаете про этот образ?',
                'image' => 'img/anketa/question_19.png',
                'option' => [	0 => ['text' => 'Нравится весь образ', 'on_values' => [0, 1, 2, 3, 4]],
                    1 => [],
                    2 => [],
                    3 => [],
                    4 => [],
                    5 => ['text' => 'Ничего не понравилось', 'only_one' => true],
                ],
                'answer' => [],
            ],
            20 => [	'type' => 'check',
                'label' => 'Что вы думаете про этот образ?',
                'image' => 'img/anketa/question_20.png',
                'option' => [	0 => ['text' => 'Нравится весь образ', 'on_values' => [0, 1, 2, 3, 4]],
                    1 => [],
                    2 => [],
                    3 => [],
                    4 => [],
                    5 => ['text' => 'Ничего не понравилось', 'only_one' => true],
                ],
                'answer' => [],
            ],
            21 => [	'type' => 'check',
                'label' => 'Что вы думаете про этот образ?',
                'image' => 'img/anketa/question_21.png',
                'option' => [	0 => ['text' => 'Нравится весь образ', 'on_values' => [0, 1, 2, 3, 4]],
                    1 => [],
                    2 => [],
                    3 => [],
                    4 => [],
                    5 => ['text' => 'Ничего не понравилось', 'only_one' => true],
                ],
                'answer' => [],
            ],
            22 => [	'type' => 'check',
                'label' => 'Что вы думаете про этот образ?',
                'image' => 'img/anketa/question_22.png',
                'option' => [	0 => ['text' => 'Нравится весь образ', 'on_values' => [0, 1, 2, 3, 4, 5]],
                    1 => [],
                    2 => [],
                    3 => [],
                    4 => [],
                    5 => [],
                    6 => ['text' => 'Ничего не понравилось', 'only_one' => true],
                ],
                'answer' => [],
            ],
            23 => [	'type' => 'check',
                'label' => 'Что вы думаете про этот образ?',
                'image' => 'img/anketa/question_23.png',
                'option' => [	0 => ['text' => 'Нравится весь образ', 'on_values' => [0, 1, 2, 3, 4, 5]],
                    1 => [],
                    2 => [],
                    3 => [],
                    4 => [],
                    5 => [],
                    6 => ['text' => 'Ничего не понравилось', 'only_one' => true],
                ],
                'answer' => [],
            ],
            24 => [	'type' => 'check',
                'label' => 'Что вы думаете про этот образ?',
                'image' => 'img/anketa/question_24.png',
                'option' => [	0 => ['text' => 'Нравится весь образ', 'on_values' => [0, 1, 2, 3, 4, 5]],
                    1 => [],
                    2 => [],
                    3 => [],
                    4 => [],
                    5 => [],
                    6 => ['text' => 'Ничего не понравилось', 'only_one' => true],
                ],
                'answer' => [],
            ],
            25 => [	'type' => 'check',
                'label' => 'Какая цветовая гамма вам ближе? Можно выбрать несколько 🙂',
                'option' => [	0 => ['text' => 'Серый монохром', 'image' => 'img/anketa/question_25_0.png',
                    'colors' => ['#FFFFFF', '#3B3838', '#757070', '#000000', '#CFCDCD', '#F1F1F1', '#7F7F7F', '#404040', '#DADADA'] ],
                    1 => ['text' => 'Бежевый монохром', 'image' => 'img/anketa/question_25_1.png',
                        'colors' => ['#BCA181', '#786058', '#F0DECB', '#D5CBBD', '#E5D1B1', '#392D2B', '#5B3022', '#FDF2E4', '#BC9887', ]],
                    2 => ['text' => 'Пастельная', 'image' => 'img/anketa/question_25_2.png',
                        'colors' => ['#D0DAF5', '#E5EFDB', '#F1D7CF', '#FDF2D0', '#F7E5D8', '#E3E4E6', '#EED4EC', '#DBD3C7', '#E0EBF6', ]],
                    3 => ['text' => 'Мягкая', 'image' => 'img/anketa/question_25_3.png',
                        'colors' => ['#484D67', '#D5CBBC', '#9C6F73', '#D1918E', '#5A7F6C', '#796F64', '#6C394D', '#B9B9AC', '#456C79',  ]],
                    4 => ['text' => 'Тёмная', 'image' => 'img/anketa/question_25_4.png',
                        'colors' => ['#151414', '#6C394D', '#3D2D2E', '#252626', '#4F555B', '#5B3022', '#5C1311', '#3F562A', '#000E54',  ]],
                    5 => ['text' => 'Яркая', 'image' => 'img/anketa/question_25_5.png',
                        'colors' => ['#F2EE50', '#3B7D7C', '#BB4239', '#AA3B80', '#312DB5', '#94B73D', '#448EC7', '#72F8D9', '#D6722E',  ]],
                ],
                'answer' => [],
            ],
            26 => [	'type' => 'text',
                'label' => ', мы уже знаем ваши предпочтения в одежде, теперь немного о вас 🙂',
                'placeholder' => 'Ваш рост (см)',
                'answer' => ''
            ],
            27 => [	'type' => 'text',
                'label' => 'Дата вашего рождения?',
                'placeholder' => '10.09.1995',
                'answer' => ''
            ],
            28 => [	'type' => 'choice',
                'label' => 'И еще немного:<br><br>Ваш род деятельности?',
                'option' => [	0 => ['text' => 'Маркетолог'],
                    1 => ['text' => 'Менеджер'],
                    2 => ['text' => 'Финансист'],
                    3 => ['text' => 'Медиа и коммуникации'],
                    4 => ['text' => 'Искусство / Творчество'],
                    5 => ['text' => 'Юрист'],
                    6 => ['text' => 'Предприниматель'],
                    7 => ['text' => 'Инженер'],
//												8 => ['text' => 'Архитектор'],
                    9 => ['text' => 'Студент'],
                    10 => ['text' => 'Дизайнер'],
                    11 => ['text' => 'IT'],
//					                            12 => ['text' => 'Консультант'],
                    13 => ['text' => 'Врач / Медицинский сотрудник'],
                    14 => ['text' => 'Ресторанный бизнес'],
//												15 => ['text' => 'Менеджмент'],
                    16 => ['text' => 'Личный помощник'],
                    17 => ['text' => 'Продавец'],
                    18 => ['text' => 'Сейчас не работаю'],
                    19 => ['text' => 'Другое'],
                ],
                'answer' => NULL,
            ],
            29 => [	'type' => 'choice',
                'label' => 'Ваш цвет волос?',
                'option' => [	0 => ['text' => 'Блонд'],
                    1 => ['text' => 'Брюнет'],
                    2 => ['text' => 'Шатен'],
                    3 => ['text' => 'Рыжий'],
                    4 => ['text' => 'Русый'],
                ],
                'answer' => NULL,
            ],
            30 => [	'type' => 'check',
                'label' => 'Класс, спасибо!<br>Дальше несколько вопросов о ваших размерах<br>Российский размер верха (платья, блузы, жакеты, свитеры и др.)',
                'option' => [	0 => ['text' => '40'],
                    1 => ['text' => '42'],
                    2 => ['text' => '44'],
                    3 => ['text' => '46'],
                    4 => ['text' => '48'],
                ],
                'answer' => [],
            ],
            31 => [	'type' => 'check',
                'label' => 'Российский размер низа (брюки, джинсы, юбки)',
                'option' => [	0 => ['text' => '40'],
                    1 => ['text' => '42'],
                    2 => ['text' => '44'],
                    3 => ['text' => '46'],
                    4 => ['text' => '48'],
                ],
                'answer' => [],
            ],
            32 => [	'type' => 'text',
                'label' => 'Замеры помогут стилисту точнее определить размер. Отвечать необязательно 🙂',
                'placeholder' => 'Объем груди (см)',
                'answer' => ''
            ],
            33 => [	'type' => 'text',
                'label' => 'Ваш объем талии (см) (необязательно)',
                'placeholder' => 'Объем талии (см)',
                'answer' => ''
            ],
            34 => [	'type' => 'text',
                'label' => 'Ваш объем бедер (см) (необязательно)',
                'placeholder' => 'Объем бедер (см)',
                'answer' => ''
            ],
            35 => [	'type' => 'check',
                'label' => 'Вы - молодец, уже прошли больше половины пути. Расскажите теперь немного о вашем стиле.<br><br>Как вам комфортнее носить блузы/рубашки/джемперы?',
                'option' => [	0 => ['text' => 'Облегающе'],
                    1 => ['text' => 'По размеру'],
                    2 => ['text' => 'Свободно'],
                ],
                'answer' => [],
            ],
            36 => [	'type' => 'check',
                'label' => 'Принято! А если говорить о брюках/джинсах?',
                'option' => [	0 => ['text' => 'Облегающе'],
                    1 => ['text' => 'По размеру'],
                    2 => ['text' => 'Свободно'],
                ],
                'answer' => [],
            ],
            37 => [	'type' => 'check',
                'label' => ', какие модели джинсов вы предпочитаете?',
                'option' => [	0 => ['text' => 'Скинни', 'image' => 'img/anketa/question_37_0.png'],
                    1 => ['text' => 'Прямые', 'image' => 'img/anketa/question_37_1.png'],
                    2 => ['text' => 'Широкие', 'image' => 'img/anketa/question_37_2.png'],
                    3 => ['text' => 'Клеш', 'image' => 'img/anketa/question_37_3.png'],
                    4 => ['text' => 'МОМ', 'image' => 'img/anketa/question_37_4.png'],
                ],
                'answer' => [],
            ],
            38 => [	'type' => 'check',
                'label' => 'А посадку?',
                'option' => [	0 => ['text' => 'Высокая', 'image' => 'img/anketa/question_38_0.png'],
                    1 => ['text' => 'Средняя', 'image' => 'img/anketa/question_38_1.png'],
                    2 => ['text' => 'Низкая', 'image' => 'img/anketa/question_38_2.png'],
                ],
                'answer' => [],
            ],
            39 => [	'type' => 'check',
                'label' => 'И наконец длина джинсов / брюк',
                'option' => [	0 => ['text' => 'Укороченные', 'image' => 'img/anketa/question_39_0.png'],
                    1 => ['text' => 'Стандартные', 'image' => 'img/anketa/question_39_1.png'],
                    2 => ['text' => 'Длинные', 'image' => 'img/anketa/question_39_2.png'],
                ],
                'answer' => [],
            ],
            40 => [	'type' => 'check',
                'label' => 'Какие юбки / платья вы предпочитаете?',
                'option' => [	0 => ['text' => 'Мини', 'image' => 'img/anketa/question_40_0.png'],
                    1 => ['text' => 'Миди', 'image' => 'img/anketa/question_40_1.png'],
                    2 => ['text' => 'Макси', 'image' => 'img/anketa/question_40_2.png'],
                ],
                'answer' => [],
            ],
            41 => [	'type' => 'check',
                'label' => ', есть части тела, которые вы предпочитаете скрывать?',
                'option' => [	0 => ['text' => 'Руки'],
                    1 => ['text' => 'Плечи'],
                    2 => ['text' => 'Спина'],
                    3 => ['text' => 'Декольте'],
                    4 => ['text' => 'Талия'],
                    5 => ['text' => 'Ноги'],
                    6 => ['text' => 'Ничего не скрываю', 'only_one' => true],
                ],
                'answer' => [],
            ],
            42 => [	'type' => 'choice',
                'label' => 'Вы больше любите джинсы с топом или платья?',
                'option' => [	0 => ['text' => 'Больше джинсы / футболки / свитера'],
                    1 => ['text' => 'Больше платья'],
                    2 => ['text' => 'Примерно 50/50'],
                ],
                'answer' => NULL,
            ],
            43 => [	'type' => 'check',
                'label' => 'Расскажите, где вы обычно покупаете одежду. Это поможет стилисту точнее понять ваше предпочтения',
                'option' => [	0 => ['text' => 'Zara'],
                    1 => ['text' => 'H&M'],
                    2 => ['text' => 'Pull&Bear'],
                    3 => ['text' => 'Topshop'],
                    4 => ['text' => 'Mango'],
                    5 => ['text' => 'Reserved'],
                    6 => ['text' => 'Massimo Dutti'],
                    7 => ['text' => 'Calvin Klein'],
                    8 => ['text' => 'Twin Set'],
                    9 => ['text' => 'Lamoda'],
                    10 => ['text' => 'Wildberries'],
                    11 => ['text' => '12storeez'],
                    12 => ['text' => 'Farfetch'],
                    13 => ['text' => 'Asos'],
                    14 => ['text' => 'Yoox'],
                    15 => ['text' => 'Ничего из этого', 'only_one' => true],
                ],
                'answer' => [],
            ],
            44 => [	'type' => 'choice',
                'label' => 'Проколоты ли у вас уши?',
                'option' => [	0 => ['text' => 'Да'],
                    1 => ['text' => 'Нет'],
                ],
                'answer' => NULL,
            ],

            45 => [	'type' => 'choice',
                'label' => 'Какие ювелирные изделия вы предпочитаете?',
                'option' => [	0 => ['text' => 'Серебро'],
                    1 => ['text' => 'Золото'],
                    2 => ['text' => '50/50'],
                ],
                'answer' => NULL,
            ],
            46 => [	'type' => 'choice',
                'label' => 'А к бижутерии готовы?',
                'option' => [	0 => ['text' => 'Да'],
                    1 => ['text' => 'Нет'],
                ],
                'answer' => NULL,
            ],
            47 => [	'type' => 'check',
                'label' => 'Каждая наша капсула собирается персонально для вас. Что вы хотите получить в капсуле прежде всего?',
                'option' => [	0 => ['text' => 'Футболки / рубашки / блузы', 'image' => 'img/anketa/question_47_0.png'],
                    1 => ['text' => 'Свитера / кардиганы / пуловеры', 'image' => 'img/anketa/question_47_1.png'],
                    2 => ['text' => 'Пиджаки / жакеты', 'image' => 'img/anketa/question_47_2.png'],
                    3 => ['text' => 'Джинсы', 'image' => 'img/anketa/question_47_3.png'],
                    4 => ['text' => 'Брюки', 'image' => 'img/anketa/question_47_4.png'],
                    5 => ['text' => 'Шорты', 'image' => 'img/anketa/question_47_5.png'],
                    6 => ['text' => 'Платья', 'image' => 'img/anketa/question_47_6.png'],
                    7 => ['text' => 'Юбки', 'image' => 'img/anketa/question_47_7.png'],
                    8 => ['text' => 'Готова на все', 'image' => 'img/anketa/question_47_8.png'],
                ],
                'answer' => [],
            ],
            48 => [	'type' => 'check',
                'label' => 'Отлично! А какую одежду вы бы не хотели видеть в капсуле?',
                'option' => [	0 => ['text' => 'Футболки / рубашки / блузы', 'image' => 'img/anketa/question_47_0.png'],
                    1 => ['text' => 'Свитера / кардиганы / пуловеры', 'image' => 'img/anketa/question_47_1.png'],
                    2 => ['text' => 'Пиджаки / жакеты', 'image' => 'img/anketa/question_47_2.png'],
                    3 => ['text' => 'Джинсы', 'image' => 'img/anketa/question_47_3.png'],
                    4 => ['text' => 'Брюки', 'image' => 'img/anketa/question_47_4.png'],
                    5 => ['text' => 'Шорты', 'image' => 'img/anketa/question_47_5.png'],
                    6 => ['text' => 'Платья', 'image' => 'img/anketa/question_47_6.png'],
                    7 => ['text' => 'Юбки', 'image' => 'img/anketa/question_47_7.png'],
                ],
                'answer' => [],
            ],
            49 => [	'type' => 'check',
                'label' => ', какие аксессуары вы точно не хотите получить?',
                'option' => [	0 => ['text' => 'Сумки', 'image' => 'img/anketa/question_49_0.png'],
                    1 => ['text' => 'Шарфы', 'image' => 'img/anketa/question_49_1.png'],
                    2 => ['text' => 'Сережки', 'image' => 'img/anketa/question_49_2.png'],
                    3 => ['text' => 'Колье / аксессуары на шею', 'image' => 'img/anketa/question_49_3.png'],
                    4 => ['text' => 'Ремни', 'image' => 'img/anketa/question_49_4.png'],
                ],
                'answer' => [],
            ],
            50 => [	'type' => 'check',
                'label' => 'Осталось совсем немного 😄<br><br>Какие цвета вы бы НЕ хотели видеть в капсуле?',
                'option' => [	0 => ['text' => 'Чёрный','color' => '#090909'],
                    1 => ['text' => 'Коричневый','color' => '#633f13'],
                    2 => ['text' => 'Бежевый','color' => '#d0bd92'],
                    3 => ['text' => 'Белый','color' => '#ffffff'],
                    4 => ['text' => 'Серый','color' => '#a6a6a6'],
                    5 => ['text' => 'Синий','color' => '#0e2076'],
                    6 => ['text' => 'Голубой','color' => '#007bff'],
                    7 => ['text' => 'Светло-голубой','color' => '#cdd9f7'],
                    8 => ['text' => 'Морской','color' => '#177e7c'],
                    9 => ['text' => 'Зеленый','color' => '#88b913'],
                    10 => ['text' => 'Желтый','color' => '#f1ef07'],
                    11 => ['text' => 'Оранжевый','color' => '#ed6e2d'],
                    12 => ['text' => 'Красный','color' => '#d7352c'],
                    13 => ['text' => 'Бордовый','color' => '#a12a14'],
                    14 => ['text' => 'Фиолетовый','color' => '#6f2f9f'],
                    15 => ['text' => 'Розовый','color' => '#f3d1ed'],
                ],
                'answer' => [],
            ],
            51 => [	'type' => 'check',
                'label' => 'Принты, которые вы точно НЕ хотели бы видеть в капсуле?',
                'option' => [
                    0 => ['text' => 'Принты животных (например: змеиная кожа или леопард)', 'image' => 'img/archiv/question_51_1.jpg'],
                    1 => ['text' => 'Цветочные принты', 'image' => 'img/archiv/question_51_2.jpg'],
                    2 => ['text' => 'Горошек', 'image' => 'img/archiv/question_51_3.png'],
                    3 => ['text' => 'Полоски', 'image' => 'img/archiv/question_51_4.jpeg'],
                    4 => ['text' => 'Клетка', 'image' => 'img/archiv/question_51_5.jpg'],
                    5 => ['text' => 'Узоры', 'image' => 'img/archiv/question_51_6.jpg'],
                    6 => ['text' => 'Печатные принты', 'image' => 'img/archiv/question_51_7.png'],
                ],
                'answer' => [],
            ],
            52 => [	'type' => 'check',
                'label' => 'Каких тканей нам следует избежать в подборке?',
                'option' => [	0 => ['text' => 'Искусственный мех'],
                    1 => ['text' => 'Искусственная кожа'],
                    2 => ['text' => 'Натуральная кожа'],
                    3 => ['text' => 'Полиэстер'],
                    4 => ['text' => 'Шерсть'],
                ],
                'answer' => [],
            ],
            53 => [	'type' => 'text',
                'label' => 'Расскажите стилисту в свободной форме дополнительные нюансы. (Например, какой вырез или, возможно, модель одежды вы точно не носите)',
                'placeholder' => 'Я не ношу джинсы с высокой талией',
                'answer' => ''
            ],

            54 => [	'type' => 'choice',
                'label' => 'Ура, финишная прямая! Поговорим о бюджете. Напомню, что покупать все вещи из подборки необязательно, можно взять даже одну. Сколько вы готовы потратить на блузу / рубашку?',
                'option' => [	0 => ['text' => '2000-3000 рублей'],
                    1 => ['text' => '3000-4000 рублей'],
                    2 => ['text' => '4000-5000 рублей'],
                    3 => ['text' => 'свыше 5000 рублей'],
                ],
                'answer' => NULL,
            ],
            55 => [	'type' => 'choice',
                'label' => 'Сколько вы готовы потратить на свитер / джемпер / пуловер?',
                'option' => [	0 => ['text' => '3000-4000 рублей'],
                    1 => ['text' => '4000-5000 рублей'],
                    2 => ['text' => '5000-7000 рублей'],
                    3 => ['text' => 'свыше 7000 рублей'],
                ],
                'answer' => NULL,
            ],
            56 => [	'type' => 'choice',
                'label' => 'Сколько вы готовы потратить на платья / сарафаны?',
                'option' => [	0 => ['text' => '3000-4000 рублей'],
                    1 => ['text' => '4000-6000 рублей'],
                    2 => ['text' => '6000-8000 рублей'],
                    3 => ['text' => 'свыше 8000 рублей'],
                ],
                'answer' => NULL,
            ],
            57 => [	'type' => 'choice',
                'label' => 'Сколько вы готовы потратить на жакет / пиджак?',
                'option' => [	0 => ['text' => '4000-5000 рублей'],
                    1 => ['text' => '5000-7000 рублей'],
                    2 => ['text' => '7000-10000 рублей'],
                    3 => ['text' => 'свыше 10000 рублей'],
                ],
                'answer' => NULL,
            ],
            58 => [	'type' => 'choice',
                'label' => 'Сколько вы готовы потратить на джинсы / брюки / юбки?',
                'option' => [	0 => ['text' => '3000-4000 рублей'],
                    1 => ['text' => '4000-5000 рублей'],
                    2 => ['text' => '5000-7000 рублей'],
                    3 => ['text' => 'свыше 7000 рублей'],
                ],
                'answer' => NULL,
            ],
            59 => [	'type' => 'choice',
                'label' => 'Сколько вы готовы потратить на сумки?',
                'option' => [	0 => ['text' => '3000-4000 рублей'],
                    1 => ['text' => '4000-6000 рублей'],
                    2 => ['text' => '6000-8000 рублей'],
                    3 => ['text' => 'свыше 8000 рублей'],
                ],
                'answer' => NULL,
            ],
            60 => [	'type' => 'choice',
                'label' => 'Сколько вы готовы потратить на другие аксессуары: ремни, шарфы, платки?',
                'option' => [	0 => ['text' => '1000-2000 рублей'],
                    1 => ['text' => '2000-3000 рублей'],
                    2 => ['text' => 'свыше 3000 рублей'],
                ],
                'answer' => NULL,
            ],
            61 => [	'type' => 'choice',
                'label' => 'Сколько вы готовы потратить на серьги / колье / браслеты?',
                'option' => [	0 => ['text' => '1000-2000 рублей'],
                    1 => ['text' => '2000-3000 рублей'],
                    2 => ['text' => 'свыше 3000 рублей'],
                ],
                'answer' => NULL,
            ],
            62 => [	'type' => 'text',
                'label' => 'Вы молодец.<br><br>Для какой цели вы хотели бы подборку?',
                'answer' => '',
                'placeholder' => 'Прежде всего мне нужны комфортные вещи, но особое внимание нужно обратить на то, что я не люблю V-образный вырез и геометрические принты'

            ],
            63 => [	'type' => 'text',
                'label' => 'Нашей курьерской службе потребуется ваш адрес: город, улица, дом и корпус',
                'answer' => '',
                'placeholder' => 'Подольск, проспект Ленина, 128/24',
            ],
            64 => [	'type' => 'text',
                'label' => ', когда вам удобно было бы получить доставку? Мы соберем подборку за 7 дней',
                'answer' => date('Y-m-d', strtotime('+7 days')),
            ],
            65 => [	'type' => 'check',
                'label' => 'В какое время вам было бы удобнее получить подборку?',
                'option' => [	0 => ['text' => '10:00 - 14:00', 'link' => 0],
                    1 => ['text' => '14:00 - 18:00', 'link' => 0],
                    2 => ['text' => '18:00 - 22:00', 'link' => 0],
                    3 => ['text' => '12:00 - 18:00', 'link' => 1],
                    4 => ['text' => '18:00 - 22:00', 'link' => 1],
                ],
                'answer' => [],
            ],
            66 => [	'type' => 'check',
                'label' => 'А во сколько забрать на следующий день?',
                'option' => [	0 => ['text' => '10:00 - 14:00', 'link' => 0],
                    1 => ['text' => '14:00 - 18:00', 'link' => 0],
                    2 => ['text' => '18:00 - 22:00', 'link' => 0],
                    3 => ['text' => '12:00 - 18:00', 'link' => 1],
                    4 => ['text' => '18:00 - 22:00', 'link' => 1],
                ],
                'answer' => [],
            ],
            67 => [	'type' => 'text',
                'label' => 'Укажите вашу фамилию. Она нужна для доставки',
                'answer' => ''
            ],
            68 => [	'type' => 'text',
                'label' => 'Стилисту будет проще подобрать для вас одежду по цвету, если вы покажете свои фото. Можно оставить ссылку или ник в соц.сетях или прикрепить фото в следующем вопросе 🙂',
                'answer' => '',
                'placeholder' => '@the_capsula'
            ],
            69 => [	'type' => 'choice',
                'label' => ', и последнее. Как вы о нас узнали?',
                'option' => [	0 => ['text' => 'Instagram'],
                    1 => ['text' => 'Совет подруги'],
                    2 => ['text' => 'Поиск в Google / Yandex'],
                    3 => ['text' => 'Увидела у блогера'],
                    4 => ['text' => 'Статья в интернете'],
                    5 => ['text' => 'Рассылка'],
                ],
                'answer' => NULL,
            ],
            70 => [	'type' => 'text',
                'label' => 'Ваш вес (кг)',
                'placeholder' => 'Ваш вес (кг)',
                'answer' => ''
            ],

            71 => [	'type' => 'choice',
                'label' => ', остались данные для доставки. Напоминаем: вы не оплачиваете вещи заранее. Мы оставим их у вас на сутки на примерку, вы оплатите только то, что захотите 🙂<br><br>В какой город доставляем капсулу?',
                'option' => [	0 => ['text' => 'Москва и Московская область'],
                    1 => ['text' => 'Санкт-Петербург и Ленинградская область'],
                    2 => ['text' => 'Другой город - доставка в конце июля'],
                ],
                'answer' => NULL,
            ],
            72 => [	'type' => 'choice',
                'label' => 'Хотели бы вы загрузить несколько фото для вашего стилиста? (Необязательно)',
                'option' => [	0 => ['text' => 'Да, готова прикрепить фото'],
                    1 => ['text' => 'Моих фото в социальных сетях достаточно'],
                ],
                'answer' => 1,
            ],
            73 => [	'type' => 'files',
                'label' => '',
            ],
            74 => [
                'type' => 'text',
                'label' => 'Нашей курьерской службе потребуется ваш адрес: город, улица, дом и корпус',
                'placeholderMO' => 'г Москва, ул Вавилова, д 18',
                'placeholderSP' => 'г Санкт-Петербург, ул ул. Вавиловых, д 14'
            ],
            75 => [
                'type' => 'text',
                'label' => 'Нашей курьерской службе потребуется ваш адрес: город, улица, дом и корпус',
                'placeholder' => 'Квартира / номер офиса'
            ],
            76 => [
                'type' => 'text',
                'label' => 'Нашей курьерской службе потребуется ваш адрес: город, улица, дом и корпус',
                'placeholder' => 'Квартира / номер офиса'
            ],
            77 => [
                'type' => 'text',
                'label' => 'Нашей курьерской службе потребуется ваш адрес: город, улица, дом и корпус',
                'placeholder' => 'Дополнительный комментарий'
            ],
            78 => [
                'type' => 'choice',
                'label' => 'Привет, меня зовут Даша, я ваш персональный стилист. Расскажите, с чем Capsula может вам помочь?',
                'option' => [
                    0 => ['text' => 'Порадовать себя', 'emoji' => '&#127873;'],
                    1 => ['text' => 'Обновить гардероб', 'emoji' => '&#128717;'],
                    2 => ['text' => 'Получить совет от стилиста', 'emoji' => '&#128105;'],
                    3 => ['text' => 'Сэкономить время', 'emoji' => '&#8987;'],
                ]
            ],
            79 => [
                'type' => 'text',
                'placeholder' => 'Фамилия'
            ],
            80 => ['type' => 'choice',
                'appeal' => '',
                'label' => 'Как вам было бы удобнее получить капсулу?',
                'option' => [
                    0 => ['text' => 'Доставка курьером'],
                    1 => ['text' => 'Доставка в пункт выдачи'],
                ]
            ],
            81 => [
                'type' => 'text',
                'label' => 'В каком пункте выдачи вы бы хотели забрать капсулу? Подтвердите свой выбор, нажав на кнопку "Выбрать отделение" 🙂'
            ],
            82 => [
                'type' => 'text',
                'label' => 'У нас работает доставка в пункт выдачи. Введите ваш город',
                'placeholder' => 'Введите город'
            ],
            83 => [
                'type' => 'choice',
                'appeal' => ', ',
                'label' => 'на какой сезон вы хотите подборку?',
                'option' => [0 => ['text' => 'Лето'],
                    1 => ['text' => 'Осень'],
                    2 => ['text' => 'Зима'],
                    3 => ['text' => 'Весна'],
                    4 => ['text' => 'Демисезон'],
                ]
            ],
            84 => [
                'type' => 'text',
                'placeholder' => 'Отчество'
            ],
            85 => [ // choosingStyle25 81
                'type' => 'check',
                'label' => 'Что вы думаете про этот образ?',
                'image' => 'settings/anketa/styles/question_85.jpg',
                'option' => [0 => ['text' => 'Нравится весь образ', 'on_values' => [0, 1, 2, 3, 4, 5, 6]],
                    1 => [],
                    2 => [],
                    3 => [],
                    4 => [],
                    5 => [],
                    6 => [],
                    7 => ['text' => 'Ничего не понравилось', 'only_one' => true],
                ]
            ],
            86 => [ // choosingStyle26 82
                'type' => 'check',
                'label' => 'Что вы думаете про этот образ?',
                'image' => 'settings/anketa/styles/question_86.jpg',
                'option' => [0 => ['text' => 'Нравится весь образ', 'on_values' => [0, 1, 2, 3, 4, 5, 6]],
                    1 => [],
                    2 => [],
                    3 => [],
                    4 => [],
                    5 => [],
                    6 => [],
                    7 => ['text' => 'Ничего не понравилось', 'only_one' => true],
                ]
            ],
            87 => [  // choosingStyle27 83
                'type' => 'check',
                'label' => 'Что вы думаете про этот образ?',
                'image' => 'settings/anketa/styles/question_87.jpg',
                'option' => [0 => ['text' => 'Нравится весь образ', 'on_values' => [0, 1, 2, 3, 4, 5, 6]],
                    1 => [],
                    2 => [],
                    3 => [],
                    4 => [],
                    5 => [],
                    6 => [],
                    7 => ['text' => 'Ничего не понравилось', 'only_one' => true],
                ]
            ],
            88 => [  // choosingStyle28 84
                'type' => 'check',
                'label' => 'Что вы думаете про этот образ?',
                'image' => 'settings/anketa/styles/question_88.jpg',
                'option' => [0 => ['text' => 'Нравится весь образ', 'on_values' => [0, 1, 2, 3, 4, 5]],
                    1 => [],
                    2 => [],
                    3 => [],
                    4 => [],
                    5 => [],
                    6 => ['text' => 'Ничего не понравилось', 'only_one' => true],
                ]
            ],
            89 => [  // choosingStyle29 85
                'type' => 'check',
                'label' => 'Что вы думаете про этот образ?',
                'image' => 'settings/anketa/styles/question_89.jpg',
                'option' => [0 => ['text' => 'Нравится весь образ', 'on_values' => [0, 1, 2, 3, 4, 5]],
                    1 => [],
                    2 => [],
                    3 => [],
                    4 => [],
                    5 => [],
                    6 => ['text' => 'Ничего не понравилось', 'only_one' => true],
                ]
            ],
            90 => [  //choosingStyle30 86
                'type' => 'check',
                'label' => 'Что вы думаете про этот образ?',
                'image' => 'settings/anketa/styles/question_90.jpg',
                'option' => [0 => ['text' => 'Нравится весь образ', 'on_values' => [0, 1, 2, 3, 4, 5]],
                    1 => [],
                    2 => [],
                    3 => [],
                    4 => [],
                    5 => [],
                    6 => ['text' => 'Ничего не понравилось', 'only_one' => true],
                ]
            ],
            91 => [  // choosingStyle31 87
                'type' => 'check',
                'label' => 'Что вы думаете про этот образ?',
                'image' => 'settings/anketa/styles/question_91.jpg',
                'option' => [0 => ['text' => 'Нравится весь образ', 'on_values' => [0, 1, 2, 3, 4, 5]],
                    1 => [],
                    2 => [],
                    3 => [],
                    4 => [],
                    5 => [],
                    6 => ['ext' => 'Ничего не понравилось', 'only_one' => true],
                ]
            ],
            92 => [  // choosingStyle32 88
                'type' => 'check',
                'label' => 'Что вы думаете про этот образ?',
                'image' => 'settings/anketa/styles/question_92.jpg',
                'option' => [0 => ['text' => 'Нравится весь образ', 'on_values' => [0, 1, 2, 3, 4, 5, 6]],
                    1 => [],
                    2 => [],
                    3 => [],
                    4 => [],
                    5 => [],
                    6 => [],
                    7 => ['text' => 'Ничего не понравилось', 'only_one' => true],
                ]
            ],
            93 => [  // choosingStyle33 89
                'type' => 'check',
                'label' => 'Что вы думаете про этот образ?',
                'image' => 'settings/anketa/styles/question_93.jpg',
                'option' => [0 => ['text' => 'Нравится весь образ', 'on_values' => [0, 1, 2, 3, 4, 5]],
                    1 => [],
                    2 => [],
                    3 => [],
                    4 => [],
                    5 => [],
                    6 => ['text' => 'Ничего не понравилось', 'only_one' => true],
                ]
            ],
            94 => [  // choosingStyle34 90
                'type' => 'check',
                'label' => 'Что вы думаете про этот образ?',
                'image' => 'settings/anketa/styles/question_94.jpg',
                'option' => [0 => ['text' => 'Нравится весь образ', 'on_values' => [0, 1, 2, 3, 4, 5]],
                    1 => [],
                    2 => [],
                    3 => [],
                    4 => [],
                    5 => [],
                    6 => ['text' => 'Ничего не понравилось', 'only_one' => true],
                ]
            ],

        ];
    }

    public function setReanketaAnswers($array) {

        $keysConvert = [

            'additionalNuances' => 53,
            'capsulaFirstOfAll' => 47,
            'capsulaNotFirstOfAll' => 48,
            'capsulaNotWantAccessories' => 49,

            //размеры
            'bioChest' => 32,
            'bioWaist' => 33,
            'bioHips' => 34,
            'sizeTop' => 30,
            'sizeBottom' => 31,

            //цены на вещи
            'howMuchToSpendOnBlouseShirt' => 54,
            'howMuchToSpendOnSweaterJumperPullover' => 55,
            'howMuchToSpendOnDressesSundresses' => 56,
            'howMuchToSpendOnJacket' => 57,
            'howMuchToSpendOnJeansTrousersSkirts' => 58,
            'howMuchToSpendOnBags' => 59,
            'howMuchToSpendOnBeltsScarvesShawls' => 60,
            'howMuchToSpendOnEarringsNecklacesBracelets' => 61,

            //цветовая гамма
            'noColor' => 50,
            'choosingPalletes25' =>  25,

            //образы
            'choosingStyle4' => 4,
            'choosingStyle5' => 5,
            'choosingStyle6' => 6,
            'choosingStyle7' => 7,
            'choosingStyle8' => 8,
            'choosingStyle16' => 16,
            'choosingStyle17' => 17,
            'choosingStyle18' => 18,
            'choosingStyle19' => 19,
            'choosingStyle20' => 20,

            'choosingStyle25' => 85,
            'choosingStyle26' => 86,
            'choosingStyle27' => 87,
            'choosingStyle28' => 88,
            'choosingStyle29' => 89,
            'choosingStyle30' => 90,
            'choosingStyle31' => 91,
            'choosingStyle32' => 92,
            'choosingStyle33' => 93,
            'choosingStyle34' => 94,



            //принты
            'printsDislike' => 51,

            //доставка
            'delivery' => 71,
            'deliveryType' => 80,
            'boxberryPoint' => 81,
            'boxberryCity' => 82,
            'address' => 63,
            'address_a' => 74,
            'addressOffice' => 76,
            'addressComment' => 77,
            'deliveryDate' => 64,
            'deliveryTime' => 65,
            'deliveryBackTime' => 66,
            'name' => 0,
            'bioSurname' => 79,
            'bioPatronymic' => 84
        ];
        foreach ($array as $key => $value) {
            if(isset($keysConvert[$key]))
                $this->answers[$keysConvert[$key]] = $value;
        }
        return $this->answers;
    }

    public function setReanketaShow($array, $type = 'AnswersWithQuestions') {
        switch($type) {
            case 'AnswersWithQuestions':
                foreach ($array as $key => $question ) {
                    ;
                    if(isset($question['answer'])) {
                        //образы
                        if(in_array($key, [4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 16, 17, 18, 19, 20, 21, 22, 23, 24]) ) {
                            $this->answers[$key]['answer'] = $question['answer'];
                            $this->answers[$key]['image'] = $this->questions[$key]['image'];

                            continue;
                        }
                        $this->answers[$key] = $question['answer'];

                        if (isset($question['option'])) {

                            if (is_array($question['answer'])) {

                                for ($i = 0; $i < count($question['answer']); $i++) {

                                    if($key == 51) {

                                        foreach ($question['option'] as $keyPrint => $printOption) {
                                            if (isset($printOption['items'])) {

                                                if (array_key_exists($question['answer'][$i], $printOption['items'])) {
                                                    $this->answers[$key][$i] = $printOption['items'][$question['answer'][$i]];
                                                }
                                            } else {
                                                if($keyPrint == $question['answer'][$i])
                                                    $this->answers[$key][$i] = $printOption;
                                            }
                                        }

                                        continue;
                                    }

                                    if(isset($question['option'][$question['answer'][$i]])) {
                                        $this->answers[$key][$i] = $question['option'][$question['answer'][$i]];
                                    }

                                }
                            } elseif(is_int($question['answer']) || in_array($key, [28])){
                                if(isset($question['option'][$question['answer']]['text']))
                                    $this->answers[$key] = $question['option'][$question['answer']]['text'];
                            }
                        }
                    }
                }
                break;

            case 'OnlyAnswers':

                foreach ($array as $key => $question ) {

                    if (isset($this->questions[$key]['option'])) {

                        //образы
                        if(in_array($key, [4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 16, 17, 18, 19, 20, 21, 22, 23, 24]) ) {
                            $this->answers[$key]['answer'] = $question;
                            $this->answers[$key]['image'] = $this->questions[$key]['image'];
                            continue;
                        }

                        if (is_array($question)) {
                            for ($i = 0; $i < count($question); $i++) {

                                if($key == 51) {
                                    foreach($this->questions[$key]['option'] as $printOption) {
                                        if(isset($printOption['items'])) {
                                            if (array_key_exists($question[$i], $printOption['items'])) {
                                                $this->answers[$key][$i] = $printOption['items'][$question[$i]];
                                            }
                                        }
                                    }
                                    continue;

                                }

                                if(isset($this->questions[$key]['option'][$question[$i]])) {
                                    $this->answers[$key][$i] = $this->questions[$key]['option'][$question[$i]];
                                }


                            }
                        } elseif(is_int($question) || in_array($key, [28])){
                            if(isset($this->questions[$key]['option'][$question]['text']))
                                $this->answers[$key] = $this->questions[$key]['option'][$question]['text'];
                        }
                    } elseif(is_string($question) && empty($this->answers[$key])){

                        if(!empty($question)) {
                            $this->answers[$key] = trim($question);
                            $this->answers[$key] = str_replace('<br>', ' ', $this->answers[$key]);
                            $this->answers[$key] = strip_tags($this->answers[$key]);
                        }

                    }
                }
                break;
        }


        return $this->answers;
    }

    public function showReanketaTest(){
        return $this->questions;
    }
}
