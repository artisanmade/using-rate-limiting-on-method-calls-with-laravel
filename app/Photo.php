<?php

namespace App;

use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * Photo Model based on Eloquent ORM.
 */
class Photo extends Eloquent
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'photos';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'caption',
        'exif',
        'path',
        'size',
        'type',
    ];
}
