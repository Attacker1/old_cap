@extends('admin-clients.layouts.main')

@section('content')
    <div class="feedback-quize  mb-3 mt-3" style="max-width: 900px" id="feedback-quize">
        <div class="question-wrapper-dialog p-3" style="margin-top: 10px">
            <img class="question-face" src="{{asset('app-assets/images/capsula/question_face.jpg')}}">
            <div class="question-text">Выберите удобный способ оплаты:</div>
            <div class="question-dialog"></div>
        </div>
        <form class="form-choose-method" method="post" action="">
            {{ csrf_field() }}


            <div class="col-lg-6 mb-3 btn-method" href="{{route('admin-clients.payment.method-doli', $feedback_uuid)}}" style="text-decoration: none; color: #848282;">
                <span style="align-self: center; margin-right: 10px">Долями - оплата частями в два клика</span>
                <input class="form-check-input-pay" type="radio" name="pay_method" value="doli" >
                <span style="align-self: center" class="logo-btn-green ">
                    <svg width="33" height="32" viewBox="0 0 33 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M16.4987 0.000976562C7.69941 0.000976562 0.5 7.20039 0.5 15.9997C0.5 24.799 7.69941 31.9984 16.4987 31.9984C25.298 31.9984 32.4974 24.799 32.4974 15.9997C32.6574 7.20039 25.458 0.000976562 16.4987 0.000976562ZM16.6587 27.0388C10.0992 27.0388 6.41952 22.2392 5.77957 17.4396C5.61958 16.7996 6.09954 16.1597 6.73949 16.1597C7.37944 15.9997 8.01939 16.4796 8.01939 17.1196C8.49935 20.9593 11.3791 24.799 16.6587 24.799C22.2582 24.799 24.818 20.7993 25.298 17.1196C25.458 16.4796 25.9379 15.9997 26.5779 16.1597C27.2178 16.3196 27.6978 16.7996 27.5378 17.4396C26.7379 23.1991 22.4182 27.0388 16.6587 27.0388Z" fill="#00E3A7"/>
                    </svg>
                </span>
                <span style="align-self: center;" class="logo-btn-gray on" >
                    <svg width="33" height="32" viewBox="0 0 33 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M16.4987 0.000976562C7.69941 0.000976562 0.5 7.20039 0.5 15.9997C0.5 24.799 7.69941 31.9984 16.4987 31.9984C25.298 31.9984 32.4974 24.799 32.4974 15.9997C32.6574 7.20039 25.458 0.000976562 16.4987 0.000976562ZM16.6587 27.0388C10.0992 27.0388 6.41952 22.2392 5.77957 17.4396C5.61958 16.7996 6.09954 16.1597 6.73949 16.1597C7.37944 15.9997 8.01939 16.4796 8.01939 17.1196C8.49935 20.9593 11.3791 24.799 16.6587 24.799C22.2582 24.799 24.818 20.7993 25.298 17.1196C25.458 16.4796 25.9379 15.9997 26.5779 16.1597C27.2178 16.3196 27.6978 16.7996 27.5378 17.4396C26.7379 23.1991 22.4182 27.0388 16.6587 27.0388Z" fill="#c9c4c4"/>
                    </svg>
                </span>
            </div>

            <div class="info-block mb-3 col-lg-6" style="max-width: 450px">Первые 25% вы оплачиваете при оформлении заказа, остальные три четверти будут списываться с вашей карты каждые две недели до полной оплаты. <br><a href="https://dolyame.ru/#faq" target="_blank">Подробнее о сервисе &rarr;</a></div>

                <div class="col-lg-5 btn-method   on" href="{{route('admin-clients.payment.send', $lead_uuid)}}" class="mb-1 mr-3" style="text-decoration: none; color: #848282;">
                    <span style="align-self: center; margin-right: 10px">Оплатить картой</span>
                    <input class="form-check-input-pay" type="radio" name="pay_method" value="card" checked>
                    <span style="align-self: center" class="logo-btn-green on">
                        <svg width="33" height="32" viewBox="0 0 33 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M16.4987 0.000976562C7.69941 0.000976562 0.5 7.20039 0.5 15.9997C0.5 24.799 7.69941 31.9984 16.4987 31.9984C25.298 31.9984 32.4974 24.799 32.4974 15.9997C32.6574 7.20039 25.458 0.000976562 16.4987 0.000976562ZM16.6587 27.0388C10.0992 27.0388 6.41952 22.2392 5.77957 17.4396C5.61958 16.7996 6.09954 16.1597 6.73949 16.1597C7.37944 15.9997 8.01939 16.4796 8.01939 17.1196C8.49935 20.9593 11.3791 24.799 16.6587 24.799C22.2582 24.799 24.818 20.7993 25.298 17.1196C25.458 16.4796 25.9379 15.9997 26.5779 16.1597C27.2178 16.3196 27.6978 16.7996 27.5378 17.4396C26.7379 23.1991 22.4182 27.0388 16.6587 27.0388Z" fill="#00E3A7"/>
                        </svg>
                    </span>
                    <span style="align-self: center" class="logo-btn-gray ">
                        <svg width="33" height="32" viewBox="0 0 33 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M16.4987 0.000976562C7.69941 0.000976562 0.5 7.20039 0.5 15.9997C0.5 24.799 7.69941 31.9984 16.4987 31.9984C25.298 31.9984 32.4974 24.799 32.4974 15.9997C32.6574 7.20039 25.458 0.000976562 16.4987 0.000976562ZM16.6587 27.0388C10.0992 27.0388 6.41952 22.2392 5.77957 17.4396C5.61958 16.7996 6.09954 16.1597 6.73949 16.1597C7.37944 15.9997 8.01939 16.4796 8.01939 17.1196C8.49935 20.9593 11.3791 24.799 16.6587 24.799C22.2582 24.799 24.818 20.7993 25.298 17.1196C25.458 16.4796 25.9379 15.9997 26.5779 16.1597C27.2178 16.3196 27.6978 16.7996 27.5378 17.4396C26.7379 23.1991 22.4182 27.0388 16.6587 27.0388Z" fill="#c9c4c4"/>
                        </svg>
                    </span>
                </div>
            </div>
            <div class="btn btn-info btn-block btn-submit col-lg-6 mt-2 mb-3" style="background-color: rgb(0, 227, 167) !important; border-color: rgb(0, 227, 167) !important; font-weight: bold;max-width: 450px;">Оплатить</div>
        </form>
