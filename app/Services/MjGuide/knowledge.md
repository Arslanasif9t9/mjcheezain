# MJ Cheezain — Website Knowledge Base (for MJGuider)

## About the website
- MJ Cheezain is a Pakistani multivendor e-commerce platform. Many vendors sell through one storefront.
- Main category right now: **Cosmetics**. A second category, **Auto Parts**, is "Coming Soon" (not launched yet).
- Website: https://mjcheezain.com — works great on mobile phones (app-like experience).
- Currency: Pakistani Rupees, always shown as "Rs.".
- Payment method: **Cash on Delivery (COD)** only. Online payment (cards/wallets/bank transfer) is not available yet.

## Official contact
- Customer support email: **support@mjcheezain.com**
- Vendor / seller inquiries email: **sellers@mjcheezain.com**
- Phone number: NOT published yet — it will be announced soon. Email is the official contact channel.
- Contact page: /contact-us

## Creating an account (signup)
- One login page for everyone: **/login-user** (also opens as a popup from the account icon in the header).
- At the top, choose account type: **Customer** or **Vendor Portal**. Then press "Sign Up".
- Signup fields: Full Name, Email, a 4-digit **OTP**, Password (minimum 6 characters), Confirm Password.
- OTP flow: type your email, press the **Get OTP** button next to the email field. A 4-digit code is emailed to you. It is valid for **10 minutes**; you can resend after a **60-second** timer.
- After signup you are logged in automatically: customers land on /customer/dashboard, vendors on /vendor/dashboard.
- The SAME email can have one Customer account AND one Vendor account — they are separate accounts with separate passwords.

### Common signup problems
- "Email already taken": that email already has an account of that same type — log in instead, or use Forgot Password.
- "Invalid or expired OTP": the code is wrong or older than 10 minutes — press Get OTP again (after the 60-second timer) and enter the new code.
- OTP email not arriving: check the Spam/Junk folder, wait a minute, then resend.
- Password errors: password must be at least 6 characters and both password fields must match.

## Logging in
1. Open /login-user (or the account icon popup).
2. Choose the correct tab first: **Customer** or **Vendor Portal**.
3. Enter your registered email and password, press Sign In.
- Customers go to /customer/dashboard, vendors to /vendor/dashboard.

### Common login problems
- "Invalid email or password": most often the WRONG TAB is selected — a vendor email will not log in on the Customer tab and vice versa. Also re-check spelling and that you registered first.
- Forgotten password: use the "Forgot your password?" link (see below).
- Still stuck: email support@mjcheezain.com.

## Password reset (works for BOTH customers and vendors)
- On the login page, the "Forgot your password?" link opens /customer-forgot-password (Customer tab) or /vendor-forgot-password (Vendor Portal tab). Both use the same 4 steps:
1. Enter your registered email and press Send OTP. (If the email is not registered, it will show an error — sign up instead.)
2. Enter the 4-digit OTP sent to your email (valid 10 minutes; resend after 60 seconds).
3. Enter a new password (minimum 6 characters) and confirm it.
4. Success — log in again with the new password.

## Shopping — how to order
1. Browse from the home page (categories, featured vendors, sliders), use the Search bar, or open **/products/all** for all products.
2. Open a product page to see images, price, discount, rating, reviews, description and the vendor's shipping time.
3. Press **Add to Cart**, or **Buy Now** to go straight to checkout.
4. Open the cart at **/cart**, review items and quantities, press Checkout (**/checkout**).
5. Checkout requires login — guests are sent to the login page first and brought back.
6. On checkout: choose or add a delivery address, payment is Cash on Delivery, add contact details and optional order notes, place the order.
7. Pay the rider in cash when the order arrives. No advance payment is ever required.

## Orders, statuses & delivery
- Each item in an order has its own status, updated by that item's vendor:
  **Order Placed → Processing → Shipping → Delivered** (or **Cancelled**).
- You get a notification every time a status changes.
- Track: My Orders (/customer/orders) → Track button → step-by-step timeline. There are no courier tracking numbers — the timeline is the tracking.
- Delivery time: each product shows its own **shipping time**, set by its vendor, on the product page. Different items in one order can arrive separately (different vendors).
- Cancel: use the Cancel button on the item in My Orders. If the button is no longer available, email support@mjcheezain.com.
- MJGuider cannot see any live order status — always guide the user to My Orders → Track.

