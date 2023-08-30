@extends('admin.layout-metronic.default')

@section('title'){{ $title }}@endsection

@section('actions')
    <div class="float-right">
        <a href="{{ url()->previous() }}"><i class="fas fa-chevron-circle-left text-danger"></i></a>
    </div>
@endsection

@section('content')

    <div class="row">
    <div class="col-sm-6  mt-3 " id="kt_profile_aside">
        <div class="card card-custom card-stretch">

            <div class="card-body pt-4">

                <div class="d-flex justify-content-end">
                    <div class="dropdown dropdown-inline">

                    </div>
                </div>
                <div class="d-flex align-items-center">
                    <div class="symbol symbol-60 symbol-xxl-100 mr-5 align-self-start align-self-xxl-center">
                        <div class="symbol-label" >  <i class="fa-5x far fa-user-circle"></i></div>
                    </div>
                    <div>
                        <a href="#" class="font-weight-bolder font-size-h5 text-dark-75 text-hover-primary">{{ $clientData->name }} {{ $clientData->second_name }}</a>
                        <div class="text-muted small">С нами: {{ \Carbon\Carbon::parse($clientData->created_at)->format('d.m.Y H:i') }}</div>
                        <div class="mt-2">
                            <a href="#" class="btn btn-sm btn-primary font-weight-bold mr-2 py-2 px-3 px-xxl-5 my-1">{{ @$clientData->bonuses->promocode }}</a>
                            <a href="#" class="btn btn-sm btn-success font-weight-bold py-2 px-3 px-xxl-5 my-1">{{ @$clientData->bonuses->points ?? 0 }} баллов</a>
                        </div>
                    </div>
                </div>
                <div class="py-9">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="font-weight-bold mr-2">E-mail:</span>
                        @if(@$manage)
                            <a href="#" class="text-muted text-hover-primary">{{ $clientData->email }}</a>
                        @endif

                    </div>
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="font-weight-bold mr-2">Телефон:</span>
                        @if(@$manage)
                            <span class="text-muted client-phones">+{{ substr($clientData->phone, 0, 1).' ('.substr($clientData->phone, 1, 3).') '.substr($clientData->phone, 4, 3).'-'.substr($clientData->phone, 7, 2).'-'.substr($clientData->phone, 9, 2) }}</span>
                        @endif

                    </div>
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="font-weight-bold mr-2">Инстаграм:</span>
                        <span class="text-muted client-phone">{{ @$clientData->socialmedia_links }}</span>
                    </div>
                    <div class="d-flex align-items-center justify-content-between">
                        <span class="font-weight-bold mr-2">Статус:</span>
                        <span class="text-muted ">{{ $clientData->status }}</span>
                    </div>
                    <div class="mt-3 d-flex align-items-center justify-content-between">
                        <span class="font-weight-bold mr-2">Комментарии:</span>
                    </div>
                    <div class="d-flex align-items-center justify-content-between">
                        <span class="form-text text-muted">{{ $clientData->comments }}</span>
                    </div>
                </div>

                <div class="navi navi-bold navi-hover navi-active navi-link-rounded">
                    @if(auth()->guard('admin')->user()->hasPermission('manage-clients'))
                    <div class="navi-item mb-2">
                        <a href="#" class="navi-link py-4 active">
															<span class="navi-icon mr-2">
																<span class="svg-icon">
																	<!--begin::Svg Icon | path:/metronic/theme/html/demo1/dist/assets/media/svg/icons/Design/Layers.svg-->
																	<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
																		<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
																			<polygon points="0 0 24 0 24 24 0 24"></polygon>
																			<path d="M12.9336061,16.072447 L19.36,10.9564761 L19.5181585,10.8312381 C20.1676248,10.3169571 20.2772143,9.3735535 19.7629333,8.72408713 C19.6917232,8.63415859 19.6104327,8.55269514 19.5206557,8.48129411 L12.9336854,3.24257445 C12.3871201,2.80788259 11.6128799,2.80788259 11.0663146,3.24257445 L4.47482784,8.48488609 C3.82645598,9.00054628 3.71887192,9.94418071 4.23453211,10.5925526 C4.30500305,10.6811601 4.38527899,10.7615046 4.47382636,10.8320511 L4.63,10.9564761 L11.0659024,16.0730648 C11.6126744,16.5077525 12.3871218,16.5074963 12.9336061,16.072447 Z" fill="#000000" fill-rule="nonzero"></path>
																			<path d="M11.0563554,18.6706981 L5.33593024,14.122919 C4.94553994,13.8125559 4.37746707,13.8774308 4.06710397,14.2678211 C4.06471678,14.2708238 4.06234874,14.2738418 4.06,14.2768747 L4.06,14.2768747 C3.75257288,14.6738539 3.82516916,15.244888 4.22214834,15.5523151 C4.22358765,15.5534297 4.2250303,15.55454 4.22647627,15.555646 L11.0872776,20.8031356 C11.6250734,21.2144692 12.371757,21.2145375 12.909628,20.8033023 L19.7677785,15.559828 C20.1693192,15.2528257 20.2459576,14.6784381 19.9389553,14.2768974 C19.9376429,14.2751809 19.9363245,14.2734691 19.935,14.2717619 L19.935,14.2717619 C19.6266937,13.8743807 19.0546209,13.8021712 18.6572397,14.1104775 C18.654352,14.112718 18.6514778,14.1149757 18.6486172,14.1172508 L12.9235044,18.6705218 C12.377022,19.1051477 11.6029199,19.1052208 11.0563554,18.6706981 Z" fill="#000000" opacity="0.3"></path>
																		</g>
																	</svg>
                                                                    <!--end::Svg Icon-->
																</span>
															</span>
                            <span class="navi-text font-size-lg">Информация о пользователе</span>
                        </a>
                    </div>


                    <div class="navi-item mb-2">
                        <a href="#" class="navi-link py-4" title="Смена пароля когда будет через e-mail">
															<span class="navi-icon mr-2">
																<span class="svg-icon">
																	<!--begin::Svg Icon | path:/metronic/theme/html/demo1/dist/assets/media/svg/icons/Communication/Shield-user.svg-->
																	<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
																		<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
																			<rect x="0" y="0" width="24" height="24"></rect>
																			<path d="M4,4 L11.6314229,2.5691082 C11.8750185,2.52343403 12.1249815,2.52343403 12.3685771,2.5691082 L20,4 L20,13.2830094 C20,16.2173861 18.4883464,18.9447835 16,20.5 L12.5299989,22.6687507 C12.2057287,22.8714196 11.7942713,22.8714196 11.4700011,22.6687507 L8,20.5 C5.51165358,18.9447835 4,16.2173861 4,13.2830094 L4,4 Z" fill="#000000" opacity="0.3"></path>
																			<path d="M12,11 C10.8954305,11 10,10.1045695 10,9 C10,7.8954305 10.8954305,7 12,7 C13.1045695,7 14,7.8954305 14,9 C14,10.1045695 13.1045695,11 12,11 Z" fill="#000000" opacity="0.3"></path>
																			<path d="M7.00036205,16.4995035 C7.21569918,13.5165724 9.36772908,12 11.9907452,12 C14.6506758,12 16.8360465,13.4332455 16.9988413,16.5 C17.0053266,16.6221713 16.9988413,17 16.5815,17 C14.5228466,17 11.463736,17 7.4041679,17 C7.26484009,17 6.98863236,16.6619875 7.00036205,16.4995035 Z" fill="#000000" opacity="0.3"></path>
																		</g>
																	</svg>
                                                                    <!--end::Svg Icon-->
																</span>
															</span>
                            <span class="navi-text font-size-lg">Сменить пароль</span>
                        </a>
                    </div>

                    <div class="navi-item mb-2 mt-5 ">
                    <a href="{{route('clients.edit', $clientData->uuid)}}" class="btn btn-primary waves-effect mr-2">Изменить</a>
{{--                    <a--}}
{{--                            data-route-destroy = "{{route('clients.destroy', $clientData->uuid)}}"--}}
{{--                            data-delete-form-csrf =  "{{ csrf_token() }}"--}}
{{--                            data-name = "{{ $clientData->name }} {{ $clientData->second_name }}"--}}
{{--                            class="btn btn-outline-danger ml-1 waves-effect text-danger mr-2 modal-client-delete">Удалить</a>--}}

                    <a href="{{route('clients.list')}}" class="btn btn-outline-secondary text-bold text-dark waves-effect">К списку</a>
                    </div>
                    @endif
                </div>

            </div>
            <!--end::Body-->
        </div>
        <!--end::Profile Card-->
    </div>

