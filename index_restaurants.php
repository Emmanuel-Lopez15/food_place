<?php
// Conexión a la base de datos
require 'conexion.php';

// Valor del input de búsqueda
$query = isset($_GET['query']) ? trim($_GET['query']) : '';
$isNumeric = is_numeric($query); // Si la entrada es un número

// Obtener el número de página desde el parámetro 'page', si no existe, asignar 1
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$results_per_page = 15;
$starting_limit = ($page - 1) * $results_per_page;

// Calcular la consulta de búsqueda
if ($isNumeric) {
    // Buscar por score
    $search_sql = "SELECT name, score, price, city, state_country, country, phone, imagen 
                   FROM restaurants 
                   WHERE score >= ? 
                   LIMIT ?, ?";
    $stmt = $conexion->prepare($search_sql);
    $stmt->bind_param('dii', $query, $starting_limit, $results_per_page);
} else {
    // Buscar por nombre, ciudad, estado o país
    $likeQuery = '%' . $query . '%';
    $search_sql = "SELECT name, score, price, city, state_country, country, phone, imagen 
                   FROM restaurants 
                   WHERE name LIKE ? OR city LIKE ? OR state_country LIKE ? OR country LIKE ? 
                   LIMIT ?, ?";
    $stmt = $conexion->prepare($search_sql);
    $stmt->bind_param('ssssii', $likeQuery, $likeQuery, $likeQuery, $likeQuery, $starting_limit, $results_per_page);
}

$stmt->execute();
$result = $stmt->get_result();

// Variable para detectar si hay resultados
$noResults = $result->num_rows === 0 ? true : false;

// Mostrar los resultados
if (!$noResults) {
    while ($row = $result->fetch_assoc()) {
        $cardId = uniqid('card_'); // Generar un ID único para cada tarjeta
        echo '<div class="cards-container">';
        echo '<div class="card" id="' . $cardId . '" data-city="' . htmlspecialchars($row['city']) . '" data-country="' . htmlspecialchars($row['country']) . '">';
        echo '<div class="card-image">';
        echo '<img src="data:image/jpeg;base64,' . base64_encode($row['imagen']) . '" alt="Image not available">';
        echo '</div>';
        echo '<div class="card-info">';
        echo '<h3>' . htmlspecialchars($row['name']) . '</h3>';
        echo '<p>Score: ' . htmlspecialchars($row['score']) . ' ★ </p>';
        echo '<p>Average Price: $' . htmlspecialchars($row['price']) . '</p>';
        echo '<p>Location: ' . htmlspecialchars($row['city']) . ', ' . htmlspecialchars($row['state_country']) . ', ' . htmlspecialchars($row['country']) . '</p>';
        echo '<p>Phone: ' . htmlspecialchars($row['phone']) . '</p>';
        echo '<div class="weather-info">Cargando clima...</div>'; // Div para los datos de clima
        echo '<a class="reserve-btn" href="form.php">Reserve</a>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
    }
} else {
    // Si no hay resultados, mostrar un valor en el HTML para que JavaScript lo detecte
    echo '<div id="no-results" style="display:none;">true</div>';
}

// Obtener el total de resultados para la paginación
if ($isNumeric) {
    // Buscar por score
    $count_sql = "SELECT COUNT(*) FROM restaurants WHERE score >= ?";
    $stmt_count = $conexion->prepare($count_sql);
    $stmt_count->bind_param('d', $query);
} else {
    // Buscar por nombre, ciudad, estado o país
    $count_sql = "SELECT COUNT(*) FROM restaurants WHERE name LIKE ? OR city LIKE ? OR state_country LIKE ? OR country LIKE ?";
    $stmt_count = $conexion->prepare($count_sql);
    $stmt_count->bind_param('ssss', $likeQuery, $likeQuery, $likeQuery, $likeQuery);
}

$stmt_count->execute();
$stmt_count->bind_result($total_results);
$stmt_count->fetch();
$total_pages = ceil($total_results / $results_per_page);

echo "<div class='pagination'>";

