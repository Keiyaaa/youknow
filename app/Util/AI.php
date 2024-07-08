<?php
//OpenAIのAPIを使うためのクラス
namespace App\Util;

use App\Models\Category;
use App\Models\SearchTag;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request;
use OpenAI;

class AI
{
    public function get_title($content){
        $client = OpenAI::client(config('app.OPENAI_KEY'));

        // $contentから文章のタイトルを作成するpromptを作成
        $prompt = $content . "\n" . "この文章のタイトルを作成してください。" . "\n" . "タイトル：";
        $result = $client->completions()->create([
            'model' => 'text-davinci-003',
            'prompt' => $prompt,
            'max_tokens' => 200,
            'temperature' => 0.7,
            'top_p' => 1,
            'n' => 1,
            'stream' => false,
            'logprobs' => null,
            'stop' => ['タイトル：'],
        ]);
        return $result['choices'][0]['text'];
    }


    public function get_keyword($content){
        $client = OpenAI::client(config('app.OPENAI_KEY'));

        // $contentから特徴的なキーワードを３つ抽出するpromptを作成
        $prompt = "#コンテンツの詳細\n";
        $prompt .= "- このコンテンツでは、与えられた{#文章}の詳細を考慮しながら、特徴的な政策キーワードを抽出します。" . "\n";
        $prompt .= "\n";
        $prompt .= "制約条件" . "\n";
        $prompt .= "1.キーワードとキーワードの区切りはカンマで行ってください。" . "\n";
        $prompt .= "2.意味の似ているキーワードは避けてください。" . "\n";
        $prompt .= "3.出力するキーワードは2つまでにしてください。" . "\n\n";
        $prompt .= "#文章:";
        $prompt .= $content . "\n";
        $prompt .= "\n";
        $prompt .= "キーワード：" . "\n";
        $prompt .= "\n";
        $prompt .= "再考察条件" . "\n";
        $prompt .= "1.抽出したキーワードを総合的に考えて、住民にとって重要度の高いもの2つに絞ってください。" . "\n\n";
        $prompt .= "最終キーワード：";

        \Log::info($prompt);
        $result = $client->completions()->create([
            'model' => 'text-davinci-003',
            'prompt' => $prompt,
            'max_tokens' => 200,
            'temperature' => 0.0,
            'top_p' => 1,
            'n' => 3,
            'stream' => false,
            'logprobs' => null,
            'stop' => ['最終キーワード：'],
        ]);
        \Log::info($result['choices'][0]['text']);
        return $result['choices'][0]['text'];
    }

    public function get_summary($content){
        $client = OpenAI::client(config('app.OPENAI_KEY'));

        // $contentから要約を作成するpromptを作成
        $prompt = $content . "\n" . "この文章を300字程度で要約してください。" . "\n" . "要約：";
        $result = $client->completions()->create([
            'model' => 'text-davinci-003',
            'prompt' => $prompt,
            'max_tokens' => 300,
            'temperature' => 0.0,
            'top_p' => 1,
            'n' => 1,
            'stream' => false,
            'logprobs' => null,
            'stop' => ['要約：'],
        ]);
        return $result['choices'][0]['text'];
    }

    //与えられたタグ情報の配列から、その議員が取り組んでいる政策を抽象化したカテゴリーを作成する
    public function get_category($tags){
        $client = OpenAI::client(config('app.OPENAI_KEY'));
        // $tagsからタグを抽象化するpromptを作成
        $prompt = "#コンテンツの詳細\n";
        $prompt .= "- このコンテンツでは、与えられた{#タグ}の詳細を考慮しながら、タグ情報をデザインします。" . "\n";
        $prompt .= "- タグの内容に取り組んでいる人を総合的に考慮し5つの抽象化したタグで表現してください。" . "\n";
        $prompt .= "- 各タグごとにこの人の力の入れ具合を%で併記してください。" . "\n";
        // 返却するタグのフォーマットの例を指定
        $prompt .= "例.環境保護:30%,子育て支援:40%,IT教育:10%,高齢化対策:10%,DX推進:10%" . "\n";
        $prompt .= "\n";
        $prompt .= "制約条件" . "\n";
        $prompt .= "1.タグとタグの区切りはカンマで行ってください。" . "\n";
        $prompt .= "2.タグとパーセンテージの区切りはコロンで行ってください。" . "\n";
        $prompt .= "3.パーセンテージは5つのタグで合計で100%になるように割り振ってください。" . "\n";
        $prompt .= "4.タグの順序に影響されず全体的に見て抽象化してください。" . "\n";
        $prompt .= "5.出力するタグは5つまでにしてください。" . "\n\n";
        $prompt .= "#タグ:";
        foreach ($tags as $tag) {
            $prompt .= $tag . ",";
        }
        $prompt .= "\n" . "出力タグ：";
        $prompt .= "再考察条件" . "\n";
        $prompt .= "1.抽出した出力タグを総合的に考えて、住民にとって重要度の高いもの5つに絞ってください。" . "\n";
        $prompt .= "2.5つで100%になるようにパーセンテージを割り振ってください。" . "\n\n";
        $prompt .= "最終タグ：";
        $result = $client->completions()->create([
            'model' => 'text-davinci-003',
            'prompt' => $prompt,
            'max_tokens' => 300,
            'temperature' => 0.0,
            'top_p' => 1,
            'n' => 1,
            'stream' => false,
            'logprobs' => null,
            'stop' => ['最終タグ：'],
        ]);
        \Log::info($result['choices'][0]['text']);
        return $result['choices'][0]['text'];
    }

