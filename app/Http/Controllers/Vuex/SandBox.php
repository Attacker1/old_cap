<?php

namespace App\Http\Controllers\Vuex;


use App\Http\Classes\Anketa\DataFieldConverter;
use App\Http\Classes\Anketa\QuestionDataToAnketa;
use App\Http\Classes\Anketa\QuestionsVuexIO;
use App\Http\Classes\Client;
use App\Http\Classes\DataConversion\AnketaToCsv;
use App\Http\Controllers\Classes\AmoCrm;
use App\Http\Controllers\Controller;
use App\Http\Models\Admin\AdminUser;
use App\Http\Models\Admin\Permission;
use App\Http\Models\Admin\Role;
use App\Http\Models\AdminClient\Questionnaire;
use App\Http\Models\Common\Lead;
use App\Http\Models\Vuex\Anketa\AnketaBuilder;
use App\Http\Models\Vuex\Anketa\AnketaQuestion;
use App\Http\Models\Vuex\Anketa\AnketaQuestionOption;
use App\Http\Models\Vuex\Anketa\AnketaQuestionType;
use App\Services\Settings\ManageFieldsService;
use App\Traits\VuexAutoMethods;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;


class SandBox extends Controller
{
    use VuexAutoMethods;


    public $converted = [];


    public function req()
    {
        $quest = \DB::select('SELECT * FROM questionnaires JOIN (SELECT uuid FROM questionnaires  ORDER BY uuid LIMIT ?, ?) as b ON b.uuid = questionnaires.uuid'
            , [47200, 1000]);

        return $quest;
    }

    public function index()
    {
        dump('sdfsdfsdf');
        $rr = new SettingsController();
        dump($rr->test());

//        dump(ManageFieldsService::);

//        $items = AdminUser::with('roles')->whereHas('roles',function ($q) {
//            $q->where('role_id',4);
//        })->first();
//        $role = Role::whereId(4)->first();
//
//
//        $user = \auth()->guard('admin')->user();


//                dump($items->permissions()->sync($role->permissions()->get()->pluck("id")));

//        dump($role->permissions()->get()->pluck("id"));

//        try {
////            $quest = \DB::select('SELECT COUNT(*) FROM `questionnaires` WHERE JSON_EXTRACT(anketa,"$.jewelry[0]") = 263');
//            $quest = \DB::statement('UPDATE questionnaires SET anketa = JSON_SET(anketa,"$.jewelry", JSON_ARRAY(264)) WHERE JSON_EXTRACT(anketa,"$.jewelry[0]") = 888');
//            dump('$quest', $quest);
//            dump('sdfsdf');
//        } catch (\Exception $e) {
//            dd($e);
//        }
//        try {
//            $qRows = $this->req();
//            foreach ($qRows as $qRow) {
//                $this->process($qRow);
//            }
//
//            dump($this->converted);
//        } catch (\Exception $e) {
//            dd($e);
//        }

    }


    public function process($row)
    {
        if(strlen($row->data) > 100)
        try {
            $dataCol = json_decode($row->data);
            $convertData = [];

            if ($dataCol && property_exists($dataCol, 'anketa') && property_exists($dataCol->anketa, 'question')) {
                $convertData = $dataCol->anketa->question;
                $this->propertiesOld($dataCol,$row->uuid);
            } else {
                $convertData = $dataCol;
                $this->propertiesNew($dataCol,$row->uuid);
            }

            $this->changeQuestion($convertData,$row->uuid);
        } catch (\Exception $e) {
            dd($e,$row);
        }


//        dump($convertData);


    }

    public function propertiesOld($data,$uuid) {
        if(isset($data->rf)) {
            $this->converted[$uuid]['rf'] = $data->rf;
        }
        if(isset($data->coupon)) {
            $this->converted[$uuid]['coupon'] = $data->coupon;
        }
        if(isset($data->anketa) && isset($data->anketa->amount)) {
            $this->converted[$uuid]['amount'] = $data->anketa->amount;
        }
    }

