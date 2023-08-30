<?php

namespace App\Http\Controllers\Admin\Anketa;

use App\Http\Controllers\Controller;
use App\Http\Models\AdminClient\Questionnaire;
use Bnb\Laravel\Attachments\Attachment;
use Doctrine\Common\Annotations\Annotation\Attributes;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class TabPhotoSupportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        echo self::class;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //http://stage-0.thecapsula.loc/admin/anketa/4be5f4f8-b044-4753-ae48-45fe2de98486
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return View | string
     */
    public function store(Request $request)
    {
        $questionnaire_uuid = '';

        try {

            foreach ($request->slim as $slim ) {
                $slim = json_decode($slim, true) ;
                $questionnaire_uuid = $slim['meta']['uuid'];
                $questionnaire = Questionnaire::where('uuid',$questionnaire_uuid)->first();
                $questionnaire->uuid = null;
                $questionnaire->attach(\Request::file('slim_output_0'),[
                    'model_uuid' => $slim['meta']['uuid'],
                    'group' => 'tab',
                    'title' => 'photo',
                    'disk' => 'public',
                ]);
            }

            return $this->show($questionnaire_uuid);

        } catch (\Exception $e) {

            Log::info($e, [self::class,'store']);

            $viewErrorMessage = [
                'status' => 'failure',
                'message' => 'Ошибка сохранения фото'
            ];

            return json_encode($viewErrorMessage);
        }
    }


    /**
     * Выборка всех фото от поддержки
     * @param $uuid
     * @return View
     */
    public static function show($uuid)
    {
//        return Attachment::where('model_uuid', $uuid)->get();
        $attachments = Attachment::where('model_uuid', $uuid)->get();
        return view('partials.admin.anketa.tabs.supportPhoto',['attachments' => $attachments]);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return View
     */
    public function edit($uuid)
    {
//        $attachments = Attachment::where('model_uuid', $uuid)->get();
//        return view('partials.admin.anketa.tabs.supportPhoto',['attachments' => $attachments]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $attachmentID
     * @return View | bool
     */
    public function destroy($attachmentID)
    {
        try {

            $attachment = $this->getAttachment($attachmentID);
            Attachment::where('uuid',$attachmentID)->delete();

            return $this->show($attachment->model_uuid);

        } catch (\Exception $e) {

            Log::info($e, [self::class,'destroy']);
            abort(500);
            return false;

        }
    }

    private function getAttachment($attachmentID){
        return Attachment::where('uuid',$attachmentID)->first();
    }
}
