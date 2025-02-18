@extends('layouts.app')

@section('styles')

<link rel="stylesheet" href="{{asset('theme/vendor/dropify/dropify.minfd53.css?v4.0.1')}}">
<link rel="stylesheet" href="{{asset('theme/assets/examples/css/advanced/toastr.minfd53.css?v4.0.1')}}">

<style>
    .form-horizontal .form-control-label {
        margin-bottom: 0;
        text-align: left;
    }
</style>
@endsection

@section('content')
<!-- Panel Full Example -->
<div class="page-header pl-20 pr-20 pb-20 pt-0">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Sliders</li>
    </ol>
    <h1 class="page-title">Sliders</h1>
    <div class="page-header-actions">
        <a class="btn btn-sm btn-primary btn-round waves-effect waves-classic" href="{{route('slider.create')}}">
            <i class="icon md-plus" aria-hidden="true"></i>
            <span class="hidden-sm-down">Create New Page</span>
        </a>
    </div>
</div>

<div class="panel">
    <div class="panel-heading">
        <h3 class="panel-title">All Sliders</h3>
    </div>
    <div class="panel-body">
        <div class="row card card-body">
            <div class="example table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>SL</th>
                            <th>Name</th>
                            <th>Image</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(!empty($sliders))
                        @foreach ($sliders as $key => $slider)
                        <tr>
                            <td>{{($key+1)}}</td>
                            <td>{{$slider->name}}</td>
                            <td>
                                <a href="{{$slider->image_url}}" target="_blank">{{$slider->image_url}}</a>
                            </td>
                            <td>
                                <form action="{{route('slider.destroy',$slider->id)}}" method="POST">
                                    @csrf @method('delete')
                                    <button onClick="return confirm('Are you sure you want to delete?')" class="btn btn-xs btn-danger"><i class="icon md-delete"></i></button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- {{var_dump($errors->toArray())}} --}} {{-- @if($errors) --}} @foreach ($errors->toArray() as $error) @php toastr()->error($error[0]) @endphp @endforeach {{-- @endif --}} {{--
<button class="btn btn-primary" id="warning-alert" data-plugin="toastr" data-message="Sabbir Hossain." data-container-id="toast-top-right" data-title="Messages" data-close-button="true" data-tap-to-dismiss="false" data-icon-class="toast-just-text toast-warning" role="button">Generate</button> --}} {{-- <a href="{{route('test')}}">Test</a> --}}
<!-- End Panel Full Example -->

@section('scripts')

<script src="{{asset('theme/vendor/toastr/toastr.minfd53.js?v4.0.1')}}"></script>
<script src="{{asset('theme/assets/js/Plugin/toastr.minfd53.js?v4.0.1')}}"></script>
<script src="{{asset('theme/vendor/dropify/dropify.minfd53.js?v4.0.1')}}"></script>
<script src="{{asset('theme/js/Plugin/dropify.minfd53.js?v4.0.1')}}"></script>

<script>
    $('.dropify').dropify({
        messages: {
            'default': 'Drag and drop a image here or click',
            'replace': 'Drag and drop or click to replace',
            'remove': 'Remove',
            'error': 'Ooops, something wrong happended.'
        }
    });
</script>
@endsection
@endsection