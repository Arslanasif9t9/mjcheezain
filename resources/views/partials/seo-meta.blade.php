{{--
    ============================================================================
    SITE-WIDE SEO META PARTIAL
    ============================================================================
    Included by every top-level layout (layouts/structure.blade.php and
    layouts/app.blade.php) so the whole site shares ONE meta/JSON-LD system.
    Do not duplicate this markup in a layout directly — include this partial
    instead, so future changes only need to happen in one place.

    Yields available to ANY page that @extends a layout including this partial
    (a page sets these the normal Blade way: @section('meta_description', '...')):

      title              <title> text. Set in the layout's own <title> tag
                          (not here) — falls back to a site default there.
      meta_description   <meta name="description">
      meta_keywords      <meta name="keywords">
      canonical          <link rel="canonical"> href. Defaults to the current
                          URL (self-referencing canonical) — most pages get a
                          correct canonical for free. Override to strip
                          query-string variants, e.g.
                          @section('canonical', url('/product/'.$product->id))
      robots_meta         <meta name="robots"> content. Default "index, follow".
                          Private/dashboard pages can @section('robots_meta',
                          'noindex, nofollow') — left for a future SEO pass.
      og_title / og_description / og_image / og_type / og_url
                          Open Graph tags. og_title/og_description fall back to
                          title/meta_description; og_image falls back to the
                          site default image; og_type falls back to "website";
                          og_url falls back to the current URL.
      twitter_title / twitter_description / twitter_image
                          Twitter Card overrides. Each falls back to the
                          matching og_* value automatically — pages normally
                          never need to set these, only to diverge from OG.
      structured_data    Extra <script type="application/ld+json"> block(s) a
                          page wants to add (Product, BreadcrumbList, etc.),
                          IN ADDITION to the sitewide Organization+WebSite
                          block below (which is always present, not yielded).
    ============================================================================
--}}
@php
    $__siteName = 'MJCheezain';
    $__siteUrl = url('/');
    $__defaultTitle = 'MJCheezain — Your Online Marketplace for Quality Products in Pakistan';
    $__defaultDescription = 'MJCheezain.com – Discover unique and quality products with excellent customer support. Visit our online store today.';
    $__defaultKeywords = 'MJCheezain, mjcheezain.com, online store, unique items';
    $__defaultImage = asset('img/short_logo.jpeg');

    // Resolve OG values once (each falls back to the matching plain meta tag,
    // then to a site default) so Twitter tags below can mirror them without
    // making pages set everything twice.
    $__ogTitle = trim($__env->yieldContent('og_title', $__env->yieldContent('title', $__defaultTitle))) ?: $__defaultTitle;
    $__ogDescription = trim($__env->yieldContent('og_description', $__env->yieldContent('meta_description', $__defaultDescription))) ?: $__defaultDescription;
    $__ogImage = trim($__env->yieldContent('og_image', $__defaultImage)) ?: $__defaultImage;
    $__ogType = trim($__env->yieldContent('og_type', 'website')) ?: 'website';
    $__ogUrl = trim($__env->yieldContent('og_url', url()->current())) ?: url()->current();
@endphp
<meta name="description" content="@yield('meta_description', $__defaultDescription)">
<meta name="keywords" content="@yield('meta_keywords', $__defaultKeywords)">
<meta name="author" content="{{ $__siteName }}">
<meta name="robots" content="@yield('robots_meta', 'index, follow')">
<link rel="canonical" href="@yield('canonical', url()->current())">

<!-- Open Graph -->
<meta property="og:site_name" content="{{ $__siteName }}">
<meta property="og:title" content="{{ $__ogTitle }}">
<meta property="og:description" content="{{ $__ogDescription }}">
<meta property="og:url" content="{{ $__ogUrl }}">
<meta property="og:type" content="{{ $__ogType }}">
<meta property="og:image" content="{{ $__ogImage }}">

<!-- Twitter Card (mirrors the OG values above unless a page explicitly sets
     its own twitter_title / twitter_description / twitter_image section) -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ trim($__env->yieldContent('twitter_title', $__ogTitle)) ?: $__ogTitle }}">
<meta name="twitter:description" content="{{ trim($__env->yieldContent('twitter_description', $__ogDescription)) ?: $__ogDescription }}">
<meta name="twitter:image" content="{{ trim($__env->yieldContent('twitter_image', $__ogImage)) ?: $__ogImage }}">

<link rel="icon" type="image/jpeg" href="{{ $__defaultImage }}">
<meta name="csrf-token" content="{{ csrf_token() }}">

{{-- Sitewide Organization + WebSite JSON-LD — always present on every page
     (not yielded). Only real, currently-used values are included: the
     social links and support email below are the same ones live in
     resources/views/components/footer.blade.php / footer/contact-us.blade.php.
     No physical address is included — the site's Office Address section is
     still a "[Street Address, City, Pakistan] — Coming Soon" placeholder,
     so it is intentionally omitted here rather than inventing one. --}}
<script type="application/ld+json">
{!! json_encode([
    '@@context' => 'https://schema.org',
    '@graph' => [
        [
            '@type' => 'Organization',
            'name' => $__siteName,
            'url' => $__siteUrl,
            'logo' => $__defaultImage,
            'sameAs' => [
                'https://www.instagram.com/mjcheezain?igsh=Nmh2ZnFwdm93Mjg5',
                'https://www.facebook.com/share/1QqziUcixy/',
                'https://www.tiktok.com/@mj.cheezain?_r=1&_t=ZS-98vtBYXFNXM',
            ],
            'contactPoint' => [
                '@type' => 'ContactPoint',
                'contactType' => 'customer support',
                'email' => 'support@mjcheezain.com',
            ],
        ],
        [
            '@type' => 'WebSite',
            'name' => $__siteName,
            'url' => $__siteUrl,
        ],
    ],
], JSON_UNESCAPED_SLASHES) !!}
</script>

@yield('structured_data')
