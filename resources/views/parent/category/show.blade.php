@extends('layouts.app')
@section('title', 'Category Show')
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
                <span class="">Category Show</span>
                <a href="{{route('parent.category.index')}}" class="btn btn-success">
                    <i class="fas fa-list mr-2"></i> List
                </a>
            </h4>
            <table class="table table-striped">
                <tr class="bg-secondary">
                    <th>Title</th>
                    <td>{{ $data['row']->title ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Description</th>
                    <td>{!! $data['row']->description ?? 'N/A' !!}</td>
                </tr>
                <tr class="bg-secondary">
                    <th>Image</th>
                    @if($data['row']->image)
                        <td><img src="{{asset('images/category/'.$data['row']->image)}}" width="300" height="240" class="" alt="Photo" /></td>
                    @else
                        <td>N/A</td>
                    @endif
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
                        <a class="btn btn-warning text-light" href="{{ route('parent.category.edit', $data['row']->id) }}" title="Edit"><span class="fas fa-edit"></span> Edit</a>
                    </th>
                    <td>
                        <form action="{{ route('parent.category.destroy', $data['row']->id) }}" class="form-inline" method="post" id="{{'delete-form-'.$data['row']->id}}">
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
