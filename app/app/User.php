<?php

namespace App;

use App\Models\UsersEvents;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Laravel\Lumen\Auth\Authorizable;

class User extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'surname', 'email', 'api_token', 'api_key', 'api_key_expired', 'remember_token', 'is_active', 'created_at', 'updated_at'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    /**
     * @Description Generation api key
     * @param $user_id
     * @param $api_token
     *
     * @return string
     * @throws \Exception
     */
    public static function createApiKey($user_id, $api_token)
    {
        return Hash::make(random_bytes(40) . $user_id . $api_token . time());
    }

    /**
     * @param $password
     * @return string
     */
    public function createPassword($password)
    {
        return Crypt::encrypt($password);
    }

    /**
     * @param int $length
     *
     * @return string
     */
    public function generatePassword($length = 6)
    {
        return mb_substr(
            str_shuffle('abcdefghjklmnopqrstuvwxyzABCDEFGHJKLMNOPQRSTUVWXYZ234567890!$%^&!$%^&'),
            0,
            $length
        );
    }

    /**
     * @return string
     */
    public function createApiToken()
    {
        return md5($this->email . Crypt::decrypt($this->password));
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function events()
    {
        return $this->hasMany(UsersEvents::class, 'user_id');
    }
}
