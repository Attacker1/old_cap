<div style="flex-basis: 50%; margin-right: 20px">
    <div class="anketa-head" style="background-color:#f7d9e0; border: 1.5px solid #e43131;">СТРОГИЕ НЕТ</div>
    <div class="question-answer-wrapper" style="border: 1px solid #ccc9c9; font-size: 16px">
        <div class="row" style="margin-left: 0px; margin-right: 0px">
            <div class="col-4">Цвета</div>
            <div class="col-8">@foreach($strogie_net['noColor'] as $item)
                    <span @isset($item['last']) style="color: #cf2c1c" @endisset>{{ @$item['text'] . ', ' }}</span>
                @endforeach
            </div>
        </div>

        <div class="row" style="margin-left: 0px; margin-right: 0px; margin-top: 10px">
            <div class="col-4">Принты</div>
            <div class="col-8">@foreach($strogie_net['printsDislike'] as $item)
                    <span @isset($item['last']) style="color: #cf2c1c" @endisset>{{ @$item['text'] . ', ' }}</span>
                @endforeach
            </div>
        </div>

        <div class="row" style="margin-left: 0px; margin-right: 0px; margin-top: 10px">
            <div class="col-4">Аксессуары</div>
            <div class="col-8">@foreach($strogie_net['capsulaNotWantAccessories'] as $item)
                    <span @isset($item['last']) style="color: #cf2c1c" @endisset>{{ @$item['text'] . ', ' }}</span>
                @endforeach
            </div>
        </div>

        <div class="row" style="margin-left: 0px; margin-right: 0px; margin-top: 10px">
            <div class="col-4">Ткани</div>
            <div class="col-8">@foreach($strogie_net['fabricsShouldAvoid'] as $item)
                    <span @isset($item['last']) style="color: #cf2c1c" @endisset>{{ @$item['text'] . ', ' }}</span>
                @endforeach
            </div>
        </div>

        <div class="row" style="margin-left: 0px; margin-right: 0px; margin-top: 10px">
            <div class="col-4">Скрыть</div>
            <div class="col-8">@foreach($strogie_net['bodyPartsToHide'] as $item)
                    <span @isset($item['last']) style="color: #cf2c1c" @endisset>{{ @$item['text'] . ', ' }}</span>
                @endforeach
            </div>
        </div>


    </div><!--question-answer-wrapper-->
</div>