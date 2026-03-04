<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Response;
use App\Domain\Slideshow\SlideRepository;
use App\Domain\Slideshow\SlideService;

class SlideController extends Controller
{
    private SlideService $service;

    public function __construct()
    {
        $this->service = new SlideService(new SlideRepository());
    }

    public function index(Request $request): Response
    {
        $slides = array_map(fn ($slide) => $slide->toArray(), $this->service->list());

        return $this->json(['data' => $slides]);
    }

    public function store(Request $request): Response
    {
        $result = $this->service->create($request->all());
        if (!empty($result['errors'])) {
            return $this->json(['errors' => $result['errors']], 422);
        }

        return $this->json(['data' => $result['slide']->toArray()], 201);
    }

    public function update(Request $request, array $params): Response
    {
        $result = $this->service->update((int) ($params['id'] ?? 0), $request->all());
        if (!empty($result['errors'])) {
            return $this->json(['errors' => $result['errors']], 422);
        }

        return $this->json(['data' => $result['slide']->toArray()]);
    }

    public function destroy(Request $request, array $params): Response
    {
        $this->service->delete((int) ($params['id'] ?? 0));

        return $this->json(['deleted' => true]);
    }
}
