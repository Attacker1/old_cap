@extends('admin.layout-metronic.default')

@section('title'){{ $title }} {{ @$data->order_id }}@endsection

@section('actions')
    <div class="float-right">
        <a href="{{ route('notes.list') }}"><i class="fas fa-chevron-circle-left text-danger"></i></a>
    </div>
@endsection

@section('maincol') col-md-12 @endsection
@section('navbar') navbar-hidden @endsection
@section('navbar2') d-none @endsection
@section('title_gray')<label class="section-label p-2">Вещи из подборки | ID подборки: {{ @$data->id }}</label> @endsection

@section('content')

    @if(isset($data->leads->clients->uuid))
    <div class="card card-custom gutter-b">
        <div class="card-body">
            <div class="symbol symbol-40 symbol-light-primary align-middle float-left">
																		<span class="symbol-label">
																			<span class="svg-icon svg-icon-lg svg-icon-primary">
																				<!--begin::Svg Icon | path:/metronic/theme/html/demo1/dist/assets/media/svg/icons/Communication/Group.svg-->
																				<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
																					<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
																						<polygon points="0 0 24 0 24 24 0 24"></polygon>
																						<path d="M18,14 C16.3431458,14 15,12.6568542 15,11 C15,9.34314575 16.3431458,8 18,8 C19.6568542,8 21,9.34314575 21,11 C21,12.6568542 19.6568542,14 18,14 Z M9,11 C6.790861,11 5,9.209139 5,7 C5,4.790861 6.790861,3 9,3 C11.209139,3 13,4.790861 13,7 C13,9.209139 11.209139,11 9,11 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"></path>
																						<path d="M17.6011961,15.0006174 C21.0077043,15.0378534 23.7891749,16.7601418 23.9984937,20.4 C24.0069246,20.5466056 23.9984937,21 23.4559499,21 L19.6,21 C19.6,18.7490654 18.8562935,16.6718327 17.6011961,15.0006174 Z M0.00065168429,20.1992055 C0.388258525,15.4265159 4.26191235,13 8.98334134,13 C13.7712164,13 17.7048837,15.2931929 17.9979143,20.2 C18.0095879,20.3954741 17.9979143,21 17.2466999,21 C13.541124,21 8.03472472,21 0.727502227,21 C0.476712155,21 -0.0204617505,20.45918 0.00065168429,20.1992055 Z" fill="#000000" fill-rule="nonzero"></path>
																					</g>
																				</svg>
                                                                                <!--end::Svg Icon-->
																			</span>
																		</span>
            </div>
            <div class=" ml-3 float-left"><b>Клиент:</b><br><a href="{{ @route('clients.show',@$data->leads->clients->uuid) }}" >{{ @$data->leads->clients->name }} </a></div>
            <div class="ml-5 float-left symbol symbol-40 symbol-light-warning align-middle">
																		<span class="symbol-label">
																			<span class="svg-icon svg-icon-lg svg-icon-warning">
																				<!--begin::Svg Icon | path:/metronic/theme/html/demo1/dist/assets/media/svg/icons/Home/Library.svg-->
																				<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
																					<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
																						<rect x="0" y="0" width="24" height="24"></rect>
																						<path d="M5,3 L6,3 C6.55228475,3 7,3.44771525 7,4 L7,20 C7,20.5522847 6.55228475,21 6,21 L5,21 C4.44771525,21 4,20.5522847 4,20 L4,4 C4,3.44771525 4.44771525,3 5,3 Z M10,3 L11,3 C11.5522847,3 12,3.44771525 12,4 L12,20 C12,20.5522847 11.5522847,21 11,21 L10,21 C9.44771525,21 9,20.5522847 9,20 L9,4 C9,3.44771525 9.44771525,3 10,3 Z" fill="#000000"></path>
																						<rect fill="#000000" opacity="0.3" transform="translate(17.825568, 11.945519) rotate(-19.000000) translate(-17.825568, -11.945519)" x="16.3255682" y="2.94551858" width="3" height="18" rx="1"></rect>
																					</g>
																				</svg>
                                                                                <!--end::Svg Icon-->
																			</span>
																		</span>
            </div>
            <div class=" ml-3 float-left"><b>Сделка:</b><br><a href="{{ @route('leads.edit',$data->leads->uuid) }}" target="_blank">{{ @$data->leads->amo_lead_id }} </a></div>

            <div class="float-left ml-5 symbol symbol-40 symbol-light-success mr-5">
														<span class="symbol-label">
															<span class="svg-icon svg-icon-lg svg-icon-success">
																<!--begin::Svg Icon | path:/metronic/theme/html/demo1/dist/assets/media/svg/icons/Communication/Group-chat.svg-->
																<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
																	<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
																		<rect x="0" y="0" width="24" height="24"></rect>
																		<path d="M16,15.6315789 L16,12 C16,10.3431458 14.6568542,9 13,9 L6.16183229,9 L6.16183229,5.52631579 C6.16183229,4.13107011 7.29290239,3 8.68814808,3 L20.4776218,3 C21.8728674,3 23.0039375,4.13107011 23.0039375,5.52631579 L23.0039375,13.1052632 L23.0206157,17.786793 C23.0215995,18.0629336 22.7985408,18.2875874 22.5224001,18.2885711 C22.3891754,18.2890457 22.2612702,18.2363324 22.1670655,18.1421277 L19.6565168,15.6315789 L16,15.6315789 Z" fill="#000000"></path>
																		<path d="M1.98505595,18 L1.98505595,13 C1.98505595,11.8954305 2.88048645,11 3.98505595,11 L11.9850559,11 C13.0896254,11 13.9850559,11.8954305 13.9850559,13 L13.9850559,18 C13.9850559,19.1045695 13.0896254,20 11.9850559,20 L4.10078614,20 L2.85693427,21.1905292 C2.65744295,21.3814685 2.34093638,21.3745358 2.14999706,21.1750444 C2.06092565,21.0819836 2.01120804,20.958136 2.01120804,20.8293182 L2.01120804,18.32426 C1.99400175,18.2187196 1.98505595,18.1104045 1.98505595,18 Z M6.5,14 C6.22385763,14 6,14.2238576 6,14.5 C6,14.7761424 6.22385763,15 6.5,15 L11.5,15 C11.7761424,15 12,14.7761424 12,14.5 C12,14.2238576 11.7761424,14 11.5,14 L6.5,14 Z M9.5,16 C9.22385763,16 9,16.2238576 9,16.5 C9,16.7761424 9.22385763,17 9.5,17 L11.5,17 C11.7761424,17 12,16.7761424 12,16.5 C12,16.2238576 11.7761424,16 11.5,16 L9.5,16 Z" fill="#000000" opacity="0.3"></path>
																	</g>
																</svg>
                                                                <!--end::Svg Icon-->
															</span>
														</span>
            </div>

            <div class=" ml-3 float-left"><b>Статус:</b><br>{{ @$data->leads->states->name}}</div>

        </div>
    </div>
    @endif
    <div class="card card-custom gutter-b">
        <div class="card-body">
    @if ($errors->any())
        <ul class="alert alert-danger">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

        <div class="row">
{{-- Товары в записке стилиста  --}}
        <div class="col-sm-9">
            <section id="note_products">
            @if($data->products()->count())
                    <div class="row card col-sm-12 ">
                        <div class="col-sm-12 mt-2" id="sortable" data-sortable-id="0" aria-dropeffect="move">
                            <div class="row">
                            @foreach(@$data->products()->orderBy('order', 'asc')->get() as $item)
                                <div class="col-sm-6 row" id="order-{{ $item->id }}" data-id="{{ $item->id }}" data-item-sortable-id="0" role="option" aria-grabbed="true" style="">
                                    @if($img = @$item->attachments()->where('main',1)->first()->url)
                                    <div class="col-sm-5">
                                        <div class="notes_thumb"><img src="{{ @$img }}"  width="64"></div>
                                        <span class="price" > <span class="label label-inline label-pill label-success label-rounded ml-1 mt-2" text-capitalized=""> {{ number_format(round($item->price), 0, '.', ' ') }} р. </span></span>
                                    </div>
                                    @else
                                        <div class="col-sm-5"><a href="#" class="img-thumbnail"><i class="fa fa-picture-o" aria-hidden="true"></i></a></div>
                                    @endif
                                        <div class="col-sm-5 d-inline">
                                            <div class="notes_name cursor-move mb-1"><h5 class="card-label">{{ @$item->name }}</h5></div>
                                            <div class="sku text-muted mb-1"><b class="text-danger">Артикул:</b> {{ @$item->sku }} </div>
                                            <div class="mb-1"><b class="text-primary">Бренд:</b> {{ @$item->brands()->first()->name }}</div>
                                            <div class=""><b class="text-info">Размер:</b> {{ @$item->size ?? '—' }}</div>
                                        </div>
                                        <div class="col-sm-2 cell" >
                                            <div class="p-1"><i class="fa fa-arrows cursor-move product-sort-icon" aria-hidden="true"></i></div>
                                            <div class="p-1"><a href="{{ route('notes.remove',[$data->id,$item->id]) }}" class="product_to_note__ "  data-product-id="{{ @$item->id }}" title="Удалить {{ @$item->name }}"><i class="fa far fa-trash-alt text-danger"></i></a></div>
                                        </div>
                                    <div class=" col-sm-12"> <hr/></div>
                                </div>
                            @endforeach
                        </div>
                        </div>
                    </div>


            </section>
        </div>
            <div class="col-3 invoice-actions mt-md-0">
                <div class="alert alert-custom alert-outline-2x alert-outline-success fade show mb-5" role="alert">
                    <div class="alert-icon d-none d-xl-block">
                        <i class="flaticon-warning"></i>
                    </div>
                    <div class="alert-text">Проверьте перед печатью, что текст поместился на странице</div>

                </div>

                <a class="btn btn-outline-primary btn-block mb-75 waves-effect"
                   href="{{ route('notes.product.print',$data->id) }}" target="_blank">
                    <i class="fa fa-print" aria-hidden="true"></i> Распечатать
                </a>

                <a href="{{ route('notes.close',$data->id) }}"
                   class="btn btn-outline-success btn-block waves-effect waves-float waves-light">
                    <i class="fa fa-floppy-o" aria-hidden="true"></i> Закрыть записку
                </a>

                <a href="{{ route('notes.list') }}"
                   class="btn btn-outline-danger btn-block waves-effect waves-float waves-light">
                    <i class="fa fa-close" aria-hidden="true"></i> Выход
                </a>
            </div>
        </div>

