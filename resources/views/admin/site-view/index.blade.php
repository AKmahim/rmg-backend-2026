@extends('layouts.admin')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Site Views</li>
                    </ol>
                </div>
                <h4 class="page-title">Site Views</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card-box tilebox-one">
                <i class="fe-eye float-right"></i>
                <h5 class="text-muted text-uppercase mb-3 mt-0">Total Views</h5>
                <h3 class="mb-3" data-plugin="counterup">{{ $totalViews }}</h3>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card-box tilebox-one">
                <i class="fe-users float-right"></i>
                <h5 class="text-muted text-uppercase mb-3 mt-0">Unique Visitors</h5>
                <h3 class="mb-3" data-plugin="counterup">{{ $uniqueVisitors }}</h3>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card-box tilebox-one">
                <i class="fe-map-pin float-right"></i>
                <h5 class="text-muted text-uppercase mb-3 mt-0">Top Country</h5>
                <h3 class="mb-3">{{ $topCountryName }}</h3>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('site-view.index') }}" method="GET" class="mb-4">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="start_date">Start Date</label>
                                    <input type="date" class="form-control" id="start_date" name="start_date"
                                        value="{{ request('start_date') }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="end_date">End Date</label>
                                    <input type="date" class="form-control" id="end_date" name="end_date"
                                        value="{{ request('end_date') }}">
                                </div>
                            </div>
                            <div class="col-md-4 align-self-end">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">Filter</button>
                                    <a href="{{ route('site-view.index') }}" class="btn btn-secondary ml-2">Reset</a>
                                </div>
                            </div>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-centered table-nowrap table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Date/Time</th>
                                    <th>IP Address</th>
                                    <th>Country</th>
                                    <th>Page URL</th>
                                    <th>User Agent</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($siteViews as $view)
                                    <tr>
                                        <td>{{ $view->created_at->format('Y-m-d H:i:s') }}</td>
                                        <td>{{ $view->ip_address }}</td>
                                        <td>{{ $view->country ?? 'N/A' }}</td>
                                        <td title="{{ $view->page_url }}">{{ Str::limit($view->page_url, 50) }}</td>
                                        <td title="{{ $view->user_agent }}">{{ Str::limit($view->user_agent, 30) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">No records found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        {{ $siteViews->appends(request()->query())->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
