<form action="#">
    <div class="bulk-div d-none row">
        <div class="d-flex align-items-center col-sm-5">
            @include('admin.leads.partials-bulk', array_merge(['options' => @$states],@$filtersData['bulkActions']) )

            @if(@$manage)
                {{--Выгрузка для Логсис--}}
                @include('partials.admin.lead.listing.exports.leads_xsl_list', ['title' => 'Выгрузка для Логсис', 'className' => 'lead_listing_downloads'])

                {{--Выгрузка для возвратов Логсис--}}
                @include('partials.admin.lead.listing.exports.leads_xsl_list', ['title' => 'Выгрузка для возвратов Логсис', 'className' => 'lead_listing_downloads_return'])
            @endif
        </div>
    </div>
</form>


<form action="javascript:">

    <input type="hidden" name="page" value="1"/>
    <input type="hidden" name="orderBy" value="" placeholder=""/>
    <input type="hidden" name="orderDirection" value="" placeholder=""/>
    <table
            class="no-footer table table-hover   dataTable dtr-inline " style="width: 100%;">
        <thead>

        <tr>
            <th>
                @include('partials.form-elems.checkbox',['label' => 'Выбрать'])
            </th>
            @if(@$manage)
                <th>
                    @include('partials.form-elems.datepicker',@$filtersData['byDate'])
                </th>
            @endif

            <th>
                @include('partials.form-elems.text',@$filtersData['clientLike'])
            </th>
            <th>
                @include('partials.form-elems.text',@$filtersData['amoLike'])
            </th>
            @if(@$manage)
                <th>
                    @include('partials.form-elems.combo_text_select',array_merge(@$filtersData['isPayment'], $filtersData['paymentSearch']))
                </th>
            @endif
            <th>
                @include('partials.form-elems.select', array_merge(['options' => @$stylists],@$filtersData['stylist']) )
            </th>
            <th>
                @include('partials.form-elems.combo_datepicker_select',array_merge(@$filtersData['deadline'],@$filtersData['isDeadline']))
            </th>
            <th>
                @include('partials.form-elems.select', array_merge(['options' => @$states],@$filtersData['states']) )
            </th>
            <th>
                {{--                @include('partials.form-elems.datepicker', array_merge(['options' => @$states],@$filtersData['deliveryAt']) )--}}
                @include('partials.form-elems.combo_datepicker_select', array_merge(@$filtersData['deliveryAt'],@$filtersData['isDeliveryAt']))
            </th>
            @if(@$manage)
                <th>
                    <div class="col-sm-12 form-group text-center">
                        <div class="col-sm-12 text-center  filter-label">
                            <label> Теги </label>
                        </div>
                        @include('partials.form-elems.multi-select', array_merge(['options' => @$tags],@$filtersData['tags'], ['class' => 'filter-tag', 'selectedTags' => []]) )
                    </div>
                </th>
            @endif
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
                          style="position: absolute; top: -46px; right: -15px;">всего: {{$data->total()}}</span>
                </div>

            </th>
        </tr>
        </thead>
        <tbody id="tbody">
        @if(@$data->total())
            @foreach(@$data->items() as $item)
                <?php
                $selectedTags = [];
                if (!empty($item->tags)) {
                    foreach ($item->tags as $tag) {
                        array_push($selectedTags, $tag->id);
                    }
                }

                if(!empty($item->questionnaire->source) && $item->questionnaire->source == 'reanketa') array_push($selectedTags, 6);
                ?>

                <tr role="row" data-id="{{@$item->uuid}}">
                    {{--Checkboxes --}}
                    <th>
                        <label class="checkbox checkbox-single ">
                            <input type="checkbox" value="" class="checkable bulk-item " data-id="{{ @$item->uuid }}">
                            <span></span>
                        </label>
                    </th>

                    {{--updated_at--}}
                    @if(@$manage)
                        <th class="text-primary small">
                            {{@substr(@$item->created_at, 0, -8)}}
                        </th>
                    @endif

                    {{--Клиент--}}
                    <td>
                        {{@$item->clients->second_name}}
                        {{@$item->clients->name}}
                        {{@$item->clients->patronymic}}
                        @if(@$manage)
                            <br/>
                            {{@$item->clients->phone}}
                            {{@$item->clients->email}}
                        @endif
                    </td>
                    {{--AMO ID--}}
                    <th>{{@$item->amo_lead_id}}</th>
                    {{--ОПЛАТА--}}
                    @if(@$manage)
                        <th>
                            @if(@$item->payments->amount)
                                <button class="btn btn-outline-success btn-sm px-2 ml-1">{{@$item->payments->amount}}</button>
                            @endif
                        </th>
                    @endif
                    {{--СТИЛИСТ--}}
                    <th>
                        @if(@$item->stylist_id)
                            {{@$item->stylists->name}}
                        @endif
                    </th>
                    {{--ДЕДЛАЙН--}}
                    <th class="text-danger small">
                        {{@substr(@$item->deadline_at, 0, -3)}}
                    </th>
                    {{--СТАТУС--}}
                    <th>
                        @if(!is_null(@$item->state_id))
                            {{@$item->states->name}}
                        @endif
                    </th>
                    <th class="text-danger small">
                        {{@substr(@$item->delivery_at, 0, -3)}}
                    </th>
                    {{--ТЕГИ--}}
                    @if(@$manage)
                        <th style="width: 150px">
                            @include('partials.form-elems.multi-select', ['class' => 'lead-select', 'leadTags' => $item->tags, 'selectedTags' => $selectedTags])
                        </th>
                    @endif
                    <td>
                        <a href="{{route('leads.edit')}}/{{@$item->uuid}}" title="Редактирование сделки{{@$item->uuid}}"
                           class="ml-5"><i class="fa far fa-edit text-primary"></i>
                        </a>
                        @if(@$item->payments->lead_id)
                            <a href="{{config('config.APP_SUBDOMAIN_BACKOFFICE')}}/pay/order/{{@$item->payments->lead_id}}"
                               title="Ссылка на оплату" target="_blank"
                               class="ml-5"><i class="fa far fa-link text-primary"></i>
                            </a>
                        @endif
                    </td>
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

<script>
    $(function () {
        // $(document).ready(function () {
        //     $('.select2-selection--multiple').css({'overflow' : 'hidden'});
        // });
        // $('.select2-container').children().css('border','none')
        // $('.select2-selection--multiple').css('cssText', 'overflow: hidden !important;height: auto !important;');


        function formatState(state) {
            const option = $(state.element);
            const color = option.data("color");

            if (!color) {
                return state.text;
            }

            return $(`<span style="color: ${color}">${state.text}</span>`);
        }


        $('.multi-select').select2({
            width: '150px',
            templateResult: formatState,
            templateSelection: formatState,
        });

        $('.lead-select').on('select2:select select2:unselecting', function (e) {
            let uuid;
            let data;
            let type = e.params._type;
            if (type == 'unselecting') {
                uuid = $(this).attr('data-uuid');
                data = e.params.args.data;
            } else if (type == 'select') {
                uuid = $(this).attr('data-uuid');
                data = e.params.data;
            }
            $.ajax({
                type: "POST",
                url: '/admin/leads/list/change-tag',
                data: {
                    'tagId': data.id,
                    'uuidLead': uuid,
                    'type': type
                },
                success: function (response, jk, kl) {
                    if ($('.lead-select').data('select2')) {
                        $('.lead-select').select2('close');
                    }
                },
                error: function (e) {
                    console.log(e);
                }
            })
        });
    })
</script>



