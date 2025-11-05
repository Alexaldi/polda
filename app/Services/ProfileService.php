<?php

namespace App\Services;

use App\Interfaces\ProfileRepositoryInterface;
use App\Interfaces\InstitutionRepositoryInterface;
use App\Interfaces\DivisionRepositoryInterface;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
class ProfileService
{
    public function __construct(
        private ProfileRepositoryInterface $repo,
        private InstitutionRepositoryInterface $institutionRepo,
        private DivisionRepositoryInterface $divisionRepo
    ) {}

    public function getInstitutions()
    {
        return $this->institutionRepo->getAllOrderedByName();
    }

    public function getDivisions()
    {
        return $this->divisionRepo->getAllOrderedByName();
    }

    public function updateProfile($id, array $data,?UploadedFile $photo = null)
    {
        $user = $this->repo->findById($id);
        $newPath = null;
        
        try {
            if ($photo) {
                // simpan foto baru
                $newPath = $photo->store('profile', 'public');
                $data['photo'] = $newPath;
            } else {
                unset($data['photo']);
            }

            // update user
            $updatedUser = $this->repo->update($id, $data);

            // hapus foto lama kalau ada
            if ($newPath && $user->photo && $user->photo !== $newPath) {
                Storage::disk('public')->delete($user->photo);
            }

            return $updatedUser;
        } catch (\Throwable $th) {
            if ($newPath) {
                Storage::disk('public')->delete($newPath);
            }
            throw $th;
        }
    }

    public function deletePhoto($id)
    {
        $user = $this->repo->findById($id);
        if ($user->photo) {
            Storage::disk('public')->delete($user->photo);
            $this->repo->update($id, ['photo' => null]);
        }
        return true;
    }

    public function updatePassword($id, string $password)
    {
        return $this->repo->update($id, ['password' => Hash::make($password)]);
    }
}
