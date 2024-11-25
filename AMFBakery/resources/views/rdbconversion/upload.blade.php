<form action="{{ route('convert.rdb') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div>
        <label for="rdb_file">Upload RDB File:</label>
        <input type="file" name="rdb_file" id="rdb_file" accept=".rdb" required>
    </div>
    <div>
        <button type="submit">Convert to CSV</button>
    </div>
</form>