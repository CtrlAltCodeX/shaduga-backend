@extends('admin.app')

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-md-12">
            <div class="w-100 d-flex justify-content-end mb-4">
                <a class="btn btn-primary" href="{{ route('admin.modules.create') }}">New</a>
            </div>
            <table class="table table-bordered table-responsive">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Community</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($modules as $module)
                        <tr>
                            <td>{{ $module->title }}</td>
                            <td>{{ $module->desc }}</td>
                            <td>{{ $module->community->name }}</td>
                            <td class="d-flex" style="grid-gap: 10px;">
                                <a class="btn btn-success ml-2"  href="{{ route('admin.modules.edit', $module->id) }}">Edit</a>
                                <a class="btn btn-success"  href="{{ route('admin.modules.delete',$module->id) }}">Delete</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection