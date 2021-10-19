<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use App\Modules\Scrappers\MangaListScrapper;
use App\Modules\Scrappers\MangaInfoScrapper;
use App\Modules\Scrappers\MangaChapterScrapper;
use App\Modules\Scrappers\MangaChapterImageScrapper;
use \Exception;

use App\Models\Manga;
use App\Models\Chapter;
use App\Models\Author;
use App\Models\Category;
use App\Models\Image;

class MangaScrappingCommand extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */

    protected $name = 'manga:scrapping';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scrap data from mangakakalot.com .';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try {
            // Begin a transaction
            DB::beginTransaction();
            $mangaListScrapper = app(MangaListScrapper::class);
            $mangaListScrapper->setUrl('https://mangakakalot.com/manga_list');
            $mangas = $mangaListScrapper->start();

            $this->mangas($mangas);
            DB::commit();
        } catch (\Exception $e) {
            // An error occured; cancel the transaction...
            DB::rollback();
        
            // and throw the error again.
            throw $e;
        }
    }

    private function mangas($mangas) {

        $mangaInfoScrapper = app(MangaInfoScrapper::class);
        $mangaChapterScrapper = app(MangaChapterScrapper::class);

        $dataMangas = Manga::all()->toArray();

        $names = array_column($dataMangas , 'name');

        foreach($mangas as $manga) {

            $index = array_search($manga['manga']['title'], $names);

            if($index === false) {
                $mangaInfoScrapper->setUrl($manga['manga']['list']);
                try {
                    $info = $mangaInfoScrapper->start();

                    $info['last_update'] = Carbon::parse(strtotime($info['last_update']));
                    $instance = new Manga(array('name' => $manga['manga']['title'], 'outer_link' => $manga['manga']['image'], 'status' => $info['status'], 'last_updated' => $info['last_update'], 'description' => $info['description']));
                    $instance->save();

                    $this->authors($info['authors'], $instance);
                    $this->categories($info['categories'], $instance);
                } catch(Exception $e) {
                    continue;
                }

            }
            else {
                $instance = $dataMangas[$index];
            }

            //$mangaInfoScrapper->setUrl($manga['manga']['list']);

            $mangaChapterScrapper->setUrl($manga['manga']['list']);
            $chapters = $mangaChapterScrapper->start();

            $this->chapters($chapters, $instance);
        }
    }

    private function authors($authors, $manga) {
        $dataAuthors = Author::all()->toArray();
        $names = array_column($dataAuthors , 'name');

        foreach($authors as $author) {
            $index = array_search($author, $names);
            if($index === false) {
                $instance = new Author(array('name' => $author));
                $instance->save();
                $id = $instance->id;
            }
            else {
                $instance = $dataAuthors[$index];
                $id = $instance['id'];
            }

            $manga->authors()->attach($id);
        }
    }

    private function categories($categories, $manga) {

        $dataCategories = Category::all()->toArray();
        $names = array_column($dataCategories , 'name');

        foreach($categories as $category) {
            $index = array_search($category, $names);
            if($index === false) {
                $instance = new Category(array('name' => $category));
                $instance->save();
                $id = $instance->id;
            }
            else {
                $instance = $dataCategories[$index];
                $id = $instance['id'];
            }
            
            $manga->categories()->attach($id);
        }
    }

    private function chapters($chapters, $manga) {

        $mangaChapterImageScrapper = app(MangaChapterImageScrapper::class);

        $data = Chapter::where('manga_id', $manga->id)->get()->toArray();
        $names = array_column($data , 'name');

        $chapters = array_reverse($chapters);
        foreach($chapters as $chapter) {

            $index = array_search($chapter['title'], $names);
            if($index == null) {
                $chapter['time'] = Carbon::parse(strtotime($chapter['time']));
                $instance = new Chapter(array('name' => $chapter['title'], 'outer_link' => $chapter['link'], 'upload' => $chapter['time']));

                $manga->chapters()->save($instance);
            }
            else {
                $instance = $data[$index];
            }
            
            $mangaChapterImageScrapper->setUrl($chapter['link']);

            $images = $mangaChapterImageScrapper->start();
            $this->images($images, $instance);
        }
    }

    private function images($images, $chapter) {

        $data = Image::where('chapter_id', $chapter->id)->get()->toArray();
        $titles = array_column($data , 'title');

        foreach($images as $image) {
            $index = array_search($chapter['title'], $titles);

            if($index == null) {
                $instance = new Image(array('title' => $image['title'], 'outer_link' => $image['src']));
                $chapter->images()->save($instance);
            }
            else {
                $instance = $data[$index];
            }
        }
    }
}