    public function propertiesNew($data,$uuid) {
        if(isset($data->rf)) {
            $this->converted[$uuid]['rf'] = $data->rf;
        }
        if(isset($data->coupon)) {
            $this->converted[$uuid]['coupon'] = $data->coupon;
        }

        if(isset($data->amount)) {
            $this->converted[$uuid]['amount'] = $data->amount;
        }

    }

    public function changeQuestion($convertData,$uuid)
    {
        try {

//            if($uuid == '00046ac0-b15b-435f-8477-0e6746b3bf1e') {
//                dump($convertData);
//            }
            if(isset($convertData)) {
                foreach ($convertData as $key => $item) {

                    if($item && gettype($item) === 'object' && property_exists($item,'answer')){
                        $this->convertItem($key,$item,$uuid);
                    } else {
                        $newItem = [];
                        $newItem['type'] = 'converted';
                        $newItem['answer'] = $item;
                        $this->convertItem($key,$newItem,$uuid);
                    }
                }
            }

        } catch (\Exception $e) {
            dd($e,$convertData,$uuid);
        }


    }


    public function convertItem($key,$item,$uuid) {

        try {
            if(method_exists(__CLASS__,'q_'.$key)) {
                call_user_func_array([__CLASS__,'q_'.$key],[$key,(array)$item,$uuid]);
            }

//            dump($key,$uuid,$item);
        } catch (\Exception $e) {
            dd($e);
        }


    }

    public function typeText($item,$uuid,$slug) {
        try {
            if(array_key_exists('answer',$item) && $item['answer']) {
                $this->converted[$uuid][$slug] = $item['answer'];
            }
        } catch (\Exception $e) {
            dd($e);
        }

    }

    public function typeCheckSingle($item,$uuid,$slug,$arr, $dump = false) {
        try {
            if(array_key_exists('answer',$item) && (!empty($item['answer']) || $item['answer'] === 0)) {
                $value = [];
                if(!is_array($item['answer']) && (!empty($item['answer']) || $item['answer'] === 0)) {
                    $item['answer'] = [$item['answer']];
                }
                if(is_array($item['answer']) && $item['answer'] ) {
                    foreach ($item['answer'] as $i) if(!empty($i) || $i === 0) {


                        if(is_numeric($i) && $i < count($arr) ){

                            if($slug === 'whatPurpose' && $i === 13) {
//                                dump('passed', $item['option'][13]->text);
                                if($item['type'] == 'converted') {
                                    continue;
                                } else {
                                    $value[] = $item['option'][13]->text;
                                }

                            } else {
                                $value[] = $arr[$i];
                            }



                        } else {
                            $value[] = $i;
                        }
                    }
                }
                try {
                    if($value){
                        $this->converted[$uuid][$slug] = $value;
                    }

                } catch (\Exception $e) {
                    dd($e);
                }

                if($dump) {
                    dump($item['answer'], $this->converted[$uuid][$slug], '-----------------------');
                }
            }
        } catch (\Exception $e) {
            dd($e,$item,$uuid,$slug,$arr);
        }


    }


    public function typeCheckImage($item,$uuid,$slug1,$slug2,$arr1,$arr2, $image, $dump = false) {

        try {
            if(array_key_exists('answer',$item) && (!empty($item['answer']) || $item['answer'] === 0)) {

                if(array_key_exists('type',$item) && $item['type'] == "converted") {
                    $arr = $arr1;
                    $slug = $slug1;
                } elseif (array_key_exists('image',$item) && $item['image'] == $image) {
                    $arr = $arr2;
                    $slug = $slug2;
                } else {
                    $arr = $arr1;
                    $slug = $slug1;
                }

                $value = [];
                if(!is_array($item['answer']) && (!empty($item['answer']) || $item['answer'] === 0)) {
                    $item['answer'] = [$item['answer']];
                }
                if(is_array($item['answer']) && $item['answer'] ) {
                    foreach ($item['answer'] as $i) if(!empty($i) || $i === 0) {
                        $value[] = $arr[$i];
                    }
                }
                if($value){
                    $this->converted[$uuid][$slug] = $value;
                }

                if($dump) {
                    dump($item['answer'], $this->converted[$uuid][$slug], '-----------------------');
                }
            }
        } catch (\Exception $e) {
            dd($e);
        }


    }


