@extends('admin-clients.layouts.main')

@section('content')

    <div class="card">
        <div class="card-header row">
            <div class="col-12">
            </div>
        </div><!--card-header-->

        <div class="card-content">
            <div class="card-header">
                <p><b>Успешная оплата</b></p>
            </div><!--card-header-->
            <div class="card-body mb-3 row ml-3">
                <div class="col-12 mb-3">Сумма вашей покупки {{ @$payment_sum }} руб.</div>
                <a href="{{ route('admin-clients.orders.list') }}" class="btn btn-success">К заказам</a>

            </div><!--card-body-->
        </div><!--card-content-->
    </div><!--card-->

@endsection

@section('scripts')
    <script>
        $(function () {
            let env = '{{ config('app.env') ?? 'local' }}';
            if(env == 'production') {
                ym(82667803,'reachGoal','goal_15')
            } else console.log('15');
        });
    </script>
@endsection

