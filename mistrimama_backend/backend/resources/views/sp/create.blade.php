@extends('layouts.app')
@section('styles')

<link rel="stylesheet" href="{{asset('theme/vendor/dropify/dropify.minfd53.css?v4.0.1')}}">
<link rel="stylesheet" href="{{asset('theme/assets/examples/css/advanced/toastr.minfd53.css?v4.0.1')}}"> {{--
<link rel="stylesheet" href="{{asset('theme/vendor/jquery-wizard/jquery-wizard.minfd53.css?v4.0.1')}}">
 --}}
<link rel="stylesheet" href="{{asset('theme/assets/select2/select2.min.css')}}">
<style>
    .form-horizontal .form-control-label {
        margin-bottom: 0;
        text-align: left;
    }
    
    .select2-container--default.select2-container--focus .select2-selection--multiple {
        border: solid white 1px !important;
        border-bottom: 1px solid #e0e0e0 !important;
        border-radius: 0px !important;
    }
    
    .select2-container--default .select2-selection--multiple {
        /* background-color: white;
    border: 1px solid #aaa;
    border-radius: 4px;
    cursor: text; */
        border: solid white 1px !important;
        border-bottom: 1px solid #e0e0e0 !important;
        border-radius: 0px !important;
    }
</style>
@endsection

