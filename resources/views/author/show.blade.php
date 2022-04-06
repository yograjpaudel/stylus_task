@extends('layouts.app')
@section('title', 'Author Show')
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
                <span class="">Author Show</span>
                <a href="{{route('author.index')}}" class="btn btn-success">
                    <i class="fas fa-list mr-2"></i> List
                </a>
            </h4>
            <table class="table table-striped">
                <tr class="bg-secondary">
                    <th>Full Name</th>
                    <td>{{ $data['row']->full_name ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td>{{ $data['row']->email ?? 'N/A' }}</td>
                </tr>
                <tr class="bg-secondary">
                    <th>Status</th>
                    <td>
                        @if($data['row']->status == '1')
                            <span class="text-success">Publish</span>
                        @else
                            <span class="text-danger">Unpublish</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>Created Date</th>
                    <td>{{ $data['row']->created_at ?? 'N/A' }}</td>
                </tr>
                <tr class="bg-secondary">
                    <th>Updated Date</th>
                    <td>{{ $data['row']->updated_at ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Created By</th>
                    <td>{{ $data['row']->createdBy->name ?? 'N/A' }}</td>
                </tr>
                <tr class="bg-secondary">
                    <th>Updated By</th>
                    <td>{{ $data['row']->updatedBy->name ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>
                        <a class="btn btn-warning text-light" href="{{ route('author.edit', $data['row']->id) }}" title="Edit"><span class="fas fa-edit"></span> Edit</a>
                    </th>
                    <td>
                        <form action="{{ route('author.destroy', $data['row']->id) }}" class="form-inline" method="post" id="{{'delete-form-'.$data['row']->id}}">
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
