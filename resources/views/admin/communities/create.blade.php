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
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" required value="{{ old('name') }}">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Logo Field -->
                    <div class="mb-3">
                        <label for="logo" class="form-label">Logo</label>
                        <input type="file" class="form-control @error('logo') is-invalid @enderror" id="logo" name="logo">
                        @error('logo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Description Field -->
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3" required>{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Categories Field -->
                    <div class="mb-3">
                        <label for="categories" class="form-label">Categories</label>
                        <select class="form-control @error('categories') is-invalid @enderror" id="categories" name="categories" required>
                            <option value="">Select Category</option>
                            <option value="category1" {{ old('categories') == 'category1' ? 'selected' : '' }}>Productivity</option>
                            <option value="category2" {{ old('categories') == 'category2' ? 'selected' : '' }}>All</option>
                            <option value="category3" {{ old('categories') == 'category3' ? 'selected' : '' }}>Gaming</option>
                            <option value="category4" {{ old('categories') == 'category4' ? 'selected' : '' }}>Music</option>
                            <option value="category5" {{ old('categories') == 'category5' ? 'selected' : '' }}>Design and Creative</option>
                            <option value="category6" {{ old('categories') == 'category6' ? 'selected' : '' }}>Fashion and Brands</option>
                            <option value="category7" {{ old('categories') == 'category7' ? 'selected' : '' }}>Finance</option>
                            <option value="category8" {{ old('categories') == 'category8' ? 'selected' : '' }}>Health and Fitness</option>
                            <option value="category9" {{ old('categories') == 'category9' ? 'selected' : '' }}>Marketing and Growth</option>
                            <option value="category10" {{ old('categories') == 'category10' ? 'selected' : '' }}>Engineering and Development</option>
                        </select>
                        @error('categories')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Is Blockchain Field -->
                    <div class="mb-3">
                        <label for="is_blockchain" class="form-label">Is Blockchain</label>
                        <select class="form-control @error('is_blockchain') is-invalid @enderror" id="is_blockchain" name="is_blockchain" required>
                            <option value="0" {{ old('is_blockchain') == '0' ? 'selected' : '' }}>No</option>
                            <option value="1" {{ old('is_blockchain') == '1' ? 'selected' : '' }}>Yes</option>
                        </select>
                        @error('is_blockchain')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Website Field -->
                    <div class="mb-3">
                        <label for="website" class="form-label">Website</label>
                        <input type="url" class="form-control @error('website') is-invalid @enderror" id="website" name="website" required value="{{ old('website') }}">
                        @error('website')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Invitation Field -->
                    <div class="mb-3">
                        <label for="invitation" class="form-label">Invitations</label>
                        <div id="invitation">
                            @foreach(old('invitation', [['', ''], ['', '']]) as $index => $invitation)
                                <div class="input-group mb-2">
                                    <input type="email" class="form-control @error("invitation.$index.0") is-invalid @enderror" name="invitation[{{ $index }}][]" placeholder="Email" value="{{ $invitation[0] }}">
                                    <input type="text" class="form-control @error("invitation.$index.1") is-invalid @enderror" name="invitation[{{ $index }}][]" placeholder="Role" value="{{ $invitation[1] }}">
                                    @error("invitation.$index.0")
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    @error("invitation.$index.1")
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            @endforeach
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
