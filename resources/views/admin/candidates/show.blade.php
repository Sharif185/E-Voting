@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="mb-0">Candidate Details</h3>
                    <div>
                        @if(!$candidate->approved)
                            <form action="{{ route('admin.candidates.approve', $candidate) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-check"></i> Approve Candidate
                                </button>
                            </form>
                        @else
                            <span class="badge bg-success">Approved</span>
                        @endif
                        <a href="{{ route('admin.candidates.index') }}" class="btn btn-secondary ml-2">
                            <i class="fas fa-arrow-left"></i> Back to List
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <!-- Candidate Photo -->
                        <div class="col-md-4 text-center">
                            <img src="{{ Storage::url($candidate->photo_path) }}"
                                 alt="Candidate Photo"
                                 class="img-fluid rounded mb-3"
                                 style="max-height: 300px;">
                            <h4>{{ $candidate->first_name }} {{ $candidate->last_name }}</h4>
                            <p class="text-muted">{{ $candidate->party }}</p>
                        </div>

                        <!-- Candidate Details -->
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-6">
                                    <h5>Personal Information</h5>
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item">
                                            <strong>Age:</strong> {{ $candidate->age }}
                                        </li>
                                        <li class="list-group-item">
                                            <strong>Gender:</strong> {{ $candidate->gender }}
                                        </li>
                                        <li class="list-group-item">
                                            <strong>Nationality:</strong> {{ $candidate->nationality }}
                                        </li>
                                        <li class="list-group-item">
                                            <strong>National ID:</strong> {{ $candidate->national_id }}
                                        </li>
                                    </ul>
                                </div>

                                <div class="col-md-6">
                                    <h5>Election Information</h5>
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item">
                                            <strong>Election ID:</strong> {{ $candidate->election_id }}
                                        </li>
                                        <li class="list-group-item">
                                            <strong>Election Type:</strong> {{ $candidate->election_type }}
                                        </li>
                                        <li class="list-group-item">
                                            <strong>Constituency:</strong> {{ $candidate->constituency }}
                                        </li>
                                        <li class="list-group-item">
                                            <strong>Status:</strong>
                                            <span class="badge bg-{{ $candidate->status == 'Active' ? 'success' : ($candidate->status == 'Pending' ? 'warning' : 'danger') }}">
                                                {{ $candidate->status }}
                                            </span>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            <!-- Documents Section -->
                            <div class="row mt-4">
                                <div class="col-12">
                                    <h5>Documents</h5>
                                    <div class="d-flex gap-3">
                                        <a href="{{ Storage::url($candidate->biography_path) }}"
                                           class="btn btn-outline-primary"
                                           target="_blank"
                                           download="biography_{{ $candidate->last_name }}.pdf">
                                            <i class="fas fa-file-pdf"></i> Download Biography
                                        </a>
                                        <a href="{{ Storage::url($candidate->manifesto_path) }}"
                                           class="btn btn-outline-primary"
                                           target="_blank"
                                           download="manifesto_{{ $candidate->last_name }}.pdf">
                                            <i class="fas fa-file-pdf"></i> Download Manifesto
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
