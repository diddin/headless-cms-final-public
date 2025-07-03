<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class LocaleInput extends Component
{
    public string $field;
    public string $type;
    public ?string $label;
    public ?string $placeholder;
    public array $locales;

    /**
     * Create a new component instance.
     */
    public function __construct(
        string $field,
        string $type = 'text',
        ?string $label = null,
        ?string $placeholder = null
    ) {
        $this->field = $field;
        $this->type = $type;
        $this->label = $label;
        $this->placeholder = $placeholder;
        $this->locales = config('locales.supported', ['en', 'id']);
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.locale-input');
    }
}
