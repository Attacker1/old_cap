@extends('admin-clients.layouts.main')

@section('content')

<div class="rounded justify-content-center bonuses-main-wrapper pb-3 mb-3">

    <div class="col-12 bonuses-text-top"><b>Получите 500₽ за друга</b></div>

    <div class="col-12 bonuses-text-top-comments">Бонусными рублями можно оплатить одежду и услуги стилистов.</div>

    <div class="card bonuses-card">

        <div class="card-content">
            <div class="card-body">

                <div class= "col-lg-6 col-md-6  pr-0 pl-1 mt-3 ">
                    <input id="input-link" type="text" value="{{config('config.ANKETA_URL')}}/?rf={{ $bonuses->promocode ?? ''}}" class="form-control" readonly style="background-color:#fff">
                </div>
                
                <div class="col-lg-6 col-md-6 pl-0 pr-0 mt-3 bonuses-buttons">
                     <button
                        class="btn btn-share-link mb-2 ml-1 mr-3"
                        id="share-promocode"
                        data-promocode="{{ $bonuses->promocode ?? ''}}">
                        Поделиться ссылкой
                    </button>

                    <button
                        class="btn btn-copy-code mr-2 mb-2"
                        id="copy-promocode"
                        data-promocode="{{ $bonuses->promocode ?? ''}}">
                        Скопировать код

                    </button>

                    <!--<div
                          class="ya-share2" 
                          data-curtain 
                          data-size="s" 
                          data-shape="round" 
                          data-limit="0" 
                          data-more-button-type="short"
                          data-services="vkontakte,facebook,telegram,whatsapp"
                          data-title= "Приглашаю тебя в Capsula! Сделай первый заказ со скидкой 500 рублей на услуги подбора!" 
                          data-url="https://anketa.thecapsula.link/{{ $bonuses->promocode ?? ''}}"
                       ></div>-->
                </div>    

                <ul class="mt-3 pl-1">
                   <li style="display:flex">
                        <span class="mr-1">
                            <i class="fa fa-circle mr-1" style="color:#28c76f" style="width:23px; height: 23px;" ></i>
                        </span>     
                        <span>Поделитесь с друзьями ссылкой или промокодом, они получат скидку 500 ₽ на услуги стилиста.</span></li>
                   <li style="display:flex"> 
                        <span class="mr-1">
                            <i class="fa fa-circle mr-1" style="color:#28c76f" style="width:23px; height: 23px;" class="mr-2"></i>
                        </span> 
                        <span>Ваш друг делает первый заказ.</span></li>
                   <li style="display:flex">
                    <span class="mr-1">
                        <i class="fa fa-circle mr-1" style="color:#28c76f" style="width:23px; height: 23px;" class="mr-2"></i>
                    </span> 
                    <span>Вы получаете 500 ₽ на ваш счет. Все счастливы!</span></li>
                </ul>

                <!--<a href="" id= "share-promocode" >Поделиться ссылкой <i class="feather icon-share-2"></i></a>-->

            </div><!--card-body-->
        </div><!--card-content-->
    </div><!--card-->

    <div class="col-lg-8 col-sm-10" style="margin-bottom: 30px;">
        <b class="bonuses-bottom-balance">Ваш баланс: {{ $bonuses->points ?? 0 }} {{ $bonus_text }}</b><br>
        Вы сможете списать их в момент оплаты 
    </div>
</div>         
@endsection

@section('scripts')
<script src="https://yastatic.net/share2/share.js"></script>
<script>

$(function () {  
    

    $('#copy-promocode').on('click', function(){
      
      var 
         text = $(this).data('promocode'),
         $tmp_input = $("<input>");

      $("body").append($tmp_input);
      $tmp_input.val(text).select();
      document.execCommand("copy");
      $tmp_input.remove();
      $(this).html('код скопирован');

      setTimeout(()=>{
         $(this).html('скопировать код');
      }, 2000);

   });


    //самописный вариант поделиться ссылкой
    $('#share-promocode').on('click', event => {

         if (navigator.share) {
          navigator.share({
            title: 'thecapsula.ru',
            text: 'Приглашаю тебя в Capsula! Сделай первый заказ со скидкой 500 рублей на услуги подбора!',
            url: '{{config('config.ANKETA_URL')}}/?rf={{ $bonuses->promocode ?? ''}}'
          }).then(() => {
            // alert('Thanks for sharing!');
          })
          .catch(console.error);
        } else {

         return;
          // alert('Web Share API не поддерживается');
        }
    });

    $('#input-link').focus(function(){
        if(this.value == this.defaultValue){
            this.select();
        }
    });

});



</script>
@endsection

@section('css')
    <style>
        .bonuses-main-wrapper {
            background: linear-gradient(to bottom, #00e3a7 50%, white 50%);
            padding-top: 30px; 
            margin-bottom:30px;
        }

        .bonuses-card {
            padding-left: 1rem;
        }

        .bonuses-text-top {
            color:#fff;
            font-size: 2rem;
            text-align: center;
        }

        .bonuses-text-top-comments {
            color:#fff;
            font-size: 1.2rem; 
            margin-bottom: 30px;
            text-align: center;
        }

        .bonuses-bottom-balance{
            font-size: 1.75rem;
        }

        .btn-share-link{
            border-color: #d070ca !important;
            background-color: #d070ca !important;
            color: #fff;
        }

        .btn-share-link:hover{
            color: #fff;!important;
        }

        .btn-copy-code{
            border-color: #e5e5e5 !important;
            background-color: #e5e5e5 !important;
            color: #686666;
        }

        .btn-copy-code:hover{
            color: #686666;
        }

        @media screen and (max-width: 600px) {
            .bonuses-main-wrapper {
                background: #fff;
            }

            .bonuses-card {
                padding-left: 0.5rem;
            }

            .bonuses-text-top {
                color:#2b2b2b;
                font-size: 1.75rem;
                text-align: left;
            }

            .bonuses-text-top-comments {
                color:#000;
                text-align: left;
            }
        }

        @media screen and (max-width: 375px) {

            .bonuses-main-wrapper {
                margin-bottom:10px;
            }

            .bonuses-card .card-body {
                padding-left: 0rem;
                padding-right: 0.3rem;
            }

            .bonuses-text-top {
                font-size: 1.60rem;
            }

            .bonuses-bottom-balance{
                font-size: 1.60rem;
            }

            .btn-share-link.mr-3{
                margin-right: 0.2rem!important;
                color: #fff;!important;
            }

            .btn-share-link.btn{
                padding-left: .3rem;
                padding-right: .3rem;
                font-size: .9rem;
            }
            .btn-copy-code.btn{
                padding-left: .3rem;
                padding-right: .3rem;
                font-size: .9rem;
            }

        }
    </style>    
@endsection
