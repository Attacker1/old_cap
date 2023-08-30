<div style="flex-basis: 50%; margin-right: 20px; margin-top: 20px">

    <!--<div class="anketa-head" style="background-color:#e7ebec; border: 1px solid #d2d2d2;">КАПСУЛА</div>-->
    <div class="question-answer-wrapper" style="border: 1px solid #ccc9c9; font-size: 16px; padding: 0px; overflow-x: scroll">
        <div style="display: flex; justify-content: space-between; margin-bottom: 10px;padding: 4px 4px 4px 4px;">

            <div style="flex: 1;min-width:250px"></div>
            @foreach($arr_ankets as $key=>$anketa)
                <div style="flex: 1; font-weight: bold;min-width:250px">Капсула №{{ $anketa['capsula_num'] }} </div>
            @endforeach
        </div>

        <div style="display: flex; justify-content: space-between; margin-bottom: 10px;padding: 4px 4px 4px 4px; font-weight: bold;">
            <?php $num = count($arr_ankets); ?>
            <div style="flex: 1;min-width:250px;background-color: #0feae0;padding: 4px 4px 4px 4px;">КАПСУЛА</div>
            @foreach($arr_ankets as $key=>$anketa)
                <div style="flex: 1; font-weight: bold; min-width:250px;background-color: #0feae0;padding: 4px 4px 4px 4px;"> </div>
                <?php $num--; ?>
            @endforeach
        </div>

        <div style="display: flex; flex-wrap: nowrap; padding: 4px 4px 4px 4px;min-width:300px;margin-bottom: 10px">
            <div style="flex: 1;min-width:250px">Цель подборки</div>
            @foreach($arr_ankets as $key=>$anketa)
                <div style="flex: 1;min-width:250px">
                    @isset($anketa['whatPurpose'])
                        @if(is_string($anketa['whatPurpose']))
                            {{ $anketa['whatPurpose'] ?? '' }}
                        @elseif(is_array($anketa['whatPurpose']))
                            {{ $anketa['whatPurpose'][0] ?? '' }}
                        @endif
                    @endisset
                </div>
            @endforeach
        </div>

        <div style="display: flex; flex-wrap: nowrap; padding: 4px 4px 4px 4px;min-width:300px; margin-bottom: 10px">
            <div style="flex: 1;min-width:250px">Как действуем</div>
            @foreach($arr_ankets as $key=>$anketa)
                <div style="flex: 1;min-width:250px">{{ $anketa['tryOtherOrSaveStyle'] ?? '' }}</div>
            @endforeach
        </div>

        <div style="display: flex; flex-wrap: nowrap; padding: 4px 4px 4px 4px;min-width:300px; margin-bottom: 10px">
            <div style="flex: 1;min-width:250px">НЕ нужны в капсуле</div>
            @foreach($arr_ankets as $key=>$anketa)
                <div style="flex: 1;min-width:250px">{{ $anketa['capsulaNotFirstOfAll'] ?? '' }}</div>
            @endforeach
        </div>

        <div style="display: flex; flex-wrap: nowrap; padding: 4px 4px 4px 4px;min-width:300px; margin-bottom: 10px">
            <div style="flex: 1;min-width:250px">Пожелания по вещам в капсуле</div>
            @foreach($arr_ankets as $key=>$anketa)
                <div style="flex: 1;min-width:250px">{{ $anketa['capsulaFirstOfAll'] ?? ''}}</div>
            @endforeach
        </div>

        <div style="display: flex; flex-wrap: nowrap; padding: 4px 4px 4px 4px;min-width:300px; margin-bottom: 10px">
            <div style="flex: 1;min-width:250px">Комментарий к стилисту</div>
            @foreach($arr_ankets as $key=>$anketa)
                <div style="flex: 1;min-width:250px">{{ $anketa['additionalNuances'] ?? ''}}</div>
            @endforeach
        </div>

        <div style="display: flex; flex-wrap: nowrap; padding: 4px 4px 4px 4px;min-width:300px; margin-bottom: 10px">
            <div style="flex: 1;min-width:250px">Цены</div>
            @foreach($arr_ankets as $key=>$anketa)
                <div style="flex: 1;min-width:250px">
                    {{ $anketa['howMuchToSpendOnBlouseShirt'] ?? '' }}<br>
                    {{ $anketa['howMuchToSpendOnSweaterJumperPullover'] ?? ''}}<br>
                    {{ $anketa['howMuchToSpendOnDressesSundresses'] ?? ''}}<br>
                    {{ $anketa['howMuchToSpendOnJacket'] ?? ''}}<br>
                    {{ $anketa['howMuchToSpendOnJeansTrousersSkirts'] ?? ''}}<br>
                    {{ $anketa['howMuchToSpendOnBags'] ?? ''}}<br>
                    {{ $anketa['howMuchToSpendOnEarringsNecklacesBracelets'] ?? ''}}<br>
                    {{ $anketa['howMuchToSpendOnBeltsScarvesShawls'] ?? ''}}<br>
                </div>
            @endforeach
        </div>

        <div style="display: flex; justify-content: space-between; margin-bottom: 10px;padding: 4px 4px 4px 4px; font-weight: bold;">
            <?php $num = count($arr_ankets); ?>
            <div style="flex: 1;min-width:250px;background-color: #0feae0;padding: 4px 4px 4px 4px;">СТИЛЬ</div>
            @foreach($arr_ankets as $key=>$anketa)
                <div style="flex: 1; font-weight: bold; min-width:250px;background-color: #0feae0;padding: 4px 4px 4px 4px;"> </div>
                <?php $num--; ?>
            @endforeach
        </div>

        <div style="display: flex; flex-wrap: nowrap; padding: 4px 4px 4px 4px;min-width:300px; margin-bottom: 10px">
            <div style="flex: 1;min-width:250px">Стиль в выходные, на работе</div>
            @foreach($arr_ankets as $key=>$anketa)
                <div style="flex: 1;min-width:250px">
                    {{ $anketa['styleOnWeekend'] ?? ''}} <br>
                    {{ $anketa['styleOnWork'] ?? ''}}
                </div>
            @endforeach
        </div>

        <div style="display: flex; flex-wrap: nowrap; padding: 4px 4px 4px 4px;min-width:300px; margin-bottom: 10px">
            <div style="flex: 1;min-width:250px">Цветовая гамма</div>
            @foreach($arr_ankets as $key=>$anketa)
                <div style="flex: 1;min-width:250px">
                    {{ $anketa['choosingPalletes25'] ?? ''}}
                </div>
            @endforeach
        </div>

        <div style="display: flex; flex-wrap: nowrap; padding: 4px 4px 4px 4px;min-width:300px; margin-bottom: 10px">
            <div style="flex: 1;min-width:250px">Стиль джинс, посадка + длина</div>
            @foreach($arr_ankets as $key=>$anketa)
                <div style="flex: 1;min-width:250px">
                    {{ $anketa['modelsJeans'] ?? ''}} / <br>
                    {{ $anketa['trousersFit'] ?? ''}} / <br>
                    {{ $anketa['trouserslength'] ?? ''}}
                </div>
            @endforeach
        </div>

        <div style="display: flex; flex-wrap: nowrap; padding: 4px 4px 4px 4px;min-width:300px; margin-bottom: 10px">
            <div style="flex: 1;min-width:250px">Юбки, платья</div>
            @foreach($arr_ankets as $key=>$anketa)
                <div style="flex: 1;min-width:250px">
                    {{ $anketa['dressesType'] ?? ''}}
                </div>
            @endforeach
        </div>

        <div style="display: flex; flex-wrap: nowrap; padding: 4px 4px 4px 4px;min-width:300px; margin-bottom: 10px">
            <div style="flex: 1;min-width:250px">Бижутерия</div>
            @foreach($arr_ankets as $key=>$anketa)
                <div style="flex: 1;min-width:250px">
                    {{ $anketa['earsPierced'] ?? ''}} /
                    {{ $anketa['bijouterie'] ?? ''}} /
                    {{ $anketa['jewelry'] ?? ''}}
                </div>
            @endforeach
        </div>

    </div>
</div>
