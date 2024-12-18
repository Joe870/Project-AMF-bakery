<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Converted CSV Files</title>
</head>
<body>
    <h1>CSV Files</h1>
    <ul>
        @foreach ($files as $file)
            <li>
                <a href="{{ asset('storage/csv_outputs/' . $file) }}" download>{{ $file }}</a>
            </li>
        @endforeach
    </ul>
</body>
</html>