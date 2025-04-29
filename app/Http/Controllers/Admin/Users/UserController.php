<?php

namespace App\Http\Controllers\Admin\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\Users\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the users.
     * Kullanıcıların listesini gösterir.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Pass page specific data if needed
        $pageConfigs = [
            'pageHeader' => false,
            'contentLayout' => "default",
            'pageClass' => 'app-user-list',
        ];

        return view('content.admin.users.user-list', ['pageConfigs' => $pageConfigs]);
    }
}
