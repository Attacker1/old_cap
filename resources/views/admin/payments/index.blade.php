@extends('admin.layout-metronic.default')

@section('title'){{ $title }}@endsection

@section('breadcrumb'){{ $title }}@endsection

@section('actions')
    <div class="float-right">
        {{--    <a href="{{ route('payments.create') }}"><button class="btn btn-sm btn-success"><i class="fa fa-plus"></i> Добавить оплату</button></a>--}}
    </div>
@endsection

@section('content')

    <div class="card card-custom gutter-b">
        <div class="card-body">
            <div id="kt_datatable_2_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">

                <div class="row filters">

                    <div class="input-group ml-1 col-sm-4">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="client_label">Клиент</span>
                        </div>
                        <input type="text" name="client" placeholder="Имя или телефон" id="client" value=""
                               class="form-control " autocomplete="off" required="" aria-label=""
                               aria-describedby="client_label">
                    </div>

                    <div class="col-sm-1">
                        <button class="btn btn-outline-success" id="filter_by_client">Ok</button>
                    </div>

                </div>

                <div class="table table-responsive">
                    <table id="datatable"
                           class="table table-separate table-head-custom table-checkable dataTable no-footer dtr-inline">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Дата</th>
                            <th>ID сделки</th>
                            <th>Клиент</th>
                            <th>Назначение</th>
                            <th>Сделка</th>
                            <th>FeedBack ID</th>
                            <th>Сумма</th>
                            <th>Источник</th>
                            <th>Управление</th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>

        @endsection

        @section('scripts')
            <link rel="stylesheet" type="text/css" href="/m/plugins/custom/datatables/datatables.bundle.css">
            <script src="/m/plugins/custom/datatables/datatables.bundle.js"></script>
            <script>
                $(function () {

                    $(document).on('click', '.modal-payment-delete', function (e) {
                        e.preventDefault();
                        let url = $(this).data('route-destroy');
                        swal.fire({
                            text: 'Вы действительно хотите удалить оплату?',
                            showCancelButton: true,
                            confirmButtonColor: '#c7131c',
                            cancelButtonColor: '#b4b4b4',
                            cancelButtonText: 'Отмена',
                            confirmButtonText: 'Удалить'
                        }).then(function (result) {
                            if (result.value === true) {
                                let form = $('<form action="' + url + '" method="get"> "</form>');
                                $('body').append(form);
                                form.submit();
                            }
                        });
                    });

                    var table = $('#datatable').DataTable({
                        order: [[1, "desc"]],
                        dom: 'rtp',
                        processing: true,
                        serverSide: true,
                        stateSave: false,
                        "lengthMenu": [50, 100],
                        length: 50,
                        "language": {
                            "url": "/app-assets/data/dt_ru.json"
                        },

                        ajax: {
                            url: '{!! route('payments.list') !!}',
                            method: "GET",
                            data: function (d) {
                                d.client = $('#client').val();
                            }
                        },
                        columns: [
                            {data: 'id', name: 'id', class: 'date w75', class: 'small'},
                            {data: 'created_at', name: 'created_at', class: 'text-danger  small'},
                            {data: 'lead_id', name: 'lead_id', class: 'd-none small'},
                            {
                                data: null, render: function (data, type, row) {
                                    if(data.leads) {
                                        return '<a target="blank" href="{{route('clients.index','')}}/'+data.leads.client_id+'">'+data.client+'</a>'
                                    } else  {
                                        return '<span>отсутствует</span>';
                                    }

                                }
                            },
                            {data: 'pay_for', name: 'pay_for', class: 'small'},
                            {
                                data: null, render: function (data, type, row) {
                                    if (data.leads) {
                                        return '<a target="blank"  href="{{route('leads.edit','')}}/' + data.leads.uuid + '">' + data.leads.amo_lead_id + '</a>'
                                    } else {
                                        return '<span>отсутствует</span>';
                                    }
                                }
                            },
                            {data: 'order', name: 'order', searchable: false, class: 'small'},
                            {data: 'amount', name: 'amount', searchable: false, class: 'small'},
                            {data: 'source', name: 'source', searchable: false, class: 'small'},
                            {data: 'action', name: 'action', searchable: false, orderable: false, class: 'small'},

                        ],
                    });



                    $(document).ajaxStart(function () {
                        $('.body-block-loaders').removeClass('d-none');
                    }).ajaxStop(function () {
                        $('.body-block-loaders').addClass('d-none');
                    });

                    $(document).on('click', '#filter_by_client', function (e) {
                        table.draw();
                    });

                });
            </script>

@endsection
