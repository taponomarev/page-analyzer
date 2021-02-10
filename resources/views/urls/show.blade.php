@extends('layouts.app')
@section('content')
    <div class="card mt-4">
        <div class="card-body">
            <h5 class="card-title ">Url: {{ $url->name }}</h5>
            <table class="table">
                <thead>
                <tr>
                    <th scope="col">Id</th>
                    <th scope="col">Name</th>
                    <th scope="col">Created at</th>
                    <th scope="col">Updated at</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <th scope="row">{{ $url->id }}</th>
                    <td>{{ $url->name }}</td>
                    <td>{{ \Carbon\Carbon::parse($url->created_at)->diffForHumans() }}</td>
                    <td>{{ \Carbon\Carbon::parse($url->updated_at)->diffForHumans() }}</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="card mt-4">
        <div class="card-body">
            <h5 class="card-title ">Url checks</h5>
            <form action="{{ route("url.checks", $url->id) }}" method="post">
                @csrf
                <input type="submit" class="btn btn-primary" value="Run check">
            </form>
            <table class="table table-bordered table-hover text-nowrap">
                <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Response status</th>
                    <th scope="col">H1</th>
                    <th scope="col">Description</th>
                    <th scope="col">Keywords</th>
                    <th scope="col">Updated at</th>
                </tr>
                </thead>
                <tbody>
                @forelse($urlChecks as $urlCheck)
                    <tr>
                        <th scope="row">{{ $urlCheck->id }}</th>
                        <td>{{ $urlCheck->status_code }}</td>
                        <td>{{ \Illuminate\Support\Str::limit($urlCheck->h1, 10, '...') }}</td>
                        <td>{{ \Illuminate\Support\Str::limit($urlCheck->description, 20, '...') }}</td>
                        <td>{{ \Illuminate\Support\Str::limit($urlCheck->keywords, 10, '...') }}</td>
                        <td>{{ \Carbon\Carbon::parse($urlCheck->updated_at)->diffForHumans() }}</td>
                    </tr>
                @empty
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

