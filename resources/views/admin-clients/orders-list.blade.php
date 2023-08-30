@extends('admin-clients.layouts.main')

@section('content')

    <a href="{{route('admin-clients.repeat-order')}}" class="btn btn-repeat-order btn-purple">Заказать новую капсулу</a>

@foreach($leads as $lead)

    <div class="order-item-wrapper mb-3 p-3">
        <div style="display: flex; flex-wrap: wrap; justify-content: space-between; margin-bottom: 20px">
             <div style="display: flex; flex-direction: column; margin-right: 5px">
                 <h5><b>Капсула #{{$lead->client_num}}</b></h5>
                 <div>{{$lead->date}}</div>
             </div>
            @isset($lead->states)
                <div style="align-self: flex-end; margin-top: 5px">
                    <span class="badge badge-success badge-lead-state">
                        {{ $lead->client_state_id }}</span>
                </div>
            @endisset
        </div>

            @if($lead->feedbeck)
            <div class="btn-feedback-wrapper">
                <a class="btn btn-fb-open btn-purple
                    @if($lead->feedbeck_text == "Оплатить") pay-btn-in-orders
                    @elseif($lead->feedbeck_text == 'Оценить и оплатить капсулу') mark-and-pay-btn-in-orders @endif"
                   href="{{$lead->feedbeck_link}}">{{$lead->feedbeck_text}}</a>
            </div>
            @endif

    </div>
    <div class="col-12" style="display: none">{{$lead->uuid}}</div>
@endforeach
@endsection

@section('scripts')
   @if((config('app.env') == 'production' || config('app.env') == 'local') && $fb_amount!='' )
       <script>
           !function(f,b,e,v,n,t,s)
           {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
               n.callMethod.apply(n,arguments):n.queue.push(arguments)};
               if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
               n.queue=[];t=b.createElement(e);t.async=!0;
               t.src=v;s=b.getElementsByTagName(e)[0];
               s.parentNode.insertBefore(t,s)}(window, document,'script',
               'https://connect.facebook.net/en_US/fbevents.js');
           fbq('init', '904387120352076');
           fbq('track', 'Purchase', {currency: "RUB", value: <?= $fb_amount ?>});
           //fbq('track', 'PageView');
       </script>
       <noscript><img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id=904387120352076&ev=PageView&noscript=1"/></noscript>
   @endif
@endsection

@section('css')
    <style>
        .order-item-wrapper {
            background-color: #fff;
            box-shadow: 0 4px 20px 0 rgb(0 0 0 / 5%);
            border-radius: 3px;
        }
         .badge-lead-state.badge.badge-success {
             background-color: #7be1b1;
             font-size: 1rem;
             font-weight: normal;
             padding: .25em .8em;
             border-radius: 3rem;
         }
         .btn-feedback-wrapper {
             display: flex;
             margin-bottom: 20px;
         }
         .btn-purple {
             border-color: #e268cd !important;
             background-color: #d070ca !important;
             color: #fff;
         }

         .btn-fb-open {
             width: 400px;
         }

        .btn-repeat-order {
            position: relative;
            bottom: 15px;
            width: 400px;
        }

        .btn-purple:hover {
            color: #fff;!important;
        }


    @media screen and (max-width: 576px) {
        .btn-repeat-order {
            position: relative;
            bottom: 20px;
        }
    }

     @media screen and (max-width: 530px) {
          .btn-fb-open {
              width: 100%;
          }
         .btn-repeat-order {
             width: 100%;
         }
     }
     @media screen and (max-width: 330px) {
         .btn-fb-open {
             font-size: 0.9rem;
             padding-left: .3rem;
             padding-right: .3rem;
         }
     }
     </style>
@endsection

