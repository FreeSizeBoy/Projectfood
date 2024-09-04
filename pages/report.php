<?php

require_once "database.php";

// รับค่า shop_id จาก session
$shopId = $_SESSION['shop_id'];

// เริ่มต้นค่าตัวแปรยอดขายและรายจ่าย
$totalSales = $dailySales = $monthlySales = 0;
$totalExpenses = $dailyExpenses = $monthlyExpenses = 0;

// Queries สำหรับรายรับและรายจ่าย
// ยอดขายรวม
$totalSalesQuery = "SELECT SUM(total_price) AS total FROM orders WHERE status = 'complete' AND shop_id = ?";
$dailySalesQuery = "SELECT SUM(total_price) AS total FROM orders WHERE status = 'complete' AND shop_id = ? AND DATE(createdAt) = CURDATE()";
$monthlySalesQuery = "SELECT SUM(total_price) AS total FROM orders WHERE status = 'complete' AND shop_id = ? AND MONTH(createdAt) = MONTH(CURDATE()) AND YEAR(createdAt) = YEAR(CURDATE())";

// รายจ่ายรวม
$totalExpensesQuery = "SELECT SUM(priceout) AS total FROM Expenses WHERE shop_id = ?";
$dailyExpensesQuery = "SELECT SUM(priceout) AS total FROM Expenses WHERE shop_id = ? AND DATE(createdAt) = CURDATE()";
$monthlyExpensesQuery = "SELECT SUM(priceout) AS total FROM Expenses WHERE shop_id = ? AND MONTH(createdAt) = MONTH(CURDATE()) AND YEAR(createdAt) = YEAR(CURDATE())";

// ฟังก์ชันสำหรับประมวลผลคำสั่ง SQL และส่งค่า
function getAmount($conn, $query, $shopId) {
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $shopId);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    return $row ? $row['total'] : 0;
}

// คำนวณยอดขายและรายจ่าย
$totalSales = getAmount($conn, $totalSalesQuery, $shopId);
$dailySales = getAmount($conn, $dailySalesQuery, $shopId);
$monthlySales = getAmount($conn, $monthlySalesQuery, $shopId);

$totalExpenses = getAmount($conn, $totalExpensesQuery, $shopId);
$dailyExpenses = getAmount($conn, $dailyExpensesQuery, $shopId);
$monthlyExpenses = getAmount($conn, $monthlyExpensesQuery, $shopId);

?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - ร้านอาหาร</title>
    <link rel="stylesheet" href="css/dashboard.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <?php include_once "component/dashborad.php"; ?>

    <div class="main-content">
        <header>
            <h1>ยินดีต้อนรับสู่ Dashboard ของร้านอาหาร</h1>
        </header>
        <!-- ส่วนแสดงผลข้อมูล -->
        <section class="sales-summary">
            <!-- ยอดขายรวม -->
            <div class="summary-card">
                <h3>ยอดขายรวม</h3>
                <p>฿<?php echo number_format($totalSales, 2); ?></p>
            </div>
            <!-- ยอดขายรายวัน -->
            <div class="summary-card">
                <h3>ยอดขายรายวัน</h3>
                <p>฿<?php echo number_format($dailySales, 2); ?></p>
            </div>
            <!-- ยอดขายรายเดือน -->
            <div class="summary-card">
                <h3>ยอดขายรายเดือน</h3>
                <p>฿<?php echo number_format($monthlySales, 2); ?></p>
            </div>
        </section>

        <section class="sales-summary">
            <!-- รายจ่ายรวม -->
            <div class="summary-card">
                <h3>รายจ่ายรวม</h3>
                <p>฿<?php echo number_format($totalExpenses, 2); ?></p>
            </div>
            <!-- รายจ่ายรายวัน -->
            <div class="summary-card">
                <h3>รายจ่ายรายวัน</h3>
                <p>฿<?php echo number_format($dailyExpenses, 2); ?></p>
            </div>
            <!-- รายจ่ายรายเดือน -->
            <div class="summary-card">
                <h3>รายจ่ายรายเดือน</h3>
                <p>฿<?php echo number_format($monthlyExpenses, 2); ?></p>
            </div>
        </section>
        <section class="cards">
            <div class="card">
                <h3>รายงานยอดขาย (ภาพรวม)</h3>
                <div class="chart-container">
                    <canvas id="salesChart"></canvas>
                </div>
                <div class="chart-controls">
                    <button id="monthBtn">รายเดือน</button>
                    <button id="yearBtn">รายปี</button>
                    <button id="dayBtn">รายวัน</button>
                </div>
            </div>
            <div class="card">
                <h3>ตารางรายรับ-รายจ่าย</h3>
                <table class="report-table">
                    <thead>
                        <tr>
                            <th>ช่วงเวลา</th>
                            <th>ประเภท</th>
                            <th>จำนวนเงิน</th>
                        </tr>
                    </thead>
                    <tbody id="reportData">
                        <!-- Data will be populated here -->
                    </tbody>
                </table>
            </div>
        </section>
    </div>

    <script>
        const ctx = document.getElementById('salesChart').getContext('2d');

        const salesChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: [],
                datasets: [{
                    label: 'รายรับ',
                    data: [],
                    backgroundColor: 'rgba(75, 192, 192, 0.6)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }, {
                    label: 'รายจ่าย',
                    data: [],
                    backgroundColor: 'rgba(255, 99, 132, 0.6)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                return tooltipItem.label + ': ฿' + tooltipItem.raw;
                            }
                        }
                    }
                }
            }
        });

        document.getElementById('monthBtn').addEventListener('click', function() {
            fetchData('month');
        });

        document.getElementById('yearBtn').addEventListener('click', function() {
            fetchData('year');
        });

        document.getElementById('dayBtn').addEventListener('click', function() {
            fetchData('day');
        });

        function fetchData(period) {
        const shopId = `<?= $_SESSION['shop_id'] ?>`; // Ensure correct shop_id is used
        fetch(`<?= ROOT_URL ?>/api/revenue?year=2024&period=${period}&shop_id=${shopId}`)
            .then(response => response.json())
            .then(data => {
                updateChart(data.labels, data.income, data.expenses);
                updateTable(data.labels, data.income, data.expenses);
            })
            .catch(error => console.error('Error:', error));
    }

    function updateChart(labels, incomeData, expenseData) {
        salesChart.data.labels = labels;
        salesChart.data.datasets[0].data = incomeData;
        salesChart.data.datasets[1].data = expenseData;
        salesChart.update();
    }

    function updateTable(labels, incomeData, expenseData) {
        const reportData = document.getElementById('reportData');
        reportData.innerHTML = '';

        labels.forEach((label, index) => {
            const income = incomeData[index] ? incomeData[index] : 0;
            const expense = expenseData[index] ? expenseData[index] : 0;

            const incomeRow = document.createElement('tr');
            incomeRow.innerHTML = `
                <td>${label}</td>
                <td>รายรับ</td>
                <td>฿${income.toLocaleString()}</td>
            `;
            reportData.appendChild(incomeRow);

            const expenseRow = document.createElement('tr');
            expenseRow.innerHTML = `
                <td>${label}</td>
                <td>รายจ่าย</td>
                <td>฿${expense.toLocaleString()}</td>
            `;
            reportData.appendChild(expenseRow);
        });
    }
    </script>
</body>
</html>
