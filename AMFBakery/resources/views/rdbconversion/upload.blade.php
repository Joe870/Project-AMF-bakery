<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload RDB File</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        h1 {
            color: #333;
        }
        form {
            margin: 20px 0;
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }
        input[type="file"] {
            margin-bottom: 10px;
        }
        button {
            padding: 8px 12px;
            font-size: 14px;
            cursor: pointer;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
        }
        button:hover {
            background-color: #0056b3;
        }
        ul {
            list-style-type: none;
            padding: 0;
        }
        li {
            margin: 5px 0;
        }
        a {
            color: #007bff;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
        .error {
            color: red;
            font-size: 13px;
        }
    </style>
</head>
<body>
<form action="{{ route('upload.file') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <label for="rdb_file">Select File:</label>
    <input type="file" name="rdb_file" accept=".rdb, .csv" required>
    @error('rdb_file')
    <div class="error">{{ $message }}</div>
    @enderror
    <button type="submit">Upload</button>
</form>

<h1>Converted CSV Files</h1>
<ul>
    @foreach ($files as $file)
        <li>
            <a href="{{ asset('storage/csv_outputs/' . $file) }}" download>{{ $file }}</a>
        </li>
    @endforeach
</ul>
</body>
</html>
