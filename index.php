<?php
require_once 'check_auth.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stock Management - FILLASON MULTIBIZ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --success-color: #27ae60;
            --warning-color: #f39c12;
            --danger-color: #e74c3c;
            --light-color: #ecf0f1;
            --dark-color: #2c3e50;
        }

        body {
            padding-top: 70px;
            padding-bottom: 70px;
            background-color: #f5f7fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        /* Top Header */
        .company-header {
            background: linear-gradient(135deg, var(--primary-color), var(--dark-color));
            color: white;
            padding: 12px 0;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1030;
        }

        .company-name {
            font-weight: 700;
            letter-spacing: 1px;
            margin-bottom: 0;
        }

        .company-address {
            font-size: 0.85rem;
            opacity: 0.9;
        }

        /* Bottom Navigation */
        .bottom-nav {
            position: fixed;
            bottom: 0;
            width: 100%;
            background: white;
            box-shadow: 0 -2px 10px rgba(0,0,0,0.1);
            z-index: 1020;
            padding: 5px 0;
        }

        .nav-link {
            text-align: center;
            padding: 8px 0;
            color: #7f8c8d;
            text-decoration: none;
            font-size: 0.75rem;
            transition: all 0.3s;
        }

        .nav-link i {
            display: block;
            font-size: 1.2rem;
            margin-bottom: 3px;
        }

        .nav-link.active {
            color: var(--secondary-color);
            font-weight: 600;
        }

        .nav-link:hover {
            color: var(--secondary-color);
        }

        /* Content Sections */
        .content-section {
            display: none;
            animation: fadeIn 0.5s ease;
        }

        .content-section.active {
            display: block;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Cards */
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            margin-bottom: 20px;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(0,0,0,0.1);
        }

        .card-header {
            background-color: white;
            border-bottom: 1px solid rgba(0,0,0,0.05);
            font-weight: 600;
            padding: 12px 15px;
            border-radius: 10px 10px 0 0 !important;
        }

        /* Tables */
        .table {
            margin-bottom: 0;
        }

        .table th {
            font-weight: 600;
            background-color: #f8f9fa;
            border-top: none;
        }

        .stock-ok {
            color: var(--success-color);
        }

        /* Buttons */
        .btn {
            border-radius: 8px;
            padding: 8px 16px;
            font-weight: 500;
            transition: all 0.3s;
        }

        .btn-sm {
            padding: 5px 10px;
            font-size: 0.8rem;
        }

        /* Forms */
        .form-control, .form-select {
            border-radius: 8px;
            padding: 8px 12px;
            border: 1px solid #ddd;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--secondary-color);
            box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
        }

        /* Badges */
        .badge {
            font-weight: 500;
            padding: 5px 8px;
        }

        /* Pagination */
        .pagination .page-item .page-link {
            border-radius: 6px;
            margin: 0 3px;
            border: none;
            color: var(--dark-color);
        }

        .pagination .page-item.active .page-link {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
        }

        /* Responsive Adjustments */
        @media (max-width: 576px) {
            .company-name {
                font-size: 1.1rem;
            }

            .company-address {
                font-size: 0.7rem;
            }

            .nav-link {
                font-size: 0.65rem;
            }

            .nav-link i {
                font-size: 1rem;
            }

            .card-header {
                padding: 10px 12px;
            }

            /* Stack date range picker vertically */
            .date-range-picker {
                flex-direction: column;
                align-items: stretch;
                gap: 15px;
            }

            .date-range-picker > div {
                width: 100%;
            }

            /* Adjust summary items for mobile */
            .summary-item {
                text-align: left;
                margin-bottom: 15px;
            }

            /* Report tables */
            #report-results .table-responsive {
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }

            #report-results table {
                font-size: 0.75rem;
                width: 100%;
                min-width: auto; /* Remove fixed min-width */
            }

            #report-results th,
            #report-results td {
                padding: 6px 4px;
                white-space: normal; /* Allow wrapping */
                word-wrap: break-word;
            }

            #report-results th {
                font-size: 0.7rem;
            }

            /* Summary grid for mobile */
            #report-results .summary-grid {
                display: grid;
                grid-template-columns: 1fr;
                gap: 10px;
            }
        }

        @media (min-width: 577px) {
            #report-results .summary-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
                gap: 15px;
            }

            #report-results table {
                font-size: 0.85rem;
            }

            #report-results th,
            #report-results td {
                padding: 8px;
            }
        }

        /* Print Styles */
        @media print {
            body, body * {
                visibility: hidden;
            }

            #print-report-container,
            #print-report-container * {
                visibility: visible;
            }

            #print-report-container {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                padding: 10mm;
                font-size: 10pt;
                color: #000 !important;
            }

            #print-report-container .report-header {
                text-align: center;
                margin-bottom: 20px;
            }

            #print-report-container .company-name {
                font-size: 14pt;
                font-weight: bold;
            }

            #print-report-container .company-address {
                font-size: 10pt;
                margin-bottom: 10px;
            }

            #print-report-container table {
                width: 100%;
                border-collapse: collapse;
                page-break-inside: auto;
            }

            #print-report-container th,
            #print-report-container td {
                border: 1px solid #000;
                padding: 5px;
                font-size: 9pt;
                color: #000 !important;
                white-space: normal;
            }

            #print-report-container tr {
                page-break-inside: avoid;
                page-break-after: auto;
            }

            #print-report-container h4,
            #print-report-container h5 {
                color: #000 !important;
                font-weight: bold;
                margin: 10px 0;
            }

            #print-report-container p {
                color: #000 !important;
                margin: 5px 0;
            }

            /* Hide print button and other UI elements */
            .print-report-btn,
            .bottom-nav,
            .company-header,
            .modal,
            .alert,
            .pagination {
                display: none !important;
            }

            /* Ensure no background colors */
            #print-report-container .table,
            #print-report-container .summary-grid {
                background-color: transparent !important;
            }
        }

        /* Section Headings */
        .section-heading {
            color: var(--primary-color);
            font-weight: 700;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #eee;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        ::-webkit-scrollbar-thumb {
            background: var(--secondary-color);
            border-radius: 3px;
        }

        /* Animation for alerts */
        .alert-animate {
            animation: slideIn 0.3s ease-out;
        }

        @keyframes slideIn {
            from { transform: translateY(-20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        .table .stock-low {
            color: #e74c3c;
            font-weight: bold;
            border: 3px solid red;
        }

        .table .stock-low td {
            background-color: red;
            color: white;
            font-weight: bold;
            border: 3px solid red;
            animation: blinkingBorder 0.5s infinite alternate;
        }

        @keyframes blinkingBorder {
            from {
                background-color: red;
                border-color: red;
            }
            to {
                border-color: transparent;
                background-color: transparent;
                color: red;
            }
        }

        .stock-low td {
            border-color: red !important;
        }

        .stock-high {
            background-color: rgba(46, 204, 113, 0.1) !important;
            color: #2ecc71;
            font-weight: bold;
        }

        .clickable-card {
            cursor: pointer;
            transition: transform 0.2s;
        }

        .clickable-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .animate-pulse {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.02); }
            100% { transform: scale(1); }
        }

        .select2-container {
            width: 100% !important;
        }

        .select2-selection {
            height: 38px;
            border: 1px solid #ced4da;
            border-radius: 0.25rem;
            box-shadow: none;
        }

        .select2-selection--single .select2-selection__rendered {
            line-height: 36px;
        }

        .select2-selection--single .select2-selection__arrow {
            height: 36px;
            right: 10px;
        }

        .select2-selection--single .select2-selection__placeholder {
            color: #6c757d;
        }

        .select2-results__option {
            padding: 8px 12px;
        }

        .select2-results__option--highlighted[aria-selected] {
            background-color: #007bff;
            color: white;
        }

        /* Report Section Styles */
        .date-range-picker {
            display: flex;
            gap: 15px;
            align-items: center;
            flex-wrap: wrap;
        }

        .report-type-select {
            min-width: 120px;
            max-width: 100%;
        }

        .report-table {
            margin-bottom: 20px;
        }

        .summary-card {
            background-color: #f8f9fa;
            padding: 1rem;
        }

        .summary-item {
            margin-bottom: 10px;
        }

        .summary-label {
            font-weight: 600;
            color: var(--primary-color);
        }

        /* Modal Enhancements */
        .modal-content {
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }

        .modal-header {
            border-bottom: none;
            padding: 1.5rem;
        }

        .modal-footer {
            border-top: none;
            padding: 1rem 1.5rem;
        }

        .modal-title {
            font-weight: 600;
        }

        .form-label {
            font-weight: 500;
            color: var(--primary-color);
        }

        .modal .btn-primary {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
        }

        .modal .btn-primary:hover {
            background-color: #2980b9;
            border-color: #2980b9;
        }

        /* Print-specific container */
        #print-report-container {
            display: none;
        }
    </style>
