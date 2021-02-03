@extends('layouts.app')
@section('content')
    <div class="card mt-4">
        <div class="card-body">
            <h5 class="card-title ">Site: {{ $site->name }}</h5>
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
                    <th scope="row">{{ $site->id }}</th>
                    <td>{{ $site->name }}</td>
                    <td>{{ $site->created_at }}</td>
                    <td>{{ $site->updated_at }}</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection

