<?php

namespace App\Console\Commands;

use App\Models\Member;
use App\Models\Tag;
use App\Models\Category;
use App\Models\Question;
use Illuminate\Support\Facades\DB;
use Illuminate\Console\Command;
use App\Util\AI;

class CreateQuestionTagCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ai:question_tag';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '質問データから、質問タグの生成、議員データから抽象化されたタグを取得し、DBに保存する';

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

        //Tagテーブルのtruncate
        Tag::truncate();
        //Categoryテーブルのtruncate
        Category::truncate();

        //Questionに紐づくMemberのstatusが1のQuestionデータから各種タグの生成
        $questions = Question::whereHas('member', function($query){
            $query->where('status', 1);
        })->get();
        foreach($questions as $question){
            //タグを取得する
            $keyword = $openai->get_keyword($question->title . $question->content . $question->answer);
            //、を,に変換する
            $keyword = str_replace('、', ',', $keyword);
            //カンマ区切りでタグを保存する
            $keywords = explode(',', $keyword);
            foreach($keywords as $keyword){
                if($keyword){
                    //タグが既にあるかどうかを確認する
                    $tag = Tag::where('member_id', $question->member_id)->where('question_id', $question->id)->where('tag', $keyword)->first();
                    if($tag){
                        continue;
                    }else{
                        $tag = new Tag();
                        $tag->member_id = $question->member_id;
                        $tag->question_id = $question->id;
                        $tag->tag = $keyword;
                        $tag->save();
                    }
                }
            }
            //質問、回答内容から要約する
            // $summary = $openai->get_summary($question->title . $question->content . $question->answer);
            // $question->summary = $summary;
            // $question->save();
        }
        return;
    }
}
