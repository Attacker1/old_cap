

<div class="input-group form-group text-center {{@$class}}">
    <div class="col-sm-12 text-center  filter-label">
        <label> {{@$label}} </label>
    </div>
{{--    <div class="input-group-prepend">--}}
{{--        <span class="input-group-text">--}}
{{--           <a href="javascript:">↓↑</a>--}}
{{--        </span>--}}
{{--    </div>--}}
    <input
            type="text"
            name="{{$name}}"
            placeholder="{{@$placeholder}}"

            value="{{@$value}}"
            class="form-control"
            autocomplete="off"
            required=""
            aria-label=""
            aria-describedby="name_label"
    >
</div>

