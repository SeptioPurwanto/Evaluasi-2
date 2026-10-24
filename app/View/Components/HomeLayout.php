<?php

namespace App\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class HomeLayout extends Component
{
    public function __construct(public string $title = 'SneakerLab')
    {
    }

    public function render(): View
    {
        return view('layouts.home-layout');
    }
}
