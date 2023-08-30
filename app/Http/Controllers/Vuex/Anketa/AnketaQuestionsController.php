<?php

namespace App\Http\Controllers\Vuex\Anketa;

use App\Http\Controllers\Controller;
use App\Http\Models\Vuex\Anketa\AnketaQuestion;
use App\Http\Models\Vuex\Anketa\AnketaQuestionOption;
use App\Http\Models\Vuex\Anketa\AnketaQuestionType;
use App\Traits\VuexAutoMethods;

class AnketaQuestionsController extends Controller
{
    use VuexAutoMethods;


    public function fetchQuestionsList(): array
    {
        return [
            'grid' => $this->grid(),
            'questionTypes' => AnketaQuestionType::all()
        ];
    }

    public function fetchUpdateQuestion(): array
    {
        $this->updateQuestion(
            request('id'),
            $this->autoDTO('anketa_questions', request('question')));

        return [
            'grid' => $this->grid(),

        ];
    }


    public function fetchCreateQuestion(): array
    {
        $this->createQuestion($this->autoDTO('anketa_questions', request('question')));
        return [
            'grid' => $this->grid(),

        ];
    }


    // questionsOptions

    public function fetchCreateQuestionOption() : array { // add options
        foreach (request('questionOption') as $option){
            AnketaQuestionOption::create($option);
        }
        return [
            'grid' => $this->grid()
        ];
    }




    private function grid()
    {
        return AnketaQuestion::with(['options'])->get();
    }

    private function updateQuestion($id, $question)
    {
        return AnketaQuestion::whereId($id)->update($question);
    }

    public function createQuestion($question){
        return AnketaQuestion::create($question);
    }




    // AnketaQuestionType
//    public function




}
