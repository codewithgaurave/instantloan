<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.html');
    exit();
}

// Database connection
$db = new mysqli('localhost', 'root', '', 'instantloan');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        :root {
            --sidebar-width: 250px;
            --header-height: 60px;
            --primary-color: #3498db;
            --secondary-color: #2980b9;
            --light-color: #ecf0f1;
            --dark-color: #2c3e50;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            display: flex;
            min-height: 100vh;
            background-color: #f5f5f5;
        }

        .sidebar {
            width: var(--sidebar-width);
            background-color: var(--dark-color);
            color: white;
            height: 100vh;
            position: fixed;
            transition: all 0.3s;
        }

        .sidebar-header {
            padding: 20px;
            background-color: var(--primary-color);
        }

        .sidebar-menu {
            padding: 20px 0;
        }

        .sidebar-menu li {
            list-style: none;
            padding: 10px 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            cursor: pointer;
        }

        .sidebar-menu li:hover {
            background-color: var(--primary-color);
        }

        .sidebar-menu li.active {
            background-color: var(--primary-color);
        }

        .main-content {
            margin-left: var(--sidebar-width);
            flex: 1;
            padding: 20px;
        }

        .header {
            height: var(--header-height);
            background-color: white;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 20px;
            margin-bottom: 20px;
        }

        .content {
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .btn {
            padding: 10px 15px;
            background-color: var(--primary-color);
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }

        .btn:hover {
            background-color: var(--secondary-color);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table,
        th,
        td {
            border: 1px solid #ddd;
        }

        th,
        td {
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: var(--primary-color);
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.4);
        }

        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 60%;
            border-radius: 5px;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .close:hover {
            color: black;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .form-actions {
            text-align: right;
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <h2>Loan Management</h2>
        </div>
        <ul class="sidebar-menu">
            <li class="active" onclick="showSection('dashboard')">Dashboard</li>
            <li onclick="showSection('loan-data')">Loan Data</li>
            <li onclick="showAddLoanForm()">Add New Loan</li>
            <li onclick="logout()">Logout</li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="header">
            <h3>Welcome, <?php echo $_SESSION['admin_username']; ?></h3>
            <div class="user-actions">
                <button class="btn" onclick="logout()">Logout</button>
            </div>
        </div>

        <div class="content" id="dashboard-section">
            <h2>Dashboard Overview</h2>
            <p>Welcome to the Loan Management System admin panel. Use the sidebar to navigate.</p>
        </div>

        <div class="content" id="loan-data-section" style="display: none;">
            <h2>Loan Applications</h2>
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Name</th>
                        <th>Father's Name</th>
                        <th>Loan Type</th>
                        <th>App No.</th>
                        <th>Amount</th>
                        <th>Interest</th>
                        <th>Tenure</th>
                        <th>EMI</th>
                        <th>Fees</th>
                        <th>Phone</th>
                        <th>Address</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $query = "SELECT * FROM loans ORDER BY application_date DESC";
                    $result = $db->query($query);

                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                            <td>{$row['application_date']}</td>
                            <td>{$row['customer_name']}</td>
                            <td>{$row['father_name']}</td>
                            <td>{$row['loan_type']}</td>
                            <td>{$row['application_number']}</td>
                            <td>{$row['loan_amount']}</td>
                            <td>{$row['interest_rate']}%</td>
                            <td>{$row['tenure']} months</td>
                            <td>{$row['emi']}</td>
                            <td>{$row['processing_fees']}</td>
                            <td>{$row['phone_number']}</td>
                            <td>{$row['address']}</td>
                            <td>
                                <button class='btn' onclick='editLoan({$row['id']})'>Edit</button>
                                <button class='btn' onclick='deleteLoan({$row['id']})'>Delete</button>
                            </td>
                        </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Add Loan Modal -->
    <div id="addLoanModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('addLoanModal')">&times;</span>
            <h2>Add New Loan Application</h2>
            <form id="loanForm" onsubmit="submitLoanForm(event)">
                <div class="form-group">
                    <label for="application_date">Date:</label>
                    <input type="date" id="application_date" name="application_date" required>
                </div>
                <div class="form-group">
                    <label for="customer_name">Name:</label>
                    <input type="text" id="customer_name" name="customer_name" required>
                </div>
                <div class="form-group">
                    <label for="father_name">Father's Name:</label>
                    <input type="text" id="father_name" name="father_name" required>
                </div>
                <div class="form-group">
                    <label for="loan_type">Loan Type:</label>
                    <select id="loan_type" name="loan_type" required>
                        <option value="">Select Loan Type</option>
                        <option value="Personal Loan">Personal Loan</option>
                        <option value="Home Loan">Home Loan</option>
                        <option value="Car Loan">Car Loan</option>
                        <option value="Business Loan">Business Loan</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="application_number">Loan Application No:</label>
                    <input type="text" id="application_number" name="application_number" required>
                </div>
                <div class="form-group">
                    <label for="loan_amount">Loan Amount:</label>
                    <input type="number" id="loan_amount" name="loan_amount" step="0.01" required>
                </div>
                <div class="form-group">
                    <label for="interest_rate">Interest Rate (%):</label>
                    <input type="number" id="interest_rate" name="interest_rate" step="0.01" required>
                </div>
                <div class="form-group">
                    <label for="tenure">Tenure (months):</label>
                    <input type="number" id="tenure" name="tenure" required>
                </div>
                <div class="form-group">
                    <label for="emi">EMI:</label>
                    <input type="number" id="emi" name="emi" step="0.01" required>
                </div>
                <div class="form-group">
                    <label for="processing_fees">Processing Fees:</label>
                    <input type="number" id="processing_fees" name="processing_fees" step="0.01" required>
                </div>
                <div class="form-group">
                    <label for="phone_number">Phone Number:</label>
                    <input type="tel" id="phone_number" name="phone_number" required>
                </div>
                <div class="form-group">
                    <label for="address">Address:</label>
                    <textarea id="address" name="address" rows="3" required></textarea>
                </div>
                <div class="form-actions">
                    <button type="button" class="btn" onclick="closeModal('addLoanModal')">Cancel</button>
                    <button type="submit" class="btn">Save</button>
                </div>
                <input type="hidden" id="loan_id" name="loan_id" value="">
            </form>
        </div>
    </div>

    <script>
        function showSection(sectionId) {
            document.getElementById('dashboard-section').style.display = 'none';
            document.getElementById('loan-data-section').style.display = 'none';

            document.getElementById(sectionId + '-section').style.display = 'block';
        }

        function showAddLoanForm() {
            document.getElementById('loanForm').reset();
            document.getElementById('loan_id').value = '';
            document.getElementById('addLoanModal').style.display = 'block';
            showSection('loan-data');
        }

        function closeModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }

        function submitLoanForm(e) {
            e.preventDefault();

            const formData = new FormData(document.getElementById('loanForm'));
            const loanId = document.getElementById('loan_id').value;
            const url = loanId ? 'update_loan.php' : 'add_loan.php';

            fetch(url, {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Loan data saved successfully!');
                        closeModal('addLoanModal');
                        window.location.reload();
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while saving the data.');
                });
        }

        function editLoan(id) {
            fetch('get_loan.php?id=' + id)
                .then(response => response.json())
                .then(data => {
                    if (data) {
                        document.getElementById('loan_id').value = data.id;
                        document.getElementById('application_date').value = data.application_date;
                        document.getElementById('customer_name').value = data.customer_name;
                        document.getElementById('father_name').value = data.father_name;
                        document.getElementById('loan_type').value = data.loan_type;
                        document.getElementById('application_number').value = data.application_number;
                        document.getElementById('loan_amount').value = data.loan_amount;
                        document.getElementById('interest_rate').value = data.interest_rate;
                        document.getElementById('tenure').value = data.tenure;
                        document.getElementById('emi').value = data.emi;
                        document.getElementById('processing_fees').value = data.processing_fees;
                        document.getElementById('address').value = data.address;

                        document.getElementById('addLoanModal').style.display = 'block';
                    }
                });
        }

        function deleteLoan(id) {
            if (confirm('Are you sure you want to delete this loan application?')) {
                fetch('delete_loan.php?id=' + id)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Loan application deleted successfully!');
                            window.location.reload();
                        } else {
                            alert('Error: ' + data.message);
                        }
                    });
            }
        }

        function logout() {
            fetch('logout.php')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.href = 'login.html';
                    }
                });
        }

        // Show dashboard by default
        showSection('dashboard');
    </script>
</body>

</html>