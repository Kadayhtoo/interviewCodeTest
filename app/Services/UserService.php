<?php
namespace App\Services;

use App\Repositories\UserRepository;
use App\Jobs\ProcessFileUpload;
use \Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\User;
use Log;
class UserService
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function getUser()
    {
        return $this->userRepository->index();
    }

    public function storeUser($request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'profile_photo' => 'required|file|mimes:jpg,png,jpeg|max:100000',
            'id_document' => 'required|file|mimes:pdf,jpg,png,jpeg|max:100000',
        ]);
         
        Log::info('User Registration.', array(
            'name' => $request->name,
            'email' => $request->email,
            'password' =>$request->password,
            'profile_photo' => $request->file('profile_photo'),
            'id_document' => $request->file('id_document'),
        ));

        // Store files and get paths
        $profilePhotoPath = $request->file('profile_photo')->store('profile_photos', 'public');
        $idDocumentPath = $request->file('id_document')->store('id_documents', 'public');

        // Encrypt the file paths before saving to the database
        $encryptedProfilePhotoPath = \Crypt::encrypt($profilePhotoPath);
        $encryptedIdDocumentPath = \Crypt::encrypt($idDocumentPath);

        // Create user
        $user = new User([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'profile_photo' => $encryptedProfilePhotoPath,
            'id_document' => $encryptedIdDocumentPath,
            'role' => 'user',
        ]);

        $user->save();
         
        // ProcessFileUpload::dispatch($user,$request->file('profile_photo'),$request->file('id_document'));

        return response()->json(['message' => 'Registration successful. Please wait for admin approval.']);
        
    }
}