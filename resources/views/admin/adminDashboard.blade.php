{{-- @extends('admin.layouts.adminDash')

@section('title', 'Admin Dashboard')

@section('mainContent')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Task Management Dashboard</title>
   
    <style>
       
        .main-content {
            flex: 1;
            padding: 30px;
        }

        .dashboard-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .dashboard-header h1 {
            font-size: 2rem;
            margin-bottom: 20px;
        }

        .stats {
            display: flex;
            gap: 30px;
            margin-bottom: 30px;
        }

        .card {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            flex: 1;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }

        .card h3 {
            font-size: 1.2rem;
            color: #334155;
            margin-bottom: 10px;
        }

        .card .number {
            font-size: 2rem;
            font-weight: bold;
            color: #0f172a;
        }

        .graphs {
            display: flex;
            gap: 30px;
        }

        .graph {
            background: #fff;
            padding: 20px;
            flex: 1;
            border-radius: 8px;
            min-height: 250px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }

        .graph h4 {
            margin-bottom: 10px;
            color: #334155;
        }

        .placeholder {
            background-color: #e2e8f0;
            height: 180px;
            border-radius: 6px;
        }
    </style>
</head>
<body>


    <div class="main-content">
        <div class="dashboard-header">
            <h1>Task Management Dashboard</h1>
        </div>

        <div class="stats">
            <div class="card">
                <h3>Total Projects</h3>
                <div class="number">12</div>
            </div>
            <div class="card">
                <h3>Total Tasks</h3>
                <div class="number">37</div>
            </div>
            <div class="card">
                <h3>My Tasks</h3>
                <div class="number">8</div>
            </div>
        </div>

        <div class="graphs">
            <div class="graph">
                <h4>Task Status Overview</h4>
                <div class="placeholder">[ Pie Chart Placeholder ]</div>
            </div>
            <div class="graph">
                <h4>Tasks by Priority</h4>
                <div class="placeholder">[ Bar Chart Placeholder ]</div>
            </div>
        </div>
    </div>

</body>
</html>

@endsection --}}