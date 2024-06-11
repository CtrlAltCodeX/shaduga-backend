@extends('admin.app')

@section('content')
<div class="container">
    <div class="row justify-content-center my-3">
        <div class="col-md-6">
            <form action="{{ route('admin.modules.update', $modules->id) }}" method="post"> <!--begin::Body-->
                @csrf
                <div class="card-body">
                    <div class="mb-3">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" class="form-control" id="title" name="title" required value="{{ $modules->title }}">
                    </div>
                    <div class="mb-3">
                        <label for="desc" class="form-label">Description</label>
                        <textarea class="form-control" id="desc" name="desc" rows="3" required> {{ $modules->desc }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label for="community_id" class="form-label">Community ID</label>
                        <select class="form-control" name="community_id" required>
                            <option value="">--Select--</option>
                            @foreach($communities as $community)
                            <option value="{{$community->id}}" {{ $modules->community_id == $community->id ? 'selected' : '' }}>{{$community->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="community_id" class="form-label">User</label>
                        <select class="form-control" name="user_id" required>
                            <option value="">--Select--</option>
                            @foreach($users as $user)
                            <option value="{{$user->id}}" {{ $user->id == $modules->user_id ? 'selected' : '' }}>{{$user->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="card-footer"> <button type="submit" class="btn btn-primary">Submit</button> </div> <!--end::Footer-->
            </form> <!--end::Form-->
        </div>
    </div>
</div>
@endsection