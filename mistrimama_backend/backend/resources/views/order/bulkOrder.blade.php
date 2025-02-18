@extends('layouts.app')

@section('styles')

<link rel="stylesheet" href="{{asset('theme/vendor/dropify/dropify.minfd53.css?v4.0.1')}}">
<link rel="stylesheet" href="{{asset('theme/assets/examples/css/advanced/toastr.minfd53.css?v4.0.1')}}">
@endsection

@section('content')
<div class="page-header pl-20 pr-20 pb-20 pt-0">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item">Order</li>
        <li class="breadcrumb-item active">Create Bulk Order</li>
    </ol>
    <h1 class="page-title">Bulk Order</h1>
</div>
@if (Session::has('Success'))
    <div class="alert alert-success" role="alert">
      {{Session::get('Success')}}
    </div>
@endif

@if (Session::has('Error'))
    <div class="alert alert-danger" role="alert">
      {{Session::get('Error')}}
    </div>
@endif
<div class="panel">
    <div class="panel-body">
        <form method="POST" enctype="multipart/form-data" action="{{route('custom.bulkorder.create')}}">
            @csrf 
            <div class="row">
                <div class="col-md-4">
                    <!-- Example Default -->
                    <div class="example-wrap mb-4">
                        <h4 class="example-title mb-1">Upload File <span class="required">*</span></h4>
                        <div class="example m-0" id="picture_file" @error('photo') style="border: 1px solid #f44336;" @enderror>
                            <input type="file" class="dropify" id="photo" name="picture_file" data-plugin="dropify" data-default-file=""/>
                        </div>
                        @if ($errors->has('photo'))
                            <div class="text-danger">{{ $errors->first('photo') }}</div>
                        @endif
                    </div>
                    <!-- End Example Default -->
                </div>
            </div>
            <button class="btn btn-success" type="submit">Add Service Provider</button>
        </form>
    </div>
</div>





@if($errors)
    @foreach ($errors->toArray() as $error)
        @php
        toastr()->error($error[0])
        @endphp
    @endforeach
@endif


@section('scripts')
<script>



</script>


<script src="{{asset('theme/vendor/toastr/toastr.minfd53.js?v4.0.1')}}"></script>
<script src="{{asset('theme/assets/js/Plugin/toastr.minfd53.js?v4.0.1')}}"></script>
<script src="{{asset('theme/vendor/dropify/dropify.minfd53.js?v4.0.1')}}"></script>



@endsection
@endsection