// Botón de Anterior
if ($page > 1) {
    echo "<a href='?query=" . urlencode($query) . "&page=" . ($page - 1) . "' class='prev'>« Previous</a>";
} else {
    echo "<span class='prev disabled'>« Previous</span>";
}

// Mostrar la página actual en medio de los botones
echo "<span class='current-page'>Page $page of $total_pages</span>";

// Botón de Siguiente
if ($page < $total_pages) {
    echo "<a href='?query=" . urlencode($query) . "&page=" . ($page + 1) . "' class='next'>Next »</a>";
} else {
    echo "<span class='next disabled'>Next »</span>";
}

echo "</div>";

$stmt_count->close();
$stmt->close();
$conexion->close();
?>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const apiKey = '2bbbad1bd0c7924e007e4e690fd97476';

    // Códigos ISO de países y traducciones de ciudades
    const countryCodes = {
        'México': 'MX',
        'EE. UU.': 'US',
        'USA': 'US',
        'Italia': 'IT',
        'Italy': 'IT'
    };

    const cityTranslations = {
        'Cdad. Victoria': 'Ciudad Victoria',
        'Cdad.México': 'Ciudad de México',
        'VICTORIA': 'Ciudad Victoria',
        'Roma': 'Rome', // Para Italia
        'Zapopan': 'Zapopan',
        'Austin': 'Austin',
        'Chicago': 'Chicago',
        'New York': 'New York',
        'Denver': 'Denver',
        'Westminster': 'Westminster',
        'Dallas': 'Dallas',
        'Brevard': 'Brevard',
        'Boston': 'Boston',
        'Pismo Beach': 'Pismo Beach',
        'Morrison': 'Morrison',
        'Weehawken Township': 'Weehawken Township',
        'Long Island City': 'Long Island City'
    };

    // Mapeo de descripciones de clima a emojis
    const weatherEmojis = {
        'cielo claro': '☀️',
        'algo de nubes': '🌤️',
        'nubes dispersas': '⛅',
        'muy nuboso': '🌥️',
        'nubes': '☁️',
        'lluvia ligera': '🌦️',
        'lluvia': '🌧️',
        'tormenta': '⛈️',
        'nieve': '❄️',
        'nevada ligera' : '⛄',
        'niebla': '🌫️',
        'bruma': '🌫️',
        'polvo': '🌪️',
        'arena': '🌪️',
        'humo': '🌫️',
        'llovizna': '🌦️',
        'chubascos': '🌦️'
    };

    const cards = document.querySelectorAll('.card');
    cards.forEach(card => {
        let city = card.dataset.city.trim();
        const country = card.dataset.country.trim();
        const countryCode = countryCodes[country] || '';

        // Aplicar traducción de ciudad si existe
        if (cityTranslations[city]) {
            city = cityTranslations[city];
        }

        if (!city || !countryCode) {
            card.querySelector('.weather-info').innerText = 'Clima no disponible.';
            return;
        }

        const url = `https://api.openweathermap.org/data/2.5/weather?q=${city},${countryCode}&appid=${apiKey}&units=metric&lang=es`;

        fetch(url)
            .then(response => response.json())
            .then(data => {
                if (data.cod === 200) {
                    const clima = data.weather[0].description;
                    const temperatura = data.main.temp;

                    // Determinar el emoji correspondiente
                    const emoji = Object.keys(weatherEmojis).find(key => clima.includes(key)) 
                        ? weatherEmojis[Object.keys(weatherEmojis).find(key => clima.includes(key))]
                        : '🌍'; // Emoji predeterminado

                    card.querySelector('.weather-info').innerHTML = `
                        <p>Clima: ${clima} ${emoji}</p>
                        <p>Temperatura: ${temperatura}°C</p>
                    `;
                } else {
                    card.querySelector('.weather-info').innerText = 'Clima no disponible.';
                }
            })
            .catch(error => {
                console.error('Error al conectar con la API:', error);
                card.querySelector('.weather-info').innerText = 'Error al obtener el clima.';
            });
    });
});


</script>