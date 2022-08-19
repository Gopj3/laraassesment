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
use Symfony\Component\HttpFoundation\Request;

class UserService
{
    /**
     * The model instance.
     *
     */
    protected User $model;

    /**
     * The request instance.
     *
     * @var \Illuminate\Http\Request
     */
    protected Request $request;

    /**
     * @param User $model
     * @param Request $request
     */
    public function __construct(User $model, Request $request)
    {
        $this->model = $model;
        $this->request = $request;
    }

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

        return $this->model::paginate($rowsToShow);
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
        // TODO make here id instead of user
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
        $rowsToShow = config('constants.defaultItemsPerPage');

        return $this->model::onlyTrashed()->paginate($rowsToShow);
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
