<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Permohonan Tidak Ditemukan</title>
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(to bottom right, #fdf6f0, #e9f7f6);
            color: #444;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        .container {
            background-color: #ffffffcc;
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
            text-align: center;
            max-width: 500px;
        }

        h1 {
            font-size: 24px;
            color: #d66b6b;
            margin-bottom: 16px;
        }

        p {
            font-size: 18px;
            color: #555;
        }

        .highlight {
            font-weight: bold;
            color: #6b8ed6;
        }

        .btn {
            margin-top: 24px;
            padding: 10px 20px;
            background-color: #6b8ed6;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        .btn:hover {
            background-color: #5575b8;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Ups 😕</h1>
        <?= $message ?>
        <!-- <button class="btn" onclick="window.history.back()">Kembali</button> -->
    </div>
</body>

</html>