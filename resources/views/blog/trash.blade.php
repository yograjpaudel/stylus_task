@extends('layouts.app')
@section('title', 'Blog Trash List')
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
                    <span class="">Blog Trash List</span>
                    <a href="{{route('blog.index')}}" class="btn btn-success">
                        <i class="fas fa-list mr-2"></i> List
                    </a>
                </h4>
                
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th data-priority="1">S.N.</th>
                            <th data-priority="2">Title</th>
                            <th data-priority="3">Author</th>
                            <th data-priority="4">Deleted At</th>
                            <th data-priority="5">Status</th>
                            <th data-priority="6">Action</th>
                        </tr>
                    </thead>
                    <tbody>               
                        @forelse ($data['rows'] as $row)
                        <tr class="text-center">
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $row->title }}</td>
                            <td>{{ $row->author->full_name ?? 'N/A'}}</td>
                            <td>{{ $row->deleted_at ? $row->deleted_at->format('Y-m-d'): 'N/A' }}</td>
                            <td>
                                @if($row->status == '1')
                                    <span class="text-success">Publish</span>
                                @else
                                    <span class="text-danger">Unpublish</span>
                                @endif
                            </td>
                            <td class="">
                                <form action="{{ route('blog.restore', $row->id) }}" class="" method="post" id="{{'restore-form-'.$row->id}}">
                                    @csrf
                                    <button type="submit" onclick = "restore(event,{{$row->id}})" class="btn btn-success" title="Restore">
                                        <span class="fas fa-trash-restore-alt text-light"></span>
                                    </button>
                                </form>

                                <form action="{{ route('blog.force_delete', $row->id) }}" class="" method="post" id="{{'delete-form-'.$row->id}}">
                                    @csrf
                                    @method('delete')
                                    <button type="submit" onclick = "forceDeleteConfirm(event,{{$row->id}})" class="btn btn-danger" title="Force Delete">
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
