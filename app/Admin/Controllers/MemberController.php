<?php

namespace App\Admin\Controllers;

use App\Models\Category;
use App\Models\Member;
use App\Models\Municipality;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use App\Admin\Actions\CreateYouknowTag;

class MemberController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '議員管理';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Member());
        $grid->model()->orderBy('id', 'desc');
        $grid->expandFilter();
        $grid->filter(function ($filter) {
            $filter->disableIdFilter();
            $municipalities = Municipality::pluck('name', 'id');
            $filter->equal('municipality_id', '自治体ID')->select($municipalities);
            //カテゴリーでフィルタをかける（or）
            $categories = Category::pluck('name', 'name');
            $filter->where(function ($query) {
                $query->whereIn('id', function ($query) {
                    $query->select('member_id')->from('categories')->whereIn('name', $this->input);
                });
            }, 'タグ')->multipleSelect($categories);
            //表示フラグのフィルタ
            $filter->equal('status', '表示フラグ')->select([0 => '非表示', 1 => '表示']);
        });

        $grid->column('id', __('ID'));
        $grid->column('municipality.name', __('市区町村'));
        //氏名を質問一覧へのリンクにする
        $grid->column('name', __('氏名'))->display(function ($name) {
            return "<a href='/admin/questions?member_id={$this->id}'>{$name}</a>";
        });
        $grid->column('kana', __('ふりがな'))->hide();
        $grid->column('image', __('顔写真'))->image();
        $grid->column('affiliation', __('所属'));
        $state = [
            'on'  => ['value' => 1, 'text' => '表示', 'color' => 'success'],
            'off' => ['value' => 0, 'text' => '非表示', 'color' => 'danger'],
        ];
        $grid->column('status', __('表示フラグ'))->switch($state);
        $grid->categories('タグ')->pluck('name')->label()->width(200);
        $grid->column('email', __('メールアドレス'))->hide();
        $grid->column('num', __('当選回数'))->hide();
        $grid->column('created_at', __('作成日時'))->hide();
        $grid->column('updated_at', __('更新日時'));
        $grid->column('deleted_at', __('削除日時'))->hide();

        $grid->actions(function ($actions) {
            $actions->disableView();
            // 各種タグを生成するボタンを追加
            $actions->add(new CreateYouknowTag());
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
        $show = new Show(Member::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('municipality.name', __('自治体'));
        $show->field('name', __('氏名'));
        $show->field('kana', __('カナ'));
        $show->field('image', __('顔写真'))->image();
        $show->field('affiliation', __('所属'));
        $show->field('email', __('メールアドレス'));
        $show->field('num', __('当選回数'));
        $show->field('created_at', __('作成日時'));
        $show->field('updated_at', __('更新日時'));
        $show->field('deleted_at', __('削除日時'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Member());

        $municipalities = Municipality::pluck('name', 'id');
        $form->select('municipality_id', __('自治体'))->options($municipalities)->rules('required');
        $form->text('name', __('氏名'));
        $form->text('kana', __('カナ'));
        $form->image('image', __('顔写真'))->move('images')->uniqueName();
        $form->text('affiliation', __('所属'));
        $form->email('email', __('メールアドレス'));
        $form->number('num', __('当選回数'))->default(0);
        $form->switch('status', __('表示フラグ'))->default(1);

        return $form;
    }
}
