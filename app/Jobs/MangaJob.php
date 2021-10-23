<?php
  
namespace App\Jobs;
   
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Collection;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use App\Modules\Scrappers\MangaInfoScrapper;
use App\Modules\Scrappers\MangaChapterScrapper;
use App\Modules\Scrappers\MangaChapterImageScrapper;
use \Exception;

use App\Models\Manga;
use App\Models\Chapter;
use App\Models\Author;
use App\Models\Category;
use App\Models\Image;

class MangaJob implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;
  
    protected $manga;
  
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($manga)
    {   
        $this->manga = $manga;
    }
   
    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            // Begin a transaction
            DB::beginTransaction();
            $this->manga($this->manga);
            DB::commit();
        } catch (\Exception $e) {

            DB::rollback();

            throw $e;
        }

    }

    private function manga($manga) {
        $mangaInfoScrapper = app(MangaInfoScrapper::class);
        $mangaChapterScrapper = app(MangaChapterScrapper::class);

        $data = Manga::all();
        $data = $data->keyBy('name');

        $instance = $data->get($manga['manga']['title']);

        $mangaInfoScrapper->setUrl($manga['manga']['list']);
        $info = $mangaInfoScrapper->start();
        $info['last_update'] = Carbon::parse(strtotime($info['last_update']));

        if($instance == null) {
            try {
                $instance = new Manga(array('name' => $manga['manga']['title'], 'outer_link' => $manga['manga']['image'], 'status' => $info['status'], 'last_updated' => $info['last_update'], 'description' => $info['description']));
                $instance->save();

                $this->authors($info['authors'], $instance);
                $this->categories($info['categories'], $instance);
            } catch(Exception $e) {
                return;
            }

        } else {
            $instance->last_updated = $info['last_update'];
            $instance->status = $info['status'];
            $instance->save();
        }

        $mangaChapterScrapper->setUrl($manga['manga']['list']);
        $chapters = $mangaChapterScrapper->start();

        $this->chapters($chapters, $instance);
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

        $data = Chapter::where('manga_id', $manga->id)->get();
        $data = $data->keyBy('name');

        $chapters = array_reverse($chapters);
        foreach($chapters as $chapter) {
            $instance = $data->get($chapter['title']);
            if($instance == null) {
                $chapter['time'] = Carbon::parse(strtotime($chapter['time']));
                $instance = new Chapter(array('name' => $chapter['title'], 'outer_link' => $chapter['link'], 'upload' => $chapter['time'], 'numerotation' => $chapter['numerotation']));

                $manga->chapters()->save($instance);
            }

            $mangaChapterImageScrapper->setUrl($chapter['link']);

            $images = $mangaChapterImageScrapper->start();
            $this->images($images, $instance);
        }
    }

    private function images($images, $chapter) {

        $data = Image::where('chapter_id', $chapter->id)->get();
        $data = $data->keyBy('title');

        foreach($images as $image) {
            $instance = $data->get($image['title']);
            if($instance == null) {
                $instance = new Image(array('title' => $image['title'], 'outer_link' => $image['src']));
                $chapter->images()->save($instance);
            }
            
        }
    }

}