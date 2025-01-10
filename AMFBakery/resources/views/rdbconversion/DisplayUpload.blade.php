<!doctype html>
<html>
 <head>
    <title>display if upload was succesfol</title>
</head>

<body>
    @if(session('success'))
        <p>{{ session('success') }}</p>
        <img src="{{ Storage::disk('public')->url(session('file')) }}" alt="Uploaded File">
    @endif
    @if(session('error'))
        <p>{{ session('error') }}</p>
    @endif
    <form action="/upload" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="file" name="file">
        <button type="submit">Upload</button>
    </form>
    <img src="{{ Storage::disk('public')->url(session('file')) }}" alt="Uploaded File">
</body>
</html>
