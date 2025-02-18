@extends('layouts.app')

@section('styles')

<link rel="stylesheet" href="{{asset('theme/vendor/dropify/dropify.minfd53.css?v4.0.1')}}">
<link rel="stylesheet" href="{{asset('theme/assets/examples/css/advanced/toastr.minfd53.css?v4.0.1')}}">

<style>
    . .form-control-label {
        margin-bottom: 0;
        text-align: left;
    }

</style>
@endsection

@section('content')
<!-- Panel Full Example -->
<div class="panel">
    <div class="panel-heading">
        <h3 class="panel-title">Add New b2b user</h3>
    </div>
    <div class="panel-body">

        <form id="exampleFullForm" autocomplete="off" method="POST" action="{{route('client.store')}}">
            @csrf
            <div class="row row-lg">
                <div class="col-xl-6 ">
                    <div class="form-group  ">
                        <h3>B2B Information </h3>
                        <a href="{{ asset('storage/app/public/report/WRTRVlel5WEPi7Hil68MdpHjN3iPzgNtZJw9P5Ly.png')}}" download="WRTRVlel5WEPi7Hil68MdpHjN3iPzgNtZJw9P5Ly.png"><img src="{{ asset('storage/app/public/report/WRTRVlel5WEPi7Hil68MdpHjN3iPzgNtZJw9P5Ly.png')}}"></a>
                        <label class="col-xl-12 col-md-3 form-control-label">Category
                            <span class="required">*</span>
                        </label>
                        <div class="col-xl-12 col-md-9">
                            <div class="input-group"> 
                                <select class="form-control">
                                    <option>Category 1</option>
                                    <option>Category 2</option>
                                    <option>Category 3</option>
                                    <option>Category 4</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group  ">
                        <label class="col-xl-12 col-md-3 form-control-label">Package
                            <span class="required">*</span>
                        </label>
                        <div class="col-xl-12 col-md-9">
                            <div class="input-group"> 
                                <select class="form-control">
                                    <option>Package 1</option>
                                    <option>Package 2</option>
                                    <option>Package 3</option>
                                    <option>Package 4</option>
                                </select>
                            </div>
                        </div>
                    </div> 
                    
                    <h3>General Information </h3>
                    <div class="form-group  ">
                        <label class="col-xl-12 col-md-3 form-control-label">B2B name
                            <span class="required">*</span>
                        </label>
                        <div class="col-xl-12 col-md-9">
                            <div class="input-group"> 
                                <input type="text" class="form-control" value="{{ old('name') }}" name="name"
                                    placeholder="Jane Doe" required="">
                            </div>
                        </div>
                    </div>
                    <div class="form-group  ">
                        <label class="col-xl-12 col-md-3 form-control-label">Address
                            <span class="required">*</span>
                        </label>
                        <div class="col-xl-12 col-md-9">
                            <div class="input-group"> 
                                <input type="text" class="form-control" value="{{ old('phone') }}" name="phone"
                                    placeholder="Address" required="">
                            </div>
                        </div>
                    </div>

                    <div class="row"> 
                        <div class="col-md-6">
                            <!-- Example Default -->
                            <div class="example-wrap m-0">
                                <h4 class="example-title mb-1">Company Logo</h4>
                                <div class="example m-0" id="picture_upload">
                                    <input type="file" class="dropify" id="company_logo" name="company_logo"
                                        data-plugin="dropify" data-default-file="" />
                                </div>
                            </div>
                            <br>
                            <!-- End Example Default -->
                        </div>
                    </div> 
                </div>
                <div class="col-xl-6 ">
                    <h3>Key Person Information </h3>

                    <div class="form-group  ">
                        <label class="col-xl-12 col-md-3 form-control-label">Name
                           <span class="required">*</span>  
                        </label>
                        <div class="col-xl-12 col-md-9">
                            <div class="input-group">
                                
                                <input type="test" class="form-control" value="{{ old('name') }}" name="name"
                                    placeholder="Jane Doe" required="false">
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group  ">
                        <label class="col-xl-12 col-md-3 form-control-label">Phone number
                            <span class="required">*</span> 
                        </label>
                        <div class="col-xl-12 col-md-9">
                            <div class="input-group">
                                 
                                <input type="email" class="form-control" value="{{ old('phone') }}" name="phone"
                                    placeholder="01XXXXXXXXXX" required="false">
                            </div>
                        </div>
                    </div>

                    <div class="form-group  ">
                        <label class="col-xl-12 col-md-3 form-control-label">Designation
                            <span class="required">*</span> 
                        </label>
                        <div class="col-xl-12 col-md-9">
                            <div class="input-group">
                                
                                <input type="test" class="form-control" value="{{ old('designation') }}" name="designation"
                                    placeholder="Designation" required="false">
                            </div>
                        </div>
                    </div>

                    <div class="form-group  ">
                        <label class="col-xl-12 col-md-3 form-control-label">Email
                            <span class="required">*</span>  
                        </label>
                        <div class="col-xl-12 col-md-9">
                            <div class="input-group">
                                 
                                <input type="email" class="form-control" value="{{ old('email') }}" name="email"
                                    placeholder="email@email.com" required="false">
                            </div>
                        </div>
                    </div>

                    <div class="form-group  ">
                        <label class="col-xl-12 col-md-3 form-control-label">Alternate Phone
                            <span class="required">*</span>  
                        </label>
                        <div class="col-xl-12 col-md-9">
                            <div class="input-group">
                                 
                                <input type="test" class="form-control" value="{{ old('phone') }}" name="phone"
                                    placeholder="01XXXXXXXXXX" required="false">
                            </div>
                        </div>
                    </div>

                    <div class="form-group  ">
                        <label class="col-xl-12 col-md-3 form-control-label">Password
                            <span class="required">*</span>
                        </label>
                        <div class="col-xl-12 col-md-9">
                            <div class="input-group">
                                 
                                <input type="password" class="form-control" name="password" placeholder="Min length 8"
                                    required="">
                            </div>
                        </div>
                    </div>
                    <div class="form-group  ">
                        <label class="col-xl-12 col-md-3 form-control-label">Confirm Password
                            <span class="required">*</span>
                        </label>
                        <div class="col-xl-12 col-md-9">
                            <div class="input-group">
                                 
                                <input type="password" class="form-control" name="cpassword" placeholder="Min length 8"
                                    required="">
                            </div>
                        </div>
                    </div>
                </div>
                <input type="text" name="type" value="affiliation" hidden>

                </div>

                <div class="form-group form-material col-xl-12 text-left padding-top-m">
                    <button type="submit" class="btn btn-primary" id="validateButton1">Submit</button>
                </div>
            </div>
        </form>
    </div>
</div>
{{-- {{var_dump($errors->toArray())}} --}}
{{-- @if($errors) --}}
@foreach ($errors->toArray() as $error)
@php
toastr()->error($error[0])
@endphp

@endforeach
{{-- @endif --}}

{{-- <button class="btn btn-primary" id="warning-alert" data-plugin="toastr" data-message="Sabbir Hossain."
    data-container-id="toast-top-right" data-title="Messages" data-close-button="true" data-tap-to-dismiss="false"
    data-icon-class="toast-just-text toast-warning" role="button">Generate</button> --}}
{{-- <a href="{{route('test')}}">Test</a> --}}
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
            'remove':  'Remove',
            'error':   'Ooops, something wrong happended.'
        }
    });
</script>


@endsection
@endsection