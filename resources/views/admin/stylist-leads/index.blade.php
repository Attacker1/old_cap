@extends('admin.layout-metronic.default')

@section('title'){{ $title }}@endsection
@section('breadcrumb'){{ $title }}@endsection

@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-body">

            <div class="dt-bootstrap4 no-footer">

                <div class="dataTables_wrapper dt-bootstrap4 no-footer">

                    <div id="sty-filter-base">
{{--                        @dump(date_diff(new DateTime(), new DateTime('2021-09-22 00:00:01'))->days)--}}
                        @include('partials.admin.stylist-leads.ajax',['data' => $data, 'filtersData' => $filtersData])
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

            $('#sty-filter-base').customFilter({
                base: '#sty-filter-base'
            })



        })
    </script>


@endsection