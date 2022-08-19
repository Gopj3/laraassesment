<?php

namespace App\Providers;

use App\Http\Controllers\Users\UsersController;
use App\Services\Interfaces\UserServiceInterface;
use App\Services\UserService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(UserServiceInterface::class, UserService::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::useBootstrap();

        /**
         * Paginate a standard Laravel Collection.
         *
         * @param int $perPage
         * @param int $total
         * @param int $page
         * @param string $pageName
         * @return array
         */
        Collection::macro('paginate', function($perPage, $total = null, $page = null, $pageName = 'page') {
            $page = $page ?: LengthAwarePaginator::resolveCurrentPage($pageName);

            return new LengthAwarePaginator(
                $this->forPage($page, $perPage),
                $total ?: $this->count(),
                $perPage,
                $page,
                [
                    'path' => LengthAwarePaginator::resolveCurrentPath(),
                    'pageName' => $pageName,
                ]
            );
        });

        Route::macro('softDeletes', function ($prefix) {
            Route::group([
                'prefix' => $prefix,
                'middleware' => ['auth'],
            ], function () {
                Route::get('trashed',  [UsersController::class, 'trashed'])->name('users.trashed');
                Route::patch('{user}/restore', [UsersController::class, 'restore'])->name('users.restore');
                Route::delete('{user}/delete', [UsersController::class, 'delete'])->name('users.delete');
            });
        });
    }
}
