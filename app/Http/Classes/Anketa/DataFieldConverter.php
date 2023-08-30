<?php

namespace App\Http\Classes\Anketa;

use App\Http\Controllers\Controller;
use App\Http\Models\AdminClient\Questionnaire;
use App\Http\Models\Vuex\Anketa\AnketaQuestion;
use App\Http\Models\Vuex\Anketa\AnketaQuestionType;


class DataFieldConverter extends Controller
{
    private $questionnaire;
    private $anketaTypes;
    private $anketaQuestions;
    public $keysBySlags = [];

    public $allResults = [];
    public $result = [];

    public $saveResult = [];

    // exclude q 73,
    // collect keys  63(74,) <= (75,76)

    public $uuid;

//    public $variant_2_from = '98883';
    public $variant_2_from = '100000';

    public $prod = false;

    public function __construct($isProd = false)
    {
        $this->prod = $isProd;

        $this->anketaTypes = AnketaQuestionType::withTrashed()->get();
        $this->anketaQuestions = AnketaQuestion::withTrashed()->with(['options', 'tables.optionsPrints'])->get();



    }

    public function envTest()
    {
        $test = Questionnaire::whereNull('anketa')
//            ->whereNotIn('converted',[1,8888])
//                ->whereNull('anketa')
//            ->where('uuid', 'fac00b3e-2fd5-4964-ab1c-757ffdab88b2')
//            ->where('uuid', '6841740f-bfac-48bf-9d37-37a63cd0bbfd')
//            ->whereNotNull('data->pvz_id')->where('data->pvz_id','!=','')->whereNotNull('data->pvz_address')
//            ->skip(10000) // 5
//            ->take(10000)
            ->limit(10)
            ->get();

//        dump($test[0]->data);
//        dump($test[1]->uuid);
//        dump($test[9]->uuid);


        return $test;

    }

    public function envProd()
    {
        return Questionnaire::whereNull('anketa')->whereNotNull('data')
            ->limit(500)
            ->orderBy('id', 'ASC')
            ->get();
    }

    public function index()
    {

        $this->slagsByKeys();

        $it = $this->prod ? $this->envProd() : $this->envTest();

        foreach ($it as $i) {
            $this->result = [];
            try {
                if ($i->data && is_array($i->data)) {
                    $this->uuid = $i->uuid;
//                    dump($i->uuid);
                    if (isset($i->data['anketa'])) {
                        $this->convertTopLevel($i);
                    } else {
                        $this->modern($i);
                    }

                    ksort($this->result);

                    if ($this->prod) {
                        array_push($this->allResults, $this->save($i, $this->result));
                    } else {
                        $this->allResults[] = $this->result;
                    }

                } else {
                    if (!$this->prod) {
//                        dump($i->uuid . ' -- ' . Questionnaire::whereUuid($i->uuid)->update(['converted' => 8888]));
                    }

//                    if($this->prod){
//                        Questionnaire::whereUuid($i->uuid)->update(['converted' => 1]);
//                    }
                }
            } catch (\Exception $e) {
                return [$this->uuid, $e];
            }

        }

        if (!$this->prod) {
            dump(count($this->allResults));
            dump($this->allResults);
        }


//        dump($this->saveResult);

        if ($this->prod) {
            return count($this->saveResult);
        }


    }

