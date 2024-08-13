<?php
// Database connection
include 'db.php';
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

// Get the incoming JSON data
$data = json_decode(file_get_contents('php://input'), true);

// Check if required fields are present and not null
if (empty($data['customer_id']) || empty($data['email']) || empty($data['first_name']) || empty($data['last_name']) || 
    empty($data['phone']) || empty($data['preferred_bank']) || empty($data['account_number']) || 
    empty($data['bank_name']) || empty($data['company_id']) || empty($data['role'])) {
    http_response_code(400);
    echo json_encode(['error' => 'All fields are required and cannot be null']);
    exit;
}

// Prepare the SQL statement
$stmt = $conn->prepare("INSERT INTO bankaccounts (customer_id, email, first_name, last_name, phone, preferred_bank, account_number, bank_name, company_id, role) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

// Bind the parameters to the SQL query
$stmt->bind_param(
    "ssssssssss", 
    $data['customer_id'], 
    $data['email'], 
    $data['first_name'], 
    $data['last_name'], 
    $data['phone'], 
    $data['preferred_bank'], 
    $data['account_number'], 
    $data['bank_name'],
    $data['company_id'],
    $data['role']
);

// Execute the SQL query and check for errors
if ($stmt->execute()) {
    $response = ['status' => 'success', 'message' => 'Bank account information stored successfully'];
} else {
    $response = ['status' => 'error', 'message' => 'Failed to store bank account information: ' . $stmt->error];
}

// Close the statement and database connection
$stmt->close();
$conn->close();

// Send the response after processing
http_response_code($response['status'] === 'success' ? 200 : 500);
echo json_encode($response);
?>

<script>
const createCustomer = async (email, firstName, lastName, phone) => {
  const response = await fetch('https://api.paystack.co/customer', {
    method: 'POST',
    headers: {
      'Authorization': 'Bearer YOUR_SECRET_KEY',
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({
      email,
      first_name: firstName,
      last_name: lastName,
      phone
    })
  });
  return await response.json();
};

// Function to create a dedicated virtual account on Paystack
const createDedicatedAccount = async (customerId, preferredBank) => {
  const response = await fetch('https://api.paystack.co/dedicated_account', {
    method: 'POST',
    headers: {
      'Authorization': 'Bearer YOUR_SECRET_KEY',
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({
      customer: customerId,
      preferred_bank: preferredBank
    })
  });
  return await response.json();
};

// Function to create a customer bank account and store it in the database
const createCustomerBankAccount = async (email, firstName, lastName, phone, preferredBank, companyId, role) => {
  try {
    // Step 1: Create the customer
    const customerResponse = await createCustomer(email, firstName, lastName, phone);
    if (!customerResponse.status) {
      throw new Error('Failed to create customer');
    }
    const customerId = customerResponse.data.id;

    // Step 2: Create the dedicated virtual account
    const accountResponse = await createDedicatedAccount(customerId, preferredBank);
    if (!accountResponse.status) {
      throw new Error('Failed to create dedicated account');
    }

    // Step 3: Store the customer and account information in the database
    const storeResponse = await fetch('store_bank_account.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({
        customer_id: customerId,
        email,
        first_name: firstName,
        last_name: lastName,
        phone,
        preferred_bank: preferredBank,
        account_number: accountResponse.data.account_number,
        bank_name: accountResponse.data.bank.name,
        company_id: companyId,
        role: role
      })
    });

    const responseData = await storeResponse.json();
    if (responseData.status !== 'success') {
      throw new Error(responseData.message || 'Failed to store bank account information');
    }

    console.log('Customer bank account created and stored successfully:', accountResponse.data);
    return accountResponse.data;
  } catch (error) {
    console.error('Error creating customer bank account:', error);
    throw error;
  }
};

// Handle form submission
const handleFormSubmit = async (event) => {
  event.preventDefault();
  
  // Get form data
  const email = document.getElementById('email').value;
  const firstName = document.getElementById('firstName').value;
  const lastName = document.getElementById('lastName').value;
  const phone = document.getElementById('phone').value;
  const preferredBank = document.getElementById('preferredBank').value;
  const companyId = document.getElementById('companyId').value;
  const role = document.getElementById('role').value;

  try {
    const account = await createCustomerBankAccount(email, firstName, lastName, phone, preferredBank, companyId, role);
    console.log('Account created:', account);
    // Handle success (e.g., show success message, clear form)
  } catch (error) {
    console.error('Error:', error);
    // Handle error (e.g., show error message)
  }
};

const form = `
<form id="customerForm" onsubmit="handleFormSubmit(event)">
  <input type="email" id="email" required placeholder="Email">
  <input type="text" id="firstName" required placeholder="First Name">
  <input type="text" id="lastName" required placeholder="Last Name">
  <input type="tel" id="phone" required placeholder="Phone">
  <select id="preferredBank" required>
    <option value="">Select Preferred Bank</option>
    <option value="wema-bank">Wema Bank</option>
    <!-- Add more bank options as needed -->
  </select>
  <input type="number" id="companyId" required placeholder="Company ID">
  <select id="role" required>
    <option value="">Select Role</option>
    <option value="manager">Manager</option>
    <option value="admin">Admin</option>
    <option value="employee">Employee</option>
  </select>
  <button type="submit">Create Customer Bank Account</button>
</form>
`;

document.body.insertAdjacentHTML('beforeend', form);

</script>