@extends('layouts.main')
@section('title', 'Pairs')
@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Pairs</h1>
        <div class="loader spinner-grow text-primary" role="status" style="display: none">
            <span class="sr-only"></span>
        </div>
        <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group me-2">
                <select class="form-select" id="select-per-page">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                </select>
            </div>
            <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" id="dropdownPeriods" data-bs-toggle="dropdown" aria-expanded="false">
                <span data-feather="calendar"></span>
                Period
            </button>

            <ul id="dropdown-periods" class="dropdown-menu" aria-labelledby="dropdownPeriods">
                <li data-value="1" class="dropdown-item">1 день</li>
                <li data-value="2" class="dropdown-item">2 дня</li>
                <li data-value="3" class="dropdown-item">7 дней</li>
                <li data-value="4" class="dropdown-item">Месяц</li>
            </ul>
        </div>
    </div>

    <script src="/js/highcharts/highcharts.js"></script>
    <script src="/js/highcharts/accessibility.js"></script>

    <div id="pairs-content">
        @include('index._table_pairs')
    </div>

@endsection
