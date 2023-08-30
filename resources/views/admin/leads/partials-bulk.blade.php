<select
        name="{{ @$name }}"
        class="form-control bg-primary text-white bulk-selection mr-5"
        @if(@$disabled)   disabled   @endif
        style="min-width: 120px" data-id="1111111">

    @if(!empty($options))
        @foreach($options as $k => $v)
                <option value="{{$k }}">{{ @$v }}</option>
        @endforeach
    @endif
</select>


