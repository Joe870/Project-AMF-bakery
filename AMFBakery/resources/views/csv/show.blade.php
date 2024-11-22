<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CSV Data</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            line-height: 1.5;
        }
        h1, h2 {
            color: #333;
        }
        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
    </style>
</head>
<body>
@if (!empty($data) && count($data) > 1)
    <p><strong>Total Errors:</strong> {{ $errorCount }}</p>

    <h3>Duplicate errors:</h3>
    @if (!empty($duplicateMessages))
        <table>
            <thead>
            <tr>
                <th>Message</th>
                <th>Count</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($duplicateMessages as $duplicate)
                <tr>
                    <td>{{ $duplicate['message'] }}</td>
                    <td>{{ $duplicate['count'] }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @endif


    <h3>Full CSV Data</h3>
    <table>
        <thead>
        <tr>
            @foreach ($data[0] as $header)
            <th>{{ $header }}</th>
            @endforeach
        </tr>
        </thead>
        <tbody>
        @foreach (array_slice($data, 1) as $row)
        <tr>
            @foreach ($row as $cell)
                <td>{{ $cell }}</td>
            @endforeach
        </tr>
        @endforeach
        </tbody>
    </table>
@else
    <p>No data available to display.</p>
@endif

</body>
</html>

