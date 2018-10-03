<?php
namespace agilman\a2\model;

/**
 * Class AccountCollectionModel
 *
 * @package agilman/a2
 * @author  Andrew Gilman <a.gilman@massey.ac.nz>
 */
class transactionModel extends Model
{
    private $accountName;
    private $array;
    private $N;

//    public function __construct()
//    {
////        $this->accountName = $account;
////        parent::__construct();
////        try {
////            if (!$result = $this->db->query("SELECT * FROM `transactions` WHERE `AccountID` = $this->accountName;")) {
////                // throw new ...
////                throw new \Exception();
////            }
////            $this->array = array_column($result->fetch_all(), 0);
////            $this->N = $result->num_rows;
////        }catch(\Exception $e){
////
////        }
//    }


    public function makeTransfer($toAccountID, $fromAccountID){
        $amount = (float)$_POST['amount'];
        $description = $_POST['description'];

        $date = date("Y-m-d");
        if($toAccountID == $fromAccountID)
            error_log("can't transfer to same account");

        if(!$result = $this->db->query("SELECT * FROM `account` WHERE `AccountID` = '$toAccountID';")){

        }
        $toAccount = $result->fetch_assoc();
        $toAccountBal = (int)$toAccount['Balance'];

        if(!$result = $this->db->query("SELECT * FROM `account` WHERE `AccountID` = '$fromAccountID';")){
            //throw
        }
        $fromAccount = $result->fetch_assoc();
        $fromAccountBal = (int)$fromAccount['Balance'];

        if($fromAccountBal < $amount){
            //throw
        }
        $balanceFrom = $fromAccountBal-$amount;
        $balanceTo = $toAccountBal+$amount;
        if(!$result = $this->db->query("INSERT INTO `transactions` VALUES (NULL, '$fromAccountID', '$description', '$date', 0, '$amount', '$balanceFrom','$toAccountID');")) {
            //throw
        }
        if(!$result = $this->db->query("INSERT INTO `transactions` VALUES (NULL, '$fromAccountID', '$description', '$date', '$amount', 0, '$balanceTo','$toAccountID');")) {
            //throw
        }
        if(!$result = $this->db->query("UPDATE `account` SET `Balance` = '$balanceFrom' WHERE `AccountID` = '$fromAccountID';")) {
            //throw
        }
        if(!$result = $this->db->query("UPDATE `account` SET `Balance` = '$balanceTo' WHERE `AccountID` = '$toAccountID';")) {
            //throw
        }
    }


    /**
     * Get account collection
     *
     * @return \Generator|AccountModel[] Accounts
     */
    public function getAccounts()
    {
        foreach ($this->accountIds as $id) {
            // Use a generator to save on memory/resources
            // load accounts from DB one at a time only when required
            yield (new AccountModel())->load($id);
        }
    }
}
