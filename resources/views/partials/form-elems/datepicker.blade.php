<div class="input-group form-group text-center {{@$class}}">
    <div class="col-sm-12 text-center  filter-label">

            <label>{{@$label}}</label>
    </div>

{{--        <div class="input-group-prepend">--}}
{{--            <span class="input-group-text">--}}
{{--                <label for="ff">↓</label>--}}
{{--                <input style="display: none" id="ff" type="checkbox"/>--}}
{{--                <label for="bb">↑</label>--}}
{{--                <input style="display: none" id="bb" type="checkbox"/>--}}
{{--            </span>--}}
{{--        </div>--}}


    <input
            placeholder="{{@$placeholder}}"
            class="form-control"
            rel="date_picker"
            value="{{@$value}}"
            name="{{@$name}}"
            autocomplete="off">
</div>
