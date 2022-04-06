@extends('layouts.app')
@section('title', 'Category Create')
@section('js')  
<script src="https://cdn.ckeditor.com/ckeditor5/23.0.0/classic/ckeditor.js"></script>
<script>
    ClassicEditor.create( document.querySelector( '#description' ) ).catch( error => {
        console.error( error );
    });
</script>
@endsection
@section('content')
    <div class="container">
        <div class="p-2">
            <div class="p-2">
                <h4 class="">
                    <div class="">
                        Category Create
                    </div>
                    <a href="{{route('parent.category.index')}}" class="btn btn-success">
                        <i class="fas fa-list mr-2"></i> List
                    </a>
                </h4>
                @include('parent.category.includes.form', ['page' => 'create'])
            </div>
        </div>
    </div>
@endsection