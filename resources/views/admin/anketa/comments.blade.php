
@foreach($anketaComments as $commentItem)
	<div class="card card-custom col-lg-12 mb-6">
		<div class="card-header">
			<div class="card-title">
				{{$commentItem->user->name}}
			</div>	
		</div>
    	<div class="card-body">
			{!!$commentItem->content!!}
		</div>
	</div>		
@endforeach