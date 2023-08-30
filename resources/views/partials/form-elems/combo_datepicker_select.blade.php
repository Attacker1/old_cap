<div class="col-sm-12 form-group text-center {{@$class}}" style="{{@$style}}">
    <div class="col-sm-12 text-center filter-label">
        <label>{{@$label}}</label>
        @if(@$order)

            <a href="javascript:" data-orderBy="{{@$order}}" data-direction="ASC"
               class="label label-sm @if(request('orderBy') == @$order && request('orderDirection') == 'ASC') active @endif  sort-order"
            >↓</a>
        @endif

        @if(@$order)
            <a href="javascript:" data-orderBy="{{@$order}}" data-direction="DESC"
               class="label label-sm @if(request('orderBy') == @$order && request('orderDirection') == 'DESC') active @endif sort-order"
            >↑</a>
        @endif

    </div>
    <div class="row">
        <div class="col-sm-5 text-center p-0">
            <select name="{{$nameSelect}}" class="form-control">
                <option selected value="">{{$placeholderSelect}}</option>
                @if(!empty($options))
                    @foreach($options as $k => $v)
                        <option value="{{$k }}">{{ @$v }}</option>
                    @endforeach
                @endif
            </select>
        </div>
        <div class="col-sm-7 text-center p-0">
            <input
                    placeholder="{{@$placeholderPicker}}"
                    class="form-control"
                    rel="date_picker"
                    value="{{@$valuePicker}}"
                    name="{{@$namePicker}}"
                    autocomplete="off">
        </div>
    </div>
</div>

