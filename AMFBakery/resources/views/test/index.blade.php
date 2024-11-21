<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Drag & Drop File Upload</title>
    <style>
        .dropzone {
            width: 100%;
            height: 200px;
            border: 2px dashed #ccc;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #aaa;
            font-size: 18px;
        }
        .dropzone.dragover {
            background-color: #f0f8ff;
            border-color: #007bff;
            color: #007bff;
        }
    </style>
</head>
<body>
    <h1>Drag & Drop File Upload</h1>
    <div class="dropzone" id="dropzone">Drop files here or click to upload</div>
    <form id="upload-form" style="display: none;">
        <input type="file" id="file-input" name="file" multiple>
    </form>
    <script>
        const dropzone = document.getElementById('dropzone');
        const fileInput = document.getElementById('file-input');

        dropzone.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropzone.classList.add('dragover');
        });

        dropzone.addEventListener('dragleave', () => {
            dropzone.classList.remove('dragover');
        });

        dropzone.addEventListener('drop', (e) => {
            e.preventDefault();
            dropzone.classList.remove('dragover');
            const files = e.dataTransfer.files;
            handleFiles(files);
        });

        dropzone.addEventListener('click', () => fileInput.click());
        fileInput.addEventListener('change', () => handleFiles(fileInput.files));

        function handleFiles(files) {
            const formData = new FormData();
            for (let i = 0; i < files.length; i++) {
                formData.append('files[]', files[i]);
            }

            uploadFiles(formData);
        }

        function uploadFiles(formData) {
            fetch('/upload', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}' // Voeg CSRF-token toe in Laravel
                }
            })
            .then(response => response.json())
            .then(data => {
                console.log('Success:', data);
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }
    </script>
</body>
</html>
