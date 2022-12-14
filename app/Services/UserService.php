<?php
declare(strict_types=1);

namespace App\Services;

use App\Domain\Enums\DetailKeysEnum;
use App\Domain\Enums\GendersEnum;
use App\Domain\Enums\PrefixNameEnum;
use App\Models\User;
use App\Services\Interfaces\UserServiceInterface;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;
use Symfony\Component\HttpFoundation\Request;

final class UserService implements UserServiceInterface
{
    /**
     * @param User $model
     * @param Request $request
     */
    public function __construct(protected readonly User $model, protected readonly Request $request)
    {}

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
            'username' => 'required|string|max:255|min:4',
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user)],
            'middlename' => 'string|max:255|nullable',
            'suffixname' => 'string|max:255|nullable',
            'prefixname' => [new Enum(PrefixNameEnum::class), 'nullable'],
            'file' => 'file|image|max:10240|mimes:jpeg,png,jpg',
            'password' => [
                'required',
                'string',
                'min:6',
                'max:16',
                'regex:/[a-z]/',
                'regex:/[A-Z]/',
                'regex:/[0-9]/',
                'regex:/[@$!%*#?&]/',
            ]
        ];
    }

    /**
     * Retrieve all resources and paginate.
     *
     * @return LengthAwarePaginator
     */
    public function list(): LengthAwarePaginator
    {
        return $this->model::paginate(config('constants.defaultItemsPerPage'));
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

        return $this->model::create($attributes);
    }

    /**
     * Retrieve model resource details.
     * Abort to 404 if not found.
     *
     * @param  int $id
     *
     * @return User
     */
    public function find(int $id): User
    {
        return $this->model::findOrFail($id);
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
        if (isset($attributes['password']) && !empty($attributes['password'])) {
            $attributes['password'] = $this->hash($attributes['password']);
        } else {
            unset($attributes['password']);
        }

        $user->update($attributes);

        return $user->save();
    }

    /**
     * Fully delete model resource.
     *
     * @param  int $id
     *
     * @return void
     */
    public function destroy(int $id): void
    {
        $this->model::withTrashed()->findOrFail($id)->forceDelete();
    }

    /**
     * Include only soft deleted records in the results.
     *
     * @return LengthAwarePaginator
     */
    public function listTrashed(): LengthAwarePaginator
    {
        return $this->model::onlyTrashed()->paginate(config('constants.defaultItemsPerPage'));
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
        $this->model::withTrashed()->find($id)->restore();
    }

    /**
     * Soft delete model resource.
     *
     * @param  int $id
     *
     * @return void
     */
    public function delete(int $id): void
    {
        $this->model::findOrFail($id)->delete();
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

    /**
     * @param User $user
     *
     * @return void
     */
    public function saveDetails(User $user): void
    {
        $gender = $user->prefixname
            ? ($user->prefixname === PrefixNameEnum::MR ? GendersEnum::Male->value : GendersEnum::Female->value)
            : null;

        $details = [
            DetailKeysEnum::FullName->value => $user->fullname,
            DetailKeysEnum::MiddleInitial->value => $user->middleinitial,
            DetailKeysEnum::Avatar->value => $user->photo,
            DetailKeysEnum::Gender->value => $gender
        ];

        $attributes = array_map(function (mixed $attribute, string $key) use ($user) {
            return ['user_id' => $user->id, 'key' => $key, 'value' => $attribute, 'type' => 'bio'];
        }, $details, array_keys($details));

        $user->details()->insert($attributes);
    }
}
