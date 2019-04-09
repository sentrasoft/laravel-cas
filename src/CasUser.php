<?php

namespace Sentrasoft\Cas;

use ArrayAccess;

class CasUser implements Contracts\User
{
    /**
     * The user's login ID.
     *
     * @var string
     */
    public $id;

    /**
     * The user's attributes.
     *
     * @var array
     */
    public $attributes;

    /**
     * Get the user's login ID of the user.
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get the attributes of the user.
     *
     * @return array
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * Get specific attribute
     *
     * @param $attribute_name string
     * @return string|null
     */
    public function getAttribute($attribute_name)
    {
        return isset($this->attributes[$attribute_name]) ? $this->attributes[$attribute_name] : null;
    }

    /**
     * Set the raw user array from the provider.
     *
     * @param  string  $user
     * @return $this
     */
    public function fill($user)
    {
        $this->id = $user['id'];
        $this->attributes = $user['attributes'];

        return $this;
    }
}
