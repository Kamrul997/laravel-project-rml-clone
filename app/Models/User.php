<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'zone_id',
        'area_id',
        'unit_id',
        'sub_unit_id',
        'area_ids',
        'unit_ids',
        'subUnit_ids',
        'employee_id',
        'designation',
        'place',
        'joining_date',
        'courier_address',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'area_ids' => 'json',
        'unit_ids' => 'json',
        'subUnit_ids' => 'json',
    ];

    public function getImageAttribute($value)
    {
        if ($value) {
            return env('APP_URL') . 'assets/images/' . $value;
        }
    }

    // Get Zone -> SubunitUsers
    public static function getZoneSubunitUserIds($zoneId)
    {
        return self::role('Subunit')
            ->whereIn('unit_id', Unit::where('zone_id', $zoneId)->pluck('id')->toArray())
            ->pluck('id')->toArray();
    }

    // Get Area -> SubunitUsers
    public static function getAreaSubunitUserIds($areaId)
    {
        return self::role('Subunit')
            ->whereIn('unit_id', Unit::where('area_id', $areaId)->pluck('id')->toArray())
            ->pluck('id')->toArray();
    }

    // Get Unit -> SubunitUsers
    public static function getUnitSubunitUserIds($unitId)
    {
        return self::role('Subunit')
            ->where('unit_id', $unitId)
            ->pluck('id')->toArray();
    }

    public function notifications()
    {
        return $this->hasMany(CustomNotification::class, 'user_id', 'id');
    }
    public static function getTimeAgo($time)
    {
        $time_difference = time() - $time;

        if ($time_difference < 1) {
            return '1 second ago';
        }
        $condition = array(
            12 * 30 * 24 * 60 * 60 =>  'year',
            30 * 24 * 60 * 60       =>  'month',
            24 * 60 * 60            =>  'day',
            60 * 60                 =>  'hour',
            60                      =>  'minute',
            1                       =>  'second'
        );

        foreach ($condition as $secs => $str) {
            $d = $time_difference / $secs;

            if ($d >= 1) {
                $t = round($d);
                return $t . ' ' . $str . ($t > 1 ? 's' : '') . ' ago';
            }
        }

    }
}
