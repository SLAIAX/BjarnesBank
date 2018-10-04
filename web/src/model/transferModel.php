<?php
namespace agilman\a2\model;
session_start();

/**
 * Class AccountModel
 *
 * @package agilman/a2
 * @author  Andrew Gilman <a.gilman@massey.ac.nz>
 */
class transferModel extends Model
{

    /**
     * @return mixed
     */
    public function findID($username)
    {
        if (!$result = $this->db->query("SELECT ID FROM `user` WHERE `Username` = '$username';")) {
          //  return 0;


        }
        $result = $result->fetch_assoc();
        //$result = $result->fetch_assoc();
        return $result['ID'];
    }





}
