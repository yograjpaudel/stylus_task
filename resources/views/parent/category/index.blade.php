@extends('layouts.app')
@section('title', 'Category List')
@section('css')
@endsection

@section('js')   
    <script src="{{asset('./assets/js/softdelete.js')}}"></script>
@endsection
@section('content')
    <div class="container">
        <div class="p-2">
            @include('parent.includes.flash_message')
            <div class="p-2">   
                <h4 class="">
                    <span class="">Category List</span>
                    <a href="{{route('parent.category.create')}}" class="btn btn-primary">
                        <i class="fas fa-pen mr-2"></i> Create
                    </a>
                    <a href="{{route('parent.category.trash')}}" class="btn btn-danger">
                        <i class="fas fa-trash mr-2"></i> Trash
                    </a>
                </h4>
                
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th data-priority="1">S.N.</th>
                            <th data-priority="2">Title</th>
                            <th data-priority="3">Description</th>
                            <th data-priority="4">Created At</th>
                            <th data-priority="5">Status</th>
                            <th data-priority="6">Action</th>
                        </tr>
                    </thead>
                    <tbody>               
                        @forelse ($data['rows'] as $row)
                        <tr class="text-center">
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $row->title }}</td>
                            <td>{!! $row->description ?? 'N/A' !!}</td>
                            <td>{{ $row->created_at ? $row->created_at->format('Y-m-d'): 'N/A' }}</td>
                            <td>
                                @if($row->status == '1')
                                    <span class="text-success">Publish</span>
                                @else
                                    <span class="text-danger">Unpublish</span>
                                @endif
                            </td>
                            <td class="">
                                <a class="btn btn-info" href="{{ route('parent.category.show', $row->id) }}" title="View Details">
                                    <span class="fas fa-eye text-light"></span>
                                </a>
                                <a class="btn btn-warning" href="{{ route('parent.category.edit', $row->id) }}" title="Edit">
                                    <span class="fas fa-edit text-light"></span>
                                </a>
                                
                                <form action="{{ route('parent.category.destroy', $row->id) }}" class="form-inline" method="post" id="{{'delete-form-'.$row->id}}">
                                    @csrf
                                    @method('delete')
                                    <button type="submit" onclick = "deleteConfirm(event,{{$row->id}})" class="btn btn-danger" title="Delete">
                                        <span class="fas fa-trash text-light"></span>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-danger">Data not found</td>
                        </tr>
                        @endforelse
                    </tbody> 
                </table>
            </div>  
        </div>
    </div>
@endsection
