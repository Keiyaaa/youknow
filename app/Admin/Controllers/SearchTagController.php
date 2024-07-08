<?php

namespace App\Admin\Controllers;

use App\Models\SearchTag;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class SearchTagController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '検索タグ管理';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new SearchTag());
        $grid->model()->orderBy('sort_order', 'asc');

        $grid->actions(function ($actions) {
            $actions->disableView();
        });

        $grid->column('id', __('ID'));
        $grid->column('name', __('検索タグ名'));
        $grid->column('sort_order', __('並び順'))->orderable();
        $grid->column('thema_color', __('テーマカラー'))->display(function () {
            return "<p style='color: white; background-color: {$this->color}'>{$this->color}</p>";
        });
        $grid->column('color', __('カラー変更'))->editable();
        $grid->column('icon', __('アイコン'))->display(function($icon){
            if($icon){
                return "<img src='/storage/{$icon}' style='max-width:50px;max-height:50px;background-color:{$this->color};' class='img img-thumbnail' />";
            }else{
                return '';
            }
        });
        $grid->column('created_at', __('作成日時'));
        $grid->column('updated_at', __('更新日時'))->hide();
        $grid->column('deleted_at', __('削除日時'))->hide();

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
        $show = new Show(SearchTag::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('name', __('Name'));
        $show->field('sort_order', __('Sort order'));
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
        $form = new Form(new SearchTag());

        $form->text('name', __('検索タグ名'));
        $form->number('sort_order', __('並び順'));
        $form->color('color', __('テーマカラー'));
        $form->image('icon', __('アイコン'))->removable()->uniqueName();

        return $form;
    }
}