@endsection

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>


<script>

    $(window).on('load', function() {

        $('.btn-method').on('click', function(){

            var attr_name =  $(this).find('input').attr('name');
            //checked
            $('input[name="' + attr_name + '"]').parent('.btn-method').removeClass('on');
            $('input[name="' + attr_name + '"]').parent('.btn-method').find('.logo-btn-green').removeClass('on');
            $('input[name="' + attr_name + '"]').parent('.btn-method').find('.logo-btn-gray').addClass('on');
            $(this).addClass('on');
            $(this).find('.logo-btn-green').addClass('on');
            $(this).find('.logo-btn-gray').removeClass('on');
            $('input[name="' + attr_name + '"]').removeAttr('checked');
            $(this).find('input[name="' + attr_name + '"]').prop("checked", true);

        });

        $('.btn-submit').on('click', function () {
            $('.form-choose-method').submit();
        })




    });
</script>

@section('css')
    <link rel="stylesheet" type="text/css" href="{{asset('css/admin-clients/feedback.bundle.css')}}">
    <style>
        .btn-method{
            padding: 0.7rem 1.7rem;
            line-height: 24px;
            font-size: 15px;
            border: 3px solid #8b8ba02b;
            cursor: pointer;
            background-color: #dcdcde2b;
            border-radius: 16px;
            font-weight: bold;
            display: flex;
            justify-content: space-between;
            max-width: 450px
        }

        .btn-method.on{
            border: 3px solid #00e3a7;
        }

        .info-block {
            border-radius: 16px;
            background-color: #5b5b5c2b;
            padding: 0.5rem 1.7rem;
        }
        .form-check-input-pay{
            opacity: 0;
        }

        .logo-btn-green{
            display: none;
        }

        .logo-btn-gray{
            display: none;
        }

        .logo-btn-green.on{
             display: block;
        }

        .logo-btn-gray.on{
            display: block;
        }

        .btn-purple {
            border-color: #e268cd !important;
            background-color: #d070ca !important;
            color: #fff;
            max-width: 450px;
        }

        .btn-purple:hover {
            color: #fff;!important: ;
        }

    </style>
@endsection