    public function slagsByKeys()
    {
        $excludeId = [58, 67, 59];
        foreach ($this->anketaQuestions as $item) if (!in_array($item->id, $excludeId)) {
            if (in_array($item->type_id, [9, 12, 21])) {
                foreach ($item->options as $option) {

                    if (!is_null($option->old_key) && $option->type != 'nextQuestion') {
//                        dump($option->slug.' -- '. $option->old_key.' -- '. $option->id);
                        $this->keysBySlags[$option->old_key] = $option->slug;
                    }
                }
            } elseif ($item->type_id == 11) {
                $optTableKeysById = [];
                foreach ($item->tables as $tables) {
                    foreach ($tables->optionsPrints as $tblOpts) {
                        $optTableKeysById[$tblOpts->option_key] = $tblOpts->id;
                    }
                }
                $this->keysBySlags[$item->old_key] = [
                    'slug' => $item->slug,
                    'keys' => $optTableKeysById
                ];

            } elseif (in_array($item->type_id, [17, 19])) {
                $this->keysBySlags[$item->old_key] = $item->slug;

            } else {
                $optKeysById = [];
                foreach ($item->options as $opt) {
                    if (!is_null($opt->option_key)) {
                        $optKeysById[$opt->option_key] = $opt->id;
                    }
                }

                $this->keysBySlags[$item->old_key] = [
                    'slug' => $item->slug,
                    'keys' => $optKeysById
                ];
            }

        }

        ksort($this->keysBySlags);

        if (!$this->prod) {
            dump($this->keysBySlags);

            $keys = [];
            $slugKeys = [];


            foreach ($this->keysBySlags as $k => $i) {
                if (is_array($i)) {
                    $keys[$i['slug']] = $i['keys'];
                    $slugKeys[$k] = $i['slug'] . ' -- []';
                } else {
                    $keys[$i] = 'tpl';
                    $slugKeys[$k] = $i . ' -- ';
                }

            }
            ksort($keys);
            ksort($slugKeys);
//            dump($slugKeys);
//            dump($keys);
//            dump(count($keys));
        }


    }

    public function convertTopLevel($item)
    {
        foreach ($item->data as $strKey => $dataItem) {
            if ($strKey === 'anketa') {
                $this->convertAnketa($dataItem, $item->uuid);
            } else {
                if ($dataItem) {
                    $this->result[$strKey] = $dataItem;
                }
            }
        }

    }

    public function convertAnketa($anketa, $uuid)
    {
        foreach ($anketa as $strKey => $anketaItem) if ($strKey != 'disclaimer') {
            if ($strKey == 'question') {
                $this->convertQuestion($anketaItem, $uuid);
            } else {
                $this->result[$strKey] = $anketaItem;
            }
        }
    }

    public function convertQuestion($questions, $uuid)
    {

        $address = '';
        foreach ($questions as $key => $question) {

            if (in_array($key, [63, 74, 75, 76])) { // addres = street + house concat
                if (array_key_exists('answer', $question)) {
                    $address .= $question['answer'] . ' ';
                }
            } else {
                if (array_key_exists($key, $this->keysBySlags)) { // if not excluded keys
                    if (is_array($this->keysBySlags[$key])) { // keys of options type
                        $this->arrayOptions($key, $question);
                    } else { // not options
                        $this->stringsOption($key, $question);
                    }
                }
            }

        }


        if (!empty(trim($address))) {
            $this->result[$this->keysBySlags[63]] = trim($address);
        }
        // end of answers parsing
//        $this->result['uuid'] = $uuid;
    }

    public function stringsOption($key, $question)
    {
        try {
            if ($question['answer']) {
                $this->result[$this->keysBySlags[$key]] = $question['answer'];
            }
        } catch (\Exception $e) {
            dd($key.' -- '.json_encode($question).'---'.$e);
        }
//        $this->result[$this->keysBySlags[$key]] = $question['answer'];

    }

