@if(auth()->guard('admin')->user()->hasPermission('anketa-tab-photo-view'))
    @foreach($attachments as $attachment)

        <div class="col-lg-4 col-md-6 col-sm-8 pb-6 mt-3" >
            <div style="position:relative;" class="m-3 p-3">
                <a href="javascript:" class="anketa-tab-photo-delete fas fa  fa-trash-alt text-danger text-danger"
                   data-attachmentUuid="{{$attachment->uuid}}"
                >

                </a>
                <a class="modal-show-foto" data-url="{{asset('storage/'.$attachment->filepath)}}"
                   style="cursor:pointer">
                    <img class="card-img img-fluid mb-1" src="{{asset('storage/'.$attachment->filepath)}}">
                </a>
            </div>
        </div>
    @endforeach
@endif
