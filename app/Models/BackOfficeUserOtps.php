<?php

namespace App\Models;

use App\Models\BackOfficeUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BackOfficeUserOtps extends Model
{
    use HasFactory;

    protected $table = 'back_office_users_otps';
    protected $guarded = ['id'];

    public function backOfficeUser()
    {
        return $this->belongsTo(BackOfficeUser::class, 'user_id' , 'id');
    }
}