{{--        Оплаты          --}}
@if(auth()->guard('admin')->user()->hasPermission('manage-clients'))
        <div class="col-sm-6  mt-3" id="kt_profile_aside">
            <div class="card card-custom card-stretch">
                <div class="card-header pb-1">
                    <div class="card-title">
                        <h4 class="mb-0">Оплаты</h4>
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
{{--        Оплаты Конец блока          --}}

{{--        Анкеты          --}}
        <div class="col-sm-6 mt-3 " id="kt_profile_aside">
            <div class="card card-custom card-stretch">
                <div class="card-header pb-1">
                    <div class="card-title">
                        <h4 class="mb-0">Анкеты</h4>
                    </div>
                </div>
                <div class="card-body pt-4">
                    <div class="table table-responsive">
                        <table id="anketa"
                               class="table table-separate table-head-custom table-checkable dataTable no-footer dtr-inline">
                            <thead>
                            <tr>
                                <th>Дата</th>
                                <th>Анкета (ID)</th>
                                <th>Стилист</th>
                                <th>Управление</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
{{--        Анкеты Конец блока          --}}
{{--        Обратная связь          --}}
        <div class="col-sm-6 mt-3 " id="kt_profile_aside">
            <div class="card card-custom card-stretch">
                <div class="card-header pb-1">
                    <div class="card-title">
                        <h4 class="mb-0">Обратная связь</h4>
                    </div>
                </div>
                <div class="card-body pt-4">
                    <div class="table table-responsive">
                        <table id="feedback"
                               class="table table-separate table-head-custom table-checkable dataTable no-footer dtr-inline">
                            <thead>
                            <tr>
                                <th>Дата</th>
                                <th>ID обратной связи</th>
                                <th>Управление</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
{{--        Обратная связь Конец блока          --}}


    </div>



