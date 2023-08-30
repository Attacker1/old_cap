@foreach($tab_reference as $reference)
    <div class="col-sm-4 form-group pb-5 reference-col">
        <a href="javascript:"  data-id="{{$reference->id}}" class="anketa-tab-reference-delete fas fa  fa-trash-alt text-danger text-danger"></a>
        <img src="{{asset($reference->photo) }}" alt="reference photo" style="max-width: 100%"/>

        <p>
            {{$reference->comment}}
        </p>

    </div>
@endforeach
