@extends('admin.layout-metronic.default')

@section('title'){{ $title }}@endsection

@section('breadcrumb'){{ $title }}@endsection

@section('actions')
    <div class="float-right">
            <a href="{{ route('leads.edit',$data->lead_uuid) }}" target="_blank"><i class="fas fa-external-link-alt text-primary mr-3"></i></a>

        <a href="{{ route('admin.doli.index') }}"><i class="fas fa-window-close text-danger"></i></a>
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

            <form name="form" method="post" action="{{ route('admin.doli.update',$data->id) }}">
                {{ csrf_field() }}
                @method("PUT")

                    <section id="note_products">
                        @if($products)

                            <div class="row card col-sm-12 ">
                                    <div class="row">
                                        <div class="m-4 float-left">
                                            <div class="input-group">
                                                <div class="input-group-prepend ">
																<span class="input-group-text bg-success text-white">
                                                                    <i class="la la-calendar-o text-white"></i> <span class="ml-2">Дата создания</span>
																</span>
                                                </div>
                                                <input type="text" class="form-control disabled" disabled placeholder="" value="{{ \Carbon\Carbon::parse($data->created_at)->format("d.m.Y H:i") }}">
                                            </div>
                                        </div>

                                        <div class="m-4 float-left">
                                            <div class="input-group">
                                                <div class="input-group-prepend ">
																<span class="input-group-text bg-primary text-white">
                                                                    <i class="la la-check text-white"></i> <span class="ml-2">Статус</span>
																</span>
                                                </div>
                                                <input type="text" class="form-control disabled text-uppercase text-primary" disabled placeholder="" value="{{ $data->state }}">
                                            </div>
                                        </div>

                                        <div class="m-4 float-left">
                                            <div class="input-group">
                                                <div class="input-group-prepend ">
																<span class="input-group-text bg-danger text-white">
                                                                    <i class="la la-rub text-white"></i> <span class="ml-2">Сумма:</span>
																</span>
                                                </div>
                                                <input type="text" class="form-control disabled text-uppercase text-danger" disabled placeholder="" value="{{ $data->amount }}">
                                            </div>
                                        </div>
                                        @foreach(@$products as $item)
                                            <div class="mt-2 ml-2 p-1 col-sm-12 col-md-6 col-lg-4 row" id="order-{{ $item->id }}" data-id="{{ $item->id }}" data-item-sortable-id="0" role="option" aria-grabbed="true" style="">
                                                @if($img = @$item->product->attachments()->where('main',1)->first()->url)
                                                    <div class="col-sm-4">
                                                        <div class="notes_thumb"><img src="{{ @$img }}"  width="64"></div>
                                                        <span class="price" > <span class="label label-inline label-pill label-success label-rounded ml-1 mt-2" text-capitalized=""> {{ number_format(round($item->price), 0, '.', ' ') }} р. </span></span>
                                                    </div>
                                                @else
                                                    <div class="col-sm-4"><a href="#" class="img-thumbnail"><i class="fa fa-picture-o" aria-hidden="true"></i></a></div>
                                                @endif
                                                <div class="col-sm-7 d-inline">
                                                    <div class="notes_name cursor-move mb-1"><h5 class="card-label">{{ @$item->product->name }}</h5></div>
                                                    <div class="sku text-muted mb-1"><b class="text-danger">Артикул:</b> {{  @$item->product->sku }} </div>
                                                    <div class="mb-1"><b class="text-primary">Бренд:</b> </div>
                                                    <div class=""><b class="text-info">Размер:</b> {{  @$item->product->size ?? '—' }}</div>
                                                    <div class="input-group form-group text-center mt-2 ">
                                                        <label class="checkbox">
                                                            <input type="checkbox" name="item[{{ @$item->id }}]" value="{{ @$item->price }}" class="checkable">
                                                            <span></span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>

                                        @endforeach
                                    </div>
                            </div>
                            @endif

                    </section>
                <div class="mt-3">
                    <div class="float-left ">
                        <div class="col-12 col-form-label">
                            <div class="radio-inline">
                                <label class="radio radio-outline radio-outline-2x radio-danger">
                                    <input type="radio" name="refund_type" value="all" checked="checked">
                                    <span></span>Полный</label>
                                <label class="radio radio-outline radio-outline-2x radio-danger">
                                    <input type="radio" name="refund_type" value="partials" >
                                    <span></span>Частичный</label>
                            </div>
                            <span class="form-text text-muted small">Отменить будет невозможно!</span>
                        </div>
                    </div>
                    <div class="float-left mt-3 ml-4"><button type="submit" class="form-group btn btn-sm btn-success waves-effect waves-light"><i class="la la-money-bill-wave"></i>
                            Возврат</button></div>

                </div>
            </form>
        </div>
    </div>

@endsection



