<?php

namespace App\Http\Controllers\Admin\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\Users\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\ValidationException;

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
      $users = User::with('roles')
        ->orderBy('id', 'desc') // En son eklenen kullanıcılar en üstte
        ->get()
        ->map(function ($user) {
        // Get the first role of the user or 'Member' as default
        // Kullanıcının ilk rolünü al veya varsayılan olarak 'Member' kullan
        $roleName = $user->roles->first() ? $user->roles->first()->name : 'Member';

        // Map status code to readable status - Frontend'de çevrilecek kod olarak kullan
        // Durum kodunu okunabilir duruma dönüştür
        $statusMap = [
          0 => 0, // 'pending',
          1 => 1, // 'inactive',
          2 => 2  // 'active'
        ];

        return [
          'id' => $user->id,
          'full_name' => $user->name, // 'name' alanını 'full_name' olarak eşleştir
          'username' => $user->username,
          'email' => $user->email,
          'avatar' => $user->profile_photo_url, // Profil fotoğrafı URL'si
          'role' => $roleName, // Spatie Permission'dan rol
          'role_id' => $user->roles->first() ? $user->roles->first()->id : null,
          'reward_system_active' => $user->reward_system_active,
          'status' => $user->status ?? 2, // Default to active if null
          'date' => $user->created_at,
          'email_verified_at' => $user->email_verified_at
        ];
      });
      return response()->json(['data' => $users]);
    }

    // Get user statistics for dashboard cards
    // İstatistik kartları için kullanıcı sayılarını al
    $userStats = $this->getUserStatistics();

    // Normal page load
    return view('content.admin.users.user-list', [
      'pageConfigs' => $pageConfigs,
      'stats' => $userStats
    ]);
  }

  /**
   * Show the profile page for the specified user.
   * Belirtilen kullanıcının profil sayfasını gösterir.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function profile($id)
  {
    try {
      // Find user
      // Kullanıcıyı bul
      $user = User::findOrFail($id);

      // Return view with user data
      // Kullanıcı verileriyle görünümü döndür
      return view('content.admin.users.user-profile', [
        'user' => $user
      ]);
    } catch (\Exception $e) {
      // Log error
      // Hatayı logla
      Log::error('User profile error: ' . $e->getMessage());

      // Redirect to users list with error message
      // Hata mesajıyla kullanıcı listesine yönlendir
      return redirect()->route('admin.users')
        ->with('error', __('An error occurred while loading the user profile'));
    }
  }

  /**
   * Get user statistics for dashboard cards
   * Dashboard kartları için kullanıcı istatistiklerini al
   *
   * @return array
   */
  private function getUserStatistics()
  {
    // Get total user count
    // Toplam kullanıcı sayısı
    $totalUsers = User::count();

    // Get reward system active user count
    // Ödül sistemi aktif olan kullanıcı sayısı
    $rewardUsers = User::where('reward_system_active', true)->count();

    // Get active user count (status = 2)
    // Aktif kullanıcı sayısı
    $activeUsers = User::where('status', 2)->count();

    // Get pending user count (status = 0)
    // Bekleyen kullanıcı sayısı
    $pendingUsers = User::where('status', 0)->count();

    // Calculate percentage changes (for demo purposes with random values)
    // Yüzde değişimlerini hesapla (demo amaçlı rastgele değerlerle)
    $totalChange = rand(5, 35); // Örnek: %5 ile %35 arası artış
    $rewardChange = rand(10, 50); // Örnek: %10 ile %50 arası artış
    $activeChange = $activeUsers > 5000 ? rand(-20, -5) : rand(5, 20); // Aktif kullanıcılar çok fazlaysa azalış, değilse artış göster
    $pendingChange = rand(25, 55); // Bekleyen kullanıcılarda artış

    return [
      'total' => [
        'count' => $totalUsers,
        'change' => $totalChange,
        'increase' => true // Artış mı, azalış mı?
      ],
      'reward' => [
        'count' => $rewardUsers,
        'change' => $rewardChange,
        'increase' => true
      ],
      'active' => [
        'count' => $activeUsers,
        'change' => abs($activeChange),
        'increase' => $activeChange > 0
      ],
      'pending' => [
        'count' => $pendingUsers,
        'change' => $pendingChange,
        'increase' => true
      ]
    ];
  }

  /**
   * Check if a username is already taken.
   * Kullanıcı adının daha önce alınıp alınmadığını kontrol eder.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function checkUsername(Request $request)
  {
    $username = $request->input('username');
    $userId = $request->input('id'); // Edit işleminde mevcut kullanıcıyı hariç tutmak için

    $query = User::where('username', $username);

    if ($userId) {
      $query->where('id', '!=', $userId);
    }

    $exists = $query->exists();

    return response()->json([
      'valid' => !$exists
    ]);
  }

  /**
   * Check if an email is already taken.
   * E-posta adresinin daha önce alınıp alınmadığını kontrol eder.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function checkEmail(Request $request)
  {
    $email = $request->input('email');
    $userId = $request->input('id'); // Edit işleminde mevcut kullanıcıyı hariç tutmak için

    $query = User::where('email', $email);

    if ($userId) {
      $query->where('id', '!=', $userId);
    }

    $exists = $query->exists();

    return response()->json([
      'valid' => !$exists
    ]);
  }

  /**
   * Check if a slug is already taken.
   * Slug'in daha önce alınıp alınmadığını kontrol eder.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function checkSlug(Request $request)
  {
    $slug = $request->input('slug');
    $userId = $request->input('id'); // Edit işleminde mevcut kullanıcıyı hariç tutmak için

    $query = User::where('slug', $slug);

    if ($userId) {
      $query->where('id', '!=', $userId);
    }

    $exists = $query->exists();

    return response()->json([
      'valid' => !$exists
    ]);
  }

  /**
   * Get all roles for select box.
   * Seçim kutusu için tüm rolleri getirir.
   *
   * @return \Illuminate\Http\Response
   */
  public function getRoles()
  {
    try {
      // Get all roles
      // Tüm rolleri al
      $roles = Role::all();

      // Return success response
      // Başarı yanıtı döndür
      return response()->json([
        'success' => true,
        'data' => $roles
      ]);
    } catch (\Exception $e) {
      // Log error
      // Hatayı logla
      Log::error('Error fetching roles: ' . $e->getMessage());

      // Return error response
      // Hata yanıtı döndür
      return response()->json([
        'success' => false,
        'message' => __('An error occurred while fetching roles')
      ], 500);
    }
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
    try {
      // Validate request
      // İstek doğrulama
      $validated = $request->validate([
        'name' => 'required|string|max:255',
        'username' => 'required|string|max:255|unique:users',
        'email' => 'required|string|email|max:255|unique:users',
        'role_id' => 'required|exists:roles,id',
        'password' => 'required|string|min:4|confirmed',
        'reward_system_active' => 'nullable|in:0,1',
        'status' => 'sometimes|integer|in:0,1,2'
      ]);

      // Create new user
      // Yeni kullanıcı oluştur
      $user = new User();
      $user->name = $validated['name'];
      $user->username = $validated['username'];
      $user->email = $validated['email'];
      $user->password = Hash::make($validated['password']);

      // Reward system için özel işlem
      $user->reward_system_active = isset($validated['reward_system_active'])
          ? filter_var($validated['reward_system_active'], FILTER_VALIDATE_BOOLEAN)
          : false;
      $user->status = $request->input('status', 0); // Default to 0 (Pending) if not provided

      $user->save();

      // Email doğrulama olayını tetikle - Gelitirme ortamda hataya neden oluyor
      // Trigger email verification
      // event(new Registered($user));

      // Assign role
      // Rol ata
      $role = Role::findById($validated['role_id']);
      $user->assignRole($role);

      // Return success response
      // Başarı yanıtı döndür
      return response()->json([
        'success' => true,
        'message' => __('user_created_successfully'),
        'data' => $user
      ]);
    }
    catch (ValidationException $e) {
      return response()->json([
        'success' => false,
        'errors' => $e->validator->errors(),
        'message' => __('validation_failed')
      ], 422);
    }
    catch (\Exception $e) {
      return response()->json([
        'success' => false,
        'message' => __('user_creation_failed'),
        'error' => $e->getMessage()
      ], 500);
    }
  }

  /**
   * Show the form for editing the specified user.
   * Belirtilen kullanıcıyı düzenleme formunu gösterir.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function edit($id)
  {
    try {
      // Find user
      // Kullanıcıyı bul
      $user = User::with('roles')->findOrFail($id);

      // Get user data
      // Kullanıcı verilerini al
      $userData = [
        'id' => $user->id,
        'name' => $user->name,
        'username' => $user->username,
        'email' => $user->email,
        'role_id' => $user->roles->first() ? $user->roles->first()->id : null,
        'role' => $user->roles->first() ? $user->roles->first()->name : null,
        'reward_system_active' => (bool)$user->reward_system_active,
        'status' => $user->status ?? 2, // Default to active if null
      ];

      // Return success response
      // Başarı yanıtı döndür
      return response()->json([
        'success' => true,
        'data' => $userData
      ]);
    } catch (\Exception $e) {
      // Log error
      // Hatayı logla
      Log::error('User edit error: ' . $e->getMessage());
      Log::error($e->getTraceAsString());

      // Return error response
      // Hata yanıtı döndür
      return response()->json([
        'success' => false,
        'message' => __('An error occurred while fetching user data')
      ], 500);
    }
  }

  /**
   * Update the specified user in storage.
   * Belirtilen kullanıcıyı veritabanında günceller.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, $id)
  {
    try {
      // Find user
      // Kullanıcıyı bul
      $user = User::findOrFail($id);

      // Validate request
      // İstek doğrulama
      $validationRules = [
        'name' => 'required|string|max:255',
        'username' => [
          'required',
          'string',
          'max:255',
          Rule::unique('users')->ignore($id),
        ],
        'email' => [
          'required',
          'string',
          'email',
          'max:255',
          Rule::unique('users')->ignore($id),
        ],
        'role_id' => 'required|exists:roles,id',
        'reward_system_active' => 'nullable|in:0,1',
        'status' => 'required|integer|in:0,1,2'
      ];

      // Password is optional in update
      // Güncelleme sırasında şifre opsiyonel
      if ($request->filled('password')) {
        $validationRules['password'] = 'string|min:4|confirmed';
      }

      $validated = $request->validate($validationRules);

      // Update user
      // Kullanıcıyı güncelle
      $user->name = $validated['name'];
      $user->username = $validated['username'];
      $user->email = $validated['email'];

      // Update password if provided
      // Şifre sağlandıysa güncelle
      if ($request->filled('password')) {
        $user->password = Hash::make($validated['password']);
      }

      // Reward system için özel işlem
      $user->reward_system_active = isset($validated['reward_system_active'])
          ? filter_var($validated['reward_system_active'], FILTER_VALIDATE_BOOLEAN)
          : false;

      // Status update
      // Durum güncelleme
      $user->status = $validated['status'];

      // Save user
      // Kullanıcıyı kaydet
      $user->save();

      // Update role
      // Rolü güncelle
      if (isset($validated['role_id'])) {
        // Remove all current roles
        // Mevcut tüm rolleri kaldır
        $user->roles()->detach();

        // Assign new role
        // Yeni rol ata
        $role = Role::findById($validated['role_id']);
        $user->assignRole($role);
      }

      // Return success response
      // Başarı yanıtı döndür
      return response()->json([
        'success' => true,
        'message' => __('user_updated_successfully'),
        'data' => $user
      ]);
    } catch (\Illuminate\Validation\ValidationException $e) {
      // Return validation errors
      // Doğrulama hatalarını döndür
      return response()->json([
        'success' => false,
        'message' => __('Validation error'),
        'errors' => $e->errors()
      ], 422);
    } catch (\Exception $e) {
      // Log error
      // Hatayı logla
      Log::error('User update error: ' . $e->getMessage());
      Log::error($e->getTraceAsString());

      // Return error response
      // Hata yanıtı döndür
      return response()->json([
        'success' => false,
        'message' => __('An error occurred while updating the user')
      ], 500);
    }
  }

  /**
   * Remove the specified user from storage.
   * Belirtilen kullanıcıyı veritabanından siler.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    try {
      // Find user
      // Kullanıcıyı bul
      $user = User::findOrFail($id);

      // Delete user
      // Kullanıcıyı sil
      $user->delete();

      // Return success response
      // Başarı yanıtı döndür
      return response()->json([
        'success' => true,
        'message' => __('User deleted successfully')
      ]);
    } catch (\Exception $e) {
      // Log error
      // Hatayı logla
      Log::error('User deletion error: ' . $e->getMessage());
      Log::error($e->getTraceAsString());

      // Return error response
      // Hata yanıtı döndür
      return response()->json([
        'success' => false,
        'message' => __('An error occurred while deleting the user')
      ], 500);
    }
  }

  /**
   * Manually verify user's email
   * Kullanıcının e-posta adresini manuel olarak doğrular
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function verifyEmail($id)
  {
    try {
      // Find user
      // Kullanıcıyı bul
      $user = User::findOrFail($id);

      // If email is already verified, return success with message
      // Eğer e-posta zaten doğrulanmışsa, başarı yanıtı döndür
      if ($user->hasVerifiedEmail()) {
        return response()->json([
          'success' => true,
          'message' => __('Email is already verified')
        ]);
      }

      // Verify email by updating email_verified_at column
      // email_verified_at sütununu güncelleyerek e-postayı doğrula
      $user->markEmailAsVerified();

      // Set user status to active (2) if it was pending (0)
      // Eğer beklemedeyse (0) kullanıcı durumunu aktif (2) olarak ayarla
      if ($user->status === 0) {
        $user->status = 2; // Active
        $user->save();
      }

      // Return success response
      // Başarı yanıtı döndür
      return response()->json([
        'success' => true,
        'message' => __('Email verified successfully')
      ]);
    } catch (\Exception $e) {
      // Log error
      // Hatayı logla
      Log::error('Email verification error: ' . $e->getMessage());
      Log::error($e->getTraceAsString());

      // Return error response
      // Hata yanıtı döndür
      return response()->json([
        'success' => false,
        'message' => __('An error occurred while verifying email')
      ], 500);
    }
  }

  /**
   * Toggle user status (activate/deactivate)
   * Kullanıcı durumunu değiştirir (aktifleştir/devre dışı bırak)
   *
   * @param  int  $id
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function toggleStatus($id, Request $request)
  {
    try {
      // Find user
      // Kullanıcıyı bul
      $user = User::findOrFail($id);

      // Get status from request (2 for active, 1 for inactive)
      // İstekten durumu al (2 aktif, 1 pasif)
      $status = $request->input('status');

      // Validate status
      // Durumu doğrula
      if (!in_array($status, [1, 2])) {
        return response()->json([
          'success' => false,
          'message' => __('Invalid status value')
        ], 422);
      }

      // Update user status
      // Kullanıcı durumunu güncelle
      $user->status = $status;
      $user->save();

      // Return success response
      // Başarı yanıtı döndür
      return response()->json([
        'success' => true,
        'message' => $status == 2
          ? __('user_activated_successfully')
          : __('user_deactivated_successfully')
      ]);
    } catch (\Exception $e) {
      // Log error
      // Hatayı logla
      Log::error('Toggle user status error: ' . $e->getMessage());
      Log::error($e->getTraceAsString());

      // Return error response
      // Hata yanıtı döndür
      return response()->json([
        'success' => false,
        'message' => __('An error occurred while updating user status')
      ], 500);
    }
  }

  /**
   * Toggle reward system status for user
   * Kullanıcı için ödül sistemi durumunu değiştirir
   *
   * @param  int  $id
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function toggleReward($id, Request $request)
  {
    try {
      // Find user
      // Kullanıcıyı bul
      $user = User::findOrFail($id);

      // Get reward status from request (true/false)
      // İstekten ödül durumunu al (true/false)
      $rewardStatus = filter_var($request->input('reward_system_active'), FILTER_VALIDATE_BOOLEAN);

      // Update user reward system status
      // Kullanıcı ödül sistemi durumunu güncelle
      $user->reward_system_active = $rewardStatus;
      $user->save();

      // Return success response
      // Başarı yanıtı döndür
      return response()->json([
        'success' => true,
        'message' => $rewardStatus
          ? __('reward_system_enabled_successfully')
          : __('reward_system_disabled_successfully')
      ]);
    } catch (\Exception $e) {
      // Log error
      // Hatayı logla
      Log::error('Toggle user reward system error: ' . $e->getMessage());
      Log::error($e->getTraceAsString());

      // Return error response
      // Hata yanıtı döndür
      return response()->json([
        'success' => false,
        'message' => __('An error occurred while updating reward system status')
      ], 500);
    }
  }

  /**
   * Delete multiple users at once
   * Birden çok kullanıcıyı aynı anda siler
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function bulkDestroy(Request $request)
  {
    try {
      // Validate request
      // İsteği doğrula
      $validated = $request->validate([
        'ids' => 'required|array',
        'ids.*' => 'integer|exists:users,id'
      ]);

      // Get the number of users to be deleted
      // Silinecek kullanıcı sayısını al
      $count = count($validated['ids']);

      // Delete users
      // Kullanıcıları sil
      User::whereIn('id', $validated['ids'])->delete();

      // Return success response
      // Başarı yanıtı döndür
      return response()->json([
        'success' => true,
        'message' => __('users_deleted_successfully', ['count' => $count])
      ]);
    } catch (\Illuminate\Validation\ValidationException $e) {
      // Return validation errors
      // Doğrulama hatalarını döndür
      return response()->json([
        'success' => false,
        'message' => __('validation_error'),
        'errors' => $e->errors()
      ], 422);
    } catch (\Exception $e) {
      // Log error
      // Hatayı logla
      Log::error('Bulk user deletion error: ' . $e->getMessage());
      Log::error($e->getTraceAsString());

      // Return error response
      // Hata yanıtı döndür
      return response()->json([
        'success' => false,
        'message' => __('An error occurred while deleting users') . ': ' . $e->getMessage()
      ], 500);
    }
  }
}
