@extends('layouts.app')
@section('title', 'Blog Create')
@section('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection
@section('js')
<script src="//cdn.ckeditor.com/4.14.1/standard/ckeditor.js"></script>
<script type="text/javascript">
    CKEDITOR.replace('content', {
        filebrowserUploadUrl: "{{route('blog.upload', ['_token' => csrf_token() ])}}",
        filebrowserUploadMethod: 'form',
        allowedContent : true,
        height :300,
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function () {
        $('.category-select2').select2();
        $('.tag-select2').select2();
    });
</script>
@endsection
@section('content')
    <div class="container">
        <div class="p-2">
            <div class="p-2">
                <h4 class="">
                    <div class="">
                        Blog Create
                    </div>
                    <a href="{{route('blog.index')}}" class="btn btn-success">
                        <i class="fas fa-list mr-2"></i> List
                    </a>
                </h4>
                @include('blog.includes.form', ['page' => 'create'])
            </div>
        </div>
    </div>
@endsection