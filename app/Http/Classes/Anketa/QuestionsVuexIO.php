<?php

namespace App\Http\Classes\Anketa;

use App\Http\Models\AdminClient\Questionnaire;
use App\Http\Models\Vuex\Anketa\AnketaQuestion;
use App\Http\Models\Vuex\Anketa\AnketaQuestionOption;
use Illuminate\Support\Facades\Log;

class QuestionsVuexIO
{

    public $data;
    public $arrResult;
    public $arrOldResult;

    public $oldAdapter = [
        "name" => [
            "type" => "forms",
            "k" => 0,
            "result" => "text"
        ],
        "styleOnWeekend" => [
            "type" => "options",
            "keys" => [
                1,
                2,
                3,
                4
            ],
            "k" => 1,
            "result" => "check"
        ],
        "styleOnWork" => [
            "type" => "options",
            "keys" => [
                5,
                6,
                7,
                8
            ],
            "k" => 2,
            "result" => "check"
        ],
        "tryOtherOrSaveStyle" => [
            "type" => "options",
            "keys" => [
                9,
                10,
                11
            ],
            "k" => 3,
            "result" => "choice"
        ],
        "choosingStyle4" => [
            "type" => "options",
            "keys" => [
                12,
                13,
                14,
                15,
                16,
                146,
                147
            ],
            "k" => 4,
            "result" => "check"
        ],
        "choosingStyle5" => [
            "type" => "options",
            "keys" => [
                22,
                23,
                24,
                25,
                26,
                148,
                149
            ],
            "k" => 5,
            "result" => "check"
        ],
        "choosingStyle6" => [
            "type" => "options",
            "keys" => [
                27,
                28,
                29,
                30,
                31,
                150,
                151
            ],
            "k" => 6,
            "result" => "check"
        ],
        "choosingStyle7" => [
            "type" => "options",
            "keys" => [
                32,
                33,
                34,
                35,
                36,
                152,
                153
            ],
            "k" => 7,
            "result" => "check"
        ],
        "choosingStyle8" => [
            "type" => "options",
            "keys" => [
                37,
                38,
                39,
                40,
                41,
                154,
                155,
                518
            ],
            "k" => 8,
            "result" => "check"
        ],
        "choosingStyle9" => [
            "type" => "options",
            "keys" => [
                434,
                435,
                436,
                437,
                438,
                487,
                488
            ],
            "k" => 9,
            "result" => "check"
        ],
        "choosingStyle10" => [
            "type" => "options",
            "keys" => [
                439,
                440,
                441,
                442,
                489,
                490
            ],
            "k" => 10,
            "result" => "check"
        ],
        "choosingStyle11" => [
            "type" => "options",
            "keys" => [
                443,
                444,
                445,
                446,
                447,
                491,
                492
            ],
            "k" => 11,
            "result" => "check"
        ],
        "choosingStyle12" => [
            "type" => "options",
            "keys" => [
                448,
                449,
                450,
                451,
                493,
                494
            ],
            "k" => 12,
            "result" => "check"
        ],
        "choosingStyle13" => [
            "type" => "options",
            "keys" => [
                452,
                453,
                454,
                455,
                495,
                496
            ],
            "k" => 13,
            "result" => "check"
        ],
        "email" => [
            "type" => "forms",
            "k" => 14,
            "result" => "text"
        ],
        "phone" => [
            "type" => "forms",
            "k" => 15,
            "result" => "text"
        ],
        "choosingStyle16" => [
            "type" => "options",
            "keys" => [
                42,
                43,
                44,
                45,
                46,
                47,
                156,
                157
            ],
            "k" => 16,
            "result" => "check"
        ],
        "choosingStyle17" => [
            "type" => "options",
            "keys" => [
                48,
                49,
                50,
                51,
                52,
                158,
                159
            ],
            "k" => 17,
            "result" => "check"
        ],
        "choosingStyle18" => [
            "type" => "options",
            "keys" => [
                89,
                90,
                91,
                92,
                93,
                94,
                160,
                161
            ],
            "k" => 18,
            "result" => "check"
        ],
        "choosingStyle19" => [
            "type" => "options",
            "keys" => [
                95,
                96,
                97,
                98,
                99,
                162,
                163
            ],
            "k" => 19,
            "result" => "check"
        ],
        "choosingStyle20" => [
            "type" => "options",
            "keys" => [
                100,
                101,
                102,
                103,
                104,
                164,
                165
            ],
            "k" => 20,
            "result" => "check"
        ],
        "choosingStyle21" => [
            "type" => "options",
            "keys" => [
                456,
                457,
                458,
                459,
                497,
                498
            ],
            "k" => 21,
            "result" => "check"
        ],
        "choosingStyle22" => [
            "type" => "options",
            "keys" => [
                460,
                461,
                462,
                463,
                464,
                499,
                500
            ],
            "k" => 22,
            "result" => "check"
        ],
        "choosingStyle23" => [
            "type" => "options",
            "keys" => [
                465,
                466,
                467,
                468,
                469,
                501,
                502
            ],
            "k" => 23,
            "result" => "check"
        ],
        "choosingStyle24" => [
            "type" => "options",
            "keys" => [
                470,
                471,
                472,
                473,
                474,
                485,
                486
            ],
            "k" => 24,
            "result" => "check"
        ],
        "choosingPalletes25" => [
            "type" => "options",
            "keys" => [
                120,
                121,
                122,
                123,
                124,
                125
            ],
            "k" => 25,
            "result" => "check"
        ],
        "bioHeight" => [
            "type" => "forms",
            "k" => 26,
            "result" => "text"
        ],
        "bioBirth" => [
            "type" => "forms",
            "k" => 27,
            "result" => "text"
        ],
        "occupation" => [
            "type" => "options",
            "keys" => [
                168,
                169,
                170,
                171,
                172,
                173,
                174,
                175,
                176,
                177,
                178,
                179,
                180,
                181,
                182,
                183,
                184,
                519,
                520,
                521
            ],
            "k" => 28,
            "result" => "choice"
        ],
        "hairColor" => [
            "type" => "options",
            "keys" => [
                185,
                186,
                187,
                188,
                189
            ],
            "k" => 29,
            "result" => "choice"
        ],
        "sizeTop" => [
            "type" => "options",
            "keys" => [
                190,
                191,
                192,
                193,
                194,
                195,
                196,
                197,
                198,
                199
            ],
            "k" => 30,
            "result" => "check"
        ],
        "sizeBottom" => [
            "type" => "options",
            "keys" => [
                200,
                201,
                202,
                203,
                204,
                205,
                206,
                207,
                208,
                209
            ],
            "k" => 31,
            "result" => "check"
        ],
        "bioChest" => [
            "type" => "forms",
            "k" => 32,
            "result" => "text"
        ],
        "bioWaist" => [
            "type" => "forms",
            "k" => 33,
            "result" => "text"
        ],
        "bioHips" => [
            "type" => "forms",
            "k" => 34,
            "result" => "text"
        ],
        "aboutTopStyle" => [
            "type" => "options",
            "keys" => [
                213,
                214,
                215
            ],
            "k" => 35,
            "result" => "check"
        ],
        "aboutBottomStyle" => [
            "type" => "options",
            "keys" => [
                216,
                217,
                218
            ],
            "k" => 36,
            "result" => "check"
        ],
        "modelsJeans" => [
            "type" => "options",
            "keys" => [
                219,
                220,
                221,
                222,
                223
            ],
            "k" => 37,
            "result" => "check"
        ],
        "trousersFit" => [
            "type" => "options",
            "keys" => [
                224,
                225,
                226
            ],
            "k" => 38,
            "result" => "check"
        ],
        "trouserslength" => [
            "type" => "options",
            "keys" => [
                227,
                228,
                229
            ],
            "k" => 39,
            "result" => "check"
        ],
        "dressesType" => [
            "type" => "options",
            "keys" => [
                230,
                231,
                232
            ],
            "k" => 40,
            "result" => "check"
        ],
        "bodyPartsToHide" => [
            "type" => "options",
            "keys" => [
                233,
                234,
                235,
                236,
                237,
                238,
                239
            ],
            "k" => 41,
            "result" => "check"
        ],
        "preferJeansWithTopOrDresses" => [
            "type" => "options",
            "keys" => [
                242,
                243,
                244
            ],
            "k" => 42,
            "result" => "choice"
        ],
        "whereUsuallyBuyClothes" => [
            "type" => "options",
            "keys" => [
                246,
                247,
                248,
                249,
                250,
                251,
                252,
                253,
                254,
                255,
                256,
                257,
                258,
                259,
                260,
                245
            ],
            "k" => 43,
            "result" => "check"
        ],
        "earsPierced" => [
            "type" => "options",
            "keys" => [
                261,
                262
            ],
            "k" => 44,
            "result" => "choice"
        ],
        "jewelry" => [
            "type" => "options",
            "keys" => [
                263,
                264,
                265
            ],
            "k" => 45,
            "result" => "choice"
        ],
        "bijouterie" => [
            "type" => "options",
            "keys" => [
                266,
                267
            ],
            "k" => 46,
            "result" => "choice"
        ],
        "capsulaFirstOfAll" => [
            "type" => "options",
            "keys" => [
                268,
                269,
                270,
                271,
                272,
                273,
                274,
                275,
                276
            ],
            "k" => 47,
            "result" => "check"
        ],
        "capsulaNotFirstOfAll" => [
            "type" => "options",
            "keys" => [
                277,
                278,
                279,
                280,
                281,
                282,
                283,
                284
            ],
            "k" => 48,
            "result" => "check"
        ],
        "capsulaNotWantAccessories" => [
            "type" => "options",
            "keys" => [
                475,
                476,
                477,
                478,
                479
            ],
            "k" => 49,
            "result" => "check"
        ],
        "noColor" => [
            "type" => "options",
            "keys" => [
                288,
                287,
                301,
                291,
                302,
                296,
                303,
                295,
                304,
                305,
                300,
                306,
                307,
                308,
                309,
                310,
                311,
                297,
                285,
                286,
                312,
                290,
                299,
                298,
                292
            ],
            "k" => 50,
            "result" => "check"
        ],
        "printsDislike" => [
            "type" => "options",
            "keys" => [
                314,
                315,
                316,
                313,
                317,
                318,
                319,
                320,
                325,
                326,
                327,
                328,
                329,
                330,
                331,
                332,
                333,
                334,
                335,
                336,
                337,
                338,
                339,
                340,
                341,
                342,
                343,
                344
            ],
            "k" => 51,
            "result" => "check"
        ],
        "fabricsShouldAvoid" => [
            "type" => "options",
            "keys" => [
                350,
                351,
                352,
                353,
                354
            ],
            "k" => 52,
            "result" => "check"
        ],
        "additionalNuances" => [
            "type" => "forms",
            "k" => 53,
            "result" => "text"
        ],
        "howMuchToSpendOnBlouseShirt" => [
            "type" => "options",
            "keys" => [
                356,
                357,
                358,
                359
            ],
            "k" => 54,
            "result" => "choice"
        ],
        "howMuchToSpendOnSweaterJumperPullover" => [
            "type" => "options",
            "keys" => [
                360,
                361,
                362,
                363
            ],
            "k" => 55,
            "result" => "choice"
        ],
        "howMuchToSpendOnDressesSundresses" => [
            "type" => "options",
            "keys" => [
                364,
                365,
                366,
                367
            ],
            "k" => 56,
            "result" => "choice"
        ],
        "howMuchToSpendOnJacket" => [
            "type" => "options",
            "keys" => [
                368,
                369,
                370,
                371
            ],
            "k" => 57,
            "result" => "choice"
        ],
        "howMuchToSpendOnJeansTrousersSkirts" => [
            "type" => "options",
            "keys" => [
                372,
                373,
                374,
                375
            ],
            "k" => 58,
            "result" => "choice"
        ],
        "howMuchToSpendOnBags" => [
            "type" => "options",
            "keys" => [
                376,
                377,
                378,
                379
            ],
            "k" => 59,
            "result" => "choice"
        ],
        "howMuchToSpendOnBeltsScarvesShawls" => [
            "type" => "options",
            "keys" => [
                383,
                384,
                385
            ],
            "k" => 60,
            "result" => "choice"
        ],
        "howMuchToSpendOnEarringsNecklacesBracelets" => [
            "type" => "options",
            "keys" => [
                380,
                381,
                382
            ],
            "k" => 61,
            "result" => "choice"
        ],
        "whatPurpose" => [
            "type" => "options",
            "keys" => [
                135,
                136,
                137,
                138,
                139,
                140,
                141,
                142,
                143,
                144,
                145,
                503,
                504
            ],
            "k" => 62,
            "result" => "check"
        ],
        "address" => [
            "type" => "forms",
            "k" => 63,
            "result" => "text"
        ],
        "deliveryDate" => [
            "type" => "forms",
            "k" => 64,
            "result" => "text"
        ],
        "deliveryTime" => [
            "type" => "options",
            "keys" => [
                418,
                419,
                420,
                507,
                510
            ],
            "k" => 65,
            "result" => "check"
        ],
        "deliveryBackTime" => [
            "type" => "options",
            "keys" => [
                425,
                426,
                427,
                508,
                509
            ],
            "k" => 66,
            "result" => "check"
        ],
        "bioName" => [
            "type" => "forms",
            "k" => 67,
            "result" => "text"
        ],
        "socials" => [
            "type" => "forms",
            "k" => 68,
            "result" => "text"
        ],
        "knewAboutUs" => [
            "type" => "options",
            "keys" => [
                408,
                409,
                410,
                411,
                412,
                413
            ],
            "k" => 69,
            "result" => "choice"
        ],
        "bioWeight" => [
            "type" => "forms",
            "k" => 70,
            "result" => "text"
        ],
        "delivery" => [
            "type" => "options",
            "keys" => [
                386,
                387,
                388
            ],
            "k" => 71,
            "result" => "choice"
        ],
        "photosAttach" => [
            "type" => "options",
            "keys" => [
                403,
                404
            ],
            "k" => 72,
            "result" => "choice"
        ],
        "photos" => [
            'type' => 'files',
            "k" => 73,
            "result" => 'files'
        ],
        "address_a" => [
            "type" => "forms",
            "k" => 74,
            "result" => "text"
        ],
        "addressHome" => [
            "type" => "forms",
            "k" => 75,
            "result" => "text"
        ],
        "addressOffice" => [
            "type" => "forms",
            "k" => 76,
            "result" => "text"
        ],
        "addressComment" => [
            "type" => "forms",
            "k" => 77,
            "result" => "text"
        ],
        "whatHelp" => [
            "type" => "options",
            "keys" => [
                131,
                132,
                133,
                134
            ],
            "k" => 78,
            "result" => "choice"
        ],
        "bioSurname" => [
            "type" => "forms",
            "k" => 79,
            "result" => "text"
        ],
        "deliveryType" => [
            "type" => "options",
            "keys" => [
                389,
                390
            ],
            "k" => 80,
            "result" => "choice"
        ],
        "boxberryPoint" => [
            "type" => "forms",
            "k" => 81,
            "result" => "text"
        ],
        "boxberryCity" => [
            "type" => "forms",
            "k" => 82,
            "result" => "text"
        ],
        "whatSeason" => [
            "type" => "options",
            "keys" => [
                480,
                481,
                482,
                483,
                484
            ],
            "k" => 83,
            "result" => "choice"
        ],
        "bioPatronymic" => [
            "type" => "forms",
            "k" => 84,
            "result" => "text"
        ],
        "choosingStyle25" => [
            "type" => "options",
            "keys" => [
                524,
                525,
                526,
                527,
                528,
                529,
                530,
                531
            ],
            "k" => 85,
            "result" => "check"
        ],
        "choosingStyle26" => [
            "type" => "options",
            "keys" => [
                532,
                533,
                534,
                535,
                536,
                537,
                538,
                539
            ],
            "k" => 86,
            "result" => "check"
        ],
        "choosingStyle27" => [
            "type" => "options",
            "keys" => [
                540,
                541,
                542,
                543,
                544,
                545,
                546,
                547
            ],
            "k" => 87,
            "result" => "check"
        ],
        "choosingStyle28" => [
            "type" => "options",
            "keys" => [
                548,
                549,
                550,
                551,
                552,
                553,
                554
            ],
            "k" => 88,
            "result" => "check"
        ],
        "choosingStyle29" => [
            "type" => "options",
            "keys" => [
                555,
                556,
                557,
                558,
                559,
                560,
                561
            ],
            "k" => 89,
            "result" => "check"
        ],
        "choosingStyle30" => [
            "type" => "options",
            "keys" => [
                562,
                563,
                564,
                565,
                566,
                567,
                568
            ],
            "k" => 90,
            "result" => "check"
        ],
        "choosingStyle31" => [
            "type" => "options",
            "keys" => [
                569,
                570,
                571,
                572,
                573,
                574,
                575
            ],
            "k" => 91,
            "result" => "check"
        ],
        "choosingStyle32" => [
            "type" => "options",
            "keys" => [
                576,
                577,
                578,
                579,
                580,
                581,
                582,
                583
            ],
            "k" => 92,
            "result" => "check"
        ],
        "choosingStyle33" => [
            "type" => "options",
            "keys" => [
                584,
                585,
                586,
                587,
                588,
                589,
                590
            ],
            "k" => 93,
            "result" => "check"
        ],
        "choosingStyle34" => [
            "type" => "options",
            "keys" => [
                591,
                592,
                593,
                594,
                595,
                596,
                597
            ],
            "k" => 94,
            "result" => "check"
        ],
        "choosingStyle4_1" => [
            "type" => "options",
            "keys" => [
                599,
                598,
                601,
                602,
                603,
                604,
                600,
            ],
            "k" => 94,
            "result" => "check"
        ],
        "choosingStyle5_1" => [
            "type" => "options",
            "keys" => [
                606,
                605,
                608,
                609,
                610,
                611,
                607
            ],
            "k" => 95,
            "result" => "check"
        ],
        "choosingStyle6_1" => [
            "type" => "options",
            "keys" => [
                613,
                612,
                615,
                616,
                617,
                614
            ],
            "k" => 96,
            "result" => "check"
        ],
        "choosingStyle7_1" => [
            "type" => "options",
            "keys" => [
                619,
                618,
                621,
                622,
                623,
                620
            ],
            "k" => 97,
            "result" => "check"
        ],
        "choosingStyle8_1" => [
            "type" => "options",
            "keys" => [
                625,
                624,
                627,
                628,
                629,
                626
            ],
            "k" => 98,
            "result" => "check"
        ],
        "choosingStyle16_1" => [
            "type" => "options",
            "keys" => [
                631,
                630,
                633,
                634,
                635,
                632
            ],
            "k" => 99,
            "result" => "check"
        ],
        "choosingStyle17_1" => [
            "type" => "options",
            "keys" => [
                637,
                636,
                639,
                640,
                638
            ],
            "k" => 100,
            "result" => "check"
        ],
        "choosingStyle18_1" => [
            "type" => "options",
            "keys" => [
                642,
                641,
                644,
                645,
                643,
            ],
            "k" => 101,
            "result" => "check"
        ],
        "choosingStyle19_1" => [
            "type" => "options",
            "keys" => [
                647,
                646,
                649,
                650,
                651,
                648
            ],
            "k" => 102,
            "result" => "check"
        ],
        "choosingStyle20_1" => [
            "type" => "options",
            "keys" => [
                653,
                652,
                655,
                656,
                657,
                654
            ],
            "k" => 103,
            "result" => "check"
        ],

    ];

