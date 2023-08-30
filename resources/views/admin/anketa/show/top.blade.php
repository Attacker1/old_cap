<div style="display: flex; margin-bottom: 30px">
    <div style="display: flex; margin-right: 20px">
        <span class="symbol symbol-40 symbol-light-success mr-3">
            <span class="symbol-label svg-icon svg-icon-primary svg-icon-2x">
                {{ App\Classes\Theme\Metronic::getSVG("m/media/svg/icons/Communication/Group.svg", "svg-icon-xl") }}
            </span>
        </span>
        <div>
            <b>Клиент:</b><br>
            {{$client->name ?? $anketa['bioName']}}
        </div>
    </div>
    <div style="display: flex">
        <span class="symbol symbol-40 symbol-light-danger mr-3">
            <span class="symbol-label svg-icon svg-icon-danger svg-icon-2x">
                {{ App\Classes\Theme\Metronic::getSVG("m/media/svg/icons/Home/Library.svg", "svg-icon-xl") }}
            </span>
        </span>
        <div>
            <b>Сделки по этой анкете:</b><br>
            {{$amo_ids}}
        </div>
    </div>
</div>
