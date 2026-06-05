<?php
require_once __DIR__ . '/UserModel.php';

class VerificationModel {
    private $userModel;

    public function __construct($pdo) {
        $this->userModel = new UserModel($pdo);
    }

    public function pendingProviders() {
        return $this->userModel->getUnverifiedProviders();
    }

    public function approveProvider($userId) {
        return $this->userModel->verifyProvider((int) $userId);
    }

    public function rejectProvider($userId) {
        return $this->userModel->rejectProvider((int) $userId);
    }
}