{{-- END Товары в записке стилиста  --}}
    </div>
    </div>
    </div>
    </div>

{{-- Фото loaded from url  --}}
    <div class="col-3">
        @include('admin.notes._unsort')
    </div>

{{-- Блок Закладки  --}}

    <div class="card col-sm-12 note-tabs">
        <div class="card-body">
            <ul class="nav nav-tabs mb-3" role="tablist">
                <li class="nav-item">
                    <a class="nav-link " id="letterIcon-tab" data-toggle="tab" href="#letterIcon" role="tab" aria-selected="false">
                        <i class="fas fa-envelope-open-text mr-3"></i> Сопроводительные тексты</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="advice-tab" data-toggle="tab" href="#adviceIcon"  role="tab" aria-selected="false">
                        <i class="fa fa-list-ol mr-3"></i> Сочетания товаров</a>
                </li>

                <li class="nav-item active ">
                    <a class="nav-link active " id="custom-advice-tab" data-toggle="tab" href="#customAdviceIcon" role="tab" aria-selected="true">
                        <i class="fa fa-list-ol mr-3" aria-hidden="true"></i> Произвольные сочетания</a>
                </li>

                <li class="nav-item ">
                    <a class="nav-link" id="priceIcon-tab" data-toggle="tab" href="#priceIcon"  role="tab" aria-selected="false">
                        <i class="fa fa-credit-card mr-3" aria-hidden="true"></i> Стоимость</a>
                </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane" id="letterIcon" aria-labelledby="letterIcon-tab" role="tabpanel">
                    @include('admin.notes.tabs.letter')
                </div>
                <div class="tab-pane" id="adviceIcon" aria-labelledby="advice-tab" role="tabpanel">
                    @include('admin.notes.tabs.product_advice')
                </div>
                <div class="tab-pane active" id="customAdviceIcon" aria-labelledby="custom-advice-tab" role="tabpanel">
                    @include('admin.notes.tabs.custom_advice')
                </div>
                <div class="tab-pane" id="priceIcon" aria-labelledby="priceIcon-tab" role="tabpanel">
                    @include('admin.notes.tabs.price')
                </div>
            </div>
        </div>
    </div>
    </div>
    </div>
    </div>


    @else
        <div class="alert alert-custom alert-outline-danger fade show mb-5" role="alert">
            <div class="alert-icon">
                <i class="flaticon-warning"></i>
            </div>
            <div class="alert-text">В записке еще нет товаров</div>
            <div class="alert-close">
                    <a href="{{ route('notes.list') }}"><i class="fas fa-window-close text-danger"></i></a>
            </div>
        </div>
    @endif

    @include('admin.notes.tabs.url-images-panel')

@endsection
@include('admin.notes.scripts')
