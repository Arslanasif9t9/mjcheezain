<?php
require_once '../mydatabase/conn.php'; // Your database connection file

// Function to get all addresses for a customer
function getCustomerAddresses($user_id) {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM customer_addresses WHERE user_id = ? ORDER BY is_default DESC, address_type ASC");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $addresses = [];
    while ($row = $result->fetch_assoc()) {
        $addresses[] = $row;
    }
    
    $stmt->close();
    return $addresses;
}

// Function to add a new address
function addCustomerAddress($user_id, $data) {
    global $conn;
    
    // If setting as default, first unset any existing default
    if ($data['is_default']) {
        $stmt = $conn->prepare("UPDATE customer_addresses SET is_default = FALSE WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->close();
    }
    
    $stmt = $conn->prepare("INSERT INTO customer_addresses 
        (user_id, address_type, full_name, phone, address_line1, address_line2, city, state, zip_code, country, is_default) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    
    $is_default = $data['is_default'] ? 1 : 0;
    $stmt->bind_param("isssssssssi", 
        $user_id,
        $data['address_type'],
        $data['full_name'],
        $data['phone'],
        $data['address_line1'],
        $data['address_line2'],
        $data['city'],
        $data['state'],
        $data['zip_code'],
        $data['country'],
        $is_default
    );
    
    $result = $stmt->execute();
    $stmt->close();
    return $result;
}

// Function to update an address
function updateCustomerAddress($address_id, $user_id, $data) {
    global $conn;
    
    // If setting as default, first unset any existing default
    if ($data['is_default']) {
        $stmt = $conn->prepare("UPDATE customer_addresses SET is_default = FALSE WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->close();
    }
    
    $stmt = $conn->prepare("UPDATE customer_addresses SET 
        address_type = ?,
        full_name = ?,
        phone = ?,
        address_line1 = ?,
        address_line2 = ?,
        city = ?,
        state = ?,
        zip_code = ?,
        country = ?,
        is_default = ?
        WHERE id = ? AND user_id = ?");
    
    $is_default = $data['is_default'] ? 1 : 0;
    $stmt->bind_param("sssssssssiii", 
        $data['address_type'],
        $data['full_name'],
        $data['phone'],
        $data['address_line1'],
        $data['address_line2'],
        $data['city'],
        $data['state'],
        $data['zip_code'],
        $data['country'],
        $is_default,
        $address_id,
        $user_id
    );
    
    $result = $stmt->execute();
    $stmt->close();
    return $result;
}

// Function to delete an address
function deleteCustomerAddress($address_id, $user_id) {
    global $conn;
    $stmt = $conn->prepare("DELETE FROM customer_addresses WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $address_id, $user_id);
    $result = $stmt->execute();
    $stmt->close();
    return $result;
}

// Function to set an address as default
function setDefaultAddress($address_id, $user_id) {
    global $conn;
    
    // First unset any existing default
    $stmt = $conn->prepare("UPDATE customer_addresses SET is_default = FALSE WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->close();
    
    // Then set the new default
    $stmt = $conn->prepare("UPDATE customer_addresses SET is_default = TRUE WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $address_id, $user_id);
    $result = $stmt->execute();
    $stmt->close();
    return $result;
}
?>