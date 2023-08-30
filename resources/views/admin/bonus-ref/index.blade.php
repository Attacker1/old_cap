@extends('admin.layout-metronic.default')

@section('title'){{ $title }}@endsection

@section('actions')
    <div class="float-right">
        <a href="{{ route('bonus.ref.create') }}"><button class="btn btn-sm btn-success"><i class="fa fa-plus"></i> Новая запись</button></a>
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
                        <td class="font-weight-bolder">ID</td>
                        <td class="font-weight-bolder">Наименование</td>
                        <td class="font-weight-bolder">Баллы</td>
                        <td class="font-weight-bolder">Тип</td>
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
                    url:'{!! route('bonus.ref.index') !!}',
                    method: "POST",
                },
                "drawCallback": function( settings ) {
                    show_modal_delete('Вы действительно хотите удалить запись?','data-id');
                },
                columns: [
                    {data: 'id', name: 'id', searchable: false,orderable:false, class: 'd-none'},
                    {data: 'name', name: 'name',},
                    {data: 'points', name: 'points',},
                    {data: 'type', name: 'type',},
                    {data: 'action', name: 'action',searchable: false, orderable:false, class:'w-15'},
                ],
            });

            $( document ).ajaxStart( function() {
                $('.body-block-loaders').removeClass('d-none');
            } ).ajaxStop ( function(){
                $('.body-block-loaders').addClass('d-none');
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
    </style>
@endsection
