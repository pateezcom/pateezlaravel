<?php

namespace App\Http\Controllers\Admin\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\Users\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
  /**
   * Display a listing of the users.
   * Kullanıcıların listesini gösterir.
   *
   * @return \Illuminate\Http\Response
   */
  public function index(Request $request)
  {
    $pageConfigs = ['layoutType' => 'content-detached-right-sidebar'];

    // If AJAX request, return JSON
    if ($request->ajax()) {
      $users = User::with('roles')->get()->map(function ($user) {
        // Get the first role of the user or 'Member' as default
        // Kullanıcının ilk rolünü al veya varsayılan olarak 'Member' kullan
        $roleName = $user->roles->first() ? $user->roles->first()->name : 'Member';
        
        // Map status code to readable status
        // Durum kodunu okunabilir duruma dönüştür
        $statusMap = [
          1 => 'Pending',
          2 => 'Active',
          3 => 'Inactive'
        ];
        
        return [
          'id' => $user->id,
          'full_name' => $user->name, // 'name' alanını 'full_name' olarak eşleştir
          'username' => $user->username,
          'email' => $user->email,
          'avatar' => $user->profile_photo_path, // Profil fotoğrafı veya null
          'role' => $roleName, // Spatie Permission'dan rol
          'role_id' => $user->roles->first() ? $user->roles->first()->id : null,
          'reward_system_active' => $user->reward_system_active,
          'status' => $user->status ?? 2, // Default to active if null
          'date' => $user->created_at
        ];
      });
      return response()->json(['data' => $users]);
    }

    // Normal page load
    return view('content.admin.users.user-list', ['pageConfigs' => $pageConfigs]);
  }

  /**
   * Check if username is available
   * Kullanıcı adının kullanılabilir olup olmadığını kontrol eder
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function checkUsername(Request $request)
  {
    $username = $request->input('username');
    $userId = $request->input('userId');
    
    $query = User::where('username', $username);
    
    if ($userId) {
      $query->where('id', '!=', $userId);
    }
    
    $exists = $query->exists();
    
    return response()->json([
      'available' => !$exists
    ]);
  }
  
  /**
   * Check if email is available
   * E-posta adresinin kullanılabilir olup olmadığını kontrol eder
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function checkEmail(Request $request)
  {
    $email = $request->input('email');
    $userId = $request->input('userId');
    
    $query = User::where('email', $email);
    
    if ($userId) {
      $query->where('id', '!=', $userId);
    }
    
    $exists = $query->exists();
    
    return response()->json([
      'available' => !$exists
    ]);
  }
  
  /**
   * Store a newly created user in storage.
   * Yeni bir kullanıcıyı veritabanına kaydeder.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    // Validate input data
    // Girilen verileri doğrula
    $request->validate([
      'userFullname' => 'required|string|max:255',
      'userUsername' => 'required|string|max:25|unique:users,username|regex:/^[a-zA-Z0-9_\-\.]+$/',
      'userEmail' => 'required|string|email|max:255|unique:users,email',
      'userPassword' => 'required|string|min:8|confirmed',
      'userRole' => 'required|exists:roles,name'
    ], [
      'userFullname.required' => 'Ad soyad alanı zorunludur',
      'userUsername.required' => 'Kullanıcı adı zorunludur',
      'userUsername.unique' => 'Bu kullanıcı adı zaten kullanılıyor',
      'userUsername.regex' => 'Kullanıcı adı yalnızca harf, sayı, alt çizgi, nokta ve tire içerebilir',
      'userEmail.required' => 'E-posta adresi zorunludur',
      'userEmail.email' => 'Geçerli bir e-posta adresi giriniz',
      'userEmail.unique' => 'Bu e-posta adresi zaten kullanılıyor',
      'userPassword.required' => 'Şifre zorunludur',
      'userPassword.min' => 'Şifre en az 8 karakter olmalıdır',
      'userPassword.confirmed' => 'Şifreler eşleşmiyor',
      'userRole.required' => 'Lütfen bir rol seçin',
    ]);

    // Create new user
    // Yeni kullanıcı oluştur
    $user = User::create([
      'name' => $request->userFullname,
      'username' => $request->userUsername,
      'email' => $request->userEmail,
      'password' => Hash::make($request->userPassword),
      'reward_system_active' => $request->has('userReward'),
      'status' => $request->userStatus ?? 2 // 2 = Active
    ]);

    // Assign role to user
    // Kullanıcıya rol ata
    $user->assignRole($request->userRole);

    return response()->json([
      'success' => true,
      'message' => 'Kullanıcı başarıyla oluşturuldu!'
    ]);
  }

  /**
   * Display the specified user.
   * Belirtilen kullanıcının detaylarını gösterir.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function show($id)
  {
    $user = User::with('roles')->findOrFail($id);
    
    return response()->json([
      'success' => true,
      'data' => $user
    ]);
  }

  /**
   * Show the form for editing the specified user.
   * Belirtilen kullanıcıyı düzenlemek için form verilerini döndürür.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function edit($id)
  {
    $user = User::with('roles')->findOrFail($id);
    
    $userData = [
      'id' => $user->id,
      'name' => $user->name,
      'username' => $user->username,
      'email' => $user->email,
      'role_id' => $user->roles->first() ? $user->roles->first()->id : null,
      'role' => $user->roles->first() ? $user->roles->first()->name : null,
      'reward_system_active' => $user->reward_system_active,
      'status' => $user->status ?? 2,
    ];
    
    return response()->json([
      'success' => true,
      'data' => $userData
    ]);
  }

  /**
   * Update the specified user in storage.
   * Belirtilen kullanıcıyı günceller.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, $id)
  {
    // Find user
    // Kullanıcıyı bul
    $user = User::findOrFail($id);

    // Validate input data
    // Girilen verileri doğrula
    $validated = $request->validate([
      'editUserFullname' => 'required|string|max:255',
      'editUserUsername' => [
        'required',
        'string',
        'max:25',
        'regex:/^[a-zA-Z0-9_\-\.]+$/',
        Rule::unique('users', 'username')->ignore($id)
      ],
      'editUserEmail' => [
        'required',
        'string',
        'email',
        'max:255',
        Rule::unique('users', 'email')->ignore($id)
      ],
      'editUserRole' => 'required|exists:roles,name',
      'editUserPassword' => 'nullable|string|min:8',
      'editUserConfirmPassword' => 'nullable|same:editUserPassword',
    ], [
      'editUserFullname.required' => 'Ad soyad alanı zorunludur',
      'editUserUsername.required' => 'Kullanıcı adı zorunludur',
      'editUserUsername.unique' => 'Bu kullanıcı adı zaten kullanılıyor',
      'editUserUsername.regex' => 'Kullanıcı adı yalnızca harf, sayı, alt çizgi, nokta ve tire içerebilir',
      'editUserEmail.required' => 'E-posta adresi zorunludur',
      'editUserEmail.email' => 'Geçerli bir e-posta adresi giriniz',
      'editUserEmail.unique' => 'Bu e-posta adresi zaten kullanılıyor',
      'editUserPassword.min' => 'Şifre en az 8 karakter olmalıdır',
      'editUserConfirmPassword.same' => 'Şifreler eşleşmiyor',
      'editUserRole.required' => 'Lütfen bir rol seçin',
    ]);

    // Update user
    // Kullanıcıyı güncelle
    $userData = [
      'name' => $request->editUserFullname,
      'username' => $request->editUserUsername,
      'email' => $request->editUserEmail,
      'reward_system_active' => $request->has('editUserReward'),
      'status' => $request->editUserStatus ?? $user->status
    ];
    
    // Update password if provided
    // Şifre girildiyse güncelle
    if ($request->filled('editUserPassword')) {
      $userData['password'] = Hash::make($request->editUserPassword);
    }
    
    $user->update($userData);

    // Sync roles
    // Rolleri senkronize et
    $user->syncRoles([$request->editUserRole]);

    return response()->json([
      'success' => true,
      'message' => 'Kullanıcı başarıyla güncellendi!'
    ]);
  }

  /**
   * Remove the specified user from storage.
   * Belirtilen kullanıcıyı siler.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    // Find and delete user
    // Kullanıcıyı bul ve sil
    $user = User::findOrFail($id);
    $user->delete();

    return response()->json([
      'success' => true,
      'message' => 'Kullanıcı başarıyla silindi!'
    ]);
  }

  /**
   * Get all available roles for dropdowns.
   * Açılır menüler için tüm mevcut rolleri getirir.
   *
   * @return \Illuminate\Http\Response
   */
  public function getRoles()
  {
    $roles = Role::all();
    return response()->json($roles);
  }

  /**
   * Get all permissions for a role.
   * Bir rol için tüm izinleri getirir.
   *
   * @param  int  $roleId
   * @return \Illuminate\Http\Response
   */
  public function getRolePermissions($roleId)
  {
    $role = Role::findOrFail($roleId);
    $permissions = $role->permissions;
    return response()->json($permissions);
  }

  /**
   * Show the form for editing user permissions.
   * Kullanıcı izinlerini düzenlemek için form verilerini döndürür.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function editPermissions($id)
  {
    $user = User::with('permissions')->findOrFail($id);
    $permissions = $user->getAllPermissions()->pluck('name');
    
    return response()->json([
      'success' => true,
      'data' => $permissions
    ]);
  }

  /**
   * Update a user's permissions.
   * Kullanıcının izinlerini günceller.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function updatePermissions(Request $request, $id)
  {
    // Find user
    // Kullanıcıyı bul
    $user = User::findOrFail($id);

    // Validate input
    // Girilen verileri doğrula
    $request->validate([
      'permissions' => 'nullable|array',
      'permissions.*' => 'exists:permissions,name'
    ], [
      'permissions.*.exists' => 'Geçersiz izin belirtildi',
    ]);

    // Get permission instances
    // İzin nesnelerini al
    $permissions = $request->input('permissions', []);

    // Sync direct permissions (in addition to role-based permissions)
    // Doğrudan izinleri senkronize et (rol tabanlı izinlere ek olarak)
    $user->syncPermissions($permissions);

    return response()->json([
      'success' => true,
      'message' => 'Kullanıcı izinleri başarıyla güncellendi!'
    ]);
  }
}
