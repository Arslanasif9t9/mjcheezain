<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin Dashboard</title>
  <!-- Tailwind CSS  -->
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <script src="https://cdn.tailwindcss.com"></script>
  <!-- font-awesome  -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <!-- Google font  -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link
    href="https://fonts.googleapis.com/css2?family=PT+Serif:ital,wght@0,400;0,700;1,400;1,700&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap"
    rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/css/style.css') }}">
  <style>
    .scrollbar-hide::-webkit-scrollbar {
      display: none;
    }
  </style>
</head>

<body class="bg-gray-100 text-gray-800">
  <div class="flex min-h-screen">
    <!-- Sidebar -->
    <x-admin.sidebar />

    <!-- Main Content -->
    <main class="flex-1 p-6 space-y-6">
      <!-- Top Cards -->
      <div class="flex">
        <div class="w-max bg-green-500 text-white px-4 py-2 mx-2 rounded font-bold">Earnings</div>
        <div class="w-max bg-green-500 text-white px-4 py-2 mx-2 rounded font-bold">Earnings</div>
        <div class="w-max bg-green-500 text-white px-4 py-2 mx-2 rounded font-bold">Earnings</div>
      </div>
      <div class="flex flex-wrap gap-4 top-cards">
          <div class="bg-blue-300 text-white shadow rounded p-4 w-[23%]">
            <div class="text-2xl font-bold">
               {{ $active_users }} <small class="text-sm font-normal"> ({{ $total_users }}) </small>
            </div>
            <div class="text-right row-span-2 text-3xl"><i class="fa-solid fa-users"></i></div>
            <div>Total Users</div>
          </div>
          <div class="bg-green-500 text-white shadow rounded p-4 w-[23%]">
            <div class="text-2xl font-bold">
              {{ $orders }}
            </div>
            <div class="text-right row-span-2 text-3xl"><i class="fa-solid fa-truck"></i></div>
            <div>Total Orders</div>
          </div>
          <div class="bg-red-500 text-white shadow rounded p-4 w-[23%]">
            <div class="text-2xl font-bold">
              0
            </div>
            <div class="text-right row-span-2 text-3xl"><i class="fa-solid fa-rotate-left"></i></div>
            <div>Total Return Orders</div>
          </div>
          <div class="bg-orange-500 text-white shadow rounded p-4 w-[23%]">
            <div class="text-2xl font-bold">
              0
            </div>
            <div class="text-right row-span-2 text-3xl font-bold"><i class="fa-solid fa-xmark"></i></div>
            <div>Total Cancelled Orders</div>
          </div>
          <div class="bg-orange-500 text-white shadow rounded p-4 w-[23%]" style="grid-template-columns: 1fr 2fr;">
            <div class="row-span-2 text-3xl font-bold"><i class="fa-solid fa-users"></i></div>
            <div class="text-right text-2xl font-bold">
              {{ $active_vendors }} <small class="text-sm font-normal"> ({{ $vendors }}) </small>
            </div>
            <div class="text-right">Total Vendors</div>
          </div>
          <div class="bg-red-500 text-white shadow rounded p-4 w-[23%]" style="grid-template-columns: 1fr 5fr;">
            <div class="row-span-2 text-3xl font-bold"><i class="fa-solid fa-file"></i></div>
            <div class="text-right text-2xl font-bold">
                  0 <br>
                  <span class='text-sm font-normal'> V(0) </span>
                  <span class='text-sm font-normal'> PR(0) </span>
                  <span class='text-sm font-normal'> PM(0) </span>
                  <span class='text-sm font-normal'> W(0) </span>
            </div>
            <div class="text-right">Total requests</div>
          </div>
          <div class="bg-green-500 text-white shadow rounded p-4 w-[23%]" style="grid-template-columns: 1fr 5fr;">
            <div class="row-span-2 text-3xl font-bold"><i class="fa-solid fa-file"></i></div>
            <div class="text-right text-2xl font-bold">
              0
            </div>
            <div class="text-right">Total value of sales</div>
          </div>
          <div class="bg-blue-300 text-white shadow rounded p-4 w-[23%]" style="grid-template-columns: 1fr 3fr;">
            <div class="row-span-2 text-3xl font-bold"><i class="fa-solid fa-file"></i></div>
            <div class="text-right text-2xl font-bold">0</div>
            <div class="text-right">Your Balance</div>
          </div>
      </div>

      <!-- Statistics and Table -->
      <div class="grid grid-cols-1 lg:grid-cols-[2fr_3fr] gap-6">
        <div class="bg-white shadow rounded p-4 w-full  mx-auto">
          <div class="font-semibold mb-4 text-center text-xl w-full">Statistics - Last 6 Months Sales</div>
          <canvas id="salesPieChart" class="w-full"></canvas>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
          document.addEventListener('DOMContentLoaded', function () {
            fetch('/admin/sales-data') // replace with your PHP file path
              .then(response => response.json())
              .then(chartData => {
                const ctx = document.getElementById('salesPieChart').getContext('2d');
                new Chart(ctx, {
                  type: 'pie',
                  data: {
                    labels: chartData.labels,
                    datasets: [{
                      label: 'Orders',
                      data: chartData.data,
                      backgroundColor: [
                        '#3b82f6', // August - Blue
                        '#f97316', // September - Orange
                        '#ef4444', // October - Red
                        '#67e8f9', // November - Teal
                        '#4ade80', // December - Green
                        '#eb8bfaff'  // Extra fallback
                      ],
                      borderColor: '#ffffff',
                      borderWidth: 2
                    }]
                  },
                  options: {
                    responsive: false,
                    plugins: {
                      legend: {
                        position: 'top'
                      }
                    }
                  }
                });
              });
          });
        </script>

        {{-- <div class="bg-white shadow rounded p-4 overflow-auto">
          <div class="font-semibold mb-2">Recent Orders</div>
          <?php 
            // Query to get top 5 recent orders with vendor and customer info
            $sql = "SELECT 
                        o.id,
                        v.store_name AS vendor_name,
                        o.id AS order_number,
                        p.name AS product_name,
                        CONCAT(c.first_name, ' ', c.last_name) AS customer_name,
                        o.total_amount AS order_total,
                        o.order_date,
                        o.fulfillment
                    FROM 
                        orders o
                    JOIN 
                        vendor_basic_info v ON o.vendor_id = v.user_id
                    JOIN 
                        vendor_products p ON o.product_id = p.id
                    JOIN 
                        customer_profile c ON o.user_id = c.user_id
                    ORDER BY 
                        o.order_date DESC
                    LIMIT 5";

            $result = $conn->query($sql);
          ?>
          <style>
            .ellipsis {
              white-space: nowrap;
              overflow: hidden;
              text-overflow: ellipsis;
              max-width: 150px;
              display: inline-block;
            }
            
            @media (max-width: 768px) {
                table {
                    display: block;
                    overflow-x: auto;
                    white-space: nowrap;
                }
            }
          </style>
          <table class="min-w-full text-sm" style="font-size: 15px">
                <thead>
                    <tr class="text-left border-b">
                        <th class="py-2">#</th>
                        <th class="py-2">Vendor name</th>
                        <th class="py-2">Order number</th>
                        <th class="py-2">Product name</th>
                        <th class="py-2">Customer</th>
                        <th class="py-2">Order total</th>
                        <th class="py-2">Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        $counter = 1;
                        while($row = $result->fetch_assoc()) {
                            echo '<tr class="border-b hover:bg-gray-50">';
                            echo '<td class="py-3">' . $counter . '</td>';
                            echo '<td class="py-3"><span class="ellipsis" title="' . htmlspecialchars($row['vendor_name']) . '">' . htmlspecialchars($row['vendor_name']) . '</span></td>';
                            echo '<td class="py-3">' . htmlspecialchars($row['order_number']) . '</td>';
                            echo '<td class="py-3"><span class="ellipsis" title="' . htmlspecialchars($row['product_name']) . '">' . htmlspecialchars($row['product_name']) . '</span></td>';
                            echo '<td class="py-3"><span class="ellipsis" title="' . htmlspecialchars($row['customer_name']) . '">' . htmlspecialchars($row['customer_name']) . '</span></td>';
                            echo '<td class="py-3">$' . number_format($row['order_total'], 2) . '</td>';
                            echo '<td class="py-3">' . date('d F Y', strtotime($row['order_date'])) . '</td>';
                            echo '</tr>';
                            $counter++;
                        }
                    } else {
                        echo '<tr><td colspan="7" class="py-4 text-center">No orders found</td></tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div> --}}
        <!-- Charts -->
      {{-- <div class="grid grid-cols-1 md:grid-cols-2 gap-6"> --}}
        <div class="bg-white shadow rounded p-4">
          <div class="font-semibold mb-2">Monthly Orders - Last 6 Months Sales</div>
            <canvas id="ordersChart" height="100"></canvas>

            <script>
              // Fetch the data from PHP
              fetch('/admin/sales-data')
                .then(response => response.json())
                .then(result => {
                  const ctx = document.getElementById('ordersChart').getContext('2d');

                  new Chart(ctx, {
                    type: 'bar',
                    data: {
                      labels: result.labels,
                      datasets: [{
                        label: 'Orders',
                        data: result.data,
                        backgroundColor: 'rgba(59, 130, 246, 0.7)', // Tailwind blue
                        borderRadius: 4,
                        barThickness: 40
                      }]
                    },
                    options: {
                      responsive: true,
                      plugins: {
                        legend: {
                          display: true,
                          position: 'top'
                        }
                      },
                      scales: {
                        y: {
                          beginAtZero: true,
                          ticks: {
                            stepSize: 5
                          }
                        }
                      }
                    }
                  });
                })
                .catch(error => {
                  console.error('Error loading chart data:', error);
                });
            </script>
        {{-- </div> --}}
        {{-- <div class="bg-white shadow rounded p-4">
          <div class="font-semibold mb-2">Monthly Users - Last 6 Months Sales</div>
          <canvas id="userAreaChart"></canvas>
          <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
          <script>
            document.addEventListener('DOMContentLoaded', function () {
              fetch('get_sales_data.php')
                .then(response => response.json())
                .then(chartData => {
                  console.log(chartData);
                  const ctx = document.getElementById('userAreaChart').getContext('2d');
                  new Chart(ctx, {
                    type: 'line', // area chart is a line chart with "fill"
                    data: {
                      labels: chartData.labels,
                      // labels: ["Feb","Mar","Apr","May","Jun","Jul"],
                      datasets: [{
                        label: 'Users',
                        data: chartData.data,
                        backgroundColor: 'rgba(59, 130, 246, 0.5)', // blue with opacity
                        borderColor: '#3b82f6', // solid blue border
                        fill: true, // this makes it an area chart
                        tension: 0.4, // smooth curve
                        pointBackgroundColor: '#3b82f6',
                        pointRadius: 5
                      }]
                    },
                    options: {
                      responsive: true,
                      plugins: {
                        legend: {
                          position: 'top'
                        },
                        tooltip: {
                          callbacks: {
                            label: function(context) {
                              return `Users: ${context.raw}`;
                            }
                          }
                        }
                      },
                      scales: {
                        y: {
                          beginAtZero: true,
                          ticks: {
                            stepSize: 2
                          },
                          title: {
                            display: true,
                            text: 'Users'
                          }
                        },
                        x: {
                          title: {
                            display: true,
                            text: 'Month'
                          }
                        }
                      }
                    }
                  });
                });
            });
          </script>
        </div> --}}
      </div>
      </div>

      

      <footer class="text-center text-sm text-gray-500 py-4">&copy;Copyright 2020-2021.</footer>
    </main>
  </div>
</body>

</html>