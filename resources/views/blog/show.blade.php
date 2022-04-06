@extends('layouts.app')
@section('title', 'Blog Show')
@section('css')
    
@endsection
@section('js')
    <script src="{{asset('./assets/js/softdelete.js')}}"></script>
@endsection
@section('content')
<div class="container">
    <div class="">
        <div class="">
            <h4 class="">
                <span class="">Blog Show</span>
                <a href="{{route('blog.index')}}" class="btn btn-success">
                    <i class="fas fa-list mr-2"></i> List
                </a>
            </h4>
            <table class="table table-striped">
                <tr class="bg-secondary">
                    <th>Title</th>
                    <td>{{ $data['row']->title ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Author</th>
                    <td>{{ $data['row']->author->full_name ?? 'N/A' }}</td>
                </tr>
                <tr class="bg-secondary">
                    <th>Content</th>
                    <td>{!! $data['row']->content ?? 'N/A' !!}</td>
                </tr>
                <tr>
                    <th>Category</th>
                    <td>
                        @forelse ($data['row']->categories as $category)
                            <span class="bg-success text-light rounded py-2 px-2 mx-2">
                                {{ $category->title }}
                            </span>
                        @empty
                            N/A
                        @endforelse
                    </td>
                </tr>
                <tr class="bg-secondary">
                    <th>Tag</th>
                    <td>
                        @forelse ($data['row']->tags as $tag)
                            <span class="bg-info text-light rounded py-2 px-2 mx-2">
                                {{ $tag->name }}
                            </span>
                        @empty
                            N/A
                        @endforelse
                    </td>
                </tr>
                <tr>
                    <th>Status</th>
                    <td>
                        @if($data['row']->status == '1')
                            <span class="text-success">Publish</span>
                        @else
                            <span class="text-danger">Unpublish</span>
                        @endif
                    </td>
                </tr>
                <tr class="bg-secondary">
                    <th>Created Date</th>
                    <td>{{ $data['row']->created_at ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Updated Date</th>
                    <td>{{ $data['row']->updated_at ?? 'N/A' }}</td>
                </tr>
                <tr class="bg-secondary">
                    <th>Created By</th>
                    <td>{{ $data['row']->createdBy->name ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Updated By</th>
                    <td>{{ $data['row']->updatedBy->name ?? 'N/A' }}</td>
                </tr>
                <tr class="bg-secondary">
                    <th>
                        <a class="btn btn-warning text-light" href="{{ route('blog.edit', $data['row']->id) }}" title="Edit"><span class="fas fa-edit"></span> Edit</a>
                    </th>
                    <td>
                        <form action="{{ route('blog.destroy', $data['row']->id) }}" class="form-inline" method="post" id="{{'delete-form-'.$data['row']->id}}">
                            @csrf
                            @method('delete')
                            <button type="submit" onclick = "deleteConfirm(event,{{$data['row']->id}})" class="btn btn-danger" title="Delete">
                                <span class="fas fa-trash text-light"></span> Delete
                            </button>
                        </form>
                    </td>
                </tr>            
            </table>
        </div>
    </div>
</div>
@endsection
