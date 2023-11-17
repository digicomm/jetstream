<?php

namespace App\Http\Controllers;

use App\Actions\Fortify\UpdateUserProfileInformation;
use App\Models\User;
use Faker\Factory;
use GuzzleHttp\Exception\ClientException;
use Hshn\Base64EncodedFile\HttpFoundation\File\Base64EncodedFile;
use Hshn\Base64EncodedFile\HttpFoundation\File\UploadedBase64EncodedFile;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Laravel\Jetstream\HasProfilePhoto;
use Microsoft\Graph\Graph;

class LoginController extends Controller
{
    public function __construct()
    {
        $this->faker = Factory::create();
    }
    //
    public function msalLogin(Request $request)
    {
        $graph = new Graph();
        $graph->setAccessToken($request->account['access_token']);
        $graph_user = $graph->createRequest('GET', '/me')->setReturnType(\Microsoft\Graph\Model\User::class)->execute();
        $user = User::where(['microsoft_id' => $request->account['localAccountId']])->first();
            try {
            Auth::login($user);

            $user->username = strtolower(strstr($request->account['username'], '@', true));;
            $user->email = strtolower($request->account['username']);
            $user->given_name = $graph_user->getGivenName();
            $user->surname = $graph_user->getSurname();




            try {
                $previous = $user->profile_photo_path;
                $profilePhoto = $this->createFile($graph->createRequest('GET', '/me/photos/360x360/\$value')->execute()->getRawBody());
                $path = $profilePhoto->storePublicly('profile-photos', ['disk' => 'public']);
                if($previous) {
                    Storage::disk('public')->delete($previous);
                }
                $user->profile_photo_path = $path;

            } catch (ClientException $e) {
                //
            }
            $user->save();

            if ($intendedUrl = session()->get('url.intended')) {
                return redirect($intendedUrl);
            }
            return to_route('dashboard');
        } catch(\TypeError $e) {
            throw ValidationException::withMessages(['microsoft_login' => 'Microsoft account is not linked to an account.']);
        }



    }
    public function createFile($photo) {
        $tempFile = tmpfile();
        $tempFilePath = stream_get_meta_data($tempFile)['uri'];

        // Save file data in file
        file_put_contents($tempFilePath, $photo);

        $tempFileObject = new File($tempFilePath);
        $file = new UploadedFile(
            $tempFileObject->getPathname(),
            $tempFileObject->getFilename(),
            $tempFileObject->getMimeType(),
            0,
            true // Mark it as test, since the file isn't from real HTTP POST.
        );

        // Close this file after response is sent.
        // Closing the file will cause to remove it from temp director!
        app()->terminating(function () use ($tempFile) {
            fclose($tempFile);
        });
        return $file;
    }
}