</head>
<body>
    <!-- Top Header -->
    <header class="company-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-10 text-center">
                    <h4 class="company-name mb-1">FILLASON MULTIBIZ COMPANY</h4>
                    <div class="company-address">309 AJEBAMIDELE ADO EKITI, EKITI STATE</div>
                </div>
                <div class="col-2 text-end">
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-light dropdown-toggle" type="button" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user-circle"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
                            <li><a class="dropdown-item" href="profile.php"><i class="fas fa-user me-2"></i>Profile</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="logout.php"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <div class="container mt-4 mb-5">
        <!-- Dashboard Section -->
        <div id="dashboard-section" class="content-section active">
            <h2 class="section-heading"><i class="fas fa-tachometer-alt me-2"></i>Dashboard Overview</h2>
            <div class="row">
                <div class="col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="card-header d-flex justify-content-between align-items-center bg-white">
                            <span><i class="fas fa-boxes me-2"></i>Current Stock</span>
                            <span class="badge bg-primary">Live</span>
                        </div>
                        <div class="card-body p-0">
                            <div id="current-stock-list" class="table-responsive">
                                <p class="text-center my-5">Loading stock data...</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="card-header d-flex justify-content-between align-items-center bg-white">
                            <span><i class="fas fa-chart-line me-2"></i>Recent Sales</span>
                            <span class="badge bg-success">Updated</span>
                        </div>
                        <div class="card-body p-0">
                            <div id="recent-sales" class="table-responsive">
                                <p class="text-center my-5">Loading sales data...</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body text-center py-3">
                            <h5 class="card-title"><i class="fas fa-cubes me-2"></i>Total Products</h5>
                            <p class="card-text fs-4 fw-bold" id="total-products">--</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card bg-success text-white">
                        <div class="card-body text-center py-3">
                            <h5 class="card-title"><i class="fas fa-cart-arrow-down me-2"></i>Today's Sales</h5>
                            <p class="card-text fs-4 fw-bold" id="today-sales-count">--</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card bg-warning text-dark clickable-card" id="low-stock-card">
                        <div class="card-body text-center py-3">
                            <h5 class="card-title"><i class="fas fa-exclamation-triangle me-2"></i>Low Stock</h5>
                            <p class="card-text fs-4 fw-bold" id="low-stock-count">--</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sales Section -->
        <div id="sales-section" class="content-section">
            <h2 class="section-heading"><i class="fas fa-shopping-cart me-2"></i>Record Sales</h2>
            <div class="row">
                <div class="col-lg-6 mb-4">
                    <div class="card">
                        <div class="card-header bg-white">
                            <i class="fas fa-plus-circle me-2"></i>New Sale Entry
                        </div>
                        <div class="card-body">
                            <form id="sales-form">
                                <div class="mb-3">
                                    <label for="sale-date" class="form-label">Date</label>
                                    <input type="date" class="form-control" id="sale-date" required value="<?php echo date('Y-m-d'); ?>">
                                </div>
                                <div class="mb-3">
                                    <label for="product-select" class="form-label">Product</label>
                                    <select class="form-select select2-product" id="product-select" required>
                                        <option value="">Select Product</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="sale-quantity" class="form-label">Quantity</label>
                                    <input type="number" class="form-control" id="sale-quantity" min="1" required>
                                </div>
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-save me-2"></i>Record Sale
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 mb-4">
                    <div class="card">
                        <div class="card-header bg-white d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-history me-2"></i>Today's Sales
                            </div>
                            <div class="w-50">
                                <input type="text" class="form-control form-control-sm" id="sales-search" placeholder="Search sales...">
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div id="today-sales-list" class="table-responsive">
                                <p class="text-center my-5">Loading today's sales...</p>
                            </div>
                            <div class="row mt-2 mx-2">
                                <div class="col-12">
                                    <nav aria-label="Sales pagination">
                                        <ul class="pagination pagination-sm justify-content-center" id="sales-pagination"></ul>
                                    </nav>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add Stock Section -->
        <div id="addstock-section" class="content-section">
            <h2 class="section-heading"><i class="fas fa-plus-circle me-2"></i>Add Stock</h2>
            <div class="row">
                <div class="col-lg-6 mb-4">
                    <div class="card">
                        <div class="card-header bg-white">
                            <i class="fas fa-arrow-up me-2"></i>Stock Addition
                        </div>
                        <div class="card-body">
                            <form id="addstock-form">
                                <div class="mb-3">
                                    <label for="addstock-date" class="form-label">Date</label>
                                    <input type="date" class="form-control" id="addstock-date" required value="<?php echo date('Y-m-d'); ?>">
                                </div>
                                <div class="mb-3">
                                    <label for="addstock-product" class="form-label">Product</label>
                                    <select class="form-select select2-product" id="addstock-product" required>
                                        <option value="">Select Product</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="addstock-quantity" class="form-label">Quantity</label>
                                    <input type="number" class="form-control" id="addstock-quantity" min="1" required>
                                </div>
                                <button type="submit" class="btn btn-success w-100">
                                    <i class="fas fa-plus me-2"></i>Add Stock
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 mb-4">
                    <div class="card">
                        <div class="card-header bg-white d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-history me-2"></i>Recent Stock Additions
                            </div>
                            <div class="w-50">
                                <input type="text" class="form-control form-control-sm" id="additions-search" placeholder="Search additions...">
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div id="recent-additions" class="table-responsive">
                                <p class="text-center my-5">Loading recent additions...</p>
                            </div>
                            <div class="row mt-2 mx-2">
                                <div class="col-12">
                                    <nav aria-label="Additions pagination">
                                        <ul class="pagination pagination-sm justify-content-center" id="additions-pagination"></ul>
                                    </nav>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Returned Products Section -->
        <div id="returns-section" class="content-section">
            <h2 class="section-heading"><i class="fas fa-undo me-2"></i>Returned Products</h2>
            <div class="row">
                <div class="col-lg-6 mb-4">
                    <div class="card">
                        <div class="card-header bg-white">
                            <i class="fas fa-undo-alt me-2"></i>Record Product Return
                        </div>
                        <div class="card-body">
                            <form id="return-form">
                                <div class="mb-3">
                                    <label for="return-date" class="form-label">Date</label>
                                    <input type="date" class="form-control" id="return-date" required value="<?php echo date('Y-m-d'); ?>">
                                </div>
                                <div class="mb-3">
                                    <label for="return-product" class="form-label">Product</label>
                                    <select class="form-select select2-product" id="return-product" required>
                                        <option value="">Select Product</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="return-quantity" class="form-label">Quantity</label>
                                    <input type="number" class="form-control" id="return-quantity" min="1" required>
                                </div>
                                <div class="mb-3">
                                    <label for="return-reason" class="form-label">Reason</label>
                                    <textarea class="form-control" id="return-reason" rows="2" placeholder="Optional reason for return"></textarea>
                                </div>
                                <button type="submit" class="btn btn-warning w-100">
                                    <i class="fas fa-undo me-2"></i>Record Return
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 mb-4">
                    <div class="card">
                        <div class="card-header bg-white d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-history me-2"></i>Recent Returns
                            </div>
                            <div class="d-flex gap-2">
                                <input type="date" class="form-control form-control-sm" id="returns-date" value="<?php echo date('Y-m-d'); ?>">
                                <input type="text" class="form-control form-control-sm" id="returns-search" placeholder="Search returns...">
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div id="recent-returns" class="table-responsive">
                                <p class="text-center my-5">Loading recent returns...</p>
                            </div>
                            <div class="row mt-2 mx-2">
                                <div class="col-12">
                                    <nav aria-label="Returns pagination">
                                        <ul class="pagination pagination-sm justify-content-center" id="returns-pagination"></ul>
                                    </nav>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Expenses Section -->
        <div id="expenses-section" class="content-section">
            <h2 class="section-heading"><i class="fas fa-money-bill-wave me-2"></i>Expenses</h2>
            <div class="row">
                <div class="col-lg-6 mb-4">
                    <div class="card">
                        <div class="card-header bg-white">
                            <i class="fas fa-plus-circle me-2"></i>Record Expense
                        </div>
                        <div class="card-body">
                            <form id="expense-form">
                                <div class="mb-3">
                                    <label for="expense-date" class="form-label">Date</label>
                                    <input type="date" class="form-control" id="expense-date" required value="<?php echo date('Y-m-d'); ?>">
                                </div>
                                <div class="mb-3">
                                    <label for="expense-amount" class="form-label">Amount (₦)</label>
                                    <input type="number" class="form-control" id="expense-amount" min="0" step="0.01" required>
                                </div>
                                <div class="mb-3">
                                    <label for="expense-description" class="form-label">Description</label>
                                    <textarea class="form-control" id="expense-description" rows="2" placeholder="Enter expense description"></textarea>
                                </div>
                                <button type="submit" class="btn btn-danger w-100">
                                    <i class="fas fa-save me-2"></i>Record Expense
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 mb-4">
                    <div class="card">
                        <div class="card-header bg-white d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-history me-2"></i>Recent Expenses
                            </div>
                            <div class="d-flex gap-2">
                                <input type="date" class="form-control form-control-sm" id="expenses-date" value="<?php echo date('Y-m-d'); ?>">
                                <input type="text" class="form-control form-control-sm" id="expenses-search" placeholder="Search expenses...">
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div id="recent-expenses" class="table-responsive">
                                <p class="text-center my-5">Loading recent expenses...</p>
                            </div>
                            <div class="row mt-2 mx-2">
                                <div class="col-12">
                                    <nav aria-label="Expenses pagination">
                                        <ul class="pagination pagination-sm justify-content-center" id="expenses-pagination"></ul>
                                    </nav>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Reports Section -->
        <div id="reports-section" class="content-section">
            <h2 class="section-heading"><i class="fas fa-chart-bar me-2"></i>Reports</h2>
            <div class="card mb-4">
                <div class="card-header bg-white">
                    <i class="fas fa-filter me-2"></i>Generate Report
                </div>
                <div class="card-body">
                    <form id="report-form">
                        <div class="mb-3 date-range-picker">
                            <div>
                                <label for="report-type" class="form-label">Report Type</label>
                                <select class="form-select report-type-select" id="report-type" required>
                                    <option value="daily">Daily</option>
                                    <option value="weekly">Weekly</option>
                                    <option value="monthly">Monthly</option>
                                    <option value="custom">Custom Range</option>
                                </select>
                            </div>
                            <div>
                                <label for="report-start-date" class="form-label">Start Date</label>
                                <input type="date" class="form-control" id="report-start-date" required value="<?php echo date('Y-m-d'); ?>">
                            </div>
                            <div id="end-date-container" class="d-none">
                                <label for="report-end-date" class="form-label">End Date</label>
                                <input type="date" class="form-control" id="report-end-date">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 mt-3">
                            <i class="fas fa-chart-line me-2"></i>Generate Report
                        </button>
                    </form>
                </div>
            </div>
            <div id="report-results" class="d-none">
                <div class="card mb-4">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <span><i class="fas fa-list me-2"></i>Report Overview</span>
                        <button class="btn btn-primary btn-sm print-report-btn">
                            <i class="fas fa-print me-2"></i>Print Report
                        </button>
                    </div>
                    <div class="card-body">
                        <div id="report-content"></div>
                    </div>
                </div>
            </div>
            <div id="print-report-container">
                <div class="report-header">
                    <h3 class="company-name">FILLASON MULTIBIZ COMPANY</h3>
                    <div class="company-address">309 AJEBAMIDELE ADO EKITI, EKITI STATE</div>
                    <hr>
                </div>
                <div id="print-report-content"></div>
            </div>
        </div>

        <!-- Products Management Section -->
        <div id="products-section" class="content-section">
            <h2 class="section-heading"><i class="fas fa-box-open me-2"></i>Products Management</h2>
            <div class="row">
                <div class="col-12 mb-4">
                    <div class="card">
                        <div class="card-header bg-white d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-boxes me-2"></i>All Products
                            </div>
                            <div class="d-flex">
                                <input type="text" class="form-control form-control-sm me-2" id="products-search" placeholder="Search products...">
                                <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addProductModal">
                                    <i class="fas fa-plus me-1"></i>New
                                </button>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div id="products-list" class="table-responsive">
                                <p class="text-center my-5">Loading products...</p>
                            </div>
                            <div class="row mt-2 mx-2">
                                <div class="col-12">
                                    <nav aria-label="Products pagination">
                                        <ul class="pagination pagination-sm justify-content-center" id="products-pagination"></ul>
                                    </nav>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Product Modal -->
    <div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="addProductModalLabel">Add New Product</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="add-product-form">
                        <div class="mb-3">
                            <label for="product-name" class="form-label">Product Name</label>
                            <input type="text" class="form-control" id="product-name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="product-description" class="form-label">Description</label>
                            <textarea class="form-control" id="product-description" rows="3"></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="initial-stock" class="form-label">Initial Stock</label>
                                <input type="number" class="form-control" id="initial-stock" min="0" value="0">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="product-price" class="form-label">Selling Price (₦)</label>
                                <input type="number" step="0.01" class="form-control" id="product-price" min="0" value="0">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="cost-price" class="form-label">Cost Price (₦)</label>
                                <input type="number" step="0.01" class="form-control" id="cost-price" min="0" value="0">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="low-stock-threshold" class="form-label">Low Stock Threshold</label>
                            <input type="number" class="form-control" id="low-stock-threshold" min="1" value="200">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="save-product-btn">Save Product</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Update Product Modal -->
    <div class="modal fade" id="updateProductModal" tabindex="-1" aria-labelledby="updateProductModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title" id="updateProductModalLabel">Update Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="update-product-form">
                        <input type="hidden" id="update-product-id">
                        <div class="mb-3">
                            <label for="update-product-name" class="form-label">Product Name</label>
                            <input type="text" class="form-control" id="update-product-name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="update-product-description" class="form-label">Description</label>
                            <textarea class="form-control" id="update-product-description" rows="3"></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="update-initial-stock" class="form-label">Current Stock</label>
                                <input type="number" class="form-control" id="update-initial-stock" min="0" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="update-product-price" class="form-label">Selling Price (₦)</label>
                                <input type="number" step="0.01" class="form-control" id="update-product-price" min="0" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="update-cost-price" class="form-label">Cost Price (₦)</label>
                                <input type="number" step="0.01" class="form-control" id="update-cost-price" min="0" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="update-low-stock-threshold" class="form-label">Low Stock Threshold</label>
                            <input type="number" class="form-control" id="update-low-stock-threshold" min="1" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-warning" id="update-product-btn">Update Product</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Update Expense Modal -->
    <div class="modal fade" id="updateExpenseModal" tabindex="-1" aria-labelledby="updateExpenseModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="updateExpenseModalLabel">Update Expense</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="update-expense-form">
                        <input type="hidden" id="update-expense-id">
                        <div class="mb-3">
                            <label for="update-expense-date" class="form-label">Date</label>
                            <input type="date" class="form-control" id="update-expense-date" required>
                        </div>
                        <div class="mb-3">
                            <label for="update-expense-amount" class="form-label">Amount (₦)</label>
                            <input type="number" class="form-control" id="update-expense-amount" min="0" step="0.01" required>
                        </div>
                        <div class="mb-3">
                            <label for="update-expense-description" class="form-label">Description</label>
                            <textarea class="form-control" id="update-expense-description" rows="2" placeholder="Enter expense description"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="update-expense-btn">Update Expense</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Low Stock Items Modal -->
    <div class="modal fade" id="lowStockModal" tabindex="-1" aria-labelledby="lowStockModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title" id="lowStockModalLabel">
                        <i class="fas fa-exclamation-triangle me-2"></i>Low Stock Items
                        <span class="badge bg-danger ms-2" id="low-stock-count-badge"></span>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning d-flex align-items-center d-none" id="low-stock-warning">
                        <i class="fas fa-info-circle me-2"></i>
                        <div>These products need to be restocked soon</div>
                    </div>
                    <div class="table-responsive">
                        <table class="table">
                            <thead class="table-warning">
                                <tr>
                                    <th width="50%">Product</th>
                                    <th width="25%">Current Stock</th>
                                    <th width="25%">Price</th>
                                </tr>
                            </thead>
                            <tbody id="low-stock-items-list">
                                <tr>
                                    <td colspan="3" class="text-center text-muted">
                                        Loading low stock items...
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bottom Navigation -->
    <nav class="navbar bottom-nav">
        <div class="container-fluid px-0">
            <div class="row g-0 w-100">
                <div class="col-2 px-0">
                    <a href="#" class="nav-link active" data-section="dashboard-section">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>Dashboard</span>
                    </a>
                </div>
                <div class="col-2 px-0">
                    <a href="#" class="nav-link" data-section="sales-section">
                        <i class="fas fa-shopping-cart"></i>
                        <span>Sales</span>
                    </a>
                </div>
                <div class="col-1 px-0">
                    <a href="#" class="nav-link" data-section="addstock-section">
                        <i class="fas fa-plus-circle"></i>
                        <span>Add Stock</span>
                    </a>
                </div>
                <div class="col-1 px-0">
                    <a href="#" class="nav-link" data-section="returns-section">
                        <i class="fas fa-undo"></i>
                        <span>Returns</span>
                    </a>
                </div>
                <div class="col-2 px-0">
                    <a href="#" class="nav-link" data-section="products-section">
                        <i class="fas fa-box-open"></i>
                        <span>Products</span>
                    </a>
                </div>
                <div class="col-1 px-0">
                    <a href="#" class="nav-link" data-section="expenses-section">
                        <i class="fas fa-money-bill-wave"></i>
                        <span>Expenses</span>
                    </a>
                </div>
                <div class="col-2 px-0">
                    <a href="#" class="nav-link" data-section="reports-section">
                        <i class="fas fa-chart-bar"></i>
                        <span>Reports</span>
                    </a>
                </div>
            </div>
        </div>
    </nav>
