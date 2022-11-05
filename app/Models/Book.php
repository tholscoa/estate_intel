<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;
    protected $fillable=[
        'name',
        'isbn',
        'authors',
        'country',
        'number_of_pages',
        'publisher',
        'release_date',
    ];


    protected $appends =[
        'authors'
    ];

    protected $hidden = [
        "updated_at", "created_at"
    ];

    public function authors(){
        return $this->hasMany(Author::class);
    }

    public function getAuthorsAttribute(){
        $authors = $this->authors()->get() ? $this->authors()->get() : null;
        $names = [];
        $i = 0;
        if ($authors != null) {
            foreach ($authors as $name) {
                $names[$i] = $name->author;
                $i++;
            }
        }
        return $names;
     }
}
