<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserInvoiceAddress extends Model
{
    use HasFactory;

    /**
     * Display text.
     *
     * @return string
     */
    public function getText()
    {
        return implode(', ', [
            $this->name,
            $this->region,
            $this->country,
        ]);
    }

    /**
     * Set the address as favorite.
     */
    public function setAsFavorite()
    {
        // Set all other user addresses as non-fav
        $this->user->addresses()->update(['favorite' => false]);

        $this->favorite = true;
        $this->save();
    }

    /**
     * Set the address as non-favorite.
     */
    public function setAsNonFavorite()
    {
        $this->favorite = false;
        $this->save();
    }

    /**
     * Get the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
