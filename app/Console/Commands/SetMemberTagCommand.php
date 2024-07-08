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

class SetMemberTagCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ai:update_tag';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '質問タグを議員タグに振り分ける';

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

        $question_tags = Tag::whereHas('member', function($query){
            $query->where('status', 1);
        })->where('status',1)->get();
        foreach($question_tags as $question_tag){
            $member_tag = $openai->get_member_tag($question_tag->tag, $question_tag->member_id);
            $category_id = Category::where('name',$member_tag)->where('member_id',$question_tag->member_id)->value('id');
            if(empty($category_id)){
                \Log::info('議員タグがありません');
                continue;
            }else{
                $question_tag->category_id = $category_id;
                $question_tag->save();
            }
        }
        return;
    }
}
