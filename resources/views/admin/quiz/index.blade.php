@extends('layouts.admin')

@section('title', 'Quiz Data Management')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Quiz Data</li>
                    </ol>
                </div>
                <h4 class="page-title">Quiz Data Management</h4>
            </div>
        </div>
    </div>

    {{-- ── Stats Cards ─────────────────────────────────────────────────────── --}}
    <div class="row">
        <div class="col-md-4">
            <div class="card-box tilebox-one">
                <i class="fe-users float-right text-primary"></i>
                <h5 class="text-muted text-uppercase mb-3 mt-0">Total Players</h5>
                <h3 class="mb-3">{{ $totalPlayers }}</h3>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card-box tilebox-one">
                <i class="fe-award float-right text-success"></i>
                <h5 class="text-muted text-uppercase mb-3 mt-0">Prize Winners</h5>
                <h3 class="mb-3">{{ $prizeWinners }}</h3>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card-box tilebox-one">
                <i class="fe-refresh-cw float-right text-warning"></i>
                <h5 class="text-muted text-uppercase mb-3 mt-0">Total Plays</h5>
                <h3 class="mb-3">{{ $totalPlayCount }}</h3>
            </div>
        </div>
    </div>

    {{-- ── Filter + Export ─────────────────────────────────────────────────── --}}
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <form action="{{ route('quiz.index') }}" method="GET" class="mb-3">
                        <div class="row align-items-end">
                            <div class="col-md-3">
                                <div class="form-group mb-2">
                                    <label for="phone">Phone Number</label>
                                    <input type="text" class="form-control" id="phone" name="phone"
                                        placeholder="01XXXXXXXXX" value="{{ request('phone') }}">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group mb-2">
                                    <label for="prize">Prize Tier</label>
                                    <select class="form-control" id="prize" name="prize">
                                        <option value="">All</option>
                                        <option value="0" {{ request('prize') === '0' ? 'selected' : '' }}>১ম পুরস্কার (75–90)</option>
                                        <option value="1" {{ request('prize') === '1' ? 'selected' : '' }}>২য় পুরস্কার (60–74)</option>
                                        <option value="2" {{ request('prize') === '2' ? 'selected' : '' }}>৩য় পুরস্কার (50–59)</option>
                                        <option value="-1" {{ request('prize') === '-1' ? 'selected' : '' }}>No Prize (&lt;50)</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group mb-2">
                                    <label for="start_date">From Date</label>
                                    <input type="date" class="form-control" id="start_date" name="start_date"
                                        value="{{ request('start_date') }}">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group mb-2">
                                    <label for="end_date">To Date</label>
                                    <input type="date" class="form-control" id="end_date" name="end_date"
                                        value="{{ request('end_date') }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group mb-2 d-flex" style="gap:6px">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fe-filter"></i> Filter
                                    </button>
                                    <a href="{{ route('quiz.index') }}" class="btn btn-secondary">
                                        <i class="fe-x"></i> Reset
                                    </a>
                                    <a href="{{ route('quiz.export') }}?{{ http_build_query(request()->only(['phone','prize','start_date','end_date'])) }}"
                                        class="btn btn-success">
                                        <i class="fe-download"></i> Export CSV
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>

                    {{-- ── Table ─────────────────────────────────────────────── --}}
                    <div class="table-responsive">
                        <table class="table table-centered table-hover table-nowrap mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th>#</th>
                                    <th>Phone Number</th>
                                    <th>Score</th>
                                    <th>Prize</th>
                                    <th>Played Count</th>
                                    <th>IP Address</th>
                                    <th>Device</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($quizzes as $i => $row)
                                    @php
                                        $score = $row->score ?? 0;
                                        if ($score >= 75) {
                                            $prize = '১ম পুরস্কার';
                                            $badgeClass = 'badge-warning';
                                        } elseif ($score >= 60) {
                                            $prize = '২য় পুরস্কার';
                                            $badgeClass = 'badge-primary';
                                        } elseif ($score >= 50) {
                                            $prize = '৩য় পুরস্কার';
                                            $badgeClass = 'badge-success';
                                        } else {
                                            $prize = 'No Prize';
                                            $badgeClass = 'badge-secondary';
                                        }

                                        $ua = strtolower($row->user_agent ?? '');
                                        $device = str_contains($ua, 'mobile') || str_contains($ua, 'android') || str_contains($ua, 'iphone')
                                            ? 'Mobile' : 'Desktop';
                                    @endphp
                                    <tr>
                                        <td>{{ $quizzes->firstItem() + $i }}</td>
                                        <td><strong>{{ $row->phone_number }}</strong></td>
                                        <td>{{ $score }}</td>
                                        <td>
                                            <span class="badge {{ $badgeClass }}">{{ $prize }}</span>
                                        </td>
                                        <td>
                                            <span class="badge badge-info">{{ $row->played_count }}x</span>
                                        </td>
                                        <td>{{ $row->ip_address ?? 'N/A' }}</td>
                                        <td>
                                            <i class="fe-{{ $device === 'Mobile' ? 'smartphone' : 'monitor' }}"></i>
                                            {{ $device }}
                                        </td>
                                        <td>{{ $row->created_at->format('Y-m-d H:i') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center text-muted py-4">No records found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3 d-flex justify-content-between align-items-center">
                        <small class="text-muted">
                            Showing {{ $quizzes->firstItem() }}–{{ $quizzes->lastItem() }} of {{ $quizzes->total() }} records
                        </small>
                        {{ $quizzes->appends(request()->query())->links('pagination::bootstrap-4') }}
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
