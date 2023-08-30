{{--  Подоборка из продукции --}}
<div class="row">

    <div class="col-sm-6">
        <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">

            <div class="col-sm-12 panel panel-default">
                <div class="panel-heading" role="tab" id="headingOne">
                    <h4 class="panel-title">
                        <a role="button"  data-id="product-advice-left-1"  data-toggle="collapse" data-parent="#accordion" href="#collapseFour" aria-expanded="true" aria-controls="collapseOne">
                            <i class="ficon feather icon-chevron-down"></i>
                            <label class="section-label p-2">Сочетание 1</label></a>
                        <a aria-hidden="true" class="delete-product-advice-left" data-id="product-advice-left-1" aria-label="Удалить">×</a>

                    </h4>
                </div>
                <div id="collapseFour" class="panel-collapse  collapse show" role="tabpanel" aria-labelledby="headingOne">
                    <div id="product-advice-left-1" class="panel-body product-advice-div">
                        @if(!empty($advice) && isset($advice->value[0]))
                            @foreach(@$advice->value[0] as $value)
                                @if($attach = @\Bnb\Laravel\Attachments\Attachment::where('key',$value)->first())
                                    <div class="display-inline-block m-1 mb-1" id="{{ @$attach->key }}">
                                        <img src="{{ @$attach->url }}" class=" d-inline img-thumbnail" width="64">
                                    </div>
                                @endif
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>


            <div class="col-sm-12 panel panel-default">
                <div class="panel-heading" role="tab" id="headingOne">
                    <h4 class="panel-title">
                        <a role="button"  data-id="product-advice-left-2"  data-toggle="collapse" data-parent="#accordion" href="#collapseFive" aria-expanded="true" aria-controls="collapseOne">
                            <i class="ficon feather icon-chevron-down"></i>
                            <label class="section-label p-2">Сочетание 2</label></a>
                        <a aria-hidden="true" class="delete-product-advice-left" data-id="product-advice-left-2" aria-label="Удалить">×</a>

                    </h4>
                </div>
                <div id="collapseFive" class="panel-collapse  collapse show" role="tabpanel" aria-labelledby="headingOne">
                    <div id="product-advice-left-2" class="panel-body product-advice-div">
                        @if(!empty($advice) && isset($advice->value[1]))
                            @foreach(@$advice->value[1] as $value)
                                @if($attach = @\Bnb\Laravel\Attachments\Attachment::where('key',$value)->first())
                                    <div class="display-inline-block m-1 mb-1" id="{{ @$attach->key }}">
                                        <img src="{{ @$attach->url }}" class=" d-inline img-thumbnail" width="64">
                                    </div>
                                @endif
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-sm-12 panel panel-default">
                <div class="panel-heading" role="tab" id="headingOne">
                    <h4 class="panel-title">
                        <a role="button"  data-id="product-advice-left-3"  data-toggle="collapse" data-parent="#accordion" href="#collapseSix" aria-expanded="true" aria-controls="collapseSix">
                            <i class="ficon feather icon-chevron-down"></i>
                            <label class="section-label p-2">Сочетание 3</label></a>
                        <a aria-hidden="true" class="delete-product-advice-left" data-id="product-advice-left-3" aria-label="Удалить">×</a>

                    </h4>
                </div>
                <div id="collapseSix" class="panel-collapse  collapse show" role="tabpanel" aria-labelledby="headingOne">
                    <div id="product-advice-left-3" class="panel-body product-advice-div">
                        @if(!empty($advice) && isset($advice->value[2]))
                            @foreach(@$advice->value[2] as $value)
                                @if($attach = @\Bnb\Laravel\Attachments\Attachment::where('key',$value)->first())
                                    <div class="display-inline-block m-1 mb-1" id="{{ @$attach->key }}">
                                        <img src="{{ @$attach->url }}" class=" d-inline img-thumbnail" width="64">
                                    </div>
                                @endif
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>


    <div class="col-sm-5">
        <div class="row">
            <div class="col-sm-12">
            <label class="section-label p-2">Вещи из подборки</label>
            <div id="product-advice-right" class="">
                @if(!empty($count))
                    @foreach(@$data->products()->orderBy('order', 'asc')->get() as $item)
                        @if($attach = @$item->attachments()->where('main',1)->first())
                            <div class="display-inline-block m-1 mb-1" id="{{ @$attach->key }}">
                                <img src="{{ @$attach->url }}" class="cell d-inline img-thumbnail" width="64" data-product-id="{{ $item->id }}"  data-name="{{ $item->name }}">
                            </div>
                        @else
                            {{--  Если нет фото --}}
                        @endif

                    @endforeach
                @endif
            </div>
            </div>

            {{--  Вещи из интернет --}}
            <div class="col-sm-12 border-bottom form-group">
                <label class="section-label p-1"><b>Вещи из интернет</b> / Двойной клик по картинке включает ресайз /</label>
                <div id="url-source" class="advice-source">
                    @if(!empty(@$data->attachments()->get()))
                        @foreach(@$data->attachments()->get() as $item)
                            <div class="display-inline-block m-1 mb-1 resizeable" id='{{ @$item->key }}'>
                                <img src="{{ $item->url }}" class="cell d-inline img-thumbnail" width="64">
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
            <div class="customizer-styling-direction px-2">
                <div class="d-flex">
                    <div class="input-group mb-1 col-sm-12">
                        <form name="form" method="post" action="{{ route('notes.attach',$data->id) }}">
                            {{ csrf_field() }}
                            <div class="input-group ">
                                <input type="text" name="img_url" class="form-control" placeholder="Фото из интернет" aria-label="img_url_label" autocomplete="off">
                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-sm btn-outline-primary waves-effect">Загрузить</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{--  Вещи из интернет END--}}

        </div>
    </div>
</div>

{{-- END Подоборка из продукции  --}}

