@extends('layouts.readflow')

@section('title', 'Dashboard')

@section('header', 'Dashboard')

@section('content')
    <div class="row g-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-primary bg-opacity-10 p-3 rounded-3">
                            <i class="bi bi-book-half text-primary fs-2"></i>
                        </div>
                        <div>
                            <h5 class="card-title mb-1">Welcome to ReadFlow</h5>
                            <p class="card-text text-muted mb-0">Track your reading journey. Start by adding your first reading material.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-info bg-opacity-10 p-3 rounded-3">
                            <i class="bi bi-book text-info fs-4"></i>
                        </div>
                        <div>
                            <h3 class="mb-0 fw-bold text-info">0</h3>
                            <small class="text-muted">Reading Materials</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-success bg-opacity-10 p-3 rounded-3">
                            <i class="bi bi-clock-history text-success fs-4"></i>
                        </div>
                        <div>
                            <h3 class="mb-0 fw-bold text-success">0</h3>
                            <small class="text-muted">Reading Sessions</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-warning bg-opacity-10 p-3 rounded-3">
                            <i class="bi bi-clock text-warning fs-4"></i>
                        </div>
                        <div>
                            <h3 class="mb-0 fw-bold text-warning">0</h3>
                            <small class="text-muted">Reading Time (min)</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-danger bg-opacity-10 p-3 rounded-3">
                            <i class="bi bi-bullseye text-danger fs-4"></i>
                        </div>
                        <div>
                            <h3 class="mb-0 fw-bold text-danger">0</h3>
                            <small class="text-muted">Active Goals</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mt-2">
        <div class="col-12 col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-bottom d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-semibold">Recent Activities</h6>
                </div>
                <div class="card-body">
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-activity fs-1 d-block mb-3"></i>
                        <p class="mb-0">No recent activities yet.</p>
                        <small>Start reading to see your activity here.</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-bottom">
                    <h6 class="mb-0 fw-semibold">Reading Progress</h6>
                </div>
                <div class="card-body">
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-pie-chart fs-1 d-block mb-3"></i>
                        <p class="mb-0">No data yet.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
