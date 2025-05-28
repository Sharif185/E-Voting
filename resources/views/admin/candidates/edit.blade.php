@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3>Edit Candidate: {{ $candidate->first_name }} {{ $candidate->last_name }}</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.candidates.update', $candidate) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                <h5>Personal Information</h5>

                                <div class="mb-3">
                                    <label for="first_name" class="form-label">First Name</label>
                                    <input type="text" class="form-control" id="first_name" name="first_name"
                                           value="{{ old('first_name', $candidate->first_name) }}" required>
                                </div>

                                <div class="mb-3">
                                    <label for="last_name" class="form-label">Last Name</label>
                                    <input type="text" class="form-control" id="last_name" name="last_name"
                                           value="{{ old('last_name', $candidate->last_name) }}" required>
                                </div>

                                <div class="mb-3">
                                    <label for="age" class="form-label">Age</label>
                                    <input type="number" class="form-control" id="age" name="age"
                                           value="{{ old('age', $candidate->age) }}" min="18" required>
                                </div>

                                <div class="mb-3">
                                    <label for="gender" class="form-label">Gender</label>
                                    <select class="form-select" id="gender" name="gender" required>
                                        <option value="Male" {{ old('gender', $candidate->gender) == 'Male' ? 'selected' : '' }}>Male</option>
                                        <option value="Female" {{ old('gender', $candidate->gender) == 'Female' ? 'selected' : '' }}>Female</option>
                                        <option value="Other" {{ old('gender', $candidate->gender) == 'Other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="nationality" class="form-label">Nationality</label>
                                    <input type="text" class="form-control" id="nationality" name="nationality"
                                           value="{{ old('nationality', $candidate->nationality) }}" required>
                                </div>

                                <div class="mb-3">
                                    <label for="national_id" class="form-label">National ID</label>
                                    <input type="text" class="form-control" id="national_id" name="national_id"
                                           value="{{ old('national_id', $candidate->national_id) }}" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <h5>Election Information</h5>

                                <div class="mb-3">
                                    <label for="election_id" class="form-label">Election ID</label>
                                    <input type="text" class="form-control" id="election_id" name="election_id"
                                           value="{{ old('election_id', $candidate->election_id) }}" required>
                                </div>

                                <div class="mb-3">
                                    <label for="election_type" class="form-label">Election Type</label>
                                    <select class="form-select" id="election_type" name="election_type" required>
                                        <option value="Mayor" {{ old('election_type', $candidate->election_type) == 'Mayor' ? 'selected' : '' }}>Mayor</option>
                                        <option value="Councilor" {{ old('election_type', $candidate->election_type) == 'Councilor' ? 'selected' : '' }}>Councilor</option>
                                        <option value="MP" {{ old('election_type', $candidate->election_type) == 'MP' ? 'selected' : '' }}>MP</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="constituency" class="form-label">Constituency</label>
                                    <select class="form-select" id="constituency" name="constituency" required>
                                        <option value="Mukono" {{ old('constituency', $candidate->constituency) == 'Mukono' ? 'selected' : '' }}>Mukono</option>
                                        <option value="Kampala" {{ old('constituency', $candidate->constituency) == 'Kampala' ? 'selected' : '' }}>Kampala</option>
                                        <option value="Wakiso" {{ old('constituency', $candidate->constituency) == 'Wakiso' ? 'selected' : '' }}>Wakiso</option>
                                        <option value="Masaka" {{ old('constituency', $candidate->constituency) == 'Masaka' ? 'selected' : '' }}>Masaka</option>
                                        <option value="General" {{ old('constituency', $candidate->constituency) == 'General' ? 'selected' : '' }}>General</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="party" class="form-label">Party</label>
                                    <input type="text" class="form-control" id="party" name="party"
                                           value="{{ old('party', $candidate->party) }}" required>
                                </div>

                                <div class="mb-3">
                                    <label for="status" class="form-label">Status</label>
                                    <select class="form-select" id="status" name="status" required>
                                        <option value="Pending" {{ old('status', $candidate->status) == 'Pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="Active" {{ old('status', $candidate->status) == 'Active' ? 'selected' : '' }}>Active</option>
                                        <option value="Withdrawn" {{ old('status', $candidate->status) == 'Withdrawn' ? 'selected' : '' }}>Withdrawn</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="photo" class="form-label">Candidate Photo</label>
                                    <input type="file" class="form-control" id="photo" name="photo">
                                    @if($candidate->photo_path)
                                        <small class="text-muted">Current: <a href="{{ Storage::url($candidate->photo_path) }}" target="_blank">View Photo</a></small>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="biography" class="form-label">Biography</label>
                                    <input type="file" class="form-control" id="biography" name="biography">
                                    @if($candidate->biography_path)
                                        <small class="text-muted">Current: <a href="{{ Storage::url($candidate->biography_path) }}" target="_blank">View Biography</a></small>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="manifesto" class="form-label">Manifesto</label>
                                    <input type="file" class="form-control" id="manifesto" name="manifesto">
                                    @if($candidate->manifesto_path)
                                        <small class="text-muted">Current: <a href="{{ Storage::url($candidate->manifesto_path) }}" target="_blank">View Manifesto</a></small>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('admin.candidates.show', $candidate) }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Update Candidate</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