    public function typeCheckAlternative($item,$uuid,$slug,$arr1,$arr2, $dump = false) {
        try {
            if(array_key_exists('answer',$item) && (!empty($item['answer']) || $item['answer'] === 0)) {
                $value = [];
                if(!is_array($item['answer']) && (!empty($item['answer']) || $item['answer'] === 0)) {
                    $item['answer'] = [$item['answer']];
                }

                $arr = [];

                if(array_key_exists('option',$item) && count($item['option']) == 5) {
                    $arr =   $arr2;
                } else {
                    $arr =   $arr1;
                }






                if(is_array($item['answer']) && $item['answer'] ) {
                    foreach ($item['answer'] as $i) if(!empty($i) || $i === 0) {
                        $value[] = $arr[$i];
                    }
//                    if(array_key_exists('option',$item) && count($item['option']) > 0) {
//                        foreach ($item['answer'] as $i) if(!empty($i) || $i === 0) {
//                            $value[] = $arr1[$i];
//                        }
//                    } else {
//                        foreach ($item['answer'] as $i) if(!empty($i) || $i === 0) {
//                            $value[] = $arr2[$i];
//                        }
//                    }
                }


                if($value){
                    $this->converted[$uuid][$slug] = $value;
                }

                if($dump) {
                    dump($item['answer'], $this->converted[$uuid][$slug], '-----------------------');
                }
            }
        } catch (\Exception $e) {
            dd($e,$item,$uuid,$slug,$arr1,$arr2);
        }


    }




    public function q_0($key,$item,$uuid){
        try {
            $this->typeText($item,$uuid,'name');
        } catch (\Exception $e) {
            dd($e);
        }
    }

    public function q_1($key,$item,$uuid){
        try {
            $this->typeCheckSingle($item,$uuid,'styleOnWeekend', [1, 2, 3, 4]);
        } catch (\Exception $e) {
            dd($e);
        }
    }

    public function q_2($key,$item,$uuid){
        try {
            $this->typeCheckSingle($item,$uuid,'styleOnWork', [5, 6, 7, 8]);
        } catch (\Exception $e) {
            dd($e);
        }
    }

    public function q_3($key,$item,$uuid){
        try {
            $this->typeCheckSingle($item,$uuid,'tryOtherOrSaveStyle', [9, 10, 11]);
        } catch (\Exception $e) {
            dd($e);
        }
    }

    public function q_4($key,$item,$uuid){
        try {
            $this->typeCheckImage($item,$uuid,'choosingStyle4','choosingStyle4_1',[ 146, 12, 13, 14, 15, 16, 147],[599, 598, 601, 602, 603, 604, 600,], 'img/anketa/question_4.png');
        } catch (\Exception $e) {
            dd($e);
        }
    }

    public function q_5($key,$item,$uuid){
        try {
            $this->typeCheckImage($item,$uuid,'choosingStyle5','choosingStyle5_1',[148, 22, 23, 24, 25, 26, 149],[606, 605, 608, 609, 610, 611, 607], 'img/anketa/question_5.png');
        } catch (\Exception $e) {
            dd($e);
        }
    }

    public function q_6($key,$item,$uuid){
        try {
            $this->typeCheckImage($item,$uuid,'choosingStyle6','choosingStyle6_1',[    150, 27, 28, 29, 30, 31, 151],[    613, 612, 615, 616, 617, 614], 'img/anketa/question_6.png');
        } catch (\Exception $e) {
            dd($e);
        }
    }

