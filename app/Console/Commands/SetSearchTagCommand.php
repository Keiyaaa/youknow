<?php

namespace App\Console\Commands;

use App\Models\Member;
use App\Models\Tag;
use App\Models\Category;
use App\Models\Question;
use App\Models\SearchTag;
use Illuminate\Support\Facades\DB;
use Illuminate\Console\Command;
use App\Util\AI;

class SetSearchTagCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ai:search_tag';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '議員タグを検索用タグに振り分ける';

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
     * @return int
     */
    public function handle()
    {
        $openai = new AI();

        //議員タグの取得
        $categories = Category::where('status',1)->get();
        foreach($categories as $category){
            $searchTag = $openai->get_search_tag($category->name);
            $search_tag_id = SearchTag::where('name',$searchTag)->value('id');
            if(empty($search_tag_id)){
                \Log::info('検索タグがありません');
                continue;
            }else{
                $category->search_tag_id = $search_tag_id;
                $category->save();
            }
        }
        return;
    }
}
