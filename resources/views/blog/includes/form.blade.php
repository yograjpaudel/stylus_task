@if($page == 'create')
<form action="{{route('blog.store')}}" enctype="multipart/form-data" method="POST" class="">
@else
<form action="{{route('blog.update', $data['row']->id)}}" enctype="multipart/form-data" method="POST" class="">
    @method('PUT')
@endif
    @csrf
    <div class="">
        <label for="author_id" class="">Author</label>
        <div class="">
          <select name="author_id" class="select" placeholder="Select Author">
            @forelse ($data['authors'] as $author)
                <option value="{{$author->id}}">{{$author->full_name}}</option>
            @empty
                <option value="">Author list not available!!!</option>
            @endforelse
          </select>
          @include('parent.includes.single_field_validation_message',['fieldname' => 'author_id'])
        </div>
      </div>
    <div class="">
        <label for="title" class="">Title</label>
        <div class="">
            <input type="text" name="title" id="title" placeholder="Title" class="" value="{{isset($data['row']->title) ? $data['row']->title :''}}">
            @include('parent.includes.single_field_validation_message',['fieldname' => 'title'])
        </div>
    </div>

    <div class="">
        <label for="content" class="">Content</label>
        <div class="">
            <textarea name="content" id="content" rows="4" class="">
                {{isset($data['row']->content) ? $data['row']->content :''}}
            </textarea>
            @include('parent.includes.single_field_validation_message',['fieldname' => 'content'])
        </div>
    </div>

    <div class="">
        <label for="category_ids[]" class="">Category</label>
        <div class="">
          <select name="category_ids[]" class="category-select2" multiple>
            @forelse ($data['categories'] as $category)
                <option value="{{$category->id}}">{{$category->title}}</option>
            @empty
                <option value="">Category list not found.</option>
            @endforelse
          </select>
          @include('parent.includes.single_field_validation_message',['fieldname' => 'category_ids'])
        </div>
    </div>

    <div class="">
        <label for="tag_ids[]" class="">Tag</label>
        <div class="">
          <select name="tag_ids[]" class="tag-select2" multiple>
            @forelse ($data['tags'] as $tag)
                <option value="{{$tag->id}}">{{$tag->name}}</option>
            @empty
                <option value="">Tag list not found.</option>
            @endforelse
          </select>
          @include('parent.includes.single_field_validation_message',['fieldname' => 'tag_ids'])
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