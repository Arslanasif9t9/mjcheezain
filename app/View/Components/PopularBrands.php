<?php

namespace App\View\Components;

use Illuminate\View\Component;

class PopularBrands extends Component
{
    public $brands;

    public function __construct($brands = [])
    {
        $this->brands = $brands;
    }

    public function render()
    {
        return view('components.popular-brands');
    }
}
