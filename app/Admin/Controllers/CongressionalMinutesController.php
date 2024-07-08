<?php

namespace App\Admin\Controllers;

use App\Models\CongressionalMinutes;
use App\Models\Municipality;
use App\Util\AI;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Exception;
use Illuminate\Support\Facades\Log;

class CongressionalMinutesController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '議事録管理';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new CongressionalMinutes());

        $grid->column('id', __('Id'));
        $grid->column('municipality.name', '市町村');
        $grid->column('date', '議事録の日付');
        $grid->column('url', '議事録のURL');
        $grid->column('title', '議事録タイトル');
        $grid->column('summary', '要約');
        $grid->column('created_at', '作成日時');
        $grid->column('updated_at', '更新日時');
        $grid->column('deleted_at', '削除日時');

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
        $show = new Show(CongressionalMinutes::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('municipality.name', '市町村');
        $show->field('date', '議事録の日付');
        $show->field('url', '議事録のURL');
        $show->field('title', '議事録タイトル');
        $show->field('summary', '要約');
        $show->field('content', '議事録の内容');
        $show->field('created_at', '作成日時');
        $show->field('updated_at', '更新日時');
        $show->field('deleted_at', '削除日時');

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new CongressionalMinutes());

        $municipalities = Municipality::pluck('name', 'id');
        $form->select('municipality_id', '市町村')->options($municipalities)->rules('required');
        $form->text('title', '議事録タイトル')->rules('required');
        $form->date('date', '議事録の日付')->rules('required');
        $form->url('url', '議事録のURL')->rules('required');

        $form->hidden('content');
        $form->hidden('summary');

        $form->saving(function (Form $form) {
            if ($form->isCreating()) {
                $form->content = $this->getContent($form->url);
                if (!empty($form->content)) {
                    $ai = new AI();
                    $form->summary = $ai->get_committee_summary($form->content);
                }
            }
        });

        return $form;
    }

    private function getContent($url)
    {
        try {
            $content = "";
            parse_str(parse_url($url, PHP_URL_QUERY), $query);

            $url = "https://ssp.kaigiroku.net/dnp/search/minutes/get_minute?callback=jQuery360043828384729893033_1706606626612";
            $data = array(
                "tenant_id" => $query['tenant_id'],
                "power_user" => false, // ここは固定
                "council_id" => $query['council_id'],
                "schedule_id" => $query['schedule_id'],
                "_" => 1693017204495 // ここは固定
            );
            //curl
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            $response = curl_exec($ch);

            //responseを日本語に変換してjsonに変換
            $response = mb_convert_encoding($response, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');
            $response = preg_replace('/^jQuery.*?\(/', '', $response);
            $response = preg_replace('/\)$/', '', $response);
            $response1 = json_decode($response, true);

            foreach ($response1['tenant_minutes'] as $val) {
                $content = $content . $val['body'];
            }

            return strip_tags($content);
        } catch (Exception $e) {
            Log::info($e);
            return "";
        }
    }
}
