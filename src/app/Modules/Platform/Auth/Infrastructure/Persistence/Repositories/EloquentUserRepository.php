<?php

declare(strict_types=1);

namespace App\Modules\Platform\Auth\Infrastructure\Persistence\Repositories;

use App\Modules\Platform\Auth\Application\Contracts\UserRepository;
use App\Modules\Platform\Auth\Domain\Entities\AuthUser;
use App\Modules\Platform\Auth\Domain\ValueObjects\PhoneNumber;
use App\Modules\Platform\Auth\Infrastructure\Persistence\Models\PlatformUserModel;

final class EloquentUserRepository implements UserRepository
{
    public function findByPhone(PhoneNumber $phone): ?AuthUser
    {
        $row = PlatformUserModel::query()
            ->where('dial_code', $phone->dialCode)
            ->where('phone', $phone->nationalNumber)
            ->first();

        if ($row === null) {
            return null;
        }

        return new AuthUser(
            id: (int) $row->id,
            name: (string) $row->name,
            phone: $phone,
            active: (bool) $row->active,
        );
    }

    public function createUser(PhoneNumber $phone, string $name = ''): AuthUser
    {
        $row = PlatformUserModel::create([
            'name' => $name,
            'dial_code' => $phone->dialCode,
            'phone' => $phone->nationalNumber,
            'active' => true,
        ]);

        return new AuthUser(
            id: (int) $row->id,
            name: (string) $row->name,
            phone: $phone,
            active: (bool) $row->active,
        );
    }

    public function findOrCreate(PhoneNumber $phone): AuthUser
    {
        return $this->findByPhone($phone) ?? $this->createUser($phone);
    }
}
