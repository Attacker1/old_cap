@extends('admin.layout-metronic.default')

@section('title'){{ $title }}@endsection

@section('actions')
    <div class="float-right">
        <a href="{{ route('clients.create') }}"><button class="btn btn-sm btn-success"><i class="fa fa-plus"></i> Добавить</button></a>
    </div>
@endsection


@section('content')

    <div class="card card-custom gutter-b">
        <div class="card-body">
            <div id="kt_datatable_2_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">

                <table id="datatable"
                       class="table table-separate table-head-custom table-checkable dataTable no-footer dtr-inline">
                    <thead>
                    <tr>
                        <td class="font-weight-bolder"></td>
                        <td class="font-weight-bolder">ID</td>
                        <td class="font-weight-bolder">Имя</td>
                        <td class="font-weight-bolder">Телефон </td>
                        <td class="font-weight-bolder">Телефон</td>
                        <td class="font-weight-bolder">E-mail</td>
                        <td class="font-weight-bolder">Реф.код</td>
                        <td class="font-weight-bolder">Бонусы</td>
                        <td class="font-weight-bolder">Примечание</td>
                        <td class="font-weight-bolder"><div style="width: 150px">Действия</div></td>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    @endsection


@section('scripts')

    {{--    Вынести куда то ? --}}
    <link rel="stylesheet" type="text/css" href="/m/plugins/custom/datatables/datatables.bundle.css">
    <script src="/m/plugins/custom/datatables/datatables.bundle.js"></script>
    <script>
        $(function () {

            function show_modal_delete(text, attr_data)
            {
                $('.modal-delete').on('click', function(){
                    let url = $(this).attr('data-id');

                    swal.fire({
                        text: text,
                        showCancelButton: true,
                        icon: "warning",
                        cancelButtonColor: '#aeaad2',
                        cancelButtonText: 'Отмена',
                        confirmButtonText: 'Удалить',
                    }).then(function (result) {
                        if (result.value === true) {
                            let form = $(`
                    <form action="` + url + `" method="post">
                        <input type="hidden" name="_token" value="` + $('meta[name="csrf-token"]').attr('content') + `">
                        <input type="hidden" name="_method" value="delete">
                    </form>`);
                            $('body').append(form);
                            form.submit();
                        }
                    });

                });
            }

            var table = $('#datatable').DataTable({
                order: [[0, "asc"]],
                dom: 'frtlp',
                processing: true,
                serverSide: true,
                stateSave: true,
                "lengthMenu": [20, 50,100 ],
                length: 20,
                "language": {
                    "url": "/app-assets/data/dt_ru.json"
                },

                ajax: {
                    url:'{!! route('clients.list') !!}',
                    method: "POST",
                },
                "drawCallback": function( settings ) {
                    show_modal_delete('Вы действительно хотите удалить запись?','data-id');
                },
                columns: [
                    {
                        "className":      'details-control',
                        "orderable":      false,
                        "searchable":      false,
                        "defaultContent": '<i class="fas fa-plus-square" title="Подробнее"></i>'
                    },
                    {data: 'uuid', name: 'uuid', searchable: false, orderable:false, class: 'd-none'},
                    {data: 'name', name: 'name', orderable: true, },
                    {data: 'phone', name: 'phone',searchable: true,class: 'd-none'},
                    {data: 'tel', name: 'tel', searchable: false, orderable: true},
                    {data: 'email', name: 'email', orderable: true},
                    {data: 'code', name: 'code', orderable: false,searchable: false,},
                    {data: 'points', name: 'points',  orderable: false,searchable: false,},
                    {data: 'comments', name: 'comments',orderable: false,searchable: false,},
                    {data: 'action', name: 'action',searchable: false, orderable:false, class:'w-15'},
                ],
            });

            function format ( d ) {

                var social = (d.socialmedia_links != null) ? d.socialmedia_links : '';
                var comments = (d.comments != null) ? d.comments : '';

                return '<table class="table table-responsive"  cellspacing="0" border="0" style="padding-left:50px; background-color: #e7e7f1">'+
                    '<tr>'+
                    '<td>Дата добавления:</td>'+
                    '<td>'+d.created_at+'</td>'+
                    '</tr>'+
                    '<tr>'+
                    '<td>Инстаграм:</td>'+
                    '<td>'+social + '</td>'+
                    '</tr>'+
                    '<tr>'+
                    '<td>Комментарий:</td>'+
                    '<td>'+comments+'</td>'+
                    '</tr>'+
                    '</table>';
            }

            table.on('click', 'td.details-control', function () {
                var tr = $(this).closest('tr');
                var row = table.row( tr );

                if ( row.child.isShown() ) {
                    row.child.hide();
                    tr.removeClass('shown');
                }
                else {
                    // Open this row
                    row.child( format(row.data()) ).show();
                    tr.addClass('shown');
                }
            } );

            $( document ).ajaxStart( function() {
                $('.body-block-loaders').removeClass('d-none');
            } ).ajaxStop ( function(){
                $('.body-block-loaders').addClass('d-none');
            });

            $( document ).on('click','.lead-create', function(e) {
                if (confirm("Создать сделку с этим клиентом?")) {
                    $.ajax({
                        url: '{{ route('leads.store') }}',
                        method: 'post',
                        dataType: 'html',
                        data: {
                            phone: $(this).attr("data-phone"),
                            state_id: 0
                        },
                        success: function(data){
                            toastr.success('Сделка создана');
                        }
                    });
                    return true;
                }
                return false;

            });

        });
    </script>

    <style>
        .filloption {
            float: left;
            border: 1px solid lightgrey;
            cursor: pointer;
            margin: 0 5px;
            max-height: 20px;
            max-width: 20px;
        }

        td.details-control {
            background: url('../resources/details_open.png') no-repeat center center;
            cursor: pointer;
        }
        tr.shown td.details-control {
            background: url('../resources/details_close.png') no-repeat center center;
        }

    </style>

@endsection
