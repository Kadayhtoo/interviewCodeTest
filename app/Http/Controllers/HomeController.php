<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Services\UserService;
use \Crypt;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    protected $userService;
    public function __construct(UserService $userService)
    {
        $this->middleware('auth');
        $this->userService = $userService;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $users=$this->userService->getUser();
        return view('home',compact("users"));
    }

    public function showPhoto(Request $request)
    {
        $decryptedProfilePhotoPath = \Crypt::decrypt($request->image);
        $file = Storage::disk('public')->get($decryptedProfilePhotoPath);

        return response($file)->header('Content-Type', 'image/jpeg'); // Adjust the content type if needed
    }
}
