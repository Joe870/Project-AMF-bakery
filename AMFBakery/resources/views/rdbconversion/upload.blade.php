<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload CSV File</title>
    <style>
        .uploader {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 40px;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 75vh;
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
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.querySelector('form');
            const fileInput = form.querySelector('input[type="file"]');
            const errorMessage = document.createElement('div');
            errorMessage.className = 'error-message';
            fileInput.insertAdjacentElement('afterend', errorMessage);

            fileInput.addEventListener('change', function (event) {
                const file = event.target.files[0];
                const maxSize = 2 * 1024 * 1024; // 2 MB

                if (file && file.size > maxSize) {
                    errorMessage.textContent = 'File is too large. Maximum size is 2 MB.';
                    fileInput.value = ''; // Reset file input
                } else {
                    errorMessage.textContent = ''; // Clear error message
                }
            });
        });
    </script>
</head>
<body>
@include('includes/navbar')
<div class="uploader">
    <form id="csv-upload-form" action="{{ route('validate.upload.csv') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <label for="csv_file">Upload CSV File:</label>
        <input type="file" name="csv_file" accept=".csv" required>
        @if ($errors->has('csv_file'))
            <div class="error-message">
                {{ $errors->first('csv_file') }}
            </div>
        @endif

        @if (session('error_message'))
            <div class="error-message">
                {{ session('error_message') }}
            </div>
        @endif

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
        <label style="margin-top: 6px;">
            <input type="checkbox" name="consent" id="consent" required>
            I consent to the processing of my data (<a href="{{ route('privacy.policy') }}" target="_blank">Privacy Policy</a>)
        </label>

        @if ($errors->has('consent'))
            <div class="error-message">
                {{ $errors->first('consent') }}
            </div>
        @endif
    </form>

    <p id="fallback-error" class="error-message" style="display: none;">File upload failed due to size limit.</p>
</form>
</div>
</body>
</html>


{{--<!DOCTYPE html>--}}
{{--<html lang="en">--}}
{{--<head>--}}
{{--    <meta charset="UTF-8">--}}
{{--    <meta name="viewport" content="width=device-width, initial-scale=1.0">--}}
{{--    <title>Upload CSV File</title>--}}
{{--    <style>--}}
{{--        .uploader {--}}
{{--            font-family: Arial, sans-serif;--}}
{{--            /*background-color: #f9f9f9;*/--}}
{{--            margin: 0;--}}
{{--            padding: 40px;--}}
{{--            display: flex;--}}
{{--            justify-content: center;--}}
{{--            align-items: center;--}}
{{--            height: 75vh;--}}
{{--        }--}}

{{--        .uploadForm {--}}
{{--            background: #ffffff;--}}
{{--            padding: 20px;--}}
{{--            border-radius: 8px;--}}
{{--            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);--}}
{{--            width: 100%;--}}
{{--            max-width: 400px;--}}
{{--        }--}}

{{--        label {--}}
{{--            font-size: 14px;--}}
{{--            margin-bottom: 8px;--}}
{{--            display: block;--}}
{{--            color: #333;--}}
{{--        }--}}

{{--        input[type="file"] {--}}
{{--            display: block;--}}
{{--            margin: 10px 0 20px;--}}
{{--            padding: 5px;--}}
{{--            border: 1px solid #ccc;--}}
{{--            border-radius: 4px;--}}
{{--            width: 100%;--}}
{{--        }--}}

{{--        button {--}}
{{--            background-color: #00d83c;--}}
{{--            color: white;--}}
{{--            padding: 10px 20px;--}}
{{--            border: none;--}}
{{--            border-radius: 20px;--}}
{{--            cursor: pointer;--}}
{{--            transition: background-color 0.3s ease;--}}
{{--        }--}}

{{--        button:hover {--}}
{{--            background-color: black; /* Like other hover effects */--}}
{{--            color: white;--}}
{{--        }--}}

{{--        .error-message {--}}
{{--            color: red;--}}
{{--            font-size: 12px;--}}
{{--        }--}}
{{--    </style>--}}
{{--    <script>--}}
{{--        document.addEventListener('DOMContentLoaded', function () {--}}
{{--            const form = document.querySelector('form');--}}
{{--            const fileInput = form.querySelector('input[type="file"]');--}}
{{--            const errorMessage = document.createElement('div');--}}
{{--            errorMessage.className = 'error-message';--}}
{{--            fileInput.insertAdjacentElement('afterend', errorMessage);--}}

{{--            fileInput.addEventListener('change', function (event) {--}}
{{--                const file = event.target.files[0];--}}
{{--                const maxSize = 2 * 1024 * 1024; // 2 MB--}}

{{--                if (file && file.size > maxSize) {--}}
{{--                    errorMessage.textContent = 'File is too large. Maximum size is 2 MB.';--}}
{{--                    fileInput.value = ''; // Reset file input--}}
{{--                } else {--}}
{{--                    errorMessage.textContent = ''; // Clear error message--}}
{{--                }--}}
{{--            });--}}
{{--        });--}}
{{--    </script>--}}
{{--</head>--}}
{{--<body>--}}
{{--@include('includes/navbar')--}}
{{--<div class="uploader">--}}
{{--    <form id="csv-upload-form" class="uploadForm" action="{{ route('validate.upload.csv') }}" method="POST" enctype="multipart/form-data">--}}
{{--        @csrf--}}
{{--        <label for="csv_file">Upload CSV File:</label>--}}
{{--        <input type="file" name="csv_file" accept=".csv" required>--}}
{{--        @if ($errors->has('csv_file'))--}}
{{--            <div class="error-message">--}}
{{--                {{ $errors->first('csv_file') }}--}}
{{--            </div>--}}
{{--        @endif--}}

{{--        @if (session('error_message'))--}}
{{--            <div class="error-message">--}}
{{--                {{ session('error_message') }}--}}
{{--            </div>--}}
{{--        @endif--}}

{{--        @if (session('expected_headers') && session('actual_headers'))--}}
{{--            <div>--}}
{{--                <p><strong>Expected Headers:</strong></p>--}}
{{--                <ul>--}}
{{--                    @foreach (session('expected_headers') as $header)--}}
{{--                        <li>{{ $header }}</li>--}}
{{--                    @endforeach--}}
{{--                </ul>--}}

{{--                <p><strong>Actual Headers:</strong></p>--}}
{{--                <ul>--}}
{{--                    @foreach (session('actual_headers') as $header)--}}
{{--                        <li>{{ $header }}</li>--}}
{{--                    @endforeach--}}
{{--                </ul>--}}
{{--            </div>--}}
{{--        @endif--}}

{{--        <button type="submit">Upload</button>--}}
{{--    </form>--}}
{{--    <p id="fallback-error" class="error-message" style="display: none;">File upload failed due to size limit.</p>--}}
{{--</div>--}}
{{--</body>--}}
{{--</html>--}}


