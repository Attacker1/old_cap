@extends('admin.layout-metronic.default')

@section('title'){{ $title }}@endsection

@section('breadcrumb'){{ $title }}@endsection

@section('actions')

    @if(auth()->guard('admin')->user()->hasPermission('anketa-listing-csv'))
        {{--Выгрузка всех полей анкет xls--}}
        @include('partials.admin.anketa.listing.exports.anketa_xsl_list_whole')
        {{--Выгрузка google xls--}}
        @include('partials.admin.anketa.listing.exports.anketa_xsl_list_short')

    @else
        <span class="badge badge-warning">Отображены анкеты только ваших клиентов</span>
    @endif

@endsection

@section('content')

    <div class="card card-custom gutter-b">
        <div class="card-body">

            <div id="kt_datatable_2_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">

                <div id="datatable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">

                    <div id="filter-base">
                        @include('partials.admin.anketa.listing.ajax',['data' => $data, 'filtersData' => $filtersData])
                    </div>
                </div>

            </div>

        </div>
    </div>

@endsection

@section('scripts')
    <link rel="stylesheet" type="text/css" href="/m/plugins/custom/datatables/datatables.bundle.css">
        <script src="{{asset('app-assets/js/scripts/forms/custom.filter.js')}}"></script>
    <script>


        $('#filter-base').customFilter({
            base: '#filter-base'
            // processingShow: true
        })

    </script>
@endsection
