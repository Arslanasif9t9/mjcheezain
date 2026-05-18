<?php
require('fpdf.php');

$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', '', 12);

// Your content text
$content = <<<EOD
🔒 Privacy Policy – Your Data, Our Responsibility
At MJCheezain, we are fully committed to protecting your personal information. When you use our website, we collect only the necessary data to process your orders, improve your shopping experience, and provide customer support.

What We Collect:
- Name, address, and contact information
- Order history and preferences
- Payment and transaction details (via secure gateways)

We never sell, rent, or misuse your personal data. All information is encrypted and stored securely.

You have full control over your account and can request data updates or deletion at any time. By using our platform, you agree to our privacy practices.

We believe your data is your property — and our duty is to protect it.

🍪 Cookie Policy (Optional but Recommended)
MJCheezain uses cookies to enhance your browsing experience. Cookies are small files stored on your device that help us remember:
- What you’ve added to your cart
- Your login status
- Your preferences and browsing history

These cookies help us personalize your shopping journey, display relevant content, and track performance for improvement.

You can choose to accept or reject cookies at any time through your browser settings. However, disabling cookies may limit some website features like saved carts or faster login.

We use cookies responsibly — and never for spying or unauthorized data sharing.

⚠️ Disclaimer (Optional but Important)
The content, product listings, and vendor information on MJCheezain are provided “as-is” and are for general information purposes only.

While we strive for 100% accuracy:
- Product images may differ slightly from real items
- Vendor descriptions are based on their input
- Prices, stock, and availability may change without notice

MJCheezain is not liable for any losses caused by vendor errors, courier delays, or buyer misuse.

Buyers and sellers must exercise their own judgment and use the platform at their own risk, under the outlined policies.

📞 5. Social & Contact – Let’s Stay Connected
We make it easy for our users to reach out for help, suggestions, or partnerships.

📱 Contact Channels:
WhatsApp: Get instant support through live chat on WhatsApp. We’re available to answer your questions, guide you with orders, and solve problems in real time.

Phone: Call us for voice support regarding orders, vendor help, or urgent issues.
📞 03XX-XXXXXXX

Email: For business inquiries, feedback, and order issues
📧 support@mjcheezain.com

🌍 Social Media Presence:
(Coming Soon – Icons will be added to the footer)

Facebook: Follow us for updates, offers, and new launches

Instagram: Discover featured products, deals, and behind-the-scenes content

YouTube: Watch product demos, tutorials, and seller tips

🏢 Office Address (If applicable):
If MJCheezain opens a physical office or warehouse, its location will be officially listed here for visits, returns, or business meetings.
EOD;

// Split the text into multiple lines
$lines = explode("\n", $content);
$lineHeight = 7;

foreach ($lines as $line) {
    // Multicell automatically wraps text
    $pdf->MultiCell(0, $lineHeight, $line);
    $pdf->Ln(1);
}

$pdf->Output('D', 'MJCheezain-Privacy-Policy.pdf');  // Force download
?>