    public function q_7($key,$item,$uuid){
        try {
            $this->typeCheckImage($item,$uuid,'choosingStyle7','choosingStyle7_1',[    152, 32, 33, 34, 35, 36, 153],[    619, 618, 621, 622, 623, 620], 'img/anketa/question_7.png');
        } catch (\Exception $e) {
            dd($e);
        }
    }

    public function q_8($key,$item,$uuid){
        try {
            $this->typeCheckImage($item,$uuid,'choosingStyle8','choosingStyle8_1',[    155, 37, 38, 39, 40, 41, 154, 518],[    625, 624, 627, 628, 629, 626], 'img/anketa/question_8.png');
        } catch (\Exception $e) {
            dd($e);
        }
    }

    public function q_9($key,$item,$uuid){
        try {
            $this->typeCheckSingle($item,$uuid,'choosingStyle9', [    487, 434, 435, 436, 437, 438, 488]);
        } catch (\Exception $e) {
            dd($e);
        }
    }

    public function q_10($key,$item,$uuid){
        try {
            $this->typeCheckSingle($item,$uuid,'choosingStyle10', [489, 439, 440, 441, 442, 490]);
        } catch (\Exception $e) {
            dd($e);
        }
    }

    public function q_11($key,$item,$uuid){
        try {
            $this->typeCheckSingle($item,$uuid,'choosingStyle11', [    491, 443, 444, 445, 446, 447, 492]);
        } catch (\Exception $e) {
            dd($e);
        }
    }

    public function q_12($key,$item,$uuid){
        try {
            $this->typeCheckSingle($item,$uuid,'choosingStyle12', [    493, 448, 449, 450, 451, 494]);
        } catch (\Exception $e) {
            dd($e);
        }
    }

    public function q_13($key,$item,$uuid){
        try {
            $this->typeCheckSingle($item,$uuid,'choosingStyle13', [    495, 452, 453, 454, 455, 496]);
        } catch (\Exception $e) {
            dd($e);
        }
    }

    public function q_14($key,$item,$uuid){
        try {
            $this->typeText($item,$uuid,'email');
        } catch (\Exception $e) {
            dd($e);
        }
    }

    public function q_15($key,$item,$uuid){
        try {
            $this->typeText($item,$uuid,'phone');
        } catch (\Exception $e) {
            dd($e);
        }
    }

    public function q_16($key,$item,$uuid){
        try {
            $this->typeCheckImage($item,$uuid,'choosingStyle16','choosingStyle16_1',[    156, 42, 43, 44, 45, 46, 47, 157],[    631, 630, 633, 634, 635, 632], 'img/anketa/question_16.png');
        } catch (\Exception $e) {
            dd($e);
        }
    }

    public function q_17($key,$item,$uuid){
        try {
            $this->typeCheckImage($item,$uuid,'choosingStyle17','choosingStyle17_1',[    158, 48, 49, 50, 51, 52, 159],[    637, 636, 639, 640, 638], 'img/anketa/question_17.png');
        } catch (\Exception $e) {
            dd($e);
        }
    }

    public function q_18($key,$item,$uuid){
        try {
            $this->typeCheckImage($item,$uuid,'choosingStyle18','choosingStyle18_1',[    160, 89, 90, 91, 92, 93, 94, 161],[    642, 641, 644, 645, 643,], 'img/anketa/question_18.png');
        } catch (\Exception $e) {
            dd($e);
        }
    }

    public function q_19($key,$item,$uuid){
        try {
            $this->typeCheckImage($item,$uuid,'choosingStyle19','choosingStyle19_1',[    162, 95, 96, 97, 98, 99, 163],[    647, 646, 649, 650, 651, 648], 'img/anketa/question_19.png');
        } catch (\Exception $e) {
            dd($e);
        }
    }

