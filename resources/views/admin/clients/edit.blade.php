@extends('admin.layout-metronic.default')

@section('title'){{ $title }}@endsection
@section('actions')
    <a href="{{ url()->previous() }}" class="btn btn-light-danger font-weight-bolder mr-2">
        <i class="ki ki-long-arrow-back icon-xs"></i>К списку</a>
@endsection


@section('content')
    <div class="row">
        <div class="col-md-7 sol-sm-12">
            <form method="POST" action="{{route('clients.update', $clientData->uuid)}}" class="col-md-12">
                {{ csrf_field() }}
                <div class="card card-custom gutter-b" id="kt_card_main">
                    <div class="card-header card-header-tabs-line">
                        <div class="card-title">
                            <h3 class="">Карточка клиента</h3>
                        </div>
                        <div class="card-toolbar">
                            <a href="#" class="btn btn-icon btn-sm mr-1" data-card-tool="toggle" data-toggle="tooltip"
                               data-placement="top" title="" data-original-title="Свернуть">
                                <i class="ki ki-arrow-down icon-nm "></i>
                            </a>
                            <ul class="nav nav-tabs nav-bold nav-tabs-line">
                                <li class="nav-item">
                                    <a class="nav-link active" data-toggle="tab" href="#kt_tab_pane_1_2">Профиль</a>
                                </li>
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button"
                                       aria-haspopup="true" aria-expanded="false">Прочее</a>
                                    <div class="dropdown-menu dropdown-menu-sm dropdown-menu-right">
                                        <a class="dropdown-item" data-toggle="tab" href="#kt_tab_pane_3_2">Log</a>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="row mb-4">
                            @method('PUT')
                            <div class="form-group col-sm-12">
                                <label for="inputUserName">Имя</label>
                                <input type="text" name="name" class="form-control bg-light"
                                       value="{{$clientData->name}}"
                                       id="inputClientName" aria-describedby="nameHelp" placeholder="Имя" required
                                       autofocus autocomplete="off">
                                @if ($errors->has('name'))
                                    <small class="form-text text-danger font-weight-bold">{{ $errors->first('name') }}</small>
                                @endif
                            </div>
                            <div class="form-group col-sm-4">
                                <label for="inputUserName">Фамилия</label>
                                <input type="text" name="second_name" class="form-control bg-light"
                                       value="{{$clientData->second_name}}"
                                       id="inputSecondName" aria-describedby="secondNameHelp" placeholder="Фамилия"
                                       autofocus autocomplete="off">
                                @if ($errors->has('second_name'))
                                    <small class="form-text text-danger font-weight-bold">{{ $errors->first('second_name') }}</small>
                                @endif
                            </div>

                            <div class="form-group col-sm-5">
                                <label for="inputUserName">Телефон</label>
                                <input type="text" name="phone" class="form-control bg-light"
                                       value="{{$clientData->phone}}"
                                       id="inputPhone" aria-describedby="phoneHelp" placeholder="Телефон" required
                                       autofocus autocomplete="off">
                                @if ($errors->has('phone'))
                                    <small class="form-text text-danger font-weight-bold">{{ $errors->first('phone') }}</small>
                                @endif
                            </div>

                            <div class="form-group col-sm-3">
                                <label>Статус</label>
                                <select class="form-control bg-light" style="width:200px" name="status">
                                    <option value="">Не выбрано</option>
                                    @foreach($client_statuses as $status)
                                        <option value="{{$status->id}}"
                                                @if($errors->has('referal_code'))
                                                @if( $status->id == old('client_status_id')) selected @endif
                                                @else
                                                @if( $status->id == $clientData->client_status_id)  selected @endif
                                                @endif > {{$status->name}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group col-sm-6">
                                <label for="inputUserName">Инстаграм</label>
                                <input type="text" name="socialmedia_links" class="form-control bg-light"
                                       value="{{$clientData->socialmedia_links}}"
                                       id="inputSocialmedia_links" aria-describedby="emailHelp" placeholder="Инстаграм"
                                       autofocus autocomplete="off">
                                @if ($errors->has('socialmedia_links'))
                                    <small class="form-text text-danger font-weight-bold">{{ $errors->first('socialmedia_links') }}</small>
                                @endif
                            </div>


                            <div class="form-group col-sm-6">
                                <label for="inputUserName">E-mail</label>
                                <input type="text" name="email" class="form-control bg-light"
                                       value="{{$clientData->email}}"
                                       id="inputEmail" aria-describedby="emailHelp" placeholder="Email" autofocus
                                       autocomplete="off">
                                @if ($errors->has('email'))
                                    <small class="form-text text-danger font-weight-bold">{{ $errors->first('email') }}</small>
                                @endif
                            </div>

                            <div class="form-group col-sm-12">
                                <label>Комментарии</label>
                                <textarea name="comments" class="form-control bg-light" placeholder="Комментарии"
                                          autofocus
                                          autocomplete="off"
                                          maxlength="2000">@if($errors->has('comments')) {{ old('comments') }} @else {{ $clientData->comments }} @endif</textarea>
                                @if ($errors->has('comments'))
                                    <small class="form-text text-danger font-weight-bold">{{ $errors->first('comments') }}</small>
                                @endif
                            </div>
                            <div class="form-group col-sm-12">
                                <button type="submit" class="btn btn-primary waves-effect"><i
                                            class="ki ki-check icon-xs"></i> Сохранить
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <div class="col-md-5">
            <form method="POST" action="{{route('clients.bonus.update', $clientData->uuid)}}" class="col-md-12">
                {{ csrf_field() }}
                <div class="card card-custom gutter-b bg-success" id="kt_card_1">
                    <div class="card-header card-header-tabs-line">
                        <div class="card-title">
                            <h3 class="text-white">Бонусы</h3>
                        </div>
                        <div class="card-toolbar">
                            <a href="#" class="btn btn-icon btn-sm mr-1" data-card-tool="toggle" data-toggle="tooltip"
                               data-placement="top" title="" data-original-title="Свернуть">
                                <i class="ki ki-arrow-down icon-nm text-white "></i>
                            </a>
                            <ul class="nav nav-light-white nav-bold nav-pills">
                                <li class="nav-item ">
                                    <a class="nav-link active text-white" data-toggle="tab" href="#kt_tab_pane_1_2">Бонусы</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link text-white" data-toggle="tab" href="#kt_tab_pane_2_2">История</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="row card-body">

                        <div class="tab-content col-sm-12">
                            <div class="tab-pane fade active show" id="kt_tab_pane_1_2" role="tabpanel">
                                <div class="form-group col-md-12 sol-sm-12">
                                    <label class="text-white">Баллы:</label>
                                    <input
                                            type="number" name="points" class="form-control"
                                            value="{{ @$clientData->bonuses->points }}" placeholder="Бонусы" autofocus
                                            autocomplete="off" maxlength="15">
                                    @if ($errors->has('points'))
                                        <small class="form-text text-danger font-weight-bold">{{ $errors->first('referal_code') }}</small>
                                    @endif
                                </div>

                                <div class="form-group col-sm-12">
                                    <label class="text-white">Реферальный код (new)</label>
                                    <input
                                            type="text" name="promocode" class="form-control"
                                            value="{{ @$clientData->bonuses->promocode }}"
                                            placeholder="Реферальный код" autofocus autocomplete="off" maxlength="100">
                                    @if ($errors->has('promocode'))
                                        <small class="form-text text-danger font-weight-bold">{{ $errors->first('promocode') }}</small>
                                    @endif
                                </div>
                                <div class="form-group col-sm-6 ">
                                    <button type="submit" class="btn btn-white font-weight-bolder">
                                        <i class="ki ki-check icon-xs"></i>Сохранить
                                    </button>
                                </div>

                            </div>
                            <div class="tab-pane fade show  " id="kt_tab_pane_2_2" role="tabpanel">

                                <div class="col-sm-12">
                                    <table class="table table-separate table-checkable dataTable no-footer dtr-inline">
                                        <thead class="text-white">
                                        <td>Дата</td>
                                        <td>Поль-ль</td>
                                        <td>Поле</td>
                                        <td>Было</td>
                                        <td>Стало</td>
                                        </thead>
                                        @if($history)
                                            @foreach($history as $item )
                                                <tr class="text-light-white">
                                                    <td>{{ @\Carbon\Carbon::parse($item->created_at)->format("d/m/Y") }}</td>
                                                    <td>{{ @\App\Http\Models\Admin\AdminUser::where('id',$item->user_id)->first()->name }}</td>
                                                    <td>{{ @$item->fieldName('key') }}</td>
                                                    @if(empty($item->old_value))
                                                        <td>0</td>
                                                    @else
                                                        <td>{{ @$item->old_value }}</td>
                                                    @endif
                                                    <td>{{ @$item->new_value }}</td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </table>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>
            </form>
            <form method="POST" action="{{route('clients.password.update', $clientData->uuid)}}" class="col-md-12">
                {{ csrf_field() }}
                <div class="card card-custom gutter-b bg-success" id="kt_card_2">
                    <div class="card-header card-header-tabs-line">
                        <div class="card-title">
                            <h3 class="text-white">Смена пароля</h3>
                        </div>
                        <div class="card-toolbar">
                            <a href="#" class="btn btn-icon btn-sm mr-1" data-card-tool="toggle" data-toggle="tooltip"
                               data-placement="top" title="" data-original-title="Свернуть">
                                <i class="ki ki-arrow-down icon-nm text-white "></i>
                            </a>
                        </div>
                    </div>
                    <div class="row card-body">

                        <div class="tab-content col-sm-12">
                            <div class="tab-pane fade active show" id="kt_tab_pane_1_2" role="tabpanel">
                                <div class="form-group col-md-12 sol-sm-12">
                                    <label class="text-white">Новый пароль:</label>
                                    <input
                                            type="password" name="new_password" class="form-control"
                                             placeholder="Пароль" autofocus
                                            autocomplete="off" maxlength="15">
                                    @if ($errors->has('new_password'))
                                        <small class="form-text text-danger font-weight-bold">{{ $errors->first('new_password') }}</small>
                                    @endif
                                </div>
                                <div class="form-group col-sm-6 ">
                                    <button type="submit" class="btn btn-white font-weight-bolder">
                                        <i class="ki ki-check icon-xs"></i>Сохранить
                                    </button>
                                </div>

                            </div>
                            <div class="tab-pane fade show  " id="kt_tab_pane_2_2" role="tabpanel">

                                <div class="col-sm-12">
                                    <table class="table table-separate table-checkable dataTable no-footer dtr-inline">
                                        <thead class="text-white">
                                        <td>Дата</td>
                                        <td>Поль-ль</td>
                                        <td>Поле</td>
                                        <td>Было</td>
                                        <td>Стало</td>
                                        </thead>
                                        @if($history)
                                            @foreach($history as $item )
                                                <tr class="text-light-white">
                                                    <td>{{ @\Carbon\Carbon::parse($item->created_at)->format("d/m/Y") }}</td>
                                                    <td>{{ @\App\Http\Models\Admin\AdminUser::where('id',$item->user_id)->first()->name }}</td>
                                                    <td>{{ @$item->fieldName('key') }}</td>
                                                    @if(empty($item->old_value))
                                                        <td>0</td>
                                                    @else
                                                        <td>{{ @$item->old_value }}</td>
                                                    @endif
                                                    <td>{{ @$item->new_value }}</td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </table>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>
            </form>
        </div>

    </div>
@endsection

@section('scripts')
    <script>
        var card_main = new KTCard('kt_card_main');
        var card = new KTCard('kt_card_1');
    </script>
@endsection
