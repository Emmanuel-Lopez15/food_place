<?php
include "conexion.php";

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
        echo '<form class="restaurants" action="form_reserva.php" method="POST">';
        $NameR = htmlspecialchars($row['name']);
        $CityR = htmlspecialchars($row['city']);
        $StateR = htmlspecialchars($row['state_country']);
        $CountryR = htmlspecialchars($row['country']);
        $PhoneR = htmlspecialchars($row['phone']);
        $CostR = htmlspecialchars($row['price']);
        $Username = "prueba";

        echo '<input type="hidden" name="NameR" value="' . $NameR . '">';
        echo '<input type="hidden" name="CityR" value="' . $CityR . '">';
        echo '<input type="hidden" name="StateR" value="' . $StateR . '">';
        echo '<input type="hidden" name="CountryR" value="' . $CountryR . '">';
        echo '<input type="hidden" name="CostR" value="' . $CostR . '">';
        echo '<input type="hidden" name="PhoneR" value="' . $PhoneR . '">';
        echo '<input type="hidden" name="Username" value="' . $Username . '">';

        echo '<div class="cards-container">';
        echo '<div class="card">';
        echo '<div class="card-image">';
        echo '<img src="data:image/jpeg;base64,' . base64_encode($row['imagen']) . '" alt="Image not available">';
        echo '</div>';
        echo '<div class="card-info">';
        echo '<h3>' . htmlspecialchars($row['name']) . '</h3>';
        echo '<p>Score: ' . htmlspecialchars($row['score']) . ' ★ </p>';
        echo '<p>Average Price: $' . htmlspecialchars($row['price']) . '</p>';
        echo '<p>Location: ' . htmlspecialchars($row['city']) . ', ' . htmlspecialchars($row['state_country']) . ', ' . htmlspecialchars($row['country']) . '</p>';
        echo '<p>Phone: ' . htmlspecialchars($row['phone']) . '</p>';
        echo '<button class="reserve-btn" type="submit">Reserve</button>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
        echo '</form>';
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