    public function q_20($key,$item,$uuid){
        try {
            $this->typeCheckImage($item,$uuid,'choosingStyle20','choosingStyle20_1',[    164, 100, 101, 102, 103, 104, 165],[    653, 652, 655, 656, 657, 654], 'img/anketa/question_20.png');
        } catch (\Exception $e) {
            dd($e);
        }
    }

    public function q_21($key,$item,$uuid){
        try {
            $this->typeCheckSingle($item,$uuid,'choosingStyle21', [    497, 456, 457, 458, 459, 498]);
        } catch (\Exception $e) {
            dd($e);
        }
    }

    public function q_22($key,$item,$uuid){
        try {
            $this->typeCheckSingle($item,$uuid,'choosingStyle22', [    499, 460, 461, 462, 463, 464, 500]);
        } catch (\Exception $e) {
            dd($e);
        }
    }

    public function q_23($key,$item,$uuid){
        try {
            $this->typeCheckSingle($item,$uuid,'choosingStyle23', [    501, 465, 466, 467, 468, 469, 502]);
        } catch (\Exception $e) {
            dd($e);
        }
    }

    public function q_24($key,$item,$uuid){
        try {
            $this->typeCheckSingle($item,$uuid,'choosingStyle24', [    485, 470, 471, 472, 473, 474, 486]);
        } catch (\Exception $e) {
            dd($e);
        }
    }

    public function q_25($key,$item,$uuid){
        try {
            $this->typeCheckSingle($item,$uuid,'choosingPalletes25', [    120, 121, 122, 123, 124, 125]);
        } catch (\Exception $e) {
            dd($e);
        }
    }

    public function q_26($key,$item,$uuid){
        try {
            $this->typeText($item,$uuid,'bioHeight');
        } catch (\Exception $e) {
            dd($e);
        }
    }

    public function q_27($key,$item,$uuid){
        try {
            $this->typeText($item,$uuid,'bioBirth');
        } catch (\Exception $e) {
            dd($e);
        }
    }

    public function q_28($key,$item,$uuid){
        try {
            $this->typeCheckSingle($item,$uuid,'occupation', [    168, 169, 170, 171, 172, 173, 174, 175, 176, 177, 178, 179, 180, 181, 182, 183, 184, 519, 520, 521]);
        } catch (\Exception $e) {
            dd($e);
        }
    }

    public function q_29($key,$item,$uuid){
        try {
            $this->typeCheckSingle($item,$uuid,'hairColor', [    185, 186, 187, 188, 189]);
        } catch (\Exception $e) {
            dd($e);
        }
    }

    public function q_30($key,$item,$uuid){
        try {
            $this->typeCheckAlternative($item, $uuid,'sizeTop', [190, 191, 192, 193, 194, 195, 196, 197, 198, 199],[191, 192, 193, 194, 195]);
        } catch (\Exception $e) {
            dd($e);
        }
    }

    public function q_31($key,$item,$uuid){
        try {
            $this->typeCheckAlternative($item, $uuid,'sizeBottom', [    200, 201, 202, 203, 204, 205, 206, 207, 208, 209],[    201, 202, 203, 204, 205]);
        } catch (\Exception $e) {
            dd($e);
        }
    }

    public function q_32($key,$item,$uuid){
        try {
            $this->typeText($item,$uuid,'bioChest');
        } catch (\Exception $e) {
            dd($e);
        }
    }

    public function q_33($key,$item,$uuid){
        try {
            $this->typeText($item,$uuid,'bioWaist');
        } catch (\Exception $e) {
            dd($e);
        }
    }

    public function q_34($key,$item,$uuid){
        try {
            $this->typeText($item,$uuid,'bioHips');
        } catch (\Exception $e) {
            dd($e);
        }
    }

    public function q_35($key,$item,$uuid){
        try {
            $this->typeCheckSingle($item,$uuid,'aboutTopStyle', [    213, 214, 215]);
        } catch (\Exception $e) {
            dd($e);
        }
    }

