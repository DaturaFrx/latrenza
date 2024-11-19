<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hashing de Texto en Vivo</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            margin-top: 50px;
        }

        h2 {
            text-align: center;
            color: #4CAF50;
        }

        .error-message {
            background-color: #f8d7da;
            padding: 10px;
            border: 1px solid #f5c6cb;
            color: #721c24;
            border-radius: 5px;
        }

        input[type="text"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 16px;
        }

        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background-color: #45a049;
        }

        textarea {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 16px;
            height: 150px;
        }

        .go-back-btn {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border-radius: 5px;
            text-decoration: none;
        }

        .go-back-btn:hover {
            background-color: #0056b3;
        }
    </style>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/4.1.1/crypto-js.js"></script>
    <script>
        function hashText() {
            const text = document.getElementById('plaintext').value;
            if (text.length > 0) {
                // Using SHA-256 for live hashing (you can replace with any hashing method)
                const hash = CryptoJS.SHA256(text).toString(CryptoJS.enc.Base64);
                document.getElementById('hashed-text').value = hash;
            } else {
                document.getElementById('hashed-text').value = ''; // Clear if input is empty
            }
        }

        function copyHash() {
            const hashField = document.getElementById('hashed-text');
            hashField.select();
            hashField.setSelectionRange(0, 99999); // For mobile devices
            document.execCommand('copy');
            alert("Hash copiado al portapapeles!");
        }
    </script>
</head>

<body>
    <div class="container">
        <h2>Hash PHP</h2>

        <form>
            <label for="plaintext">Texto:</label>
            <input type="text" id="plaintext" oninput="hashText()" placeholder="Escribe tu texto aquí" required>

            <h3>Hash:</h3>
            <textarea id="hashed-text" readonly placeholder="El hash del texto aparecerá aquí..."></textarea>
        </form>

        <button class="go-back-btn" onclick="window.history.back()">Volver</button>
        <button class="go-back-btn" onclick="copyHash()">Copiar Hash</button>
    </div>
</body>

</html>