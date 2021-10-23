<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\MangaJob;
use App\Modules\Scrappers\MangaSearchScrapper;
use \Exception;

class MangaScrappingOneCommand extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */

    protected $name = 'scrapping:one';

    protected $signature = 'scrapping:one {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scrap one specifiq manga from mangakakalot.com .';

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
        $name = $this->argument('name');
        $name = str_replace(' ','_', strtolower($name));

        $mangaListScrapper = app(MangaSearchScrapper::class);
        $mangaListScrapper->setUrl("https://mangakakalot.com/search/story/$name");
        $mangas = $mangaListScrapper->start();

        $manga = array_shift($mangas);
        if($manga == null) {
            throw new Exception('No manga found with this name');
        }

        dispatch(new MangaJob($manga));

    }
}