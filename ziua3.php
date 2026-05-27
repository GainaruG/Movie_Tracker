<?php
if (PHP_SAPI === 'cli') {
    // Generăm un array aleatoriu de 10 elemente sau îl citim dacă este dat ca argument
    $numere = [];
    if ($argc > 1) {
        $input = explode(',', $argv[1]);
        $numere = array_map('intval', $input);
        if (count($numere) < 10) {
            $numere = array_merge($numere, array_fill(0, 10 - count($numere), 0));
        }
        $numere = array_slice($numere, 0, 10);
    } else {
        for ($i = 0; $i < 10; $i++) {
            $numere[] = rand(1, 100);
        }
    }

    $pare = 0;
    $impare = 0;

    echo "\n=========================================\n";
    echo "            P A R I T Y .\n";
    echo "=========================================\n";
    echo "Numerele analizate: " . implode(", ", $numere) . "\n\n";

    // Utilizăm instrucțiunea for pentru a parcurge șirul
    for ($i = 0; $i < 10; $i++) {
        $curent = $numere[$i];
        
        // Utilizăm instrucțiunea if pentru a verifica paritatea
        if ($curent % 2 === 0) {
            echo " -> Pozitia " . ($i + 1) . ": Numărul " . str_pad($curent, 3, " ", STR_PAD_LEFT) . " este PAR\n";
            $pare++;
        } else {
            echo " -> Pozitia " . ($i + 1) . ": Numărul " . str_pad($curent, 3, " ", STR_PAD_LEFT) . " este IMPAR\n";
            $impare++;
        }
    }

    echo "\n-----------------------------------------\n";
    echo "REZULTATE FINALE:\n";
    echo "-----------------------------------------\n";
    echo " Total numere PARE:   $pare\n";
    echo " Total numere IMPARE: $impare\n";
    echo "=========================================\n\n";
    exit;
}

// Generăm sau procesăm numerele pentru interfața web
$numere = [];
$status_mesaj = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['numere_input'])) {
    $raw_input = $_POST['numere_input'];
    // Separăm elementele prin virgulă și curățăm spațiile
    $parts = explode(',', $raw_input);
    $filtered = [];
    foreach ($parts as $part) {
        $trimmed = trim($part);
        if ($trimmed !== '') {
            $filtered[] = intval($trimmed);
        }
    }
    
    $numere = $filtered;
    
    // Corectăm lungimea șirului la exact 10 elemente
    if (count($numere) < 10) {
        $completare = 10 - count($numere);
        $numere = array_merge($numere, array_fill(0, $completare, 0));
        $status_mesaj = 'Șirul a fost completat automat cu valoarea 0 până la limita de 10 numere.';
    } elseif (count($numere) > 10) {
        $numere = array_slice($numere, 0, 10);
        $status_mesaj = 'Șirul a fost limitat automat la primele 10 numere introduse.';
    }
} else {
    // Generare implicită la prima încărcare
    for ($i = 0; $i < 10; $i++) {
        $numere[] = rand(1, 100);
    }
}

// Pregătim valorile ca text pentru a fi afișate în câmpul de input
$numere_string = implode(', ', $numere);

// Calculăm paritatea
$pare = 0;
$impare = 0;
$rezultate = [];

