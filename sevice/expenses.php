<?php
function getExpensesById($conn, $id)
{
    $sql = "SELECT * FROM expenses WHERE id = $id";
    $result = $conn->query($sql);

    if ($result->num_rows == 0) {
        return null;
    }

    return $result->fetch_assoc();
}

function getExpenses($conn, $shopId = null)
{
    // Start building the SQL query
    $sql = "SELECT * FROM expenses WHERE (1 = 1)";

    // If $shopId is specified, add a condition to filter by shop_id
    if ($shopId !== null) {
        // Check if $shopId is a valid value
        if (!is_numeric($shopId)) {
            throw new InvalidArgumentException('Invalid shop ID');
        }
        $sql .= " AND shop_id = ?";
    }

    // Prepare the SQL statement
    $stmt = $conn->prepare($sql);

    // Bind parameters if $shopId is provided
    if ($shopId !== null) {
        $stmt->bind_param('i', $shopId);
    }

    $stmt->execute();

    $result = $stmt->get_result();
    $expenses = [];
    
    while ($row = $result->fetch_assoc()) {
        $expenses[] = $row;
    }

    return $expenses;
}




function deleteExpenses($conn, $id)
{
    $sql = "DELETE FROM expenses WHERE id = $id";
    $conn->query($sql);
}

function createExpenses($conn, $shop_id, $note , $priceout)
{
    $sql = "INSERT INTO expenses (shop_id, note, priceout) VALUES ('$shop_id', '$note', '$priceout')";

    if ($conn->query($sql) === TRUE) {
        return getExpensesById($conn, $conn->insert_id);
    }

    return null;
}

function updateExpenses($conn, $id, $shop_id, $note , $priceout)
{
    $sql = "UPDATE expenses SET shop_id = '$shop_id', note = '$note', priceout = '$priceout' WHERE id = $id";

    $conn->query($sql);

    return getExpensesById($conn, $id);
}
