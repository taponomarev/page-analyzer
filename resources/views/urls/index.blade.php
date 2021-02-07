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
                    @forelse($urls as $idx => $url)
                        <tr>
                            <th scope="row">{{ $idx + 1 }}</th>
                            <td>
                                <a href="{{ route('urls.show', $url->id) }}">{{ $url->name }}</a>
                            </td>
                            <td>{{ $url->created_at }}</td>
                            <td>{{ $url->updated_at }}</td>
                        </tr>
                    @empty
                        Urls not added
                    @endforelse
                </tbody>
            </table>
            {{ $urls->links() }}
        </div>
    </div>
@endsection
