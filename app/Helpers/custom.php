<?php
define('LOGO_PATH', 'logo/');
define('USER_PHOTO_PATH', 'user/');
define('PRODUCT_IMAGE_PATH', 'product/');
define('EMPLOYEE_NID_PHOTO', 'employee/');
define('EMPLOYEE_IMAGE_PATH', 'employee/');
define('PURCHASE_DOCUMENT_PATH', 'purchase-document/');
define('SALE_DOCUMENT_PATH', 'sale-document/');
define('ASSET_PHOTO_PATH', 'asset-photo/');
define('VOUCHER_TYPE_VALUE', [
    'DEBIT-VOUCHER'    => 'DEBIT-VOUCHER',
    'CREDIT-VOUCHER'   => 'CREDIT-VOUCHER',
    'CONTRA-VOUCHER'   => 'CONTRA-VOUCHER',
    'JOURNAL-VOUCHER'  => 'JOURNAL-VOUCHER'
]);
define('PRODUCT_LEDGER_TYPE', [
    '1'      => 'General Sale',
    '2'      => 'Walking Sale',
    '3'      => 'Sale',
    '4'      => 'General Purchase',
    '5'      => 'Walking Purchase',
    '6'      => 'Purchase',
    '7'      => 'Production Use Product',
    '8'      => 'Production Stock',
    '9'      => 'Production Sale'
]);
define('OPENING_BALANCE_VALUE', [
    '1'  => 'Receivable',
    '2'  => 'Payable',
]);
define('OPENING_STOCK_VALUE', [
    '1'  => 'Yes',
    '2'  => 'No',
]);

define('TENANT_PRODUCTION_LABEL', [
    '1'  => '<span class="label label-primary label-pill label-inline" style="min-width:100px !important;">Raw</span>',
    '2'  => '<span class="label label-success label-pill label-inline" style="min-width:100px !important;">Ready Product</span>',
]);

define('OPENING_STOCK_LABLE', [
    '1'  => '<span class="label label-primary label-pill label-inline" style="min-width:100px !important;">Yes</span>',
    '2'  => '<span class="label label-danger label-pill label-inline" style="min-width:100px !important;">No</span>',
]);
define('VOUCHER_TYPE_LABEL', [
    'DEBIT-VOUCHER'    => '<span class="label label-primary label-pill label-inline" style="min-width:100px !important;">Current Asset</span>',
    'CREDIT-VOUCHER'   => '<span class="label label-success label-pill label-inline" style="min-width:100px !important;">Non-Current Asset</span>',
    'CONTRA-VOUCHER'   => '<span class="label label-secondary label-pill label-inline" style="min-width:100px !important;">Current Liabilities</span>',
    'JOURNAL-VOUCHER'  => '<span class="label label-danger label-pill label-inline" style="min-width:100px !important;">Non-Current Liabilities</span>',
]);
define('BALANCE_TYPE_VALUE', [
    '1'  => 'Receivable',
    '2'  => 'Payable',
    '3'  => 'No Balance',
]);
define('BALANCE_TYPE_LABEL', [
    '1'  => '<span class="label label-info label-pill label-inline" style="min-width:100px !important;">Receivable</span>',
    '2'  => '<span class="label label-success label-pill label-inline" style="min-width:100px !important;">Payable</span>',
    '3'  => '<span class="label label-warning label-pill label-inline" style="min-width:100px !important;">No Balance</span>',
]);
define('MASTER_HEAD_VALUE', [
    '1'  => 'Current Asset',
    '2'  => 'Non-Current Asset',
    '3'  => 'Current Liabilities',
    '4'  => 'Non-Current Liabilities',
    '5'  => 'Income',
    '6'  => 'Cost Of Goods Sold',
    '7'  => 'Expenses',
    '8'  => 'Equity'
]);
define('MASTER_HEAD_LABEL', [
    '1'  => '<span class="label label-primary label-pill label-inline" style="min-width:100px !important;">Current Asset</span>',
    '2'  => '<span class="label label-success label-pill label-inline" style="min-width:100px !important;">Non-Current Asset</span>',
    '3'  => '<span class="label label-secondary label-pill label-inline" style="min-width:100px !important;">Current Liabilities</span>',
    '4'  => '<span class="label label-danger label-pill label-inline" style="min-width:100px !important;">Non-Current Liabilities</span>',
    '5'  => '<span class="label label-info label-pill label-inline" style="min-width:100px !important;">Income</span>',
    '6'  => '<span class="label label-info label-pill label-inline" style="min-width:100px !important;">Cost Of Goods Sold</span>',
    '7'  => '<span class="label label-warning label-pill label-inline" style="min-width:100px !important;">Expenses</span>',
    '8'  => '<span class="label label-dark label-pill label-inline" style="min-width:100px !important;">Equity</span>',
]);
define('HEAD_TYPE', [
    '1'  => '<span class="label label-info label-pill label-inline" style="min-width:100px !important;">Head</span>',
    '2'  => '<span class="label label-success label-pill label-inline" style="min-width:100px !important;">Sub Head</span>',
    '3'  => '<span class="label label-warning label-pill label-inline" style="min-width:100px !important;">Child Head</span>',
]);
define('MERGE_TYPE_VALUE', [
    '1'  => 'Milling',
    '2'  => 'Delivery',
    '3'  => 'Stock',
]);
define('SALE_TYPE_VALUE', [
    '1'  => 'Regular',
    '2'  => 'Pre Order'
]);
define('SALE_TYPE_LABEL', [
    '1' => '<span class="label label-success label-pill label-inline" style="min-width:70px !important;">Regular</span>',
    '2' => '<span class="label label-danger label-pill label-inline" style="min-width:70px !important;">Pre Order</span>'
]);
define('PARTY_TYPE_VALUE', [
    '1'  => 'General',
    '2'  => 'Walking'
]);
define('PARTY_TYPE_LABEL', [
    '1' => '<span class="label label-success label-pill label-inline" style="min-width:100px !important;">General</span>',
    '2' => '<span class="label label-danger label-pill label-inline" style="min-width:100px !important;">Walking</span>'
]);
define('STOCK_TRANSFER_VALUE', [
    '1'  => 'Transfer',
    '2'  => 'Received'
]);
define('STOCK_TRANSFER_LABEL', [
    '1' => '<span class="label label-success label-pill label-inline" style="min-width:70px !important;">Transfer</span>',
    '2' => '<span class="label label-danger label-pill label-inline" style="min-width:70px !important;">Received</span>'
]);
define('STOCK_TRANSFER_ITEM_VALUE', [
    '1'  => 'Product',
]);
define('REQUISITION_STATUS_LABEL', [
    '1' => '<span class="label label-success label-pill label-inline" style="min-width:70px !important;">Approved</span>',
    '2' => '<span class="label label-danger label-pill label-inline" style="min-width:70px !important;">Pending</span>'
]);
define('REQUISITION_STATUS_VALUE', [
    '1'  => 'Approved',
    '2'  => 'Pending'
]);
define('TYPE_STATUS_LABEL', [
    '1' => '<span class="label label-success label-pill label-inline" style="min-width:70px !important;">Requisition</span>',
    '2' => '<span class="label label-info label-pill label-inline" style="min-width:70px !important;">Purchase</span>'
]);
define('TYPE_STATUS_VALUE', [
    '1' => 'Requisition',
    '2' => 'Purchase'
]);
define('PURCHASE_TYPE_STATUS_LABEL', [
    '1' => '<span class="label label-success label-pill label-inline" style="min-width:70px !important;">Requisition</span>',
    '2' => '<span class="label label-info label-pill label-inline" style="min-width:70px !important;">Purchase</span>'
]);
define('PURCHASE_TYPE_STATUS_VALUE', [
    '1' => 'Requisition',
    '2' => 'Purchase'
]);
define('STOCK_TRANSFER_ITEM_LABEL', [
    '1' => '<span class="label label-success label-pill label-inline" style="min-width:70px !important;">Product</span>',
    '2' => '<span class="label label-danger label-pill label-inline" style="min-width:70px !important;">Material</span>'
]);
define('SALE_STATUS_VALUE', [
    '1'  => 'Ordered',
    '2'  => 'Pending',
    '3'  => 'Rejected',
    '4'  => 'Approved'
]);
define('SALE_STATUS_LABEL', [
    '1' => '<span class="label label-success label-pill label-inline" style="min-width:100px !important;">Ordered</span>',
    '2' => '<span class="label label-danger label-pill label-inline" style="min-width:100px !important;">Pending</span>',
    '3' => '<span class="label label-info label-pill label-inline" style="min-width:100px !important;">Rejected</span>',
    '4' => '<span class="label label-info label-pill label-inline" style="min-width:100px !important;">Approved</span>',
]);
define('DISTRIBUTION_STATUS_VALUE', [
    '1'  => 'Ordered',
    '2'  => 'Pending',
    '3'  => 'Rejected',
    '4'  => 'Approved'
]);
define('DISTRIBUTION_STATUS_LABEL', [
    '1' => '<span class="label label-success label-pill label-inline" style="min-width:100px !important;">Approved</span>',
    '2' => '<span class="label label-danger label-pill label-inline" style="min-width:100px !important;">Pending</span>',
]);
define('VOUCHER_APPROVE_STATUS_LABEL', [
    '1' => '<span class="label label-success label-pill label-inline" style="min-width:70px !important;">Approved</span>',
    '2' => '<span class="label label-danger label-pill label-inline" style="min-width:70px !important;">Rejected</span>',
    '3' => '<span class="label label-info label-pill label-inline" style="min-width:70px !important;">Pending</span>',
]);
define('GENDER_LABEL', [
    '1' => '<span class="label label-primary label-pill label-inline" style="min-width:70px !important;">Male</span>',
    '2' => '<span class="label label-info label-pill label-inline" style="min-width:70px !important;">Female</span>',
    '3' => '<span class="label label-warning label-pill label-inline" style="min-width:70px !important;">Other</span>',
]);
define('STATUS', ['1' => 'Active', '2' => 'Inactive']);
define('PRODUCT_TYPE', ['1' => 'Standard', '2' => 'Combo', '3' => 'Digital']);
define('APPROVE_STATUS', ['1' => 'Approved', '2' => 'Canceled', '3' => 'Pending']);
define('SUPPLIER_TYPE', ['1' => 'Material', '2' => 'Machine']);
define('TAX_METHOD', ['1' => 'Exclusive', '2' => 'Inclusive']);
define('STATUS_LABEL', [
    '1' => '<span class="label label-success label-pill label-inline" style="min-width:70px !important;">Active</span>',
    '2' => '<span class="label label-danger label-pill label-inline" style="min-width:70px !important;">Inactive</span>',
]);
define('PRE_POST_LABEL', [
    '1' => '<span class="label label-success label-pill label-inline" style="min-width:70px !important;">Pre</span>',
    '2' => '<span class="label label-danger label-pill label-inline" style="min-width:70px !important;">Post</span>',
]);
define('PAID_UNPAID_LABEL', [
    '1' => '<span class="label label-success label-pill label-inline" style="min-width:70px !important;">Unpaid</span>',
    '2' => '<span class="label label-danger label-pill label-inline" style="min-width:70px !important;">Paid</span>',
]);
define('ALLOWANCE_DEDUCTION_LABEL', [
    '1' => '<span class="label label-success label-pill label-inline" style="min-width:70px !important;">Allowance</span>',
    '2' => '<span class="label label-danger label-pill label-inline" style="min-width:70px !important;">Deduction</span>',
    '3' => '<span class="label label-info label-pill label-inline" style="min-width:70px !important;">Others</span>',
]);
define('DAY_NIGHT_LABEL', [
    '1' => '<span class="label label-success label-pill label-inline" style="min-width:70px !important;">Day</span>',
    '2' => '<span class="label label-danger label-pill label-inline" style="min-width:70px !important;">Night</span>',
]);
define('SUPPLIER_TYPE_LABEL', [
    '1' => '<span class="label label-primary label-pill label-inline" style="min-width:70px !important;">Material</span>',
    '2' => '<span class="label label-danger label-pill label-inline" style="min-width:70px !important;">Machine</span>',
]);
define('APPROVE_STATUS_LABEL', [
    '1' => '<span class="label label-success label-pill label-inline" style="min-width:70px !important;">Approved</span>',
    '2' => '<span class="label label-danger label-pill label-inline" style="min-width:70px !important;">Rejected</span>',
    '3' => '<span class="label label-info label-pill label-inline" style="min-width:70px !important;">Pending</span>',
]);
define('STOCK_STATUS_LABEL', [
    '1' => '<span class="label label-success label-pill label-inline" style="min-width:70px !important;">Available</span>',
    '2' => '<span class="label label-danger label-pill label-inline" style="min-width:70px !important;">Out of Stock</span>',
]);
define('DELETABLE_LABEL', [
    '1' => '<span class="label label-danger label-pill label-inline" style="min-width:70px !important;">No</span>',
    '2' => '<span class="label label-success label-pill label-inline" style="min-width:70px !important;">Yes</span>',
]);
define('BARCODE_SYMBOL', ([
    "C128"  => "Code 128",
    "C39"   => "Code 39",
    "UPCA"  => "UPC-A",
    "UPCE"  => "UPC-E",
    "EAN8"  => "EAN-8",
    "EAN13" => "EAN-13"
]));
define('ASSET_STATUS', ([
    "1" => "Good",
    "2" => "Broken",
    "3" => "Recycled",
    "4" => "On Service",
    "5" => "Archived",
]));
define('BATCH_DISTRIBUTION_TYPE', [
    '1' => '<span class="label label-danger label-pill label-inline" style="min-width:70px !important;">Bulk</span>',
    '2' => '<span class="label label-success label-pill label-inline" style="min-width:70px !important;">Packing</span>',
]);
define('PRODUCTION_STATUS', ['1' => 'Pending', '2' => 'Cancel', '3' => 'Processing', '4' => 'Finished']);
define('PRODUCTION_STATUS_LABEL', [
    '1' => '<span class="label label-danger label-pill label-inline" style="min-width:70px !important;">Pending</span>',
    '2' => '<span class="label label-info label-pill label-inline" style="min-width:70px !important;">Cancel</span>',
    '3' => '<span class="label label-warning label-pill label-inline" style="min-width:70px !important;">Processing</span>',
    '4' => '<span class="label label-success label-pill label-inline" style="min-width:70px !important;">Finished</span>',
]);
define('PRODUCTION_MATERIAL_DELIVERY_STATUS', ['1' => 'Pending', '2' => 'Full Delivered', '3' => 'Partially Delivered']);
define('PRODUCTION_MATERIAL_DELIVERY_LABEL', [
    '1' => '<span class="label label-danger label-pill label-inline" style="min-width:70px !important;">Pending</span>',
    '2' => '<span class="label label-success label-pill label-inline" style="min-width:70px !important;">Delivered</span>',
    '3' => '<span class="label label-warning label-pill label-inline" style="min-width:70px !important;">Partially Delivered</span>'
]);
define('PRODUCTION_MATERIAL_RECEIVE_STATUS', ['1' => 'Pending', '2' => 'Received', '3' => 'Partially Received']);
define('PRODUCTION_MATERIAL_RECEIVE_LABEL', [
    '1' => '<span class="label label-danger label-pill label-inline" style="min-width:70px !important;">Pending</span>',
    '2' => '<span class="label label-success label-pill label-inline" style="min-width:70px !important;">Received</span>',
    '3' => '<span class="label label-warning label-pill label-inline" style="min-width:70px !important;">Partially Received</span>'
]);
define('DAYS', ['1' => 'Saturday', '2' => 'Sunday', '3' => 'Monday', '4' => 'Tuesday', '5' => 'Wednesday', '6' => 'Thursday']);
define('PURCHASE_STATUS_VALUE', ['1' => 'Ordered', '2' => 'Pending', '3' => 'Reject', '4' => 'Approved']);
define('PURCHASE_STATUS_LABEL', [
    '1' => '<span class="label label-success label-pill label-inline" style="min-width:70px !important;">Ordered</span>',
    '2' => '<span class="label label-warning label-pill label-inline" style="min-width:70px !important;">Pending</span>',
    '3' => '<span class="label label-danger label-pill label-inline" style="min-width:70px !important;">Reject</span>',
    '4' => '<span class="label label-info label-pill label-inline" style="min-width:70px !important;">Approved</span>',
]);
define('PAYMENT_STATUS', ['1' => 'Paid', '2' => 'Partial', '3' => 'Due']);
define('PAYMENT_STATUS_LABEL', [
    '1' => '<span class="label label-success label-pill label-inline" style="min-width:70px !important;">Paid</span>',
    '2' => '<span class="label label-info label-pill label-inline" style="min-width:70px !important;">Partial</span>',
    '3' => '<span class="label label-danger label-pill label-inline" style="min-width:70px !important;">Due</span>',
]);
define('PAYMENT_METHOD', ['1' => 'Cash', '2' => 'Cheque', '3' => 'Mobile Bank']);
define('SALE_PAYMENT_METHOD', ['1' => 'Cash', '2' => 'Bank', '3' => 'Mobile Bank']);
define('DELIVERY_STATUS', ['1' => 'Pending', '2' => 'Partially Delivered', '3' => 'Delivered']);
define('MAIL_MAILER', (['smtp', 'sendmail', 'mail']));
define('MAIL_ENCRYPTION', (['none' => 'null', 'tls' => 'tls', 'ssl' => 'ssl']));
//Production Batch Constant
define('FLAG', [
    '1'     => 'Mixing',
    '2'     => 'Dying',
    '3'     => 'Roasting',
    '4'     => 'Distribution'
]);
//Expense Type Constant
define('EXPENSE_TYPE_VALUE', [
    '1'      => 'Production Expense',
    '2'      => 'Other Expense',
    '3'      => 'Transport Expense'
]);
define('EXPENSE_TYPE_LABEL', [
    '1' => '<span class="label label-success label-pill label-inline" style="min-width:70px !important;">Production Expense</span>',
    '2' => '<span class="label label-info label-pill label-inline" style="min-width:70px !important;">Other Expense</span>',
    '3' => '<span class="label label-danger label-pill label-inline" style="min-width:70px !important;">Transport Expense</span>',
]);
//Employee Form Constant
define('JOB_STATUS', ['1' => 'Permanent', '2' => 'Probation', '3' => 'Resigned', '4' => 'Suspended']);
define('DUTY_TYPE', ['1' => 'Full Time', '2' => 'Part Time', '3' => 'Contractual', '4' => 'Other']);
define('RATE_TYPE', ['1' => 'Hourly', '2' => 'Salary']);
define('PAY_FREQUENCY', ['1' => 'Weekly', '2' => 'Biweekly', '3' => 'Monthly', '4' => 'Annual']);
define('GENDER', ['1' => 'Male', '2' => 'Female', '3' => 'Other']);
define('MARITAL_STATUS', ['1' => 'Single', '2' => 'Married', '3' => 'Divorced', '4' => 'Widowed', '5' => 'Other']);
define('BLOOD_GROUP', ['1' => 'A+', '2' => 'B+', '3' => 'A-', '4' => 'B-', '5' => 'AB+', '6' => 'AB-', '7' => 'O+', '8' => 'O-']);
define('IS_SUPERVISOR', ['1' => 'Yes', '2' => 'No']);
define('OVERTIME', ['1' => 'Allowed', '2' => 'Not Allowed']);
define('RESIDENTIAL_STATUS', ['1' => 'Resident', '2' => 'Non Resident']);

