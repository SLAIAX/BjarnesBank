<?php
namespace agilman\a2\model;

use \Exception;
use agilman\a2\exception\EmptyFieldException;

class TransactionModel extends Model
{
    private $mID;
    private $mAmount;
    private $mToAccountID;
    private $mFromAccountID;
    private $mDescription;
    private $mDate;
    private $mMoneyIn;
    private $mMoneyOut;
    private $mBalance;


    private $mBalanceFrom;
    private $mBalanceTo;

    /**
     * @return mixed
     */
    public function getID()
    {
        return $this->mID;
    }

    /**
     * @param mixed $mID
     */
    public function setID($mID)
    {
        $this->mID = $mID;
    }



    /**
     * @return mixed
     */
    public function getAmount()
    {
        return $this->mAmount;
    }

    /**
     * @param mixed $mAmount
     */
    public function setAmount($mAmount)
    {
        $this->mAmount = $mAmount;
    }

    /**
     * @return mixed
     */
    public function getToAccountID()
    {
        return $this->mToAccountID;
    }

    /**
     * @param mixed $mToAccountID
     */
    public function setToAccountID($mToAccountID)
    {
        $this->mToAccountID = $mToAccountID;
    }

    /**
     * @return mixed
     */
    public function getFromAccountID()
    {
        return $this->mFromAccountID;
    }

    /**
     * @param mixed $mFromAccountID
     */
    public function setFromAccountID($mFromAccountID)
    {
        $this->mFromAccountID = $mFromAccountID;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->mDescription;
    }

    /**
     * @param mixed $mDescription
     */
    public function setDescription($mDescription)
    {
        $this->mDescription = $mDescription;
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->mDate;
    }

    /**
     * @param mixed $mDate
     */
    public function setDate($mDate)
    {
        $this->mDate = $mDate;
    }

    /**
     * @return mixed
     */
    public function getMoneyIn()
    {
        return $this->mMoneyIn;
    }

    /**
     * @param mixed $mMoneyIn
     */
    public function setMoneyIn($mMoneyIn)
    {
        $this->mMoneyIn = $mMoneyIn;
    }

    /**
     * @return mixed
     */
    public function getMoneyOut()
    {
        return $this->mMoneyOut;
    }

    /**
     * @param mixed $mMoneyOut
     */
    public function setMoneyOut($mMoneyOut)
    {
        $this->mMoneyOut = $mMoneyOut;
    }

    /**
     * @return mixed
     */
    public function getBalance()
    {
        return $this->mBalance;
    }

    /**
     * @param mixed $mBalance
     */
    public function setBalance($mBalance)
    {
        $this->mBalance = $mBalance;
    }






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


    public function load($id)
    {
        if (!$result = $this->db->query("SELECT * FROM `transactions` WHERE `TransID` = $id;")) {
            throw new \mysqli_sql_exception();
        }
        $result = $result->fetch_assoc();
        $this->setToAccountID(['ToAccoutnID']);
        $this->setFromAccountID($result['FromAccountID']);
        $this->setBalance($result['Balance']);
        $this->setDate($result['DateOfTrans']);
        $this->setMoneyIn($result['MoneyIn']);
        $this->setMoneyOut($result['MoneyOut']);
        $this->setDescription($result['Description']);
        $this->setID($id);
        return $this;
    }


    public function save(){
        if (!$result = $this->db->query("UPDATE `account` SET `Balance` = '$this->mBalanceFrom' WHERE `AccountID` = '$this->mFromAccountID';")) {
            throw new \mysqli_sql_exception();
        }
        if (!$result = $this->db->query("UPDATE `account` SET `Balance` = '$this->mBalanceTo' WHERE `AccountID` = '$this->mToAccountID';")) {
            throw new \mysqli_sql_exception();
        }
    }

}
