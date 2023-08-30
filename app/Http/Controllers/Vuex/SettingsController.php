<?php

namespace App\Http\Controllers\Vuex;

use App\Http\Controllers\Controller;
use App\Http\Models\Admin\AdminUser;
use App\Http\Models\Vuex\Settings\ManageBlock;
use App\Http\Models\Vuex\Settings\ManageBlockItem;
use App\Http\Models\Vuex\Settings\Menu;
use App\Traits\VuexAutoMethods;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;


class SettingsController extends Controller
{
    use VuexAutoMethods;

    public function init(): array
    {
        return [
            'manageBlocks' => $this->manageBlocks(),
            'namespace' => request('namespace')
        ];
    }

    public function sideMenu()
    {
        return Menu::with(['children'])->whereNull('parent')
            ->where('active', 1)->orderBy('sort')
            ->get();
    }


    public function uploadImage(): string
    {
        $resp = '';

        try {
            $image = request('image');
            $base_path = request('path');
            $base_filename = request('filename');
            $ext = '.jpg';
            $path = "$base_path/$base_filename" . $ext;

            if (preg_match('/^data:image\/(\w+);base64,/', $image)) {
                $data = substr($image, strpos($image, ',') + 1);
                $data = base64_decode($data);

                if (Storage::disk('public')->put($path, $data)) {
                    mb_internal_encoding("UTF-8");
                    $resp = mb_substr($path, 1);
                }

            } else
                $resp = 'errrrr';


        } catch (\Exception $e) {
            Log::channel('anketa_arr')->critical('Запись фото: ' . $e->getMessage());
        }
        return $resp;

    }


    public function usersByRoles()
    {
        return [
            'stylist' => AdminUser::byRole('stylist')
        ];
    }

    // manage Blocks
    public function manageBlock(){
        return [
            'manageBlocks' => $this->createOrUpdateManageBlock()
        ];
    }

    public function manageBlockItems(){
        return [
            'manageBlocks' => $this->createOrUpdateManageBlockItems()
        ];
    }


    //*** inner

    private function manageBlocks($namespace = null)
    {
        return ManageBlock::with(['items'])
            ->where('namespace', $namespace ?? request('namespace'))->get();
    }

    private function getNamespace($id) {
        return ManageBlock::where('id',$id)->first()->namespace;
    }



    private function createOrUpdateManageBlock(){
        $request = $this->autoDTO('manage_blocks',request('manageBlock'), true) ;
        ManageBlock::updateOrCreate(['id' => $request['id']],$request);
        return $this->manageBlocks($request['namespace']);
    }

    private function createOrUpdateManageBlockItems(){
        try {
            $request = request('manageBlockItem');
            ManageBlockItem::updateOrCreate(['id' => $request['id'], 'block_id' => $request['block_id']],$request);
            $namespace = $this->getNamespace($request['block_id']);
            return $this->manageBlocks($namespace);

        } catch (\Exception $e) {
            dd($e);
        }
    }


}
