@if($page == 'create')
<form action="{{route('parent.category.store')}}" enctype="multipart/form-data" method="POST" class="">
@else
<form action="{{route('parent.category.update', $data['row']->id)}}" enctype="multipart/form-data" method="POST" class="">
    @method('PUT')
@endif
    @csrf
    <div class="">
    <label for="title" class="">Title</label>
        <div class="">
            <input type="text" name="title" id="title" placeholder="Title" class="" value="{{isset($data['row']->title) ? $data['row']->title :''}}">
            @include('parent.includes.single_field_validation_message',['fieldname' => 'title'])
        </div>
    </div>

    <div class="">
        <label for="description" class="">Description</label>
        <div class="">
            <textarea name="description" id="description" rows="4" class="">
                {{isset($data['row']->description) ? $data['row']->description :''}}
            </textarea>
            @include('parent.includes.single_field_validation_message',['fieldname' => 'description'])
        </div>
    </div>    

    <div class="">
    <label for="photo" class="">Image</label>
        <div class="">
            <label for="example-fileinput">Please Upload Image File</label>
            <input type="file" name="photo" id="photo" class="-file">
            @include('parent.includes.single_field_validation_message',['fieldname' => 'photo'])
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