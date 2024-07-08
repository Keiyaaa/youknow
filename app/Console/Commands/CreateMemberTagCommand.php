<?php

namespace App\Console\Commands;

use App\Models\Member;
use App\Models\Tag;
use App\Models\Category;
use App\Models\Question;
use Illuminate\Support\Facades\DB;
use Illuminate\Console\Command;
use App\Util\AI;

class CreateMemberTagCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ai:member_tag';

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

        //Categoryテーブルのtruncate
        Category::truncate();

        //議員タグの生成
        $members = Member::where('status',1)->get();
        foreach($members as $member){
            // Category::where('member_id', $member->id)->delete();
            $str = [];
            //member_idを指定して、タグを取得する
            $tags = Tag::where('member_id', $member->id)->where('status',1)->get();
            if(empty($tags)){
                \Log::info('タグがありません');
                continue;
            }else{
                foreach($tags as $tag){
                    $str[] = $tag->tag;
                }
                if(empty($str)){
                    \Log::info('タグがありません');
                    continue;
                }
                $arr = $openai->get_category($str);
                //カンマ区切りでカテゴリーを保存する
                $arrs = explode(',', $arr);
                foreach($arrs as $arr){
                    \Log::info($arr);
                    $category = new Category();
                    $category->member_id = $member->id;
                    //$arrの不要な文字を削除する
                    $arr = str_replace(' ', '', $arr);
                    //大文字の：を小文字に変換する
                    $arr = str_replace('：', ':', $arr);
                    $persent = explode(':', $arr);
                    //%の文字を削除する
                    if(isset($persent[1])){
                        $persent[1] = str_replace('%', '', $persent[1]);
                        $persent[1] = str_replace('％', '', $persent[1]);
                        $category->name = $persent[0];
                        $category->persent = $persent[1];
                        $category->save();
                    }
                }
            }
        }
        return;
    }
}
