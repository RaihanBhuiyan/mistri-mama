@extends('layouts.app')

@section('styles')
@endsection

@section('content')

<div class="page-header pl-20 pr-20 pb-20 pt-0">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Advertisement</li>
    </ol>
    <h1 class="page-title">Advertisement</h1>
    <div class="page-header-actions">
        <a href="{{ route('advertisement.create') }}" class="btn btn-sm btn-primary btn-round waves-effect waves-classic">Create Advertisement</a>
    </div>
</div>
<div class="panel">
    <div class="panel-heading">
        <h3 class="panel-title">Manage Advertisement</h3>
    </div>
    <div class="panel-body">
        <div class="table-responsive">
            @if(!empty($advertisements))
            <table class="table">
                <thead class="flip-content">
                    <tr>
                        <th>Place Name</th>
                        <th>Advertisements</th>
                        <th style="text-align:right">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($advertisements as $advertisement)
                    <tr>
                        <td class="text-capitalize">{{ $advertisement->place_name }}</td>
                        <td>
                            <a href="javaScript:;" data-toggle="modal" data-target="#advertisementModal{{ $advertisement->id }}">Show advertisement</a>
                            <div id="advertisementModal{{ $advertisement->id }}" class="modal fade text-center" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-body">
                                            @if (!empty($advertisement->advertisement_image))
                                            <img style="width:100%;" src="{{ $advertisement->advertisement_image }}" alt="">
                                            @endif
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="align-right">
                            <form action="{{route('advertisement.destroy', $advertisement->id)}}" method="POST">
                                @csrf
                                @method('delete')
                                <button onClick="return confirm('Are you sure you want to delete?')" class="btn btn-xs btn-danger"><i class="icon md-delete"></i></button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @endif
        </div>
    </div>
</div>
@section('scripts')
@endsection
@endsection