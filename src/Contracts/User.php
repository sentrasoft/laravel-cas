<?php
namespace Sentrasoft\Cas\Contracts;

interface User
{
    /**
     * Get the user's login ID of the user.
     *
     * @return string
     */
    public function getId();

    /**
     * Get the attributes of the user.
     *
     * @return array
     */
    public function getAttributes();
}
