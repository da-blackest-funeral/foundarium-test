<?php

namespace App\Models;

use App\Exceptions\CarIsBusyException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Car
 *
 * @property int $id
 * @property string $name
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $users
 * @property-read int|null $users_count
 * @method static \Database\Factories\CarFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Car newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Car newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Car query()
 * @method static \Illuminate\Database\Eloquent\Builder|Car whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Car whereName($value)
 * @mixin \Eloquent
 */
class Car extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public $timestamps = false;

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function currentUser()
    {
        return $this->users()->first();
    }

    /**
     * @throws CarIsBusyException
     */
    public function giveTo(User $user)
    {
        if (! is_null($this->currentUser())) {
            throw new CarIsBusyException('This car is busy now.');
        }

        $this->users()->attach($user);
    }

    public function removeUser()
    {
        $this->users()->detach($this->currentUser());
    }
}
