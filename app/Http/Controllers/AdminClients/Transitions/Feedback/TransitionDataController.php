<?php

namespace App\Http\Controllers\AdminClients\Transitions\Feedback;

use App\Http\Classes\Common;
use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use App\Http\Models\AdminClient\FeedbackgeneralQuize;
use App\Http\Models\AdminClient\FeedbackQuize;
use App\Http\Models\Catalog\Note;

class TransitionDataController extends Controller
{

    public function feedback_main()
    {
        $all_main_feedback = DB::table('oc_main_feedback')->get();
        foreach ($all_main_feedback as $item)
        {
            
            $feedback_general = new FeedbackgeneralQuize();

            $feedback_general->old_id = $item->ID;
            if($item->Rating_personality) $feedback_general->personal_attitude = $item->Rating_personality;
            if($item->Rating_style) $feedback_general->design = $item->Rating_style;
            if($item->Rating_again) $feedback_general->buy_more = $item->Rating_again;
            if($item->Rating_all) $feedback_general->general_impression = $item->Rating_all;
            if($item->Note_recom) $feedback_general->stylist_note_wanted = $item->Note_recom;
            if($item->Comment) $feedback_general->other_any_comments = $item->Comment;
            if($item->NPS) $feedback_general->recommended = $item->NPS;
            if($item->Reason_response) $feedback_general->mark_reason = $item->Reason_response;
            if($item->Rework) $feedback_general->mark_up_actions = $item->Rework;
            if($item->Rating_delivery) $feedback_general->delivery_mark = $item->Rating_delivery;
            if($item->Comment_delivery) $feedback_general->delivery_comment = $item->Comment_delivery;
            if($item->AMOID) $feedback_general->amo_id = $item->AMOID;
            if($item->TIMESTAMP) $feedback_general->created_at = $item->TIMESTAMP;

            switch($item->New_stylist) {
                case 'Да': $feedback_general->new_stylist = 'yes'; break;
                case 'Нет': $feedback_general->new_stylist = 'no'; break;
            }

            $feedback_general->save();
        }
    }

    public function feedback()
    {
        $all_feedback = DB::table('oc_feedback')->get();
        foreach ($all_feedback as $item)
        {
            
            $feedback = new FeedbackQuize();

            $feedback->old_id = $item->ID;
            if($item->Timestamp) $feedback->created_at = $item->Timestamp;
            if ($item->Code) $feedback->old_code = $item->Code;
            
            switch($item->Purchase_feedback) { 
                case 'Купил(а)':$feedback->action_result = 'buy'; break;
                case 'Вернул(а)':$feedback->action_result = 'return'; break;
            }

            switch($item->Size_feedback) { 
                case 'Мал':$feedback->size_result = 'small'; break;
                case 'Как раз':$feedback->size_result = 'ok'; break;
                case 'Большой':$feedback->size_result = 'big'; break;
            }

            if($item->Quality_feedback) $feedback->quality_opinion = $item->Quality_feedback;
            if($item->Price_feedback) $feedback->price_opinion = $item->Price_feedback;
            if($item->Style_feedback) $feedback->style_opinion = $item->Style_feedback;
            if($item->Comment_feedback) $feedback->comments = $item->Comment_feedback;
            if($item->AMOID) $feedback->amo_id = $item->AMOID;
            if($item->Order_feedback) $feedback->order = $item->Order_feedback;
            if($item->URL) $feedback->old_url = $item->URL;
            if($item->cost) $feedback->old_cost = $item->cost;

            $feedback->save();
        }
    }

    public function set_relation()
    {
        $all_main_feedback = FeedbackgeneralQuize::all();
        foreach ($all_main_feedback as $item)
        {
            if($item->amo_id) {
                $feedback = FeedbackQuize::where('amo_id', $item->amo_id)->update(['feedbackgeneral_quize_id' => $item->id]);
            }   
        }

    }

    public function set_product_id()
    {
        $all_feedback = FeedbackQuize::all();
        foreach ($all_feedback as $feedback) {
            $note = Note::where('order_id', $feedback->amo_id)->first();
            if($note) {
                $product = $note->products()->get();
                if($product) {
                    dd($product);
                }
            }
        }


    }

}
