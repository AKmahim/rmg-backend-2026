@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')

    {{-- ── Page Title ─────────────────────────────────────────────────────────── --}}
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Dashboard</h4>
            </div>
        </div>
    </div>

    {{-- ── Stat Tiles ──────────────────────────────────────────────────────────── --}}
    <div class="row">

        {{-- Spinner --}}
        <div class="col-xl-3 col-md-6">
            <div class="card-box tilebox-one">
                <i class="ri-trophy-line float-right text-warning" style="font-size:24px"></i>
                <h5 class="text-muted text-uppercase mb-3 mt-0">Spinner Players</h5>
                <h3 class="mb-1">{{ $spinner_total }}</h3>
                <p class="text-muted mb-0">
                    <span class="text-success mr-2"><i class="fe-users"></i> {{ $spinner_winners }} winners</span>
                    <span class="text-muted">{{ $spinner_today }} today</span>
                </p>
            </div>
        </div>

        {{-- Quiz --}}
        <div class="col-xl-3 col-md-6">
            <div class="card-box tilebox-one">
                <i class="ri-question-answer-line float-right text-primary" style="font-size:24px"></i>
                <h5 class="text-muted text-uppercase mb-3 mt-0">Quiz Players</h5>
                <h3 class="mb-1">{{ $quiz_total }}</h3>
                <p class="text-muted mb-0">
                    <span class="text-success mr-2"><i class="fe-users"></i> {{ $quiz_winners }} winners</span>
                    <span class="text-muted">{{ $quiz_today }} today</span>
                </p>
            </div>
        </div>

        {{-- Total Players --}}
        <div class="col-xl-3 col-md-6">
            <div class="card-box tilebox-one">
                <i class="fe-users float-right text-success" style="font-size:24px"></i>
                <h5 class="text-muted text-uppercase mb-3 mt-0">Total Players</h5>
                <h3 class="mb-1">{{ $spinner_total + $quiz_total }}</h3>
                <p class="text-muted mb-0">
                    <span class="text-success mr-2"><i class="fe-award"></i> {{ $spinner_winners + $quiz_winners }} total winners</span>
                </p>
            </div>
        </div>

        {{-- Site Views --}}
        <div class="col-xl-3 col-md-6">
            <div class="card-box tilebox-one">
                <i class="fe-eye float-right text-info" style="font-size:24px"></i>
                <h5 class="text-muted text-uppercase mb-3 mt-0">Site Views</h5>
                <h3 class="mb-1">{{ $views_total }}</h3>
                <p class="text-muted mb-0">
                    <span class="text-muted">{{ $views_today }} today</span>
                </p>
            </div>
        </div>

    </div>

    {{-- ── Chart ───────────────────────────────────────────────────────────────── --}}
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">Player Activity — Last 7 Days</h4>
                    <canvas id="activityChart" height="100"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Quick Links ─────────────────────────────────────────────────────────── --}}
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center py-4">
                    <i class="ri-trophy-line" style="font-size:40px;color:#f7b731"></i>
                    <h5 class="mt-3 mb-1">Spinner Data</h5>
                    <p class="text-muted mb-3">View, filter and export all spinner records</p>
                    <a href="{{ route('spinner.index') }}" class="btn btn-warning btn-sm px-4">Manage</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center py-4">
                    <i class="ri-question-answer-line" style="font-size:40px;color:#4a90e2"></i>
                    <h5 class="mt-3 mb-1">Quiz Data</h5>
                    <p class="text-muted mb-3">View, filter and export all quiz records</p>
                    <a href="{{ route('quiz.index') }}" class="btn btn-primary btn-sm px-4">Manage</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center py-4">
                    <i class="fe-eye" style="font-size:40px;color:#1abc9c"></i>
                    <h5 class="mt-3 mb-1">Site View Stats</h5>
                    <p class="text-muted mb-3">Monitor traffic and visitor statistics</p>
                    <a href="{{ route('site-view.index') }}" class="btn btn-success btn-sm px-4">View</a>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('this-page-js')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4/dist/chart.umd.min.js"></script>
<script>
    const labels  = @json($chart_labels);
    const spinner = @json($chart_spinner);
    const quiz    = @json($chart_quiz);

    new Chart(document.getElementById('activityChart'), {
        type: 'bar',
        data: {
            labels,
            datasets: [
                {
                    label: 'Spinner',
                    data: spinner,
                    backgroundColor: 'rgba(247,183,49,0.8)',
                    borderColor: '#f7b731',
                    borderWidth: 1,
                    borderRadius: 4,
                },
                {
                    label: 'Quiz',
                    data: quiz,
                    backgroundColor: 'rgba(74,144,226,0.8)',
                    borderColor: '#4a90e2',
                    borderWidth: 1,
                    borderRadius: 4,
                },
            ],
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'top' },
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 1 },
                },
            },
        },
    });
</script>
@endsection