for ($i = 0; $i < 10; $i++) {
    $valoare = $numere[$i];
    $este_par = ($valoare % 2 === 0);
    if ($este_par) {
        $pare++;
    } else {
        $impare++;
    }
    $rezultate[] = [
        'valoare' => $valoare,
        'paritate' => $este_par ? 'par' : 'impar',
        'pozitie' => $i + 1
    ];
}
?>
<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parity. — Analiză Numerică</title>
    
    <!-- Google Fonts cu un font serif elegant (Lora) și un geometric modern (Plus Jakarta Sans) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,400;0,500;1,400&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --bg: #09090b;
            --card-bg: #121214;
            --border: #222226;
            --border-hover: #2e2e33;
            
            --text-main: #f4f4f5;
            --text-muted: #8e8e93;
            
            --accent-even: #10b981;
            --accent-even-bg: rgba(16, 185, 129, 0.05);
            --accent-odd: #ef4444;
            --accent-odd-bg: rgba(239, 68, 68, 0.05);
            
            --focus-ring: #3b82f6;
        }

        body {
            margin: 0;
            padding: 0;
            background-color: var(--bg);
            color: var(--text-main);
            font-family: 'Plus Jakarta Sans', sans-serif;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow-x: hidden;
            letter-spacing: -0.01em;
        }

        /* Fundal geometric minimalist tip human-design - rețea fină de pixeli */
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            background-image: 
                radial-gradient(var(--border) 1px, transparent 1px);
            background-size: 24px 24px;
            opacity: 0.4;
        }

        .wrapper {
            width: 100%;
            max-width: 680px;
            padding: 2.5rem 1.5rem;
            box-sizing: border-box;
            animation: slideUp 0.6s cubic-bezier(0.16, 1, 0.3, 1);
        }

        header {
            margin-bottom: 3rem;
            text-align: center;
        }

        header .logo {
            font-family: 'Lora', serif;
            font-size: 2.2rem;
            font-weight: 500;
            color: var(--text-main);
            margin: 0 0 0.5rem 0;
            letter-spacing: -0.02em;
        }

        header .subtitle {
            font-size: 0.95rem;
            color: var(--text-muted);
            margin: 0;
            font-weight: 400;
            max-width: 480px;
            margin-left: auto;
            margin-right: auto;
            line-height: 1.5;
        }

        .main-card {
            background-color: var(--card-bg);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 2rem;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.3);
        }

        .input-group {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
            margin-bottom: 1.5rem;
        }

        .input-label {
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--text-muted);
        }

        .input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .text-input {
            width: 100%;
            background-color: #030303;
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 1rem;
            color: var(--text-main);
            font-family: 'JetBrains Mono', monospace;
            font-size: 1.05rem;
            transition: all 0.25s ease;
            box-sizing: border-box;
        }

        .text-input:focus {
            outline: none;
            border-color: var(--focus-ring);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
        }

        .actions {
            display: grid;
            grid-template-columns: 1.2fr 1fr;
            gap: 1rem;
            margin-bottom: 2rem;
        }

        @media (max-width: 480px) {
            .actions {
                grid-template-columns: 1fr;
            }
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.85rem 1.5rem;
            border-radius: 8px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 0.9rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            box-sizing: border-box;
            text-decoration: none;
        }

        .btn-primary {
            background-color: var(--text-main);
            color: var(--bg);
            border: 1px solid var(--text-main);
        }

        .btn-primary:hover {
            background-color: #ffffff;
            transform: translateY(-1px);
        }

        .btn-secondary {
            background-color: transparent;
            color: var(--text-main);
            border: 1px solid var(--border);
        }

        .btn-secondary:hover {
            border-color: var(--border-hover);
            background-color: rgba(255, 255, 255, 0.02);
            transform: translateY(-1px);
        }

        .status-banner {
            font-size: 0.8rem;
            padding: 0.75rem 1rem;
            border-radius: 6px;
            background-color: rgba(59, 130, 246, 0.05);
            border: 1px solid rgba(59, 130, 246, 0.1);
            color: #93c5fd;
            margin-bottom: 1.5rem;
        }

        .divider {
            height: 1px;
            background-color: var(--border);
            margin: 2rem 0;
        }

        .results-section h3 {
            font-family: 'Lora', serif;
            font-size: 1.25rem;
            font-weight: 500;
            margin: 0 0 1.25rem 0;
            color: var(--text-main);
        }

        .results-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 0.75rem;
            margin-bottom: 2rem;
        }

        @media (max-width: 600px) {
            .results-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        .result-item {
            background-color: #030303;
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 1rem 0.5rem;
            text-align: center;
            transition: all 0.2s ease;
            position: relative;
        }

        .result-item:hover {
            border-color: var(--border-hover);
            transform: translateY(-2px);
        }

        .result-item.par {
            border-left: 3px solid var(--accent-even);
            background-color: var(--accent-even-bg);
        }

        .result-item.impar {
            border-left: 3px solid var(--accent-odd);
            background-color: var(--accent-odd-bg);
        }

        .result-val {
            font-family: 'JetBrains Mono', monospace;
            font-size: 1.4rem;
            font-weight: 500;
            display: block;
            margin-bottom: 0.25rem;
        }

        .result-label {
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .result-item.par .result-val,
        .result-item.par .result-label {
            color: var(--accent-even);
        }

        .result-item.impar .result-val,
        .result-item.impar .result-label {
            color: var(--accent-odd);
        }

        .summary-box {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            background-color: #030303;
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 1.25rem;
        }

        .summary-col {
            text-align: center;
        }

        .summary-col:first-child {
            border-right: 1px solid var(--border);
        }

        .summary-val {
            font-family: 'JetBrains Mono', monospace;
            font-size: 2.2rem;
            font-weight: 600;
            line-height: 1;
            margin-bottom: 0.25rem;
        }

        .summary-col.even-col .summary-val {
            color: var(--accent-even);
        }

        .summary-col.odd-col .summary-val {
            color: var(--accent-odd);
        }

        .summary-lbl {
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(12px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <header>
            <h1 class="logo">Parity.</h1>
            
                
            </p>
        </header>

        <main class="main-card">
            <?php if ($status_mesaj): ?>
                <div class="status-banner">
                    <?php echo htmlspecialchars($status_mesaj); ?>
                </div>
            <?php endif; ?>

            <form id="analiza-form" method="POST" action="">
                <div class="input-group">
                    <label class="input-label" for="numere_input">Șirul tău de 10 numere</label>
                    <div class="input-wrapper">
                        <input type="text" id="numere_input" name="numere_input" class="text-input" value="<?php echo htmlspecialchars($numere_string); ?>" autocomplete="off" placeholder="Ex: 12, 45, 78...">
                    </div>
                </div>

                <div class="actions">
                    <button type="submit" class="btn btn-primary">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                        Analizează șirul
                    </button>
                    <button type="button" class="btn btn-secondary" onclick="generateRandom()">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21.5 2v6h-6M21.34 15.57a10 10 0 1 1-.57-8.38l5.67-5.67"/></svg>
                        Valori aleatorii
                    </button>
                </div>
            </form>

            <div class="divider"></div>

            <div class="results-section">
                <h3>Rezultatele analizei</h3>
                
                <div class="results-grid">
                    <?php foreach ($rezultate as $r): ?>
                        <div class="result-item <?php echo $r['paritate']; ?>">
                            <span class="result-val"><?php echo $r['valoare']; ?></span>
                            <span class="result-label"><?php echo $r['paritate']; ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="summary-box">
                    <div class="summary-col even-col">
                        <div class="summary-val"><?php echo $pare; ?></div>
                        <div class="summary-lbl">Numere Pare</div>
                    </div>
                    <div class="summary-col odd-col">
                        <div class="summary-val"><?php echo $impare; ?></div>
                        <div class="summary-lbl">Numere Impare</div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        // Funcția de generare a unor valori aleatorii noi, fără reîncărcarea cache-uită a paginii
        function generateRandom() {
            // Generăm 10 valori întregi între 1 și 100
            const randomNums = Array.from({length: 10}, () => Math.floor(Math.random() * 100) + 1);
            // Populăm input-ul din pagină
            document.getElementById('numere_input').value = randomNums.join(', ');
            // Trimitem formularul automat pentru a procesa noile date direct în PHP
            document.getElementById('analiza-form').submit();
        }
    </script>
</body>
</html>
