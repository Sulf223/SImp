<?php
// Include conexiune.php doar când avem nevoie
$cod_sursa = '';
$run_id = isset($_GET['run_id']) ? (int)$_GET['run_id'] : 0;

if ($run_id > 0) {
    include_once 'conexiune.php';

    $sql = "SELECT fisier_cpp FROM metode WHERE id_metoda = ?";
    if ($stmt = $con->prepare($sql)) {
        $stmt->bind_param("i", $run_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            $fisier_cpp = $row['fisier_cpp'];
            if (!empty($fisier_cpp)) {
                $file_path = '../CPP/' . $fisier_cpp;
                if (file_exists($file_path)) {
                    $cod_sursa = file_get_contents($file_path);
                }
            }
        }
        $stmt->close();
    }
    $con->close();
}

// Cod implicit dacă nu avem altceva
if (empty($cod_sursa)) {
    $cod_sursa = "#include <iostream>\nusing namespace std;\n\nint main() {\n    cout << \"Hello, World!\" << endl;\n    return 0;\n}";
}
?>
<section class="compilator-section">
    <h2>🚀 Compilator C++ Online - Rapid & Gratuit</h2>
    <p class="compilator-intro">
        Editor C++ profesional cu execuție instant. Scrie cod, apasă <strong>Run</strong> și vezi rezultatul imediat!
    </p>

    <div class="compiler-container">
        <!-- OneCompiler Embed - fără reclame, rapid și gratuit! -->
        <iframe 
            src="https://onecompiler.com/embed/cpp?hideLanguageSelection=true&hideNew=true&hideNewFileOption=true&availableLanguages=true&hideTitle=true" 
            width="100%" 
            height="600" 
            loading="lazy"
            frameborder="0"
            style="border-radius: 12px; border: 2px solid rgba(102,126,234,0.25);">
        </iframe>
    </div>

    <?php if (!empty($cod_sursa) && $run_id > 0): ?>
    <div class="code-box">
        <div class="code-header">
            <span>📝 Cod pentru această metodă de sortare:</span>
            <button onclick="copySourceCode()" class="copy-btn">📋 Copiază</button>
        </div>
        <pre class="code-content"><code><?php echo htmlspecialchars($cod_sursa); ?></code></pre>
    </div>
    
    <script>
    function copySourceCode() {
        const code = <?php echo json_encode($cod_sursa); ?>;
        navigator.clipboard.writeText(code).then(() => {
            const btn = event.target;
            const original = btn.innerHTML;
            btn.innerHTML = '✅ Copiat!';
            btn.style.background = '#10b981';
            setTimeout(() => {
                btn.innerHTML = original;
                btn.style.background = '#667eea';
            }, 2000);
        });
    }
    </script>
    <?php endif; ?>

    <div class="instructions">
        <h3>💡 Cum să folosești compilatorul:</h3>
        <ol>
            <li>Scrie sau lipește codul C++ în editor (în iframe-ul de sus)</li>
            <li>Apasă butonul verde <strong>"Run"</strong></li>
            <li>Output-ul apare instant în panoul de jos</li>
            <li>Pentru input (cin), folosește zona "stdin" înainte să apeși Run</li>
        </ol>
    </div>
</section>

<style>
.compilator-section {
    max-width: 1200px;
    margin: 0 auto;
    padding: 24px;
}

.compilator-section h2 {
    font-size: 1.9rem;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    margin-bottom: 12px;
}

.compilator-intro {
    color: #6b7280;
    margin-bottom: 24px;
    font-size: 1.05rem;
}

.compiler-container {
    margin-bottom: 24px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.1);
    border-radius: 12px;
    overflow: hidden;
}

.code-box {
    background: linear-gradient(135deg, #f8f9ff, #ffffff);
    border-radius: 12px;
    overflow: hidden;
    margin-bottom: 24px;
    border: 2px solid rgba(102,126,234,0.2);
}

.code-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 14px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    color: white;
    font-weight: 600;
}

.copy-btn {
    background: rgba(255,255,255,0.25);
    border: none;
    padding: 8px 16px;
    border-radius: 6px;
    color: white;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
}

.copy-btn:hover {
    background: rgba(255,255,255,0.35);
    transform: translateY(-2px);
}

.code-content {
    background: #ffffff;
    color: #1f2937;
    padding: 20px;
    margin: 0;
    overflow-x: auto;
    font-family: 'Consolas', 'Monaco', monospace;
    font-size: 0.9rem;
    line-height: 1.6;
    max-height: 400px;
    border-top: 1px solid rgba(102,126,234,0.1);
}

.instructions {
    background: linear-gradient(135deg, #f0f4ff, #faf5ff);
    padding: 24px;
    border-radius: 12px;
    border: 2px solid rgba(118,75,162,0.15);
}

.instructions h3 {
    margin-top: 0;
    color: #667eea;
    font-size: 1.2rem;
    margin-bottom: 16px;
}

.instructions ol {
    color: #4b5563;
    line-height: 1.8;
    margin-bottom: 20px;
}

.instructions li {
    margin-bottom: 10px;
}

.features {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
    margin-top: 16px;
}

.features span {
    background: white;
    padding: 8px 16px;
    border-radius: 20px;
    font-size: 0.9rem;
    color: #667eea;
    font-weight: 600;
    box-shadow: 0 2px 8px rgba(102,126,234,0.15);
}

@media (max-width: 768px) {
    .compilator-section {
        padding: 16px;
    }
    
    .compilator-section h2 {
        font-size: 1.5rem;
    }
    
    .code-header {
        flex-direction: column;
        gap: 10px;
        text-align: center;
    }
    
    .copy-btn {
        width: 100%;
    }
    
    .compiler-container iframe {
        height: 500px !important;
    }
}
</style>
