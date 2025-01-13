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
<h1>CSV Data</h1>

@if (!empty($data) && count($data) > 1)
    {{-- Display Total Errors and Frequent Errors --}}
    <h2>Error Summary</h2>
    <p><strong>Total Errors:</strong> {{ $errorCount }}</p>

    <h3>Most Frequent Errors:</h3>
    @if (!empty($errorFrequencies))
        <ul>
            @foreach ($errorFrequencies as $error => $count)
                <li>{{ $error }}: {{ $count }} occurrences</li>
            @endforeach
        </ul>
    @else
        <p>No errors found in the data.</p>
    @endif

    {{-- Display Duplicate Errors --}}
    <h3>Duplicate Errors in Rows:</h3>
    @if (!empty($duplicateErrorRows))
        <table>
            <thead>
            <tr>
                <th>Row Data</th>
                <th>Duplicate Error</th>
                <th>Count</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($duplicateErrorRows as $duplicate)
                <tr>
                    <td>{{ implode(', ', $duplicate['row']) }}</td>
                    <td>{{ $duplicate['error'] }}</td>
                    <td>{{ $duplicate['count'] }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @else
        <p>No duplicate errors found in rows.</p>
    @endif

    {{-- Display CSV Data --}}
    <h3>Full CSV Data</h3>
    <table>
        <thead>
            <tr>
                @foreach ($data[0] as $header) {{-- Use the first row as header --}}
                <th>{{ $header }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
        @foreach (array_slice($data, 1) as $row) {{-- Skip header row --}}
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

