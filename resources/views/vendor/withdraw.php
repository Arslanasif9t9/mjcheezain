<?php
    session_start();
    if (!isset($_SESSION['user_id'])) {
        header("Location: ../index.php");
    }
    elseif (isset($_SESSION['type']) && $_SESSION['type'] == "customer") {
        header("Location: ../customer/dashboard.php");
    }

    require_once '../mydatabase/conn.php';
    $user_id = $_SESSION['user_id'];
    // Get basic info
    $stmt = $conn->prepare("SELECT * FROM vendor_basic_info WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $basic_info = $result->fetch_assoc();
    // Default values if data doesn't exist
    $profile_picture = !empty($basic_info['profile_picture']) ? $basic_info['profile_picture'] : '../img/default_profile.webp';
    $full_name = $basic_info['full_name'] ?? 'Not specified';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Withdraw</title>
    <!-- Tailwind CSS  -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="./CDN tailwind.js"></script>
    <!-- font-awesome  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google font  -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=PT+Serif:ital,wght@0,400;0,700;1,400;1,700&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="../css/vendor_dashboard.css">
    <link rel="stylesheet" href="../css/vendor_navbar.css">
    <style>
        main {
            max-width: 800px;
            margin: auto;
        }

        /* Custom animation for modal */
        #logoutModal {
            animation: fadeIn 0.3s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>

<body class="bg-gray-100 font-sans">
    <div class="flex min-h-screen">

        <!-- Sidebar -->
        <button id="btn-side" onclick="navbarToggle(this)"><i class="fas fa-bars m-4"></i></button>
        <aside id="aside" class="w-64 bg-gray-900 text-white p-4">
            <div class="flex flex-col items-center">
                <img class="w-24 h-24 rounded-full object-cover" src="<?php echo $profile_picture; ?>" alt="Profile" />
                <h2 class="mt-4 font-semibold text-xl"><?php echo $full_name; ?></h2>
                <?php if ($basic_info['profile_visibility']) 
                        echo "<span class='active-button mt-1 bg-green-500 px-2 rounded-full'> Active </span>";
                    else echo "<span class='active-button mt-1 bg-red-500 px-2 rounded-full'> Close </span>";
                ?>
                <div class="text-yellow-500 mb-4 text-lg"> ★★★★★ </div>
            </div>
            <nav class="space-y-4">
                <a href="./dashboard.php" class="flex items-center gap-2"><i class="fa fa-chart-bar"></i> Dashboard</a>
                <a href="./products.php" class="flex items-center gap-2"><i
                        class="fa fa-box"></i>
                    Products</a>
                <a href="./orders.php" class="flex items-center gap-2"><i class="fa fa-shopping-cart"></i> Orders</a>
                <!-- <a href="./chat.php" class="flex items-center gap-2"><i class="fa-brands fa-rocketchat"></i> Live
                    Chat</a> -->
                <a href="./withdraw.php" class="flex items-center gap-2 bg-red-500 text-white p-2 rounded"><i class="fa fa-wallet"></i> Withdraw</a>
                <a href="./profile.php" class="flex items-center gap-2"><i class="fa-solid fa-user"></i> Profile</a>
                <a href="#" id="logoutBtn" class="flex items-center gap-2"><i class="fas fa-sign-out-alt"></i> Log out</a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-6 overflow-y-auto bg-gray-100">
            <!-- Header -->
            <div class="mb-6">
                <h1 class="text-2xl font-semibold text-gray-700">Withdraw Funds</h1>
                <p class="text-sm text-gray-500">Enter your withdrawal details to receive your funds.</p>
            </div>

            <!-- Withdraw Form -->
            <div class="bg-white p-6 rounded shadow-md w-full max-w-3xl">
                <form class="space-y-4">
                    <div>
                        <label class="block text-gray-600 mb-1">Withdraw Amount (PKR)</label>
                        <input type="number" placeholder="Enter amount"
                            class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-green-500" />
                    </div>

                    <div>
                        <label class="block text-gray-600 mb-1">Withdraw Method</label>
                        <select id="bankSelect" onchange="this.value == 'bank' ? bankName.classList.remove('hidden') : bankName.classList.add('hidden');"
                            class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-green-500">
                            <option value="">Select method</option>
                            <option value="jazzcash">JazzCash</option>
                            <option value="easypaisa">EasyPaisa</option>
                            <option value="bank">Bank Transfer</option>
                        </select>
                    </div>

                    <div class="hidden" id="bankName">
                        <label class="block text-gray-600 mb-1">Bank Name</label>
                        <input type="text" placeholder="e.g., Habib Bank Limited"
                            class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-green-500" />
                    </div>

                    <div>
                        <label class="block text-gray-600 mb-1">Account / IBAN / Mobile No</label>
                        <input type="text" placeholder="e.g., 03123456789 or PK00XXXX..."
                            class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-green-500" />
                    </div>

                    <div>
                        <label class="block text-gray-600 mb-1">Account Holder Name</label>
                        <input type="text" placeholder="Enter full name"
                            class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-green-500" />
                    </div>

                    <button type="submit"
                        class="bg-green-600 text-white px-6 py-2 rounded hover:bg-green-700 transition-all">Submit
                        Request</button>
                </form>
            </div>

            <!-- Info Section -->
            <div class="mt-6 max-w-3xl text-sm text-gray-600 bg-yellow-50 p-4 rounded border border-yellow-200">
                <h2 class="font-semibold text-yellow-800 mb-2"><i class="fa fa-info-circle mr-1"></i> Withdrawal Policy
                </h2>
                <ul class="list-disc list-inside space-y-1">
                    <li>Minimum withdrawal amount: <strong>500 PKR</strong></li>
                    <li>Withdrawals are processed within <strong>24 to 48 hours</strong></li>
                    <li>Ensure correct account details to avoid delays</li>
                    <li>No withdrawal fee for transactions above 1000 PKR</li>
                    <li>JazzCash and EasyPaisa withdrawals may reflect instantly</li>
                </ul>
            </div>
        </main>
    </div>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('form');
        
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const amount = parseFloat(form.querySelector('input[type="number"]').value);
            const method = form.querySelector('#bankSelect').value;
            const accountNumber = form.querySelector('input[type="text"][placeholder*="PK00"]').value;
            const accountHolder = form.querySelector('input[type="text"][placeholder*="full name"]').value;
            const bankName = method === 'bank' ? form.querySelector('#bankName input').value : '';
            
            if (!amount || amount < 500) {
                alert('Minimum withdrawal amount is 500 PKR');
                return;
            }
            
            if (!method) {
                alert('Please select a withdrawal method');
                return;
            }
            
            if (!accountNumber || !accountHolder) {
                alert('Please provide all required account details');
                return;
            }
            
            if (method === 'bank' && !bankName) {
                alert('Please provide bank name');
                return;
            }
            
            // Prepare data for AJAX
            const formData = new FormData();
            formData.append('amount', amount);
            formData.append('method', method === 'bank' ? 'bank_transfer' : method);
            formData.append('account_number', accountNumber);
            formData.append('account_holder', accountHolder);
            if (bankName) formData.append('bank_name', bankName);
            
            // Show loading state
            const submitBtn = form.querySelector('button[type="submit"]');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
            
            // Send AJAX request
            fetch('process_withdrawal.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Withdrawal request submitted successfully!');
                    form.reset();
                    // Optionally update balance display if you have one
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
            })
            .finally(() => {
                submitBtn.disabled = false;
                submitBtn.textContent = 'Submit Request';
            });
        });
        
        // Toggle bank name field based on selection
        document.getElementById('bankSelect').addEventListener('change', function() {
            const bankNameDiv = document.getElementById('bankName');
            if (this.value === 'bank') {
                bankNameDiv.classList.remove('hidden');
            } else {
                bankNameDiv.classList.add('hidden');
            }
        });
    });
</script>


    <div id="logoutModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
        <div class="bg-white p-6 rounded-lg shadow-xl max-w-md w-full mx-4">
            <h2 class="text-xl font-semibold mb-4 text-gray-800">Are you sure you want to logout?</h2>
            <p class="text-gray-600 mb-6">You'll need to sign in again to access your account.</p>
            <div class="flex justify-end space-x-3">
                <button id="cancelBtn"
                    class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition">
                    Cancel
                </button>
                <button id="confirmLogout"
                    class="px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600 transition">
                    Yes, Logout
                </button>
            </div>
        </div>
    </div>
    <script src="../script/logout.js"></script>

    <script src="../script/vendor_navbar.js"></script>
</body>

</html>