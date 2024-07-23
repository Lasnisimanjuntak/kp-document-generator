@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card border-0 shadow">
                <div class="card-header" style="background-color: #ED2B24; color: #fff; py-4">
                    <h2 class="text-center">{{ __('Welcome to Your Dashboard') }}</h2>
                </div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <p class="lead text-center mb-5">{{ __('You are logged in! Explore the features of our application below.') }}</p>

                    <div class="row justify-content-center">
                        <div class="col-md-4 mb-4">
                            <div class="card border-0 rounded-lg shadow-sm text-center p-4">
                                <div class="card-body">
                                    <h5 class="card-title mb-3">Create New Report</h5>
                                    <p class="card-text">Generate a new test report.</p>
                                    <a href="{{ route('laporan.create') }}" class="btn" style="background-color: #ED2B24; color: #fff; border-radius: 50px;">{{ __('Create Report') }}</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-4">
                            <div class="card border-0 rounded-lg shadow-sm text-center p-4">
                                <div class="card-body">
                                    <h5 class="card-title mb-3">View Reports</h5>
                                    <p class="card-text">Browse and manage existing test reports.</p>
                                    <a href="{{ route('laporan.index') }}" class="btn" style="background-color: #ED2B24; color: #fff; border-radius: 50px;">{{ __('View Reports') }}</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-4">
                            <div class="card border-0 rounded-lg shadow-sm text-center p-4">
                                <div class="card-body">
                                    <h5 class="card-title mb-3">Export to PDF</h5>
                                    <p class="card-text">Export test reports to PDF format.</p>
                                    <a href="{{ route('laporan.pdf') }}" class="btn" style="background-color: #ED2B24; color: #fff; border-radius: 50px;">{{ __('Export to PDF') }}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
