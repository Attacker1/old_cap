
<div class="col-sm-12 form-group text-center {{@$class ?? ''}}">
    <div class="col-sm-12 text-center  filter-label">
        <label> {{@$label ?? ''}} </label>
    </div>

    <select
            name="{{$name ?? ''}}"
            class="form-control"
            @if(@$disabled)   disabled   @endif
            style="min-width: 120px">
        <option selected value="">{{$placeholder ?? ''}}</option>

        @if(!empty($options))
            @foreach($options as $k => $v)
                    <option
{{--                            @if($k == request($name)) selected @endif--}}
                            value="{{$k }}"
                    >{{ @$v }}</option>
            @endforeach
        @endif
    </select>
</div>

