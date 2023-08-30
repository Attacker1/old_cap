<?php

namespace App\Http\Controllers\Vuex\Anketa;

use App\Http\Controllers\Controller;
use App\Http\Models\Vuex\Anketa\AnketaBuilder;
use App\Traits\VuexAutoMethods;
use http\Env\Response;

class AnketaBuilderController extends Controller
{
    use VuexAutoMethods;

    private function grid(){
        return AnketaBuilder::whereNull('deleted_at')->get();
    }
    public function create(){
        return AnketaBuilder::create(request('item'));
    }
    public function update(){
        return AnketaBuilder::whereId(request('id'))->update(request('item'));
    }
    public function delete(){
        return AnketaBuilder::whereId(request('id'))->delete();
    }

    public function createAndFetch(){
        $this->create();
        return $this->grid();
    }

    public function updateAndFetch(){
        $this->update();
        return $this->grid();
    }

    public function deleteAndFetch(){
        $this->delete();
        return $this->grid();
    }

    public function makeDefault(){
        AnketaBuilder::whereNotNull('id')->update(['is_current' => 0]);
        AnketaBuilder::whereId(request('id'))->update(['is_current' => 1]);
        return $this->grid();
    }


}