    public function q_36($key,$item,$uuid){
        try {
            $this->typeCheckSingle($item,$uuid,'aboutBottomStyle', [216, 217, 218]);
        } catch (\Exception $e) {
            dd($e);
        }
    }

    public function q_37($key,$item,$uuid){
        try {
            $this->typeCheckSingle($item,$uuid,'modelsJeans', [219, 220, 221, 222, 223]);
        } catch (\Exception $e) {
            dd($e);
        }
    }

    public function q_38($key,$item,$uuid){
        try {
            $this->typeCheckSingle($item,$uuid,'trousersFit', [   224, 225, 226]);
        } catch (\Exception $e) {
            dd($e);
        }
    }

    public function q_39($key,$item,$uuid){
        try {
            $this->typeCheckSingle($item,$uuid,'trouserslength', [227, 228, 229]);
        } catch (\Exception $e) {
            dd($e);
        }
    }

    public function q_40($key,$item,$uuid){
        try {
            $this->typeCheckSingle($item,$uuid,'dressesType', [230, 231, 232]);
        } catch (\Exception $e) {
            dd($e);
        }
    }

    public function q_41($key,$item,$uuid){
        try {
            $this->typeCheckSingle($item,$uuid,'bodyPartsToHide', [233, 234, 235, 236, 237, 238, 239]);
        } catch (\Exception $e) {
            dd($e);
        }
    }

    public function q_42($key,$item,$uuid){
        try {
            $this->typeCheckSingle($item,$uuid,'preferJeansWithTopOrDresses', [242, 243, 244]);
        } catch (\Exception $e) {
            dd($e);
        }
    }

    public function q_43($key,$item,$uuid){
        try {
            $this->typeCheckSingle($item,$uuid,'whereUsuallyBuyClothes', [246, 247, 248, 249, 250, 251, 252, 253, 254, 255, 256, 257, 258, 259, 260, 245]);
        } catch (\Exception $e) {
            dd($e);
        }
    }

    public function q_44($key,$item,$uuid){
        try {
            $this->typeCheckSingle($item,$uuid,'earsPierced', [261, 262]);
        } catch (\Exception $e) {
            dd($e);
        }
    }

    public function q_45($key,$item,$uuid){
        try {
            $this->typeCheckSingle($item,$uuid,'jewelry', [120, 121, 122, 123, 124, 125]);
        } catch (\Exception $e) {
            dd($e);
        }
    }

    public function q_46($key,$item,$uuid){
        try {
            $this->typeCheckSingle($item,$uuid,'bijouterie', [266, 267]);
        } catch (\Exception $e) {
            dd($e);
        }
    }

    public function q_47($key,$item,$uuid){
        try {
            $this->typeCheckSingle($item,$uuid,'capsulaFirstOfAll', [268, 269, 270, 271, 272, 273, 274, 275, 276]);
        } catch (\Exception $e) {
            dd($e);
        }
    }

    public function q_48($key,$item,$uuid){
        try {
            $this->typeCheckSingle($item,$uuid,'capsulaNotFirstOfAll', [277, 278, 279, 280, 281, 282, 283, 284]);
        } catch (\Exception $e) {
            dd($e);
        }
    }

    public function q_49($key,$item,$uuid){
        try {
            $this->typeCheckSingle($item,$uuid,'capsulaNotWantAccessories', [475, 476, 477, 478, 479]);
        } catch (\Exception $e) {
            dd($e);
        }
    }

    public function q_50($key,$item,$uuid){
        try {
            $this->typeCheckSingle($item,$uuid,'noColor', [288, 287, 301, 291, 302, 296, 303, 295, 304, 305, 300, 306, 307, 308, 309, 310, 311, 297, 285, 286, 312, 290, 299, 298, 292]);
        } catch (\Exception $e) {
            dd($e);
        }
    }

