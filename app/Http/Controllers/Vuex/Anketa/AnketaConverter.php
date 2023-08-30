<?php

namespace App\Http\Controllers\Vuex\Anketa;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Vuex\Anketa\ConverterFiles\ConvertEntities;
use App\Http\Models\AdminClient\Questionnaire;
use App\Traits\VuexAutoMethods;
use Illuminate\Support\Facades\DB;

class AnketaConverter extends Controller
{
    use VuexAutoMethods;

    public $result = [];
    public $toConvert = [];
    public $came = [];

    public function getQuestData(): array
    {
        $time_start = microtime(true);
        $this->saveAnswers();

        $count = DB::select('SELECT COUNT(*) AS `Count` FROM questionnaires');


        $quest = \DB::select('SELECT * FROM questionnaires JOIN (SELECT uuid FROM questionnaires  ORDER BY uuid LIMIT ?, ?) as b ON b.uuid = questionnaires.uuid'
            ,[\request('skip'),\request('take')]);

        $quests = [];
        foreach ($quest as $q) {
            $quests[] = [
                'uuid' => $q->uuid,
                'data' => json_decode($q->data),
                'anketa' => json_decode($q->anketa),
            ];
        }

        $time_end = microtime(true);
        $time = $time_end - $time_start;

        return [
            'count' => $count[0]->Count,
            'questData' => $quests,
            'skipQuest' => (int)\request('skip') + (int)\request('take'),
            'takeQuest' => \request('take'),
            'convertedAnswers' => (object)[],
            'result' => $this->result,
            'came' => $this->came,
            'toConvert' => $this->toConvert,
            '$time' => $time
        ];
    }


    public function saveAnswers() {
        foreach (request('convertedAnswers') as $uuid => $answer){
            $this->came[$uuid] = $uuid;

            $anketa = Questionnaire::whereUuid($uuid)->first();

            if(!$anketa->anketa) {
                $res =  Questionnaire::whereUuid($uuid)->update(['anketa' => $answer, 'is_test' => 1]);
                $this->result[$uuid] = $res;
            }
        }
    }


    public function getParse() {
        $count = DB::select('SELECT COUNT(*) AS `Count` FROM questionnaires');


        $quest = \DB::select('SELECT * FROM questionnaires JOIN (SELECT uuid FROM questionnaires  ORDER BY uuid LIMIT ?, ?) as b ON b.uuid = questionnaires.uuid'
            ,[\request('skip'),\request('take')]);

        $convert = new ConvertEntities($quest);


        foreach ( $convert->index() as $uuid => $answer){
            try {
                $this->came[$uuid] = $uuid;

                $anketa = Questionnaire::whereUuid($uuid)->first();

            if(!$anketa->anketa) {
                $res =  Questionnaire::whereUuid($uuid)->update(['anketa' => $answer, 'is_test' => 1]);
                $this->result[$uuid] = $res;
            }
            } catch (\Exception $e) {
                dd($e);
            }

        }

        return [
            'count' => $count[0]->Count,
//            'questData' => $quests,
            'skipQuest' => (int)\request('skip') + (int)\request('take'),
            'takeQuest' => \request('take'),
//            'convertedAnswers' => (object)[],
//            'result' => $this->result,
            'came' => count($this->came),
//            'toConvert' => $this->toConvert,
//            '$time' => $time
        ];

    }



    public function repareJewelery() {
        $count = DB::select('SELECT COUNT(*) AS `Count` FROM questionnaires');


        $quest = \DB::select('SELECT * FROM questionnaires JOIN (SELECT uuid FROM questionnaires  ORDER BY uuid LIMIT ?, ?) as b ON b.uuid = questionnaires.uuid'
            ,[\request('skip'),\request('take')]);

        $convert = new ConvertEntities($quest);


        foreach ( $convert->index() as $uuid => $answer){
            try {
                $this->came[$uuid] = $uuid;

                $anketa = Questionnaire::whereUuid($uuid)->first();

                if(!$anketa->anketa) {
                    $res =  Questionnaire::whereUuid($uuid)->update(['anketa' => $answer, 'is_test' => 1]);
                    $this->result[$uuid] = $res;
                }
            } catch (\Exception $e) {
                dd($e);
            }

        }

        return [
            'count' => $count[0]->Count,
//            'questData' => $quests,
            'skipQuest' => (int)\request('skip') + (int)\request('take'),
            'takeQuest' => \request('take'),
//            'convertedAnswers' => (object)[],
//            'result' => $this->result,
            'came' => count($this->came),
//            'toConvert' => $this->toConvert,
//            '$time' => $time
        ];
    }

    public function convertJewelery() {

    }





}
