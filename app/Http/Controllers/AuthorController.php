<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\File;
use App\Models\Setting;
use Illuminate\Support\Facades\Validator;

class AuthorController extends Controller
{
    public function index(Request $request)
    {
        return view('back.pages.home');
    }

    public function logout()
    {
        Auth::guard('web')->logout();
        return redirect()->route('author.login');
    }

    public function ResetForm(Request $request, $token = null)
    {
        $data = [
            'pageTitle' => 'Reset Password'
        ];

        return view('back.pages.auth.reset', $data)->with(['token' => $token, 'email' => $request->email]);
    }

    public function changeProfilePicture(Request $request)
    {
        $user = User::find(auth('web')->id());
        $path = 'back/dist/img/authors/';
        $file = $request->file('file');
        $old_picture = $user->getAttributes()['picture'];
        $file_path = $path.$old_picture;
        $new_picture_name = 'AIMG'.$user->id.time().rand(1,100000).'.jpg';

        if($old_picture != null && File::exists(public_path($file_path))){
            File::delete(public_path($file_path));
        }

        $upload = $file->move(public_path($path), $new_picture_name);
        if($upload){
            $user->update([
                'picture' => $new_picture_name
            ]);
            return response()->json(['status' => 1, 'msg' => 'Your profile picture has been successfully updated']);
        }else{
            return response()->json(['status' => 0, 'msg' => 'Something went wrong']);
        }
    }

    public function changeBlogLogo(Request $request)
    {
        // dd('berhasil');
        $settings =  Setting::find(1);
        $logo_path = 'back/dist/img/logo-favicon';
        $old_logo = $settings->getAttributes()['blog_logo'];
        $file = $request->file('blog_logo');
        $filename = time().'_'.rand(1,100000).'_larablog_logo.png';

        if($request->hasFile('blog_logo')){
            if($old_logo != null && File::exists(public_path($logo_path.'/'.$old_logo))){
                File::delete(public_path($logo_path.'/'.$old_logo));
            }

            $upload = $file->move(public_path($logo_path), $filename);
            if($upload){
                $settings->update([
                    'blog_logo' => $filename
                ]);
                return response()->json(['status' => 1, 'msg' => 'Larablog logo has been successfully updated']);
            }else{
                return response()->json(['status' => 0, 'msg' => 'Something went wrong']);
            }
        }
    }

    public function changeBlogFavicon(Request $request)
{
    $settings = Setting::find(1);
    $favicon_path = 'back/dist/img/logo-favicon';
    $old_favicon = $settings->getAttributes()['blog_favicon'];
    $file = $request->file('blog_favicon');

    // Define the validation rules
    $rules = [
        'blog_favicon' => 'required|image|mimes:jpg,jpeg,png|max:1024|dimensions:ratio=1/1',
    ];

    // Validate the uploaded file
    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
        // Validation failed, return the error response
        return response()->json(['status' => 0, 'msg' => $validator->errors()->first()]);
    }

    if ($old_favicon != null && File::exists(public_path($favicon_path . '/' . $old_favicon))) {
        File::delete(public_path($favicon_path . '/' . $old_favicon));
    }

    $filename = time() . '_' . rand(1, 100000) . '_larablog_favicon.ico';

    $upload = $file->move(public_path($favicon_path), $filename);
    if ($upload) {
        $settings->update([
            'blog_favicon' => $filename
        ]);
        return response()->json(['status' => 1, 'msg' => 'Blog favicon has been successfully updated.']);
    } else {
        return response()->json(['status' => 0, 'msg' => 'Something went wrong.']);
    }
}


}
