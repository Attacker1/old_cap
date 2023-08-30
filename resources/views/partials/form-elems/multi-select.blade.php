<select class="multi-select {{ $class ?? '' }}" name="{{ $name ?? '' }}" data-uuid="{{@$item->uuid}}" multiple="multiple">
    @foreach($tags as $tag)
        <option {{ (isset($selectedTags) && in_array($tag->id, $selectedTags)) ? 'selected="selected"' : '' }} data-color="{{ $tag->color }}" value="{!! $tag->id !!}">{!! $tag->name !!}</option>
    @endforeach
</select>