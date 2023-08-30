<form action="#">
    <div class="bulk-div d-none row">
        <div class="col-sm-3">@include('admin.leads.partials-bulk', array_merge(['options' => @$states],@$filtersData['bulkActions']) )</div>
    </div>
</form>

<form action="javascript:">

    <input type="hidden" name="page" value="1"/>
    <input type="hidden" name="orderBy" value="" placeholder=""/>
    <input type="hidden" name="orderDirection" value="" placeholder=""/>
    <table
            class="no-footer table table-hover table-striped table-bordered dataTable dtr-inline " style="width: 100%;">
        <thead>

        <tr>
            <th>
                @include('partials.form-elems.select', array_merge(['options' => @$stylists],@$filtersData['stylist']) )
            </th>
            <th class="text-center">
                Номер сделки
            </th>
            <th class="text-center">
                @include('partials.form-elems.datepicker',@$filtersData['deadline'])
            </th>
            <th>
                @include('partials.form-elems.select', @$filtersData['isCompletedDeadline'] )
            </th>
            <th class="text-center">
                Сделка перешла в статус "Анкета у стилиста"
            </th>
            <th class="text-center">
                Сделка перешла в статус "Подборка составлена"
            </th>
            <th class="text-center">
                Была ли сделка в статусе "Проблема с подбором"
            </th>
            <th>

                <div class="input-group form-group text-center">
                    <div class="col-sm-12 text-center">
                        <label> Управление </label>
                    </div>
                    <a href="javascript:" id="filter-clear" style="margin: 0 auto;">
                        <button type="button" class="btn btn-sm btn-bg-light">
                            Очистить
                        </button>
                    </a>
                    <span class="badge badge-light"
                          style="position: absolute; top: -46px; right: -15px;">всего: {{ $data->total() }}</span>
                </div>

            </th>
        </tr>
        </thead>
        <tbody id="tbody">
        @if($data->total())
            @foreach($data->items() as $item)

                <tr role="row" data-id="{{@$item->uuid}}">

                    {{--СТИЛИСТ--}}
                    <th class="text-center">
                        @if(@$item->stylist_id)
                            {{@$item->stylists->name}}
                        @endif
                    </th>
                    {{--AMO ID--}}
                    <th class="text-center">
                        <a href="{{route('leads.edit')}}/{{@$item->uuid}}">{{@$item->amo_lead_id}}</a>
                    </th>

                    {{--ДЕДЛАЙН--}}
                    <th class="text-danger text-center small">
                        {{@substr(@$item->deadline_at, 0, -3)}}
                    </th>

                    {{--Был ли выполнен дедлайн--}}
                    <th class="text-center small">
                        @if($item->deadline_at)
                            @if(count($item->revisionHistory) > 0)
                                @foreach($item->revisionHistory as $history)
                                    @if($history->key == 'state_id' && $history->new_value == 6)
                                        {{ $history->created_at > $item->deadline_at ? 'Нет' : 'Да' }}
                                        @break
                                    @endif
                                    @if($history->id == $item->revisionHistory[count($item->revisionHistory) - 1]->id && date("Y-m-d H:i:s") > $item->deadline_at)
                                        Нет
                                    @endif
                                @endforeach
                            @else
                                @if(date("Y-m-d H:i:s") > $item->deadline_at)
                                    Нет
                                @endif
                            @endif
                        @endif
                    </th>

                    {{--Дата, когда сделка перешла в анкету у стилиста--}}
                    <th class="text-center small">
                        @if(count($item->revisionHistory) > 0)
                            @foreach($item->revisionHistory as $history)
                                @if($history->key == 'state_id' && $history->new_value == 4)
                                    {{ substr($history->created_at, 0, -3) }}
                                    @break
                                @endif
                            @endforeach
                        @endif
                    </th>

                    {{--Дата, когда анкета перешла в подборку составлена--}}
                    <th class="text-center small">
                        @if(count($item->revisionHistory) > 0)
                            @foreach($item->revisionHistory as $history)
                                @if($history->key == 'state_id' && $history->new_value == 6)
                                    {{ substr($history->created_at, 0, -3) }}
                                    @break
                                @endif
                            @endforeach
                        @endif
                    </th>

                    {{--Была ли сделка в статусе "проблема с подбором"--}}
                    <th class="text-center small">
                        @if($item->states->id == 5)
                            @if(count($item->revisionHistory) > 0)
                                @if($history->key == 'state_id' && $history->new_value == 5)
                                    Да, {{ date_diff($history->created_at, new DateTime())->days . ' дней' }}
                                    @break
                                @endif
                            @else
                                Да, {{ date_diff($item->created_at, new DateTime())->days . ' дней' }}
                            @endif
                        @else
                            @if(count($item->revisionHistory) > 0)
                                @foreach($item->revisionHistory as $history)
                                    @if($history->key == 'state_id' && $history->new_value == 5)
                                        Да,
                                        @foreach($item->revisionHistory as $secondHistory)
                                            @if($secondHistory->key == 'state_id' && $secondHistory->old_value == 5)
                                                {{ date_diff($secondHistory->created_at, $history->created_at)->days . ' дней' }}
                                            @endif
                                        @endforeach
                                        @break
                                    @endif
                                    @if($history->id == $item->revisionHistory[count($item->revisionHistory) - 1]->id)
                                        Нет
                                    @endif
                                @endforeach
                            @else
                                Нет
                            @endif
                        @endif
                    </th>
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="@if(@$manage) 9 @else 7 @endif">
                    <div class="col-sm-12 text-center">
                        <p class="text-danger font-large-1 bold"><b>Варианты отсутствуют!</b> <br/> Попробуйте изменить
                            значения фильтров </p>
                    </div>
                </td>
            </tr>
        @endif
        </tbody>
    </table>
</form>
<div class="dataTables_paginate paging_simple_numbers row" id="datatable_paginate">
    {{@$data->links('partials.pagination.ajax')}}
</div>






