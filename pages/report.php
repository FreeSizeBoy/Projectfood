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
    <div class="sidebar">
        <h2>Dashboard</h2>
        <a href="profile">แก้ไขโปรไฟล์ Admin</a>
        <ul>
            <li><a href="dashboard">หน้าหลัก</a></li>
            <li><a href="manage">จัดการสมาชิก</a></li>
            <li><a href="food">จัดการเมนู</a></li>
            <li><a href="order">คำสั่งซื้อ</a></li>
            <li><a href="report">รายงานยอดขาย</a></li>
            <li><a href="setting">จัดการร้านอาหาร</a></li>
            <li><a href="dashboard_m">เปลี่ยนไปยังหน้าโทรศัพท์</a></li>
            <li><a href="<?= ROOT_URL ?>/api/logout">ออกจากระบบ</a></li>
        </ul>
    </div>
    <div class="main-content">
        <header>
            <h1>ยินดีต้อนรับสู่ Dashboard ของร้านอาหาร</h1>
        </header>
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

        const salesData = {
            labels: ['มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษาคม', 'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'],
            datasets: [{
                label: 'รายรับ',
                data: [300, 500, 400, 700, 600, 900, 800, 900, 500, 400, 700, 300],
                backgroundColor: 'rgba(75, 192, 192, 0.6)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }, {
                label: 'รายจ่าย',
                data: [150, 200, 250, 300, 200, 350, 300, 600, 600, 600, 600, 600],
                backgroundColor: 'rgba(255, 99, 132, 0.6)',
                borderColor: 'rgba(255, 99, 132, 1)',
                borderWidth: 1
            }]
        };

        const salesChart = new Chart(ctx, {
            type: 'bar',
            data: salesData,
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
            updateChart(
                ['มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษาคม', 'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'],
                [300, 500, 400, 700, 600, 900, 800, 900, 500, 400, 700, 300],
                [150, 200, 250, 300, 200, 350, 300, 600, 600, 600, 600, 600]
            );
            updateTable('รายเดือน', [300, 500, 400, 700, 600, 900, 800, 900, 500, 400, 700, 300], [150, 200, 250, 300, 200, 350, 300, 600, 600, 600, 600, 600]);
        });

        document.getElementById('yearBtn').addEventListener('click', function() {
            updateChart(['2023'], [5000], [2000]);
            updateTable('รายปี', [5000], [2000]);
        });

        document.getElementById('dayBtn').addEventListener('click', function() {
            updateChart(
                ['จันทร์', 'อังคาร', 'พุธ', 'พฤหัส', 'ศุกร์', 'เสาร์', 'อาทิตย์'],
                [100, 200, 150, 300, 250, 400, 350],
                [50, 100, 75, 150, 125, 200, 175]
            );
            updateTable('รายวัน', [100, 200, 150, 300, 250, 400, 350], [50, 100, 75, 150, 125, 200, 175]);
        });

        function updateChart(labels, incomeData, expenseData) {
            salesChart.data.labels = labels;
            salesChart.data.datasets[0].data = incomeData;
            salesChart.data.datasets[1].data = expenseData;
            salesChart.update();
        }

        function updateTable(period, incomeData, expenseData) {
            const reportData = document.getElementById('reportData');
            reportData.innerHTML = '';

            incomeData.forEach((income, index) => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${period}</td>
                    <td>รายรับ</td>
                    <td>฿${income}</td>
                `;
                reportData.appendChild(row);

                if (index < expenseData.length) { // Ensure there is corresponding expense data
                    const expenseRow = document.createElement('tr');
                    expenseRow.innerHTML = `
                        <td>${period}</td>
                        <td>รายจ่าย</td>
                        <td>฿${expenseData[index]}</td>
                    `;
                    reportData.appendChild(expenseRow);
                }
            });
        }
    </script>
</body>

</html>
