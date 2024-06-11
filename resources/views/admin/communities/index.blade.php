@extends('admin.app')

@section('content')
<style>
    nav {
        text-align: end;
        margin-bottom: 20px;
    }
</style>
<div class="container mt-4">
    <div class="row">
        <div class="col-md-12">
            <div class="w-100 d-flex justify-content-end mb-4">
                <a class="btn btn-primary" href="{{ route('admin.communities.create') }}">New</a>
            </div>
            <table class="table table-bordered table-responsive">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Logo</th>
                        <th>Description</th>
                        <th>Categories</th>
                        <th>Is Blockchain</th>
                        <th>Website</th>
                        <th>Link</th>
                        <th>Invitation</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($communities as $item)
                    <tr>
                        <td>{{ $item->id }}</td>
                        <td>{{ $item->name }}</td>
                        <td>
                            @if(!empty($item->logo))
                            <img src="{{ $item->logo }}" alt="Logo" style="width: 50px; height: 50px;">
                            @else
                            N/A
                            @endif
                        </td>
                        <td>{{ $item->description }}</td>
                        <td>
                            asdas
                        </td>
                        <td>{{ $item->is_blockchain ? 'Yes' : 'No' }}</td>
                        <td>
                            @if($item->website)
                            <a href="{{ $item->website }}" target="_blank" style='text-decoration:none;'>Vist</a></td>
                            @else
                            N/A
                            @endif
                        <td>
                            @if($item->link)
                            <a href="{{ $item->link }}" target="_blank" style='text-decoration:none;'>Vist</a>
                            @else
                            N/A
                            @endif
                        </td>
                        <td>
                            @if(!empty($item->invites))
                            @php $inivities = json_decode($item->invites); @endphp
                            <ul>
                                @foreach($inivities as $inv)
                                <li>{{ $inv[0] }} - {{ $inv[1] }}</li>
                                @endforeach
                            </ul>
                            @else
                            N/A
                            @endif
                        </td>
                        <td class="d-flex" style="grid-gap: 10px;">
                            <a class="btn btn-success ml-2" href="{{ route('admin.communities.edit', $item->id) }}">Edit</a>
                            <a class="btn btn-danger" href="{{ route('admin.communities.delete', $item->id) }}">Delete</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $communities->links() }}
        </div>
    </div>
</div>
@endsection