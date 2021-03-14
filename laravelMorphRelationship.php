<php

// Morph To One Laravel Example

// Morph relationship is a way of vinculate one table with many others
// by indicating the model and its ID that owns the data in the table

Schema::create('images', function (Blueprint $table) {
    $table->id();
    $table->string('url');
    $table->morphs('imageable');
    $table->timestamps();
});

Schema::create('posts', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->timestamps();
});

use App\Models\Image;

class Post extends Model
{
    use HasFactory;
    protected $fillable = ['name'];

    public function image()
    {
        return $this->morphOne(Image::class, 'imageable');
    }
}

class Image extends Model
{
    use HasFactory;
    protected $fillable = ['url', 'imageable_id', 'imageable_type'];

    public function imageable()
    {
        return $this->morphTo();
    }
}

// At class Post
public function show($id)
{
    $post = Post::find($id);
    $post->load('image');

    return response()->json([$post]);
}

// At class app/Providers/AppServiceProvider.php
use Illuminate\Database\Eloquent\Relations\Relation;

public function boot()
{
    Relation::morphMap([
        'Post' => 'App\Models\Post',
    ]);
}


