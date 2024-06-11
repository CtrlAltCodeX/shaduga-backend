@extends('admin.app')

@section('content')
<div class="container">
    <div class="row justify-content-center mt-3">
        <div class="col-md-6">
            <form action="{{ route('admin.communities.store') }}" method="post" enctype="multipart/form-data"> <!--begin::Body-->
                @csrf
                <div class="card-body">
                    <!-- Name Field -->
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>

                    <!-- Logo Field -->
                    <div class="mb-3">
                        <label for="logo" class="form-label">Logo</label>
                        <input type="file" class="form-control" id="logo" name="logo">
                    </div>

                    <!-- Description Field -->
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
                    </div>

                    <!-- Categories Field -->
                    <div class="mb-3">
                        <label for="categories" class="form-label">Categories</label>
                        <select class="form-control" id="categories" name="categories" required>
                            <option value="category1">Productivity</option>
                            <option value="category1">All</option>
                            <option value="category1">Gaming</option>
                            <option value="category1">Music</option>
                            <option value="category1">Design and Creative</option>
                            <option value="category1">Fashion and Brands</option>
                            <option value="category1">Finance</option>
                            <option value="category1">Health and Fitness</option>
                            <option value="category1">Marketing and Growth</option>
                            <option value="category1">Engineering and Development</option>
                        </select>
                    </div>

                    <!-- Is Blockchain Field -->
                    <div class="mb-3">
                        <label for="is_blockchain" class="form-label">Is Blockchain</label>
                        <select class="form-control" id="is_blockchain" name="is_blockchain" required>
                            <option value="0">No</option>
                            <option value="1">Yes</option>
                        </select>
                    </div>

                    <!-- Website Field -->
                    <div class="mb-3">
                        <label for="website" class="form-label">Website</label>
                        <input type="url" class="form-control" id="website" name="website" required>
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
                            <div class="input-group mb-2">
                                <input type="email" class="form-control" name="invitation[0][]" placeholder="Email" required>
                                <input type="text" class="form-control" name="invitation[0][]" placeholder="Role" required>
                            </div>
                            <div class="input-group mb-2">
                                <input type="email" class="form-control" name="invitation[1][]" placeholder="Email" required>
                                <input type="text" class="form-control" name="invitation[1][]" placeholder="Role" required>
                            </div>
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