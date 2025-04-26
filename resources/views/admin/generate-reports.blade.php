<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Generate Reports - FoodBridge</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Generate Reports</h2>
        <form action="{{ route('admin.generate-reports') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label>Report Type</label>
                <select name="report_type" class="form-control" required>
                    <option value="users">All Users</option>
                    <option value="donations">Donations</option>
                    <option value="donors">Donors</option>
                    <option value="recipients">Recipients</option>
                </select>
            </div>
            <div class="mb-3">
                <label>Format</label>
                <select name="format" class="form-control" required>
                    <option value="csv">CSV</option>
                    <option value="pdf">PDF</option>
                </select>
            </div>
            <div class="mb-3">
                <label>Start Date</label>
                <input type="date" name="start_date" class="form-control">
            </div>
            <div class="mb-3">
                <label>End Date</label>
                <input type="date" name="end_date" class="form-control">
            </div>
            <button type="submit" class="btn btn-primary">Generate Report</button>
        </form>
    </div>
</body>
</html>