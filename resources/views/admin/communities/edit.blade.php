@extends('admin.app')

@section('content')
<div class="container">
    <div class="row justify-content-center my-3">
        <div class="col-md-6">
            <form action="{{ route('admin.communities.update', $communities->id) }}" method="post" enctype="multipart/form-data"> <!--begin::Body-->
                @csrf
                <div class="card-body">
                    <!-- Name Field -->
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" value='{{ $communities->name }}' name="name" required>
                    </div>

                    <!-- Logo Field -->
                    <div class="mb-3">
                        <label for="logo" class="form-label">Logo</label>
                        <input type="file" class="form-control" id="logo" name="logo">
                        <img src="{{ $communities->logo }}" width="200" class="mt-2" />
                    </div>

                    <!-- Description Field -->
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3" required>{{ $communities->description }}</textarea>
                    </div>

                    <!-- Categories Field -->
                    <div class="mb-3">
                        <label for="categories" class="form-label">Categories</label>
                        <select class="form-control" id="categories" name="categories" required>
                            <option value="category1">Category 1</option>
                            <option value="category2">Category 2</option>
                            <option value="category3">Category 3</option>
                        </select>
                    </div>

                    <!-- Is Blockchain Field -->
                    <div class="mb-3">
                        <label for="is_blockchain" class="form-label">Is Blockchain</label>
                        <select class="form-control" id="is_blockchain" value='{{ $communities->is_blockchain }}' name="is_blockchain" required>
                            <option value="0">No</option>
                            <option value="1">Yes</option>
                        </select>
                    </div>

                    <!-- Website Field -->
                    <div class="mb-3">
                        <label for="website" class="form-label">Website</label>
                        <input type="url" class="form-control" id="website" value='{{ $communities->website }}' name="website" required>
                    </div>

                    <!-- Link Field -->
                    <!-- <div class="mb-3">
                        <label for="link" class="form-label">Link</label>
                        <input type="url" class="form-control" id="link" name="link" required>
                    </div> -->

                    <!-- Invitation Field -->
                    <div class="mb-3">
                        <label for="invitation" class="form-label">Invitations</label>
                        <div id="invitation">
                            @if($communities->invites)
                            @php $inivities = json_decode($communities->invites); @endphp
                            @foreach($inivities as $key => $invitations)
                            <div class="input-group mb-2">
                                <input type="email" class="form-control" name="invitation[{{$key}}][]" placeholder="Email" required value="{{ $invitations[0] }}">
                                <input type="text" class="form-control" name="invitation[{{$key}}][]" placeholder="Role" required value="{{ $invitations[1] }}">
                            </div>
                            @endforeach
                            @endif
                            <!-- Add more invitations as needed -->
                        </div>
                        <button type="button" class="btn btn-secondary" onclick="addInvitationField()">Add Invitation</button>
                    </div>
                </div> <!--end::Body-->
                <div class="card-footer"> <button type="submit" class="btn btn-primary">Submit</button> </div> <!--end::Footer-->
            </form> <!--end::Form-->
        </div>
    </div>
</div>
<script>
    function addInvitationField() {
        const invitationDiv = document.getElementById('invitation');
        const newIndex = invitationDiv.children.length;
        const newInputGroup = document.createElement('div');
        newInputGroup.className = 'input-group mb-2';
        newInputGroup.innerHTML = `
            <input type="email" class="form-control" name="invitation[${newIndex}][]" placeholder="Email" required>
            <input type="text" class="form-control" name="invitation[${newIndex}][]" placeholder="Role" required>
        `;
        invitationDiv.appendChild(newInputGroup);
    }
</script>
@endsection