if (!function_exists('permission')) {
    function permission(string $value)
    {
        if (collect(\Illuminate\Support\Facades\Session::get('user_permission'))->contains($value)) {
            return true;
        }
        return false;
    }
}

if (!function_exists('change_status')) {
    function change_status(int $id, int $status, string $name)
    {
        if ($id && $status && $name) {
            return $status == 1 ? '<span class="label label-success label-pill label-inline change_status" data-id="' . $id . '" data-name="' . $name . '" data-status="2" style="min-width:70px !important;cursor:pointer;">Active</span>'
                : '<span class="label label-danger label-pill label-inline change_status" data-id="' . $id . '" data-name="' . $name . '" data-status="1"  style="min-width:70px !important;cursor:pointer;">Inactive</span>';
        }
    }
}

if (!function_exists('action_button')) {
    function action_button($action)
    {
        return '<div class="dropdown">
                    <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-th-list text-white"></i>
                    </button>
                    <div class="dropdown-menu" style = "max-height: 180px;overflow-y: auto;" aria-labelledby="dropdownMenuButton">
                    ' . $action . '
                    </div>
                </div>';
    }
}

if (!function_exists('row_checkbox')) {
    function row_checkbox($id)
    {
        return '<div class="custom-control custom-checkbox">
                    <input type="checkbox" value="' . $id . '"
                    class="custom-control-input select_data" onchange="select_single_item()" id="checkbox' . $id . '">
                    <label class="custom-control-label" for="checkbox' . $id . '"></label>
                </div>';
    }
}

