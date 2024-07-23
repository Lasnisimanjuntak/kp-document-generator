@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card border-0 shadow">
        <div class="card-header" style="background-color: #ED2B24; color: #fff;">
            <h1 class="card-title">Create Test Report</h1>
        </div>
        <div class="card-body">
            <form action="{{ route('laporan.store') }}" method="POST" id="createForm">
                @csrf
                <div class="row">
                    <!-- First Column -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="test_number">Test Number</label>
                            <input type="text" class="form-control" id="test_number" name="test_number" required>
                        </div>
                        <div class="form-group">
                            <label for="virtual_ccu">Virtual CCU</label>
                            <input type="number" class="form-control" id="virtual_ccu" name="virtual_ccu" required>
                        </div>
                        <div class="form-group">
                            <label for="test_time">Test Time (Seconds)</label>
                            <input type="number" class="form-control" id="test_time" name="test_time" required>
                        </div>
                        <div class="form-group">
                            <label for="success_rate">Success Rate (%)</label>
                            <div class="input-group">
                                <input type="number" step="0.01" class="form-control" id="success_rate" name="success_rate" placeholder="%" required>
                                <div class="input-group-append">
                                    <span class="input-group-text">%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Second Column -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="max_tps">Max TPS</label>
                            <input type="number" class="form-control" id="max_tps" name="max_tps" required>
                        </div>
                        <div class="form-group">
                            <label for="total_request">Total Request</label>
                            <input type="number" class="form-control" id="total_request" name="total_request" required>
                        </div>
                        <div class="form-group">
                            <label for="request_per_minute">Request per Minute</label>
                            <input type="number" class="form-control" id="request_per_minute" name="request_per_minute" readonly>
                        </div>
                        <div class="form-group">
                            <label for="error_rate">Error Rate (%)</label>
                            <div class="input-group">
                                <input type="number" step="0.01" class="form-control" id="error_rate" name="error_rate" placeholder="%" required>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Http Codes and Errors -->
                <div class="form-group">
                    <label for="http_codes">Http Codes and Errors</label>
                    <div id="http_codes_container">
                        <div class="http_code_entry input-group mb-3">
                            <select class="form-select" name="http_codes[]">
                                <option value="" selected>Select HTTP Code</option>
                                <option value="400">400 Bad Request</option>
                                <option value="401">401 Unauthorized</option>
                                <option value="402">402 Payment Required</option>
                                <option value="403">403 Forbidden</option>
                                <option value="404">404 Not Found</option>
                                <option value="405">405 Method Not Allowed</option>
                                <option value="500">500 Internal Server Error</option>
                                <option value="502">502 Bad Gateway</option>
                                <option value="503">503 Service Unavailable</option>
                            </select>
                            <input type="number" class="form-control" name="total_errors[]" placeholder="Total Error" required>
                            <button type="button" class="btn btn-danger ml-2" onclick="removeHttpCodeEntry(this)">Remove</button>
                        </div>
                    </div>
                    <button type="button" class="btn btn-secondary mt-2" onclick="addHttpCodeEntry()" style="background-color: #fff; color: #ED2B24; border-color: #ED2B24;">Add Http Code</button>
                </div>
                <!-- Labels and Values -->
                <div class="form-group">
                    <label for="labels_values">Labels and Values</label>
                    <div id="labels_values_container">
                        <div class="labels_values_entry input-group mb-3">
                            <input type="text" class="form-control" name="labels[]" placeholder="Label">
                            <input type="text" class="form-control" name="values[]" placeholder="Value">
                            <button type="button" class="btn btn-danger ml-2" onclick="removeLabelsValuesEntry(this)">Remove</button>
                        </div>
                    </div>
                    <button type="button" class="btn btn-secondary mt-2" onclick="addLabelsValuesEntry()" style="background-color: #fff; color: #ED2B24; border-color: #ED2B24;">Add Labels</button>
                </div>
                <!-- Submit Button -->
                <button type="submit" class="btn btn-danger mt-3">Submit</button>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var successRateInput = document.getElementById('success_rate');
    var errorRateInput = document.getElementById('error_rate');

    successRateInput.addEventListener('input', function() {
        var successRate = parseFloat(successRateInput.value);
        if (!isNaN(successRate)) {
            var errorRate = 100 - successRate;
            if (errorRate < 0) {
                errorRate = 0;
            } else if (errorRate > 100) {
                errorRate = 100;
            }
            errorRateInput.value = errorRate.toFixed(2);
        } else {
            errorRateInput.value = '';
        }
    });

    errorRateInput.addEventListener('input', function() {
        var errorRate = parseFloat(errorRateInput.value);
        if (!isNaN(errorRate)) {
            var successRate = 100 - errorRate;
            if (successRate < 0) {
                successRate = 0;
            } else if (successRate > 100) {
                successRate = 100;
            }
            successRateInput.value = successRate.toFixed(2);
        } else {
            successRateInput.value = '';
        }
    });

    var totalRequestInput = document.getElementById('total_request');
    var testTimeInput = document.getElementById('test_time');
    var requestPerMinuteInput = document.getElementById('request_per_minute');

    totalRequestInput.addEventListener('input', calculateRequestPerMinute);
    testTimeInput.addEventListener('input', calculateRequestPerMinute);

    function calculateRequestPerMinute() {
        var totalRequest = parseFloat(totalRequestInput.value);
        var testTime = parseFloat(testTimeInput.value);

        if (!isNaN(totalRequest) && !isNaN(testTime) && testTime !== 0) {
            var requestPerMinute = (totalRequest / testTime) * 60; // 1 minute equals 60 seconds
            requestPerMinuteInput.value = requestPerMinute.toFixed(2);
        } else {
            requestPerMinuteInput.value = '';
        }
    }
});

function addHttpCodeEntry() {
    var container = document.getElementById('http_codes_container');
    var entry = document.createElement('div');
    entry.className = 'http_code_entry input-group mb-3';
    entry.innerHTML = `<select class="form-select" name="http_codes[]">
                            <option value="" selected>Select HTTP Code</option>
                            <option value="400">400 Bad Request</option>
                            <option value="401">401 Unauthorized</option>
                            <option value="402">402 Payment Required</option>
                            <option value="403">403 Forbidden</option>
                            <option value="404">404 Not Found</option>
                            <option value="405">405 Method Not Allowed</option>
                            <option value="500">500 Internal Server Error</option>
                            <option value="502">502 Bad Gateway</option>
                            <option value="503">503 Service Unavailable</option>
                        </select>
                        <input type="number" class="form-control" name="total_errors[]" placeholder="Total Error" required>
                        <button type="button" class="btn btn-danger ml-2" onclick="removeHttpCodeEntry(this)">Remove</button>`;
    container.appendChild(entry);
}

function removeHttpCodeEntry(button) {
    button.parentElement.remove();
}

function addLabelsValuesEntry() {
    var container = document.getElementById('labels_values_container');
    var entry = document.createElement('div');
    entry.className = 'labels_values_entry input-group mb-3';
    entry.innerHTML = '<input type="text" class="form-control" name="labels[]" placeholder="Label"> <input type="text" class="form-control" name="values[]" placeholder="Value"> <button type="button" class="btn btn-danger ml-2" onclick="removeLabelsValuesEntry(this)">Remove</button>';
    container.appendChild(entry);
}

function removeLabelsValuesEntry(button) {
    button.parentElement.remove();
}

</script>
@endsection
