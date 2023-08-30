
<table cellspacing="2" border="1" cellpadding="5">

    <thead>
    <tr>
        @foreach ($data[0] as $k => $item)
            <th>{{$k}}</th>
        @endforeach

    </tr>
    </thead>
    <tbody>
    @foreach ($data as $arItems)

        <tr>

            @foreach($arItems as $arItem)
                @if(is_array($arItem))
                    <td>
                        @foreach($arItem as $item)
                        @dump($item)
                        @endforeach

                    </td>
                @else
                    <td>{{$arItem}}</td>
                @endif

            @endforeach

        </tr>
    @endforeach
    </tbody>

</table>