    public function arrayOptions($key, $question)
    {
        try {
            if (array_key_exists('answer', $question) && $question['answer']) {
                $currentSlug = $this->keysBySlags[$key]['slug'];
                $this->result[$currentSlug] = [];

                if (is_array($question['answer'])) { // if array
                    foreach ($question['answer'] as $anverKey) {
                        if (is_numeric($anverKey)) {
                            if ($currentSlug === 'whatPurpose' && $anverKey === 13) {
                                array_push($this->result[$currentSlug], $question['option'][13]['text']);
                            } else {
                                array_push($this->result[$currentSlug], $this->keysBySlags[$key]['keys'][$anverKey]);
                            }

                        } else {
                            try {
                                array_push($this->result[$currentSlug], $anverKey);
                            } catch (\Exception $e) {
                                dd($e, '$anverKey_' . $anverKey, '$key_' . $key, $question);
                            }

                        }
                    }
                } else {
                    if (is_integer($question['answer'])) {
                        array_push($this->result[$currentSlug], $this->keysBySlags[$key]['keys'][$question['answer']]);
                    } else {
                        try {
                            array_push($this->result[$currentSlug], $question['answer']);
                        } catch (\Exception $e) {
                            dd($e, '$anverKey_' . $question['answer'], '$key_' . $key, $question);
                        }

                    }

                }

            }
        } catch
        (\Exception $e) {
            dd($this->uuid, $e, $key, $question);
        }

    }


//    getRealKey(){}

    public function modern($item)
    {
        try {
            $address = '';
            foreach ($item->data as $k => $v) {
                try {
                    if (is_numeric($k) && !in_array((int)$k,[63, 74, 75, 76])) {
                        if(is_array($this->keysBySlags[(int)$k])){
                            if($v){
                                $this->arrayOptionsModern((int)$k,['answer' => is_array($v) ? $v : [(int)$v] ]);
                            }
                        } else {
                            if($v){
                                $this->stringsOption((int)$k,['answer' => $v]);
                            }
                        }
                    } else {

                        if (in_array($k, [63, 74, 75, 76])) { // addres = street + house concat
                            $address .= $v . ' ';
                        } else {
                            $this->result[$k] = $v;
                        }
                    }
                } catch (\Exception $e) {
                    dd($k .' -- '.$e);
                }
            }

            if (!empty(trim($address))) {
                $this->result[$this->keysBySlags[63]] = trim($address);
            }
        } catch (\Exception $e) {
            dd($e);
        }

    }

    public function arrayOptionsModern($key, $question)
    {
        try {
            if (array_key_exists('answer', $question) && $question['answer']) {
                $currentSlug = $this->keysBySlags[$key]['slug'];
                $this->result[$currentSlug] = [];

                if (is_array($question['answer'])) { // if array
                    foreach ($question['answer'] as $anverKey) {
                        if (is_numeric($anverKey)) {
                            if ($currentSlug === 'whatPurpose' && $anverKey === 13) {
                                continue;
//                                array_push($this->result[$currentSlug], $question['option'][13]['text']);
                            } else {
                                array_push($this->result[$currentSlug], $this->keysBySlags[$key]['keys'][$anverKey]);
                            }

                        } else {
                            try {
                                array_push($this->result[$currentSlug], $anverKey);
                            } catch (\Exception $e) {
                                dd($e, '$anverKey_' . $anverKey, '$key_' . $key, $question);
                            }

                        }
                    }
                } else {
                    if (is_integer($question['answer'])) {
                        array_push($this->result[$currentSlug], $this->keysBySlags[$key]['keys'][$question['answer']]);
                    } else {
                        try {
                            array_push($this->result[$currentSlug], $question['answer']);
                        } catch (\Exception $e) {
                            dd($e, '$anverKey_' . $question['answer'], '$key_' . $key, $question);
                        }

                    }

                }

            }
        } catch
        (\Exception $e) {
            dd($e, $key, $question);
        }

    }


    public function save($item, $newData)
    {
        try {
//            $save = Anketa::create([
//                'uuid' => $item->uuid,
//                'id' => $item->id,
//                'code' => $item->code,
//                'data' => $newData,
//                'amo_id' => $item->amo_id,
//                'client_uuid' => $item->client_uuid,
//                'manager_comment' => $item->manager_comment,
//                'filename' => $item->filename,
//                'status' => $item->status,
//                'created_at' => $item->created_at,
//                'updated_at' => $item->updated_at,
//                'deleted_at' => $item->deleted_at,
//            ]);
            return Questionnaire::whereUuid($item->uuid)->update(['anketa' => $newData]);

        } catch (\Exception $e) {
            dd($e);
        }


    }
}
