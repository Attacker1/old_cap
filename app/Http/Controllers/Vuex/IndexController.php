<?php

namespace App\Http\Controllers\Vuex;

use App\Traits\VuexAutoMethods;

class IndexController
{
    use VuexAutoMethods;

    //*** root route
    public function index() {
        return view('vuex.index',['settings' => $this->settings()]);
    }

}