@section('content')
<!-- Panel Full Example -->
<div class="panel">
    <div class="panel-heading">
        <h3 class="panel-title">Add Service Provider</h3>
    </div>
    <div class="panel-body">
        <form method="POST" action="{{route('service-provider.store')}}">
            @csrf
            <div class="form-group">
                <label class="form-control-label">Full Name<span class="required">*</span></label>
                <div class="input-group">
                    <span class="input-group-addon"><i class="icon md-accounts-add" aria-hidden="true"></i></span>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" name="name" placeholder="Jon Doe">
                </div>
                @if($errors->has('name'))
                    <div class="text-danger">{{ $errors->first('name') }}</div>
                @endif
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label class="form-control-label">Phone Number <span class="required">*</span></label>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="icon md-phone" aria-hidden="true"></i>  
                        </span>
                        <span class="input-group-addon">
                            +88
                        </span>
                        <input type="tel" pattern="[0-9]*" maxlength="11" minlength="11" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone') }}" name="phone" placeholder="017xxxxxxxx">
                    </div>
                    @if($errors->has('phone'))
                        <div class="text-danger">{{ $errors->first('phone') }}</div>
                    @endif
                </div>
                <div class="form-group col-md-6">
                    <label class="form-control-label">Alt. Phone Number</label>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="icon md-phone" aria-hidden="true"></i>
                        </span>
                        <span class="input-group-addon">
                            +88
                        </span>
                        <input type="tel" pattern="[0-9]*" maxlength="11" minlength="11" class="form-control" value="{{ old('alt_phone') }}" name="alt_phone" placeholder="017xxxxxxxx">
                    </div>
                    @if ($errors->has('alt_phone'))
                        <div class="text-danger">{{ $errors->first('alt_phone') }}</div>
                    @endif
                </div>
            </div>
            <div class="form-group">
                <label class="form-control-label">Email</label>
                <div class="input-group">
                    <span class="input-group-addon">
                        <i class="icon md-email" aria-hidden="true"></i>
                    </span>
                    <input type="email" class="form-control" value="{{ old('email') }}" name="email" placeholder="email@email.com">
                </div>
                @if ($errors->has('email'))
                    <div class="text-danger">{{ $errors->first('email') }}</div>
                @endif
            </div>
            <div class="form-group">
                <label class="form-control-label">Shop Name</label>
                <div class="input-group">
                    <span class="input-group-addon">
                        <i class="icon md-map" aria-hidden="true"></i>
                    </span>
                    <input class="form-control @error('shop_name') is-invalid @enderror" name="shop_name" value="{{ old('shop_name') }}" placeholder="Shop Name">
                </div>
                @if ($errors->has('shop_name'))
                    <div class="text-danger">{{ $errors->first('shop_name') }}</div>
                @endif
            </div>
            <div class="form-group">
                <label class="form-control-label">Address</label>
                <textarea class="form-control" name="address" placeholder="Details Address">{{ old('address') }}</textarea>
                @if ($errors->has('address'))
                    <div class="text-danger">{{ $errors->first('address') }}</div>
                @endif
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label class="form-control-label" for="select">MFS Type</label>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="icon md-case-check"></i></span>
                        <select class="form-control @error('mfs_type') is-invalid @enderror" name="mfs_type" id="select">
                            <option value="">Select an option</option>
                            <option value="Bkash" {{(old('mfs_type') == 'Bkash') ? 'selected' : '' }}>Bkash</option>
                        </select>
                    </div>
                    @if ($errors->has('mfs_type'))
                        <div class="text-danger">{{ $errors->first('mfs_type') }}</div>
                    @endif
                </div>
                <div class="form-group col-md-6">
                    <div class="form-group">
                        <label class="form-control-label">MFS Number <span class="required">*</span></label>
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="icon md-map" aria-hidden="true"></i>
                            </span>
                            <span class="input-group-addon">
                                +88
                            </span>
                            <input type="tel" pattern="[0-9]*" maxlength="11" minlength="11" class="form-control @error('mfs_no') is-invalid @enderror" value="{{ old('mfs_no') }}" name="mfs_no" placeholder="017xxxxxxxx">
                        </div>
                        @if ($errors->has('mfs_no'))
                            <div class="text-danger">{{ $errors->first('mfs_no') }}</div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label class="form-control-label">NID Number <span class="required">*</span></label>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="icon md-balance-wallet" aria-hidden="true"></i>
                        </span>
                        <input type="number" maxlength="16" minlength="10" class="form-control @error('nid_no') is-invalid @enderror" value="{{ old('nid_no') }}" name="nid_no" placeholder="1229123123312312">
                    </div>
                    @if ($errors->has('nid_no'))
                        <div class="text-danger">{{ $errors->first('nid_no') }}</div>
                    @endif
                </div>
                <div class="form-group col-md-4">
                    <label class="form-control-label">Trade License Number</label>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="icon md-map" aria-hidden="true"></i>
                        </span>
                        <input type="number" maxlength="16" minlength="10" class="form-control @error('trade_lic_no') is-invalid @enderror" value="{{ old('trade_lic_no') }}" name="trade_lic_no" placeholder="129387123">
                    </div>
                    @if ($errors->has('trade_lic_no'))
                        <div class="text-danger">{{ $errors->first('trade_lic_no') }}</div>
                    @endif
                </div>
                <div class="form-group col-md-4">
                    <label class="form-control-label">Tin Certificate</label>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="icon md-map" aria-hidden="true"></i>
                        </span>
                        <input type="number" maxlength="16" minlength="10" class="form-control @error('tin_no') is-invalid @enderror" value="{{ old('tin_no') }}" name="tin_no" placeholder="1231233123">
                    </div>
                    @if ($errors->has('tin_no'))
                        <div class="text-danger">{{ $errors->first('tin_no') }}</div>
                    @endif
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <!-- Example Default -->
                    <div class="example-wrap mb-4">
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
                <div class="col-md-4">
                    <!-- Example Default -->
                    <div class="example-wrap mb-4">
                        <h4 class="example-title mb-1">Secondary Profile Picture</h4>
                        <div class="example m-0" id="photo2_upload">
                            <input type="file" class="dropify" id="photo2" name="photo2" data-plugin="dropify" data-default-file="" />
                        </div>
                        @if ($errors->has('photo2'))
                            <div class="text-danger">{{ $errors->first('photo2') }}</div>
                        @endif
                    </div>
                    <!-- End Example Default -->
                </div>
                <div class="col-md-4">
                    <!-- Example Default -->
                    <div class="example-wrap mb-4">
                        <h4 class="example-title mb-1">NID Front Picture <span class="required">*</span></h4>
                        <div class="example m-0" id="nid_front_upload" @error('nid_front') style="border: 1px solid #f44336;" @enderror>
                            <input type="file" class="dropify" id="nid_front" name="nid_front" data-plugin="dropify" data-default-file=""/>
                        </div>
                        @if ($errors->has('nid_front'))
                            <div class="text-danger">{{ $errors->first('nid_front') }}</div>
                        @endif
                    </div>
                    <!-- End Example Default -->
                </div>
                <div class="col-md-4">
                    <!-- Example Default -->
                    <div class="example-wrap mb-4">
                        <h4 class="example-title mb-1">NID Back Picture <span class="required">*</span></h4>
                        <div class="example m-0" id="nid_back_upload" @error('nid_back') style="border: 1px solid #f44336;" @enderror>
                            <input type="file" class="dropify" id="nid_back" name="nid_back" data-plugin="dropify" data-default-file=""/>
                        </div>
                        @if ($errors->has('nid_back'))
                            <div class="text-danger">{{ $errors->first('nid_back') }}</div>
                        @endif
                    </div>
                    <!-- End Example Default -->
                </div>
                <div class="col-md-4">
                    <!-- Example Default -->
                    <div class="example-wrap mb-4">
                        <h4 class="example-title mb-1">Trade License</h4>
                        <div class="example m-0" id="trade_lic_image_upload">
                            <input type="file" class="dropify" id="trade_lic_image" name="trade_lic_image" data-plugin="dropify" data-default-file="" />
                        </div>
                    </div>
                    <!-- End Example Default -->
                </div>
                <div class="col-md-4">
                    <!-- Example Default -->
                    <div class="example-wrap mb-4">
                        <h4 class="example-title mb-1">TIN Certificate</h4>
                        <div class="example m-0" id="tin_image_upload">
                            <input type="file" class="dropify" id="tin_image" name="tin_image" data-plugin="dropify" data-default-file="" />
                        </div>
                    </div>
                    <!-- End Example Default -->
                </div>
            </div>
            <div class="form-group">
                <label class="form-control-label" for="select">Service Provider Type</label>
                <div class="input-group">
                    <span class="input-group-addon">
                        <i class="icon md-case-check"></i>
                    </span>
                    <select class="form-control @error('type') is-invalid @enderror" name="type" id="type">
                        <option value="esp" {{(old('type') == 'esp') ? 'selected' : '' }}>Enterprise Service Provider</option>
                        <option value="fsp" {{(old('type') == 'fsp') ? 'selected' : '' }}>Freelance Service Provider</option>
                    </select>
                </div>
            </div>
            
            <div class="form-group">
                <label class="form-control-label" for="select">Ability to provide Service</label>
                <div class="input-group" @error('service_category') style="border: 1px solid #f44336;" @enderror>
                    <span class="input-group-addon">
                        <i class="icon md-case-check"></i>
                    </span>
                    <select style="width:100%;" class="form-control" multiple="multiple" name="service_category[]" id="service_category">
                        @if(!empty($categories))
                        @foreach ($categories as $category)
                        <option value="{{$category->id}}" @if(!empty(old('service_category'))) {{ (in_array($category->id, old('service_category'))) ? 'selected' : '' }} @endif>{{$category->name}}</option>
                        @endforeach
                        @endif
                    </select>
                </div>
                @if ($errors->has('service_category'))
                    <div class="text-danger">{{ $errors->first('service_category') }}</div>
                @endif
            </div>
            <div class="row">
                <div class="form-group col-md-4">
                    <label class="form-control-label" for="select">Dvision</label>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="icon md-case-check"></i>
                        </span>
                        <select class="form-control @error('division') is-invalid @enderror" name="division[]" id="division">
                            @if(!empty($divisions))
                            @foreach ($divisions as $division)
                            <option value="{{$division->id}}" {{(old('division') == $division->id) ? 'selected' : '' }}>{{$division->name}}</option>
                            @endforeach
                            @endif
                        </select>
                    </div>
                    @if ($errors->has('division'))
                        <div class="text-danger">{{ $errors->first('division') }}</div>
                    @endif
                </div>
                <div class="form-group has-error col-md-4">
                    <label class="form-control-label" for="select">Cluster</label>
                    <div class="input-group" @error('cluster') style="border: 1px solid #f44336;" @enderror>
                        <span class="input-group-addon">
                            <i class="icon md-case-check"></i>
                        </span>
                        <select style="width:100%;" class="form-control" multiple="multiple" name="cluster[]" id="cluster">
                            @if(!empty($clusters))
                            @foreach ($clusters as $cluster)
                            <option value="{{$cluster->id}}" @if(!empty(old('service_category'))) {{ (in_array($cluster->id, old('cluster'))) ? 'selected' : '' }} @endif>{{$cluster->name}}</option>
                            @endforeach
                            @endif
                        </select>
                    </div>
                    @if ($errors->has('cluster'))
                        <div class="text-danger">{{ $errors->first('cluster') }}</div>
                    @endif
                </div>
                <div class="form-group col-md-4">
                    <label class="form-control-label" for="select">Zones</label>
                    <div class="input-group" @error('zone') style="border: 1px solid #f44336;" @enderror>
                        <span class="input-group-addon">
                            <i class="icon md-case-check"></i>
                        </span>
                        <select style="width:100%;" class="form-control" multiple="multiple" name="zone[]" id="zone">
                            @if(!empty($zones))
                            @foreach ($zones as $zone)
                            <option value="{{$zone->id}}" @if(!empty(old('service_category'))) {{ (in_array($zone->id, old('zone'))) ? 'selected' : '' }} @endif>{{$zone->name}}</option>
                            @endforeach
                            @endif
                        </select>
                    </div>
                    @if ($errors->has('zone'))
                        <div class="text-danger">{{ $errors->first('zone') }}</div>
                    @endif
                </div>
            </div>
            
            <div class="checkbox">
                <label><input type="checkbox" id="serviceDayCheckAll" value="true" name="service_day_check_all">&nbsp;Check / Uncheck All</label>
            </div>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col">Service Days</th>
                            <th scope="col">Start Time</th>
                            <th scope="col">End Time</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($days as $day)
                        <tr>
                            <td>
                                <label class="form-check-label" for="{{ $day }}">
                                    <input type="checkbox" class="form-check-input" id="{{ $day }}" value="{{ $day }}" name="day[]">
                                    {{ $day }}
                                </label>
                            </td>
                            <td>
                                <select class="form-control day-start-time-0 start_time valid" aria-invalid="false" name="start[{{ $day }}][]">
                                    @foreach($start_times as $start_time)
                                    <option value="{{ $start_time }}">{{ $start_time }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <select class="form-control day-end-time-1 end_time valid" aria-invalid="false" name="end[{{ $day }}][]">
                                    @foreach($end_times as $end_time)
                                    <option value="{{ $end_time }}">{{ $end_time }}</option>
                                    @endforeach
                                </select>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <button class="btn btn-success" type="submit">Add Service Provider</button>
        </form>
    </div>
</div>

<!-- End Panel Full Example -->

@section('scripts')


<script src="{{asset('theme/vendor/toastr/toastr.minfd53.js?v4.0.1')}}"></script>
<script src="{{asset('theme/assets/js/Plugin/toastr.minfd53.js?v4.0.1')}}"></script>
<script src="{{asset('theme/vendor/dropify/dropify.minfd53.js?v4.0.1')}}"></script>
<script src="{{asset('theme/js/Plugin/dropify.minfd53.js?v4.0.1')}}"></script>

<script src="{{asset('theme/assets/js/Site.minfd53.js?v4.0.1')}}"></script>
<script src="{{asset('theme/js/Plugin/asscrollable.minfd53.js?v4.0.1')}}"></script>

{{--
<script src="{{asset('theme/vendor/formvalidation/formValidation.minfd53.js')}}"></script>
<script src="{{asset('theme/vendor/formvalidation/framework/bootstrap.minfd53.js')}}"></script>
<script src="{{asset('theme/vendor/matchheight/jquery.matchHeight-minfd53.js')}}"></script>
<script src="{{asset('theme/vendor/jquery-wizard/jquery-wizard.minfd53.js')}}"></script>

<script src="{{asset('theme/js/Plugin/slidepanel.minfd53.js?v4.0.1')}}"></script>
<script src="{{asset('theme/js/Plugin/switchery.minfd53.js?v4.0.1')}}"></script>
<script src="{{asset('theme/js/Plugin/jquery-wizard.minfd53.js?v4.0.1')}}"></script>
<script src="{{asset('theme/js/Plugin/jquery-wizard.minfd53.js?v4.0.1')}}"></script>
<script src="{{asset('theme/js/Plugin/matchheight.minfd53.js?v4.0.1')}}"></script>
<script src="{{asset('theme/assets/examples/js/forms/wizard.minfd53.js?v4.0.1')}}"></script> --}}

<script src="{{asset('theme/assets/select2/select2.min.js')}}"></script>

<script>
$(document).ready(function() {
    $("#serviceDayCheckAll").click(function(){
        $("input[type=checkbox]").prop('checked', $(this).prop('checked'));
    });

    var drEvent = $('.dropify').dropify();
    drEvent.on('dropify.afterClear', function(event, element){
        $(this).next('input[type="text"]').remove();
    });
    $('#service_category').select2();
    $('#cluster').select2();
    $('#zone').select2();

    
    // $('.next').click(function() {
    //     $('#TabsRightTwo').show();
    //     // $('#TabsRightOne').hide();
    //     $(this).hide();
    // });

    // $('.prev').click(function(e) {
    //     e.preventDefault();
    //     $('#TabsRightOne').show();
    //     $('#TabsRightTwo').hide();
    // })

});
</script>

@endsection
@endsection