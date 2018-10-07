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

    /**
     * @return mixed
     */
    public function getBalanceFrom()
    {
        return $this->mBalanceFrom;
    }

    /**
     * @param mixed $mBalanceFrom
     */
    public function setBalanceFrom($mBalanceFrom)
    {
        $this->mBalanceFrom = $mBalanceFrom;
    }

    /**
     * @return mixed
     */
    public function getBalanceTo()
    {
        return $this->mBalanceTo;
    }

    /**
     * @param mixed $mBalanceTo
     */
    public function setBalanceTo($mBalanceTo)
    {
        $this->mBalanceTo = $mBalanceTo;
    }






    public function validateTransfer()
    {

        $toAccountID = $this->getToAccountID();
        $fromAccountID = $this->getAccountID();
        if (!$this->getAmount()) {
            throw new \UnexpectedValueException();
        }
        if ($toAccountID == $fromAccountID) {
            throw new \LogicException();
        }
        if (!$result = $this->db->query("SELECT * FROM `account` WHERE `AccountID` = '$toAccountID';")) {
            throw new \mysqli_sql_exception();
        }
        $toAccount = $result->fetch_assoc();
        $this->setBalanceTo((int)$toAccount['Balance']);

        if (!$result = $this->db->query("SELECT * FROM `account` WHERE `AccountID` = '$fromAccountID';")) {
            throw new \mysqli_sql_exception();
        }
        $fromAccount = $result->fetch_assoc();
        $this->setBalanceFrom((int)$fromAccount['Balance']);
        if ($this->getBalanceFrom() < $this->getAmount()) {
            throw new \LogicException();
        }
    }

    public function makeTransfer()
    {

            $toAccountID = $this->getToAccountID();
            $fromAccountID = $this->getAccountID();
            $description = $this->getDescription();
            $date = date("Y-m-d");
            $amount = $this->getAmount();
            $balanceFrom = $this->getBalanceFrom() - $amount;
            $balanceTo =  $this->getBalanceTo() + $amount;
        if (!$result = $this->db->query("INSERT INTO `transactions` VALUES (NULL, '$fromAccountID', '$description', '$date', 0, '$amount', '$balanceFrom','$toAccountID');")) {
            throw new \mysqli_sql_exception();
        }
        if (!$result = $this->db->query("INSERT INTO `transactions` VALUES (NULL, '$fromAccountID', '$description', '$date', '$amount', 0, '$balanceTo','$toAccountID');")) {
            throw new \mysqli_sql_exception();
        }
    }


    public function load($id)
    {
        if (!$result = $this->db->query("SELECT * FROM `transactions` WHERE `TransID` = $id;")) {
            throw new \mysqli_sql_exception();
        }
        $result = $result->fetch_assoc();
        $this->setToAccountID($result['ToAccountID']);
        $this->setFromAccountID($result['FromAccountID']);
        $this->setBalance($result['Balance']);
        $this->setDate($result['DateOfTrans']);
        $this->setMoneyIn($result['MoneyIn']);
        $this->setMoneyOut($result['MoneyOut']);
        $this->setDescription($result['Description']);
        $this->setID($id);
        return $this;
    }


    public function save()
    {
        $balanceTo = $this->getBalanceTo();
        $balanceFrom = $this->getBalanceFrom);
        if (!$result = $this->db->query("UPDATE `account` SET `Balance` = '$balanceFrom' WHERE `AccountID` = '$this->mFromAccountID';")) {
            throw new \mysqli_sql_exception();
        }
        if (!$result = $this->db->query("UPDATE `account` SET `Balance` = '$balanceTo' WHERE `AccountID` = '$this->mToAccountID';")) {
            throw new \mysqli_sql_exception();
        }
    }
}
