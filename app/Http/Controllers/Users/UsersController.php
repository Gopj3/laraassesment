<?php
declare(strict_types=1);

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserCollection;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\UserService;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Throwable;

final class UsersController extends Controller
{
    /**
     * @param UserService $userService
     * @param LoggerInterface $logger
     */
    public function __construct(
        private readonly UserService     $userService,
        private readonly LoggerInterface $logger
    )
    {
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            $users = $this->userService->list();

            return response()->json(new UserCollection($users));
        } catch (Throwable $e) {
            $this->logger->error($e->getMessage(), ['exception' => $e]);

            return response()->json('Unknown error occurred', 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param UserRequest $request
     *
     * @return JsonResponse
     */
    public function store(UserRequest $request): JsonResponse
    {
        try {
            /**@var UploadedFile $uploadedFile */
            $uploadedFile = $request->file()['file'] ?? null;

            if ($uploadedFile) {
                $path = $this->userService->upload($uploadedFile);
            }

            $attributes = $request->all();
            $attributes['password'] = $this->userService->hash($attributes['password']);

            $user = $this->userService->store([...$attributes, 'photo' => $path ?? null]);

            return response()->json($user->id, ResponseAlias::HTTP_OK);
        } catch (Exception $e) {
            $this->logger->error($e->getMessage(), ['exception' => $e]);

            return response()->json('Unknown error occurred', ResponseAlias::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        try {
            $user = $this->userService->find($id);

            return response()->json(new UserResource($user));
        } catch (ModelNotFoundException $e) {
            return response()->json($e->getMessage(), ResponseAlias::HTTP_NOT_FOUND);
        } catch (Throwable $e) {
            $this->logger->error($e->getMessage(), ['exception' => $e]);

            return response()->json('Unknown error occurred', ResponseAlias::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UserRequest $request
     * @param User $user
     *
     * @return JsonResponse
     */
    public function update(UserRequest $request, User $user): JsonResponse
    {
        try {
            /**@var UploadedFile $uploadedFile */
            $uploadedFile = $request->file()['file'] ?? null;

            if ($uploadedFile) {
                $path = $this->userService->upload($uploadedFile);
            }

            $attributes = $request->all();
            $attributes['password'] = $this->userService->hash($attributes['password']);

            $this->userService->update(
                $user, [
                    ...$attributes,
                    'photo' => $path ?? $user->photo,
                ]
            );

            return response()->json('Ok', ResponseAlias::HTTP_ACCEPTED);
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage(), ['exception' => $e]);

            return response()->json($e->getMessage(), ResponseAlias::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $this->userService->destroy($id);

            return response()->json('Ok', ResponseAlias::HTTP_NO_CONTENT);
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage(), ['exception' => $e]);

            return response()->json('Unknown error occurred', 500);
        }
    }

    /**
     * Display a list of trashed users
     *
     * @return JsonResponse
     */
    public function trashed(): JsonResponse
    {
        try {
            $users = $this->userService->listTrashed();

            return response()->json(new UserCollection($users));
        } catch (Throwable $e) {
            $this->logger->error($e->getMessage(), ['exception' => $e]);

            return response()->json('Unknown error occurred', 500);
        }
    }

    /**
     * @param string|int $id
     *
     * @return JsonResponse
     */
    public function restore(string|int $id): JsonResponse
    {
        try {
            $this->userService->restore($id);

            return response()->json('Ok', ResponseAlias::HTTP_OK);
        } catch (Throwable $e) {
            $this->logger->error($e->getMessage(), ['exception' => $e]);

            return response()->json($e->getMessage(), 500);
        }
    }

    /**
     * Soft delete user
     *
     * @param User $user
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(User $user): JsonResponse
    {
        try {
            // better to use policy or gate
            if (auth()->user()->id === $user->id) {
                // Defense from fool (me :D) but ofc user can delete -> logout
                return response()->json('You can not delete yourself', ResponseAlias::HTTP_FORBIDDEN);
            }

            $this->userService->delete($user);

            return response()->json('Ok', ResponseAlias::HTTP_NO_CONTENT);
        } catch (Exception $e) {
            $this->logger->error($e->getMessage(), ['exception' => $e]);

            return response()->json($e->getMessage(), ResponseAlias::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
