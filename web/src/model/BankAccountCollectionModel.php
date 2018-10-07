<?php
namespace agilman\a2\model;


class BankAccountCollectionModel extends Model
{
    private $accountIds;
    private $N;



    public function __construct($id)
    {
        parent::__construct();
        try {
            if (!$result = $this->db->query("SELECT `AccountID` FROM `account` WHERE `UserID` = $id;")) {
               // throw new \Exception();
            }
            $this->accountIds = array_column($result->fetch_all(), 0);
            $this->N = $result->num_rows;
        }catch(\Exception $e){

        }
    }

    public function getAccounts()
    {
        foreach ($this->accountIds as $id) {
            // Use a generator to save on memory/resources
            // load accounts from DB one at a time only when required
            yield (new BankAccountModel())->load($id);
        }
    }
}
