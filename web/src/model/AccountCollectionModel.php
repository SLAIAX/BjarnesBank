<?php
namespace agilman\a2\model;

/**
 * Class AccountCollectionModel
 *
 * @package agilman/a2
 * @author  Andrew Gilman <a.gilman@massey.ac.nz>
 */
class AccountCollectionModel extends Model
{
    private $accountIds;

    private $N;

    public function __construct()
    {
        parent::__construct();
        try {
            if (!$result = $this->db->query("SELECT `ID` FROM `user`;")) {
                // throw new ...
                throw new \Exception();
            }
            $this->accountIds = array_column($result->fetch_all(), 0);
            $this->N = $result->num_rows;
        }catch(\Exception $e){

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
