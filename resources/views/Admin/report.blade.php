<!DOCTYPE html>
<html lang="ur-Latn">

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
        .opt-card { background: #FFF9F5; border: 1px solid #FDE7EE; border-radius: 0.75rem; }
        table.cmp { border-collapse: collapse; width: 100%; }
        table.cmp th, table.cmp td { border: 1px solid #FDE7EE; padding: 0.5rem 0.75rem; text-align: left; vertical-align: top; }
        table.cmp th { background: #FFF3F0; }
    </style>
</head>

<body class="bg-[#FFF6F0] text-gray-800">
    <div class="flex min-h-screen">
        <x-admin.sidebar />

        <div class="flex-1 p-4 sm:p-8 max-w-5xl mx-auto w-full">
            <div class="mb-8">
                <span class="inline-block text-xs font-semibold uppercase tracking-widest text-pink-500 mb-2">Internal · Sirf Admin</span>
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Development Report</h1>
                <p class="text-gray-500 mt-1 text-sm">Ab tak jo kaam hua hai uska tarteeb-war (order-wise) record — kya bana, kis mein kya tricky baat thi, aur kya abhi baaqi hai. Har naye kaam ke baad ye page update hota rahega.</p>
            </div>

            <div class="mb-6 item-card p-5 border-l-4" style="border-left-color:#FFC275;">
                <p class="text-sm font-semibold text-gray-900 mb-2">⏳ Baaqi kaam / aapka faisla chahiye</p>
                <ul class="text-sm text-gray-600 list-disc pl-5 space-y-1">
                    <li><strong>Phone OTP (SMS)</strong> — neeche item #5 mein poora detail hai, sab options ke saath.</li>
                    <li><strong>"Gym Accessories" / "Women's Fashion" naam pehle se maujood thay</strong> — nayi categories banate waqt ye naam pehle se list mein thay, is liye tasaadum (collision) se bachne ke liye naye naam diye gaye ("Personal Gym Accessories", aur purana "Women's Fashion" — jis mein 0 products thay — ko nayi clothing category ke liye reuse kar liya). Ab merge karna hai ya aise hi rehne dena hai?</li>
                </ul>
            </div>

            <div class="space-y-4">

                <div class="item-card p-5">
                    <div class="flex items-start justify-between gap-3 mb-2">
                        <h3 class="font-semibold text-gray-900">1. Footer pages mobile-friendly</h3>
                        <span class="badge-done text-xs font-semibold px-2.5 py-1 rounded-full whitespace-nowrap">Done</span>
                    </div>
                    <p class="text-sm text-gray-600">Return &amp; Replacement page ke bullets mobile aur desktop dono pe toot rahe thay.</p>
                    <p class="text-sm text-gray-500 mt-2"><strong class="text-gray-700">Tricky baat:</strong> bullet wala &lt;li&gt; display:flex tha, aur uske andar raw &lt;strong&gt; text apna hi flex-item ban gaya tha — jumla columns mein toot raha tha. Text ko &lt;span&gt; mein wrap karke theek kiya.</p>
                </div>

                <div class="item-card p-5">
                    <div class="flex items-start justify-between gap-3 mb-2">
                        <h3 class="font-semibold text-gray-900">2. Fault images single product page pe show nahi ho rahi thi</h3>
                        <span class="badge-done text-xs font-semibold px-2.5 py-1 rounded-full whitespace-nowrap">Done</span>
                    </div>
                    <p class="text-sm text-gray-600">Vendor ke upload kiye huay fault/damage photos customer wale product page tak pohanch hi nahi rahe thay.</p>
                    <p class="text-sm text-gray-500 mt-2"><strong class="text-gray-700">Tricky baat:</strong> do alag fault systems hain (Auto Parts aur general products), aur customer-facing controller in mein se koi bhi query hi nahi karta tha — sirf vendor wala controller karta tha. Ab dono "Faults / Known Issues" tab mein dikhte hain.</p>
                </div>

                <div class="item-card p-5">
                    <div class="flex items-start justify-between gap-3 mb-2">
                        <h3 class="font-semibold text-gray-900">3. Vendor pencil / freehand annotation tool</h3>
                        <span class="badge-done text-xs font-semibold px-2.5 py-1 rounded-full whitespace-nowrap">Done</span>
                    </div>
                    <p class="text-sm text-gray-600">Vendor ab fault photo upload karne se pehle usi pe draw/circle kar ke exact damage dikha sakta hai.</p>
                    <p class="text-sm text-gray-500 mt-2"><strong class="text-gray-700">Tricky baat:</strong> drawing HTML5 canvas pe hoti hai, phir canvas.toBlob() se ek nayi image ban ke DataTransfer API ke zariye wapas usi file-input mein daal di jati hai — server ko pata hi nahi chalta ke annotation hui hai, usay bas ek normal image milti hai. Pointer Events use kiye hain taake mouse, touch, aur pen — teeno ek hi code se kaam karein.</p>
                </div>

                <div class="item-card p-5">
                    <div class="flex items-start justify-between gap-3 mb-2">
                        <h3 class="font-semibold text-gray-900">4. Address form — sirf Pakistan, 4 provinces</h3>
                        <span class="badge-done text-xs font-semibold px-2.5 py-1 rounded-full whitespace-nowrap">Done</span>
                    </div>
                    <p class="text-sm text-gray-600">Country ab Pakistan pe fix hai; State ek fixed dropdown hai — Punjab / Sindh / KPK / Balochistan.</p>
                </div>

                <div class="item-card p-5 border-l-4" style="border-left-color:#DC2626;">
                    <div class="flex items-start justify-between gap-3 mb-2">
                        <h3 class="font-semibold text-gray-900">5. Phone OTP verify + address usi verified number se linked</h3>
                        <span class="badge-skipped text-xs font-semibold px-2.5 py-1 rounded-full whitespace-nowrap">Rukka hua — faisla chahiye</span>
                    </div>
                    <p class="text-sm text-gray-600 mb-3">Edit Profile mein phone number change karte waqt, aur Address form mein bhi, OTP verification honi chahiye — customer sirf apne verified number ko hi address pe use kar sakay, warna wahan bhi OTP maangi jaye.</p>

                    <p class="text-sm text-gray-700 font-semibold mb-2">Abhi kyun nahi bana:</p>
                    <p class="text-sm text-gray-600 mb-4">App mein sirf EMAIL OTP ka nizaam hai (Mail::send se, password-reset ke liye) — koi SMS gateway abhi tak connect nahi hai. Phone pe OTP bhejne ke liye ek real SMS/WhatsApp provider chahiye, jiska account banana, verify karana, aur paisay dena padta hai. Neeche har mumkin rasta (option) tafseel se likha hai:</p>

                    <div class="space-y-3">

                        <div class="opt-card p-4">
                            <p class="font-semibold text-gray-900 mb-1">Option A — International SMS gateway (Twilio, Vonage/Nexmo, Infobip, Msg91)</p>
                            <p class="text-sm text-gray-600 mb-1"><strong>Kaisay kaam karta hai:</strong> ek account bana ke API key milti hai, phir Laravel se ek simple HTTP request bhej ke SMS chala jata hai. Sab se zyada documentation aur sabse tez integration.</p>
                            <p class="text-sm text-gray-600 mb-1"><strong>Cost:</strong> taqreeban $0.02–$0.08 fi SMS (Pakistan number pe thoda mehnga pad sakta hai). Payment credit/debit card se hoti hai, USD mein.</p>
                            <p class="text-sm text-gray-600 mb-1"><strong>Masla:</strong> Pakistan mein PTA ke SMS delivery rules ki wajah se kabhi kabhi delivery slow/fail ho sakti hai agar sender ID PTA-registered na ho. International card chahiye hoga sign-up ke liye.</p>
                            <p class="text-sm text-gray-600"><strong>Kab sahi hai:</strong> agar jaldi launch karna hai aur per-SMS cost ka masla nahi, aur international card available hai.</p>
                        </div>

                        <div class="opt-card p-4">
                            <p class="font-semibold text-gray-900 mb-1">Option B — Local Pakistani bulk-SMS reseller (Jazz Business SMS, Telenor eBiz, ya koi PTA-registered local SMS company)</p>
                            <p class="text-sm text-gray-600 mb-1"><strong>Kaisay kaam karta hai:</strong> Pakistan ke kisi local SMS company se business account banwana parta hai (CNIC/NTN documents ke saath), phir wo apna Masking/Sender-ID approve karwa ke deta hai, uske baad API milti hai.</p>
                            <p class="text-sm text-gray-600 mb-1"><strong>Cost:</strong> aksar sabse sasta option — Rs 0.30 se Rs 1 fi SMS tak, bulk packages mein aur bhi sasta. Payment PKR mein, local bank account se.</p>
                            <p class="text-sm text-gray-600 mb-1"><strong>Masla:</strong> setup mein waqt lagta hai (business verification, documents), aur company chunne mein research karni parti hai — quality/reliability company se company alag hoti hai.</p>
                            <p class="text-sm text-gray-600"><strong>Kab sahi hai:</strong> agar app ka scale bara hai (roz saikron OTPs) aur long-term cost bachani hai — one-time setup ke baad sabse sasta chalta hai.</p>
                        </div>

                        <div class="opt-card p-4">
                            <p class="font-semibold text-gray-900 mb-1">Option C — WhatsApp OTP (Meta Cloud API ya Twilio WhatsApp)</p>
                            <p class="text-sm text-gray-600 mb-1"><strong>Kaisay kaam karta hai:</strong> OTP SMS ke bajaye WhatsApp message ke zariye jata hai. Site pe pehle se WhatsApp support number maujood hai (wa.me link), isliye same infra thodi extend ho sakti hai.</p>
                            <p class="text-sm text-gray-600 mb-1"><strong>Cost:</strong> aksar SMS se sasta, aur Pakistan mein delivery bohat reliable hai kyunke tazrooba (almost) har customer WhatsApp use karta hai.</p>
                            <p class="text-sm text-gray-600 mb-1"><strong>Masla:</strong> Meta Business verification lagti hai (business documents, approval mein kuch din lag sakte hain), aur WhatsApp ka apna message-template approval process hai (OTP template pehle se approve karwana parta hai).</p>
                            <p class="text-sm text-gray-600"><strong>Kab sahi hai:</strong> agar customer base zyada tar WhatsApp use karta hai (Pakistan mein aam baat hai) — is app ke liye acha fit lagta hai kyunke WhatsApp already branding mein hai.</p>
                        </div>

                        <div class="opt-card p-4">
                            <p class="font-semibold text-gray-900 mb-1">Option D — Firebase Phone Authentication (Google)</p>
                            <p class="text-sm text-gray-600 mb-1"><strong>Kaisay kaam karta hai:</strong> Google apni taraf se SMS bhejta hai aur verify bhi khud karta hai — humein sirf Firebase SDK integrate karni hoti hai, apna OTP-generate/verify logic likhne ki zaroorat nahi.</p>
                            <p class="text-sm text-gray-600 mb-1"><strong>Cost:</strong> pehle 10,000 verifications/mahina free hain, uske baad bohat kam per-verification charge.</p>
                            <p class="text-sm text-gray-600 mb-1"><strong>Masla:</strong> frontend-heavy setup hai (Firebase JS SDK browser mein chalana parta hai, reCAPTCHA bhi involve hota hai) — Laravel backend ke saath thora extra wiring chahiye hoti hai (Firebase Admin SDK se token verify karna).</p>
                            <p class="text-sm text-gray-600"><strong>Kab sahi hai:</strong> agar cost sabse kam rakhni hai (shuru mein free) aur reliability chahiye, thora zyada dev-time lagane ko tayyar hain.</p>
                        </div>

                        <div class="opt-card p-4">
                            <p class="font-semibold text-gray-900 mb-1">Option E — Filhaal sirf Email OTP rakhein (koi SMS na add karein)</p>
                            <p class="text-sm text-gray-600 mb-1"><strong>Kaisay kaam karta hai:</strong> jo email-OTP infra pehle se hai, wahi phone-number-change waqt bhi use ho jaye (customer ka registered email pe OTP jaye, phone number tab update ho).</p>
                            <p class="text-sm text-gray-600 mb-1"><strong>Cost:</strong> Rs 0 — koi naya kharcha nahi.</p>
                            <p class="text-sm text-gray-600 mb-1"><strong>Masla:</strong> ye asal mein "phone verify" nahi hai, sirf "account owner verify" hai — agar maqsad ye hai ke phone number waqai us insaan ka hai, ye tareeqa poora bharosemand nahi.</p>
                            <p class="text-sm text-gray-600"><strong>Kab sahi hai:</strong> sirf temporary/launch-jaldi ke liye, jab tak koi SMS/WhatsApp provider decide na ho jaye.</p>
                        </div>

                    </div>

                    <div class="overflow-x-auto mt-4">
                        <table class="cmp text-sm">
                            <tr>
                                <th>Option</th>
                                <th>Setup waqt</th>
                                <th>Cost</th>
                                <th>Reliability (Pakistan)</th>
                            </tr>
                            <tr>
                                <td>A — International (Twilio waghera)</td>
                                <td>Sabse tez (1–2 din)</td>
                                <td>Zyada, USD mein</td>
                                <td>Theek, kabhi kabhi slow</td>
                            </tr>
                            <tr>
                                <td>B — Local Pakistani reseller</td>
                                <td>Dheema (documents/approval)</td>
                                <td>Sabse kam, PKR mein</td>
                                <td>Achi (agar company sahi ho)</td>
                            </tr>
                            <tr>
                                <td>C — WhatsApp OTP</td>
                                <td>Darmiyana (Meta approval)</td>
                                <td>Kam-se-darmiyana</td>
                                <td>Bohat achi</td>
                            </tr>
                            <tr>
                                <td>D — Firebase Phone Auth</td>
                                <td>Darmiyana (dev-time zyada)</td>
                                <td>Shuru mein free</td>
                                <td>Achi</td>
                            </tr>
                            <tr>
                                <td>E — Sirf Email OTP</td>
                                <td>Turant (already ban chuka)</td>
                                <td>Rs 0</td>
                                <td>Phone verify nahi karta</td>
                            </tr>
                        </table>
                    </div>

                    <p class="text-sm text-gray-700 mt-4"><strong class="text-gray-900">Meri rai (recommendation):</strong> is app ke liye Option C (WhatsApp OTP) sabse acha fit lagta hai — Pakistan mein SMS se zyada reliable hai, cost bhi kam hai, aur site pe WhatsApp branding pehle se maujood hai. Agar WhatsApp Business verification mein waqt lag jaye to darmiyani muddat ke liye Option A (Twilio) se jaldi shuru kar ke baad mein switch kiya ja sakta hai.</p>
                    <p class="text-sm text-gray-700 mt-2"><strong class="text-gray-900">Aap se chahiye:</strong> bata dein kaunsa option pasand hai, aur uska account/API-key bana kar de dein (ya bolein main sign-up ka tareeqa bata doon) — us ke baad ye feature turant ban sakti hai.</p>
                </div>

                <div class="item-card p-5">
                    <div class="flex items-start justify-between gap-3 mb-2">
                        <h3 class="font-semibold text-gray-900">6. Shipping fee — flat Rs 300 + vendor free-delivery option</h3>
                        <span class="badge-done text-xs font-semibold px-2.5 py-1 rounded-full whitespace-nowrap">Done</span>
                    </div>
                    <p class="text-sm text-gray-600">Cart aur checkout dono ab Rs 300 flat shipping lagate hain, ya Rs 0 ("Free Delivery") jab cart ke sab items vendor ki taraf se free-delivery marked hon.</p>
                </div>

                <div class="item-card p-5">
                    <div class="flex items-start justify-between gap-3 mb-2">
                        <h3 class="font-semibold text-gray-900">7. Buy Now → login → Checkout redirect + Profile "Back to Checkout"</h3>
                        <span class="badge-done text-xs font-semibold px-2.5 py-1 rounded-full whitespace-nowrap">Done</span>
                    </div>
                    <p class="text-sm text-gray-600">Logged-out customer Buy Now dabaye to login ke baad ab sahi Checkout page pe pohanchta hai (pehle Profile/Dashboard pe chala jata tha).</p>
                    <p class="text-sm text-gray-500 mt-2"><strong class="text-gray-700">Tricky baat:</strong> redirect URL mein shuru ka "/" missing tha — login JS ka ek safety-check chup-chaap usay reject kar ke dashboard pe bhej deta tha. Ek aur bug bhi theek kiya: checkout ke baad Profile pe Back button dabane se purana state dikhta tha jab tak manually refresh na karo (bfcache issue) — ab automatically reload hota hai.</p>
                </div>

                <div class="item-card p-5">
                    <div class="flex items-start justify-between gap-3 mb-2">
                        <h3 class="font-semibold text-gray-900">8. Checkout — discount hataya, 2.5% tax laga diya</h3>
                        <span class="badge-done text-xs font-semibold px-2.5 py-1 rounded-full whitespace-nowrap">Done</span>
                    </div>
                    <p class="text-sm text-gray-600">Checkout se coupon/discount ka nizaam hata diya; ab har order pe hamesha 2.5% tax lagta hai jo asal charge hone wali total mein bhi shamil hai (sirf summary mein nahi dikhaya jata).</p>
                </div>

                <div class="item-card p-5">
                    <div class="flex items-start justify-between gap-3 mb-2">
                        <h3 class="font-semibold text-gray-900">9. Order tracking timeline icons</h3>
                        <span class="badge-done text-xs font-semibold px-2.5 py-1 rounded-full whitespace-nowrap">Done</span>
                    </div>
                    <p class="text-sm text-gray-600">Customer Orders page ke status-step icons ab asal order status ke mutabiq sahi dikhte hain.</p>
                    <p class="text-sm text-gray-500 mt-2"><strong class="text-gray-700">Tricky baat:</strong> JS selector aisi Tailwind classes dhoond raha tha ("items-center justify-between") jo markup mein kabhi ek saath aati hi nahi thi (wahan responsive-prefixed classes thin, jaise "md:items-center") — is liye selector 0 elements match karta tha aur kuch update hi nahi hota tha.</p>
                </div>

                <div class="item-card p-5">
                    <div class="flex items-start justify-between gap-3 mb-2">
                        <h3 class="font-semibold text-gray-900">10. Vendor order-notification badge</h3>
                        <span class="badge-done text-xs font-semibold px-2.5 py-1 rounded-full whitespace-nowrap">Pehle se kaam kar raha tha</span>
                    </div>
                    <p class="text-sm text-gray-600">Poori tarah check kiya — koi code change ki zaroorat nahi thi, order aane pe vendor ko notification pehle se milti thi. Agar live site pe nahi dikh raha to isliye ke mjcheezain.com manual upload se update hota hai, auto-deploy se nahi.</p>
                </div>

                <div class="item-card p-5">
                    <div class="flex items-start justify-between gap-3 mb-2">
                        <h3 class="font-semibold text-gray-900">11. Return Policy — pura workflow</h3>
                        <span class="badge-done text-xs font-semibold px-2.5 py-1 rounded-full whitespace-nowrap">Done</span>
                    </div>
                    <p class="text-sm text-gray-600">Vendor ab sirf return request pe comment de sakta hai; final approve/reject decision Admin leta hai. Courier ka kharcha return ki wajah (reason) se decide hota hai (vendor ki ghalti → vendor pay karega; customer ki apni marzi → customer pay karega). Product physically wapas milne ke baad ek Quality-Check step add kiya hai: Pass → refund, Fail → product customer ko wapas, refund nahi.</p>
                    <p class="text-sm text-gray-500 mt-2"><strong class="text-gray-700">Tricky baat:</strong> pehle vendor khud approve/reject status set kar sakta tha — ye policy ke khilaf tha, ab sirf comment tak limit kar diya. Auto-generate Return Order ID, video upload, aur return photos pe pencil annotation (wahi tool jo vendor fault photos ke liye bana) bhi add kiye.</p>
                </div>

                <div class="item-card p-5">
                    <div class="flex items-start justify-between gap-3 mb-2">
                        <h3 class="font-semibold text-gray-900">12. Replacement Policy — pura naya form</h3>
                        <span class="badge-done text-xs font-semibold px-2.5 py-1 rounded-full whitespace-nowrap">Done</span>
                    </div>
                    <p class="text-sm text-gray-600">Customer ab replacement request kar sakta hai — is flow mein refund ka koncept sirey se nahi hai.</p>
                    <p class="text-sm text-gray-500 mt-2"><strong class="text-gray-700">Tricky baat:</strong> replacement product usi store se hona chahiye aur original price se same ya zyada honi chahiye — ye server-side check hoti hai (client-side preview pe bharosa nahi kiya jata). Agar replacement mehnga ho to price ka farq "Additional Amount Payable" mein khud-b-khud add ho jata hai. Agar asal masla vendor ki ghalti se tha to customer se delivery charge nahi liya jata.</p>
                </div>

                <div class="item-card p-5">
                    <div class="flex items-start justify-between gap-3 mb-2">
                        <h3 class="font-semibold text-gray-900">13. Reference ID system (MJ-CUS/VEN/ORD/PRD/RET/RPL...)</h3>
                        <span class="badge-done text-xs font-semibold px-2.5 py-1 rounded-full whitespace-nowrap">Done</span>
                    </div>
                    <p class="text-sm text-gray-600">Ab customer, vendor, aur admin — teeno jagah ek jaisi formatted ID dikhti hai.</p>
                    <p class="text-sm text-gray-500 mt-2"><strong class="text-gray-700">Tricky baat (asal bug):</strong> Order ID customer, vendor, aur print-hone wale shipping label — teeno pe alag number dikha raha tha — vendor wala view cart ke line-item ki ID dikha raha tha, asal order ID nahi, aur print label vendor ke (ghalat) number se match karta tha, customer ke (sahi) number se nahi. Ab teeno jagah ek hi shared helper se ID banti hai.</p>
                </div>

                <div class="item-card p-5">
                    <div class="flex items-start justify-between gap-3 mb-2">
                        <h3 class="font-semibold text-gray-900">14. Jewellery &amp; Accessories category (11 subcategory forms)</h3>
                        <span class="badge-done text-xs font-semibold px-2.5 py-1 rounded-full whitespace-nowrap">Done</span>
                    </div>
                    <p class="text-sm text-gray-600">Rings, Necklace, Earrings, Bangles, Chain, Pendants, Anklets, Nose Pins, Brooches, Charms, Jewelry Sets — har ek ka apna dynamic form.</p>
                    <p class="text-sm text-gray-500 mt-2"><strong class="text-gray-700">Tricky baat:</strong> Purity field sirf Gold ya Silver material pe dikhti hai (dono ki apni option list ke saath), aur Stone Type sirf tab dikhta hai jab "Stone Included" = Yes ho — ye sab conditional show/hide JS se handle kiya, taake form har subcategory ke hisab se saaf rahay.</p>
                </div>

                <div class="item-card p-5">
                    <div class="flex items-start justify-between gap-3 mb-2">
                        <h3 class="font-semibold text-gray-900">15. Fragrance, Bags, Gym, Kitchen, Smart Home, Personal Care, Electronic Accessories</h3>
                        <span class="badge-done text-xs font-semibold px-2.5 py-1 rounded-full whitespace-nowrap">Done</span>
                    </div>
                    <p class="text-sm text-gray-600">7 nayi categories add hui hain, zyada tar ek hi common fields set share karti hain; Fragrance mein 4 conditional subcategory-specific fields hain (Perfumes ke liye Fragrance Type, Attars/Oils ke liye Alcohol Free, Deodorants ke liye Deodorant Type, Gift Sets ke liye Included Items).</p>
                </div>

                <div class="item-card p-5">
                    <div class="flex items-start justify-between gap-3 mb-2">
                        <h3 class="font-semibold text-gray-900">16. Fashion &amp; Clothing — baaqi 4 categories</h3>
                        <span class="badge-done text-xs font-semibold px-2.5 py-1 rounded-full whitespace-nowrap">Done</span>
                    </div>
                    <p class="text-sm text-gray-600">Women's Fashion, Kids &amp; Baby Fashion, Footwear, aur Fashion Accessories &amp; Bags — ab in sab ke apne dynamic fields hain, Men's Fashion ke saath.</p>
                </div>

                <div class="item-card p-5">
                    <div class="flex items-start justify-between gap-3 mb-2">
                        <h3 class="font-semibold text-gray-900">17. Product video banner khali safed tasveer jaisa dikhta tha</h3>
                        <span class="badge-done text-xs font-semibold px-2.5 py-1 rounded-full whitespace-nowrap">Done</span>
                    </div>
                    <p class="text-sm text-gray-600">Styled video frame (pink border, shadow, "Watch Now" badge) ab sirf tab dikhta hai jab product ka waqai video upload ho.</p>
                </div>

            </div>
        </div>
    </div>
</body>
</html>
