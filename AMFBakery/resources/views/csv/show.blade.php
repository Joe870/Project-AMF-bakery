<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CSV Weergave</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">CSV Bestandsweergave</h1>

        <!-- CSV Upload Formulier -->
        <div class="mb-4">
            <form action="{{ route('upload') }}" method="POST" enctype="multipart/form-data" class="d-flex">
                @csrf
                <input type="file" name="csv_file" class="form-control me-2" accept=".csv" required>
                <button type="submit" class="btn btn-primary">Upload CSV</button>
            </form>
        </div>

        <!-- Zoekfilter -->
        <div class="mb-4">
            <form action="{{ route('csv.show') }}" method="GET" class="row g-3">
                <div class="col-md-5">
                    <label for="start_date" class="form-label">Van</label>
                    <input type="date" name="start_date" id="start_date" class="form-control" value="{{ request('start_date') }}">
                </div>
                <div class="col-md-5">
                    <label for="end_date" class="form-label">Tot</label>
                    <input type="date" name="end_date" id="end_date" class="form-control" value="{{ request('end_date') }}">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                </div>
            </form>
        </div>

        <!-- Toon fouten bij het uploaden -->
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Controleer of data beschikbaar is -->
        @if(!empty($data) && count($data) > 0)
            <table class="table table-bordered">
                <thead>
                    <tr>
                        @foreach ($data[0] as $header)
                            <th>{{ $header }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $key => $row)
                        @if($key > 0) {{-- Sla de header over --}}
                            <tr>
                                @foreach ($row as $cell)
                                    <td>{{ $cell }}</td>
                                @endforeach
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="alert alert-warning">
                {{ session('message', 'Geen gegevens om weer te geven. Upload een bestand of pas een filter toe.') }}
            </div>
        @endif
    </div>
</body>
</html>
