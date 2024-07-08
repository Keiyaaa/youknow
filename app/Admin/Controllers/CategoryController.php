<?php

namespace App\Admin\Controllers;

use App\Models\Member;
use App\Models\Category;
use App\Models\SearchTag;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class CategoryController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '議員タグ管理';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Category());
        $grid->model()->orderBy('id', 'desc');
        $grid->actions(function ($actions) {
            $actions->disableView();
            $actions->disableDelete();
            // $actions->disableEdit();
        });
        $grid->expandFilter();
        //議員名でフィルタ
        $grid->filter(function ($filter) {
            $filter->disableIdFilter();
            $filter->equal('member_id', '議員名')->select(Member::pluck('name', 'id'));
            $filter->equal('search_tag_id', '検索タグ')->select(SearchTag::OrderBy('sort_order','asc')->pluck('name', 'id'));
            $filter->equal('status', '有効フラグ')->select([0 => '非表示', 1 => '表示']);
        });

        $grid->column('id', __('Id'));
        $grid->column('search_tag_id', __('検索タグ'))->editable('select',function($data){
            return  $searchTags = SearchTag::OrderBy('sort_order','asc')->pluck('name', 'id');
        });
        $grid->column('member.name', __('議員名'));
        $grid->column('name', __('議員タグ'));
        $grid->column('persent', __('%'))->progressBar();
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
        $show = new Show(Category::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('member_id', __('Member id'));
        $show->field('name', __('Name'));
        $show->field('persent', __('Persent'));
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
        $form = new Form(new Category());

        $form->select('search_tag_id', __('検索タグ'))->options(SearchTag::OrderBy('sort_order','asc')->pluck('name', 'id'));
        $form->select('member_id', __('議員名'))->options(Member::pluck('name', 'id'));
        $form->text('name', __('議員タグ名'));
        $form->number('persent', __('比率'))->default(0);
        $form->switch('status', __('有効フラグ'))->default(1);
        return $form;
    }
}
