<?php
namespace agilman\a2\model;


class BankAccountCollectionModel extends Model
{
    /**
     * @var array of Bank Account IDs
     */
    private $accountIds;
    /**
     * @var The number of bank accounts for said user
     */
    private $N;


    /**
     * BankAccountCollectionModel constructor.
     * @param $id
     */
    public function __construct($id)
    {
        parent::__construct();
        try {
            if (!$result = $this->db->query("SELECT `AccountID` FROM `account` WHERE `UserID` = $id;")) {
                throw new \Exception();
            }
            $this->accountIds = array_column($result->fetch_all(), 0);
            $this->N = $result->num_rows;
        } catch (\Exception $e) {
        }
    }

    /**
     * Gets bank account information
     * @return \Generator
     */
    public function getAccounts()
    {
        foreach ($this->accountIds as $id) {
            // Use a generator to save on memory/resources
            // load accounts from DB one at a time only when required
            yield (new BankAccountModel($id, null))->load();
        }
    }
}
