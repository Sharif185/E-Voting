@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h2 class="mb-0">Election Results: {{ $election->title }}</h2>
            <p class="mb-0">{{ $election->constituency }} Constituency</p>
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card bg-light">
                        <div class="card-body text-center">
                            <h5>Total Votes Cast</h5>
                            <h3 class="text-primary">{{ number_format($totalVotes) }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-light">
                        <div class="card-body text-center">
                            <h5>Voter Turnout</h5>
                            <h3 class="text-primary">{{ $voterTurnout }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-light">
                        <div class="card-body text-center">
                            <h5>Winner</h5>
                            <h3 class="text-primary">
                                {{ $winner->candidate->name ?? 'Pending' }}
                            </h3>
                        </div>
                    </div>
                </div>
            </div>

            <h4 class="mb-3">Official Results</h4>
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="thead-light">
                        <tr>
                            <th>Position</th>
                            <th>Candidate</th>
                            <th>Party</th>
                            <th>Votes</th>
                            <th>Percentage</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($results as $result)
                        <tr>
                            <td>{{ $result->position }}</td>
                            <td>{{ $result->candidate->name }}</td>
                            <td>{{ $result->candidate->party }}</td>
                            <td>{{ number_format($result->votes_count) }}</td>
                            <td>{{ $totalVotes > 0 ? number_format(($result->votes_count/$totalVotes)*100, 2).'%' : '0%' }}</td>
                            <td>
                                @if($result->is_winner)
                                    <span class="badge bg-success">Winner</span>
                                @elseif($result->position <= 3)
                                    <span class="badge bg-info">Top 3</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer text-muted">
            Results as of {{ now()->format('l, F j, Y \a\t g:i A') }}
        </div>
    </div>
</div>
@endsection
