@extends('layouts.app')
@section('styles')

<link rel="stylesheet" href="{{asset('theme/vendor/dropify/dropify.minfd53.css?v4.0.1')}}">
<link rel="stylesheet" href="{{asset('theme/assets/examples/css/advanced/toastr.minfd53.css?v4.0.1')}}">
<link rel="stylesheet" href="{{asset('theme/assets/select2/select2.min.css')}}">

<style>
    .form-horizontal .form-control-label {
        margin-bottom: 0;
        text-align: left;
    }
    .dropify-wrapper{
        height: 121px
    }
</style>
@endsection
@section('content')
<!-- Panel Full Example -->
<div class="page-header pl-20 pr-20 pb-20 pt-0">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Notification Create</li>
    </ol>
    <h1 class="page-title">Notification Create</h1>
</div>

<div class="panel">
    <div class="panel-heading">
        <h3 class="panel-title">Notification Create</h3>
    </div>
    <div class="panel-body">
        <form id="exampleFullForm" autocomplete="off" method="POST" action="{{route('notifications.store')}}">
            @csrf
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label class="form-control-label">Title<span class="required">*</span></label>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="icon md-bus" aria-hidden="true"></i>
                        </span>
                        <input type="text" class="form-control" value="{{ old('notifications_title') }}" name="notifications_title" placeholder="Notification Title" required="">
                    </div>
                </div>
                <div class="form-group col-md-6">
                    <label class="form-control-label">Notification For<span class="required">*</span></label>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="icon md-bus" aria-hidden="true"></i>
                        </span>
                        <select class="form-control" value="{{ old('notifications_for') }}" name="notifications_for" required="">
                            <!-- <option value="sp">SP</option>  -->
                            <option value="client">Client</option>
                        </select>
                    </div>
                </div>
                <!-- <div class="form-group col-md-6">
                    <label class="form-control-label">Launch URL</label>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="icon md-bus" aria-hidden="true"></i>
                        </span>
                        <input type="text" class="form-control" value="{{ old('launch_url') }}" name="launch_url" placeholder="Notification  URL">
                    </div>
                </div>
                <div class="form-group col-md-6">
                    <div class="form-group">
                        <label class="form-control-label">Expire Date </label>
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="icon md-bus" aria-hidden="true"></i>
                            </span>
                            <input type="date" class="form-control" value="{{ old('expire_date') }}" name="expire_date" placeholder="Expire Date" >
                        </div>
                    </div>
                </div> -->
                <div class="form-group col-md-6">
                    <div class="form-group">
                        <label class="form-control-label">Description<span class="required">*</span></label>
                        <div class="input-group">
                            <textarea type="text" rows="4" class="form-control" name="description" placeholder="Description" required="">{{ old('description') }}</textarea>
                        </div>
                    </div>
                </div>
                <!-- <div class="form-group col-md-6">
                    <div class="form-group">
                        <label class="form-control-label">Upload Notification Image</label>
                        <div class="example m-0" id="Notification_imagex">
                            <input type="file" class="dropify" id="notification_image" name="notification_image" data-plugin="dropify" data-default-file="" />
                        </div>
                        @if ($errors->has('notification_image'))
                            <div class="text-danger">{{ $errors->first('notification_image') }}</div>
                        @endif
                    </div>
                </div> -->
            </div> 
            <button type="submit" class="btn btn-primary" >Submit</button>
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

<script src="{{asset('theme/vendor/toastr/toastr.minfd53.js?v4.0.1')}}"></script>
<script src="{{asset('theme/assets/js/Plugin/toastr.minfd53.js?v4.0.1')}}"></script>
<script src="{{asset('theme/vendor/dropify/dropify.minfd53.js?v4.0.1')}}"></script>
<script src="{{asset('theme/js/Plugin/dropify.minfd53.js?v4.0.1')}}"></script>
<script src="{{asset('theme/assets/select2/select2.min.js')}}"></script>

<script>
$(document).ready(function() {
    var drEvent = $('.dropify').dropify();
    drEvent.on('dropify.afterClear', function(event, element){
        $(this).next('input[type="text"]').remove();
    });

    $('#find_service').select2();
    $("#Notifications_type").change(function(){
        var type =  $(this).val();

        $("#display_quick_order_Notification").css('display', 'none');
        $("#display_discount_Notification").css('display', 'none');
        if(type == 'quick_order_Notification')
        {
            $("#display_quick_order_Notification").css('display', 'block');
        }
        if(type == 'discount_Notification')
        {
            $("#display_discount_Notification").css('display', 'flex');
        }
    });

    $("#service_id").change(function(){
        var service_id = $(this).val();
        $.ajax({
            url: "{{ url('api/get-service-bit') }}/" + service_id,
            type: 'get',
            success: function(response) {
                var sel = '<option value="">Select Service Bit</option>';
                $.each(response, function(index, value){
                    sel += '<option value="'+index+'">'+value+'</option>';
                });
                $("#service_bit_id").html(sel);
            },
            error: function (error) {
            }
        });
    });

    $("#find_service").select2({
        ajax:{
            type: "POST",
            url: "{{ url('api/quickorderitems') }}",
            dataType: 'json',
            data: function (term) {
                return {
                    find: term.term
                };
            },
            processResults: function (data) {
                return {
                    results: $.map(data.data, function (item) {
                        return {
                            text: item,
                            id: item
                        }
                    })
                };
            }
        }
    })
});
</script>

@endsection
@endsection