    public function q_51($key,$item,$uuid){
        try {
            $this->typeCheckSingle($item,$uuid,'printsDislike', [314, 315, 316, 313, 317, 318, 319, 320, 325, 326, 327, 328, 329, 330, 331, 332, 333, 334, 335, 336, 337, 338, 339, 340, 341, 342, 343, 344]);
        } catch (\Exception $e) {
            dd($e);
        }
    }

    public function q_52($key,$item,$uuid){
        try {
            $this->typeCheckSingle($item,$uuid,'fabricsShouldAvoid', [350, 351, 352, 353, 354]);
        } catch (\Exception $e) {
            dd($e);
        }
    }

    public function q_53($key,$item,$uuid){
        try {
            $this->typeText($item,$uuid,'additionalNuances');
        } catch (\Exception $e) {
            dd($e);
        }
    }

    public function q_54($key,$item,$uuid){
        try {
            $this->typeCheckSingle($item,$uuid,'howMuchToSpendOnBlouseShirt', [356, 357, 358, 359]);
        } catch (\Exception $e) {
            dd($e);
        }
    }

    public function q_55($key,$item,$uuid){
        try {
            $this->typeCheckSingle($item,$uuid,'howMuchToSpendOnSweaterJumperPullover', [360, 361, 362, 363]);
        } catch (\Exception $e) {
            dd($e);
        }
    }

    public function q_56($key,$item,$uuid){
        try {
            $this->typeCheckSingle($item,$uuid,'howMuchToSpendOnDressesSundresses', [364, 365, 366, 367]);
        } catch (\Exception $e) {
            dd($e);
        }
    }

    public function q_57($key,$item,$uuid){
        try {
            $this->typeCheckSingle($item,$uuid,'howMuchToSpendOnJacket', [368, 369, 370, 371]);
        } catch (\Exception $e) {
            dd($e);
        }
    }

    public function q_58($key,$item,$uuid){
        try {
            $this->typeCheckSingle($item,$uuid,'howMuchToSpendOnJeansTrousersSkirts', [372, 373, 374, 375]);
        } catch (\Exception $e) {
            dd($e);
        }
    }

    public function q_59($key,$item,$uuid){
        try {
            $this->typeCheckSingle($item,$uuid,'howMuchToSpendOnBags', [376, 377, 378, 379]);
        } catch (\Exception $e) {
            dd($e);
        }
    }

    public function q_60($key,$item,$uuid){
        try {
            $this->typeCheckSingle($item,$uuid,'howMuchToSpendOnBeltsScarvesShawls', [383, 384, 385]);
        } catch (\Exception $e) {
            dd($e);
        }
    }

    public function q_61($key,$item,$uuid){
        try {
            $this->typeCheckSingle($item,$uuid,'howMuchToSpendOnEarringsNecklacesBracelets', [380, 381, 382]);
        } catch (\Exception $e) {
            dd($e);
        }
    }

    public function q_62($key,$item,$uuid){
        try {
            $this->typeCheckSingle($item,$uuid,'whatPurpose', [135, 136, 137, 138, 139, 140, 141, 142, 143, 144, 145, 503, 504]);
        } catch (\Exception $e) {
            dd($e);
        }
    }

    public function q_63($key,$item,$uuid){
        try {
            $this->typeText($item,$uuid,'address');
        } catch (\Exception $e) {
            dd($e);
        }
    }

    public function q_64($key,$item,$uuid){
        try {
            $this->typeText($item,$uuid,'deliveryDate');
        } catch (\Exception $e) {
            dd($e);
        }
    }

    public function q_65($key,$item,$uuid){
        try {
            $this->typeCheckSingle($item,$uuid,'deliveryTime', [418, 419, 420, 507, 510]);
        } catch (\Exception $e) {
            dd($e);
        }
    }

    public function q_66($key,$item,$uuid){
        try {
            $this->typeCheckSingle($item,$uuid,'deliveryBackTime', [425, 426, 427, 508, 509]);
        } catch (\Exception $e) {
            dd($e);
        }
    }

