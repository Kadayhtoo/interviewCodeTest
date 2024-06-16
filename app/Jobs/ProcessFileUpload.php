<?php

namespace App\Jobs;

// use Illuminate\Bus\Queueable;
// use Illuminate\Contracts\Queue\ShouldBeUnique;
// use Illuminate\Contracts\Queue\ShouldQueue;
// use Illuminate\Foundation\Bus\Dispatchable;
// use Illuminate\Queue\InteractsWithQueue;
// use Illuminate\Queue\SerializesModels;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use \Crypt;

class ProcessFileUpload implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;
    protected $profilePhoto;
    protected $idDocument; 

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user, $profilePhoto, $idDocument)
    {
        $this->$user = $user;
        $this->profilePhoto = $profilePhoto;
        $this->idDocument = $idDocument;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $profilePhotoPath = $this->profilePhoto->store('profile_photos','public');
        $idDocumentPath = $this->idDocument->store('id_documents','public');

        // // Store files and get paths
        // $profilePhotoPath = $this->request->file('profile_photo')->store('profile_photos', 'public');
        // $idDocumentPath = $this->request->file('id_document')->store('id_documents', 'public');

        // Encrypt the file paths before saving to the database
        $encryptedProfilePhotoPath = \Crypt::encrypt($profilePhotoPath);
        $encryptedIdDocumentPath = \Crypt::encrypt($idDocumentPath);

        $this->user->update([
            'profile_photo' => $encryptedProfilePhotoPath,
            'id_document' => $encryptedIdDocumentPath,
        ]);
    }
}