if (!function_exists('generator')) {
    function generator($lenth)
    {
        $number = array("A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "N", "M", "O", "P", "Q", "R", "S", "U", "V", "T", "W", "X", "Y", "Z", "1", "2", "3", "4", "5", "6", "7", "8", "9", "0");

        for ($i = 0; $i < $lenth; $i++) {
            $rand_value = rand(0, 34);
            $rand_number = $number["$rand_value"];

            if (empty($con)) {
                $con = $rand_number;
            } else {
                $con = "$con" . "$rand_number";
            }
        }
        return $con;
    }
}
define('EVENT_STATUS', ['1' => 'Event', '2' => 'Holiday']);
define('EVENT_STATUS_LABEL', [
    '1' => '<span class="label label-success label-pill label-inline" style="min-width:70px !important;">Event</span>',
    '2' => '<span class="label label-info label-pill label-inline" style="min-width:70px !important;">Holiday</span>'
]);

define('ALLOWANCE_DEPARTMENT', ['1' => 'Employee', '2' => 'Labor']);
define('ALLOWANCE_DEPARTMENT_LABEL', [
    '1' => '<span class="label label-success label-pill label-inline" style="min-width:70px !important;">Employee</span>',
    '2' => '<span class="label label-info label-pill label-inline" style="min-width:70px !important;">Labor</span>'
]);

define('LEAVE_STATUS', ['1' => 'Pending', '2' => 'Approved', '3' => 'Deleted']);
define('LEAVE_STATUS_LABEL', [
    '1' => '<span class="label label-success label-pill label-inline" style="min-width:70px !important;">Pending</span>',
    '2' => '<span class="label label-info label-pill label-inline" style="min-width:70px !important;">Approved</span>',
    '3' => '<span class="label label-danger label-pill label-inline" style="min-width:70px !important;">Deleted</span>'
]);

function salaryYears()
{
    $lastFiveYears = [];
    $currentYear = date('Y');

    for ($i = 0; $i < 5; $i++) {
        $lastFiveYears[] = $currentYear - $i;
    }
    return $lastFiveYears;
}

function allMonths()
{
    $months = [
        1 => "January",
        2 => "February",
        3 => "March",
        4 => "April",
        5 => "May",
        6 => "June",
        7 => "July",
        8 => "August",
        9 => "September",
        10 => "October",
        11 => "November",
        12 => "December"
    ];
    return $months;
}

define('DAILY_ATTENDENCE', ['1' => 'Pending', '2' => 'Approved']);
define('DAILY_ATTENDENCE_LABEL', [
    '1' => '<span class="label label-success label-pill label-inline" style="min-width:70px !important;">Pending</span>',
    '2' => '<span class="label label-info label-pill label-inline" style="min-width:70px !important;">Approved</span>'
]);
define('EMPLOYEE_TYPE_LABEL', [
    '1' => '<span class="label label-success label-pill label-inline" style="min-width:70px !important;">Provision</span>',
    '2' => '<span class="label label-danger label-pill label-inline" style="min-width:70px !important;">Permanent</span>',
    '3' => '<span class="label label-info label-pill label-inline" style="min-width:70px !important;">Full Time</span>',
    '4' => '<span class="label label-info label-pill label-inline" style="min-width:70px !important;">Part Time</span>',
]);
define('TYPE_STATUS1', ['1' => 'Event', '2' => 'Holiday']);
define('TYPE_STATUS1_LABEL', [
    '1' => '<span class="label label-success label-pill label-inline" style="min-width:70px !important;">Event</span>',
    '2' => '<span class="label label-info label-pill label-inline" style="min-width:70px !important;">Holiday</span>'
]);
