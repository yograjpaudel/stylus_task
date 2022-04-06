@if(Session::has('success_message'))
    <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-500 px-4 py-3 my-2" role="alert">
        <i class="fas fa-info-circle text-white"></i>
        <p>{{ Session::get('success_message') }}</p>
      </div>
@endif

@if(Session::has('error_message'))
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 my-2" role="alert">
        <p class="font-bold">Be Warned</p>
        <p>{{ Session::get('error_message') }}</p>
      </div>
@endif
