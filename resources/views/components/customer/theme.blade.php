{{-- Shared brand theme for all customer pages: fonts, tailwind config, base styles --}}
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/tailwind.css') }}?v={{ @filemtime(public_path('css/tailwind.css')) ?: 1 }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<script src="{{ asset('js/page-loader.js') }}?v={{ @filemtime(public_path('js/page-loader.js')) ?: 1 }}"></script>
<style>
    body {
        font-family: 'Poppins', sans-serif;
        background-color: #FFF6F0;
        -webkit-tap-highlight-color: transparent;
    }

    .brand-gradient {
        background: linear-gradient(115deg, #FF7DA0 0%, #FFC275 100%);
    }

    .brand-gradient-soft {
        background: linear-gradient(115deg, rgba(255, 125, 160, 0.12) 0%, rgba(255, 194, 117, 0.12) 100%);
    }

    .brand-text-gradient {
        background: linear-gradient(115deg, #E85D85 0%, #FF9F5A 100%);
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
    }

    .brand-shadow {
        box-shadow: 0 6px 18px rgba(255, 125, 160, 0.30);
    }

    .app-card {
        background: #fff;
        border-radius: 1.25rem;
        box-shadow: 0 2px 12px rgba(232, 93, 133, 0.07);
        border: 1px solid rgba(232, 93, 133, 0.08);
    }

    .pb-safe {
        padding-bottom: calc(0.5rem + env(safe-area-inset-bottom));
    }

    /* Hide scrollbar but keep scroll */
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }

    /* Sidebar active state (brand) */
    .sidebar-item.active {
        background: linear-gradient(115deg, rgba(255, 125, 160, 0.14) 0%, rgba(255, 194, 117, 0.14) 100%);
        color: #E85D85 !important;
        border-right: 3px solid #E85D85;
        font-weight: 600;
    }

    /* Smooth page entrance */
    @keyframes pageFadeUp {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .page-enter { animation: pageFadeUp 0.35s ease-out both; }
</style>
