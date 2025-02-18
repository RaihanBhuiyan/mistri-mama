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
        <h3 class="panel-title">User Edit</h3>
    </div>
    <div class="panel-body">

        <form id="exampleFullForm" autocomplete="off" method="POST" a action="{{route('profile.update')}}">
            @csrf
            <input type="hidden" name="id" value="{{$user->id}}">
            <div class="row row-lg">
                <div class="col-xl-6 form-horizontal">
                    <div class="form-group row form-material">
                        <label class="col-xl-12 col-md-3 form-control-label">Full Name
                            <span class="required">*</span>
                        </label>
                        <div class="col-xl-12 col-md-9">
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="icon md-accounts-add" aria-hidden="true"></i>
                                </span>
                                <input type="text" class="form-control" value="{{$user->name}}" name="name"
                                placeholder="Jane Doe" required="">
                            </div>
                        </div>
                    </div>

                    <div class="form-group row form-material">
                        <label class="col-xl-12 col-md-3 form-control-label">Phone Number
                            <span class="required">(Readonly)</span>
                        </label>
                        <div class="col-xl-12 col-md-9">
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="icon md-phone" aria-hidden="true"></i>
                                </span>
                                <input type="text" class="form-control" value="{{$user->phone}}" name="phone"
                                placeholder="017xxxxxxxx" readonly disable>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row form-material">
                        <label class="col-xl-12 col-md-3 form-control-label">Email
                            <span class="required">(Readonly)</span>
                        </label>
                        <div class="col-xl-12 col-md-9">
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="icon md-email" aria-hidden="true"></i>
                                </span>
                                <input type="email" class="form-control" value="{{$user->email}}" name="email"
                                placeholder="email@email.com" readonly disable>
                            </div>
                        </div>
                    </div>


                    <div class="form-group row form-material">
                        <label class="col-xl-12 col-md-3 form-control-label">Address
                            <span class="required">*</span>
                        </label>
                        <div class="col-xl-12 col-md-9">
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="icon md-map" aria-hidden="true"></i>
                                </span>
                                <textarea class="form-control" name="address" value="" rows="2"
                                placeholder="Details Address" required="">{{$user->admin->address}}</textarea>
                            </div>
                        </div>

                    </div>
                    <div class="form-group row form-material">
                        <label class="col-xl-12 col-md-3 form-control-label">Password
                            <span class="required">(If change)</span>
                        </label>
                        <div class="col-xl-12 col-md-9">
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="icon md-lock" aria-hidden="true"></i>
                                </span>
                                <input type="password" autocomplete="off" class="form-control" name="password" placeholder="Min length 8">
                            </div>
                        </div>
                    </div>

                </div>

                <div class="col-xl-6 form-horizontal">
                    <div class="row">
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
                        </div>
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