    public function inputAnketa($dataFromVuex)
    {

        try {

            foreach ($dataFromVuex as $key => $value) {


                if ($key == 'boxberryCity') {
                    $this->arrResult[$key] = $value['forms'][0];
                }
                elseif ($key == 'boxberryPoint') {
                    if(array_key_exists('boxberryPoint',$value['forms'])){
                        $this->arrResult['boxberryPoint'] = $value['forms']['boxberryPoint'];
                    }

                    if(array_key_exists('pvz_id',$value['forms'])){
                        $this->arrResult['pvz_id'] = $value['forms']['pvz_id'];
                    }

                    if(array_key_exists('pvz_address',$value['forms'])){
                        $this->arrResult['pvz_address'] = $value['forms']['pvz_address'];
                    }

                    if(array_key_exists('pvz_name',$value['forms'])){
                        $this->arrResult['pvz_name'] = $value['forms']['pvz_name'];
                    }

                    if(array_key_exists('pvz_price',$value['forms'])){
                        $this->arrResult['pvz_price'] = $value['forms']['pvz_price'];
                    }
//                    $this->arrResult[$key] = $value['forms'][0];
                }
                elseif ($key == 'clientPhotos' ) {
                    $this->arrResult[$key] = $value['forms'];
//                foreach ($value['forms'] as $photo) {
//                    array_push($this->arrResult[$key],$photo);
//                }
                }
                elseif ($key == 'rf') {
                    $this->arrResult[$key] = $value;
                }
                elseif ($key == 'amount') {
                    $this->arrResult[$key] = $value;
                } elseif ($key == 'coupon') {
                    $this->arrResult[$key] = $value;
                } elseif ($key == 'lastQuestionSlug') {
                    $this->arrResult[$key] = $value;
                } else {

                    if (count($value['forms'])) {
                        $this->formsInput($key, $value);
                    } else {
                        $this->optionsInput($key, $value);
                    }

                }

            }
            ksort($this->arrResult);

            return [
                'result' => $this->arrResult,
                'old' => [] // $this->oldAnswers()
            ];
        } catch (\Exception $e) {
            dd($e);
        }






    }

