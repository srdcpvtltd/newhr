<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\Vendors\VendorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VendorRegisterController extends Controller
{

    public function __construct(
        private VendorService $vendorService
    ) {}

    public function viewRegistrationForm()
    {
        return view('auth.vendorRegister');
    }

    public function getErrorMessage($data)
    {
        // dd($data,count($data));
        $message = [];
        foreach ($data as $key => $value) {
            $message[$key] = $value[0];
        }
        return $message;
    }
    public function store(Request $request)
    {
        // dd($request->all());
        try {
            $validatedData = Validator::make($request->all(), [
                'name' => ['required', 'string', 'max:25'],
                'email' => ['required', 'string', 'email', 'max:255'],
                'phone' => ['required', 'numeric'],
                'password' => ['required', 'string', 'confirmed'],
            ]);
            if ($validatedData->fails()) {
                // dd($validatedData->errors());
                return redirect()->back()->withErrors(self::getErrorMessage($validatedData->getMessageBag()->messages()));
            }
            $this->vendorService->saveVendorDetails($request->all());

            return redirect()->route('auth.login');
        } catch (\Throwable $th) {
            return redirect()->back();
        }
    }
}
