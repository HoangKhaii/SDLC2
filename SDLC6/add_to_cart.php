<?php
session_start();
if (isset($_POST['dish_id'])) {
    $dish_id = intval($_POST['dish_id']);
    if (!isset($_SESSION['cart'][$dish_id])) {
        $_SESSION['cart'][$dish_id] = ['quantity' => 1];
    } else {
        $_SESSION['cart'][$dish_id]['quantity']++;
    }
    echo 'OK';
} else {
    echo 'ERROR';
} 