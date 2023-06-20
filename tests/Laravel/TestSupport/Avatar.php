<?php

declare(strict_types=1);

namespace YieldStudio\TailwindMerge\Tests\Laravel\TestSupport;

use Illuminate\View\Component;

class Avatar extends Component
{
    public function render(): string
    {
        return <<<'blade'
            <div {{ $attributes->tw('rounded-full') }}></div>
        blade;
    }
}
