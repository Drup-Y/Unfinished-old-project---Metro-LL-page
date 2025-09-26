<?php
// Configurar UTF-8 y headers
header('Content-Type: text/html; charset=utf-8');
header('Access-Control-Allow-Origin: *');

// Clave de la API de Steam - Asegúrate de usar una clave válida
$apiKey = '43B20BA79B0BFC8990D70DE4EB11C574';
$appId = '287390'; // ID de Metro: Last Light

// Configurar el caché para evitar llamadas frecuentes a la API
$cacheFile = 'steam_cache_' . $appId . '.json';
$cacheTime = 3600; // 1 hora

// Verificar si existe caché válido
if (file_exists($cacheFile) && (time() - filemtime($cacheFile)) < $cacheTime) {
    $response = file_get_contents($cacheFile);
} else {
    // URL de la API de Steam
    $url = "https://store.steampowered.com/api/appdetails?appids=$appId&key=$apiKey";

    // Configurar cURL
    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_SSL_VERIFYPEER => true,
        CURLOPT_USERAGENT => 'Mozilla/5.0',
        CURLOPT_TIMEOUT => 30
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlError = curl_error($ch);
    curl_close($ch);

    // Verificar errores
    if ($response === false) {
        die(json_encode(['error' => "Error cURL: $curlError"]));
    }

    if ($httpCode !== 200) {
        die(json_encode(['error' => "Error HTTP: $httpCode"]));
    }

    // Guardar en caché
    file_put_contents($cacheFile, $response);
}

// Procesar y mostrar datos
$data = json_decode($response, true);

if ($data && isset($data[$appId]['success']) && $data[$appId]['success']) {
    $gameData = $data[$appId]['data'];
    ?>
    <div class="steam-game-info">
        <h2><?php echo htmlspecialchars($gameData['name']); ?></h2>
        <div class="steam-game-content">
            <img src="<?php echo htmlspecialchars($gameData['header_image']); ?>" 
                 alt="<?php echo htmlspecialchars($gameData['name']); ?>" 
                 class="steam-game-image">
            
            <div class="steam-game-details">
                <p class="game-description"><?php echo htmlspecialchars($gameData['short_description']); ?></p>
                
                <?php if (isset($gameData['price_overview'])): ?>
                <div class="price-info">
                    <p class="price">
                        Precio: <?php echo htmlspecialchars($gameData['price_overview']['final_formatted']); ?>
                    </p>
                    <?php if (isset($gameData['price_overview']['discount_percent']) && $gameData['price_overview']['discount_percent'] > 0): ?>
                    <p class="discount">
                        ¡<?php echo $gameData['price_overview']['discount_percent']; ?>% de descuento!
                    </p>
                    <?php endif; ?>
                </div>
                <?php endif; ?>

                <a href="https://store.steampowered.com/app/<?php echo $appId; ?>" 
                   class="steam-button" 
                   target="_blank">
                    Ver en Steam
                </a>
            </div>
        </div>
    </div>
    <?php
} else {
    echo '<p class="error">No se pudo obtener la información del juego. Por favor, inténtalo más tarde.</p>';
}
?>