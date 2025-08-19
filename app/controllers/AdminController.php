<?php
require_once ROOT . 'core/Auth.php';

class AdminController {
    
    public function dashboard() {
        Auth::restringirDashboard();
        include ROOT . 'app/views/admin/dashboard.php';
    }
}
