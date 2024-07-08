<?php

namespace App\Admin\Controllers;

use App\Models\Question;
use App\Models\Member;
use App\Models\Tag;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use App\Util\AI;
use App\Admin\Imports\QuestionImport;


class QuestionController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '質問管理';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Question());
        $grid->model()->orderBy('id', 'desc');

        $grid->expandFilter();
        //議員でフィルタをかける
        $grid->filter(function($filter){
            $filter->column(1 / 2, function ($filter) {
                $filter->disableIdFilter();
                $members = Member::pluck('name', 'id');
                $filter->equal('member_id', '議員氏名')->select($members);
                $filter->like('title', 'タイトル');
            });
            $filter->column(1 / 2, function ($filter) {
                $filter->like('mtg', '定例会');
                //タグでフィルタをかける（or）
                $tags = Tag::pluck('tag', 'tag');
                $filter->where(function ($query) {
                    $query->whereIn('id', function ($query) {
                        $query->select('question_id')->from('tags')->whereIn('tag', $this->input);
                    });
                }, 'タグ')->multipleSelect($tags);
            });
        });

        $grid->column('id', __('Id'));
        $grid->column('member.name', __('氏名'));
        $grid->column('date', __('議会だより日時'))->hide();
        $grid->column('title', __('タイトル'));
        //質問内容の文字数を制限
        $grid->column('summary', __('質問内容'))->display(function ($summary) {
            return mb_substr($summary, 0, 40) . '...';
        })->hide();
        $grid->column('mtg', __('定例会'))->hide();
        $grid->tags('タグ')->pluck('tag')->label();
        $grid->column('created_at', __('作成日時'))->hide();
        $grid->column('updated_at', __('更新日時'))->hide();
        $grid->column('deleted_at', __('削除日時'))->hide();

        $grid->tools(function ($tools) {
            $tools->append(new QuestionImport());
        });

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(Question::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('member_id', __('Member id'));
        $show->field('date', __('Date'));
        $show->field('title', __('Title'));
        $show->field('content', __('Content'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));
        $show->field('deleted_at', __('Deleted at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Question());

        $form->select('member_id', __('議員氏名'))->options(Member::all()->pluck('name', 'id'))->rules('required');
        $form->date('date', __('議会だより日時'))->default(date('Y-m-d'));
        $form->text('title', __('タイトル'))->rules('required');
        $form->textarea('content', __('質問内容'))->rules('required');
        $form->textarea('answer', __('回答内容'))->rules('required');
        $form->textarea('summary', __('要約'))->readonly();

        //質問内容を入力したら、OpenAIのAPIを使ってキーワードを取得する
        $form->saving(function (Form $form) {
            $openai = new AI();
            $summary = $openai->get_summary($form->title . $form->content . $form->answer);
            $form->summary = $summary;
        });

        $form->saved(function (Form $form) {
            //質問内容を入力したら、OpenAIのAPIを使ってキーワードを取得する
            $openai = new AI();
            $keyword = $openai->get_keyword($form->title . $form->content . $form->answer);
            \Log::info($keyword);
            //$keywordから,で区切って1つづつをTagモデルに保存する
            $keywords = explode('、', $keyword);
            foreach ($keywords as $keyword) {
                if($keyword){
                    //タグが既にあるかどうかを確認する
                    $tag = Tag::where('member_id', $form->member_id)->where('question_id', $form->model()->id)->where('tag', $keyword)->first();
                    if($tag){
                        continue;
                    }else{
                        $tag = new Tag();
                        $tag->member_id = $form->member_id;
                        $tag->question_id = $form->model()->id;
                        $tag->tag = $keyword;
                        $tag->save();
                    }
                }
            }
        });

        return $form;
    }
}
