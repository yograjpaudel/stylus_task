@if($page == 'create')
<form action="{{route('author.store')}}" method="POST" class="">
@else
<form action="{{route('author.update', $data['row']->id)}}" method="POST" class="">
    @method('PUT')
@endif
    @csrf
    <div class="">
    <label for="full_name" class="">Full Name</label>
        <div class="">
            <input type="text" name="full_name" id="full_name" placeholder="Full Name" class="" value="{{isset($data['row']->full_name) ? $data['row']->full_name :''}}">
            @include('parent.includes.single_field_validation_message',['fieldname' => 'full_name'])
        </div>
    </div>
    
    <label for="email" class="">Email</label>
        <div class="">
            <input type="text" name="email" id="email" placeholder="Email" class="" value="{{isset($data['row']->email) ? $data['row']->email :''}}">
            @include('parent.includes.single_field_validation_message',['fieldname' => 'email'])
        </div>
    </div>

    <div class="">
        <div class="d-flex gap-2 my-4">
            <button type="submit" class="btn btn-primary px-4 py-2">
                <i class="fas fa-save"></i> Save
            </button>
            <button type="reset" class="btn btn-danger  rounded px-4 py-2">
                <i class="fas fa-redo-alt"></i> Reset
            </button>
        </div>
    </div>
</form>