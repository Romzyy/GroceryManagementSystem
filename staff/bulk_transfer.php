<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Files</title>
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
      body {
                font-family: 'Roboto', sans-serif;
                background-color: #f7f9fc;
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
                margin: 0;
            }
            .container {
                background-color: #fff;
                padding: 20px;
                border-radius: 8px;
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
                width: 100%;
                max-width: 400px;
            }
            h2 {
                text-align: center;
                margin-top: 0;
                color: #333;
            }
            input[type="file"] {
                width: 100%;
                margin-bottom: 10px;
            }
            button {
                background-color: #1967ff;
                color: white;
                padding: 10px 20px;
                border: none;
                border-radius: 5px;
                cursor: pointer;
                width: 100%;
            }
            button:hover {
                background-color: #1967ff;
            }
            .file-list {
                margin-top: 10px;
            }
            .file-item {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 5px;
                border: 1px solid #ddd;
                border-radius: 5px;
                margin-bottom: 5px;
                background-color: #f9f9f9;
            }
            .remove-btn {
                background-color: #ff5c5c;
                border: none;
                color: white;
                padding: 5px;
                border-radius: 5px;
                cursor: pointer;
                font-size: 14px; 
                width: auto;
                display: flex; 
                align-items: center; 
            }
            .remove-btn i {
                font-size: 18px;          
            }
            .remove-btn:hover {
                background-color: #ff1c1c;
            }
            .message {
                margin-top: 20px;
                font-size: 0.9em;
                color: #555;
            }
    </style>    
</head>
<body>
    <div class="container">
        <h2>Upload Files</h2>
        <form id="uploadForm" method="post" enctype="multipart/form-data" action="upload_files.php">
            <input type="file" id="fileInput" name="files[]" multiple accept=".csv, .json, .xml">
            <div class="file-list" id="fileList"></div>
            <button type="submit">Upload</button>
        </form>
        <div class="message" id="message"></div>
    </div>
    <script>
        const fileInput = document.getElementById('fileInput');
        const fileList = document.getElementById('fileList');
        const message = document.getElementById('message');
        let selectedFiles = [];

        fileInput.addEventListener('change', function(event) {
            const files = Array.from(event.target.files);
            selectedFiles = [...selectedFiles, ...files];
            renderFile();
        });

        function renderFile() {
            fileList.innerHTML = '';
            selectedFiles.forEach((file, index) => {
                const fileItem = document.createElement('div');
                fileItem.className = 'file-item';
                fileItem.innerHTML = `
                    ${file.name} (${(file.size / 1024).toFixed(2)} KB)
                    <button class="remove-btn" onclick="removeFile(${index})"><i class="fas fa-trash-alt"></i></button>
                `;
                fileList.appendChild(fileItem);
            });
        }

        function removeFile(index) {
            selectedFiles.splice(index, 1);
            renderFileList();
        }

        document.getElementById('uploadForm').addEventListener('submit', function(event) {
            event.preventDefault();

            if (selectedFiles.length === 0) {
                alert('No files selected.');
                return;
            }

            const formData = new FormData();
            selectedFiles.forEach(file => formData.append('files[]', file));

            fetch('upload_files.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                message.innerHTML = data;
                selectedFiles = [];
                renderFileList();
            })
            .catch(error => {
                message.innerHTML = 'An error occurred while uploading files.';
                console.error('Error:', error);
            });
        });
    </script>
</body>
</html>
