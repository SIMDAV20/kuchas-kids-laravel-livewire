<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SecondaryPagesController extends Controller
{
    /**
     * Renderiza una página de información dinámica desde un archivo .md
     * Los .md viven en resources/markdown/pages/{page}.md
     */
    public function markdownPage(string $page)
    {
        $path = resource_path("markdown/pages/{$page}.md");

        abort_if(!file_exists($path), 404);

        $raw     = file_get_contents($path);
        $content = Str::markdown($raw);

        // Extrae el primer # como título de página (sin el '#')
        preg_match('/^#\s+(.+)$/m', $raw, $matches);
        $title = $matches[1] ?? ucwords(str_replace('-', ' ', $page));

        return view('secondary-pages.markdown-page', compact('content', 'title'));
    }
}
