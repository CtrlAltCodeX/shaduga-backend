@extends('admin.app')

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-md-12">
            <div class="w-100 d-flex justify-content-end mb-4">
                <a class="btn btn-primary" href="{{ route('admin.reviews.create') }}">New</a>
            </div>
            <table class="table table-bordered table-responsive">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Rating</th>
                        <th>Title</th>
                        <th>Body</th>
                        <th>Status</th>
                        <th>Bookmarked</th>
                        <th>Image</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reviews as $review)
                    <tr>
                        <td>{{ $review->user->name }}</td>
                        <td>{{ $review->rating }}</td>
                        <td>{{ $review->title }}</td>
                        <td>{{ $review->body }}</td>
                        <td>{{ $review->status ? 'Active' : 'InActive' }}</td>
                        <td>{{ $review->bookmarked ? 'Yes' : 'No' }}</td>
                        <td><img src="{{ $review->image }}" width="100" /></td>
                        <td class="d-flex" style="grid-gap: 10px;">
                            <a class="btn btn-success ml-2" href="{{ route('admin.reviews.edit', $review->id) }}">Edit</a>
                            <a class="btn btn-danger" href="{{ route('admin.reviews.delete', $review->id) }}">Delete</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection