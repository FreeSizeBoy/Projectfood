<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ประวัติการสั่งซื้อ</title>
    <link rel="stylesheet" href="css/order_history.css">
</head>
<body>
    <div class="container">
        <h1>ประวัติการสั่งซื้อ</h1>
        <table>
            <thead>
                <tr>
                    <th>เลขที่คำสั่งซื้อ</th>
                    <th>วันที่</th>
                    <th>สถานะ</th>
                    <th>ยอดรวม</th>
                    <th>รายละเอียด</th>
                </tr>
            </thead>
            <tbody id="order-list">
                <!-- ข้อมูลคำสั่งซื้อลงที่นี่ -->
            </tbody>
        </table>
        <button id="back-button">กลับ</button>
    </div>

    <!-- Modal -->
    <div id="orderModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>รายละเอียดคำสั่งซื้อ</h2>
            <div id="order-details"></div>
        </div>
    </div>

    <script>
        const userId = <?= $_SESSION["id"] ?>;
        const ROOT_URL = '<?= ROOT_URL ?>';

        function fetchOrders() {
            fetch(`${ROOT_URL}/api/users/${userId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.status) {
                        const orders = data.data.orders;
                        const orderList = document.getElementById("order-list");
                        orderList.innerHTML = ""; // Clear existing orders

                        // Sort orders by createdAt date (newest first)
                        const sortedOrders = Object.values(orders).sort((a, b) => new Date(b.createdAt) - new Date(a.createdAt));

                        for (const order of sortedOrders) {
                            const tr = document.createElement("tr");

                            const orderIdTd = document.createElement("td");
                            orderIdTd.textContent = order.order_id;
                            tr.appendChild(orderIdTd);

                            const dateTd = document.createElement("td");
                            const date = new Date(order.createdAt);
                            dateTd.textContent = date.toLocaleDateString("th-TH");
                            tr.appendChild(dateTd);

                            const statusTd = document.createElement("td");
                            statusTd.textContent = order.status;
                            tr.appendChild(statusTd);

                            const totalPriceTd = document.createElement("td");
                            totalPriceTd.textContent = order.total_price;
                            tr.appendChild(totalPriceTd);

                            const detailsTd = document.createElement("td");
                            const detailsButton = document.createElement("button");
                            detailsButton.textContent = "ดูรายละเอียด";
                            detailsButton.addEventListener("click", function(){
                                openModal(order);
                            });
                            detailsTd.appendChild(detailsButton);
                            tr.appendChild(detailsTd);

                            orderList.appendChild(tr);
                        }
                    } else {
                        alert("ไม่พบข้อมูลคำสั่งซื้อ");
                    }
                })
                .catch(error => {
                    console.error("เกิดข้อผิดพลาด:", error);
                });
        }

        document.getElementById("back-button").addEventListener("click", function(){
            window.location.href = 'menu';
        });

        function openModal(order) {
            let detailsHtml = `
                <p>เลขที่คำสั่งซื้อ: ${order.order_id}</p>
                <p>วันที่: ${new Date(order.createdAt).toLocaleDateString("th-TH")}</p>
                <p>สถานะ: ${order.status}</p>
                <p>ยอดรวม: ${order.total_price}</p>
                <h3>รายละเอียดเมนู:</h3>
                <ul>
            `;

            order.details.forEach(detail => {
                detailsHtml += `
                    <li>
                        <img src="<?= FOOD_UPLOAD_DIR ?>${detail.menu_image}" alt="${detail.menuname}" style="width: 100px; height: 100px; object-fit: cover;">
                        <p>ชื่อเมนู: ${detail.menuname}</p>
                        <p>ราคา: ${detail.detail_price} บาท</p>
                        <p>จำนวน: ${detail.amount}</p>
                        <p>หมายเหตุ: ${detail.note ? detail.note : 'ไม่มี'}</p>
                        <p>พิเศษ: ${detail.extra}</p>
                    </li>
                `;
            });

            detailsHtml += '</ul>';

            document.getElementById("order-details").innerHTML = detailsHtml;
            document.getElementById("orderModal").style.display = "block";
        }

        document.getElementsByClassName("close")[0].onclick = function() {
            document.getElementById("orderModal").style.display = "none";
        }

        window.onclick = function(event) {
            if (event.target == document.getElementById("orderModal")) {
                document.getElementById("orderModal").style.display = "none";
            }
        }

        // Fetch orders every 5 seconds
        fetchOrders(); // Initial fetch
        setInterval(fetchOrders, 5000); // Fetch every 5 seconds
    </script>

    <style>
        /* Modal styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0,0,0);
            background-color: rgba(0,0,0,0.4);
        }

        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 600px;
            border-radius: 10px;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
</body>
</html>
