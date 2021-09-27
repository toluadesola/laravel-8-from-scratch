<?php


namespace App\Models;


use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\File;
use phpDocumentor\Reflection\Types\Collection;
use Spatie\YamlFrontMatter\YamlFrontMatter;

class OldPost
{
    public string $title;
    public string $excerpt;
    public string $date;
    public string $body;
    public string $slug;

    public function __construct($title, $excerpt, $date, $body, $slug)
    {
        $this->title = $title;
        $this->excerpt = $excerpt;
        $this->date = $date;
        $this->body = $body;
        $this->slug = $slug;
    }

    /**
     * @throws Exception
     */
    public static function all(): \Illuminate\Support\Collection
    {
        return cache()->rememberForever('posts.all', function () {
            return collect(File::files(resource_path("posts")))
                ->map(fn($file) => YamlFrontMatter::parseFile($file))
                ->map(fn($document) => new OldPost(
                    $document->matter('title'),
                    $document->matter('excerpt'),
                    $document->matter('date'),
                    $document->body(),
                    $document->matter('slug')
                )
            )->sortByDesc('date');
        });
    }

    /**
     * @throws Exception
     */
    public static function find(string $slug)
    {

//        return cache()->remember("posts.".$slug, 1200, fn() => file_get_contents($path));

        $post = static::all()->firstWhere('slug', $slug);

        if(! $post) throw new ModelNotFoundException();

        return $post;
    }
}
