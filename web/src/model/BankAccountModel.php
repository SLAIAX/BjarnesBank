<?php
namespace agilman\a2\model;
session_start();

class BankAccountModel extends Model
{
    /**
     * @var Bank account ID
     */
    private $mAccountID;
    /**
     * @var Users ID who owns said bank account
     */
    private $mUserID;
    /**
     * @var The name of the bank account
     */
    private $mAccountName;
    /**
     * @var The type of bank account
     */
    private $mType;
    /**
     * @var The balance of the bank account
     */
    private $mBalance;

    /**
     * BankAccountModel constructor.
     * @param $mAccountID
     * @param $mUserID
     */
    public function __construct($mAccountID, $mUserID)
    {
        $this->mAccountID = $mAccountID;
        $this->mUserID = $mUserID;
        parent::__construct();
    }


    /**
     * @return mixed
     */
    public function getAccountID()
    {
        return $this->mAccountID;
    }

    /**
     * @param mixed $mAccountID
     */
    public function setAccountID($mAccountID)
    {
        $this->mAccountID = $mAccountID;
    }

    /**
     * @return mixed
     */
    public function getUserID()
    {
        return $this->mUserID;
    }

    /**
     * @param mixed $mUserID
     */
    public function setUserID($mUserID)
    {
        $this->mUserID = $mUserID;
    }

    /**
     * @return mixed
     */
    public function getAccountName()
    {
        return $this->mAccountName;
    }

    /**
     * @param mixed $mAccountName
     */
    public function setAccountName($mAccountName)
    {
        $this->mAccountName = $mAccountName;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->mType;
    }

    /**
     * @param mixed $mType
     */
    public function setType($mType)
    {
        $this->mType = $mType;
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
     * Finds the ID of the account given a account name
     * @param $accountName
     * @return mixed
     */
    public function findID($accountName)
    {
        $id = $this->getUserID();
        if (!$accountName) {
            throw new \UnexpectedValueException();
        }
        if (!$result = $this->db->query("SELECT `AccountID` FROM `account` WHERE `AccountName` = '$accountName' and `UserID` = '$id';")) {
            throw new \UnexpectedValueException();
        }
        $result = $result->fetch_assoc();
        return $result['AccountID'];
    }

    /**
     * Loads the object with data found in the database
     * @return $this
     */
    public function load()
    {
        $id = $this->getAccountID();
        if (!$result = $this->db->query("SELECT * FROM `account` WHERE `AccountID` = $id;")) {
            throw new \mysqli_sql_exception();
        }
        $result = $result->fetch_assoc();
        $this->setAccountID($result['AccountID']);
        $this->setUserID($result['UserID']);
        $this->setBalance($result['Balance']);
        $this->setType($result['AccountType']);
        $this->setAccountName($result['AccountName']);
        return $this;
    }

    /**
     * Creates an account and saves it in the database
     */
    public function save()
    {
        $id = $this->getUserID();
        $AccountName = $this->getAccountName();
        $AccountType = $this->getType();
        if (!$result = $this->db->query("INSERT INTO `account` VALUES (NULL, '$id', '$AccountType', 0, '$AccountName');")) {
            throw new \mysqli_sql_exception();
        }
    }

    /**
     * Validates that the user entered all necessary information and that an account of the same name doesn't already exist
     */
    public function validate()
    {
        $accountName = $this->getAccountName();
        $id = $this->getUserID();
        if (!$accountName) {
            throw new \UnexpectedValueException();
        }
        if ($result = $this->db->query("SELECT * FROM `account` WHERE `AccountName` = '$accountName' and `UserID` = '$id';")) {
            $accounts = $result->fetch_assoc();
            if ($accounts['AccountName'] != "") {
                throw new \LogicException();
            }
        }
    }

    /**
     * Deletes an account and its corresponding transactions
     */
    public function deleteAccount()
    {
        $id = $this->getAccountID();
        if (!$result = $this->db->query("DELETE FROM `account` WHERE `AccountID` = '$id';")) {
            throw new \mysqli_sql_exception();
        }
        if (!$result = $this->db->query("DELETE FROM `transactions` WHERE `ToAccountID` = '$id' and `MoneyIn` > 0 or `FromAccountID` = '$id' and `MoneyOut` > 0;")) {
            //Don't throw as may not have any transactions.
        }
    }
}
