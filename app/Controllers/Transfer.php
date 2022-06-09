<?php
session_start();
require "../../init.php";

// Company Model object
$company = new Company();
$bussiness = new Bussiness();
// Banks account
$bank = new Banks();
// Transfer 
$transfer = new Transfer();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $loged_user = json_decode($_SESSION["bussiness_user"]);

    $company_FT_data = $company->getCompanyActiveFT($loged_user->company_id);
    $company_ft = $company_FT_data->fetch(PDO::FETCH_OBJ);

    // Add new out transfer
    if (isset($_POST["addouttransfer"])) {
        $loged_user = json_decode($_SESSION["bussiness_user"]);
        $company_id = $loged_user->company_id;

        $details = helper::test_input($_POST["details"]);
        $date = helper::test_input($_POST["date"]);
        $newdate = strtotime($date);
        $transfercode = helper::test_input($_POST["transfercode"]);
        $vouchercode = helper::test_input($_POST["vouchercode"]);
        $rsaraf_ID = helper::test_input($_POST["rsaraf_ID"]);
        $currency = helper::test_input($_POST["currency"]);
        $amount = helper::test_input($_POST["amount"]);
        $mycommission = helper::test_input($_POST["mycommission"]);
        $sarafcommission = helper::test_input($_POST["sarafcommission"]);

        // Daily Customer sender 
        $Daily_sender_id = 0;
        if ($_POST["addsender"] == "true") {
            $sender_phone = helper::test_input($_POST["sender_phone"]);
            $sender_fname = helper::test_input($_POST["sender_fname"]);
            $sender_lname = helper::test_input($_POST["sender_lname"]);
            $sender_Fathername = helper::test_input($_POST["sender_Fathername"]);
            $sender_nid = helper::test_input($_POST["sender_nid"]);
            $sender_details = helper::test_input($_POST["sender_details"]);
            $Daily_sender_id = $bussiness->addDailyCustomer([$sender_fname, $sender_lname, $sender_Fathername, $sender_phone, $sender_nid, $sender_details, 'Daily Customer', time(), $loged_user->user_id, 1]);
        } else {
            $daily_sender_data = $bussiness->GetDailyCustomer(helper::test_input($_POST["sender_phone"]));
            $daily_sender_details = $daily_sender_data->fetch(PDO::FETCH_OBJ);
            $Daily_sender_id = $daily_sender_details->customer_id;
        }

        // Daily Customer receiver 
        $Daily_receiver_id = 0;
        if ($_POST["addreceiver"] == "true") {
            $receiver_phone = helper::test_input($_POST["receiver_phone"]);
            $receiver_fname = helper::test_input($_POST["receiver_fname"]);
            $receiver_lname = helper::test_input($_POST["receiver_lname"]);
            $receiver_Fathername = helper::test_input($_POST["receiver_Fathername"]);
            $receiver_nid = helper::test_input($_POST["receiver_nid"]);
            $receiver_details = helper::test_input($_POST["receiver_details"]);
            $Daily_receiver_id = $bussiness->addDailyCustomer([$receiver_fname, $receiver_lname, $receiver_Fathername, $receiver_phone, $receiver_nid, $receiver_details, 'Daily Customer', time(), $loged_user->user_id, 1]);
        } else {
            $daily_receiver_data = $bussiness->GetDailyCustomer(helper::test_input($_POST["sender_phone"]));
            $daily_receiver_details = $daily_receiver_data->fetch(PDO::FETCH_OBJ);
            $Daily_receiver_id = $daily_receiver_details->customer_id;
        }

        // just add one payment method
        $paymentID = $_POST["paymentID"];
        $payment_amount = $_POST["payment_amount"];
        $company_financial_term_id = 0;
        if (isset($company_ft->term_id)) {
            $company_financial_term_id = $company_ft->term_id;
        }
        $recipt_details = helper::test_input($_POST["reciptItemdetails"]);

        $leadger_id = $transfer->addTransferOutLeadger([$rsaraf_ID, $paymentID, $company_financial_term_id, $newdate, $details, 1, $loged_user->user_id, 0, "transferout", $loged_user->company_id, $currency]);
        $transfer->addTransferOutMoney([$paymentID, $leadger_id, $payment_amount, "Crediet", $loged_user->company_id, $details, 1]);
        $transfer->addTransferOutMoney([$rsaraf_ID, $leadger_id, $payment_amount, "Debet", $loged_user->company_id, $recipt_details, 1]);

        if ($_POST["paymentIDcounter"] > 0) {
            // add all payment method
            for ($i = 1; $i <= $_POST["paymentIDcounter"]; $i++) {
                $paymentID_temp = $_POST[("paymentID" . $i)];
                $payment_amount_temp = $_POST[("payment_amount" . $i)];
                $transfer->addTransferOutMoney([$rsaraf_ID, $leadger_id, $payment_amount_temp, "Debet", $loged_user->company_id, $_POST[("reciptItemdetails" . $i)], 1]);
            }
        }

        $saraf_cus_id_data = $bank->getCustomerByBank($rsaraf_ID);
        $saraf_cus_id_details = $saraf_cus_id_data->fetch(PDO::FETCH_OBJ);

        $transfer_ID = $transfer->addOutTransfer([$loged_user->user_id, $mycommission, $saraf_cus_id_details->customer_id, $sarafcommission, $Daily_sender_id, $Daily_receiver_id, $amount, $currency, $newdate, 0, 0, $transfercode, $vouchercode, $details, 0, "out", $loged_user->company_id, $leadger_id]);
        echo $transfer_ID;
    }


    // Add new In transfer
    if (isset($_POST["addintransfer"])) {
        $loged_user = json_decode($_SESSION["bussiness_user"]);
        $company_id = $loged_user->company_id;

        $details = helper::test_input($_POST["details"]);
        $date = helper::test_input($_POST["date"]);
        $newdate = strtotime($date);
        $transfercode = helper::test_input($_POST["transfercode"]);
        $vouchercode = helper::test_input($_POST["vouchercode"]);
        $rsaraf_ID = helper::test_input($_POST["rsaraf_ID"]);
        $currency = helper::test_input($_POST["currency"]);
        $amount = helper::test_input($_POST["amount"]);
        $mycommission = helper::test_input($_POST["mycommission"]);
        $sarafcommission = helper::test_input($_POST["sarafcommission"]);

        // Daily Customer sender 
        $Daily_sender_id = 0;
        if ($_POST["addsender"] == "true") {
            $sender_phone = helper::test_input($_POST["sender_phone"]);
            $sender_fname = helper::test_input($_POST["sender_fname"]);
            $sender_lname = helper::test_input($_POST["sender_lname"]);
            $sender_Fathername = helper::test_input($_POST["sender_Fathername"]);
            $sender_nid = helper::test_input($_POST["sender_nid"]);
            $sender_details = helper::test_input($_POST["sender_details"]);
            $Daily_sender_id = $bussiness->addDailyCustomer([$sender_fname, $sender_lname, $sender_Fathername, $sender_phone, $sender_nid, $sender_details, 'Daily Customer', time(), $loged_user->user_id, 1]);
        } else {
            $daily_sender_data = $bussiness->GetDailyCustomer(helper::test_input($_POST["sender_phone"]));
            $daily_sender_details = $daily_sender_data->fetch(PDO::FETCH_OBJ);
            $Daily_sender_id = $daily_sender_details->customer_id;
        }

        // Daily Customer receiver 
        $Daily_receiver_id = 0;
        if ($_POST["addreceiver"] == "true") {
            $receiver_phone = helper::test_input($_POST["receiver_phone"]);
            $receiver_fname = helper::test_input($_POST["receiver_fname"]);
            $receiver_lname = helper::test_input($_POST["receiver_lname"]);
            $receiver_Fathername = helper::test_input($_POST["receiver_Fathername"]);
            $receiver_nid = helper::test_input($_POST["receiver_nid"]);
            $receiver_details = helper::test_input($_POST["receiver_details"]);
            $Daily_receiver_id = $bussiness->addDailyCustomer([$receiver_fname, $receiver_lname, $receiver_Fathername, $receiver_phone, $receiver_nid, $receiver_details, 'Daily Customer', time(), $loged_user->user_id, 1]);
        } else {
            $daily_receiver_data = $bussiness->GetDailyCustomer(helper::test_input($_POST["sender_phone"]));
            $daily_receiver_details = $daily_receiver_data->fetch(PDO::FETCH_OBJ);
            $Daily_receiver_id = $daily_receiver_details->customer_id;
        }

        // just add one payment method
        $paymentID = $_POST["paymentID"];
        $payment_amount = $_POST["payment_amount"];
        $company_financial_term_id = 0;
        if (isset($company_ft->term_id)) {
            $company_financial_term_id = $company_ft->term_id;
        }
        $recipt_details = helper::test_input($_POST["reciptItemdetails"]);
        $leadger_id = $transfer->addTransferInLeadger([$rsaraf_ID, $paymentID, $company_financial_term_id, $newdate, $details, 1, $loged_user->user_id, 0, "transferin", $loged_user->company_id, $currency]);
        $transfer->addTransferOutMoney([$paymentID, $leadger_id, $payment_amount, "Debet", $loged_user->company_id, $details, 1]);
        $transfer->addTransferOutMoney([$rsaraf_ID, $leadger_id, $payment_amount, "Crediet", $loged_user->company_id, $recipt_details, 1]);

        if ($_POST["paymentIDcounter"] > 0) {
            // add all payment method
            for ($i = 1; $i <= $_POST["paymentIDcounter"]; $i++) {
                $paymentID_temp = $_POST[("paymentID" . $i)];
                $payment_amount_temp = $_POST[("payment_amount" . $i)];
                $transfer->addTransferOutMoney([$paymentID_temp, $leadger_id, $payment_amount_temp, "Debet", $loged_user->company_id, $_POST[("reciptItemdetails" . $i)], 1]);
            }
        }

        $saraf_cus_id_data = $bank->getCustomerByBank($rsaraf_ID);
        $saraf_cus_id_details = $saraf_cus_id_data->fetch(PDO::FETCH_OBJ);

        $transfer_ID = $transfer->addInTransfer([$saraf_cus_id_details->customer_id, $sarafcommission, $loged_user->user_id, $mycommission, $Daily_sender_id, $Daily_receiver_id, $amount, $currency, $newdate, 0, 0, $transfercode, $vouchercode, $details, 0, "in", $loged_user->company_id, $leadger_id]);
        echo $transfer_ID;
    }

    // Cancel Transfer
    if (isset($_POST["cancel_transer_done"])) {
        $transfer_id = $_POST["transferID"];
        $transfer->deleteAccoumtMoneyByLeadger($transfer_id);
        $transfer->deleteTransferByLeadger($transfer_id);
        $transfer->deleteTransferLeadger($transfer_id);
        echo "done";
    }

    // Approve Transfer
    if (isset($_POST["approve_transer_done"])) {
        $transfer_id = $_POST["transferID"];
        $transfer->approveTransferMoneyByLeadger($transfer_id);
        $transfer->approveTransfer($transfer_id);
        echo "done";
    }
}

if ($_SERVER["REQUEST_METHOD"] == "GET") {

    $transfer = new Transfer();
    $loged_user = json_decode($_SESSION["bussiness_user"]);

    if (isset($_GET["transferoutalldetails"])) {
        $leadger = $_GET["leadger_id"];
        $details = $transfer->getTransferByLeadger($leadger, 'transferin');
        echo json_encode($details->fetchAll(PDO::FETCH_OBJ));
    }

    // Get Currency Details
    if (isset($_GET["getCurrencyDetails"])) {
        $company = new Company();
        $debet_data = $company->GetCompanyCurrencyDetails($loged_user->company_id, $_GET["cur"]);
        $debet = $debet_data->fetch(PDO::FETCH_OBJ);

        echo json_encode($debet);
    }

    // Get Daily customer Money sender
    if (isset($_GET["DCMS"])) {
        $id = $_GET["id"];
        $detials = $transfer->getDailyCusDetails($id);
        echo json_encode($detials->fetch(PDO::FETCH_OBJ));
    }

    // Get Financial Accounts details
    if (isset($_GET["account"])) {
        $id = $_GET["id"];
        $detials = $bank->getBank_Saif($id);
        echo json_encode($detials->fetch(PDO::FETCH_OBJ));
    }
}
