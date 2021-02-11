@extends('layouts.app')
@section('content')
    <div class="row justify-center bg-light p-6">
        <div class="col-5">
            <div class="form-title display-3 font-semibold mb-3">{{ env('APP_NAME') }}</div>
            <div class="form-description mb-2">Check sites for SEO for free</div>
            <form class="row" action="{{ route('urls.store') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="col-md">
                    <input
                        type="text"
                        name="url[name]"
                        class="form-control @error('urls.name') is-invalid @enderror"
                        placeholder="https://google.com"
                    >
                    @error('urls.name')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md">
                    <input type="submit" class="btn btn-success" value="Check">
                </div>
            </form>
        </div>
    </div>
@endsection
