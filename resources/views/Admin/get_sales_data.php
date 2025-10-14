<?php
include '../mydatabase/conn.php';

$data = [];
$labels = [];

$sql = "
SELECT 
    months.month_name AS month,
    COALESCE(COUNT(o.order_date), 0) AS count
FROM
    (SELECT 
        DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL seq MONTH), '%Y-%m') AS month,
        DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL seq MONTH), '%M') AS month_name
    FROM
        (SELECT 0 AS seq UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5) AS seq_table
    ) AS months
LEFT JOIN orders o ON DATE_FORMAT(o.order_date, '%Y-%m') = months.month
GROUP BY months.month, months.month_name
ORDER BY months.month ASC
";

$result = $conn->query($sql);

while ($row = $result->fetch_assoc()) {
    $labels[] = $row['month'];
    $data[] = $row['count'];
}

echo json_encode(['labels' => $labels, 'data' => $data]);
?>
