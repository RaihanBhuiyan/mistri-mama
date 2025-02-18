@extends('layouts.app')

@section('styles')
@endsection

@section('content')
<div class="page-header pl-20 pr-20 pb-20 pt-0">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Career</li>
    </ol>
    <h1 class="page-title">Career</h1>
</div>
<div class="panel">
    <div class="panel-heading">
        <h3 class="panel-title">Manage Career</h3>
    </div>
    <div class="panel-body">
        <div class="table-responsive">
            @if(!empty($careers))
            <table class="table">
                <thead class="flip-content">
                    <tr>
                        <th>SL</th>
                        <th>Name</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th>Position</th>
                        <th>Years of Experience</th>
                        <th>Salary Expectation</th>
                        <th>comments</th>
                        <th>Download CV</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($careers as $key => $career)
                    <tr>
                        <td>{{ ($key+1) }}</td>
                        <td>{{ $career->name}}</td>
                        <td>{{ $career->phone_number }}</td>
                        <td>{{ $career->email}}</td>
                        <td>{{ $career->position}}</td>
                        <td>{{ $career->year_of_experience}}</td>
                        <td>{{ $career->salary_expectation}}</td>
                        <td>{{ $career->comments}}</td>
                        <td>
                            <a class="btn btn-info btn-xs" href="{{ route('resume.download', $career->cv) }}">Download</a>
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