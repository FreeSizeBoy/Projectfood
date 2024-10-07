<?php
function getExpensesById($conn, $id)
{
    $sql = "SELECT * FROM Expenses WHERE id = $id";
    $result = $conn->query($sql);

    if ($result->num_rows == 0) {
        return null;
    }

    return $result->fetch_assoc();
}

function getExpenses($conn, $page = 1, $limit = 10, $shopId = null)
{
    $offset = ($page - 1) * $limit;

    // Start building the SQL query
    $sql = "SELECT * FROM Expenses WHERE (1 = 1)";

    // If $shopId is specified, add a condition to filter by shop_id
    if ($shopId !== null) {
        // Check if $shopId is a valid value
        if (!is_numeric($shopId)) {
            throw new InvalidArgumentException('Invalid shop ID');
        }
        $sql .= " AND shop_id = ?";
    }

    // If filter is provided, append to the SQL query

    $sql .= " LIMIT ? OFFSET ?";

    // Prepare the SQL statement
    $stmt = $conn->prepare($sql);

    // Create parameters for bind_param

    // Determine the parameter types and bind them accordingly
    if  ($shopId !== null) {
        // If only $shopId is specified
        $stmt->bind_param('iii',  $shopId, $limit, $offset);
    }  else {
        // If neither $shopId nor $filter is specified
        $stmt->bind_param('ii',  $limit, $offset);
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
    $sql = "DELETE FROM Expenses WHERE id = $id";
    $conn->query($sql);
}

function createExpenses($conn, $shop_id, $note , $priceout)
{
    $sql = "INSERT INTO Expenses (shop_id, note, priceout) VALUES ('$shop_id', '$note', '$priceout')";

    if ($conn->query($sql) === TRUE) {
        return getExpensesById($conn, $conn->insert_id);
    }

    return null;
}

function updateExpenses($conn, $id, $shop_id, $note , $priceout)
{
    $sql = "UPDATE Expenses SET shop_id = '$shop_id', note = '$note', priceout = '$priceout' WHERE id = $id";

    $conn->query($sql);

    return getExpensesById($conn, $id);
}
