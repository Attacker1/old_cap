@extends('admin.layout-metronic.default')

@section('title'){{ $title }}@endsection
@section('breadcrumb'){{ $title }}@endsection


@section('content')
    <?php
        $payedToday = [];
        foreach($data as $item) {
            if ($item->states->id ==2 && $item->created_at > new DateTime('today')) {
                array_push($payedToday, $item);
            }
        }
    ?>
    <div class="card card-custom gutter-b">
        <div class="card-body">
            <p style="padding: 10px;width: 100%;font-size: 25px;border: 1px solid #000000;">{!! count($payedToday) . ' новых анкет' !!}</p>
            @if(count($data))
                <div class="dt-bootstrap4 no-footer">
                    <div class="dataTables_wrapper dt-bootstrap4 no-footer">
                        <div id="lid-filter-base">
                            @include('partials.admin.analytics.table',
                                ['data' => $data,
                                'statuses' => $statuses,
                                'parentId' => false
                                ])
                            @include('partials.admin.analytics.table',
                                ['titleTable' => 'ПРОБЛЕМЫ С ПОДБОРОМ',
                                'data' => $data,
                                'statuses' => $statuses,
                                'parentId' => 5
                                ])
                            @include('partials.admin.analytics.table',
                                ['titleTable' => 'НЕРЕАЛИЗОВАННЫЕ',
                                'data' => $data,
                                'statuses' => $statuses,
                                'parentId' => 19
                                ])
                        </div>
                    </div>

                </div>
            @endif
        </div>
    </div>
@endsection

@section('scripts')

    <script>
        $(function () {
            $('.analytics-item').each(function() {
                if(!$(this).find('.analytics-status').length) {
                    $(this).remove();
                }
            });

        })
    </script>


@endsection