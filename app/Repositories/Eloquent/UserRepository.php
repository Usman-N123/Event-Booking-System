<?php

namespace App\Repositories\Eloquent;

use App\Enums\UserRole;
use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class UserRepository implements UserRepositoryInterface
{
    public function manage(\App\DTOs\User\ManageUserDTO $dto): User
    {
        if ($dto->id) {
            $user = $this->findById($dto->id);
            if (! $user) {
                throw new \Exception("User not found.", 404);
            }
        } else {
            $user = new User();
            $user->is_approved = ($dto->role !== \App\Enums\UserRole::Organizer);
        }

        $user->name = $dto->name;
        $user->email = $dto->email;
        $user->role = $dto->role->value;

        if ($dto->password) {
            $user->password = $dto->password;
        }

        if ($dto->profilePicture !== null) {
            if ($user->profile_picture_path) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($user->profile_picture_path);
            }
            $user->profile_picture_path = $dto->profilePicture->store('users/profile_pictures', 'public');
        }

        $user->save();

        return $user;
    }

    /**
     * Get the total number of registered users.
     */
    public function getTotalCount(): int
    {
        return User::count();
    }

    /**
     * Get the total number of organizer accounts.
     */
    public function getOrganizerCount(): int
    {
        return User::where('role', UserRole::Organizer->value)->count();
    }

    /**
     * Get all users paginated (for admin view).
     */
    public function getAllUsersPaginated(int $perPage = 15): LengthAwarePaginator
    {
        return User::latest()->paginate($perPage, ['*'], 'users_page')->withQueryString();
    }

    /**
     * Get all Organizers whose is_approved = false (pending approval queue).
     */
    public function getPendingOrganizers(int $perPage = 15): LengthAwarePaginator
    {
        return User::where('role', UserRole::Organizer->value)
            ->where('is_approved', false)
            ->latest()
            ->paginate($perPage, ['*'], 'organizers_page')->withQueryString();
    }

    /**
     * Find a user by their primary key.
     */
    public function findById(int $id): ?User
    {
        return User::find($id);
    }

    /**
     * Approve an organizer account (is_approved = true).
     */
    public function approve(int $id): bool
    {
        $user = $this->findById($id);
        if (! $user) {
            return false;
        }
        $user->is_approved = true;
        return $user->save();
    }

    /**
     * Reject / un-approve an organizer account (is_approved = false).
     */
    public function reject(int $id): bool
    {
        $user = $this->findById($id);
        if (! $user) {
            return false;
        }
        $user->is_approved = false;
        return $user->save();
    }

    /**
     * Soft-delete a user account.
     * Requires the User model to use SoftDeletes.
     */
    public function softDelete(int $id): bool
    {
        $user = $this->findById($id);
        return (bool) $user?->delete();
    }
}
