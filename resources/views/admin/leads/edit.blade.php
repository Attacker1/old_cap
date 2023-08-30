@extends('admin.layout-metronic.default')

@section('title'){{ $title }}@endsection

@section('breadcrumb'){{ $title }}@endsection

@section('actions')
    <div class="float-right">
        @if(!empty($data->amo_lead_id))
            <a href="https://thecapsula.amocrm.ru/leads/detail/{{ @$data->amo_lead_id }}" target="_blank"><i
                        class="fas fa-external-link-alt text-primary mr-3"></i></a>

            <a href="{{ route('lead.corrections.create',["lead_uuid"=> $data->uuid]) }}" class="ml-5 mr-6" title="Создать коррекцию"><i class="fas fa-credit-card text-success"></i></a>
        @endif
        <a href="{{ route('leads.list') }}"><i class="fas fa-chevron-circle-left text-danger"></i></a>
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

            <form name="form" method="post" action="{{ route('leads.edit',$data->uuid) }}" id="form">
                {{ csrf_field() }}

                <div class="row">

                    @if(auth()->guard('admin')->user()->hasPermission('edit-lead-summ'))

                        <div class="col-sm-12">
                            @if(empty($data->summ) || empty($data->clients->name) || empty($data->clients->second_name) || empty($data->clients->phone) || empty($data->clients->email))
                                <div class="col-12 mt-2 text-danger form-group small">
                                    <span class="text-dark">Для формирования ссылки на оплату нет след. данных:</span>
                                    <b>
                                        @if(empty($data->summ)) Cуммы @endif
                                        @if(empty($data->clients->name)) Имени @endif
                                        @if(empty($data->clients->second_name)) Фамилии  @endif
                                        @if(empty($data->clients->phone)) Телефона  @endif
                                        @if(empty($data->clients->email)) E-mail  @endif
                                    </b></div>
                            @endif
                        </div>
                    @endif


                    <div class="input-group mb-6 col-sm-4">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="parent_id_title">Стилист:</span>
                        </div>
                        <select name="stylist_id" class="form-control " id="stylist_id">
                            @if(!$manage)
                                <option value="" @if($data->stylist_id == '') selected @else disabled @endif>Не
                                    назначен
                                </option>
                            @else
                                <option value="">Не назначен</option>
                            @endif
                            @foreach($stylists as $k=>$v)
                                <option value="{{ $k }}"
                                        @if(!$manage)
                                        @if($data->stylist_id == $k) selected @else disabled @endif
                                        @else
                                        @if($data->stylist_id == $k) selected @endif
                                        @endif

                                >{{ @$v }}
                                </option>

                            @endforeach
                        </select>
                        {{--  todo:uretral Предидущая реализация! Если менеджеры отреагируют нормально на новую, убрать эту  / решена проблема возврата нулевого знвчения - обе рабочие, разное отображение/      --}}
                        {{--
                                                @if($manage == true)
                                                    <select name="stylist_id" class="form-control " id="stylist_id">
                                                        <option value="">Не назначен</option>
                                                        @if(!empty($stylists))
                                                            @foreach($stylists as $k=>$v)
                                                                <option value="{{ $k }}"
                                                                        @if($data->stylist_id == $k) selected @endif>{{ @$v }}</option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                @else
                                                    <input type="text"
                                                           value="{{ auth()->guard('admin')->user()->name }}"
                                                           class="form-control disabled " autocomplete="off" aria-label=""
                                                           aria-describedby="name_label" disabled>
                                                    <input type="hidden" id="stylist_id" name="stylist_id" value="{{@$data->stylist_id}}"
                                                           placeholder=""/>
                                                @endif
                        --}}
                    </div>


                    <div class="input-group mb-6 col-sm-4">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="parent_id_title">Статус:</span>
                        </div>
                        <select name="state_id" class="form-control " id="state_id">
                            <option value="">{{ $data->states->name ?? 'Не указан' }}</option>
                            @if(!empty($states))
                                @foreach($states as $k=>$v)
                                    <option value="{{ $k }}"
                                            @if($data->state_id == $k) selected @endif>{{ @$v }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>


                    <div class="input-group mb-6 col-sm-4">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="name_label">Amo ID</span>
                        </div>
                        <input type="text" name="amo_lead_id" id="amo_lead_id" value="{{ @$data->amo_lead_id }}"
                               class="form-control " autocomplete="off" aria-label="" aria-describedby="name_label"
                               @if($manage != true) disabled @endif>
                    </div>

                    <div class="input-group col-sm-4 mb-6" style="z-index: 9999">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="name_label">Дедлайн</span>
                        </div>
                        @php
                            if (empty($data->deadline_at))
                                $deadline_at = now()->addDay(1)->format("Y-m-d H:i");
                            else
                                $deadline_at = \Carbon\Carbon::parse($data->deadline_at)->format("Y-m-d H:i");
                        @endphp

                        <input data-date-format="yyyy-mm-dd" name="deadline_at" placeholder="Дедлайн"
                               class="form-group form-control" id="deadline_at" value="{{ @$deadline_at }}"
                               autocomplete="off" @if(!auth()->guard('admin')->user()->hasPermission('edit-lead-deadline')) disabled @endif>
                        @if($manage == true || auth()->guard('admin')->user()->hasPermission('edit-lead-deadline'))
                            <div class="input-group-append">
                                <button type="button" class="btn btn-sm btn-outline-primary waves-effect"
                                        id="reset_date" title="Дедлайн"><i class="fa fa-times" aria-hidden="true"></i>
                                </button>
                            </div>
                        @endif
                    </div>

                    @if(auth()->guard('admin')->user()->hasPermission('edit-lead-summ'))
                        <div class="input-group mb-6 col-sm-4 ">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="parent_id_title">Сумма:</span>
                            </div>
                            <input name="summ" type="number" class="form-control" min="0" value="{{ @$data->summ }}"
                                   title="Сумма для оплаты сделки">

                            @if(!empty($data->summ) && !empty($data->clients->name) && !empty($data->clients->second_name) && !empty($data->clients->phone) && !empty($data->clients->email))
                                <div class="input-group-append">
                                    <span class="input-group-text line-height-0 py-0">
                                        <a href="{{ route('leads.pay.link',$data->uuid) }}" title="Ссылка на оплату"
                                           target="_blank"><i class="fas fa-link text-success"></i></a>
                                    </span>
                                </div>
                            @endif

                        </div>

                            <div class="input-group mb-6 col-sm-4 ">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="parent_id_title">total:</span>
                                </div>
                                <input name="total" type="number" step="0.01" class="form-control" min="0" value="{{ @$data->total }}"
                                       title="Товары минус скидка">

                            </div>

                            <div class="input-group mb-6 col-sm-4 ">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="parent_id_title">discount:</span>
                                </div>
                                <input name="discount" type="number" step="0.01" class="form-control" min="0" value="{{ @$data->discount }}"
                                       title="Скидка по товарам">

                            </div>

                    @endif


                    <div class="input-group mb-6 col-sm-4">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="parent_id_title">Купон</span>
                        </div>
                        <input type="text" name="coupon" id="coupon"
                               value="{{$coupon}}"
                               placeholder="Код купона"
                               class="form-control " autocomplete="off" aria-label="" aria-describedby="name_label"
                               @if($manage != true) disabled @endif >
                    </div>

                    <div class="input-group mb-6 col-sm-4">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="parent_id_title">Город</span>
                        </div>
                        <input type="text" name="city_delivery" id="city_delivery"
                               value="{{ @$data->data['city_delivery'] ?? @$city_delivery['value']}}"
                               placeholder="Город"
                               class="form-control " autocomplete="off" aria-label="" aria-describedby="name_label"
                               class="form-control " autocomplete="off" aria-label="" aria-describedby="name_label"
                               @if($manage != true) disabled @endif>
                    </div>

                    <div class="input-group mb-6 col-sm-8">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="parent_id_title">Адрес</span>
                        </div>
                        <input type="text" name="address_delivery" id="address_delivery"
                               value="{{ @$address_delivery['value'] }}"
                               placeholder="{{ @$address_delivery['placeholder']}}"
                               class="form-control " autocomplete="off" aria-label="" aria-describedby="name_label"
                               @if($manage != true) disabled @endif>
                        <div class="col-12">{{ $new_address ?? ''}}</div>
                    </div>

                    <div class="input-group mb-6 col-sm-8">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="parent_id_title">Адрес возврата</span>
                        </div>
                        <input type="text" name="address_back" id="address_back"
                               value="{{ @$address_back }}"
                               class="form-control " autocomplete="off" aria-label="" aria-describedby="name_label"
                               @if($manage != true) disabled @endif>
                    </div>

                </div>
                <div class="row">

                    <div class="input-group col-sm-4 mb-6" style="z-index: 9999">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="delivery_label">Дата доставки</span>
                        </div>
                        <input data-date-format="yyyy-mm-dd" @if($manage == true) name="delivery_at"
                               placeholder="Дата доставки" id="delivery_at" @endif class="form-group form-control"
                               value="{{ !empty($data->delivery_at) ? @\Carbon\Carbon::parse($data->delivery_at)->format('Y-m-d') : '' }}"
                               autocomplete="off" @if($manage != true) disabled @endif>
                        @if($manage == true)
                            <div class="input-group-append">
                                <button type="button" class="btn btn-sm btn-outline-primary waves-effect"
                                        id="reset_date" title="Дата доставки"><i class="fa fa-times"
                                                                                 aria-hidden="true"></i></button>
                            </div>
                        @endif
                    </div>


                    <div class="input-group mb-6 col-sm-4">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="parent_id_title">Интервал доставки:</span>
                        </div>
                        <select name="time_delivery" class="form-control " id="time_delivery">
                            <option value="">{{ @$time_delivery['value'] ?? 'Не указано' }}</option>
                            @if(!empty($time_delivery['options']))
                                @foreach($time_delivery['options'] as $option)
                                    <option value="{{ $option['text'] }}"
                                            @if(@$time_delivery['value'] == $option['text']) selected @endif>{{
                                        $option['text'] }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>


                    <div class="input-group mb-6 col-sm-4 @if(empty($data->substate_id)) d-none @endif sub_states">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="parent_id_title">Подстатус:</span>
                        </div>
                        <select name="sub_state_id" class="form-control " id="sub_state_id">
                        </select>
                    </div>


                    <div class="input-group col-sm-4 mb-6" style="z-index: 9999">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="delivery_label">Дата возврата</span>
                        </div>
                        <input data-date-format="yyyy-mm-dd" @if($manage == true) name="date_back"
                               placeholder="Дата возврата" id="date_back" @endif class="form-group form-control"
                               value="{{ !empty($data->data['date_back']) ? @\Carbon\Carbon::parse($data->data['date_back'])->format('Y-m-d') : '' }}"
                               autocomplete="off" @if($manage != true) disabled @endif>
                        @if($manage == true)
                            <div class="input-group-append">
                                <button type="button" class="btn btn-sm btn-outline-primary waves-effect"
                                        id="reset_date" title="Дата возврата"><i class="fa fa-times"
                                                                                 aria-hidden="true"></i></button>
                            </div>
                        @endif
                    </div>


                    <div class="input-group mb-6 col-sm-4">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="parent_id_title">Интервал возврата:</span>
                        </div>
                        <select name="time_back" class="form-control " id="time_back">
                            <option value="">{{ @$time_back['value'] ?? 'Не указано' }}</option>
                            @if(!empty($time_back['options']))
                                @foreach($time_back['options'] as $option)
                                    <option value="{{ $option['text'] }}"
                                            @if(@$time_back['value'] == $option['text']) selected @endif>{{
                                        $option['text'] }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <div class="input-group mb-6 col-sm-4 btn-group btn-group-toggle" data-toggle="buttons">
                        <label class="btn btn-secondary @if(in_array($data->tag,['BOXBERRY','boxberry'])) active focus @endif">
                            <input type="radio" name="tag" id="tag_boxberry" autocomplete="off"
                                   @if(in_array($data->tag,['BOXBERRY','boxberry'])) checked @endif value="BOXBERRY">BOXBERRY
                        </label>
                        <label class="btn btn-secondary @if(in_array($data->tag,['logsys','logsis'])) active focus @else @endif">
                            <input type="radio" name="tag" id="tag_logsys" autocomplete="off"
                                   @if(in_array($data->tag,['logsys','logsis'])) checked @endif value="logsys">logsys
                        </label>
                        <label class="btn btn-secondary @if(empty($data->tag)) active focus @endif">
                            <input type="radio" id="tag_undefined" autocomplete="off"
                                   @if(empty($data->tag)) checked @endif>Не выбран
                        </label>
                    </div>

                    {{--  Заполнено клиентом Пункт выдачи заказа --}}
                    @if(@$data->delivery()->first())

                        <div class="input-group mb-6 col-sm-4">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="delivery_point_id_title">ПВЗ</span>
                            </div>
                            <input type="text" name="delivery_point_id" id="delivery_point_id"
                                   title="{{ @$data->delivery()->first()->delivery_point_id  }}"
                                   value="{{ @$data->delivery()->first()->delivery_point_id  }}"
                                   class="form-control " autocomplete="off" aria-label=""
                                   aria-describedby="delivery_point_id_title"
                                   disabled>
                        </div>

                        <div class="input-group mb-6 col-sm-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="delivery_address_title">Трек №</span>
                            </div>
                            <input type="text" name="delivery_id" id="delivery_track_id"
                                   value="{{ @$data->delivery()->first()->delivery_id }}"
                                   title="{{ @$data->delivery()->first()->delivery_id }}"
                                   class="form-control " autocomplete="off" aria-label=""
                                   aria-describedby="delivery_id_title" disabled>
                        </div>


                        <div class="input-group mb-6 col-sm-5">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="delivery_address_title">ПВЗ Адрес</span>
                            </div>
                            <input type="text" name="delivery_point_id" id="delivery_point_id"
                                   title="{{  @$data->delivery()->first()->delivery_address }}"
                                   value="{{ @$data->delivery()->first()->delivery_address }}"
                                   class="form-control " autocomplete="off" aria-label=""
                                   aria-describedby="delivery_address_title" disabled>
                        </div>

                    @endif


                    <div class="input-group mb-2 col-sm-12">
                        <button type="submit" class="btn btn-primary mr-1 mb-1 waves-effect waves-light" id="btn-submit">Сохранить</button>
                    </div>


                    <div class="col-sm-12 form-group mt-2">
                        <label><i class="mr-3 far fa-comment-alt"></i><b>Доп. заметки</b> </label>

                        <p id="lead-comments-text" @if(empty($data->description))
                            style="display: none; min-width: 100%; min-height: 50px" @endif>{!! $data->description !!} </p>

                        <textarea  id="kt_maxlength_letter_1" maxlength="4000" name="description"
                                   @if(!empty($data->description)) style="display: none" @endif
                                  class="form-control bg-light" type="text" rows="6"
                                  placeholder="Дополнительная информация по сделке"></textarea>
                    </div>

                    <div class="col-sm-12 form-group">
                        <label><i class="mr-3 far fa-clock"></i><b>История изменений</b> </label>
                        <textarea class="form-control bg-light" type="text" rows="3" placeholder="История изменений">@foreach($data->revisionHistory as $history)
@if($history->fieldName() == "ID статуса")
{{ \Carbon\Carbon::parseFromLocale($history->created_at,"ru_RU")->format("d.m.Y H:i") }} {{ @$history->userResponsible()->name ?? 'Система'}}: исправил "{{ $history->fieldName() }}" с {{ @$all_states[$history->oldValue()] }} на {{ @$all_states[$history->newValue()]}}
@else
{{ \Carbon\Carbon::parseFromLocale($history->created_at,"ru_RU")->format("d.m.Y H:i") }} {{ @$history->userResponsible()->name ?? 'Система'}}: исправил "{{ $history->fieldName() }}" с {{ $history->oldValue() }} на {{ $history->newValue() }}
    @endif
@endforeach
                        </textarea>
                    </div>


                </div>
            </form>
        </div>
    </div>


    <div class="row">
        {{--        Профиль клиента--}}
        <div class="col-sm-6 " id="kt_profile_aside">
            <div class="card card-custom card-stretch">

                <div class="card-body pt-4">

                    <div class="d-flex justify-content-end">
                        <div class="dropdown dropdown-inline">

                        </div>
                    </div>
                    <div class="d-flex align-items-center">
                        <div class="symbol symbol-60 symbol-xxl-100 mr-5 align-self-start align-self-xxl-center">
                            <div class="symbol-label"><i class="fa-5x far fa-user-circle"></i></div>
                        </div>
                        <div>
                            <a href="@if(!empty($clientData->uuid)) {{ route('clients.show',@$clientData->uuid) }} @else# @endif"
                               class="font-weight-bolder font-size-h5 text-dark-75 text-hover-primary">
                                {{ @$clientData->second_name . ' ' }} {{ @$clientData->name . ' ' }} {{ @$clientData->patronymic }}
                            @if(!empty($clientData->uuid))<i
                                        class="fas fa-chevron-circle-right text-danger"></i>@endif</a>
                            <div class="text-muted small">С
                                нами: {{ @\Carbon\Carbon::parse($clientData->created_at)->format('d.m.Y H:i') }}</div>
                            <div class="mt-2">
                                <a href="#"
                                   class="btn btn-sm btn-primary font-weight-bold mr-2 py-2 px-3 px-xxl-5 my-1">{{ @$clientData->bonuses->promocode ?? '—' }}</a>
                                <a href="#"
                                   class="btn btn-sm btn-success font-weight-bold py-2 px-3 px-xxl-5 my-1">{{ @$clientData->bonuses->points ?? 0 }}
                                    баллов</a>
                            </div>
                        </div>
                    </div>
                    <div class="py-9">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="font-weight-bold mr-2">E-mail:</span>
                            <a href="#" class="text-muted text-hover-primary">{{ @$clientData->email }}</a>
                        </div>
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="font-weight-bold mr-2">Телефон:</span>
                            <span class="text-muted client-phone">{{ @$clientData->phone }}</span>
                        </div>
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="font-weight-bold mr-2">Инстаграм:</span>
                            <span class="text-muted client-phone">{{ @$clientData->socialmedia_links }}</span>
                        </div>
                        @if(!empty($clientData->uuid))
                            @if($bonus = \App\Http\Models\Common\Bonus::where('client_id',$clientData->uuid)->first())
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <span class="font-weight-bold mr-2">Промокод:</span>
                                    <span class="text-muted text-hover-primary">{{ @$bonus->promocode }}</span>
                                </div>
                            @endif
                        @endif
                        <div class="d-flex align-items-center justify-content-between">
                            <span class="font-weight-bold mr-2">Статус:</span>
                            <span class="text-muted ">{{ @$clientData->status }}</span>
                        </div>
                        <div class="mt-3 d-flex align-items-center justify-content-between">
                            <span class="font-weight-bold mr-2">Комментарии:</span>
                        </div>
                        <div class="d-flex align-items-center justify-content-between">
                            <span class="form-text text-muted">{{ @$clientData->comments }}</span>
                        </div>
                    </div>
                    <div class="navi navi-bold navi-hover navi-active navi-link-rounded">

                        @if(!empty($reAnketaUuidLink))
                            <div class="navi-item mb-2">
                                <a href="{{ $reAnketaUuidLink }}" class="navi-link py-4 active"
                                   target="_blank">
                                    <i class="far fa-eye text-primary"></i>
                                    <span class="ml-5 navi-text font-size-lg">Просмотр повторной анкеты</span>
                                </a>
                            </div>
                        @endif

                        @if($anketaUuidLink)
                            <div class="navi-item mb-2">
                                <a href="{{ $anketaUuidLink }}" class="navi-link py-4 active"
                                   target="_blank">
                                    <i class="far fa-eye text-primary"></i>
                                    <span class="ml-5 navi-text font-size-lg">Просмотр анкеты</span>
                                </a>
                            </div>
                        @endif

                        @if(!empty($feedback_id))
                            <div class="navi-item mb-2">
                                <a href="/admin/feedback/{{ @$feedback_id }}" class="navi-link py-4 active"
                                   title="Просмотр обратной связи" target="_blank">
                                    <i class="far fa-comment-alt text-primary"></i>
                                    <span class="ml-5 navi-text font-size-lg">Просмотр обратной связи</span>
                                </a>
                            </div>
                        @endif
                    </div>

                </div>

            </div>
        </div>
        {{--        Оплаты по сделке         --}}
        @if(auth()->guard('admin')->user()->hasPermission('manage-clients'))
            <div class="col-sm-6 " id="kt_profile_aside">
                <div class="card card-custom card-stretch">
                    <div class="card-header pb-1">
                        <div class="card-title">
                            <h4 class="mb-0">Оплаты по сделке</h4>
                        </div>
                    </div>
                    <div class="card-body pt-4">
                        <div class="table table-responsive">
                            <table id="payments"
                                   class="table table-separate table-head-custom table-checkable dataTable no-footer dtr-inline">
                                <thead>
                                <tr>
                                    <th>Дата</th>
                                    <th>Сумма</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        {{--        Оплаты по сделке         --}}
    </div>
@endsection
@section('scripts')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/css/bootstrap-datepicker.min.css"
          rel="stylesheet"/>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/js/bootstrap-datepicker.min.js"></script>

    <link rel="stylesheet" type="text/css" href="/m/plugins/custom/datatables/datatables.bundle.css">
    <script src="/m/plugins/custom/datatables/datatables.bundle.js"></script>

    <link rel="stylesheet" href="{{asset('app-assets/css/plugins/datetimepicker/jquery.datetimepicker.css')}}"/>
    <script src="{{asset('app-assets/js/scripts/datetimepicker/jquery.datetimepicker.full.js')}}"></script>

    <script>
        $(function () {

            $('#delivery_at,#date_back').datepicker({
                language: 'ru',
                weekStart: 1,
                daysOfWeekHighlighted: "6,0",
                autoclose: true,
                todayHighlight: true,
                startDate: "now",
                format: 'yyyy-mm-dd',
            });


            $('#deadline_at').datetimepicker({
                formatDate: 'Y-m-d',
                format: 'Y-m-d H:i',
                formatTime: 'H',
            });

            var table = $('#payments').DataTable({
                order: [[1, "desc"]],
                dom: 'rt',
                processing: true,
                serverSide: true,
                stateSave: false,
                "lengthMenu": [50, 100],
                length: 50,
                "language": {
                    "url": "/app-assets/data/dt_ru.json"
                },

                ajax: {
                    url: '{!! route('leads.payments',$data->uuid) !!}',
                    method: "POST",
                    data: function (d) {
                        d.client = $('#client').val();
                    }
                },
                columns: [
                    {data: 'created_at', name: 'created_at', class: 'text-danger  small'},
                    {data: 'amount', name: 'amount', searchable: false, class: 'small'},
                ],
            });

            // Получение подстатусов
            subStates({{ @$data->state_id }});

            $(document).on('change', '#state_id', function () {
                var state_id = $(this).val();
                subStates(state_id)
            });

            function subStates(state_id) {

                $.ajax({
                    url: '{{ route('leads.states') }}/' + state_id,
                    type: "POST",
                    success: function (response) {

                        var sub_state = $('#sub_state_id');
                        var sub_state_id = {{ @$data->substate_id ?? 0 }};
                        html = '';
                        if (response.result == true) {
                            $.each(response.data, function (index, v) {
                                if (sub_state_id == v.id)
                                    html += '<option value="' + v.id + '" selected>' + v.name + '</option>';
                                else
                                    html += '<option value="' + v.id + '">' + v.name + '</option>'
                            });
                            sub_state.html(html);
                            $('.sub_states').removeClass('d-none');
                        } else {
                            sub_state.html(html);
                            $('.sub_states').addClass('d-none');
                        }

                    },
                    error: function () {
                        //toastr.error('Ошибка получения подстатусов!');
                    }
                });
            }

            //действия над комментариями к сделке
             function comments(element, comments_method, valid_elements = []) {

                 $.ajax({
                     url: '{{ route('leads.description') }}',
                     type: "POST",
                     data: {
                         lead_uuid: "{{ $data->uuid }}",
                         comments_method: comments_method,
                         description: element.html(),
                         valid_elements: valid_elements},
                     async: false,
                     success: function (response) {

                         if (response.result == true) {
                             if(typeof response.content!=="undefined")
                                element.html(response.content);
                         } else {
                             toastr.error('Ошибка поля комментарий к сделке');
                         }
                     },
                     error: function () {
                         toastr.error('Ошибка поля комментарий к сделке');
                     }
                 });
             }

             var description_textarea = $('#kt_maxlength_letter_1'),
                 description_read = $('#lead-comments-text');
            comments( description_textarea, 'add_content');
            //для перехода по ссылкам в textarea
            description_read.on('dblclick', function(){
                 $(this).css('display', 'none');
                 description_textarea.css('display', 'block');
             });

            $(document).mouseup( function(e){

                if ( !description_textarea.is(e.target) &&
                    description_textarea.has(e.target).length === 0 &&
                     !description_read.is(e.target) &&
                    description_read.has(e.target).length === 0 &&
                    description_read.is(':hidden') &&
                    !description_textarea.is(':hidden')) {

                    console.log('save');

                    if(description_textarea.val() != '') {
                        description_textarea.css('display', 'none');
                    }

                    description_textarea.html(description_textarea.val());
                    comments(description_textarea,'save_content');
                    if(description_textarea.val() != '') {
                        description_read.css('display', 'block');
                    }
                    comments(description_read,'read_area');
                }
            });

            description_textarea.keydown(function(e) {
                if(e.keyCode === 9) { // tab was pressed
                    // get caret position/selection
                    var start = this.selectionStart;
                    var end = this.selectionEnd;

                    var $this = $(this);
                    var value = $this.val();

                    // set textarea value to: text before caret + tab + text after caret
                    $this.val(value.substring(0, start)
                        + "\t"
                        + value.substring(end));

                    // put caret at right position again (add one for the tab)
                    this.selectionStart = this.selectionEnd = start + 1;

                    // prevent the focus lose
                    e.preventDefault();
                }
            });

            $("#form").submit(function (event) {
                //if(description_textarea.is(':hidden')) comments(description_textarea,'save_content');
                $('.body-block-loaders').removeClass('d-none');
            });
        });
    </script>

@endsection




