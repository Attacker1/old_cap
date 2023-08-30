@extends('admin.layout-metronic.default')

@section('title'){{ $title }}@endsection
@section('breadcrumb'){{ $title }}@endsection

@section('styles')
    <link rel="stylesheet" href="{{asset('app-assets/css/plugins/select2/select2.min.css')}}"/>
    <style>
        .select2-selection--multiple {
            border: 1px solid #E4E6EF !important;
        }

        .select2-selection--multiple {
            overflow: hidden;
        }
    </style>
@endsection

@section('actions')
    @if(auth()->guard('admin')->user()->hasPermission('create-leads'))
        <div class="float-left">
            <a href="{{ route('leads.create') }}">
                <button class="btn btn-sm btn-success"><i class="fa fa-plus"></i> Создать сделку</button>
            </a>
            <a href="#" id="export_leads_barcodes">
                <button class="btn btn-sm btn-primary"><i class="fa fa-file-excel"></i> Выгрузка для штрихкодов</button>
            </a>
        </div>
    @endif
@endsection

@section('content')

    <div class="card card-custom gutter-b">
        <div class="card-body">

            <div class="dt-bootstrap4 no-footer">

                <div class="dataTables_wrapper dt-bootstrap4 no-footer">

                    <div id="lid-filter-base">
                        @include('partials.admin.lead.listing.ajax',['data' => $data, 'filtersData' => $filtersData])
                    </div>
                </div>

            </div>

        </div>
    </div>

@endsection

@section('scripts')

    <script src="{{asset('app-assets/js/scripts/forms/custom.filter.js')}}"></script>
    <link rel="stylesheet" href="{{asset('app-assets/css/plugins/datetimepicker/jquery.datetimepicker.css')}}"/>
    <script src="{{asset('app-assets/js/scripts/datetimepicker/jquery.datetimepicker.full.js')}}"></script>

    <script>
        $(function () {
            $(document).on('click','[rel="date_picker"]',function(){
                $(this).datetimepicker({
                    format: 'Y-m-d',
                    timepicker:false,
                });
            });

            $(document).on('change','[rel="date_picker"]',function(){
                $(this).datetimepicker('destroy');
            });

            $('#lid-filter-base').customFilter({
                base: '#lid-filter-base'
            });

            $(document).on('click','#export_leads_barcodes',function(e)
            {
                toastr.success('Файл формируется!');
                e.preventDefault();
                window.location.href = "{{ route('export.leads.barcode') }}";
            });

        })
    </script>


@endsection