    public function outputAnketa($uuid)
    {

        $quest = Questionnaire::where('uuid', $uuid)->first();
        if ($quest->anketa) {
            $collection = collect($quest->anketa);
            $collection->each(function ($value, $key) {
                if($key === 'clientPhotos' ) {
                    $this->arrResult[$key]['options'] = [];
                    $this->arrResult[$key]['forms'] = $value;
                    $this->arrResult[$key]['own'] = '';
//                foreach ($value as $k => $photo) {
////                    $imgKey = 'img_'.$k++;
//                    $this->arrResult[$key]['forms']['img_'.$k++] = $photo;
////                    array_push($this->arrResult[$key]['forms'],$k);
////                    array_push($this->arrResult[$key]['forms']['img_'.(int)$k+1],$photo);
//                }
                } else {
                    is_array($value)
                        ? $this->optionsOutput($key, $value)
                        : $this->formsOutput($key, $value);
                }
            });
        }

        return $this->arrResult ?: json_decode('{}');

    }

    public function optionsInput($key, $value)
    {
        if (is_array($value['options'])) {
            $this->arrResult[$key] = $value['options'];
        } else {
            $this->arrResult[$key] = [$value['options']];
        }

        if ($value['own']) {
            array_push($this->arrResult[$key], $value['own']);
        }
    }

