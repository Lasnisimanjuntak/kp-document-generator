@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Delete Test Report</h1>
        <p>Are you sure you want to delete this report?</p>
        <form action="{{ route('laporan.destroy', $laporan->id) }}" method="POST">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">Delete</button>
            <a href="{{ route('laporan.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
@endsection
