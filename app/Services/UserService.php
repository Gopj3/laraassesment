<?php
declare(strict_types=1);

namespace App\Services;

use App\Domain\Enums\PrefixNameEnum;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class UserService
{
    /**
     * Define the validation rules for the model.
     *
     * @param  ?User $user
     *
     * @return array
     */
    public function rules(?User $user = null): array
    {
        return [
            'firstname' => 'required|max:255|string',
            'lastname' => 'required|string|max:255',
            'username' => 'required|string|max:255',
            'email' => ['required', 'max:255', Rule::unique('users')->ignore($user)],
            'middlename' => 'string|max:255',
            'suffixname' => 'string|max:255',
            'prefixname' => [new Enum(PrefixNameEnum::class)],
            'file' => 'file|image|max:10240',
            'password' => 'string'
        ];
    }

    /**
     * Retrieve all resources and paginate.
     *
     * @param ?int $perPage
     *
     * @return LengthAwarePaginator
     */
    public function list(?int $perPage = null): LengthAwarePaginator
    {
        $rowsToShow = $perPage ?? config('constants.defaultItemsPerPage');

        return User::paginate($rowsToShow);
    }

    /**
     * Create model resource.
     *
     * @param  array $attributes
     *
     * @return User
     */
    public function store(array $attributes): User
    {
        $attributes['password'] = $this->hash($attributes['password']);

        return User::create($attributes);
    }

    /**
     * Retrieve model resource details.
     * Abort to 404 if not found.
     *
     * @param  int $id
     *
     * @return ?User
     */
    public function find(int $id): ?User
    {
        return User::findOrFail($id);
    }

    /**
     * Update model resource.
     *
     * @param  User    $user
     * @param  array   $attributes
     * @return boolean
     */
    public function update(User $user, array $attributes): bool
    {
        if (isset($attributes['password'])) {
            $attributes['password'] = $this->hash($attributes['password']);
        }

        $user->update($attributes);

        return $user->save();
    }

    /**
     * Permanently delete model resource.
     *
     * @param  User $user
     *
     * @return void
     */
    public function destroy(User $user): void
    {
    }

    /**
     * Include only soft deleted records in the results.
     *
     * @return LengthAwarePaginator
     */
    public function listTrashed(): LengthAwarePaginator
    {
        $rowsToShow = $perPage ?? config('constants.defaultItemsPerPage');

        return User::onlyTrashed()->paginate($rowsToShow);
    }

    /**
     * Restore model resource.
     *
     * @param  string|int $id
     *
     * @return void
     */
    public function restore(string|int $id): void
    {
        User::withTrashed()->find($id)->restore();
    }

    /**
     * Soft delete model resource.
     *
     * @param  User $user
     * @return void
     */
    public function delete(User $user): void
    {
        $user->delete();
    }

    /**
     * Generate random hash key.
     *
     * @param  string $key
     * @return string
     */
    public function hash(string $key): string
    {
        return Hash::make($key);
    }

    /**
     * Upload the given file.
     *
     * @param  UploadedFile $file
     *
     * @return ?string
     */
    public function upload(UploadedFile $file): ?string
    {
        $filename = time().$file->getClientOriginalName();

        return Storage::disk('local')->putFileAs(
            'files/avatars',
            $file,
            $filename
        );
    }
}
