/**
 * Tailwind v3 — deliberately matches what the Play CDN was serving, so swapping
 * to this prebuilt stylesheet causes no visual change. (v4 changes defaults like
 * border colour and ring width, which would silently alter every page.)
 *
 * The theme below is the merge of the 17 inline `tailwind.config` blocks that
 * used to live in individual Blade files.
 *
 * Build with:  npm run css
 * Output:      public/css/tailwind.css  (committed — deploys are FTP-only)
 */
export default {
    content: [
        './resources/views/**/*.blade.php',
        './public/js/**/*.js',
        './app/**/*.php',
    ],
    theme: {
        extend: {
            colors: {
                // Brand palette
                'brand': '#E85D85',
                'brand-light': '#FF7DA0',
                'brand-peach': '#FFC275',
                'light-bg': '#FFF6F0',
                'dark-bg': '#1f2937',

                // Legacy aliases still used across older pages — all resolve to
                // the brand pink; kept so existing markup keeps working.
                'umart-blue': '#E85D85',
                'primary-blue': '#E85D85',
                'primary': '#E85D85',

                // comming-soon.blade.php (checkout also declared `secondary`/
                // `accent` but never used them, so these values win)
                'secondary': '#C94A72',
                'accent': '#F59E0B',

                'star-yellow': '#FFC700',
                'verified-green': '#10b981',
                'custom-gold': '#C57614',

                fashion: {
                    pink: '#ec4899',
                    purple: '#a855f7',
                    teal: '#14b8a6',
                },
            },
            fontFamily: {
                sans: ['Poppins', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                poppins: ['Poppins', 'sans-serif'],
            },
            boxShadow: {
                card: '0 4px 20px rgba(232,93,133,.08)',
            },
            animation: {
                'fade-in': 'fadeIn 0.5s ease-in-out',
                'slide-up': 'slideUp 0.5s ease-out',
                'pulse-slow': 'pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                'bounce-slow': 'bounce 2s infinite',
            },
            keyframes: {
                fadeIn: {
                    '0%': { opacity: '0' },
                    '100%': { opacity: '1' },
                },
                slideUp: {
                    '0%': { transform: 'translateY(20px)', opacity: '0' },
                    '100%': { transform: 'translateY(0)', opacity: '1' },
                },
            },
        },
    },
    plugins: [],
};