    private function formsInput($key, $value)
    {
        if ($value['forms']) {
            foreach ($value['forms'] as $k => $v) if ($v) {
                $this->arrResult[$k] = $v;
            }
        }
    }



    public function optionsOutput($key, $value)
    {
        $this->arrResult[$key]['options'] = [];
        $this->arrResult[$key]['forms'] = [];
        $this->arrResult[$key]['own'] = '';

        $collection = collect($value);

        $collection->each(function ($val) use ($key) {
                !is_numeric($val)
                    ? $this->arrResult[$key]['own'] = $val
                    : array_push($this->arrResult[$key]['options'], $val);

        });
    }

    public function formsOutput($key, $value)
    {
        if ($key == 'boxberryCity') {
            $this->arrResult['boxberryCity']['options'] = [];
            $this->arrResult['boxberryCity']['own'] = '';
            $this->arrResult['boxberryCity']['forms'][0] = $value;
        } else {
            $formOption = AnketaQuestionOption::with('question')->where('slug', $key)->first();

            if ($formOption && $formOption->question->slug) {
                $this->arrResult[$formOption->question->slug]['options'] = [];
                $this->arrResult[$formOption->question->slug]['own'] = '';
                $this->arrResult[$formOption->question->slug]['forms'][$key] = $value;
            } else {
                $this->arrResult[$key] = $value;
            }
        }


    }


