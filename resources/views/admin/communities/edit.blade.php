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
                        <input type="text" class="form-control" id="name" value="{{ old('name', $communities->name) }}" name="name" required>
                        @if($errors->has('name'))
                            <div class="text-danger">{{ $errors->first('name') }}</div>
                        @endif
                    </div>

                    <!-- Logo Field -->
                    <div class="mb-3">
                        <label for="logo" class="form-label">Logo</label>
                        <input type="file" class="form-control" id="logo" name="logo">
                        <img src="{{ $communities->logo }}" width="200" class="mt-2" />
                        @if($errors->has('logo'))
                            <div class="text-danger">{{ $errors->first('logo') }}</div>
                        @endif
                    </div>

                    <!-- Description Field -->
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3" required>{{ old('description', $communities->description) }}</textarea>
                        @if($errors->has('description'))
                            <div class="text-danger">{{ $errors->first('description') }}</div>
                        @endif
                    </div>

                    <!-- Categories Field -->
                    <div class="mb-3">
                        <label for="categories" class="form-label">Categories</label>
                        <select class="form-control" id="categories" name="categories" required>
                            <option value="category1" {{ old('categories', $communities->categories) == 'category1' ? 'selected' : '' }}>Category 1</option>
                            <option value="category2" {{ old('categories', $communities->categories) == 'category2' ? 'selected' : '' }}>Category 2</option>
                            <option value="category3" {{ old('categories', $communities->categories) == 'category3' ? 'selected' : '' }}>Category 3</option>
                        </select>
                        @if($errors->has('categories'))
                            <div class="text-danger">{{ $errors->first('categories') }}</div>
                        @endif
                    </div>

                    <!-- Is Blockchain Field -->
                    <div class="mb-3">
                        <label for="is_blockchain" class="form-label">Is Blockchain</label>
                        <select class="form-control" id="is_blockchain" name="is_blockchain" required>
                            <option value="0" {{ old('is_blockchain', $communities->is_blockchain) == '0' ? 'selected' : '' }}>No</option>
                            <option value="1" {{ old('is_blockchain', $communities->is_blockchain) == '1' ? 'selected' : '' }}>Yes</option>
                        </select>
                        @if($errors->has('is_blockchain'))
                            <div class="text-danger">{{ $errors->first('is_blockchain') }}</div>
                        @endif
                    </div>

                    <!-- Website Field -->
                    <div class="mb-3">
                        <label for="website" class="form-label">Website</label>
                        <input type="url" class="form-control" id="website" value="{{ old('website', $communities->website) }}" name="website" required>
                        @if($errors->has('website'))
                            <div class="text-danger">{{ $errors->first('website') }}</div>
                        @endif
                    </div>

                    <!-- Invitation Field -->
                    <div class="mb-3">
                        <label for="invitation" class="form-label">Invitations</label>
                        <div id="invitation">
                            @if($communities->invites)
                            @php $inivities = json_decode($communities->invites); @endphp
                            @foreach($inivities as $key => $invitations)
                            <div class="input-group mb-2">
                                <input type="email" class="form-control" name="invitation[{{$key}}][]" placeholder="Email"  value="{{ old('invitation.'.$key.'.0', $invitations[0]) }}">
                                <input type="text" class="form-control" name="invitation[{{$key}}][]" placeholder="Role"  value="{{ old('invitation.'.$key.'.1', $invitations[1]) }}">
                                @if($errors->has('invitation.'.$key.'.0'))
                                    <div class="text-danger">{{ $errors->first('invitation.'.$key.'.0') }}</div>
                                @endif
                                @if($errors->has('invitation.'.$key.'.1'))
                                    <div class="text-danger">{{ $errors->first('invitation.'.$key.'.1') }}</div>
                                @endif
                            </div>
                            @endforeach
                            @endif
                            <!-- Add more invitations as needed -->
                        </div>
                        <button type="button" class="btn btn-secondary" onclick="addInvitationField()">Add Invitation</button>
                    </div>
                </div> <!--end::Body-->
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div> <!--end::Footer-->
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
