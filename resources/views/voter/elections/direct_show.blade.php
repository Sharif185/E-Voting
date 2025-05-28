<!-- resources/views/voter/elections/direct_show.blade.php -->
<div class="container">
    <!-- Debug Information Section -->
    <div class="alert alert-info mb-4">
        <h4>Election Verification</h4>
        <p><strong>Election ID:</strong> {{ $election->id }}</p>
        <p><strong>Type:</strong> {{ $election->type }}</p>
        <p><strong>Constituency:</strong> {{ $election->constituency }}</p>
        <p><strong>Active Candidates Found:</strong> {{ $candidates->count() }}</p>

        @if($candidates->count() > 0)
        <div class="mt-3">
            <h5>Candidate Verification:</h5>
            <p><strong>Sample Candidate ID:</strong> {{ $candidates->first()->id }}</p>
            <p><strong>Linked to Election ID:</strong> {{ $candidates->first()->election_id }}</p>
            <p><strong>Status:</strong> <span class="badge bg-{{ $candidates->first()->status == 'Active' ? 'success' : 'warning' }}">
                {{ $candidates->first()->status }}
            </span></p>
        </div>
        @endif
    </div>

    <!-- Election Header -->
    <h1 class="mb-3">{{ $election->title }}</h1>
    <div class="alert alert-primary">
        <h5 class="mb-2">Election Details</h5>
        <p class="mb-1"><strong>Type:</strong> {{ $election->type }}</p>
        <p class="mb-1"><strong>Area:</strong> {{ $election->constituency }}</p>
        <p class="mb-1"><strong>Voting Period:</strong>
            {{ $election->start_date->format('M j, Y') }} to {{ $election->end_date->format('M j, Y') }}
        </p>
    </div>

    <!-- Candidates Section -->
    <h3 class="mt-4 mb-3">Candidates</h3>

    @if($candidates->count() > 0)
    <div class="row">
        @foreach($candidates as $candidate)
        <div class="col-md-4 mb-4">
            <div class="card h-100 shadow-sm">
                <!-- Candidate Photo -->
                <div class="candidate-photo-container" style="height: 300px; overflow: hidden;">
                    @if($candidate->photo_path && Storage::exists($candidate->photo_path))
                    <img src="{{ asset('storage/'.$candidate->photo_path) }}"
                         class="card-img-top h-100 w-100 object-fit-cover"
                         alt="{{ $candidate->first_name }} {{ $candidate->last_name }}">
                    @else
                    <div class="h-100 d-flex align-items-center justify-content-center bg-light">
                        <i class="fas fa-user-tie fa-5x text-secondary"></i>
                    </div>
                    @endif
                </div>

                <div class="card-body">
                    <h4 class="card-title">
                        {{ $candidate->first_name }} {{ $candidate->last_name }}
                    </h4>

                    <div class="candidate-details">
                        <p><i class="fas fa-landmark me-2"></i> <strong>Party:</strong> {{ $candidate->party }}</p>
                        <p><i class="fas fa-map-marker-alt me-2"></i> <strong>Area:</strong> {{ $candidate->constituency }}</p>
                        <p class="mb-0">
                            <span class="badge bg-{{ $candidate->status == 'Active' ? 'success' : 'secondary' }}">
                                {{ ucfirst($candidate->status) }} Candidate
                            </span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="alert alert-warning">
        <h5>No Active Candidates Found</h5>
        <p class="mb-2">We couldn't find any approved candidates for this election.</p>

        <div class="debug-info bg-light p-3 mt-3">
            <h6>Technical Details:</h6>
            <p>Query executed:</p>
            <code class="d-block mb-2">
                SELECT * FROM candidates <br>
                WHERE election_id = {{ $election->id }} <br>
                AND status = 'Active'
            </code>

            <p class="mt-2">Possible solutions:</p>
            <ol>
                <li>Verify candidates are assigned to election ID {{ $election->id }}</li>
                <li>Check candidate status is set to 'Active'</li>
            </ol>
        </div>
    </div>
    @endif
</div>

@if($candidates->count() > 0)
<!-- Raw Data Debug (Remove in production) -->
<div class="container mt-5 border-top pt-3">
    <div class="alert alert-secondary">
        <h5>Debug Data (Remove After Testing)</h5>
        <button class="btn btn-sm btn-primary mb-3" type="button" data-bs-toggle="collapse" data-bs-target="#debugData">
            Toggle Raw Data
        </button>
        <div class="collapse" id="debugData">
            <pre>{{ json_encode($candidates->toArray(), JSON_PRETTY_PRINT) }}</pre>
        </div>
    </div>
</div>
@endif
