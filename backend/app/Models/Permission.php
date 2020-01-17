<?php

namespace App\Models;

use Spatie\Permission\Models\Permission as PermissionParent;

class Permission extends PermissionParent
{
    public $fillable = ['name', 'guard_name'];
}
