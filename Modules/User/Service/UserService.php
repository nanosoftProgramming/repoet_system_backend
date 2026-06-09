<?php

namespace Modules\User\Service;

use Modules\User\App\Models\User;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Modules\Common\Helpers\UploadHelper;

class UserService
{
    use UploadHelper;

    function findAll($data = [], $relations = [], $role)
    {
        $query = User::whereRole($role)->with($relations)->latest();
        return getCaseCollection($query, $data);
    }


    public function changePassword($data)
    {
        $user = auth('user')->user();
        $user->update([
            'password' => Hash::make($data['new_password'])
        ]);
    }

    public function updateProfile($data)
    {
        $user = auth('user')->user();
        if (request()->hasFile('image')) {
            if ($user->image) {
                File::delete(public_path('uploads/user/' . $this->getImageName('user', $user->image)));
            }
            $data['image'] = $this->upload(request()->file('image'), 'user');
        }
        $user->update($data);
        return $user->fresh();
    }


    function save($data)
    {
        if (request()->hasFile('image')) {
            $data['image'] = $this->upload(request()->file('image'), 'user');
        }
        if (request()->hasFile('file')) {
            $data['file'] = $this->uploadFile(request()->file('file'), 'user/file');
        }
        $user = User::create($data);
        $user->assignRole($data['role']);
        return $user;
    }

    function update($user, $data)
    {
        if (request()->hasFile('image')) {
            File::delete(public_path('uploads/user/' . $this->getImageName('user', $user->image)));
            $data['image'] = $this->upload(request()->file('image'), 'user');
        }
        if (request()->hasFile('file')) {
            File::delete(public_path('uploads/user/' . $this->getImageName('user', $user->image)));
            $data['file'] = $this->uploadFile(request()->file('file'), 'user/file');
        }
        if (empty($data['password'])) {
        unset($data['password']); // حذفها من المصفوفة إذا كانت فارغة لمنع تحديثها
    } else {
        $data['password'] = Hash::make($data['password']); // تشفيرها فقط إذا أُرسلت
    }
        $user->update($data);
        return $user->fresh();
    }

    function toggleActivate($user)
    {
        $user->update([
            'is_active' => !$user->is_active
        ]);
        return $user->fresh();
    }
}