@endsection

@section('scripts')
    <link rel="stylesheet" type="text/css" href="/m/plugins/custom/datatables/datatables.bundle.css">
    <script src="/m/plugins/custom/datatables/datatables.bundle.js"></script>
<script>
    $(function () {

        $('.modal-client-delete').on('click', function () {
            let url = $(this).data('route-destroy');
            swal.fire({
                text: 'Вы действительно хотите удалить клиента ' + $(this).data('name') + '?',
                showCancelButton: true,
                confirmButtonColor: '#28c76f',
                cancelButtonColor: '#aeaad2',
                confirmButtonText: 'Удалить'
            }).then(function (result) {
                if (result.value === true) {
                    let form = $('<form action="' + url + '" method="post"><input type="hidden" name="_token" value="' + $('.modal-client-delete').data('delete-form-csrf') + '"> "<input type="hidden" name="_method" value="delete"></form>');
                    $('body').append(form);
                    form.submit();
                }
            });

        });

        var payments = $('#payments').DataTable({
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
                url: '{!! route('admin.anketa.payments',$clientData->uuid) !!}',
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

        var anketa = $('#anketa').DataTable({
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
                url: '{!! route('admin.anketa.ajax',$clientData->uuid) !!}',
                method: "POST",
                data: function (d) {
                    d.client = $('#client').val();
                }
            },
            columns: [
                {data: 'created_at', name: 'created_at', class: 'text-danger  small'},
                {data: 'id', name: 'id', searchable: false, class: 'small'},
                {data: 'stylist', name: 'stylist', searchable: false, class: 'small'},
                {data: 'action', name: 'action', searchable: false, class: 'small'},
            ],
        });

        var feedback = $('#feedback').DataTable({
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
                url: '{!! route('admin.anketa.feedback',$clientData->uuid) !!}',
                method: "POST",
                data: function (d) {
                    d.client = $('#client').val();
                }
            },
            columns: [
                {data: 'created_at', name: 'created_at', class: 'text-danger  small'},
                {data: 'id', name: 'id', searchable: false, class: 'small'},
                {data: 'action', name: 'action', searchable: false, class: 'small'},
            ],
        });

    });

</script>
@endsection
