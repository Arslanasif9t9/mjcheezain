{{-- Site-wide customer bottom tab bar (mobile only).
     Shows on EVERY page (home, cosmetics, product, cart, ...) when the
     logged-in user is a customer, so they can jump to orders/wishlist/etc.
     from anywhere. --}}
@auth
    @if (Auth::user()->type === 'customer')
        <x-customer.mobile-nav />
        {{-- Spacer so fixed nav never covers page content / footer on mobile --}}
        <div class="h-16 md:hidden" aria-hidden="true"></div>
        <style>
            /* Any page-level fixed bottom bars (e.g. product page cart summary)
               sit just above the customer tab bar on mobile */
            @media (max-width: 767px) {
                #cartSummary { bottom: 3.6rem !important; }
            }
        </style>
    @endif
@endauth
