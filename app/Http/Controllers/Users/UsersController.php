<?php
declare(strict_types=1);

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\UserService;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\View\View;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

/**
 * class UserController
 */
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
     * @return View
     */
    public function index(): View
    {
        try {
            $users = $this->userService->list();

            return view('users.index', compact('users'));
        } catch (Throwable $e) {
            $this->logger->error($e->getMessage(), ['exception' => $e]);

            return view('welcome');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create(): View
    {
        return view('users.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param UserRequest $request
     *
     * @return View
     */
    public function store(UserRequest $request): View
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

            return view('users.show', compact('user'));
        } catch (Exception $e) {
            $this->logger->error($e->getMessage(), ['exception' => $e]);

            abort(500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return View
     */
    public function show(int $id): View
    {
        try {
            $user = $this->userService->find($id);
            $user = new UserResource($user);

            return view('users.show', compact('user'));
        } catch (ModelNotFoundException $e) {
            $this->logger->error($e->getMessage(), ['exception' => $e]);

            abort(404);
        } catch (Throwable $e) {
            $this->logger->error($e->getMessage(), ['exception' => $e]);

            abort(500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return View
     */
    public function edit(int $id): View
    {
        try {
            $user = $this->userService->find($id);

            return view('users.edit', compact('user'));
        } catch (ModelNotFoundException $e) {
            abort(404);
        } catch (Throwable $e) {
            $this->logger->error($e->getMessage(), ['exception' => $e]);

            abort(500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UserRequest $request
     * @param User $user
     *
     * @return View
     */
    public function update(User $user, UserRequest $request): View
    {
        try {
            /**@var UploadedFile $uploadedFile */
            $uploadedFile = $request->file()['file'] ?? null;

            if ($uploadedFile) {
                $path = $this->userService->upload($uploadedFile);
            }

            $attributes = $request->all();
            $password = $request->get('password');

            if (isset($password) && !empty($password)) {
                $attributes['password'] = $this->userService->hash($password);
            }else {
                unset($attributes['password']);
            }

            $this->userService->update(
                $user, [
                    ...$attributes,
                    'photo' => $path ?? $user->photo,
                ]
            );

            return view('users.show', compact('user'));
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage(), ['exception' => $e]);

            abort(500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function destroy(int $id): Response
    {
        try {
            $this->userService->destroy($id);

            return back()->with('success', 'User successfully deleted');
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage(), ['exception' => $e]);

            abort(500);
        }
    }

    /**
     * Display a list of trashed users
     *
     * @return View
     */
    public function trashed(): View
    {
        try {
            $users = $this->userService->listTrashed();

            return view('users.trashed', compact('users'));
        } catch (Throwable $e) {
            $this->logger->error($e->getMessage(), ['exception' => $e]);

            abort(500);
        }
    }

    /**
     * @param int $id
     *
     * @return Response
     */
    public function restore(int $id): Response
    {
        try {
            $this->userService->restore($id);

            return back()->with('success', 'User successfully restored');
        } catch (Throwable $e) {
            $this->logger->error($e->getMessage(), ['exception' => $e]);

            abort(500);
        }
    }

    /**
     * Soft delete user
     *
     * @param int $id
     *
     * @return Response
     */
    public function delete(int $id): Response
    {
        try {
            // better to use policy or gate
            if (auth()->user()->id === $id) {
                // Defense from fool (me :D) but ofc user can delete -> logout
                return back()->with('error', 'You are not allowed to delete yourself');
            }

            $this->userService->delete($id);
            $users = $this->userService->list();

            return response(view('users.index', compact('users')));
        } catch (Exception $e) {
            $this->logger->error($e->getMessage(), ['exception' => $e]);

           abort(500);
        }
    }
}
