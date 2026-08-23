<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Development Report | MJCheezain Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; }
        .brand-gradient { background: linear-gradient(115deg, #FF7DA0 0%, #FFC275 100%); }
        .item-card {
            background: #fff;
            border-radius: 1rem;
            border: 1px solid #FDE7EE;
            box-shadow: 0 8px 24px rgba(232, 93, 133, 0.06);
        }
        .badge-done { background: #ECFDF5; color: #059669; }
        .badge-pending { background: #FFFBEB; color: #B45309; }
        .badge-progress { background: #EFF6FF; color: #2563EB; }
        .badge-skipped { background: #FEF2F2; color: #DC2626; }
    </style>
</head>

<body class="bg-[#FFF6F0] text-gray-800">
    <div class="flex min-h-screen">
        <x-admin.sidebar />

        <div class="flex-1 p-4 sm:p-8 max-w-5xl mx-auto w-full">
            <div class="mb-8">
                <span class="inline-block text-xs font-semibold uppercase tracking-widest text-pink-500 mb-2">Internal · Admin Only</span>
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Development Report</h1>
                <p class="text-gray-500 mt-1 text-sm">Ordered log of what's been built, what's tricky about it, and what's still pending. Updated after every batch of work.</p>
            </div>

            <div class="mb-6 item-card p-5 border-l-4" style="border-left-color:#FFC275;">
                <p class="text-sm font-semibold text-gray-900 mb-2">⏳ Pending / needs your input</p>
                <ul class="text-sm text-gray-600 list-disc pl-5 space-y-1">
                    <li><strong>Phone OTP (SMS)</strong> — no SMS gateway is wired into the app yet (only email OTP exists, via Mail::send). Need a provider name + account before this can be built.</li>
                    <li><strong>"Gym Accessories" / "Women's Fashion" naming duplicates</strong> — both names already existed in the category list before the new work; new categories were given adjusted names to avoid collisions ("Personal Gym Accessories", and the old "Women's Fashion" — which had 0 products — was repurposed into the new clothing category). Merge or keep as-is?</li>
                </ul>
            </div>

            <div class="space-y-4">

                <div class="item-card p-5">
                    <div class="flex items-start justify-between gap-3 mb-2">
                        <h3 class="font-semibold text-gray-900">1. Footer pages mobile-friendly</h3>
                        <span class="badge-done text-xs font-semibold px-2.5 py-1 rounded-full whitespace-nowrap">Done</span>
                    </div>
                    <p class="text-sm text-gray-600">Return &amp; Replacement page bullets were breaking apart on mobile and desktop.</p>
                    <p class="text-sm text-gray-500 mt-2"><strong class="text-gray-700">Tricky part:</strong> the bullet &lt;li&gt; was display:flex, and a raw &lt;strong&gt; text node as a direct child became its own flex item and got blockified — sentences split into columns. Fixed by wrapping text in a &lt;span&gt;.</p>
                </div>

                <div class="item-card p-5">
                    <div class="flex items-start justify-between gap-3 mb-2">
                        <h3 class="font-semibold text-gray-900">2. Fault images not showing on single product page</h3>
                        <span class="badge-done text-xs font-semibold px-2.5 py-1 rounded-full whitespace-nowrap">Done</span>
                    </div>
                    <p class="text-sm text-gray-600">Vendor-uploaded fault/damage photos never reached the customer-facing product page.</p>
                    <p class="text-sm text-gray-500 mt-2"><strong class="text-gray-700">Tricky part:</strong> two separate fault systems exist (Auto Parts vs general products) and the customer-facing controller never queried either — only the vendor-side controller did. Both now render in a "Faults / Known Issues" tab.</p>
                </div>

                <div class="item-card p-5">
                    <div class="flex items-start justify-between gap-3 mb-2">
                        <h3 class="font-semibold text-gray-900">3. Vendor pencil / freehand annotation tool</h3>
                        <span class="badge-done text-xs font-semibold px-2.5 py-1 rounded-full whitespace-nowrap">Done</span>
                    </div>
                    <p class="text-sm text-gray-600">Vendors can draw/circle exactly where damage is on a fault photo before uploading.</p>
                    <p class="text-sm text-gray-500 mt-2"><strong class="text-gray-700">Tricky part:</strong> drawing happens on an HTML5 canvas, then gets flattened into a new image file via canvas.toBlob() and injected back into the original file input via the DataTransfer API — the server never even knows annotation happened, it just receives a normal image. Built on Pointer Events so mouse, touch, and pen all work through one code path.</p>
                </div>

                <div class="item-card p-5">
                    <div class="flex items-start justify-between gap-3 mb-2">
                        <h3 class="font-semibold text-gray-900">4. Address form — Pakistan only, 4 provinces</h3>
                        <span class="badge-done text-xs font-semibold px-2.5 py-1 rounded-full whitespace-nowrap">Done</span>
                    </div>
                    <p class="text-sm text-gray-600">Country locked to Pakistan; State is a fixed dropdown of Punjab / Sindh / KPK / Balochistan.</p>
                </div>

                <div class="item-card p-5 border-l-4" style="border-left-color:#DC2626;">
                    <div class="flex items-start justify-between gap-3 mb-2">
                        <h3 class="font-semibold text-gray-900">5. Phone OTP verify + address linked to verified number</h3>
                        <span class="badge-skipped text-xs font-semibold px-2.5 py-1 rounded-full whitespace-nowrap">Skipped — needs decision</span>
                    </div>
                    <p class="text-sm text-gray-600">Edit Profile phone number changes and Address-form phone numbers should require OTP verification.</p>
                    <p class="text-sm text-gray-500 mt-2"><strong class="text-gray-700">Why skipped:</strong> the app only has email OTP today (Mail::send-based). Sending SMS OTPs needs a real SMS gateway (Twilio, or a local Pakistani provider) — no such account/integration exists yet.</p>
                </div>

                <div class="item-card p-5">
                    <div class="flex items-start justify-between gap-3 mb-2">
                        <h3 class="font-semibold text-gray-900">6. Shipping fee — flat Rs 300 + vendor free-delivery option</h3>
                        <span class="badge-done text-xs font-semibold px-2.5 py-1 rounded-full whitespace-nowrap">Done</span>
                    </div>
                    <p class="text-sm text-gray-600">Cart and checkout now both charge a flat Rs 300 shipping, or Rs 0 ("Free Delivery") when every item in the cart is marked free-delivery by its vendor.</p>
                </div>

                <div class="item-card p-5">
                    <div class="flex items-start justify-between gap-3 mb-2">
                        <h3 class="font-semibold text-gray-900">7. Buy Now → login → Checkout redirect + Profile "Back to Checkout"</h3>
                        <span class="badge-done text-xs font-semibold px-2.5 py-1 rounded-full whitespace-nowrap">Done</span>
                    </div>
                    <p class="text-sm text-gray-600">Buy Now while logged out now correctly lands back on Checkout after login (was landing on Profile/Dashboard).</p>
                    <p class="text-sm text-gray-500 mt-2"><strong class="text-gray-700">Tricky part:</strong> the redirect URL was missing a leading "/" — a same-site safety check in the login JS silently rejected it and fell back to the dashboard. Also added a fix for a stale-page bug: pressing browser Back to Profile after checkout showed old state until manually refreshed (classic bfcache issue) — now auto-reloads via a pageshow listener.</p>
                </div>

                <div class="item-card p-5">
                    <div class="flex items-start justify-between gap-3 mb-2">
                        <h3 class="font-semibold text-gray-900">8. Checkout — discount removed, 2.5% tax added</h3>
                        <span class="badge-done text-xs font-semibold px-2.5 py-1 rounded-full whitespace-nowrap">Done</span>
                    </div>
                    <p class="text-sm text-gray-600">Coupon/discount mechanism removed from checkout; a transparent 2.5% tax line is now always applied and included in the real charged total, not just the displayed summary.</p>
                </div>

                <div class="item-card p-5">
                    <div class="flex items-start justify-between gap-3 mb-2">
                        <h3 class="font-semibold text-gray-900">9. Order tracking timeline icons</h3>
                        <span class="badge-done text-xs font-semibold px-2.5 py-1 rounded-full whitespace-nowrap">Done</span>
                    </div>
                    <p class="text-sm text-gray-600">Status step icons on the customer Orders page now correctly reflect the real order status.</p>
                    <p class="text-sm text-gray-500 mt-2"><strong class="text-gray-700">Tricky part:</strong> the JS selector was looking for literal Tailwind class combos like "items-center justify-between" that never actually appear together in the markup (which used responsive-prefixed classes like "md:items-center") — so it silently matched zero elements and never updated anything.</p>
                </div>

                <div class="item-card p-5">
                    <div class="flex items-start justify-between gap-3 mb-2">
                        <h3 class="font-semibold text-gray-900">10. Vendor order notification badge</h3>
                        <span class="badge-done text-xs font-semibold px-2.5 py-1 rounded-full whitespace-nowrap">Already working</span>
                    </div>
                    <p class="text-sm text-gray-600">Verified end-to-end — no code change was needed, it already notifies the vendor when an order comes in. If it's not showing on the live site, that's because mjcheezain.com is updated by manual upload, not auto-deploy.</p>
                </div>

                <div class="item-card p-5">
                    <div class="flex items-start justify-between gap-3 mb-2">
                        <h3 class="font-semibold text-gray-900">11. Return Policy — full workflow</h3>
                        <span class="badge-done text-xs font-semibold px-2.5 py-1 rounded-full whitespace-nowrap">Done</span>
                    </div>
                    <p class="text-sm text-gray-600">Vendor can only comment on a return request; Admin makes the final approve/reject decision. Courier cost is attributed based on the return reason (vendor's fault → vendor pays; customer's own choice → customer pays). A Quality-Check checkpoint was added after the product is physically received: Pass → refund, Fail → product sent back to customer, no refund.</p>
                    <p class="text-sm text-gray-500 mt-2"><strong class="text-gray-700">Tricky part:</strong> vendor previously could set approve/reject status directly — that broke the policy. Now locked to comment-only. Also added an auto-generated Return Order ID, video upload, and pencil annotation on return photos (reusing the same annotation tool built for vendor fault photos).</p>
                </div>

                <div class="item-card p-5">
                    <div class="flex items-start justify-between gap-3 mb-2">
                        <h3 class="font-semibold text-gray-900">12. Replacement Policy — full new form</h3>
                        <span class="badge-done text-xs font-semibold px-2.5 py-1 rounded-full whitespace-nowrap">Done</span>
                    </div>
                    <p class="text-sm text-gray-600">Customers can request a replacement — no refund concept exists in this flow.</p>
                    <p class="text-sm text-gray-500 mt-2"><strong class="text-gray-700">Tricky part:</strong> replacement product must be from the same store and priced the same or higher than the original — this is validated server-side (never trusts the client-side preview). If the replacement costs more, the price difference is auto-calculated as "Additional Amount Payable." If the original issue was the vendor's fault, delivery charge is waived for the customer.</p>
                </div>

                <div class="item-card p-5">
                    <div class="flex items-start justify-between gap-3 mb-2">
                        <h3 class="font-semibold text-gray-900">13. Reference ID system (MJ-CUS/VEN/ORD/PRD/RET/RPL...)</h3>
                        <span class="badge-done text-xs font-semibold px-2.5 py-1 rounded-full whitespace-nowrap">Done</span>
                    </div>
                    <p class="text-sm text-gray-600">Consistent formatted IDs across customer, vendor, and admin views.</p>
                    <p class="text-sm text-gray-500 mt-2"><strong class="text-gray-700">Tricky part (the real bug):</strong> the Order ID shown to the customer, the vendor, and the printed shipping label were three different numbers — the vendor view was showing a cart line-item ID instead of the real order ID, and the print label matched the vendor's (wrong) number instead of the customer's (right) one. All three now resolve through one shared helper.</p>
                </div>

                <div class="item-card p-5">
                    <div class="flex items-start justify-between gap-3 mb-2">
                        <h3 class="font-semibold text-gray-900">14. Jewellery &amp; Accessories category (11 subcategory forms)</h3>
                        <span class="badge-done text-xs font-semibold px-2.5 py-1 rounded-full whitespace-nowrap">Done</span>
                    </div>
                    <p class="text-sm text-gray-600">Rings, Necklace, Earrings, Bangles, Chain, Pendants, Anklets, Nose Pins, Brooches, Charms, Jewelry Sets — each with its own dynamic fields.</p>
                    <p class="text-sm text-gray-500 mt-2"><strong class="text-gray-700">Tricky part:</strong> Purity field only appears for Gold or Silver material (with different option lists for each), and Stone Type only appears when "Stone Included" is Yes — all handled with conditional show/hide JS, keeping the form clean per subcategory.</p>
                </div>

                <div class="item-card p-5">
                    <div class="flex items-start justify-between gap-3 mb-2">
                        <h3 class="font-semibold text-gray-900">15. Fragrance, Bags, Gym, Kitchen, Smart Home, Personal Care, Electronic Accessories</h3>
                        <span class="badge-done text-xs font-semibold px-2.5 py-1 rounded-full whitespace-nowrap">Done</span>
                    </div>
                    <p class="text-sm text-gray-600">7 new categories added, most sharing one common field set; Fragrance has 4 conditional subcategory-specific fields (Fragrance Type for Perfumes, Alcohol Free for Attars/Oils, Deodorant Type, Included Items for Gift Sets).</p>
                </div>

                <div class="item-card p-5">
                    <div class="flex items-start justify-between gap-3 mb-2">
                        <h3 class="font-semibold text-gray-900">16. Fashion &amp; Clothing — remaining 4 categories</h3>
                        <span class="badge-done text-xs font-semibold px-2.5 py-1 rounded-full whitespace-nowrap">Done</span>
                    </div>
                    <p class="text-sm text-gray-600">Women's Fashion, Kids &amp; Baby Fashion, Footwear, and Fashion Accessories &amp; Bags now have their own dynamic fields, alongside Men's Fashion.</p>
                </div>

                <div class="item-card p-5">
                    <div class="flex items-start justify-between gap-3 mb-2">
                        <h3 class="font-semibold text-gray-900">17. Product video banner looked like a blank white placeholder</h3>
                        <span class="badge-done text-xs font-semibold px-2.5 py-1 rounded-full whitespace-nowrap">Done</span>
                    </div>
                    <p class="text-sm text-gray-600">The styled video frame (pink border, shadow, "Watch Now" badge) now only shows when a product actually has a video uploaded.</p>
                </div>

            </div>
        </div>
    </div>
</body>
</html>
