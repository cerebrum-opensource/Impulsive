<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Users extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name','last_name', 'salutation','username', 'email', 'status', 'user_type', 'is_logged_in', 'street', 'postal_code', 'city', 'country', 'additional_address'
    ];

    
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password','created_at', 'updated_at', 'deleted_at'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
 	* Saving new user details.
 	*
 	* @var string
 	*/
    public static function addUserDetails(
        $user_id,
        $domain_name,
        $first_name,
        $mi,
        $last_name,
        $maiden_name,
        $nick_name,
        $email,
        $status,
        $work_phone,
        $home_phone,
        $country_code,
        $mobile_number,
        $fax,
        $password
    )
    {


        return $newUserData = User::create([
            'connection' => 'mysql3',
            'name' => $first_name." ".$last_name,
            'domain_name' => $domain_name,
            'email' => $email,
            'password' => $password,
            'first_name' => $first_name,
            'last_name' => $last_name,
            'middle_name' => $mi,
            'nick_name' => $nick_name,
            'status' => $status,
            'fax' => $fax,
            'maiden_name' => $maiden_name,
            'parent_id' => $user_id,
            'work_phone' => $work_phone,
            'home_phone' => $home_phone,
            'country_code' => $country_code,
            'mobile_number' => $mobile_number
        ]);
    }

    /**
     * Deleting user record .
     *
     * @var int
     */
    public static function deleteUser($id) {
        $affectedRows = User::where('id','=', $id)->delete();

        if ($affectedRows) {
            return redirect('/Setting/ManageUser')->with('success',
                'Success');
        } else {
            return redirect('/Setting/ManageUser')->with('error',
                'Error.');
        }
    }

    /**
     * Deactivate user record .
     *
     * @var int
     */
    public static function deactivateUser($id,$status) {
        $responseData = User::find($id);
        $responseData->update([
            'status' => $status
        ]);
        return $responseData;
    }

    /**
     * Update password .
     *
     * @var int
     * @var string
     */
    public static function updatePassword(
        $user,
        $password
    ){
        $responseData = User::find($user);
        $responseData->update([
            'password' => $password
        ]);
        return $responseData;
    }

}
