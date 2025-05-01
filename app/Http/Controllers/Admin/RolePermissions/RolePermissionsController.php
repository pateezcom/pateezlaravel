<?php

namespace App\Http\Controllers\Admin\RolePermissions;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RolePermissionsController extends Controller
{
  public function index()
  {
    return view('content.admin.rolepermissions.app-access-roles');
  }
}