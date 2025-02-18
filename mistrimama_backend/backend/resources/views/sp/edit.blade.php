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
        <h3 class="panel-title">Edit Service Provider</h3>
    </div>
    <div class="panel-body">
        <form method="POST" action="{{route('service-provider.update', $serviceProvider->id)}}">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="form-control-label">Full Name<span class="required">*</span></label>
                <div class="input-group">
                    <span class="input-group-addon">
                        <i class="icon md-accounts-add" aria-hidden="true"></i>
                    </span>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" value="{{$serviceProvider->name}}" name="name" placeholder="Jon Doe" required>
                </div>
                @error('name')
                <p class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </p>
                @enderror
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
                        <input readonly="" type="tel" pattern="[0-9]*" maxlength="11" minlength="11" class="form-control @error('phone') is-invalid @enderror" value="{{$serviceProvider->phone}}" name="phone" placeholder="017xxxxxxxx" required>
                    </div>
                    @error('phone')
                    <p class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </p>
                    @enderror
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
                        <input type="tel" pattern="[0-9]*" maxlength="11" minlength="11" class="form-control" value="{{ $serviceProvider->alt_phone }}" name="alt_phone" placeholder="017xxxxxxxx">
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="form-control-label">Email </label>
                <div class="input-group">
                    <span class="input-group-addon">
                        <i class="icon md-email" aria-hidden="true"></i>
                    </span>
                    <input readonly="" type="email" class="form-control" value="{{ $serviceProvider->email }}" name="email" placeholder="email@email.com">
                </div>
            </div>
            <div class="form-group">
                <label class="form-control-label">Shop Name </label>
                <div class="input-group">
                    <span class="input-group-addon">
                        <i class="icon md-map" aria-hidden="true"></i>
                    </span>
                    <input class="form-control @error('shop_name') is-invalid @enderror" name="shop_name" value="{{ $serviceProvider->shop_name }}" placeholder="Shop Name">
                </div>
            </div>
            <div class="form-group">
                <label class="form-control-label">Address</label>
                <textarea class="form-control" name="address" placeholder="Details Address">{{ $serviceProvider->address }}</textarea>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <div class="form-group">
                        <label class="form-control-label" for="select">MFS Type</label>
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="icon md-case-check"></i>
                            </span>
                            <select class="form-control" name="mfs_type" id="select">
                                <option {{($serviceProvider->mfs_type=='Bkash'?'selected':'')}} value="Bkash">Bkash</option>
                            </select>
                        </div>
                    </div>
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
                            <input type="tel" pattern="[0-9]*" maxlength="11" minlength="11" class="form-control" value="{{ $serviceProvider->mfs_no }}" name="mfs_no" placeholder="017xxxxxxxx" required>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-4">
                    <div class="form-group">
                        <label class="form-control-label">NID Number <span class="required">*</span></label>
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="icon md-balance-wallet" aria-hidden="true"></i>
                            </span>
                            <input type="number" maxlength="16" minlength="10" class="form-control" value="{{ $serviceProvider->nid_no }}" name="nid_no" placeholder="1229123123312312" required>
                        </div>
                    </div>
                </div>
                <div class="form-group col-md-4">
                    <div class="form-group">
                        <label class="form-control-label">Trade License Number</label>
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="icon md-map" aria-hidden="true"></i>
                            </span>
                            <input type="number" maxlength="16" minlength="10" class="form-control" value="{{ $serviceProvider->trade_lic_no }}" name="trade_lic_no" placeholder="129387123">
                        </div>
                    </div>
                </div>
                <div class="form-group col-md-4">
                    <div class="form-group">
                        <label class="form-control-label">Tin Certificate</label>
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="icon md-map" aria-hidden="true"></i>
                            </span>
                            <input type="number" maxlength="16" minlength="10" class="form-control" value="{{ $serviceProvider->tin_no }}" name="tin_no" placeholder="1231233123">
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <!-- Example Default -->
                    <div class="example-wrap mb-4">
                        <h4 class="example-title mb-1">Profile Picture</h4>
                        <div class="example m-0" id="picture_upload">
                            <input type="file" class="dropify" id="photo" name="photo" data-plugin="dropify" data-default-file="" />
                        </div>
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
                    </div>
                    <!-- End Example Default -->
                </div>
                <div class="col-md-4">
                    <!-- Example Default -->
                    <div class="example-wrap mb-4">
                        <h4 class="example-title mb-1">NiD Front Picture</h4>
                        <div class="example m-0" id="nid_front_upload">
                            <input type="file" class="dropify" id="nid_front" name="nid_front" data-plugin="dropify" data-default-file=""/>
                        </div>
                    </div>
                    <!-- End Example Default -->
                </div>
                <div class="col-md-4">
                    <!-- Example Default -->
                    <div class="example-wrap mb-4">
                        <h4 class="example-title mb-1">NiD Back Picture</h4>
                        <div class="example m-0" id="nid_back_upload">
                            <input type="file" class="dropify" id="nid_back" name="nid_back" data-plugin="dropify" data-default-file=""/>
                        </div>
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
                    <select class="form-control" name="type" id="type" required>
                        <option {{($serviceProvider->type=='esp'?'selected':'')}} value="esp">Enterprise Service Provider</option>
                        <option {{($serviceProvider->type=='fsp'?'selected':'')}} value="fsp">Freelance Service Provider</option>
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
                        <option value="{{$category->id}}" {{ (in_array($category->id, $serviceProvider->service->pluck('category_id')->toArray()) ? 'selected' : '' ) }}>{{$category->name}}</option>
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
                            <option value="{{$division->id}}" {{ (in_array($division->id, $serviceProvider->division->pluck('division_id')->toArray()) ? 'selected' : '' ) }}>{{$division->name}}</option>
                            @endforeach
                            @endif
                        </select>
                    </div>
                    @if ($errors->has('division'))
                        <div class="text-danger">{{ $errors->first('division') }}</div>
                    @endif
                </div>
                <div class="form-group col-md-4">
                    <label class="form-control-label" for="select">Cluster</label>
                    <div class="input-group" @error('cluster') style="border: 1px solid #f44336;" @enderror>
                        <span class="input-group-addon">
                            <i class="icon md-case-check"></i>
                        </span>
                        <select style="width:100%;" class="form-control" multiple="multiple" name="cluster[]" id="cluster">
                            @if(!empty($clusters))
                            @foreach ($clusters as $cluster)
                            <option value="{{$cluster->id}}" {{ (in_array($cluster->id, $serviceProvider->cluster->pluck('cluster_id')->toArray()) ? 'selected' : '' ) }}>{{$cluster->name}}</option>
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
                            <option value="{{$zone->id}}" {{ (in_array($zone->id, $serviceProvider->zone->pluck('zone_id')->toArray()) ? 'selected' : '' ) }}>{{$zone->name}}</option>
                            @endforeach
                            @endif
                        </select>
                    </div>
                    @if ($errors->has('zone'))
                        <div class="text-danger">{{ $errors->first('zone') }}</div>
                    @endif
                </div>
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
                        <?php
                        $service_day_arr = array();
                        $service_day = \App\ServiceProviderTime::where('service_provider_id', $serviceProvider->id)->where('day', $day)->first();
                        ?>
                        <tr>
                            <td>
                                <label class="form-check-label" for="{{ $day }}">
                                    <input {{(isset($service_day->day)?'checked':'')}} type="checkbox" class="form-check-input" id="{{ $day }}" value="{{ $day }}" name="day[]">
                                    {{ $day }}
                                </label>
                            </td>
                            <td>
                                <select class="form-control day-start-time-0 start_time valid" aria-invalid="false" name="start[{{ $day }}][]">
                                    @foreach($start_times as $start_time)
                                    <option {{(isset($service_day->day) && $start_time==$service_day->start?'selected':'')}} value="{{ $start_time }}">{{ $start_time }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <select class="form-control day-end-time-1 end_time valid" aria-invalid="false" name="end[{{ $day }}][]">
                                    @foreach($end_times as $end_time)
                                    <option {{(isset($service_day->day) && $end_time==$service_day->end?'selected':'')}} value="{{ $end_time }}">{{ $end_time }}</option>
                                    @endforeach
                                </select>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <button class="btn btn-success" type="submit">Save</button>
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
<script src="{{asset('theme/assets/examples/js/forms/wizard.minfd53.js?v4.0.1')}}"></script>
--}}

<script src="{{asset('theme/assets/select2/select2.min.js')}}"></script>

<script>
    $(document).ready(function() {
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