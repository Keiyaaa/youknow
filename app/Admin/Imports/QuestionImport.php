<?php

namespace App\Admin\Imports;

use App\Models\Member;
use App\Models\Question;
use DB;
use Encore\Admin\Actions\Action;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;

HeadingRowFormatter::default('none');

class QuestionImport extends Action implements ToCollection, WithHeadingRow, WithCustomCsvSettings
{
    public $name = '議会質問一括登録';
    protected $selector = '.import-question';

    public function handle(Request $request)
    {
        ini_set('memory_limit', '1024M');
        ini_set('max_execution_time', '800');

        $file = $request->file('file');
        try {
            Excel::import(new QuestionImport(), $file);
            return $this->response()->success('登録完了しました')->refresh();
        } catch (QueryException $e) {
            return $this->response()->error('エラー: 保存に失敗しました。' . $e->getMessage())->refresh();
        } catch (Exception $e) {
            return $this->response()->error('エラー: ' . $e->getMessage())->refresh();
        }
    }

    public function collection(Collection $rows)
    {
        DB::beginTransaction();
        try {
            foreach ($rows as $row) {
                if ($row["氏名"] == "") {
                    break;
                }

                $question = new Question();

                //氏名
                //　全角スペースを半角スペースに変換
                $row["氏名"] = str_replace("　", " ", $row["氏名"]);
                $member = Member::where("name", $row["氏名"])->first();
                //現職議員のみインポート
                if ($member) {
                    $question->member_id = $member->id;
                    $question->title = $row["テーマ"];
                    $question->content = $row["質問"];
                    $question->answer = $row["回答"];
                    $question->mtg = $row["議会だより日時"];
                    $question->save();
                }
            }
            DB::commit();
        } catch (QueryException $e) {
            DB::rollback();
            throw $e;
        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function getCsvSettings(): array
    {
        return [
            'input_encoding' => 'cp932',
        ];
    }

    public function form()
    {
        $this->confirm('インポートしてもよろしいでしょうか？');
        $this->file('file', 'エクセルファイルをアップロードしてください。')->help('<div style="margin-top:16px" class="text-warning">保存ボタンを押した後は、画面の操作をせずに処理が終了するまでお待ちください。</div>');
    }

    public function html()
    {
        return <<<HTML
        <a class="btn btn-sm btn-twitter import-question"><i class="fa fa-upload"></i> エクセルインポート</a>
HTML;
    }
}
