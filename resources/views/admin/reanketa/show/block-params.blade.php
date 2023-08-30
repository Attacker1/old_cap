<div style="flex-basis: 50%; margin-top: 20px">
    <div class="anketa-head" style="background-color:#e7fcf7; border: 1.5px solid #3699ff;">ПАРАМЕТРЫ</div>
    <div class="question-answer-wrapper" style="border: 1px solid #3699ff;  font-size: 16px">
        <div class="row" style="margin-left: 0px; margin-right: 0px">
            <div class="col-4">Рост, вес</div>
            <div class="col-8">
                <span @isset($arr_params['bioHeight']['last']) style="color: #5aa3af" @endisset>
                    {{ $arr_params['bioHeight']['answer'] }}
                </span>
                <span @isset($arr_params['bioWeight']['last']) style="color: #5aa3af" @endisset>
                    @if($arr_params['bioHeight']['answer'] && $arr_params['bioWeight']['answer']), @endif
                    {{ $arr_params['bioWeight']['answer'] }}
                </span>

            </div>
        </div>

        <div class="row" style="margin-left: 0px; margin-right: 0px; margin-top: 10px">
            <div class="col-4">Возраст, деятельность</div>
            <div class="col-8">
                <span @isset($arr_params['bioBirth']['last']) style="color: #5aa3af" @endisset>
                    {{ $arr_params['bioBirth']['answer'] }}
                </span>
                <span @isset($arr_params['occupation']['last']) style="color: #5aa3af" @endisset>
                    @if($arr_params['bioBirth']['answer'] && $arr_params['occupation']['answer']), @endif
                    {{ $arr_params['occupation']['answer'] }}
                </span>

            </div>
        </div>

        <div class="row" style="margin-left: 0px; margin-right: 0px; margin-top: 10px">
            <div class="col-4">Цвет волос</div>
            <div class="col-8">
                <span @isset($arr_params['hairColor']['last']) style="color: #5aa3af" @endisset>
                    {{ $arr_params['hairColor']['answer'] }}
                </span>
            </div>
        </div>

        <div class="row" style="margin-left: 0px; margin-right: 0px; margin-top: 10px">
            <div class="col-4">Размер верха</div>
            <div class="col-8">
                <span @isset($arr_params['sizeTop']['last']) style="color: #5aa3af" @endisset>
                    {{ $arr_params['sizeTop']['answer'] }}
                </span>
                <span @isset($arr_params['aboutTopStyle']['last']) style="color: #5aa3af" @endisset>
                    @if($arr_params['sizeTop']['answer'] && $arr_params['aboutTopStyle']['answer']), @endif
                    {{ $arr_params['aboutTopStyle']['answer'] }}
                </span>
            </div>
        </div>

        <div class="row" style="margin-left: 0px; margin-right: 0px; margin-top: 10px">
            <div class="col-4">Размер низа</div>
            <div class="col-8">
                <span @isset($arr_params['sizeBottom']['last']) style="color: #5aa3af" @endisset>
                    {{ $arr_params['sizeBottom']['answer'] }}
                </span>
                <span @isset($arr_params['aboutBottomStyle']['last']) style="color: #5aa3af" @endisset>
                    @if($arr_params['sizeBottom']['answer'] && $arr_params['aboutBottomStyle']['answer']), @endif
                    {{ $arr_params['aboutBottomStyle']['answer'] }}
                </span>
            </div>
        </div>

        <div class="row" style="margin-left: 0px; margin-right: 0px; margin-top: 10px">
            <div class="col-4">Замеры</div>
            <div class="col-8">
                <span @isset($arr_params['bioChest']['last']) style="color: #5aa3af" @endisset>
                    {{ $arr_params['bioChest']['answer'] }}
                </span>/
                <span @isset($arr_params['bioWaist']['last']) style="color: #5aa3af" @endisset>
                    {{ $arr_params['bioWaist']['answer'] }}
                </span>/
                <span @isset($arr_params['bioHips']['last']) style="color: #5aa3af" @endisset>
                    {{ $arr_params['bioHips']['answer'] }}
                </span>,
                <span @isset($arr_params['figura']['last']) style="color: #5aa3af" @endisset>
                    {{ $arr_params['figura']['answer'] }}
                </span>
            </div>
        </div>

        <div class="row" style="margin-left: 0px; margin-right: 0px; margin-top: 10px">
            <div class="col-4">Фото</div>
            <div class="col-8">
                <span @isset($arr_params['socials']['last']) style="color: #5aa3af" @endisset>
                    {{ $arr_params['socials']['answer'] }}
                </span>
            </div>
        </div>

    </div>
</div>