<div class="tab-pane" id="kt_tab_pane_2" role="tabpanel">
    <div class="row">
        @if(isset($anketa['pallete_arr']))
            @foreach($anketa['pallete_arr'] as $choosingPalletes25)

                <div class="col-lg-3 pb-6">
                    <div style="display: flex; flex-wrap: wrap;">
                        <?php $hexs = explode(',', $choosingPalletes25['pallete']); ?>
                        @foreach($hexs as $value)

                            <div class="anketa-colors mr-2 mb-2"
                                 style="background-color: {{ $value }}"
                                 class="mr-2 mb-2">
                            </div>
                        @endforeach

                    </div>
                    <div>{{ $choosingPalletes25['answer'] }}</div>
                </div>
            @endforeach
        @endif
        @if(isset($anketa['printsDislike_arr']))
            @if(count($anketa['printsDislike_arr']) > 0)
                <div class="col-lg-9 pb-6">
                    <div style="font-size: 16px;font-weight: 700;border-bottom: 2px solid #e43131;margin-bottom: 10px;">
                        Нежелательные принты
                    </div>
                    <div style="display: grid; grid-template-columns: repeat(7, 1fr); gap: 5px">
                        @foreach($anketa['printsDislike_arr'] as $printDislike)

                            <div class="anketa-colors__wrapper"
                                 style="display: flex; flex-direction: column; align-items: center">
                                <div class="anketa-colors"
                                     style=" background-position: center;
                                             background-size: cover;
                                             background-repeat: no-repeat;
                                             margin-bottom: 6px;
                                             background-image: url({{ $printDislike['image'] }})"
                                     class="mr-2 mb-2">
                                </div>
                                <span style="font-size: 12px; text-align: center">{{ $printDislike['answer'] }}</span>
                            </div>

                        @endforeach
                    </div>
                </div>
            @endif
        @endif
        @if(isset($anketa['noColor_arr']))
            @if(count($anketa['noColor_arr']) > 0)
                <div class="col-lg-8 pb-6">
                    <div style="font-size: 16px;font-weight: 700;border-bottom: 2px solid #e43131;margin-bottom: 10px;">
                        Нежелательные цвета
                    </div>
                    <div style="display: grid; grid-template-columns: repeat(7, 1fr); gap: 5px">
                        @foreach($anketa['noColor_arr'] as $noColor)
                            <div class="anketa-colors__wrapper"
                                 style="display: flex; flex-direction: column; align-items: center">
                                <div class="anketa-colors"
                                     style="margin-bottom: 6px;
                                             background-color: {{ $noColor['pallete'] }}"
                                     class="mr-2 mb-2">
                                </div>
                                <span style="font-size: 12px; text-align: center">{!! $noColor['answer'] !!}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        @endisset
    </div>
</div>