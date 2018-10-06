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
    private $mAmount;
    private $mToAccountID;
    private $mFromAccountID;
    private $mBalanceFrom;
    private $mBalanceTo;

    public function validateTransfer($accountTo, $accountFrom){
        $this->mAmount = $_POST['amount'];
        $this->mToAccountID = $accountTo;
        $this->mFromAccountID = $accountFrom;

        if(!$this->mAmount){
            throw new \UnexpectedValueException();
        }
        if($this->mToAccountID == $this->mFromAccountID) {
            throw new \LogicException();
        }
        if(!$result = $this->db->query("SELECT * FROM `account` WHERE `AccountID` = '$this->mToAccountID';")){
            throw new \mysqli_sql_exception();
        }
        $toAccount = $result->fetch_assoc();
        $this->mBalanceTo = (int)$toAccount['Balance'];

        if(!$result = $this->db->query("SELECT * FROM `account` WHERE `AccountID` = '$this->mFromAccountID';")){
            throw new \mysqli_sql_exception();
        }
        $fromAccount = $result->fetch_assoc();
        $this->mBalanceFrom = (int)$fromAccount['Balance'];

        if ($this->mBalanceFrom < $this->mAmount) {
            throw new \LogicException();
        }

    }

    public function makeTransfer(){
            $description = $_POST['description'];
            $date = date("Y-m-d");
            $this->mBalanceFrom -= $this->mAmount;
            $this->mBalanceTo += $this->mAmount;
            if (!$result = $this->db->query("INSERT INTO `transactions` VALUES (NULL, '$this->mFromAccountID', '$description', '$date', 0, '$this->mAmount', '$this->mBalanceFrom','$this->mToAccountID');")) {
                throw new \mysqli_sql_exception();
            }
            if (!$result = $this->db->query("INSERT INTO `transactions` VALUES (NULL, '$this->mFromAccountID', '$description', '$date', '$this->mAmount', 0, '$this->mBalanceTo','$this->mToAccountID');")) {
                throw new \mysqli_sql_exception();
            }

    }

    public function save(){
        if (!$result = $this->db->query("UPDATE `account` SET `Balance` = '$this->mBalanceFrom' WHERE `AccountID` = '$this->mFromAccountID';")) {
            throw new \mysqli_sql_exception();
        }
        if (!$result = $this->db->query("UPDATE `account` SET `Balance` = '$this->mBalanceTo' WHERE `AccountID` = '$this->mToAccountID';")) {
            throw new \mysqli_sql_exception();
        }
    }



    public function getTransactions($accountID){  //Move to collection Model
        if(!$result = $this->db->query("SELECT * FROM `transactions` WHERE `FromAccountID` = '$accountID' and `MoneyOut` > 0 or `ToAccountID` = '$accountID' and `MoneyIn` > 0;")){
            throw new \mysqli_sql_exception();
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
