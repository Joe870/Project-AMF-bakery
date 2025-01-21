<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vergrote Errors</title>
    @livewireStyles
    @include('includes/navbar')
    <link rel="stylesheet" href="{{ asset('css/charts.css') }}">
    <style>
        /* General Styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
            color: #333;
        }

        h2 {
            text-align: center;
            margin-top: 20px;
            color: #555;
        }

        /* Error Message */
        .error-message {
            background-color: #f8d7da;
            color: #721c24;
            padding: 10px;
            margin: 20px auto;
            width: 80%;
            border: 1px solid #f5c6cb;
            border-radius: 4px;
            text-align: center;
        }

        /* Table Styles */
        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            background-color: white;
        }

        table thead {
            background-color: #ed2027;
            color: white;
        }

        table th,
        table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        table tr:hover {
            background-color: #f1f1f1;
        }

        table th {
            font-weight: bold;
            font-size: 14px;
        }

        table td {
            font-size: 13px;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .search-bar {
                flex-direction: column;
                gap: 15px;
            }

            .search-bar form {
                flex-wrap: wrap;
            }

            table {
                width: 100%;
            }
        }
    </style>
</head>
<body>
<div class="search-bar">
    <form action="/dashboard/all-errors" method="GET">
        <label class="styled-checkbox">
            <span>Urgent</span>
            <input type="checkbox" name="urgentFilter" value="1">
        </label>
        <input type="text" name="searchTerm" placeholder="Search errors...">
        <input type="date" name="startDate" placeholder="Start Date">
        <input type="date" name="endDate" placeholder="End Date">
        <input type="hidden" name="searchTriggered" id="searchTriggered" value="false">
        <input type="hidden" name="errorMessage" id="errorMessage" value="false">
        <button type="submit" onclick="document.getElementById('searchTriggered').value='true'">Search</button>
    </form>
</div>

@if(session()->has('errorMessage'))
    <div class="error-message">
        {{ session('errorMessage') }}
    </div>
@endif

<div>
    <h2>All Errors</h2>
    <table>
        <thead>
        <tr>
            <th>Message</th>
            <th>Event Time</th>
            <th>Priority</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($errors as $error)
            <tr>
                <td>{{ $error->Message }}</td>
                <td>{{ $error->EventTime }}</td>
                <td>{{ $error->priority }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

</body>
</html>
