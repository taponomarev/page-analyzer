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
                    <th scope="col">Status code</th>
                    <th scope="col">Last check</th>
                </tr>
                </thead>
                <tbody>
                    @forelse($urls as $idx => $url)
                        <tr>
                            <th scope="row">{{ $url->id }}</th>
                            <td>
                                <a href="{{ route('urls.show', $url->id) }}">{{ $url->name }}</a>
                            </td>
                            <td>{{ $url->last_check_status_code }}</td>
                            <td>{{ \Carbon\Carbon::parse($url->last_checked_at)->diffForHumans() }}</td>
                        </tr>
                    @empty
                        Sites not added
                    @endforelse
                </tbody>
            </table>
            {{ $urls->links() }}
        </div>
    </div>
@endsection
