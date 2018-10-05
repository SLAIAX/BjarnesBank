<?php
namespace agilman\a2\model;

use \Exception;
use agilman\a2\exception\EmptyFieldException;
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

            $amount = $_POST['amount'];
            $description = $_POST['description'];

            if(!$amount){
                //throw

                throw new \UnexpectedValueException();

            }

            $date = date("Y-m-d");
            if($toAccountID == $fromAccountID) {
                throw new \LogicException();
            }

            if(!$result = $this->db->query("SELECT * FROM `account` WHERE `AccountID` = '$toAccountID';")){

            }
            $toAccount = $result->fetch_assoc();
            $toAccountBal = (int)$toAccount['Balance'];

            if(!$result = $this->db->query("SELECT * FROM `account` WHERE `AccountID` = '$fromAccountID';")){
                //throw

            }
            $fromAccount = $result->fetch_assoc();
            $fromAccountBal = (int)$fromAccount['Balance'];

            if ($fromAccountBal < $amount) {
                //throw
                throw new \LogicException();
            }

            $balanceFrom = $fromAccountBal - $amount;
            $balanceTo = $toAccountBal + $amount;
            if (!$result = $this->db->query("INSERT INTO `transactions` VALUES (NULL, '$fromAccountID', '$description', '$date', 0, '$amount', '$balanceFrom','$toAccountID');")) {
                //throw
            }
            if (!$result = $this->db->query("INSERT INTO `transactions` VALUES (NULL, '$fromAccountID', '$description', '$date', '$amount', 0, '$balanceTo','$toAccountID');")) {
                //throw
            }
            if (!$result = $this->db->query("UPDATE `account` SET `Balance` = '$balanceFrom' WHERE `AccountID` = '$fromAccountID';")) {
                //throw
            }
            if (!$result = $this->db->query("UPDATE `account` SET `Balance` = '$balanceTo' WHERE `AccountID` = '$toAccountID';")) {
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

    public function getTransactions($accountID){
        if(!$result = $this->db->query("SELECT * FROM `transactions` WHERE `FromAccountID` = '$accountID' and `MoneyOut` > 0 or `ToAccountID` = '$accountID' and `MoneyIn` > 0;")){
            //throw
            echo "throw";
        }
        $i = 0;
        while($transactions = $result->fetch_assoc()){
            $temp[$i][0] = $transactions['FromAccountID'];
            $temp[$i][1] = $transactions['ToAccountID'];
            $temp[$i][2] = $transactions['MoneyIn'];
            $temp[$i][3] = $transactions['MoneyOut'];
            $temp[$i][4] = $transactions['Balance'];
            $temp[$i][6] = $transactions['Description'];
            $temp[$i][5] = $transactions['DateOfTrans'];
            $i++;
        }
        return $temp;
    }
}
