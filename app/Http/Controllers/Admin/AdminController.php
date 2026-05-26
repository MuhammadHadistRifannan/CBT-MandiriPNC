<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AdminDashboardService;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function __construct(private readonly AdminDashboardService $dashboardService) {}

    public function index(): View
    {
        return view('admin.dashboard', $this->dashboardService->data());
    }
}
