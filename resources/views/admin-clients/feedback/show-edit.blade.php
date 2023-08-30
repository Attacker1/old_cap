    @extends('admin-clients.layouts.main')

@section('content')

    <h5><b>Какие вещи вы хотите оставить?</b></h5>
    <form method="post" action="{{route('admin-clients.feedback.store')}}">
        <div class="feedback-quize  mb-3" style="max-width: 900px" id="feedback-quize">
            {{ csrf_field() }}

            @if ($errors->any())
                <ul class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            @endif

            @foreach($order_items as $order_item)
                <div class="items-wrapper p-3">
                    <div class="product-wrapper">
                        <img class="rounded img-product" src = "{{ $order_item['img_url'] }}" alt="img">

                        <div class="text-product">
                            <span class="text-product-item" style="color: #848282">{{ $order_item['product_name'] }} </span>
                            <span class="text-product-item" style="color: #848282">{{ $order_item['brand_name'] }} </span>
                            <span class="text-product-item"><b>{{ $order_item['product_price'] }} руб.</b></span>
                        </div>
                    </div>

                    <div class="answer-wrapper" style="margin-top: 30px">

                        <div class="answer-item text-center">
                            <div class="answer-button big @if($action == 'edit') @if('buy' == $order_item['fb']->action_result) on @endif @endif" v-on:click="handleBtnBuy({{ $order_item['id'] }})">
                                <input class="form-check-input" type="radio" name="action_result[{{ $order_item['id'] }}]" value="buy" required autofocus @if($action == 'edit') @if('buy' == $order_item['fb']->action_result) checked="checked" @endif @endif>
                                <span>Покупаю</span>
                            </div>
                        </div>

                        <div class="answer-item text-center">
                            <div class="answer-button big  @if($action == 'edit') @if('return' == $order_item['fb']->action_result) on @endif @endif" v-on:click="handleBtnReturn({{ $order_item['id'] }})">
                                <input class="form-check-input" type="radio" name="action_result[{{ $order_item['id'] }}]"  value="return" @if($action == 'edit') @if('return' == $order_item['fb']->action_result) checked="checked" @endif @endif>
                                <span>Возвращаю</span>
                            </div>
                        </div>
                    </div>

                    <div class="mb-2 question-label">
                        Подошел ли Вам <span class="text-nowrap">размер?</span>
                    </div>

                    <div class="answer-wrapper">

                        <div class="answer-item text-center">
                            <div class="answer-button @if($action == 'edit') @if('small' == $order_item['fb']->size_result) on @endif @endif">
                                <input class="form-check-input" type="radio" name="size_result[{{ $order_item['id'] }}]" value="small" @if($action == 'edit') @if('small' == $order_item['fb']->size_result) checked="checked" @endif @endif>
                                <div class="answer-button-sizesmall">Мал</div>
                            </div>
                        </div>

                        <div class="answer-item text-center">
                            <div class="answer-button @if($action == 'edit') @if('ok' == $order_item['fb']->size_result) on @endif @endif">
                                <input class="form-check-input" type="radio" name="size_result[{{ $order_item['id'] }}]"  value="ok" @if($action == 'edit') @if('ok' == $order_item['fb']->size_result) checked="checked" @endif @endif>
                                <div class="answer-button-sizeok">Как раз</div>
                            </div>
                        </div>
                        <div class="answer-item text-center">
                            <div class="answer-button @if($action == 'edit') @if('big' == $order_item['fb']->size_result) on @endif @endif">
                                <input class="form-check-input" type="radio" name="size_result[{{ $order_item['id'] }}]" value="big" @if($action == 'edit') @if('big' == $order_item['fb']->size_result) checked="checked" @endif @endif>
                                <span>Большой</span>
                            </div>
                        </div>
                    </div>

                    <div class="mb-1 question-label">
                        Что вы думаете про полученную вещь?
                    </div>

                    <div class="mt-3 mb-1 question-label">
                        Качество
                    </div>


                    <div class="answer-wrapper">
                        @for($j = 1; $j <= 5; $j++)
                        <div class="answer-item item-number text-center">
                            <div class="answer-button button-number @if($action == 'edit') @if($j == $order_item['fb']->quality_opinion) on @endif @endif">
                                <input class="form-check-input" type="radio" name="quality_opinion[{{ $order_item['id'] }}]" value="{{$j}}" @if($action == 'edit') @if($j == $order_item['fb']->quality_opinion) checked="checked" @endif @endif>
                                <div>{{$j}}</div>
                            </div>
                        </div>
                        @endfor
                    </div>

                    <div class="mt-3 mb-1 question-label">
                        Цена
                    </div>

                    <div class="answer-wrapper">
                        @for($j = 1; $j <= 5; $j++)
                        <div class="answer-item item-number text-center">
                            <div class="answer-button button-number @if($action == 'edit') @if($j == $order_item['fb']->price_opinion) on @endif @endif">
                                <input class="form-check-input" type="radio" name="price_opinion[{{ $order_item['id'] }}]" value="{{$j}}" @if($action == 'edit') @if($j == $order_item['fb']->price_opinion) checked="checked" @endif @endif>
                                <span>{{$j}}</span>
                            </div>
                        </div>
                        @endfor
                    </div>

                    <div class="mt-3 mb-1 question-label">
                        Длина изделия
                    </div>

                    <div class="answer-wrapper">
                        @for($j = 1; $j <= 5; $j++)
                            <div class="answer-item item-number text-center">
                                <div class="answer-button button-number @if($action == 'edit') @if($j == $order_item['fb']->data['product_length']) on @endif @endif">
                                    <input class="form-check-input" type="radio" name="product_length[{{ $order_item['id'] }}]" value="{{$j}}" @if($action == 'edit') @if($j == $order_item['fb']->data['product_length']) checked="checked" @endif @endif>
                                    <span>{{$j}}</span>
                                </div>
                            </div>
                        @endfor
                    </div>

                    <div class="pl-0 mb-1 question-label">
                        Дополнительные комментарии:
                    </div>

                    <textarea class="form-control" rows="4" placeholder="Очень приятная ткань!" name="comments[{{ $order_item['id'] }}]" maxlength="500">@if($action == 'edit') {{ $order_item['fb']->comments }} @endif</textarea>

                    <input type="hidden" name="product_ids[{{ $order_item['id'] }}]">
                </div><!-- items-wrapper -->
            @endforeach

            <div class="question-wrapper-dialog p-3">
                <img class="question-face" src="{{asset('app-assets/images/capsula/question_face.jpg')}}">
                <div class="question-text">В целом, как бы вы оценили работу Capsula? Ощутили ли вы персональный подход?</div>
                <div class="question-dialog"></div>
            </div>

            <div class="answer-wrapper mb-3">
                @for($j = 1; $j <= 5; $j++)
                <div class="answer-item item-number text-center">
                    <div class="answer-button button-number @if($action == 'edit') @if($j == $fb_main->personal_attitude) on @endif @endif">
                        <input class="form-check-input" type="radio" name="personal_attitude" value="{{$j}}" @if($action == 'edit') @if($j == $fb_main->personal_attitude) checked="checked" @endif @endif>
                        <span>{{$j}}</span>
                    </div>
                </div>
                @endfor
            </div>

            <div class="question-wrapper-dialog p-3">
                <img class="question-face" src="{{asset('app-assets/images/capsula/question_face.jpg')}}">
                <div class="question-text">Понравился ли вам стиль подборки?</div>
                <div class="question-dialog"></div>
            </div>

            <div class="answer-wrapper  mb-3">
                @for($j = 1; $j <= 5; $j++)
                <div class="answer-item item-number text-center">
                    <div class="answer-button button-number @if($action == 'edit') @if($j == $fb_main->design) on @endif @endif">
                        <input class="form-check-input" type="radio" name="design" value="{{$j}}" @if($action == 'edit') @if($j == $fb_main->design) checked="checked" @endif @endif>
                        <span>{{$j}}</span>
                    </div>
                </div>
                @endfor
            </div>

            <div class="question-wrapper-dialog p-3">
                <img class="question-face" src="{{asset('app-assets/images/capsula/question_face.jpg')}}">
                <div class="question-text">Хотели бы вы воспользоваться услугами Capsula еще раз?</div>
                <div class="question-dialog"></div>
            </div>

            <div class="answer-wrapper  mb-3">
                @for($j = 1; $j <= 5; $j++)
                <div class="answer-item item-number text-center">
                    <div class="answer-button button-number @if($action == 'edit') @if($j == $fb_main->buy_more) on @endif @endif">
                        <input class="form-check-input" type="radio" name="buy_more" value="{{$j}}" @if($action == 'edit') @if($j == $fb_main->buy_more) checked="checked" @endif @endif>
                        <span>{{$j}}</span>
                    </div>
                </div>
                @endfor
            </div>

            <div class="question-wrapper-dialog p-3">
                <img class="question-face" src="{{asset('app-assets/images/capsula/question_face.jpg')}}">
                <div class="question-text">Оцените внешний вид одежды от 1 до 5, где 1 - вещи очень мятые, неаккуратно сложены, 5 - вещи очень аккуратно сложены, нет помятостей, мешающих примерке)</div>
                <div class="question-dialog"></div>
            </div>

            <div class="answer-wrapper  mb-3">
                @for($j = 1; $j <= 5; $j++)
                <div class="answer-item item-number text-center">
                    <div class="answer-button button-number @if($action == 'edit') @if($j == $fb_main->data['clothing_external_look']) on @endif @endif">
                        <input class="form-check-input" type="radio" name="clothing_external_look" value="{{$j}}" @if($action == 'edit') @if($j == $fb_main->data['clothing_external_look']) checked="checked" @endif @endif>
                        <span>{{$j}}</span>
                    </div>
                </div>
                @endfor
            </div>

            <div class="question-wrapper-dialog p-3">
                <img class="question-face" src="{{asset('app-assets/images/capsula/question_face.jpg')}}">
                <div class="question-text">Какую информацию вы бы хотели видеть в персональной записке от стилиста?</div>
                <div class="question-dialog"></div>
            </div>

            <textarea class="form-control"  rows="4" placeholder="введите текст" name="stylist_note_wanted" maxlength="500">@if($action == 'edit') {{ $fb_main->stylist_note_wanted }} @endif</textarea>

            <div class="question-wrapper-dialog p-3">
                <img class="question-face" src="{{asset('app-assets/images/capsula/question_face.jpg')}}">
                <div class="question-text">Любые комментарии нам и вашему стилисту
                    <img alt="smile" src= "{{ asset('app-assets/images/capsula-clients-admin/emoji.svg')}}" height="20">
                </div>
                <div class="question-dialog"></div>
            </div>

            <textarea class="form-control"  rows="4" placeholder="введите текст" name="other_any_comments" maxlength="500">@if($action == 'edit') {{ $fb_main->other_any_comments }} @endif</textarea>

            <div class="question-wrapper-dialog p-3">
                <img class="question-face" src="{{asset('app-assets/images/capsula/question_face.jpg')}}">
                <div class="question-text">Хотели бы вы попробовать нового стилиста в следующей капсуле?</div>
                <div class="question-dialog"></div>
            </div>

            <div class="answer-wrapper  mb-3">

                <div class="answer-item item-number text-center">
                    <div class="answer-button button-number" style="display: flex; flex-direction: column; justify-content: center">
                        <input class="form-check-input" type="radio" name="new_stylist" value="yes">
                        <span>Да</span>
                    </div>
                </div>

                <div class="answer-item item-number text-center">
                    <div class="answer-button button-number">
                        <input class="form-check-input" type="radio" name="new_stylist"  value="no">
                        <span>Нет, закрепите за мной текущего стилиста</span>
                    </div>
                </div>
            </div>

            <div class="question-wrapper-dialog p-3">
                <img class="question-face" src="{{asset('app-assets/images/capsula/question_face.jpg')}}">
                <div class="question-text">На сколько вероятно, что вы порекомендуете нас своим друзьям и знакомым?</div>
                <div class="question-dialog"></div>
            </div>

            <div class="answer-wrapper  mb-3">
                @for($j = 1; $j <= 5; $j++)
                <div class="answer-item item-number text-center">
                    <div class="answer-button button-number @if($action == 'edit') @if($j == $fb_main->recommended) on @endif @endif">
                        <input class="form-check-input" type="radio" name="recommended" value="{{$j}}" @if($action == 'edit') @if($j == $fb_main->recommended) checked="checked" @endif @endif>
                        <span>{{$j}}</span>
                    </div>
                </div>
                @endfor
            </div>

            <div class="answer-wrapper  mb-3">
                @for($j = 6; $j <= 10; $j++)
                <div class="answer-item item-number text-center">
                    <div class="answer-button button-number  @if($action == 'edit') @if($j == $fb_main->recommended) on @endif @endif">
                        <input class="form-check-input" type="radio" name="recommended" value="{{$j}}" @if($action == 'edit') @if($j == $fb_main->recommended) checked="checked" @endif @endif>
                        <span>{{$j}}</span>
                    </div>
                </div>
                @endfor
            </div>

            <div class="question-wrapper-dialog p-3">
                <img class="question-face" src="{{asset('app-assets/images/capsula/question_face.jpg')}}">
                <div class="question-text">(Опционально) Какова причина, по которой вы поставили такую оценку?</div>
                <div class="question-dialog"></div>
            </div>
            <textarea class="form-control"  rows="4" placeholder="введите текст" name="mark_reason" maxlength="500">@if($action == 'edit') {{ $fb_main->mark_reason }} @endif</textarea>

            <div class="question-wrapper-dialog p-3">
                <img class="question-face" src="{{asset('app-assets/images/capsula/question_face.jpg')}}">
                <div class="question-text">(Опционально) Что мы можем доработать, чтобы ваша оценка улучшилась?</div>
                <div class="question-dialog"></div>
            </div>
            <textarea class="form-control"  rows="4" placeholder="введите текст" name="mark_up_actions" maxlength="500">@if($action == 'edit') {{ $fb_main->mark_up_actions }} @endif</textarea>

            <div class="question-wrapper-dialog p-3">
                <img class="question-face" src="{{asset('app-assets/images/capsula/question_face.jpg')}}">
                <div class="question-text">Когда вы хотите получить свою следующую подборку?</div>
                <div class="question-dialog"></div>
            </div>

            <div class="answer-wrapper wrapper-repeatdate">

                <div style="display: flex; padding-bottom: 1rem">
                    <div class="answer-item item-repeatdate text-center">
                        <div class="answer-button answer-button-repeatdate @if($action == 'edit') @if('week' == $fb_main->repeat_date) on @endif @endif">
                            <input class="form-check-input" type="radio" name="repeat_date" value="week" @if($action == 'edit') @if('week' == $fb_main->repeat_date) checked="checked" @endif @endif>
                            <span>Через неделю (скидка -700 руб. на услуги стилиста)</span>
                        </div>
                    </div>

                    <div class="answer-item item-repeatdate text-center">
                        <div class="answer-button answer-button-repeatdate @if($action == 'edit') @if('month' == $fb_main->repeat_date) on @endif @endif">
                            <input class="form-check-input" type="radio" name="repeat_date"  value="month" @if($action == 'edit') @if('month' == $fb_main->repeat_date) checked="checked" @endif @endif>
                            <span>Через месяц (скидка -700 руб. на услуги стилиста)</span>
                        </div>
                    </div>
                </div>

                <div style="display: flex; padding-bottom: 1rem;">
                    <div class="answer-item item-repeatdate text-center">
                        <div class="answer-button answer-button-repeatdate @if($action == 'edit') @if('two_months' == $fb_main->repeat_date) on @endif @endif">
                            <input class="form-check-input" type="radio" name="repeat_date" value="two_months" @if($action == 'edit') @if('two_months' == $fb_main->repeat_date) checked="checked" @endif @endif>
                            <span> Через 2 месяца (скидка -500 руб. на услуги стилиста)</span>
                        </div>
                    </div>

                    <div class="answer-item answer-item-repeatdate text-center" style="display:flex; flex-direction: column; justify-content: center;">
                        <div class="answer-button answer-button-repeatdate @if($action == 'edit') @if('half_year' == $fb_main->repeat_date) on @endif @endif">
                            <input class="form-check-input" type="radio" name="repeat_date" value="half_year" @if($action == 'edit') @if('half_year' == $fb_main->repeat_date) checked="checked" @endif @endif>
                            <span>Через полгода</span>
                        </div>
                    </div>
                </div>

                <div style="width: 100%; margin-right: 0px">
                    <input type="text" name="repeat_date_own" class="form-control input-repeatedate" placeholder="Введите свой вариант" maxlength="500" @if($action == 'edit') value= "{{$fb_main->repeat_date_own}}" @endif>
                </div>

            </div>



            <div class="question-wrapper-dialog p-3">
                <img class="question-face" src="{{asset('app-assets/images/capsula/question_face.jpg')}}">
                <div class="question-text">(Опционально) Ваша оценка по работе службы доставки</div>
                <div class="question-dialog"></div>
            </div>

            <div class="answer-wrapper  mb-3">
                @for($j = 1; $j <= 5; $j++)
                <div class="answer-item item-number text-center">
                    <div class="answer-button button-number">
                        <input class="form-check-input" type="radio" name="delivery_mark" value="{{$j}}">
                        <span>{{$j}}</span>
                    </div>
                </div>
                @endfor
            </div>

            <div class="answer-wrapper  mb-3">
                @for($j = 6; $j <= 10; $j++)
                <div class="answer-item item-number text-center">
                    <div class="answer-button button-number @if($action == 'edit') @if($j == $fb_main->delivery_mark) on @endif @endif">
                        <input class="form-check-input" type="radio" name="delivery_mark" value="{{$j}}" @if($action == 'edit') @if($j == $fb_main->delivery_mark) checked="checked" @endif @endif>
                        <span>{{$j}}</span>
                    </div>
                </div>
                @endfor
            </div>

            <div class="question-wrapper-dialog p-3">
                <img class="question-face" src="{{asset('app-assets/images/capsula/question_face.jpg')}}">
                <div class="question-text">(Опционально) Ваш комментарий по работе службы доставки</div>
                <div class="question-dialog"></div>
            </div>

            <div class="mb-3 ">
                <textarea class="form-control"  rows="4" placeholder="введите текст" name="delivery_comment" maxlength="500">@if($action == 'edit') {{$fb_main->delivery_comment}} @endif</textarea>
            </div>
            <input type="hidden" name= "lead_id" value="{{$lead_id}}">
            <div style="width: 100%; display: flex; justify-content: center; margin-bottom: 30px">
                <button type="submit"
                        v-if="btn_result === false"
                        class="btn btn-info btn-block"
                        style="background-color: #00e3a7 !important; border-color: #00e3a7 !important; width: 50%; font-weight: bold">
                    Отправить
                </button>

                <button v-else
                        v-on:click.once="paySubmit"
                        type="submit"
                        class="btn btn-info btn-block pay-button-in-fb"
                        style="background-color: #00e3a7 !important; border-color: #00e3a7 !important; width: 50%; font-weight: bold">
                    Оплатить
                </button>
            </div>


        </div>
    </form>

