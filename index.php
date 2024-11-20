<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Снимок с веб-камеры</title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; margin-top: 20px; }
        form { display: inline-block; margin-top: 20px; }
        video, canvas { border: 1px solid #ccc; margin-bottom: 20px; }
        input, button { display: block; margin: 10px auto; padding: 10px; font-size: 16px; }
        button:disabled { background-color: #ccc; cursor: not-allowed; }
    </style>
</head>
<body>
    <h1>Снимок с веб-камеры</h1>
    <video id="video" autoplay width="640" height="480"></video>
    <canvas id="canvas" width="640" height="480" style="display:none;"></canvas>
    <form id="captureForm" method="POST" enctype="multipart/form-data" action="process.php">
        <input type="text" name="full_name" id="full_name" placeholder="Введите ФИО" required>
        <input type="email" name="email" id="email" placeholder="Введите e-mail" required>
        <input type="hidden" name="image_data" id="image_data">
        <button type="button" id="captureButton" disabled>Сделать снимок</button>
        <button type="submit" id="submitButton" style="display:none;">Отправить</button>
    </form>

    <script>
        const video = document.getElementById('video');
        const canvas = document.getElementById('canvas');
        const captureButton = document.getElementById('captureButton');
        const submitButton = document.getElementById('submitButton');
        const emailInput = document.getElementById('email');
        const imageDataInput = document.getElementById('image_data');
        
        // Проверка валидности e-mail
        emailInput.addEventListener('input', () => {
            captureButton.disabled = !emailInput.checkValidity();
        });

        // Подключение к веб-камере
        navigator.mediaDevices.getUserMedia({ video: true })
            .then(stream => {
                video.srcObject = stream;
            })
            .catch(err => {
                alert('Не удалось получить доступ к веб-камере: ' + err);
            });

        // Снимок
        captureButton.addEventListener('click', () => {
            const context = canvas.getContext('2d');
            context.drawImage(video, 0, 0, canvas.width, canvas.height);
            const imageData = canvas.toDataURL('image/jpeg');
            imageDataInput.value = imageData;
            captureButton.style.display = 'none';
            submitButton.style.display = 'block';
        });
    </script>
</body>
</html>
