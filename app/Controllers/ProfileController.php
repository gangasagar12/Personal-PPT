<?php
namespace App\Controllers;

use App\Core\View;
use App\Models\CompanyProduct;

class ProfileController {
    public function index(): void {
        $company_products = CompanyProduct::allWithFeaturesAndPills();

        View::render('profile', [
            'company_products' => $company_products
        ]);
    }
}
