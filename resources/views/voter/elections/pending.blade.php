@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="card">
        <div class="card-header">
            <h3>Upcoming Elections</h3>
        </div>
        <div class="card-body">
            @foreach($pendingElections as $type => $elections)
                <div class="mb-4">
                    <h4 class="mb-3">{{ $type }} Elections</h4>
                    <div class="row">
                        @foreach($elections as $election)
                        <div class="col-md-6 mb-3">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $election->title }}</h5>
                                    <p class="card-text">
                                        <strong>Constituency:</strong> {{ $election->constituency }}<br>
                                        <strong>Starts:</strong> {{ $election->start_date->format('M j, Y g:i A') }}<br>
                                        <strong>Ends:</strong> {{ $election->end_date->format('M j, Y g:i A') }}
                                    </p>
                                    <a href="{{ route('voter.elections.show', $election) }}" class="btn btn-primary">
                                        View Details & Candidates
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            @endforeach

            @if($pendingElections->isEmpty())
                <div class="alert alert-info">
                    There are currently no upcoming elections.
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
