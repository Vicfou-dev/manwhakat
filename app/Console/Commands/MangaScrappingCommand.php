<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\MangaJob;
use App\Modules\Scrappers\MangaListScrapper;

class MangaScrappingCommand extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */

    protected $name = 'scrapping:all';

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

        $mangaListScrapper = app(MangaListScrapper::class);
        $mangaListScrapper->setUrl('https://mangakakalot.com/manga_list');
        $mangas = $mangaListScrapper->start();

        foreach($mangas as $manga) {
            dispatch(new MangaJob($manga));
        }

    }
}