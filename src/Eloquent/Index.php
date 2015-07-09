<?php

namespace Metrique\Index\Eloquent;

use Illuminate\Database\Eloquent\Model;

class Index extends Model
{
    protected $fillable = ['params', 'order', 'title', 'slug', 'meta', 'href', 'disabled', 'navigation', 'published'];
}