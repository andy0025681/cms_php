<?php

namespace model;

class EmailAddressBook
{
    function __construct()
    {
        $this->sql = new SendSQL();
    }

    /**
     * get email address book by name.
     *
     * @return      array|bool      email address and name / can't get it.
     * by Andy (2020-01-21)
     */
    function getEmailAddressBookByName($name)
    {
        $buf = $this->sql->sqlSelectEmailAddressBookByName($name);
        foreach ($buf as $row) {
            return $row;
        }
        return false;
    }
}