</body>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        // Initialize the application
        initApp();

        // Initialize Select2 for all product selects
        $('.select2-product').select2({
            placeholder: "Select Product",
            allowClear: true
        });

        // Navigation handling
        $('.nav-link').click(function(e) {
            e.preventDefault();
            if ($(this).hasClass('active')) return;

            $('.nav-link').removeClass('active');
            $(this).addClass('active');
            $('.content-section').removeClass('active');
            $('#' + $(this).data('section')).addClass('active');

            // Load data for the active section
            loadSectionData($(this).data('section'));
        });

        // Form submissions
        $('#sales-form').submit(function(e) {
            e.preventDefault();
            recordSale();
        });

        $('#addstock-form').submit(function(e) {
            e.preventDefault();
            addStock();
        });

        $('#return-form').submit(function(e) {
            e.preventDefault();
            recordReturn();
        });

        $('#expense-form').submit(function(e) {
            e.preventDefault();
            recordExpense();
        });

        $('#report-form').submit(function(e) {
            e.preventDefault();
            generateReport();
        });

        $('#save-product-btn').click(function() {
            addProduct();
        });

        $('#update-product-btn').click(function() {
            updateProduct();
        });

        $('#update-expense-btn').click(function() {
            updateExpense();
        });

        // Search functionality
        $('#sales-search').keyup(debounce(function() {
            loadTodaySales(1);
        }, 300));

        $('#additions-search').keyup(debounce(function() {
            loadRecentAdditions(1);
        }, 300));

        $('#returns-search, #returns-date').on('change keyup', debounce(function() {
            loadRecentReturns(1);
        }, 300));

        $('#products-search').keyup(debounce(function() {
            loadAllProducts(1);
        }, 300));

        $('#expenses-search, #expenses-date').on('change keyup', debounce(function() {
            loadRecentExpenses(1);
        }, 300));

        // Report type change handler
        $('#report-type').change(function() {
            const reportType = $(this).val();
            if (reportType === 'custom') {
                $('#end-date-container').removeClass('d-none');
                $('#report-end-date').prop('required', true);
            } else {
                $('#end-date-container').addClass('d-none');
                $('#report-end-date').prop('required', false);
            }
        });

        // Low stock card click
        $('#low-stock-card').click(function() {
            loadLowStockItems();
            $('#lowStockModal').modal('show');
        });
    });

    function initApp() {
        // Load initial data
        loadSectionData('dashboard-section');
        loadProductsForSelects();
        updateDashboardStats();
    }

    function loadSectionData(section) {
        switch(section) {
            case 'dashboard-section':
                loadCurrentStock();
                loadRecentSales();
                updateDashboardStats();
                break;
            case 'sales-section':
                loadTodaySales();
                break;
            case 'addstock-section':
                loadRecentAdditions();
                break;
            case 'returns-section':
                loadRecentReturns();
                break;
            case 'products-section':
                loadAllProducts();
                break;
            case 'expenses-section':
                loadRecentExpenses();
                break;
            case 'reports-section':
                $('#report-results').addClass('d-none');
                break;
        }
    }

    function loadProductsForSelects() {
        $.ajax({
            url: 'api/get_products.php',
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                let options = '<option value="">Select Product</option>';
                data.forEach(product => {
                    options += `<option value="${product.id}">${product.name} (Stock: ${product.current_stock})</option>`;
                });
                $('.select2-product').html(options).trigger('change');
            },
            error: function(xhr) {
                showAlert('Error loading products: ' + xhr.responseText, 'danger');
            }
        });
    }

    function loadCurrentStock() {
        $.ajax({
            url: 'api/get_current_stock.php',
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                let html = '<table class="table table-hover">';
                html += '<thead><tr><th>Product</th><th>Stock</th><th>Threshold</th><th>Status</th></tr></thead><tbody>';
                
                if (data.length === 0) {
                    html += '<tr><td colspan="4" class="text-center">No products in stock</td></tr>';
                } else {
                    data.forEach(product => {
                        const isLowStock = product.current_stock < product.low_stock_threshold;
                        const statusClass = isLowStock ? 'stock-low' : 'stock-ok';
                        const statusText = isLowStock ? 'Low Stock' : 'In Stock';
                        
                        html += `<tr class="${statusClass}">
                            <td>${product.name}</td>
                            <td>${product.current_stock}</td>
                            <td>${product.low_stock_threshold}</td>
                            <td>${statusText}</td>
                        </tr>`;
                    });
                }
                
                html += '</tbody></table>';
                $('#current-stock-list').html(html);
            },
            error: function(xhr) {
                showAlert('Error loading stock: ' + xhr.responseText, 'danger');
            }
        });
    }

    function loadRecentSales() {
        $.ajax({
            url: 'api/get_recent_sales.php',
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                let html = '<table class="table table-hover">';
                html += '<thead><tr><th>Date</th><th>Product</th><th>Qty</th><th>Amount</th></tr></thead><tbody>';
                
                if (data.length === 0) {
                    html += '<tr><td colspan="4" class="text-center">No recent sales</td></tr>';
                } else {
                    data.forEach(sale => {
                        const amount = sale.quantity * (sale.product_price || 0);
                        html += `<tr>
                            <td>${formatDate(sale.sale_date)}</td>
                            <td>${sale.product_name}</td>
                            <td>${sale.quantity}</td>
                            <td>₦${amount.toFixed(2)}</td>
                        </tr>`;
                    });
                }
                
                html += '</tbody></table>';
                $('#recent-sales').html(html);
            },
            error: function(xhr) {
                showAlert('Error loading sales: ' + xhr.responseText, 'danger');
            }
        });
    }

    function loadTodaySales(page = 1, search = '') {
        const today = new Date().toISOString().split('T')[0];
        search = search || $('#sales-search').val();
        
        $.ajax({
            url: 'api/get_sales_by_date_paginated.php',
            type: 'GET',
            data: { 
                date: today,
                page: page,
                search: search
            },
            dataType: 'json',
            success: function(data) {
                let html = '<table class="table table-hover">';
                html += '<thead><tr><th>Product</th><th>Qty</th><th>Amount</th><th>Time</th><th>Action</th></tr></thead><tbody>';
                
                if (data.sales.length === 0) {
                    html += '<tr><td colspan="5" class="text-center">No sales recorded today</td></tr>';
                } else {
                    data.sales.forEach(sale => {
                        const amount = sale.quantity * (sale.product_price || 0);
                        html += `<tr>
                            <td>${sale.product_name}</td>
                            <td>${sale.quantity}</td>
                            <td>₦${amount.toFixed(2)}</td>
                            <td>${formatTime(sale.recorded_at)}</td>
                            <td><button class="btn btn-sm btn-danger delete-sale" data-id="${sale.id}"><i class="fas fa-trash"></i></button></td>
                        </tr>`;
                    });
                }
                
                html += '</tbody></table>';
                $('#today-sales-list').html(html);
                
                $('.delete-sale').click(function() {
                    const saleId = $(this).data('id');
                    deleteSale(saleId);
                });
                
                updatePagination('#sales-pagination', page, data.totalPages, loadTodaySales);
            },
            error: function(xhr) {
                showAlert('Error loading today\'s sales: ' + xhr.responseText, 'danger');
            }
        });
    }

    function loadRecentAdditions(page = 1, search = '') {
        search = search || $('#additions-search').val();
        
        $.ajax({
            url: 'api/get_recent_additions_paginated.php',
            type: 'GET',
            data: {
                page: page,
                search: search
            },
            dataType: 'json',
            success: function(data) {
                let html = '<table class="table table-hover">';
                html += '<thead><tr><th>Date</th><th>Product</th><th>Qty</th><th>Action</th></tr></thead><tbody>';
                
                if (data.additions.length === 0) {
                    html += '<tr><td colspan="4" class="text-center">No stock additions found</td></tr>';
                } else {
                    data.additions.forEach(addition => {
                        html += `<tr>
                            <td>${formatDate(addition.addition_date)}</td>
                            <td>${addition.product_name}</td>
                            <td>${addition.quantity}</td>
                            <td><button class="btn btn-sm btn-danger delete-addition" data-id="${addition.id}"><i class="fas fa-trash"></i></button></td>
                        </tr>`;
                    });
                }
                
                html += '</tbody></table>';
                $('#recent-additions').html(html);
                
                $('.delete-addition').click(function() {
                    const additionId = $(this).data('id');
                    if (confirm('Are you sure you want to delete this stock addition?')) {
                        deleteStockAddition(additionId);
                    }
                });
                
                updatePagination('#additions-pagination', page, data.totalPages, loadRecentAdditions);
            },
            error: function(xhr) {
                showAlert('Error loading additions: ' + xhr.responseText, 'danger');
            }
        });
    }

    function loadRecentReturns(page = 1, search = '', date = '') {
        search = search || $('#returns-search').val();
        date = date || $('#returns-date').val();
        
        $.ajax({
            url: 'api/get_returns_paginated.php',
            type: 'GET',
            data: {
                page: page,
                search: search,
                date: date
            },
            dataType: 'json',
            success: function(data) {
                let html = '<table class="table table-hover">';
                html += '<thead><tr><th>Date</th><th>Product</th><th>Qty</th><th>Reason</th><th>Action</th></tr></thead><tbody>';
                
                if (data.returns.length === 0) {
                    html += '<tr><td colspan="5" class="text-center">No returns found</td></tr>';
                } else {
                    data.returns.forEach(ret => {
                        html += `<tr>
                            <td>${formatDate(ret.return_date)}</td>
                            <td>${ret.product_name}</td>
                            <td>${ret.quantity}</td>
                            <td>${ret.reason || 'N/A'}</td>
                            <td><button class="btn btn-sm btn-danger delete-return" data-id="${ret.id}"><i class="fas fa-trash"></i></button></td>
                        </tr>`;
                    });
                }
                
                html += '</tbody></table>';
                $('#recent-returns').html(html);
                
                $('.delete-return').click(function() {
                    const returnId = $(this).data('id');
                    if (confirm('Are you sure you want to delete this return record?')) {
                        deleteReturn(returnId);
                    }
                });
                
                updatePagination('#returns-pagination', page, data.totalPages, loadRecentReturns);
            },
            error: function(xhr) {
                showAlert('Error loading returns: ' + xhr.responseText, 'danger');
            }
        });
    }

    function loadRecentExpenses(page = 1, search = '', date = '') {
        search = search || $('#expenses-search').val();
        date = date || $('#expenses-date').val();
        
        $.ajax({
            url: 'api/get_expenses.php',
            type: 'GET',
            data: {
                page: page,
                search: search,
                date: date
            },
            dataType: 'json',
            success: function(data) {
                let html = '<table class="table table-hover">';
                html += '<thead><tr><th>Date</th><th>Amount</th><th>Description</th><th>Action</th></tr></thead><tbody>';
                
                if (data.expenses.length === 0) {
                    html += '<tr><td colspan="4" class="text-center">No expenses found</td></tr>';
                } else {
                    data.expenses.forEach(expense => {
                        html += `<tr>
                            <td>${formatDate(expense.expense_date)}</td>
                            <td>₦${parseFloat(expense.amount).toFixed(2)}</td>
                            <td>${expense.description || 'N/A'}</td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <button class="btn btn-warning update-expense" data-id="${expense.id}" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-danger delete-expense" data-id="${expense.id}" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>`;
                    });
                }
                
                html += '</tbody></table>';
                $('#recent-expenses').html(html);
                
                $('.update-expense').click(function() {
                    const expenseId = $(this).data('id');
                    populateUpdateExpenseModal(expenseId);
                    $('#updateExpenseModal').modal('show');
                });
                
                $('.delete-expense').click(function() {
                    const expenseId = $(this).data('id');
                    if (confirm('Are you sure you want to delete this expense record?')) {
                        deleteExpense(expenseId);
                    }
                });
                
                updatePagination('#expenses-pagination', page, data.totalPages, loadRecentExpenses);
            },
            error: function(xhr) {
                showAlert('Error loading expenses: ' + xhr.responseText, 'danger');
            }
        });
    }

    function loadAllProducts(page = 1, search = '') {
        search = search || $('#products-search').val();
        
        $.ajax({
            url: 'api/get_products_paginated.php',
            type: 'GET',
            data: {
                page: page,
                search: search
            },
            dataType: 'json',
            success: function(data) {
                let html = '<table class="table table-hover">';
                html += '<thead><tr><th>Name</th><th>Description</th><th>Stock</th><th>Threshold</th><th>Status</th><th>Price</th><th>Actions</th></tr></thead><tbody>';
                
                if (data.products.length === 0) {
                    html += '<tr><td colspan="7" class="text-center">No products found</td></tr>';
                } else {
                    data.products.forEach(product => {
                        const isLowStock = product.current_stock < (product.low_stock_threshold || 200);
                        const statusClass = isLowStock ? 'text-danger' : 'text-success';
                        const statusIcon = isLowStock ? 'fa-exclamation-circle' : 'fa-check-circle';
                        
                        html += `<tr>
                            <td>${product.name}</td>
                            <td>${product.description || 'N/A'}</td>
                            <td>${product.current_stock}</td>
                            <td>${product.low_stock_threshold || 200}</td>
                            <td class="${statusClass}"><i class="fas ${statusIcon} me-1"></i> ${isLowStock ? 'Low Stock' : 'OK'}</td>
                            <td>₦${product.price ? parseFloat(product.price).toFixed(2) : '0.00'}</td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <button class="btn btn-warning update-product" data-id="${product.id}" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-danger delete-product" data-id="${product.id}" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>`;
                    });
                }
                
                html += '</tbody></table>';
                $('#products-list').html(html);
                
                $('.update-product').click(function() {
                    const productId = $(this).data('id');
                    populateUpdateProductModal(productId);
                    $('#updateProductModal').modal('show');
                });
                
                $('.delete-product').click(function() {
                    const productId = $(this).data('id');
                    if (confirm('Are you sure you want to delete this product and all its related records?')) {
                        deleteProduct(productId);
                    }
                });
                
                updatePagination('#products-pagination', page, data.totalPages, loadAllProducts);
            },
            error: function(xhr) {
                showAlert('Error loading products: ' + xhr.responseText, 'danger');
            }
        });
    }

    function populateUpdateProductModal(productId) {
        $.ajax({
            url: 'api/get_product.php',
            type: 'GET',
            data: { id: productId },
            dataType: 'json',
            success: function(data) {
                if (data.success && data.product) {
                    $('#update-product-id').val(data.product.id);
                    $('#update-product-name').val(data.product.name);
                    $('#update-product-description').val(data.product.description || '');
                    $('#update-initial-stock').val(data.product.current_stock || 0);
                    $('#update-product-price').val(data.product.price || 0);
                    $('#update-cost-price').val(data.product.cost_price || 0);
                    $('#update-low-stock-threshold').val(data.product.low_stock_threshold || 200);
                } else {
                    showAlert(data.error || 'Failed to load product details', 'danger');
                    $('#updateProductModal').modal('hide');
                }
            },
            error: function(xhr) {
                showAlert('Error loading product details: ' + xhr.responseText, 'danger');
                $('#updateProductModal').modal('hide');
            }
        });
    }

    function populateUpdateExpenseModal(expenseId) {
        $.ajax({
            url: 'api/get_expense.php',
            type: 'GET',
            data: { id: expenseId },
            dataType: 'json',
            success: function(data) {
                if (data.success && data.expense) {
                    $('#update-expense-id').val(data.expense.id);
                    $('#update-expense-date').val(data.expense.expense_date);
                    $('#update-expense-amount').val(data.expense.amount);
                    $('#update-expense-description').val(data.expense.description || '');
                } else {
                    showAlert(data.error || 'Failed to load expense details', 'danger');
                    $('#updateExpenseModal').modal('hide');
                }
            },
            error: function(xhr) {
                showAlert('Error loading expense details: ' + xhr.responseText, 'danger');
                $('#updateExpenseModal').modal('hide');
            }
        });
    }

    function loadLowStockItems() {
        $.ajax({
            url: 'api/get_low_stock_items.php',
            type: 'GET',
            dataType: 'json',
            beforeSend: function() {
                $('#low-stock-items-list').html('<tr><td colspan="3" class="text-center"><div class="spinner-border text-warning" role="status"></div></td></tr>');
            },
            success: function(response) {
                if (response.success && response.data) {
                    let html = '';
                    
                    if (response.data.length === 0) {
                        html = '<tr><td colspan="3" class="text-center text-muted">No low stock items found</td></tr>';
                    } else {
                        response.data.forEach(product => {
                            html += `<tr>
                                <td>${product.name || 'N/A'}</td>
                                <td class="text-danger fw-bold">${product.current_stock || 0}</td>
                                <td>₦${product.price ? parseFloat(product.price).toFixed(2) : '0.00'}</td>
                            </tr>`;
                        });
                    }
                    
                    $('#low-stock-items-list').html(html);
                } else {
                    showErrorInModal(response.error || 'Invalid response format');
                }
            },
            error: function(xhr) {
                let errorMsg = 'Failed to load low stock items';
                try {
                    const response = JSON.parse(xhr.responseText);
                    errorMsg += ': ' + (response.error || response.message || 'Unknown error');
                } catch (e) {
                    errorMsg += `: ${xhr.statusText} (${xhr.status})`;
                }
                showErrorInModal(errorMsg);
            }
        });
    }

    function generateReport() {
        const reportType = $('#report-type').val();
        const startDate = $('#report-start-date').val();
        const endDate = $('#report-end-date').val();

        if (!reportType || !startDate) {
            showAlert('Please select report type and start date', 'warning');
            return;
        }

        if (reportType === 'custom' && !endDate) {
            showAlert('Please select an end date for custom reports', 'warning');
            return;
        }

        $.ajax({
            url: 'api/get_reports.php',
            type: 'POST',
            contentType: 'application/json',
            data: JSON.stringify({
                report_type: reportType,
                start_date: startDate,
                end_date: endDate
            }),
            dataType: 'json',
            beforeSend: function() {
                $('#report-results').addClass('d-none').html('<p class="text-center my-5">Loading report data...</p>');
            },
            success: function(response) {
                if (response.success) {
                    console.log('Raw summary data:', response.summary);
                    displayReportResults(response);
                    $('#report-results').removeClass('d-none');
                    showAlert('Report generated successfully', 'success');
                } else {
                    showAlert(response.error || 'Failed to generate report', 'danger');
                    $('#report-results').html('<p class="text-center text-danger">Failed to load report. Please try again.</p>');
                }
            },
            error: function(xhr) {
                showAlert('Error generating report: ' + xhr.responseText, 'danger');
                $('#report-results').html('<p class="text-center text-danger">Error loading report. Please try again.</p>');
            }
        });
    }

    function displayReportResults(data) {
        let html = '<div class="p-4 bg-white rounded shadow-sm">';

        const reportTitle = `${capitalize(data.report_type)} Performance Report`;
        const dateRange = data.report_type === 'custom' 
            ? `${formatDate(data.start_date)} to ${formatDate(data.end_date)}`
            : `for ${formatDate(data.start_date)}`;

        html += `<h4 class="mb-2">${reportTitle}</h4>`;
        html += `<p class="text-muted mb-4">This report summarizes business performance ${dateRange}. It includes total sales revenue, profits, expenses, and returns, along with detailed transaction records.</p>`;

        // Summary
        const summary = data.summary;
        html += `
            <h5 class="mt-4 mb-3">Summary</h5>
            <table class="table table-bordered mb-4">
                <thead><tr>
                    <th>Total Sales</th>
                    <th>Total Cost</th>
                    <th>Gross Profit</th>
                    <th>Total Expenses</th>
                    <th>Total Return Losses</th>
                    <th>Total Returns</th>
                    <th>Net Profit</th>
                </tr></thead>
                <tbody><tr>
                    <td>${formatCurrency(summary.total_sales)}</td>
                    <td>${formatCurrency(summary.total_cost)}</td>
                    <td>${formatCurrency(summary.gross_profit)}</td>
                    <td>${formatCurrency(summary.total_expenses)}</td>
                    <td>${formatCurrency(summary.total_return_losses)}</td>
                    <td>${formatNumber(summary.total_returns, true)}</td>
                    <td>${formatCurrency(summary.net_profit)}</td>
                </tr></tbody>
            </table>
        `;

        // Sales
        html += '<h5 class="mt-5 mb-3">Sales Transactions</h5>';
        if (!data.sales.length) {
            html += '<p class="text-muted">No sales recorded for this period.</p>';
        } else {
            html += `
                <table class="table table-hover">
                    <thead><tr>
                        <th>Date</th>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Revenue</th>
                        <th>Cost</th>
                        <th>Gross Profit</th>
                        <th>Salesperson</th>
                    </tr></thead>
                    <tbody>
            `;
            data.sales.forEach(sale => {
                html += `
                    <tr>
                        <td>${formatDate(sale.date)}</td>
                        <td>${sale.product_name}</td>
                        <td>${formatNumber(sale.quantity, true)}</td>
                        <td>${formatCurrency(sale.revenue)}</td>
                        <td>${formatCurrency(sale.cost)}</td>
                        <td>${formatCurrency(sale.gross_profit)}</td>
                        <td>${sale.salesperson}</td>
                    </tr>
                `;
            });
            html += '</tbody></table>';
        }

        // Expenses
        html += '<h5 class="mt-5 mb-3">Expenses</h5>';
        if (!data.expenses.length) {
            html += '<p class="text-muted">No expenses recorded for this period.</p>';
        } else {
            html += `
                <table class="table table-hover">
                    <thead><tr>
                        <th>Date</th>
                        <th>Amount</th>
                        <th>Description</th>
                    </tr></thead>
                    <tbody>
            `;
            data.expenses.forEach(expense => {
                html += `
                    <tr>
                        <td>${formatDate(expense.date)}</td>
                        <td>${formatCurrency(expense.amount)}</td>
                        <td>${expense.description || 'N/A'}</td>
                    </tr>
                `;
            });
            html += '</tbody></table>';
        }

        // Returns
        html += '<h5 class="mt-5 mb-3">Returns</h5>';
        if (!data.returns.length) {
            html += '<p class="text-muted">No returns recorded for this period.</p>';
        } else {
            html += `
                <table class="table table-hover">
                    <thead><tr>
                        <th>Date</th>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Return Loss (₦)</th>
                        <th>Reason</th>
                    </tr></thead>
                    <tbody>
            `;
            data.returns.forEach(ret => {
                html += `
                    <tr>
                        <td>${formatDate(ret.date)}</td>
                        <td>${ret.product_name}</td>
                        <td>${formatNumber(ret.quantity, true)}</td>
                        <td>${formatCurrency(ret.return_loss)}</td>
                        <td>${ret.reason || 'N/A'}</td>
                    </tr>
                `;
            });
            html += '</tbody></table>';
        }

        html += '</div>';
        $('#report-results').html(html);
    }

    // Helpers
    function formatNumber(value, isInteger = false) {
        if (value === null || value === undefined || isNaN(parseFloat(value)) || !isFinite(value)) {
            return isInteger ? '0' : '0.00';
        }
        const num = parseFloat(value);
        return isInteger ? Math.round(num).toString() : num.toFixed(2);
    }

    function formatCurrency(amount) {
        return '₦' + formatNumber(amount).replace(/\d(?=(\d{3})+\.)/g, '$&,');
    }

    function formatDate(dateString) {
        if (!dateString) return 'N/A';
        const date = new Date(dateString);
        return date.toLocaleDateString('en-GB', {
            day: '2-digit',
            month: 'short',
            year: 'numeric'
        });
    }

    function capitalize(text) {
        return text.charAt(0).toUpperCase() + text.slice(1);
    }

    function recordSale() {
        const productId = $('#product-select').val();
        const quantity = $('#sale-quantity').val();
        const saleDate = $('#sale-date').val();
        
        if (!productId || !quantity || !saleDate) {
            showAlert('Please fill all required fields', 'warning');
            return;
        }
        
        $.ajax({
            url: 'api/record_sale.php',
            type: 'POST',
            data: {
                product_id: productId,
                quantity: quantity,
                sale_date: saleDate
            },
            success: function(response) {
                showAlert('Sale recorded successfully', 'success');
                $('#sales-form')[0].reset();
                $('#sale-date').val(new Date().toISOString().split('T')[0]);
                loadTodaySales();
                loadCurrentStock();
                loadProductsForSelects();
                updateDashboardStats();
            },
            error: function(xhr) {
                showAlert('Error recording sale: ' + xhr.responseText, 'danger');
            }
        });
    }

    function addStock() {
        const productId = $('#addstock-product').val();
        const quantity = $('#addstock-quantity').val();
        const additionDate = $('#addstock-date').val();
        
        if (!productId || !quantity || !additionDate) {
            showAlert('Please fill all required fields', 'warning');
            return;
        }
        
        $.ajax({
            url: 'api/add_stock.php',
            type: 'POST',
            data: {
                product_id: productId,
                quantity: quantity,
                addition_date: additionDate
            },
            success: function(response) {
                showAlert('Stock added successfully', 'success');
                $('#addstock-form')[0].reset();
                $('#addstock-date').val(new Date().toISOString().split('T')[0]);
                loadRecentAdditions();
                loadCurrentStock();
                loadProductsForSelects();
                updateDashboardStats();
            },
            error: function(xhr) {
                showAlert('Error adding stock: ' + xhr.responseText, 'danger');
            }
        });
    }

    function recordReturn() {
        const productId = $('#return-product').val();
        const quantity = $('#return-quantity').val();
        const returnDate = $('#return-date').val();
        const reason = $('#return-reason').val();
        
        if (!productId || !quantity || !returnDate) {
            showAlert('Please fill all required fields', 'warning');
            return;
        }
        
        $.ajax({
            url: 'api/record_return.php',
            type: 'POST',
            data: {
                product_id: productId,
                quantity: quantity,
                return_date: returnDate,
                reason: reason
            },
            success: function(response) {
                showAlert('Return recorded successfully', 'success');
                $('#return-form')[0].reset();
                $('#return-date').val(new Date().toISOString().split('T')[0]);
                loadRecentReturns();
                loadCurrentStock();
                loadProductsForSelects();
                updateDashboardStats();
            },
            error: function(xhr) {
                showAlert('Error recording return: ' + xhr.responseText, 'danger');
            }
        });
    }

    function recordExpense() {
        const amount = $('#expense-amount').val();
        const description = $('#expense-description').val();
        const expenseDate = $('#expense-date').val();
        const userId = 1; // Replace with actual user ID from session/auth

        if (!amount || !expenseDate) {
            showAlert('Please fill all required fields', 'warning');
            return;
        }

        $.ajax({
            url: 'api/record_expense.php',
            type: 'POST',
            contentType: 'application/json',
            data: JSON.stringify({
                user_id: userId,
                amount: parseFloat(amount),
                description: description,
                expense_date: expenseDate
            }),
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    showAlert('Expense recorded successfully', 'success');
                    $('#expense-form')[0].reset();
                    $('#expense-date').val(new Date().toISOString().split('T')[0]);
                    loadRecentExpenses();
                } else {
                    showAlert(response.error || 'Failed to record expense', 'danger');
                }
            },
            error: function(xhr) {
                showAlert('Error recording expense: ' + xhr.responseText, 'danger');
            }
        });
    }

    function updateExpense() {
        const expenseId = $('#update-expense-id').val();
        const amount = $('#update-expense-amount').val();
        const description = $('#update-expense-description').val();
        const expenseDate = $('#update-expense-date').val();
        const userId = 1; // Replace with actual user ID from session/auth

        if (!expenseId || !amount || !expenseDate) {
            showAlert('Please fill all required fields', 'warning');
            return;
        }

        $.ajax({
            url: 'api/update_expense.php',
            type: 'POST',
            contentType: 'application/json',
            data: JSON.stringify({
                id: expenseId,
                user_id: userId,
                amount: parseFloat(amount),
                description: description,
                expense_date: expenseDate
            }),
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    showAlert('Expense updated successfully', 'success');
                    $('#updateExpenseModal').modal('hide');
                    $('#update-expense-form')[0].reset();
                    loadRecentExpenses();
                } else {
                    showAlert(response.error || 'Failed to update expense', 'danger');
                }
            },
            error: function(xhr) {
                showAlert('Error updating expense: ' + xhr.responseText, 'danger');
            }
        });
    }

    function addProduct() {
        const name = $('#product-name').val();
        const description = $('#product-description').val();
        const stock = $('#initial-stock').val() || 0;
        const price = $('#product-price').val() || 0;
        const costPrice = $('#cost-price').val() || 0;
        const threshold = $('#low-stock-threshold').val() || 200;

        if (!name) {
            showAlert('Product name is required', 'warning');
            return;
        }

        if (costPrice > price) {
            showAlert('Cost price cannot be greater than selling price', 'warning');
            return;
        }

        $.ajax({
            url: 'api/add_product.php',
            type: 'POST',
            data: {
                name: name,
                description: description,
                stock: stock,
                price: price,
                cost_price: costPrice,
                low_stock_threshold: threshold
            },
            success: function(response) {
                if (response.success) {
                    showAlert('Product added successfully', 'success');
                    $('#addProductModal').modal('hide');
                    $('#add-product-form')[0].reset();
                    loadAllProducts();
                    loadProductsForSelects();
                    loadCurrentStock();
                    updateDashboardStats();
                } else {
                    showAlert(response.error || 'Failed to add product', 'danger');
                }
            },
            error: function(xhr) {
                showAlert('Error adding product: ' + xhr.responseText, 'danger');
            }
        });
    }

    function updateProduct() {
        const productId = $('#update-product-id').val();
        const name = $('#update-product-name').val();
        const description = $('#update-product-description').val();
        const stock = $('#update-initial-stock').val();
        const price = $('#update-product-price').val();
        const costPrice = $('#update-cost-price').val();
        const threshold = $('#update-low-stock-threshold').val();

        if (!productId || !name || !stock || !price || !costPrice || !threshold) {
            showAlert('Please fill all required fields', 'warning');
            return;
        }

        if (parseFloat(costPrice) > parseFloat(price)) {
            showAlert('Cost price cannot be greater than selling price', 'warning');
            return;
        }

        $.ajax({
            url: 'api/update_product.php',
            type: 'POST',
            data: {
                id: productId,
                name: name,
                description: description,
                stock: stock,
                price: price,
                cost_price: costPrice,
                low_stock_threshold: threshold
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    showAlert('Product updated successfully', 'success');
                    $('#updateProductModal').modal('hide');
                    $('#update-product-form')[0].reset();
                    loadAllProducts();
                    loadProductsForSelects();
                    loadCurrentStock();
                    updateDashboardStats();
                } else {
                    showAlert(response.error || 'Failed to update product', 'danger');
                }
            },
            error: function(xhr) {
                showAlert('Error updating product: ' + xhr.responseText, 'danger');
            }
        });
    }

    function deleteSale(saleId) {
        if (!confirm('Are you sure you want to delete this sale record?')) {
            return;
        }
        
        $.ajax({
            url: 'api/delete_sale.php',
            type: 'POST',
            data: { id: saleId },
            success: function(response) {
                showAlert('Sale deleted successfully', 'success');
                loadTodaySales();
                loadCurrentStock();
                updateDashboardStats();
            },
            error: function(xhr) {
                showAlert('Error deleting sale: ' + xhr.responseText, 'danger');
            }
        });
    }

    function deleteStockAddition(additionId) {
        if (!confirm('Are you sure you want to delete this stock addition?')) {
            return;
        }
        
        $.ajax({
            url: 'api/delete_addition.php',
            type: 'POST',
            data: { id: additionId },
            success: function(response) {
                showAlert('Stock addition deleted successfully', 'success');
                loadRecentAdditions();
                loadCurrentStock();
                updateDashboardStats();
            },
            error: function(xhr) {
                showAlert('Error deleting addition: ' + xhr.responseText, 'danger');
            }
        });
    }

    function deleteReturn(returnId) {
        if (!confirm('Are you sure you want to delete this return record?')) {
            return;
        }
        
        $.ajax({
            url: 'api/delete_return.php',
            type: 'POST',
            data: { id: returnId },
            success: function(response) {
                showAlert('Return deleted successfully', 'success');
                loadRecentReturns();
                loadCurrentStock();
                updateDashboardStats();
            },
            error: function(xhr) {
                showAlert('Error deleting return: ' + xhr.responseText, 'danger');
            }
        });
    }

    function deleteExpense(expenseId) {
        if (!confirm('Are you sure you want to delete this expense record?')) {
            return;
        }
        
        $.ajax({
            url: 'api/delete_expense.php',
            type: 'POST',
            data: { id: expenseId },
            success: function(response) {
                showAlert('Expense deleted successfully', 'success');
                loadRecentExpenses();
            },
            error: function(xhr) {
                showAlert('Error deleting expense: ' + xhr.responseText, 'danger');
            }
        });
    }

    function deleteProduct(productId) {
        if (!confirm('Are you sure you want to delete this product and all its related records?')) {
            return;
        }
        
        $.ajax({
            url: 'api/delete_product.php',
            type: 'POST',
            data: { id: productId },
            success: function(response) {
                showAlert('Product deleted successfully', 'success');
                loadAllProducts();
                loadProductsForSelects();
                loadCurrentStock();
                updateDashboardStats();
            },
            error: function(xhr) {
                showAlert('Error deleting product: ' + xhr.responseText, 'danger');
            }
        });
    }

    function updateDashboardStats() {
        $.ajax({
            url: 'api/get_dashboard_stats.php',
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                $('#total-products').text(data.total_products);
                $('#today-sales-count').text(data.today_sales);
                $('#low-stock-count').text(data.low_stock_items);
                
                if (data.low_stock_items > 0) {
                    $('#low-stock-card').addClass('animate-pulse');
                } else {
                    $('#low-stock-card').removeClass('animate-pulse');
                }
            },
            error: function(xhr) {
                console.error('Error loading dashboard stats:', xhr.responseText);
            }
        });
    }

    function updatePagination(selector, currentPage, totalPages, callback) {
        let html = '';
        const maxVisible = 5;
        let startPage, endPage;
        
        if (totalPages <= 1) {
            $(selector).empty();
            return;
        }
        
        if (totalPages <= maxVisible) {
            startPage = 1;
            endPage = totalPages;
        } else {
            const maxVisibleBefore = Math.floor(maxVisible / 2);
            const maxVisibleAfter = Math.ceil(maxVisible / 2) - 1;
            
            if (currentPage <= maxVisibleBefore) {
                startPage = 1;
                endPage = maxVisible;
            } else if (currentPage + maxVisibleAfter >= totalPages) {
                startPage = totalPages - maxVisible + 1;
                endPage = totalPages;
            } else {
                startPage = currentPage - maxVisibleBefore;
                endPage = currentPage + maxVisibleAfter;
            }
        }
        
        if (currentPage > 1) {
            html += `<li class="page-item"><a class="page-link" href="#" data-page="${currentPage - 1}"><i class="fas fa-chevron-left"></i></a></li>`;
        } else {
            html += `<li class="page-item disabled"><span class="page-link"><i class="fas fa-chevron-left"></i></span></li>`;
        }
        
        if (startPage > 1) {
            html += `<li class="page-item"><a class="page-link" href="#" data-page="1">1</a></li>`;
            if (startPage > 2) {
                html += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
            }
        }
        
        for (let i = startPage; i <= endPage; i++) {
            if (i === currentPage) {
                html += `<li class="page-item active"><span class="page-link">${i}</span></li>`;
            } else {
                html += `<li class="page-item"><a class="page-link" href="#" data-page="${i}">${i}</a></li>`;
            }
        }
        
        if (endPage < totalPages) {
            if (endPage < totalPages - 1) {
                html += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
            }
            html += `<li class="page-item"><a class="page-link" href="#" data-page="${totalPages}">${totalPages}</a></li>`;
        }
        
        if (currentPage < totalPages) {
            html += `<li class="page-item"><a class="page-link" href="#" data-page="${currentPage + 1}"><i class="fas fa-chevron-right"></i></a></li>`;
        } else {
            html += `<li class="page-item disabled"><span class="page-link"><i class="fas fa-chevron-right"></i></span></li>`;
        }
        
        $(selector).html(html);
        
        $(selector + ' .page-link[data-page]').click(function(e) {
            e.preventDefault();
            callback(parseInt($(this).data('page')));
        });
    }

    function showAlert(message, type) {
        const alert = $(`<div class="alert alert-${type} alert-dismissible fade show alert-animate position-fixed top-0 start-50 translate-middle-x mt-3" style="z-index: 9999;">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>`);
        
        $('body').append(alert);
        
        setTimeout(() => {
            alert.alert('close');
        }, 3000);
    }

    function showErrorInModal(message) {
        $('#low-stock-items-list').html(
            `<tr>
                <td colspan="3" class="text-center text-danger">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    ${message}
                    <div class="mt-2">
                        <button class="btn btn-sm btn-warning" onclick="loadLowStockItems()">
                            <i class="fas fa-sync-alt me-1"></i> Retry
                        </button>
                    </div>
                </td>
            </tr>`
        );
    }

    function formatDate(dateString) {
        if (!dateString) return '';
        const date = new Date(dateString);
        return date.toLocaleDateString('en-GB');
    }

    function formatTime(datetimeString) {
        if (!datetimeString) return '';
        const date = new Date(datetimeString);
        return date.toLocaleTimeString('en-GB', { hour: '2-digit', minute: '2-digit' });
    }

    function debounce(func, wait) {
        let timeout;
        return function() {
            const context = this, args = arguments;
            clearTimeout(timeout);
            timeout = setTimeout(() => {
                func.apply(context, args);
            }, wait);
        };
    }
    function printReport() {
    if ($('#report-results').hasClass('d-none')) {
        showAlert('Please generate a report first', 'warning');
        return;
    }
    window.print();
}
</script>
</html>

