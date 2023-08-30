<div class="col-sm-12 paper-a4">
    <div class="print-amo-id">{{ @$data->order_id }}</div>
    <div class="print-logo-capsula">
        <img src="/app-assets/images/capsula/logo-horisontal.svg" class="pring-logo-capsula" width="320" >
    </div>

    <div class="row">
        <div class="col-sm-12 list-1-heading">
            Вещи из подборки
        </div>

        <div class="col-sm-12">
            <div class="row">
                <div class="col-sm-12 list-1-product-first-container">
                    @if(!empty($count))
                        @foreach(@$data->products()->orderBy('order', 'asc')->get() as $item)
                            @if($attach = @$item->attachments()->where('main',1)->first())
                                <div class="list-1-product-box">
                                    <div class="list-1-product-image"  >
                                        <img src="{{ @$attach->url }}">
                                    </div>
                                    <div>
                                        <div class="list-1-product-name">
                                            {{ @$item->name }}
                                        </div>
                                        <div class="list-1-product-brand">
                                            {{ @$brands[$item->brand_id] }}
                                        </div>
                                        <div class="list-1-product-price">
                                            {{ number_format(round(@$item->price), 0, '.', ' ')}} руб.
                                        </div>
                                    </div>
                                </div>
                            @else
                                {{--  Если нет фото --}}
                            @endif

                        @endforeach
                    @endif
                </div>
            </div>
        </div>
        <div class="col-sm-12 clearfix"></div>

        <div class="col-sm-12">
            <div class="row">
                <div class="col-sm-12 list-1-second-heading">
                    Дополнительные образы с другими вещами
                </div>
                <div class="col-sm-12 list-2-product-second-container ">

                    @if(isset($customAdvice->value[0]))
                        <div class="list-2-product-second-box ">
                            @foreach($customAdvice->value[0] as $k=>$v)
                                @if($v)
                                <div class="list-2-product-second-image">
                                    <img  src="{{ @\Bnb\Laravel\Attachments\Attachment::where('key',$v)->first()->url }}" width="150">
                                </div>
                                @endif
                            @endforeach
                        </div>
                    @endif

                    @if(isset($customAdvice->value[1]))
                        <div class="list-2-product-second-box ">
                            @foreach($customAdvice->value[1] as $k=>$v)
                                @if($v)
                                <div class="list-2-product-second-image">
                                    <img  src="{{ @\Bnb\Laravel\Attachments\Attachment::where('key',$v)->first()->url }}"  width="150">
                                </div>
                                @endif
                            @endforeach
                        </div>
                    @endif

                    @if(isset($customAdvice->value[2]))
                        <div class="list-2-product-second-box">
                            @foreach($customAdvice->value[2] as $k=>$v)
                                @if($v)
                                <div class="list-2-product-second-image">
                                    <img class="absolute" src="{{ @\Bnb\Laravel\Attachments\Attachment::where('key',$v)->first()->url }}">
                                </div>
                                @endif
                            @endforeach
                        </div>
                    @endif

                </div>
            </div>
        </div>

        <div class="note-print-form-text-2 ml-1 mr-2">
            {!!  @$data->content_advice !!}
        </div>
    </div>
</div>
