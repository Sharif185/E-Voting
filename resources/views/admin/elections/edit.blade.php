@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3>Edit Election: {{ $election->title }}</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.elections.update', $election->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="title" class="form-label">Election Title</label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror"
                                       id="title" name="title" value="{{ old('title', $election->title) }}" required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="type" class="form-label">Election Type</label>
                                <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                                    <option value="">Select Type</option>
                                    <option value="Mayor" {{ old('type', $election->type) == 'Mayor' ? 'selected' : '' }}>Mayor</option>
                                    <option value="Councilor" {{ old('type', $election->type) == 'Councilor' ? 'selected' : '' }}>Councilor</option>
                                    <option value="MP" {{ old('type', $election->type) == 'MP' ? 'selected' : '' }}>MP</option>
                                </select>
                                @error('type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="start_date" class="form-label">Start Date & Time</label>
                                <input type="datetime-local" class="form-control @error('start_date') is-invalid @enderror"
                                       id="start_date" name="start_date"
                                       value="{{ old('start_date', $election->start_date->format('Y-m-d\TH:i')) }}" required>
                                @error('start_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="end_date" class="form-label">End Date & Time</label>
                                <input type="datetime-local" class="form-control @error('end_date') is-invalid @enderror"
                                       id="end_date" name="end_date"
                                       value="{{ old('end_date', $election->end_date->format('Y-m-d\TH:i')) }}" required>
                                @error('end_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="voting_duration_hours" class="form-label">Voting Duration (hours)</label>
                                <input type="number" class="form-control @error('voting_duration_hours') is-invalid @enderror"
                                       id="voting_duration_hours" name="voting_duration_hours"
                                       value="{{ old('voting_duration_hours', $election->voting_duration_hours) }}" min="1" required>
                                @error('voting_duration_hours')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="constituency" class="form-label">Constituency</label>
                                <select class="form-select @error('constituency') is-invalid @enderror" id="constituency" name="constituency" required>
                                    <option value="">Select Constituency</option>
                                    <option value="Mukono" {{ old('constituency', $election->constituency) == 'Mukono' ? 'selected' : '' }}>Mukono</option>
                                    <option value="Kampala" {{ old('constituency', $election->constituency) == 'Kampala' ? 'selected' : '' }}>Kampala</option>
                                    <option value="Wakiso" {{ old('constituency', $election->constituency) == 'Wakiso' ? 'selected' : '' }}>Wakiso</option>
                                    <option value="Masaka" {{ old('constituency', $election->constituency) == 'Masaka' ? 'selected' : '' }}>Masaka</option>
                                    <option value="General" {{ old('constituency', $election->constituency) == 'General' ? 'selected' : '' }}>General (All Constituencies)</option>
                                </select>
                                @error('constituency')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description (Optional)</label>
                            <textarea class="form-control @error('description') is-invalid @enderror"
                                      id="description" name="description" rows="3">{{ old('description', $election->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-check mb-3">
                            <input class="form-check-input @error('is_active') is-invalid @enderror"
                                   type="checkbox" id="is_active" name="is_active" value="1"
                                   {{ old('is_active', $election->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">Activate this election</label>
                            @error('is_active')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.elections.index') }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Update Election</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Validate end date is after start date
        $('form').on('submit', function(e) {
            const startDate = new Date($('#start_date').val());
            const endDate = new Date($('#end_date').val());

            if (endDate <= startDate) {
                e.preventDefault();
                alert('End date must be after start date');
                $('#end_date').focus();
            }
        });
    });
</script>
@endsection
