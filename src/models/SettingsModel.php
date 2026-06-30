<?php
class SettingsModel {
    private $file;

    public function __construct() {
        $this->file = __DIR__ . '/../config/settings.json';
        if (!file_exists($this->file)) {
            $this->save($this->defaults());
        }
    }

    private function defaults() {
        return [
            'commission_rate' => 10,
            'shipping_cost' => 15000,
            'payment_methods' => ['bank_transfer', 'cash'],
            'email_template_welcome' => 'Selamat datang di BisaBantu!',
            'email_template_order' => 'Pesanan Anda telah diterima.',
            'notification_enabled' => true,
            'session_timeout' => 60,
            'suspended_users' => [],
        ];
    }

    public function getAll() {
        $data = json_decode(file_get_contents($this->file), true);
        return is_array($data) ? array_merge($this->defaults(), $data) : $this->defaults();
    }

    public function save(array $data) {
        $merged = array_merge($this->defaults(), $data);
        return file_put_contents($this->file, json_encode($merged, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)) !== false;
    }

    public function isUserSuspended($userId) {
        $settings = $this->getAll();
        return in_array((int) $userId, $settings['suspended_users'], true);
    }

    public function toggleUserSuspension($userId) {
        $settings = $this->getAll();
        $userId = (int) $userId;
        $key = array_search($userId, $settings['suspended_users'], true);
        if ($key !== false) {
            unset($settings['suspended_users'][$key]);
            $settings['suspended_users'] = array_values($settings['suspended_users']);
        } else {
            $settings['suspended_users'][] = $userId;
        }
        return $this->save($settings);
    }
}
