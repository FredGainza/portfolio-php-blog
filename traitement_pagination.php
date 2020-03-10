<?php
if (isset($_GET['largeur']) && !empty($_GET['largeur'])) {
    $_GET['largeur'] < 992 ? $items_per_page = 2 : $items_per_page = 3;
} else {
    $items_per_page = 6;
}
echo json_encode($items_per_page);
