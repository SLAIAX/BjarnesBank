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

    public function validateTransfer(){
        if(!$this->mAmount){
            throw new \UnexpectedValueException();
        }
        if($this->mToAccountID == $this->mFromAccountID) {
            throw new \LogicException();
        }

    }

    public function makeTransfer(){

            $amount = $_POST['amount'];
            $description = $_POST['description'];



            $date = date("Y-m-d");


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

            if ($this->mBalanceFrom < $amount) {
                throw new \LogicException();
            }

            $this->mBalanceFrom -= $amount;
            $this->mBalanceTo += $amount;
            if (!$result = $this->db->query("INSERT INTO `transactions` VALUES (NULL, '$this->mFromAccountID', '$description', '$date', 0, '$amount', '$this->mBalanceFrom','$this->mToAccountID');")) {
                throw new \mysqli_sql_exception();
            }
            if (!$result = $this->db->query("INSERT INTO `transactions` VALUES (NULL, '$this->mFromAccountID', '$description', '$date', '$amount', 0, '$this->mBalanceTo','$this->mToAccountID');")) {
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
