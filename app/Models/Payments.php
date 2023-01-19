<?php

class Payments
{
    // Database class
    private $conn;

    public function __construct()
    {
        $this->conn = new Connection();
    }

    public function addPaymentLeadger($params)
    {
        $query = "INSERT INTO general_leadger(leadger_id,recievable_id,payable_id,currency_id,remarks,company_financial_term_id,reg_date,currency_rate,approved,createby,updatedby,op_type,company_id) 
        VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?)";
        $result = $this->conn->Query($query, $params, true);
        return $result;
    }

    public function getPaymentLeadger($companyID, $termid)
    {
        $query = "SELECT *, 
        SUM( 
            CASE 
                WHEN account_money.rate != 0 THEN account_money.rate * account_money.amount 
                ELSE account_money.amount END
            ) as total FROM general_leadger 
        INNER JOIN account_money ON general_leadger.leadger_id = account_money.leadger_ID 
        INNER JOIN company_currency ON general_leadger.currency_id = company_currency.company_currency_id 
        WHERE general_leadger.company_id = ? 
        AND general_leadger.op_type = ? 
        AND general_leadger.cleared=? 
        AND general_leadger.company_financial_term_id = ? 
        AND general_leadger.approved = ? 
        AND general_leadger.deleted = ? 
        AND account_money.ammount_type = ? 
        AND account_money.company_id = ? 
        GROUP BY general_leadger.leadger_id 
        ORDER BY general_leadger.leadger_id ASC";
        $result = $this->conn->Query($query, [$companyID, "Payment", 0, $termid,1, 0, "Debet", $companyID]);
        return $result;
    }

    public function getPaymentAccount($leadger_id)
    {
        $query = "SELECT * FROM account_money INNER JOIN chartofaccount ON account_money.account_id = chartofaccount.chartofaccount_id 
                 WHERE account_money.leadger_ID = ? ";
        $result = $this->conn->Query($query, [$leadger_id]);
        return $result;
    }
}
