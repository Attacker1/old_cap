<?php

namespace App\Http\Controllers\Admin\Anketa;

use App\Http\Controllers\Controller;
use App\Http\Models\Admin\ClientAnketaReference;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class TabReferenceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Сохраняет новый референс и
     * возвращает Вью списка референсов
     *
     * @param Request $request
     * @return View
     */
    public function store(Request $request)
    {

        try {
            $request->validate([
                'photo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);

            if ($request->file('photo')) {
                $path = "/anketa/reference/";
                $fileName =  "photo-".time().'.'.$request->photo->getClientOriginalExtension();
                $request->photo->storeAs('public'.$path, $fileName);

                Storage::disk('local')->put('example.txt', 'Contents');


                ClientAnketaReference::create([
                    'client_uuid' => $request->client_uuid ?? '',
                    'anketa_uuid' => $request->anketa_uuid,
                    'photo' => 'storage'.$path.$fileName,
                    'comment' => $request->comment ?? ''
                ]);

                return self::show($request->anketa_uuid);
            }
        } catch (\Exception $e) {
            Log::info($e, [self::class,'store']);
            abort(500);
        }
    }

    /**
     * Возвращает Вью списка референсов
     *
     * @param  int  $uuid
     * @return View
     */
    public static function show($uuid)
    {
        return view('partials.admin.anketa.tabs.reference',[
            'tab_reference' => ClientAnketaReference::where('anketa_uuid',$uuid)->get()]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return View
     */
    public function destroy($id)
    {
        try {
            $clientAnketaReference = $this->getClientAnketaReference($id);
            ClientAnketaReference::find($id)->delete();
//            $res->delete();
            return self::show($clientAnketaReference->anketa_uuid);
        } catch (\Exception $e) {
            Log::info($e, [self::class,'destroy']);
            abort(500);
        }

    }

    /**
     * @param $id
     * @return mixed
     */
    private function getClientAnketaReference($id){
        return ClientAnketaReference::where('id',$id)->first();
    }
}
