<form action="javascript:">

    <input type="hidden" name="page" value="1"/>
    <table id="datatable"
           class="no-footer table table-hover table-striped table-bordered dataTable dtr-inline" style="width: 100%;">
        <thead>
        <tr>
            @if(@$manage)
                <th>
                    @include('partials.form-elems.text',$filtersData['idLike'])
                </th>
            @else {{--if stylist--}}
            <th>
                @include('partials.form-elems.text',$filtersData['idLike'])
            </th>
            @endif

            <th>
                @include('partials.form-elems.text',$filtersData['clientFullName'])
            </th>
            <th>
                @include('partials.form-elems.select', $filtersData['photos'])
            </th>
            <th>
                @include('partials.form-elems.combo_text_select', array_merge(['label' => 'Соц. сети'], $filtersData['socials'], $filtersData['socialsLike']))
            </th>
            {{--            <th>--}}
            {{--                @include('partials.form-elems.select', $filtersData['photosAndSocials'])--}}
            {{--            </th>--}}
            <th>
                @include('partials.form-elems.select', $filtersData['sizesTop'])
            </th>
            <th>
                @include('partials.form-elems.select', $filtersData['sizesBottom'])
            </th>
            <th>

                <div class="input-group form-group text-center">
                    <div class="col-sm-12 text-center">
                        <label> Управление </label>
                    </div>
                    <a href="javascript:" id="filter-clear" style="margin: 0 auto;">
                        <button type="button" class="btn btn-sm btn-bg-light">
                            Очистить фильтр
                        </button>
                    </a>

                </div>
            </th>
        </tr>
        </thead>
        <tbody id="tbody">
        @if(@$data->total())
            @foreach(@$data->items() as $item)
                <tr role="row">

                    <td class="date">
                        {{$item->id}}
                    </td>
                    <td>
                        {{@$item->client->name}} {{@$item->client->second_name}}
                        @if(@$manage)
                            <br/>{{@$item->client->phone}} {{@$item->client->email}}
                        @endif
                        </td>
                    <td class="text-center">
                        @if($item->photos_allow)
                            <span class="text-secondary">Нет</span>
                        @else
                            <span class="text-primary">Да</span>
                        @endif
                    </td>
                    <td style="width: 260px; max-width: 260px;">
                        @if($item->data_social != 'null')
                            {{$item->data_social }}
                        @endif
                    </td>
                    {{--                    <td>--}}
                    {{--                        @if($item->photos_allow && $item->data_social)--}}
                    {{--                            Нет--}}
                    {{--                        @else--}}
                    {{--                            Да--}}
                    {{--                        @endif--}}
                    {{--                    </td>--}}
                    <td>
                        @foreach(explode(',',@trim($item->data_sizes_top, '[]')) as $size)
                            @if($size)
                                <span class="label text-primary">{{$filtersData['sizesTop']['options'][(int)$size] }}</span>
                            @elseif($size === '0')
                                <span class="label text-primary">{{$filtersData['sizesTop']['options'][0] }}</span>
                            @endif

                        @endforeach
                    </td>
                    <td>
                        @foreach(explode(',',@trim($item->data_sizes_bottom, '[]')) as $size)
                            @if($size)
                                <span class="label text-primary">{{$filtersData['sizesBottom']['options'][(int)$size] }}</span>
                            @elseif($size === '0')
                                <span class="label text-primary">{{$filtersData['sizesBottom']['options'][0] }}</span>
                            @endif
                        @endforeach
                    </td>
                    <td>
                        <div style="display:flex;">
                            <a href="/admin/anketa/{{$item->uuid}}" title="Просмотр анкеты"
                               class="ml-5"><i class="fa far fa-eye text-primary"></i>
                            </a>
                            {{--amo--}}
                            @if($item->amo_id)
                                <a href="{{config('config.AMO_URL')}}/leads/detail/{{$item->amo_id}}" title="Ссылка на амо"
                                   target="_blank"
                                   class="ml-5"><i class="fa far fa-link text-primary"></i>
                                </a>
                            @else
                                <a title="Ссылка на амо отсутствует"
                                   class="ml-5"><i class="fa far fa-link text-light"></i>
                                </a>
                            @endif
                            {{--client--}}
                            @if(@$item->client_uuid )
                                <a href="{{route('clients.show',@$item->client_uuid)}}" title="Ссылка на клиента"
                                   target="_blank"
                                   class="ml-5"><i class="fa far fa-user text-primary"></i>
                                </a>
                            @else
                                <a title="Ссылка на клиента отсутствует"
                                   class="ml-5"><i class="fa far fa-user text-light"></i>
                                </a>
                            @endif
                            {{--lead--}}

                            @foreach(@$item->hasLids as $lid)
                                <a href="{{route('leads.edit',@$lid->uuid)}}" title="Ссылка на лид" target="_blank"
                                   class="ml-5"><i class="fa far fa-leaf text-primary"></i>
                                </a>
                            @endforeach
                        </div>


                    </td>
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="7">
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






