<?php

namespace Dulannadeeja\Mvc;

abstract class UserModel extends DatabaseModel
{
    abstract public function getUserDisplayName(): string;

}