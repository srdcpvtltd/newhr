<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CrmEnquery extends Model
{
    use HasFactory;

    protected $table = 'crm_enquires';

    protected $fillable = [
        'name',
        'email',
        'number',
        'address',
        'message',
        'created_by',
        'updated_by'
    ];

    public function assignedUser()
{
    
    return $this->belongsTo(User::class, 'assign_user');
}

public function department()
    {
        return $this->belongsTo(Department::class);
    }


}
