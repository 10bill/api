<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


/**
 * @OA\Schema(
 *     schema="Post",
 *     type="object",
 *     properties={
 *         @OA\Property(property="id", type="integer"),
 *         @OA\Property(property="titre", type="string"),
 *         @OA\Property(property="description", type="string"),
 *         @OA\Property(property="created_at", type="string", format="date-time"),
 *         @OA\Property(property="updated_at", type="string", format="date-time")
 *     }
 * )
 */

class Post extends Model
{
    use HasFactory;
}