    public function q_67($key,$item,$uuid){
        try {
            $this->typeText($item,$uuid,'bioName');
        } catch (\Exception $e) {
            dd($e);
        }
    }

    public function q_68($key,$item,$uuid){
        try {
            $this->typeText($item,$uuid,'socials');
        } catch (\Exception $e) {
            dd($e);
        }
    }

    public function q_69($key,$item,$uuid){
        try {
            $this->typeCheckSingle($item,$uuid,'knewAboutUs', [408, 409, 410, 411, 412, 413]);
        } catch (\Exception $e) {
            dd($e);
        }
    }

    public function q_70($key,$item,$uuid){
        try {
            $this->typeText($item,$uuid,'bioWeight');
        } catch (\Exception $e) {
            dd($e);
        }
    }

    public function q_71($key,$item,$uuid){
        try {
            $this->typeCheckSingle($item,$uuid,'delivery', [386, 387, 388]);
        } catch (\Exception $e) {
            dd($e);
        }
    }

    public function q_72($key,$item,$uuid){
        try {
            $this->typeCheckSingle($item,$uuid,'photosAttach', [403, 404]);
        } catch (\Exception $e) {
            dd($e);
        }
    }

    public function q_74($key,$item,$uuid){
        try {
            $this->typeText($item,$uuid,'address_a');
        } catch (\Exception $e) {
            dd($e);
        }
    }

    public function q_75($key,$item,$uuid){
        try {
            $this->typeText($item,$uuid,'addressHome');
        } catch (\Exception $e) {
            dd($e);
        }
    }

    public function q_76($key,$item,$uuid){
        try {
            $this->typeText($item,$uuid,'addressOffice');
        } catch (\Exception $e) {
            dd($e);
        }
    }

    public function q_77($key,$item,$uuid){
        try {
            $this->typeText($item,$uuid,'addressComment');
        } catch (\Exception $e) {
            dd($e);
        }
    }

    public function q_78($key,$item,$uuid){
        try {
            $this->typeCheckSingle($item,$uuid,'whatHelp', [131, 132, 133, 134]);
        } catch (\Exception $e) {
            dd($e);
        }
    }


    public function q_79($key,$item,$uuid){
        try {
            $this->typeText($item,$uuid,'bioSurname');
        } catch (\Exception $e) {
            dd($e);
        }
    }

    public function q_80($key,$item,$uuid){
        try {
            $this->typeCheckSingle($item,$uuid,'deliveryType', [389, 390]);
        } catch (\Exception $e) {
            dd($e);
        }
    }

    public function q_81($key,$item,$uuid){
        try {
            $this->typeText($item,$uuid,'boxberryPoint');
        } catch (\Exception $e) {
            dd($e);
        }
    }

    public function q_82($key,$item,$uuid){
        try {
            $this->typeText($item,$uuid,'boxberryCity');
        } catch (\Exception $e) {
            dd($e);
        }
    }

    public function q_83($key,$item,$uuid){
        try {
            $this->typeCheckSingle($item,$uuid,'whatSeason', [480, 481, 482, 483, 484]);
        } catch (\Exception $e) {
            dd($e);
        }
    }

    public function q_84($key,$item,$uuid){
        try {
            $this->typeText($item,$uuid,'bioPatronymic');
        } catch (\Exception $e) {
            dd($e);
        }
    }


    public function q_iiiiii($key,$item,$uuid){
        try {
            $this->typeCheckImage($item,$uuid,'','',[],[], '',true);
        } catch (\Exception $e) {
            dd($e);
        }
    }


    public function q_si($key,$item,$uuid){
        try {
            $this->typeCheckSingle($item,$uuid,'', [],true);
        } catch (\Exception $e) {
            dd($e);
        }
    }

    public function q_alt($key,$item,$uuid){
        try {
            $this->typeCheckAlternative($item, $uuid,'', [],[],true);
        } catch (\Exception $e) {
            dd($e);
        }
    }






























}
