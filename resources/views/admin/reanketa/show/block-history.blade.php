<div style="flex-basis: 50%; margin-right: 20px; margin-top: 20px">
    <!--<div class="anketa-head" style="background-color:#e7ebec; border: 1px solid #d2d2d2;">КАПСУЛА</div>-->
    <div class="question-answer-wrapper" style="border: 1px solid #ccc9c9; font-size: 16px; padding: 0px; overflow-x: scroll">
        <div style="display: flex; justify-content: space-between; margin-bottom: 10px;padding: 4px 4px 4px 4px;">
            <div style="flex: 1; font-weight: bold;min-width:120px">Дата анкеты</div>
            <div style="flex: 1; font-weight: bold;min-width:120px">Анкета</div>
            <div style="flex: 1; font-weight: bold;min-width:120px">Дата сделки</div>
            <div style="flex: 1; font-weight: bold;min-width:120px">Amo</div>
            <div style="flex: 1; font-weight: bold;min-width:250px">Стилист</div>
            <div style="flex: 1; font-weight: bold;min-width:250px">Номер капсулы</div>
            <div style="flex: 1; font-weight: bold;min-width:120px">Обратная связь</div>
            <div style="flex: 1; font-weight: bold;min-width:120px">Записка</div>
        </div>

        @foreach($arr_history as $history)
            <div style="display: flex; flex-wrap: nowrap; padding: 4px 4px 4px 4px;min-width:300px;margin-bottom: 10px">
                <div style="flex: 1;min-width:120px">{{ $history['anketa_date'] ?? '' }}</div>
                <div style="flex: 1;min-width:120px">
                    @if($history['anketa_url'] != '')
                        @if($history['anketa_url'] != "повторная")
                            <a href="{{ $history['anketa_url'] ?? '' }}" target="_blank">анкета</a>
                        @else
                            повторная
                        @endif
                    @endif
                </div>
                <div style="flex: 1;min-width:120px">{{ $history['lead_date'] ?? '' }}</div>
                <div style="flex: 1;min-width:120px">
                    @if(!empty($history['amo']))
                        <a href="https://thecapsula.amocrm.ru/leads/detail/{{ $history['amo'] }}" target="_blank">
                            {{ $history['amo'] }}</a>
                    @endif
                </div>
                <div style="flex: 1;min-width:250px">{{ $history['stylist'] ?? '' }}</div>
                <div style="flex: 1;min-width:250px"> Капсула №{{ $history['capsula_num'] ?? '?' }}</div>

                <div style="flex: 1;min-width:120px">
                    @if($history['os_url'] != '')
                        <a href="{{ $history['os_url'] ?? '' }}" target="_blank">обратная связь</a>
                    @endif
                </div>
                <div style="flex: 1;min-width:120px">
                    @if( $history['note_url']!='' )
                        <a href="{{ $history['note_url'] ?? '' }}" target="_blank">записка</a>
                    @endif
                </div>
            </div>
        @endforeach

    </div>
</div>
