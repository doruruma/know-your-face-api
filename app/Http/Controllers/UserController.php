<?php

namespace App\Http\Controllers;

use app\Helpers\Constant;
use App\Http\Requests\StoreUserFormRequest;
use App\Http\Requests\UpdatePasswordFormRequest;
use App\Http\Requests\UpdateProfileFormRequest;
use App\Http\Requests\UpdateUserFormRequest;
use App\Http\Resources\UserCollection;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    protected $genders = ['m', 'f'];

    public function getAll(Request $request): UserCollection
    {
        $currentUserId = Auth::user()->id;
        $data = User::with('position');
        if ($request->has('search') && $request->search != '')
            $data = $data->where(function (Builder $query) use ($request) {
                $query->where('nik', 'like', "%$request->search%")
                    ->orWhere('name', 'like', "%$request->search%")
                    ->orWhere('phone', 'like', "%$request->search%")
                    ->orWhere('email', 'like', "%$request->search%");
            });
        if ($request->has('position_id') && $request->position_id)
            $data = $data->where('position_id', $request->position_id);
        $data = $data
            ->where('id', '!=', $currentUserId)
            ->orderBy('name')
            ->paginate(Constant::$PAGE_SIZE);
        return new UserCollection($data);
    }

    public function updatePassword(UpdatePasswordFormRequest $request, $id): JsonResponse
    {
        $user = User::find($id);
        if (!$user)
            return response()->json([
                'message' => 'Pegawai tidak ditemukan'
            ], 500);
        if (!Hash::check($request->old_password, $user->password))
            return response()->json([
                'errors' => [
                    'old_password' => 'Password lama tidak valid'
                ]
            ], 422);
        $user->password = Hash::make($request->new_password);
        $user->save();
        return (new UserResource($user))->response();
    }

    public function updateProfile(UpdateProfileFormRequest $request, $id): JsonResponse
    {
        $user = User::find($id);
        if (!$user)
            return response()->json([
                'message' => 'Pegawai tidak ditemukan'
            ], 500);
        $user->name = $request->name;
        $user->phone = $request->phone;
        $profileImage = $user->profile_image;
        try {
            if ($request->hasFile('profile_image')) {
                if ($profileImage != Constant::$USER_PROFILE_IMAGE)
                    Storage::delete("profile-images/$profileImage");
                $file = $request->file('profile_image');
                $profileImage = $user->email . $file->getClientOriginalName();
                $file->storeAs('profile-images', $profileImage);
                $user->profile_image = $profileImage;
            }
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
        }
        $user->save();
        return (new UserResource($user))->response();
    }

    public function getById($id): JsonResponse
    {
        $data = User::with('position')->find($id);
        if (!$data)
            return response()->json(null, 204);
        return (new UserResource($data))->response();
    }

    public function store(StoreUserFormRequest $request): JsonResponse
    {
        $user = new User;
        if (!in_array($request->gender, $this->genders))
            return response()->json([
                'errors' => [
                    'gender' => 'Jenis kelamin tidak valid'
                ]
            ], 422);
        $user->nik = $request->nik;
        $user->position_id = $request->position_id;
        $user->name = $request->name;
        $user->phone = $request->phone;
        $user->gender = $request->gender;
        $user->email = $request->email;
        $user->password = $request->password;
        $faceImage = null;
        $profileImage = Constant::$USER_PROFILE_IMAGE;
        try {
            if ($request->hasFile('profile_image')) {
                $file = $request->file('profile_image');
                $profileImage = $user->email . $file->getClientOriginalName();
                $file->storeAs('profile-images', $profileImage);
                $user->profile_image = $profileImage;
            }
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
        }
        try {
            if ($request->hasFile('face_image')) {
                $file = $request->file('face_image');
                $faceImage = $user->email . $file->getClientOriginalName();
                $file->storeAs('face-images', $faceImage);
                $user->face_image = $faceImage;
            }
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
        }
        $user->save();
        return (new UserResource($user))->response();
    }

    public function update(UpdateUserFormRequest $request, $id): JsonResponse
    {
        $user = User::find($id);
        if (!$user)
            return response()->json([
                'message' => 'Pegawai tidak ditemukan'
            ], 500);
        if (!in_array($request->gender, $this->genders))
            return response()->json([
                'errors' => [
                    'gender' => 'Jenis kelamin tidak valid'
                ]
            ], 422);
        $user->nik = $request->nik;
        $user->position_id = $request->position_id;
        $user->name = $request->name;
        $user->phone = $request->phone;
        $user->gender = $request->gender;
        $user->email = $request->email;
        $user->password = $request->password;
        $faceImage = null;
        $profileImage = $user->profile_image;
        $faceImage = $user->face_image;
        try {
            if ($request->hasFile('profile_image')) {
                if ($profileImage != Constant::$USER_PROFILE_IMAGE)
                    Storage::delete("profile-images/$profileImage");
                $file = $request->file('profile_image');
                $profileImage = $user->email . $file->getClientOriginalName();
                $file->storeAs('profile-images', $profileImage);
                $user->profile_image = $profileImage;
            }
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
        }
        try {
            if ($request->hasFile('face_image')) {
                if ($faceImage)
                    Storage::delete("face-images/$faceImage");
                $file = $request->file('face_image');
                $faceImage = $user->email . $file->getClientOriginalName();
                $file->storeAs('face-images', $faceImage);
                $user->face_image = $faceImage;
            }
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
        }
        $user->save();
        return (new UserResource($user))->response();
    }

    public function delete($id): JsonResponse
    {
        $currentUserId = Auth::user()->id;
        if ($id == $currentUserId)
            return response()->json([
                'message' => 'Tidak bisa menghapus akun ini'
            ], 500);
        $user = User::find($id);
        if (!$user)
            return response()->json([
                'message' => 'Pegawai tidak ditemukan'
            ], 500);
        try {
            if ($user->face_image)
                Storage::delete("face-images/$user->face_image");
            if ($user->profile_image != Constant::$USER_PROFILE_IMAGE)
                Storage::delete("profile-images/$user->profile_image");
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
        }
        $user->delete();
        return (new UserResource($user))->response();
    }
}
