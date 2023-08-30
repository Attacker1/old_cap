<div class="col-sm-12 paper-a4">
    <div class="print-amo-id">{{ @$data->order_id }}</div>
    <div class="print-logo-capsula">
        <img src="/app-assets/images/capsula/logo-horisontal.svg" class="pring-logo-capsula" width="320"  >
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
                    Образы с одеждой из капсулы
                </div>
                <div class="col-sm-12 list-1-product-second-container ">

                    @if(isset($advice[0]))
                        <div class="list-1-product-second-box ">
                            <div class="list-1-product-secondary-name"><span> 1.
                               @foreach($advice[0] as $k=>$v)
                                        {{ @$v['name'] }}@if(!$loop->last && !empty($advice[0][$k+1]['name'])), @endif
                                @endforeach
                                </span>
                            </div>

                            @foreach($advice[0] as $k=>$v)
                                <div class="list-1-product-second-image">
                                    @if($v)
                                        <img  src="{{ @$v['url'] }}" class="cell">
                                    @endif
                                </div>
                            @endforeach

                        </div>
                    @endif

                        @if(isset($advice[1]))
                            <div class="list-1-product-second-box ">
                                <div class="list-1-product-secondary-name"><span> 2.
                               @foreach($advice[1] as $k=>$v)
                                            {{ @$v['name'] }}@if(!$loop->last)@if(!empty($advice[1][$k+1]['name'])), @endif @endif
                                        @endforeach
                                </span>
                                </div>

                                @foreach($advice[1] as $k=>$v)
                                    <div class="list-1-product-second-image">
                                        @if($v)
                                            <img  src="{{ @$v['url'] }}" class="cell">
                                        @endif
                                    </div>
                                @endforeach

                            </div>
                        @endif

                        @if(isset($advice[2]))
                            <div class="list-1-product-second-box ">
                                <div class="list-1-product-secondary-name"><span> 3.
                               @foreach($advice[2] as $k=>$v)
                                    {{ @$v['name'] }}@if(!$loop->last && !empty($advice[2][$k+1]['name'])), @endif
                                @endforeach
                                </span>
                                </div>

                                @foreach($advice[2] as $k=>$v)
                                    <div class="list-1-product-second-image">
                                        @if($v)
                                            <img  src="{{ @$v['url'] }}" class="cell">
                                        @endif
                                    </div>
                                @endforeach

                            </div>
                        @endif

                </div>
            </div>
        </div>

        <div class="note-print-form-text ml-1 mr-2">
            {!!  @$data->content !!}
        </div>
    </div>
</div>
