<?php

namespace App\Repositories;

use App\Models\Role;
use App\Models\Vendor;
use Illuminate\Support\Facades\Hash;

class VendorRepository {

    public function store($validatedData){
        $role_id = Role::where('slug','vendor')->value('id');
        $data = [
            'role_id' => $role_id,
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'phone' => $validatedData['phone'],
            'password' => Hash::make($validatedData['password']),
        ];

        return Vendor::create($data)->fresh();
    }
}
