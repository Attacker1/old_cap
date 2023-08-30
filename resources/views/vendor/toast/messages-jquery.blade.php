@if(Session::has('toasts'))
	<!-- Messenger http://github.hubspot.com/messenger/ -->
	<script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
	<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">

	<script>
		$( document ).ready(function() {
			toastr.options = {
				"closeButton": true,
				"newestOnTop": true,
				"positionClass": "toast-bottom-right"
			};

			@foreach(Session::get('toasts') as $toast)
					toastr["{{ $toast['level'] }}"]("{{ $toast['message'] }}", "{{ $toast['title'] }}");
			@endforeach
		});
	</script>
@endif
