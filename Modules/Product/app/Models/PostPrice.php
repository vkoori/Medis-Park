<?php

namespace Modules\Product\Models;

use App\Traits\Paginatable;
use Illuminate\Database\Eloquent\Model;

class PostPrice extends Model
{
    use Paginatable;

    protected $table = 'post_prices';

    const UPDATED_AT = null;

    protected $fillable = [
        'coin_value',
    ];
}