    public function oldAnswers()
    {

        for ($i = 0; $i <= 94; $i++) {
            try {

                $this->arrOldResult[strval($i)] = $this->parseOldItem($i);
            } catch (\Exception $e) {
                Log::error($e);
//                dump($e);
            }
        }

        $rest = array_diff_key($this->arrResult, $this->oldAdapter);
        $rrr = $this->arrOldResult+$rest;

        return $rrr;

    }

    public function parseOldItem($i)
    {
        try {
            $oldAdapterItem = array_filter($this->oldAdapter, function ($n) use ($i) {
                return ($n['k'] == $i);
            });


            $oldAdapterItemKey = array_key_first($oldAdapterItem);

            if(array_key_exists($oldAdapterItemKey, $oldAdapterItem)) {
                $oldAdapterItemArr = $oldAdapterItem[$oldAdapterItemKey];

                if(array_key_exists($oldAdapterItemKey,$this->arrResult)){
                    if($oldAdapterItemArr['type'] === 'options') {

                        $res = [];

                        foreach ($this->arrResult[$oldAdapterItemKey] as $k => $v) {
                            if(($val = array_search($v, $oldAdapterItemArr['keys'])) !== false)
                                array_push($res,$val);
                        }

                    }

                    if($oldAdapterItemArr['type'] === 'forms') {
                        $res = $this->arrResult[$oldAdapterItemKey];
                    }

                } else {

                    if($oldAdapterItemArr['type'] === 'options') {
                        $res = [];
                    }

                    if($oldAdapterItemArr['type'] === 'forms') {
                        $res = '';
                    }

                }

            } else {
                $res = '';
            }

            return $res;
        } catch (\Exception $e) {
            dd($e);
        }



    }




