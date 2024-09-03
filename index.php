<?php
// เชื่อมต่อกับฐานข้อมูล
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "db_library";

// สร้างการเชื่อมต่อ
$conn = new mysqli($servername, $username, $password, $dbname);

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ตรวจสอบการค้นหา
$search_query = "";
if (isset($_POST['search'])) {
    $search_query = $_POST['search_query'];
    $search_query = $conn->real_escape_string($search_query);
    $sql = "SELECT * FROM tb_borrow_book 
            WHERE b_id LIKE '%$search_query%' 
            OR m_user LIKE '%$search_query%' 
            OR br_date_br LIKE '%$search_query%' 
            ORDER BY br_date_br DESC";
} else {
    $sql = "SELECT * FROM tb_borrow_book ORDER BY br_date_br DESC";
}

$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Borrowing Records</title>
</head>
<style>
    /* General Styles */
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #e8f0fe;
        margin: 0;
        padding: 0;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    h1 {
        color: #2c3e50;
        margin-top: 40px;
        font-size: 28px;
        text-align: center;
        text-transform: uppercase;
    }

    /* Search Form Styles */
    form {
        display: flex;
        justify-content: flex-end;
        width: 80%;
        margin-top: 20px;
        margin-bottom: 20px;
    }

    form input[type="text"] {
        width: 60%;
        padding: 10px;
        border: 2px solid #3498db;
        border-radius: 5px 0 0 5px;
        font-size: 16px;
    }

    form button {
        padding: 10px 20px;
        border: 2px solid #3498db;
        background-color: #3498db;
        color: white;
        border-radius: 0 5px 5px 0;
        font-size: 16px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    form button:hover {
        background-color: #2980b9;
    }

    /* Table Styles */
    table {
        width: 80%;
        margin: 20px 0;
        border-collapse: collapse;
        background-color: #fff;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        border-radius: 10px;
        overflow: hidden;
    }

    table th,
    table td {
        padding: 15px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }

    table thead {
        background-color: #2980b9;
        color: white;
    }

    table tbody tr:hover {
        background-color: #f1f1f1;
    }

    /* Button Group Styles */
    .button-group {
        width: 80%;
        display: flex;
        justify-content: space-between;
        margin: 20px 0;
    }

    .button-group .button {
        text-decoration: none;
        padding: 12px 25px;
        background-color: #e74c3c;
        color: white;
        border-radius: 50px;
        transition: background-color 0.3s ease, transform 0.3s ease;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .button-group .button:hover {
        background-color: #c0392b;
        transform: translateY(-3px);
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        table {
            width: 95%;
        }

        form {
            flex-direction: column;
            align-items: center;
        }

        form input[type="text"] {
            width: 100%;
            margin-bottom: 10px;
        }

        .button-group {
            flex-direction: column;
            align-items: center;
        }

        .button-group .button {
            width: 100%;
            margin-bottom: 10px;
            text-align: center;
        }
    }
</style>

<body>
    <h1>ข้อมูลการยืม-คืนหนังสือ</h1>

    <form method="POST" action="">
        <input type="text" name="search_query" placeholder="ค้นหาตามชื่อหนังสือ หรือชื่อผู้ยืม-คืน" value="<?php echo htmlspecialchars($search_query); ?>">
        <button type="submit" name="search">ค้นหา</button>
    </form>

    <table>
        <thead>
            <tr>
                <th>วันที่ยืม</th>
                <th>วันที่คืน</th>
                <th>รหัสหนังสือ</th>
                <th>ชื่อผู้ใช้</th>
                <th>ค่าปรับ</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row["br_date_br"] . "</td>";
                    echo "<td>" . ($row["br_date_rt"] == '0000-00-00' ? 'ยังไม่คืน' : $row["br_date_rt"]) . "</td>";
                    echo "<td>" . $row["b_id"] . "</td>";
                    echo "<td>" . $row["m_user"] . "</td>";
                    echo "<td>" . $row["br_fine"] . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='5'>ไม่มีข้อมูล</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <div class="button-group">
        <a href="manage_borrow_return.php" class="button">จัดการข้อมูลการยืม-คืน</a>
        <a href="statistics.php" class="button">ข้อมูลสถิติ</a>
    </div>
</body>

</html>

<?php
// ปิดการเชื่อมต่อ
$conn->close();
?>