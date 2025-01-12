<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Upload RDB File</title>
</head>
<body>
    <form action="{{ route('upload.file') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <label for="uploadedFile">Choose RDB File:</label>
        <input type="file" name="uploadedFile" required>
        @error('uploadedFile')
            {{$message}}
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
