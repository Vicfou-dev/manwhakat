<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Manga;

class MangaController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function index(Request $request)
    {
        $categories = $request->get('categories', '');
        $categories = explode(",", $categories);
        $categories = $categories[0] != "" ? $categories : array();

        $authors = $request->get('authors', '');
        $authors = explode(",", $authors);
        $authors = $authors[0] = "" ? $authors : array();

        $model = new Manga;
        
        $clause_authors = array();

        foreach($authors as $author) {
            $clause_authors[] = array('name', '=', $author);
        }
        
        $clause_categories = array();
        foreach($categories as $category) {
            $clause_categories[] = array('name', '=', $category);
        }

        $relations = array();

        $relations['categories'] = function($query) use ($clause_categories) {
            $query->where($clause_categories);
        };

        $relations['authors'] = function($query) use ($clause_authors) {
            $query->where($clause_authors);
        };

        // Manga::where('votes', '>', 100)->paginate(15);
        $mangas = $model->with('categories','authors')->whereHas('categories', $relations['categories'])->whereHas('authors', $relations['authors'])->orderBy('last_updated', 'desc')->paginate(15);

        return $mangas;
    }

    public function show(Request $request, Int $id)
    {
        $manga = Manga::with(['categories','authors','chapters' => function($query) {$query->orderBy('upload', 'desc');}])->find($id);

        return $manga;
    }

    //
}
