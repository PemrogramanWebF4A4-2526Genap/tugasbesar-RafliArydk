<?php
// --- CONTROLLER: CART MANAGEMENT ---
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$action = $_GET['action'] ?? '';

// Initialize cart session
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Action: Add Item to Cart
if ($action === 'add') {
    $service_id = (int) ($_POST['service_id'] ?? 0);
    $quantity = (int) ($_POST['quantity'] ?? 1);

    if ($service_id > 0 && $quantity > 0) {
        if (isset($_SESSION['cart'][$service_id])) {
            $_SESSION['cart'][$service_id] += $quantity;
        } else {
            $_SESSION['cart'][$service_id] = $quantity;
        }
        header('Location: index.php?page=cart&msg=added');
        exit;
    }
}

// Action: Update Cart Quantity
if ($action === 'update') {
    $service_id = (int) ($_POST['service_id'] ?? 0);
    $quantity = (int) ($_POST['quantity'] ?? 0);

    if ($service_id > 0) {
        if ($quantity > 0) {
            $_SESSION['cart'][$service_id] = $quantity;
        } else {
            unset($_SESSION['cart'][$service_id]);
        }
        header('Location: index.php?page=cart&msg=updated');
        exit;
    }
}

// Action: Remove Item from Cart
if ($action === 'remove') {
    $service_id = (int) ($_GET['id'] ?? 0);
    if (isset($_SESSION['cart'][$service_id])) {
        unset($_SESSION['cart'][$service_id]);
    }
    header('Location: index.php?page=cart&msg=removed');
    exit;
}

// Action: Clear Cart
if ($action === 'clear') {
    $_SESSION['cart'] = [];
    header('Location: index.php?page=cart&msg=cleared');
    exit;
}

// Default redirect
header('Location: index.php?page=cart');
exit;