    //与えられた1つのタグを大カテゴリに分類する
    public function get_search_tag($tag){
        $client = OpenAI::client(config('app.OPENAI_KEY'));

        // $tagから大カテゴリに分類するpromptを作成
        $prompt = "#コンテンツの詳細\n";
        $prompt .= "- このコンテンツでは、与えられた{#タグ}の詳細を考慮しながら、{#大カテゴリ}の中で一番適切なものを1つ選択します。" . "\n";
        $prompt .= "\n";
        $prompt .= "制約条件" . "\n";
        $prompt .= "大カテゴリの出現順序に影響されず、全体的に判断してください。" . "\n";
        $prompt .= "\n";
        $prompt .= "#タグ" . "\n";
        $prompt .= $tag . "\n";
        $prompt .= "\n";
        $prompt .= "#大カテゴリ" . "\n";
        $searchTags = SearchTag::OrderBy('sort_order','asc')->pluck('name', 'id');
        foreach ($searchTags as $key => $value) {
            $prompt .= $value . "\n";
        }
        $prompt .= "\n";
        $prompt .= "最終大カテゴリ：";
        $result = $client->completions()->create([
            'model' => 'text-davinci-003',
            'prompt' => $prompt,
            'max_tokens' => 300,
            'temperature' => 0.0,
            'top_p' => 1,
            'n' => 1,
            'stream' => false,
            'logprobs' => null,
            'stop' => ['最終大カテゴリ：'],
        ]);
        \Log::info($result['choices'][0]['text']);
        return $result['choices'][0]['text'];
    }

    //与えられたタグが、議員タグの中でどの大カテゴリに分類されるかを判定する
    public function get_member_tag($tag, $id){
        $client = OpenAI::client(config('app.OPENAI_KEY'));

        // $tagから大カテゴリに分類するpromptを作成
        $prompt = "#コンテンツの詳細\n";
        $prompt .= "- このコンテンツでは、与えられた{#タグ}の詳細を考慮しながら、{#大カテゴリ}の中で一番適切なものを1つ選択します。" . "\n";
        $prompt .= "\n";
        $prompt .= "制約条件" . "\n";
        $prompt .= "大カテゴリの出現順序に影響されず、全体的に判断してください。" . "\n";
        $prompt .= "\n";
        $prompt .= "#タグ" . "\n";
        $prompt .= $tag . "\n";
        $prompt .= "\n";
        $prompt .= "#大カテゴリ" . "\n";
        $categories = Category::where('member_id',$id)->pluck('name', 'id');
        foreach ($categories as $key => $value) {
            $prompt .= $value . "\n";
        }
        $prompt .= "\n";
        $prompt .= "最終大カテゴリ：";
        $result = $client->completions()->create([
            'model' => 'text-davinci-003',
            'prompt' => $prompt,
            'max_tokens' => 300,
            'temperature' => 0.0,
            'top_p' => 1,
            'n' => 1,
            'stream' => false,
            'logprobs' => null,
            'stop' => ['最終大カテゴリ：'],
        ]);
        return $result['choices'][0]['text'];
    }

    //委員会議事録を要約する
    public function get_committee_summary($content){
        $client = OpenAI::client(config('app.OPENAI_KEY'));

        // $contentから要約を作成するpromptを作成
        $prompt = "あなたは、市議会の議事録を要約する役割を持っています。" . "\n";
        $prompt .= "市民にもわかりやすい内容で要約してください。" . "\n";
        $prompt .= "議事録：" . "\n";
        $prompt .= $content . "\n";
        $prompt .= "要約：";
        \Log::info($prompt);

        $result = $client->chat()->create([
            'model' => 'gpt-4-turbo-preview',
            'messages' => [
                [
                    'role' => 'assistant',
                    'content' => $prompt,
                ],
            ],
        ]);
        return $result['choices'][0]['message']['content'];
    }
}
