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

if (!isset($_SESSION['user']) || ($_SESSION['user']['role'] ?? '') !== 'buyer') {
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'error' => 'auth']);
        exit;
    }
    header('Location: index.php?page=home&auth=login');
    exit;
}

// Action: Add Item to Cart
if ($action === 'add') {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Location: index.php?page=cart&error=invalid_request');
        exit;
    }
    require_csrf();
    $service_id = (int) ($_POST['service_id'] ?? 0);
    $quantity = (int) ($_POST['quantity'] ?? 1);

    if ($service_id > 0 && $quantity > 0) {
        if (isset($_SESSION['cart'][$service_id])) {
            $_SESSION['cart'][$service_id] += $quantity;
        } else {
            $_SESSION['cart'][$service_id] = $quantity;
        }

        // If AJAX request, return JSON
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
            header('Content-Type: application/json');
            
            // Build simple preview HTML for the updated cart
            $previewHtml = '';
            require_once __DIR__ . '/../models/ServiceModel.php';
            global $pdo; // ensure $pdo is accessible if we need it, though CartController uses it if included
            if (!isset($pdo)) {
                require_once __DIR__ . '/../../config/database.php';
            }
            $srvModel = new ServiceModel($pdo);
            $cartCount = 0;
            
            if (!empty($_SESSION['cart'])) {
                foreach ($_SESSION['cart'] as $sId => $qty) {
                    $svc = $srvModel->getById((int) $sId);
                    if ($svc) {
                        $cartCount += (int) $qty;
                        $icon = category_icon($svc['category_name'] ?? '');
                        $previewHtml .= '<a class="cart-preview-item" href="index.php?page=cart">';
                        $previewHtml .= '<span class="cart-preview-thumb"><i class="bi ' . htmlspecialchars($icon) . '"></i></span>';
                        $previewHtml .= '<span class="cart-preview-copy">';
                        $previewHtml .= '<span>' . htmlspecialchars($svc['title']) . '</span>';
                        $previewHtml .= '<small>' . htmlspecialchars($svc['provider_name'] . ' - ' . $svc['location']) . '</small>';
                        $previewHtml .= '</span>';
                        $previewHtml .= '<strong>' . (int) $qty . 'x Rp' . number_format($svc['price'], 0, ',', '.') . '</strong>';
                        $previewHtml .= '</a>';
                    }
                }
            }

            echo json_encode([
                'success' => true, 
                'msg' => 'added', 
                'cart_count' => $cartCount,
                'preview_html' => $previewHtml
            ]);
            exit;
        }

        // Redirect back to previous page (not cart), with msg in URL
        $referer = $_SERVER['HTTP_REFERER'] ?? '';
        if ($referer && strpos($referer, 'page=cart') === false) {
            $sep = strpos($referer, '?') !== false ? '&' : '?';
            header('Location: ' . $referer . $sep . 'msg=added');
        } else {
            header('Location: index.php?page=home&msg=added');
        }
        exit;
    }
}

// Action: Update Cart Quantity
if ($action === 'update') {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Location: index.php?page=cart&error=invalid_request');
        exit;
    }
    require_csrf();
    $service_id = (int) ($_POST['service_id'] ?? 0);
    $quantity = (int) ($_POST['quantity'] ?? 0);

    if ($service_id > 0) {
        $old_qty = $_SESSION['cart'][$service_id] ?? 1;
        if ($quantity > 0) {
            $_SESSION['cart'][$service_id] = $quantity;
        } else {
            unset($_SESSION['cart'][$service_id]);
        }

        // AJAX response
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
            header('Content-Type: application/json');
            echo json_encode([
                'success'    => true,
                'cart_count' => array_sum($_SESSION['cart']),
                'old_qty'    => (int) $old_qty,
            ]);
            exit;
        }

        header('Location: index.php?page=cart&msg=updated');
        exit;
    }
}

// Action: Remove Item from Cart
if ($action === 'remove') {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Location: index.php?page=cart&error=invalid_request');
        exit;
    }
    require_csrf();
    $service_id = (int) ($_POST['id'] ?? 0);
    if (isset($_SESSION['cart'][$service_id])) {
        unset($_SESSION['cart'][$service_id]);
    }
    header('Location: index.php?page=cart&msg=removed');
    exit;
}

// Action: Clear Cart
if ($action === 'clear') {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Location: index.php?page=cart&error=invalid_request');
        exit;
    }
    require_csrf();
    $_SESSION['cart'] = [];
    header('Location: index.php?page=cart&msg=cleared');
    exit;
}

// Default redirect
header('Location: index.php?page=cart');
exit;
