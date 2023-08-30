<?php

namespace App\Http\Controllers\Vuex\Lead;

use App\Http\Controllers\Controller;
use App\Http\Filters\Leads\ListingFilter;
use App\Http\Models\Admin\AdminUser;
use App\Http\Models\Catalog\Tags;
use App\Http\Models\Common\Lead;
use App\Http\Models\Common\LeadRef;
use App\Http\Models\Vuex\Anketa\AnketaQuestionOption;
use App\Traits\VuexAutoMethods;

class LeadController extends Controller
{
    use VuexAutoMethods;


    //*** root route
    public function index() {
        return view('vuex.lead.index',['settings' => $this->settings()]);
    }

    //*** api
    public function initList() {
        return [
            'leads' => $this->leads(),
            'leadStates' => $this->leadRefs(),
            'leadTags' => $this->leadTags(),
        ];
    }

    public function initLead() {
        return [
            'lead' => $this->lead(),
            'leadStates' => $this->leadRefs(),
            'leadTags' => $this->leadTags(),
            'deliveryIntervals' => $this->deliveryIntervals(),
            'deliveryBackIntervals' => $this->deliveryBackIntervals(),
        ];
    }

    public function fetchLeads() {
        return $this->leads();
    }


   //*** inner methods
    private function leads() {

        return Lead::with(['leadClient','leadState','leadTag'])->vuexFilter()
//            ->orderBy(request('filters.orderBy') ?? 'created_at', request('filters.orderDirection') ?? 'desc')
            ->paginate(request('per_page'));

    }

    private function lead() {
        // @TODO-cdnadom:  hasDelivery
        return Lead::with(['leadClient','leadState','leadTag','hasDelivery','questionnaire'])->where('uuid',request('uuid'))->first();
    }

    private function leadRefs() {
        return LeadRef::all();
    }

    private function leadTags() {
        return Tags::all();
    }

    private function deliveryIntervals() {
        return AnketaQuestionOption::whereQuestionId(61)->get();
    }

    private function deliveryBackIntervals() {
        return AnketaQuestionOption::whereQuestionId(62)->get();
    }






}
