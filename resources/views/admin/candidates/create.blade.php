<!-- resources/views/admin/candidates/create.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">Register New Candidate</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('admin.candidates.store') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="first_name" class="form-label">First Name</label>
                                <input type="text" class="form-control" id="first_name" name="first_name" required>
                            </div>
                            <div class="col-md-6">
                                <label for="last_name" class="form-label">Last Name</label>
                                <input type="text" class="form-control" id="last_name" name="last_name" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-3">
                                <label for="age" class="form-label">Age</label>
                                <input type="number" class="form-control" id="age" name="age" min="18" required>
                            </div>
                            <div class="col-md-3">
                                <label for="gender" class="form-label">Gender</label>
                                <select class="form-select" id="gender" name="gender" required>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="nationality" class="form-label">Nationality</label>
                                <input type="text" class="form-control" id="nationality" name="nationality" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="national_id" class="form-label">National ID</label>
                                <input type="text" class="form-control" id="national_id" name="national_id" required>
                            </div>
                            <div class="col-md-6">
                                <label for="election_id" class="form-label">Election ID</label>
                                <input type="text" class="form-control" id="election_id" name="election_id" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="election_type" class="form-label">Election Type</label>
                                <select class="form-select" id="election_type" name="election_type" required>
                                    <option value="Mayor">Mayor</option>
                                    <option value="Councilor">Councilor</option>
                                    <option value="MP">MP</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="constituency" class="form-label">Constituency</label>
                                <select class="form-select" id="constituency" name="constituency" required>
                                    <option value="Mukono">Mukono</option>
                                    <option value="Kampala">Kampala</option>
                                    <option value="Wakiso">Wakiso</option>
                                    <option value="Masaka">Masaka</option>
                                    <option value="General">General</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="photo" class="form-label">Photo</label>
                                <input type="file" class="form-control" id="photo" name="photo" accept="image/*" required>
                            </div>
                            <div class="col-md-6">
                                <label for="party" class="form-label">Political Party</label>
                                <input type="text" class="form-control" id="party" name="party" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="biography" class="form-label">Biography (PDF/DOC)</label>
                                <input type="file" class="form-control" id="biography" name="biography" accept=".pdf,.doc,.docx" required>
                            </div>
                            <div class="col-md-6">
                                <label for="manifesto" class="form-label">Manifesto (PDF/DOC)</label>
                                <input type="file" class="form-control" id="manifesto" name="manifesto" accept=".pdf,.doc,.docx" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select" id="status" name="status" required>
                                    <option value="Pending">Pending</option>
                                    <option value="Active">Active</option>
                                    <option value="Withdrawn">Withdrawn</option>
                                </select>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Register Candidate</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
