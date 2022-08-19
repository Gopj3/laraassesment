<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Events\UserSaved;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'firstname',
        'lastname',
        'username',
        'email',
        'password',
        'middlename',
        'suffixname',
        'prefixname',
        'photo'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at, created_at'];

    protected $dispatchesEvents = [
        'saved' => UserSaved::class
    ];

    /**
     * @return HasMany
     */
    public function details(): HasMany
    {
        return $this->HasMany(Detail::class, 'user_id', 'id');
    }

    /**
     * @return ?string
     */
    public function getMiddleinitialAttribute(): ?string
    {
        return $this->middlename ? substr($this->middlename, 0, 1) : null;
    }

    /**
     * Retrieve the user's full name in the format:
     *  [firstname][ mi?][ lastname]
     * Where:
     *  [ mi?] is the optional middle initial.
     *
     * @return string
     */
    public function getFullNameAttribute(): string
    {
        return $this->middlename
            ? "$this->firstname $this->middlename $this->lastname"
            : "$this->firstname $this->lastname";
    }

    /**
     * Retrieve the default photo from storage.
     * Supply a base64 png image if the `photo` column is null.
     *
     * @return string
     */
    public function getAvatarAttribute(): string
    {
        $image = $this->photo
            ? Storage::disk('local')->get($this->photo)
            : Storage::disk('public')->get('default-avatar.png');

        $size = getimagesizefromstring($image);
        $extension = image_type_to_extension($size[2]);

        return "data:image/$extension;base64," . base64_encode($image);
    }
}
