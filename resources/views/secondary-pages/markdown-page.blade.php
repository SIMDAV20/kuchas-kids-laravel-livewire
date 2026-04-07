<x-app-layout>
    <div class="container py-8 min-h-screen">

        <div class="markdown-content">
            {!! $content !!}
        </div>

    </div>
</x-app-layout>

<style>
    /* Mapea los elementos HTML que genera Str::markdown() al estilo existente */
    .markdown-content h1 {
        font-size: 1.875rem; /* text-3xl */
        color: #f472b6;      /* text-pink-400 */
        font-weight: 700;
        margin-bottom: 1.25rem;
    }

    .markdown-content h2 {
        font-size: 1.5rem;   /* text-2xl */
        font-weight: 600;
        color: #2563eb;      /* text-blue-600 */
        margin-bottom: 0.75rem;
        margin-top: 1.5rem;
    }

    .markdown-content h3 {
        font-size: 1.25rem;  /* text-xl */
        font-weight: 600;
        color: #3b82f6;      /* text-blue-500 */
        margin-bottom: 0.5rem;
        margin-top: 1.25rem;
    }

    .markdown-content p {
        margin-bottom: 0.5rem;
    }

    .markdown-content ul {
        padding-left: 1.25rem;
        margin-bottom: 1rem;
    }

    .markdown-content ul li {
        position: relative;
        padding-left: 1.25rem;
        margin-bottom: 0.5rem;
    }

    .markdown-content ul li::before {
        content: "•";
        position: absolute;
        left: 0;
        color: currentColor;
        font-size: 1.1em;
        line-height: 1.5;
    }

    .markdown-content ol {
        padding-left: 1.5rem;
        margin-bottom: 1rem;
        counter-reset: list-counter;
    }

    .markdown-content ol li {
        position: relative;
        padding-left: 1.25rem;
        margin-bottom: 0.5rem;
        counter-increment: list-counter;
    }

    .markdown-content ol li::before {
        content: counter(list-counter) ".";
        position: absolute;
        left: 0;
        font-weight: 600;
    }

    .markdown-content a {
        color: #2563eb;
        text-decoration: underline;
    }

    .markdown-content a:hover {
        color: #3b82f6;
    }

    .markdown-content strong {
        font-weight: 700;
    }

    .markdown-content hr {
        border-color: #e5e7eb;
        margin: 1.5rem 0;
    }
</style>
