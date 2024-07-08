<?php

namespace App\Admin\Controllers;

use App\Models\Category;
use App\Models\Member;
use App\Models\Tag;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class TagController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '質問タグ管理';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Tag());
        $grid->model()->orderBy('id', 'desc');
        $grid->actions(function ($actions) {
            $actions->disableView();
            $actions->disableDelete();
            $actions->disableEdit();
        });

        // フィルターを開いた状態で表示する
        $grid->expandFilter();
        $grid->filter(function ($filter) {
            $filter->disableIdFilter();
            $filter->equal('member_id', '議員名')->select(Member::pluck('name', 'id'));
            $filter->equal('status', '有効フラグ')->select([0 => '非表示', 1 => '表示']);
        });

        $grid->column('id', __('ID'));
        $grid->column('member.name', __('議員名'));
        $grid->column('question.title', __('タイトル'));
        $grid->column('tag', __('質問タグ'));
        $grid->column('category_id', __('大分類タグ'))->editable('select',function($data){
            return  $categories = Category::where('member_id',$data->member_id)->pluck('name', 'id');
        });
        $status = [
            'on'  => ['value' => 1, 'text' => '表示', 'color' => 'success'],
            'off' => ['value' => 0, 'text' => '非表示', 'color' => 'danger'],
        ];
        $grid->column('status', __('有効フラグ'))->switch($status);
        $grid->column('created_at', __('Created at'))->hide();
        $grid->column('updated_at', __('Updated at'))->hide();
        $grid->column('deleted_at', __('Deleted at'))->hide();

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
        $show = new Show(Tag::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('member_id', __('Member id'));
        $show->field('question_id', __('Question id'));
        $show->field('tag', __('Tag'));
        $show->field('status', __('Status'));
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
        $form = new Form(new Tag());

        $form->number('member_id', __('Member id'));
        $form->number('question_id', __('Question id'));
        $form->text('tag', __('Tag'));
        $form->number('category_id', __('Category id'));
        $form->switch('status', __('Status'))->default(1);

        return $form;
    }
}
