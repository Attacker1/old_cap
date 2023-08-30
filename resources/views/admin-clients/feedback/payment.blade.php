@extends('admin-clients.layouts.main')

@section('content')
<div class="card">
    <div class="card-header row">
       <div class="col-12">
       </div>
    </div><!--card-header-->

    <div class="card-content">
        <div class="card-header">
           <p><b>Оплата</b></p>
        </div><!--card-header-->
        <div class="card-body mb-3 row ml-3">

            <div class="col-12 mb-3">Сумма вашей покупки {{$payment_sum}} руб.</div>

                <form method="post" action="{{route('admin-clients.payment.bonuses-store')}}" style="width: 100%">

                    {{ csrf_field() }}

                    <div class="col-12">
                        @if($bonuses_text)
                            <div class="col-12">Доступно для списания {{$bonuses_text}}</div>
                            <br><input type="text" name="points" class="form-control" maxlength="500" autocomplete="off" style="width: 150px">
                            @if ($errors->has('points'))
                                <small class="form-text text-danger font-weight-bold">{{ $errors->first('points') }}</small>
                            @endif
                            @if ($errors->has('lead_id'))
                                <small class="form-text text-danger font-weight-bold">{{ $errors->first('lead_id') }}</small>
                            @endif
                            <br><input type="hidden" name="lead_id" value="{{$lead_id}}">
                        @endif
                        <a href="{{ route('admin-clients.feedback.edit', $feedback_uuid) }}" class="btn mb-1 mr-3" style="background-color: #fff; border: 2px solid #00e3a7;  font-weight: bold; width:120px; color: #71777e">Изменить</a>
                            @if($bonuses_text)
                                <button type="submit" class="btn btn-info mb-1" style="background-color: #00e3a7 !important; border-color: #00e3a7 !important; font-weight: bold; width:120px; margin-right: 20px">Оплатить</button>
                            @else
                                <a href="{{  route('admin-clients.payment.show-method', $lead_id) }}" class="btn mb-1" style="background-color: #fff; border: 2px solid #00e3a7;  font-weight: bold; width:120px; color: #71777e">Оплатить</a>
                            @endif
                    </div>

                </form>


        </div><!--card-body-->
    </div><!--card-content-->
</div><!--card-->
@endsection
