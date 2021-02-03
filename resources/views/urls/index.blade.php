@extends('layouts.app')
@section('content')
    <div class="card mt-4">
        <div class="card-body">
            <h5 class="card-title ">Sites</h5>
            <table class="table">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Name</th>
                    <th scope="col">Created at</th>
                    <th scope="col">Updated at</th>
                </tr>
                </thead>
                <tbody>
                    @forelse($sites as $idx => $site)
                        <tr>
                            <th scope="row">{{ $idx + 1 }}</th>
                            <td>
                                <a href="{{ route('urls.show', $site->id) }}">{{ $site->name }}</a>
                            </td>
                            <td>{{ $site->created_at }}</td>
                            <td>{{ $site->updated_at }}</td>
                        </tr>
                    @empty
                        Websites not added
                    @endforelse
                </tbody>
            </table>
            {{ $sites->links() }}
        </div>
    </div>
@endsection