@endsection

@section('scripts')
    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

    <script>
        $(window).on('load', function() {
            $('.answer-button').on('click', function(){
                var attr_name =  $(this).find('input').attr('name');
                //checked
                $('input[name="' + attr_name + '"]').parent('.answer-button').removeClass('on');
                $(this).addClass('on');

                $('input[name="' + attr_name + '"]')[0].checked = false;

                $(this).find('input[name="' + attr_name + '"]')[0].checked = true;

            });


            $('form').submit(function(){
                $(this).find('input[type=submit], button[type=submit]').hide();
                $(this).find('input[type=submit], button[type=submit]').prop('disabled', true);
            });

        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.12/dist/vue.js"></script>
    <script>
        new Vue({
            el: '#feedback-quize',
            data: {
                products_result : {},
                btn_result : false
            },
            methods: {
                handleBtnBuy: function(product_id) {
                    this.products_result[product_id] = 1;
                    this.btn_result = true;
                },

                handleBtnReturn: function(product_id) {
                    delete this.products_result[product_id];
                    this.btn_result = !$.isEmptyObject(this.products_result);
                },

                paySubmit: function() {
                    let env = '{{ config('app.env') ?? 'local' }}';
                    if(env == 'production') {
                        ym(82667803,'reachGoal','goal_7')
                    } else console.log('7');
                }
            }
        });
    </script>
@endsection

@section('css')
    <link rel="stylesheet" type="text/css" href="{{asset('css/admin-clients/feedback.bundle.css')}}">
@endsection
