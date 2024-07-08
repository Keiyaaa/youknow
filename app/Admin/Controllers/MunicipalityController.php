<?php

namespace App\Admin\Controllers;

use App\Models\Municipality;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class MunicipalityController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '市区町村管理';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Municipality());

        $grid->actions(function ($actions) {
            $actions->disableView();
        });
        $grid->column('id', __('ID'));
        $grid->column('name', __('市区町村名'));
        $grid->column('sort_order', __('並び順'))->orderable();
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
        $show = new Show(Municipality::findOrFail($id));

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
        $form = new Form(new Municipality());

        $form->text('name', __('市区町村名'));
        $form->number('sort_order', __('並び順'))->default(0);

        return $form;
    }
}
