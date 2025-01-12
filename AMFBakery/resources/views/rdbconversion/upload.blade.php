{{--<!DOCTYPE html>--}}
{{--<html lang="en">--}}
{{--<head>--}}
{{--    <meta charset="UTF-8">--}}
{{--    <title>Upload RDB File</title>--}}
{{--</head>--}}
{{--<body>--}}
{{--    <form action="{{ route('upload.file') }}" method="POST" enctype="multipart/form-data">--}}
{{--        @csrf--}}
{{--        <label for="rdb_file">Choose RDB File:</label>--}}
{{--        <input type="file" name="rdb_file" accept=".rdb, .csv" required>--}}
{{--        @error('rdb_file')--}}
{{--            {{$message}}--}}
{{--        @enderror--}}
{{--        <button type="submit">Upload</button>--}}
{{--    </form>--}}

{{--    <h1>Converted CSV Files</h1>--}}
{{--    <ul>--}}
{{--        @foreach ($files as $file)--}}
{{--            <li>--}}
{{--                <a href="{{ asset('storage/csv_outputs/' . $file) }}" download>{{ $file }}</a>--}}
{{--            </li>--}}
{{--        @endforeach--}}
{{--    </ul>--}}
{{--</body>--}}
{{--</html>--}}
    <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload CSV File</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 40px;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        form {
            background: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }

        label {
            font-size: 14px;
            margin-bottom: 8px;
            display: block;
            color: #333;
        }

        input[type="file"] {
            display: block;
            margin: 10px 0 20px;
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
            width: 100%;
        }

        button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
        }

        button:hover {
            background-color: #45a049;
        }

        .error-message {
            color: red;
            font-size: 12px;
        }
    </style>
</head>
<body>
<form action="{{ route('validate.upload.csv') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <label for="csv_file">Upload CSV File:</label>
    <input type="file" name="csv_file" accept=".csv" required>

    {{-- Display error message --}}
    @if ($errors->has('csv_file'))
        <div class="error-message">
            {{ $errors->first('csv_file') }}
        </div>
    @endif

    {{-- Show expected and actual headers if available in the session --}}
    @if (session('expected_headers') && session('actual_headers'))
        <div>
            <p><strong>Expected Headers:</strong></p>
            <ul>
                @foreach (session('expected_headers') as $header)
                    <li>{{ $header }}</li>
                @endforeach
            </ul>

            <p><strong>Actual Headers:</strong></p>
            <ul>
                @foreach (session('actual_headers') as $header)
                    <li>{{ $header }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <button type="submit">Upload</button>
</form>
</body>
</html>
