<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Upload RDB File</title>
</head>
<body>
    <form action="{{ route('convert') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <label for="rdb_file">Choose RDB File:</label>
        <input type="file" id="rdb_file" name="rdb_file" required>
        <button type="submit">Convert</button>
    </form>
</body>
</html>