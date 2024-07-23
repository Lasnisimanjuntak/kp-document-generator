<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Reports</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f9;
            color: #333;
        }
        .container {
            max-width: 90%;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            margin: 20px auto;
        }
        h1 {
            font-family: 'Inter', sans-serif;
            font-weight: 700;
            color: #000;
            margin-bottom: 20px;
            text-align: center;
        }
        .btn-primary, .btn-secondary, .btn-danger {
            margin-bottom: 10px;
        }
        .btn-primary {
            background-color: #0c3f6c;
            border-color: #6c757d;
        }
        .btn-primary:hover {
            background-color: #0a314e;
            border-color: #6c757d;
        }
        .btn-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
        }
        .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
        }
        .btn {
            font-size: 0.875rem;
            padding: 0.5rem 1rem;
            border-radius: 5px;
            transition: all 0.3s ease;
        }
        .btn:hover {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .table {
            width: 100%;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px 12px;
            text-align: left;
            vertical-align: middle;
        }
        th {
            background-color: #ED2B24;
            color: #fff;
            text-align: center;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .table thead th {
            border-bottom: 2px solid #ddd;
        }
        .table tbody tr:hover {
            background-color: #e9f7ff;
        }
        .actions form {
            display: inline-block;
            margin: 0;
        }
        .actions .btn {
            margin-right: 5px;
        }
        .btn-warning {
            background-color: #fff;
            color: #ffc107;
            border-color: #ffc107;
            font-family: 'Inter', sans-serif;
            font-weight: 600;
        }
        .btn-danger {
            background-color: #fff;
            color: #dc3545;
            border-color: #dc3545;
            font-family: 'Inter', sans-serif;
            font-weight: 600;
        }
        @media print {
            #addReportBtn, #exportPdfBtn, #deleteAllForm {
                display: none !important;
            }
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <h1>Test Reports</h1>
        @if (!isset($is_pdf))
        <div class="d-flex justify-content-between mb-3">
            <div>
                <a href="{{ route('laporan.create') }}" class="btn btn-primary" id="addReportBtn">Add Report</a>
                <a href="{{ route('laporan.pdf') }}" class="btn btn-secondary" id="exportPdfBtn">Export PDF</a>
            </div>
            <form action="{{ route('laporan.destroyAll') }}" method="POST" id="deleteAllForm" onsubmit="return confirm('Are you sure you want to delete all reports?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">Delete All</button>
            </form>
        </div>
        @endif

        @php
            $uniqueHttpCodes = collect($laporan)->pluck('http_codes')->flatten()->unique()->filter();
            $uniqueLabels = collect($laporan)->pluck('labels')->flatten()->unique()->filter();
        @endphp

        <div class="table-responsive">
            <table class="table table-hover mt-4">
                <thead>
                    <tr>
                        <th rowspan="2">ID</th>
                        <th rowspan="2">Test Number</th>
                        <th rowspan="2">Virtual CCU</th>
                        <th rowspan="2">Test Time</th>
                        <th rowspan="2">Success Rate</th>
                        <th rowspan="2">Error Rate</th>
                        <th rowspan="2">Max TPS</th>
                        <th rowspan="2">Request per Minute</th>
                        <th rowspan="2">Total Request</th>
                        <th colspan="{{ $uniqueHttpCodes->count() + 1 }}">Error</th>
                        @foreach($uniqueLabels as $label)
                            <th rowspan="2">{{ $label }}</th>
                        @endforeach
                        @if (!isset($is_pdf))
                        <th rowspan="2">Actions</th>
                        @endif
                    </tr>
                    <tr>
                        <th>Not Found</th>
                        @foreach($uniqueHttpCodes as $httpCode)
                            <th>{{ $httpCode }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach($laporan as $test)
                    <tr>
                        <td>{{ $test->id }}</td>
                        <td>{{ $test->test_number }}</td>
                        <td>{{ $test->virtual_ccu }}</td>
                        <td>{{ $test->test_time }}</td>
                        <td>{{ $test->success_rate }}</td>
                        <td>{{ $test->error_rate }}</td>
                        <td>{{ $test->max_tps }}</td>
                        <td>{{ $test->request_per_minute }}</td>
                        <td>{{ $test->total_request }}</td>
                        <td>
                            @php
                                $index = array_search('Not Found', $test->http_codes);
                                echo $index !== false ? $test->total_errors[$index] : '';
                            @endphp
                        </td>
                        @foreach($uniqueHttpCodes as $httpCode)
                            <td>
                                @php
                                    $index = array_search($httpCode, $test->http_codes);
                                    echo $index !== false ? $test->total_errors[$index] : '';
                                @endphp
                            </td>
                        @endforeach
                        @foreach($uniqueLabels as $label)
                            <td>
                                @php
                                    $index = array_search($label, $test->labels);
                                    echo $index !== false ? $test->values[$index] : '';
                                @endphp
                            </td>
                        @endforeach
                        @if (!isset($is_pdf))
                        <td class="actions-col">
                            <a href="{{ route('laporan.edit', $test) }}" class="btn btn-warning">Edit</a>
                            <form action="{{ route('laporan.destroy', $test) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                        </td>
                        @endif
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
