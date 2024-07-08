<?php

namespace App\Admin\Actions;

use App\Models\Category;
use App\Models\Member;
use App\Models\Tag;
use App\Models\Question;
use App\Models\SearchTag;
use Encore\Admin\Actions\RowAction;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Util\AI;

class CreateYouknowTag extends RowAction
{
    public $name = 'タグ生成';

    public function handle(Model $model)
    {
        //実行時間の上限値を500秒に設定する
        ini_set('max_execution_time', 500);
        //メモリの上限値を設定する
        ini_set('memory_limit', '1024M');

        $openai = new AI();
        //質問タグテーブルから該当議員のレコードを削除
        Tag::where('member_id', $model->id)->delete();

        //質問データから該当議員のレコードを取得する
        $questions = Question::where('member_id', $model->id)->get();
        foreach($questions as $question){
            //タグを取得する
            $keyword = $openai->get_keyword($question->title . $question->content . $question->answer);
            //、を,に変換する
            $keyword = str_replace('、', ',', $keyword);
            //タグを配列に変換する
            $keyword = explode(',', $keyword);
            //タグを登録する
            foreach($keyword as $key){
                if(empty($key)){
                    continue;
                }else{
                    //タグが既にあるかどうかを確認する
                    $tag = Tag::where('member_id', $model->id)->where('question_id', $question->id)->where('tag', $key)->first();
                    if($tag){
                        continue;
                    }
                    $tag = new Tag();
                    $tag->member_id = $model->id;
                    $tag->question_id = $question->id;
                    $tag->tag = $key;
                    $tag->status = 1;
                    $tag->save();
                }
            }
        }

        //議員タグの生成
        //議員タグテーブルから該当議員のレコードを削除
        Category::where('member_id', $model->id)->delete();

        //該当議員の質問タグを取得する
        $tags = Tag::where('member_id', $model->id)->where('status',1)->get();
        if(empty($tags)){
            \Log::info('タグがありません');
            return $this->response()->error('タグがありません。')->refresh();
        }else{
            foreach($tags as $tag){
                $str[] = $tag->tag;
            }
            $arr = $openai->get_category($str);
            //カンマ区切りでカテゴリーを保存する
            $arrs = explode(',', $arr);
            foreach($arrs as $arr){
                \Log::info($arr);
                $category = new Category();
                $category->member_id = $model->id;
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
        //質問タグを議員タグに振り分ける
        $question_tags = Tag::where('member_id', $model->id)->where('status',1)->get();
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
        //議員タグを検索用タグに振り分ける
        $categories = Category::where('member_id',$model->id)->get();
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

        return $this->response()->success('成功しました。')->redirect('/admin/members/');
    }

    public function dialog()
    {
        $this->confirm('この議員のタグデータを生成しますか?');
    }

}
