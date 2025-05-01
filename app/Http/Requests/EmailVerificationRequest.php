<?php

namespace App\Http\Requests;

use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use App\Models\Admin\Users\User;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Carbon;

class EmailVerificationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // URL'den user'ı buluyoruz
        $user = User::find($this->route('id'));

        // Doğrulama işlemi için temel kontrol - kullanıcı ve hash kontrolü
        if (!$user || !hash_equals(
            sha1($user->email),
            (string) $this->route('hash')
        )) {
            return false;
        }

        // URL'nin imza kontrolü - Eğer imza geçerli değilse false dön
        // expires parametresi için kontrol, orijinal URL'den alarak
        if ($this->has('expires')) {
            $expires = $this->query('expires');
            if (Carbon::createFromTimestamp($expires)->isPast()) {
                return false; // Süresi dolmuş
            }
        }

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }

    /**
     * Fulfill the email verification request.
     *
     * @return void
     */
    public function fulfill()
    {
        $user = User::find($this->route('id'));

        if (!$user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();

            // Eğer kullanıcı durumu pending (0) ise aktif (2) yap
            if ($user->status === 0) {
                $user->status = 2; // Active
                $user->save();
            }

            event(new Verified($user));
        }
    }
}
