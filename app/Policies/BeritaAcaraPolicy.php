<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\BaStatus;
use App\Models\BeritaAcara;
use App\Models\User;

class BeritaAcaraPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->isActive();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, BeritaAcara $beritaAcara): bool
    {
        if (! $user->isActive()) {
            return false;
        }

        if ($user->isSuperadmin()) {
            return true;
        }

        return $beritaAcara->created_by === $user->id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->isActive();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, BeritaAcara $beritaAcara): bool
    {
        if (! $user->isActive() || $beritaAcara->isFinalized()) {
            return false;
        }

        if ($user->isSuperadmin()) {
            return true;
        }

        return $beritaAcara->created_by === $user->id && $beritaAcara->status->isEditable();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, BeritaAcara $beritaAcara): bool
    {
        if (! $user->isActive() || $beritaAcara->isFinalized()) {
            return false;
        }

        if ($user->isSuperadmin()) {
            return true;
        }

        return $beritaAcara->created_by === $user->id && $beritaAcara->status === BaStatus::Draft;
    }

    /**
     * Determine whether the user can archive the model.
     */
    public function archive(User $user, BeritaAcara $beritaAcara): bool
    {
        return $user->isSuperadmin() && $beritaAcara->status === BaStatus::Selesai;
    }
}
