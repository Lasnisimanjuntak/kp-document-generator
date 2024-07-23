@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card border-0 shadow">
        <div class="card-header" style="background-color: #ED2B24; color: #fff;">
            <h1 class="card-title">Edit Test Report</h1>
        </div>
        <div class="card-body">
            <form action="{{ route('laporan.update', $laporan->id) }}" method="POST" id="editReportForm">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="test_number">Test Number</label>
                            <input type="text" class="form-control" id="test_number" name="test_number" value="{{ old('test_number', $laporan->test_number) }}" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="virtual_ccu">Virtual CCU</label>
                            <input type="number" class="form-control" id="virtual_ccu" name="virtual_ccu" value="{{ old('virtual_ccu', $laporan->virtual_ccu) }}" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="test_time">Test Time (Detik)</label>
                            <input type="number" class="form-control" id="test_time" name="test_time" value="{{ old('test_time', $laporan->test_time) }}" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="success_rate">Success Rate (%)</label>
                            <div class="input-group">
                                <input type="number" step="0.01" class="form-control" id="success_rate" name="success_rate" value="{{ old('success_rate', $laporan->success_rate) }}" required>
                                <div class="input-group-append">
                                    <span class="input-group-text">%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="max_tps">Max TPS</label>
                            <input type="number" class="form-control" id="max_tps" name="max_tps" value="{{ old('max_tps', $laporan->max_tps) }}" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="total_request">Total Request</label>
                            <input type="number" class="form-control" id="total_request" name="total_request" value="{{ old('total_request', $laporan->total_request) }}" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="request_per_minute">Request per Minute (Otomatis)</label>
                            <input type="number" class="form-control" id="request_per_minute" name="request_per_minute" value="{{ old('request_per_minute', $laporan->request_per_minute) }}" readonly>
                        </div>
                        <div class="form-group mb-3">
                            <label for="error_rate">Error Rate (%) (Otomatis)</label>
                            <div class="input-group">
                                <input type="number" step="0.01" class="form-control" id="error_rate" name="error_rate" value="{{ old('error_rate', $laporan->error_rate) }}" readonly>
                                <div class="input-group-append">
                                    <span class="input-group-text">%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="http_codes">Error (Http Code & Total Error)</label>
                            <div id="http_codes_container">
                                @foreach($laporan->http_codes as $index => $code)
                                    <div class="http_code_entry input-group mb-3">
                                        <input type="text" class="form-control" name="http_codes[]" placeholder="Http Code" value="{{ old('http_codes.' . $index, $code) }}" required>
                                        <input type="number" class="form-control" name="total_errors[]" placeholder="Total Error" value="{{ old('total_errors.' . $index, $laporan->total_errors[$index]) }}" required>
                                        <div class="input-group-append">
                                            <button class="btn btn-danger" type="button" onclick="removeHttpCodeEntry(this)">Remove</button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <button type="button" class="btn btn-secondary mt-2" onclick="addHttpCodeEntry()" style="background-color: #fff; color: #ED2B24; border-color: #ED2B24;">Add Http Code</button>
                        </div>
                    </div>
                </div>

                <!-- Dynamic Fields for Extra Label and Value -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="extra_fields">Extra Fields</label>
                            <div id="extra_fields_container">
                                @if (old('extra_labels'))
                                    @foreach(old('extra_labels') as $key => $label)
                                        <div class="input-group mb-3">
                                            <input type="text" class="form-control" name="extra_labels[]" placeholder="Label" value="{{ old('extra_labels.' . $key) }}" required>
                                            <input type="text" class="form-control" name="extra_values[]" placeholder="Value" value="{{ old('extra_values.' . $key) }}" required>
                                            <div class="input-group-append">
                                                <button class="btn btn-danger" type="button" onclick="removeExtraField(this)">Remove</button>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                            <button type="button" class="btn btn-secondary mt-2" onclick="addExtraField()" style="background-color: #fff; color: #ED2B24; border-color: #ED2B24;">Add Extra Field</button>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-danger" style="background-color: #ED2B24; border-color: #ED2B24;">Update</button>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle changes in success rate
    document.getElementById('success_rate').addEventListener('input', function() {
        calculateErrorRate();
    });

    // Handle changes in error rate
    document.getElementById('error_rate').addEventListener('input', function() {
        calculateSuccessRate();
    });

    // Handle changes in test time
    document.getElementById('test_time').addEventListener('input', function() {
        calculateRequestPerMinute();
    });

    // Handle changes in total request
    document.getElementById('total_request').addEventListener('input', function() {
        calculateRequestPerMinute();
    });

    // Function to calculate error rate
    function calculateErrorRate() {
        var successRate = parseFloat(document.getElementById('success_rate').value);
        if (!isNaN(successRate)) {
            var errorRate = 100 - successRate;
            document.getElementById('error_rate').value = errorRate.toFixed(2);
        }
    }

    // Function to calculate success rate
    function calculateSuccessRate() {
        var errorRate = parseFloat(document.getElementById('error_rate').value);
        if (!isNaN(errorRate)) {
            var successRate = 100 - errorRate;
            document.getElementById('success_rate').value = successRate.toFixed(2);
        }
    }

    // Function to calculate request per minute
    function calculateRequestPerMinute() {
        var testTime = parseFloat(document.getElementById('test_time').value);
        var totalRequest = parseFloat(document.getElementById('total_request').value);
        if (!isNaN(testTime) && testTime > 0 && !isNaN(totalRequest)) {
            var requestPerMinute = (totalRequest / testTime) * 60;
            document.getElementById('request_per_minute').value = requestPerMinute.toFixed(2);
        } else {
            document.getElementById('request_per_minute').value = '';
        }
    }

    // Call error rate calculation function when the page loads
    calculateErrorRate();

    // Function to add new extra field (label and value)
    window.addExtraField = function addExtraField() {
        var container = document.getElementById('extra_fields_container');
        var fieldGroup = document.createElement('div');
        fieldGroup.className = 'input-group mb-3';
        fieldGroup.innerHTML = `
            <input type="text" class="form-control" name="labels[]" placeholder="Label" required>
            <input type="text" class="form-control" name="values[]" placeholder="Value" required>
            <div class="input-group-append">
                <button class="btn btn-danger" type="button" onclick="removeExtraField(this)">Remove</button>
            </div>
        `;
        container.appendChild(fieldGroup);
    }

    // Function to remove extra field (label and value)
    window.removeExtraField = function removeExtraField(button) {
        var fieldGroup = button.parentNode.parentNode;
        fieldGroup.parentNode.removeChild(fieldGroup);
    }

    // Function to add new Http Code and Total Error entry
    window.addHttpCodeEntry = function addHttpCodeEntry() {
        var container = document.getElementById('http_codes_container');
        var httpCodeEntry = document.createElement('div');
        httpCodeEntry.className = 'http_code_entry input-group mb-3';
        httpCodeEntry.innerHTML = `
            <input type="text" class="form-control" name="http_codes[]" placeholder="Http Code" required>
            <input type="number" class="form-control" name="total_errors[]" placeholder="Total Error" required>
            <div class="input-group-append">
                <button class="btn btn-danger" type="button" onclick="removeHttpCodeEntry(this)">Remove</button>
            </div>
        `;
        container.appendChild(httpCodeEntry);
    }

    // Function to remove Http Code and Total Error entry
    window.removeHttpCodeEntry = function removeHttpCodeEntry(button) {
        var httpCodeEntry = button.parentNode.parentNode;
        httpCodeEntry.parentNode.removeChild(httpCodeEntry);
    }
});
</script>
@endsection
