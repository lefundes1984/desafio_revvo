<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Core\Controller;
use App\Domain\Slideshow\SlideRepository;
use App\Domain\Slideshow\SlideService;
use App\Core\Request;
use App\Core\Response;

class HomeController extends Controller
{
    private SlideService $slides;

    public function __construct()
    {
        $this->slides = new SlideService(new SlideRepository());
    }

    public function index(Request $request): Response
    {
        $slides = $this->slides->list();

        return $this->view('pages/home', [
            'slides' => $slides,
            'userName' => 'XPTO',
        ]);
    }
}