    public function reSaveAnketa($fronBase){
        try {
            $res = [];
            $res['rf'] =  array_key_exists('rf',$fronBase) ? $fronBase['rf'] : null;
            $res['coupon'] =  array_key_exists('coupon',$fronBase) ? $fronBase['coupon'] : null;
            $res['amount'] =  array_key_exists('amount',$fronBase) ? $fronBase['amount'] : 0;
            if(array_key_exists('boxberryPoint',$fronBase)) {$res['boxberryPoint'] = $fronBase['boxberryPoint'];}
            if( array_key_exists('pvz_id',$fronBase)) {$res['pvz_id'] = $fronBase['pvz_id'];}
            if(array_key_exists('pvz_address',$fronBase)) {$res['pvz_address'] = $fronBase['pvz_address'];}
            if(array_key_exists('pvz_name',$fronBase)) {$res['pvz_name'] = $fronBase['pvz_name'];}
            if(array_key_exists('pvz_price',$fronBase)) {$res['pvz_price'] = $fronBase['pvz_price'];}



            foreach ($this->oldAdapter as $sampleKey => $sampleVal) {

                if(array_key_exists($sampleKey,$fronBase)) {

                    $elem = $fronBase[$sampleKey];

                    if($sampleVal['result'] === 'text') {
                        $res[$sampleVal['k']] = $this->opText($elem,$sampleVal);
                    } elseif ($sampleVal['result'] === 'check') {
                        $res[$sampleVal['k']] = $this->opCheck($elem,$sampleVal);
                    } elseif ($sampleVal['result'] === 'choice') {
                        $res[$sampleVal['k']] = $this->opChoise($elem,$sampleVal);
                    }

                } else {

                    if($sampleVal['result'] === 'text') {
                        $res[$sampleVal['k']] = '';
                    } elseif ($sampleVal['result'] === 'check') {
                        $res[$sampleVal['k']] = [];
                    } elseif ($sampleVal['result'] === 'choice') {
                        $res[$sampleVal['k']] = NULL;
                    }

                }

            }

            return $res;

        } catch (\Exception $e) {
            Log::error($e);
        }
    }


    public function opText($elem,$sampleVal) {
        return $elem;
    }
    public function opCheck($elem,$sampleVal) { // array

        $arr = [];

        foreach ($elem as $val) {
            if(is_numeric($val)){
                $key = array_search ($val, $sampleVal['keys']);
            } else {
                $key = $val;
            }

            array_push($arr, $key);
        }

        return $arr;
    }
    public function opChoise($elem,$sampleVal) { // number
        if(count($elem)) {
            return array_search ($elem[0], $sampleVal['keys']);
        } else {
            return NULL;
        }
    }

}