## Customer panel (after login)
- **Dashboard** (/customer/dashboard): order stats and recent orders.
- **My Orders** (/customer/orders): all orders with per-item status. Buttons: Track, Cancel, Rate Product, Request Return, Request Replacement.
- **Wishlist** (/customer/wishlist): products saved with the heart icon (login required to save).
- **Addresses** (/customer/addresses): add/edit delivery addresses, set a default.
- **Profile** (/customer/profile) and **Profile Settings** (/customer/profile/edit): photo, name, email, phone, birthday, bio.
- **Notifications** (/customer/notifications): order updates and platform messages, grouped by date.
- On mobile a bottom tab bar switches between these pages.

## Returns & replacements (customer side)
- Both are requested from **My Orders** after an item is DELIVERED.
- Return: item → Request Return → choose reason, add details and a photo → submit → follow the multi-step timeline on the return tracking page (up to refund). A pending return request can be cancelled.
- Replacement: item → Request Replacement → describe the issue → submit → track it from the same order; you may be asked to mark the item as shipped back.
- The vendor reviews the request; notifications are sent on every status change. Admins oversee disputes.

## Ratings & reviews
- After delivery: My Orders → Rate Product → stars plus an optional written review with photos. Reviews appear on the product page.

## Becoming a vendor & the vendor journey
1. Sign up on /login-user with the **Vendor Portal** tab selected (same OTP signup as customers). Info page: /vendor-zone. Questions: sellers@mjcheezain.com.
2. Complete your store profile at /vendor/profile-edit: basic info, store details, address, photos.
3. Add products: /vendor/products → Add New (/vendor/products/create). You can save as **Draft** (only you see it) or **Publish**.
4. Published products go to **pending approval** — MJ Cheezain admins review every product before it appears on the storefront. Editing an already-approved product keeps it live.
5. Set each product's price (Rs.), discount/MRP, quantity, images, description and **shipping time** (this is the delivery estimate customers see).
6. Vendors can also suggest a new category while adding a product; admins approve suggestions.
7. Orders: /vendor/orders — see customer orders and update each item's status (Order Placed / Processing / Shipping / Delivered / Cancelled). The customer is notified automatically.
8. Earnings: delivered orders build your balance (see /vendor/dashboard and /vendor/balance/details for transactions).
9. Withdraw: /vendor/withdraw — minimum withdrawal **Rs. 100**, requires your bank name and account details. The amount moves to "pending" while admins process the request. History is on the balance details page.
10. Returns & replacements: review and process customer requests at /vendor/returns and /vendor/replacements.
- Vendor notifications: /vendor/notifications.

## Site features — quick how-tos
- **Search**: use the search bar in the header on any page; results show matching products.
- **Wishlist**: tap the heart icon on any product (login required); view at /customer/wishlist.
- **Cart**: /cart — change quantities, remove items, or clear the cart.
- **Addresses**: /customer/addresses or add a new one during checkout.
- **Notifications**: bell icon / panel pages; tap to mark as read.
- **MJGuider (this assistant)**: the chat bubble on the site. It answers questions about MJ Cheezain and can suggest products — suggested products appear as small cards under its reply with name, price and link. It CANNOT see your personal account, orders or any private data, and chat history stays only in your browser.

## Info pages
- About: /about · Contact: /contact-us · FAQs: /FAQs · Vendor Zone: /vendor-zone
- Privacy Policy: /privacy-policy · Legal: /legal-policies · Cookie Policy: /cookie-policy · Disclaimer: /disclaimer · Future Vision: /future-vision

## Things that do NOT exist yet (never claim they do)
- No online payment — COD only. If asked about card/wallet/bank payment: it is coming in the future, for now pay cash on delivery.
- No phone support number yet — email support@mjcheezain.com.
- No mobile app in app stores — the website itself is app-like on mobile.
- Auto Parts category is not launched yet ("Coming Soon").
- No courier tracking numbers — tracking is the in-site order timeline.

## Privacy stance (for MJGuider's own behavior)
- MJ Cheezain never shares one user's information with another user, and neither does MJGuider.
- To reach a vendor about a product or order, use the order's return/replacement flow or email support@mjcheezain.com — personal contact details of vendors, customers or staff are never given out.
