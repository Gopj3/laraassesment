<?php
declare(strict_types=1);

namespace App\Http\Requests;

use App\Services\Interfaces\UserServiceInterface;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Foundation\Http\FormRequest;

final class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     * @throws BindingResolutionException
     */
    public function rules()
    {
        return $this->container->make(
            UserServiceInterface::class
        )->rules($this->user);
    }
}
