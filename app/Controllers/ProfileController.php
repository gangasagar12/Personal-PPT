<?php
namespace App\Controllers;

use App\Core\View;

class ProfileController {
    public function index(): void {
        View::render('profile');
    }
}

