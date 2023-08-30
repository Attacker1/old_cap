<?php

namespace App\Http\Classes\Anketa;

use App\Http\Models\AdminClient\Questionnaire;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class QuestionDataToAnketa
{

    private $limit;
    private $adapter;
    private $adapterByKeys = [];

    public $kOld;
    public $kNew;

    public $newAnketaOutput;

    public function __construct($limit = 2)
    {
        $this->limit = $limit;

    }


    public function convertFirst($uuid): array
    {
        try {
            $collection = Questionnaire::whereUuid($uuid)->first();

            $io = new QuestionsVuexIO();
            $this->adapter = $io->oldAdapter;
            $this->byKeys();

            $this->kOld = [];
            $this->kNew = [];

            $this->newAnketaOutput = [];
            $this->checkAnswers($collection);
            $this->convertOldData($collection);

//            $arrDiff = array_diff($this->kOld, $this->kNew);

//            dump($arrDiff);
//            dump($this->newAnketaOutput);


        } catch (\Exception $e) {
            \Log::error('QuestionDataToAnketa', [$e]);
        }

        return $this->newAnketaOutput;
    }


    public function convertToAnketaField()
    {
        $collection = Questionnaire::whereNull('anketa')->limit($this->limit)->get(); //
        $io = new QuestionsVuexIO();
        $this->adapter = $io->oldAdapter;
        $this->byKeys();

        $collection->each(function ($questionnaire) {
            if (isset($questionnaire->data)) {
                $this->kOld = [];
                $this->kNew = [];


                $this->newAnketaOutput = [];
//                $this->checkAnswers($questionnaire);
                $this->convertOldData($questionnaire);

//                $arrDiff = array_diff($this->kOld, $this->kNew);

                Questionnaire::where('uuid', $questionnaire->uuid)->update(['anketa' => $this->newAnketaOutput]);

//                dump($this->newAnketaOutput);

//                dump( '======================================='); // 0cad6607-6bf6-4584-9799-9d75ad0d039a
            }

        });

    }


    public function byKeys()
    {
        foreach ($this->adapter as $k => $a) {
            $a['slug'] = $k;
            $this->adapterByKeys[$a['k']] = $a;
        }
    }

    public function checkAnswers($questionnaire)
    {
        try {
            $qn = $questionnaire->data['anketa']['question'];
            $r = [];

            foreach ($qn as $k => $q) if (array_key_exists('answer', $q)) {

                if (!is_null($q['answer']) && $q['answer'] !== '') {
                    if (is_array($q['answer'])) {
                        if (count($q['answer']) !== 0) {
                            $this->kOld[] = $k;
                            $r[$k . '--' . $q['type']] = $q['answer'];
                        }
                    } else {
                        $this->kOld[] = $k;
                        $r[$k . '--' . $q['type']] = $q['answer'];
                    }
                }
            }

//            dump($questionnaire->uuid,$r);
        } catch (\Exception $e) {
//            dd($e);
        }


    }

    public function convertOldData($questionnaire)
    {
//        dump($questionnaire->data);
//        dump($this->adapterByKeys);


        if (
            array_key_exists('anketa', $questionnaire->data)
            && array_key_exists('question', $questionnaire->data['anketa'])
        ) {

            $this->topData($questionnaire->data);
            $qn = $questionnaire->data['anketa']['question'];

            foreach ($qn as $k => $v) {

                $adapterCol = $this->adapterByKeys[$k];

                try {
                    if ($v['type'] == 'text' && array_key_exists('answer', $v) && $v['answer'] !== '') {
                        $this->text($k, $v, $adapterCol);
                    } elseif (in_array($v['type'], ['choice', 'check']) && array_key_exists('answer', $v) && $v['answer'] !== null) {
                        $this->choice($k, $v, $adapterCol);
                    } elseif ($v['type'] == 'files') {
                        $this->files($k, $v, $adapterCol);
                    }
                } catch (\Exception $e) {
//                    dd($k);
                }

            }
        } else {

            foreach ($questionnaire->data as $k => $v) {
                if ($k === 'coupon') {
                    $this->newAnketaOutput['coupon'] = $v;
                } elseif ($k === 'rf') {
                    $this->newAnketaOutput['rf'] = $v;
                } elseif ($k === 'amount') {
                    $this->newAnketaOutput['amount'] = $v;
                } else {
                    $adapterCol = $this->adapterByKeys[$k];

                    try {
                        if ($adapterCol['result'] == 'text'  && !empty($v)) {
                            $res['answer'] = $v;
                            $this->text($k, $res, $adapterCol);
                        }
                        elseif (in_array($adapterCol['result'], ['choice', 'check'])  && $v !== null) {
                            $res['answer'] = $v;
                            $this->choice($k, $res, $adapterCol);
                        }
                        elseif ($adapterCol['result'] == 'files') {
                            $res['answer'] = $v;
                            $this->files($k, $res, $adapterCol);
                        }
                    } catch (\Exception $e) {
//                    dd($k);
                    }
                }
            }


        }


    }



    public function topData($anketa)
    {

        if (array_key_exists('coupon', $anketa)) {
            $this->newAnketaOutput['coupon'] = $anketa['coupon'];
        }
        if (array_key_exists('rf', $anketa)) {
            $this->newAnketaOutput['rf'] = $anketa['rf'];
        }
        if (array_key_exists('anketa', $anketa) && array_key_exists('amount', $anketa['anketa'])) {
            $this->newAnketaOutput['amount'] = $anketa['anketa']['amount'];
        }
    }


    public function text($k, $v, $adapterCol)
    {

        if ($k == 62) {
            if (is_array($v['answer'])) { // новый формат
                $this->check($k, $v, $adapterCol);
            } else { // старый формат
                $this->kNew[] = $k;
                $this->newAnketaOutput[$adapterCol['slug']] = $v['answer'];
            }
        } elseif (in_array($k, [64, 27])) {
            $this->kNew[] = $k;
            $this->newAnketaOutput[$adapterCol['slug']] = Carbon::parse($v['answer'])->format('Y-m-d');
        } elseif (!empty($v['answer'])) {
            $this->kNew[] = $k;
            $this->newAnketaOutput[$adapterCol['slug']] = $v['answer'];
        }
    }

    public function choice($k, $v, $adapterCol)
    {
        if (!is_array($v['answer'])) {
            $v['answer'] = [$v['answer']];
        }
        $this->kNew[] = $k;
        foreach ($v['answer'] as $key => $val) {
            $this->newAnketaOutput[$adapterCol['slug']][] = $adapterCol['keys'][$val];
        }

    }

    public function check($k, $v, $adapterCol)
    {
        $this->kNew[] = $k;
        foreach ($v['answer'] as $key => $val) {
            $this->newAnketaOutput[$adapterCol['slug']][] = $adapterCol['keys'][$val];
        }
    }

    public function files($k, $v, $adapterCol)
    {

//        dump($v);
//        $this->newAnketaOutput[$adapterCol['slug']] = $v['answer'];
    }

    //пока не произойдет конвертация старая база
    public function convertFirstOld($amo_id): array
    {

        try {

            $collection =  DB::table('anketa')
                ->where('amo_id', $amo_id)
                ->latest()
                ->first();

            $collection->data = json_decode($collection->data, true);


            $io = new QuestionsVuexIO();
            $this->adapter = $io->oldAdapter;
            $this->byKeys();

            $this->kOld = [];
            $this->kNew = [];

            $this->newAnketaOutput = [];
            $this->checkAnswers($collection);

            $this->convertOldData($collection);

//            $arrDiff = array_diff($this->kOld, $this->kNew);

//            dump($arrDiff);
//            dump($this->newAnketaOutput);


        } catch (\Exception $e) {
            \Log::error('QuestionDataToAnketa', [$e]);
        }

        return $this->newAnketaOutput;
    }

}
