<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Category;

class FooterPublic extends Component
{
    /**
     * Secciones del footer con sus links.
     *
     * Cada link puede usar:
     *   - 'route'  => nombre de ruta existente  (sin parámetros adicionales)
     *   - 'route'  => 'info.page', 'page' => 'slug-del-archivo-md'
     *       → apunta a resources/markdown/pages/{slug}.md
     */
    public array $footerLinks = [
        'SERVICIO AL CLIENTE' => [
            ['label' => 'Preguntas Frecuentes', 'route' => 'info.page', 'page' => 'frequent-questions'],
            ['label' => 'Políticas de Envío',   'route' => 'info.page', 'page' => 'shipping-policies'],
            ['label' => 'Contáctanos',           'route' => 'contact.index'],
        ],
        'INFORMACIÓN DE UTILIDAD' => [
            ['label' => 'Términos y Condiciones', 'route' => 'info.page', 'page' => 'terms-and-conditions'],
            ['label' => 'Cambios y devoluciones', 'route' => 'info.page', 'page' => 'terms-and-conditions'],
        ],
    ];

    public function render()
    {
        $categories = Category::all();

        return view('livewire.footer-public', compact('categories'));
    }
}
