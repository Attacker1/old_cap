@extends('admin.layout-metronic.default')

@section('title'){{ $title }}@endsection

@section('breadcrumb'){{ $title }}@endsection

@section('actions')
    <div class="float-right">
            <a href="{{ route('leads.edit',$data->lead_uuid) }}" target="_blank"><i class="fas fa-external-link-alt text-primary mr-3"></i></a>

        <a href="{{ route('lead.corrections.index') }}"><i class="fas fa-window-close text-danger"></i></a>
    </div>
@endsection

@section('content')

    <div class="card card-custom gutter-b">
        <div class="card-body">
            @if ($errors->any())
                <ul class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            @endif

            <form name="form" method="post" action="{{ route('lead.corrections.update',$data->id) }}">
                {{ csrf_field() }}
                @method("PUT")
                <div class="row">

                    <div class="input-group mb-6 col-sm-6">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="lead_uuid">ID сделки</span>
                        </div>
                        <input type="text" name="lead_uuid" id="lead_uuid" value="{{ @$data->leads->amo_lead_id }}"
                               class="form-control " autocomplete="off" aria-label=""
                               aria-describedby="lead_uuid_label" disabled>

                    </div>

                    <div class="input-group mb-6 col-sm-4">
                        <button type="submit" class="btn btn-primary waves-effect waves-light">Сохранить
                        </button>
                    </div>
                </div>

                <div class="col-sm-12">
                    <section id="note_products">
                        @if($products)
                            <div class="row card col-sm-12 ">
                                <div class="col-sm-12 mt-2" id="sortable" data-sortable-id="0" aria-dropeffect="move">
                                    <div class="row">
                                        @foreach(@$products as $item)
                                            <div class="col-sm-6 row" id="order-{{ $item->id }}" data-id="{{ $item->id }}" data-item-sortable-id="0" role="option" aria-grabbed="true" style="">
                                                @if($img = @$item->attachments()->where('main',1)->first()->url)
                                                    <div class="col-sm-3">
                                                        <div class="notes_thumb"><img src="{{ @$img }}"  width="64"></div>
                                                        <span class="price" > <span class="label label-inline label-pill label-success label-rounded ml-1 mt-2" text-capitalized=""> {{ number_format(round($item->price), 0, '.', ' ') }} р. </span></span>
                                                    </div>
                                                @else
                                                    <div class="col-sm-3"><a href="#" class="img-thumbnail"><i class="fa fa-picture-o" aria-hidden="true"></i></a></div>
                                                @endif
                                                <div class="col-sm-9 d-inline">
                                                    <div class="notes_name cursor-move mb-1"><h5 class="card-label">{{ @$item->name }}</h5></div>
                                                    <div class="sku text-muted mb-1"><b class="text-danger">Артикул:</b> {{ @$item->sku }} </div>
                                                    <div class="mb-1"><b class="text-primary">Бренд:</b> {{ @$item->brands()->first()->name }}</div>
                                                    <div class=""><b class="text-info">Размер:</b> {{ @$item->size ?? '—' }}</div>
                                                </div>
                                                    <div>
                                                        <div class="input-group col-sm-12 mt-3">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text bg-danger text-white" >Новая цена</span>
                                                            </div>
                                                            <input type="number" min="0" name="products[{{ @$item->id }}]"  value="{{ @$data->products()->where("product_id",$item->id)->first()->pivot->price }}"
                                                                   class="form-control bg-light "  autocomplete="off" aria-label=""
                                                                   aria-describedby="price_id[{{ @$item->id }}]_label">

                                                        </div>
                                                    </div>
                                                <div class=" col-sm-12"> <hr/></div>

                                            </div>

                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            @endif

                    </section>
                </div>
            </form>
        </div>
    </div>

@endsection



