<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Models\Experience;
use App\Models\Award;
use App\Core\View;

class ExperienceController {
    public function index(): void {
        $experiences = Experience::allWithDetails();
        $awards = Award::allWithDetails();

        View::render('experience', [
            'experiences' => $experiences,
            'awards' => $awards
        ]);
    }
}
