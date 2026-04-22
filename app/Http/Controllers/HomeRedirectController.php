<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class HomeRedirectController extends Controller
{
    public function __invoke(Request $request): RedirectResponse
    {
        if ($request->user()->isEmployee()) {
            return redirect()->route('employee.home');
        }

        return redirect()->route('dashboard');
    }
}
