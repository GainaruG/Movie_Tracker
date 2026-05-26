<?php
    $mesaj = "Bun venit la Movie Tracker!";
    $data = date("d-m-Y H:i:s");
?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movie Tracker</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            background-color: #1a1a2e;
            color: white;
        }
        .card {
            background-color: #16213e;
            border: 2px solid #6c63ff;
            border-radius: 12px;
            padding: 40px;
            text-align: center;
            max-width: 500px;
        }
        h1 { color: #6c63ff; }
        p { color: #ccc; }
        a {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 30px;
            background: #6c63ff;
            color: white;
            border-radius: 8px;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="card">
        <h1>🎬 Movie Tracker</h1>
        <p><?php echo $mesaj; ?></p>
        <p>📅 <?php echo $data; ?></p>
        <a href="login.php">Intră în cont</a>
    </div>

    <script>
        console.log("🎬 Movie Tracker pornit!");
        console.log("Data: <?php echo $data; ?>");
    </script>
</body>
</html>
