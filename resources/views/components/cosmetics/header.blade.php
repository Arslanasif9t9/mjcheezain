@props(['user' => null, 'vendor' => null, 'profile' => null, 'dashboardPage' => null, 'imgPath' => null])

{{-- All pages share the same header as the home page (user decision, 2026-07-14).
     The :vendor prop keeps the "View Store" button on single product pages. --}}
<x-site-header :user="$user" :vendor="$vendor" :profile="$profile" :dashboardPage="$dashboardPage" :imgPath="$imgPath" />
