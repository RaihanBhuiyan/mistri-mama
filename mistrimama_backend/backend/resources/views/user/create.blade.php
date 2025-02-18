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
<div class="panel">
    <div class="panel-heading">
        <h3 class="panel-title">Create User</h3>
    </div>
    <div class="panel-body">
        <form id="exampleFullForm" autocomplete="off" method="POST" action="{{route('users.store')}}">
            @csrf
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-control-label">Full Name <span class="required">*</span></label>
                        <input type="text" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" value="{{ old('name') }}" name="name" placeholder="Jane Doe">
                        @if ($errors->has('name'))
                            <div class="invalid-feedback">{{ $errors->first('name') }}</div>
                        @endif
                    </div>

                    <div class="form-group">
                        <label class="form-control-label">Phone Number <span class="required">*</span></label>
                        <input  type="tel" pattern="[0-9]*" maxlength="11" minlength="11"  class="form-control {{ $errors->has('phone') ? 'is-invalid' : '' }}" value="{{ old('phone') }}" name="phone" placeholder="017xxxxxxxx">
                        @if ($errors->has('phone'))
                            <div class="text-danger">{{ $errors->first('phone') }}</div>
                        @endif
                    </div>


                    <div class="form-group">
                        <label class="form-control-label">Email <span class="required">*</span></label>
                        <input type="email" class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" value="{{ old('email') }}" name="email" placeholder="email@email.com">
                        @if ($errors->has('email'))
                            <div class="text-danger">{{ $errors->first('email') }}</div>
                        @endif
                    </div>

                    <div class="form-group">
                        <label class="form-control-label">Password <span class="required">*</span></label>
                        <input type="password" class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}" name="password" placeholder="Min length 8">
                    </div>
                    <div class="form-group">
                        <label class="form-control-label">Confirm Password <span class="required">*</span></label>
                        <input type="password" class="form-control {{ $errors->has('password_confirmation') ? 'is-invalid' : '' }}" name="password_confirmation" placeholder="Min length 8">
                        @if ($errors->has('password'))
                            <div class="text-danger">{{ $errors->first('password') }}</div>
                        @endif
                    </div>

                    <div class="form-group">
                        <label class="form-control-label">Address <span class="required">*</span></label>
                        <textarea class="form-control {{ $errors->has('address') ? 'is-invalid' : '' }}" name="address" rows="2" placeholder="Details Address">{{ old('address') }}</textarea>
                        @if ($errors->has('address'))
                            <div class="text-danger">{{ $errors->first('address') }}</div>
                        @endif
                    </div>
                </div>

                <div class="col-md-6">
                    <!-- Example Default -->
                    <div class="example-wrap mb-4" style="width:200px; margin:0 auto">
                        <h4 class="example-title mb-1">Profile Picture <span class="required">*</span></h4>
                        <div class="example m-0" id="picture_upload" @error('photo') style="border: 1px solid #f44336;" @enderror>
                            <input type="file" class="dropify" id="photo" name="photo" data-plugin="dropify" data-default-file=""/>
                        </div>
                        @if ($errors->has('photo'))
                            <div class="text-danger">{{ $errors->first('photo') }}</div>
                        @endif
                    </div>
                    <!-- End Example Default -->
                    <div class="form-group">
                        <label class="form-control-label" for="select">User Role</label>
                        <select class="form-control {{ $errors->has('select') ? 'is-invalid' : '' }}" name="type" id="select">
                            @foreach ($roles as $key => $role)
                            <option value="{{ $key }}">{{ strtoupper($role)}}</option>
                            @endforeach
                        </select>
                        @if ($errors->has('type'))
                            <div class="text-danger">{{ $errors->first('type') }}</div>
                        @endif
                    </div>
                </div>

                <div class="form-group form-material col-xl-12 text-left padding-top-m">
                    <button type="submit" class="btn btn-primary" id="validateButton1">Submit</button>
                </div>
            </div>
        </form>
    </div>
</div>

@section('scripts')

<script src="{{asset('theme/vendor/toastr/toastr.minfd53.js?v4.0.1')}}"></script>
<script src="{{asset('theme/assets/js/Plugin/toastr.minfd53.js?v4.0.1')}}"></script>
<script src="{{asset('theme/vendor/dropify/dropify.minfd53.js?v4.0.1')}}"></script>
<script src="{{asset('theme/js/Plugin/dropify.minfd53.js?v4.0.1')}}"></script>


<script>
$(document).ready(function() {
    var drEvent = $('.dropify').dropify();
    drEvent.on('dropify.afterClear', function(event, element){
        $(this).next('input[type="text"]').remove();
    });
});
</script>


@endsection
@endsection