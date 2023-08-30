<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use App\Http\Models\Catalog\CategoriesTranslator;
use App\Http\Models\Common\Lead;
use App\Http\Models\Common\LeadCorrections;
use App\Http\Requests\Catalog\CategoriesTranslatorRequest;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Yajra\DataTables\Facades\DataTables;

class LeadCorrectionsController extends Controller
{
    protected LeadCorrections $model;

    /**
     * CategoriesTranslatorController constructor.
     * @param LeadCorrections $model
     */
    public function __construct(LeadCorrections $model)
    {
        $this->model = $model;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     * @throws \Exception
     */
    public function index()
    {
        if(request()->ajax()) {

            $dt = DataTables::eloquent($this->model::query());
            $dt->addColumn('user_name', function ($data) {
                return @$data->users->name;
            });
            $dt->addColumn('amo_id', function ($data) {
                return @$data->leads->amo_lead_id;
            });
            $dt->addColumn('action', function ($data) {
                $buttons = '';
                $buttons .= '<a href = "' . route('lead.corrections.edit', $data->id) . '" title = " Редактировать" ><i class=" ml-5 fa far fa-edit text-primary"></i></a></a >';
                //$buttons .= '<a  data-dt="true" data-url = "'.route('lead.corrections.destroy', $data->id).'" class="ml-3 confirm-delete" title = "" ><i class="fa far fa-trash-alt text-danger"></i></a></a >';

                return $buttons;
            });
            return $dt->make(true);
        }

        return view("admin.leads.corrections.index", [
            'title' => 'Коррекция цен по сделкам'
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param bool $lead_uuid
     * @return Response
     */
    public function create()
    {

        if ($lead_uuid = \request()->input('lead_uuid'))
            return self::storeDirect($lead_uuid);

        return view("admin.leads.corrections.create", [
            'title' => 'Новая коррекция',
            "lead_uuid" => $lead_uuid
        ]);
    }

    private function storeDirect($lead_uuid){

        $data = [
            "lead_uuid" => $lead_uuid,
            "user_id" => auth()->id()
        ];

        $item = $this->model::create($data);
        toastr()->success('Добавлена новая коррекция');
        return redirect()->route('lead.corrections.edit',$item->id);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return Response
     */
    public function store(Request $request)
    {

        if (!$lead_uuid = self::validateLead($request->input("amo_id"))){
            toastr()->warning("Сделки не существует");
            return redirect()->back()->withInput();
        }

        $data = [
            "lead_uuid" => $lead_uuid,
            "note_id" => $request->input("note_id"),
            "user_id" => auth()->id()
        ];

        $item = $this->model::create($data);
        toastr()->success('Добавлена новая коррекция');
        return redirect()->route('lead.corrections.edit',$item->id);
    }

    private function validateLead($amo_lead_id){

        if (!$lead = Lead::where("amo_lead_id",$amo_lead_id)->first())
            return false;

        return $lead->uuid;

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $item = $this->model::with(["leads","products"])->find($id);

        if (!$products = self::getRelations($item))
            return redirect()->back();

        return view("admin.leads.corrections.edit", [
            'title' => 'Редактировать коррекцию ' . @$item->leads->amo_lead_id,
            'data' => $item,
            "products" => $products
        ]);
    }

    private function getRelations($correction){
        try {
            return $correction->leads->notes->where("state", 1)->sortByDesc("id")->first()->products()->get();
        }
        catch (\Exception $e){
            return toastr()->warning("Что то не так с товарами или запиской!");

        }
    }

    public function update($id)
    {

        $item = $this->model::with(["leads","products"])->find($id);
        $collection = collect(\request()->input("products"));
        self::updatePrices($item,$collection);

        toastr()->success('Коррекция сохранена');
        return redirect()->route('lead.corrections.index');
    }


    public function destroy($id)
    {

        $this->model::find($id)->delete();
        toastr()->error('Удалена коррекция по сделке');
        return redirect()->route('lead.corrections.index');
    }

    private function updatePrices(Model $item, Collection $collection){

        if ($corrections = $collection->whereNotNull()->toArray()){
            foreach ($corrections as $k=>$v){
                $products[] = [
                    "product_id" => $k,
                    "price" => $v,
                    "lead_uuid" => $item->lead_uuid
                ];
            }
            $item->products()->sync($products);
        }

        return true;
    }
}
