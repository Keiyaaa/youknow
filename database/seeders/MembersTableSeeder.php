<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class MembersTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('members')->truncate();

        \DB::table('members')->insert(array (
            0 =>
            array (
                'affiliation' => NULL,
                'created_at' => '2023-09-28 22:34:19',
                'deleted_at' => NULL,
                'email' => NULL,
                'id' => 1,
                'image' => 'images/c54517b36afe00307d150d22cc04ab84.png',
                'kana' => NULL,
                'municipality_id' => 1,
                'name' => '西岡　めぐみ',
                'num' => 0,
                'updated_at' => '2023-09-28 22:34:19',
            ),
            1 =>
            array (
                'affiliation' => NULL,
                'created_at' => NULL,
                'deleted_at' => NULL,
                'email' => NULL,
                'id' => 2,
                'image' => NULL,
                'kana' => NULL,
                'municipality_id' => 1,
                'name' => '大坂　隆洋',
                'num' => NULL,
                'updated_at' => NULL,
            ),
            2 =>
            array (
                'affiliation' => NULL,
                'created_at' => NULL,
                'deleted_at' => NULL,
                'email' => NULL,
                'id' => 3,
                'image' => NULL,
                'kana' => NULL,
                'municipality_id' => 1,
                'name' => 'のざわ　哲夫',
                'num' => NULL,
                'updated_at' => NULL,
            ),
            3 =>
            array (
                'affiliation' => NULL,
                'created_at' => NULL,
                'deleted_at' => NULL,
                'email' => NULL,
                'id' => 4,
                'image' => NULL,
                'kana' => NULL,
                'municipality_id' => 1,
                'name' => '小枝　すみ子',
                'num' => NULL,
                'updated_at' => NULL,
            ),
            4 =>
            array (
                'affiliation' => NULL,
                'created_at' => NULL,
                'deleted_at' => NULL,
                'email' => NULL,
                'id' => 5,
                'image' => NULL,
                'kana' => NULL,
                'municipality_id' => 1,
                'name' => 'えごし　雄一',
                'num' => NULL,
                'updated_at' => NULL,
            ),
            5 =>
            array (
                'affiliation' => NULL,
                'created_at' => NULL,
                'deleted_at' => NULL,
                'email' => NULL,
                'id' => 6,
                'image' => NULL,
                'kana' => NULL,
                'municipality_id' => 1,
                'name' => '米田　かずや',
                'num' => NULL,
                'updated_at' => NULL,
            ),
            6 =>
            array (
                'affiliation' => NULL,
                'created_at' => NULL,
                'deleted_at' => NULL,
                'email' => NULL,
                'id' => 7,
                'image' => NULL,
                'kana' => NULL,
                'municipality_id' => 1,
                'name' => '牛尾　こうじろう',
                'num' => NULL,
                'updated_at' => NULL,
            ),
            7 =>
            array (
                'affiliation' => NULL,
                'created_at' => NULL,
                'deleted_at' => NULL,
                'email' => NULL,
                'id' => 8,
                'image' => NULL,
                'kana' => NULL,
                'municipality_id' => 1,
                'name' => '岩佐　りょう子',
                'num' => NULL,
                'updated_at' => NULL,
            ),
            8 =>
            array (
                'affiliation' => NULL,
                'created_at' => NULL,
                'deleted_at' => NULL,
                'email' => NULL,
                'id' => 9,
                'image' => NULL,
                'kana' => NULL,
                'municipality_id' => 1,
                'name' => '小野　なりこ',
                'num' => NULL,
                'updated_at' => NULL,
            ),
            9 =>
            array (
                'affiliation' => NULL,
                'created_at' => NULL,
                'deleted_at' => NULL,
                'email' => NULL,
                'id' => 10,
                'image' => NULL,
                'kana' => NULL,
                'municipality_id' => 1,
                'name' => '池田　とものり',
                'num' => NULL,
                'updated_at' => NULL,
            ),
            10 =>
            array (
                'affiliation' => NULL,
                'created_at' => NULL,
                'deleted_at' => NULL,
                'email' => NULL,
                'id' => 11,
                'image' => NULL,
                'kana' => NULL,
                'municipality_id' => 1,
                'name' => 'はやお　恭一',
                'num' => NULL,
                'updated_at' => NULL,
            ),
            11 =>
            array (
                'affiliation' => NULL,
                'created_at' => NULL,
                'deleted_at' => NULL,
                'email' => NULL,
                'id' => 12,
                'image' => NULL,
                'kana' => NULL,
                'municipality_id' => 1,
                'name' => '春山　あすか',
                'num' => NULL,
                'updated_at' => NULL,
            ),
            12 =>
            array (
                'affiliation' => NULL,
                'created_at' => NULL,
                'deleted_at' => NULL,
                'email' => NULL,
                'id' => 13,
                'image' => NULL,
                'kana' => NULL,
                'municipality_id' => 1,
                'name' => 'はまもり　かおり',
                'num' => NULL,
                'updated_at' => NULL,
            ),
            13 =>
            array (
                'affiliation' => NULL,
                'created_at' => NULL,
                'deleted_at' => NULL,
                'email' => NULL,
                'id' => 14,
                'image' => NULL,
                'kana' => NULL,
                'municipality_id' => 1,
                'name' => '白川　司',
                'num' => NULL,
                'updated_at' => NULL,
            ),
            14 =>
            array (
                'affiliation' => NULL,
                'created_at' => NULL,
                'deleted_at' => NULL,
                'email' => NULL,
                'id' => 15,
                'image' => NULL,
                'kana' => NULL,
                'municipality_id' => 1,
                'name' => '永田　壮一',
                'num' => NULL,
                'updated_at' => NULL,
            ),
            15 =>
            array (
                'affiliation' => NULL,
                'created_at' => NULL,
                'deleted_at' => NULL,
                'email' => NULL,
                'id' => 16,
                'image' => NULL,
                'kana' => NULL,
                'municipality_id' => 1,
                'name' => '入山　たけひこ',
                'num' => NULL,
                'updated_at' => NULL,
            ),
            16 =>
            array (
                'affiliation' => NULL,
                'created_at' => NULL,
                'deleted_at' => NULL,
                'email' => NULL,
                'id' => 17,
                'image' => NULL,
                'kana' => NULL,
                'municipality_id' => 1,
                'name' => '田中　えりか',
                'num' => NULL,
                'updated_at' => NULL,
            ),
            17 =>
            array (
                'affiliation' => NULL,
                'created_at' => NULL,
                'deleted_at' => NULL,
                'email' => NULL,
                'id' => 18,
                'image' => NULL,
                'kana' => NULL,
                'municipality_id' => 1,
                'name' => '岩田　かずひと',
                'num' => NULL,
                'updated_at' => NULL,
            ),
            18 =>
            array (
                'affiliation' => NULL,
                'created_at' => NULL,
                'deleted_at' => NULL,
                'email' => NULL,
                'id' => 19,
                'image' => NULL,
                'kana' => NULL,
                'municipality_id' => 1,
                'name' => '小林　たかや',
                'num' => NULL,
                'updated_at' => NULL,
            ),
            19 =>
            array (
                'affiliation' => NULL,
                'created_at' => NULL,
                'deleted_at' => NULL,
                'email' => NULL,
                'id' => 20,
                'image' => NULL,
                'kana' => NULL,
                'municipality_id' => 1,
                'name' => '林　則行',
                'num' => NULL,
                'updated_at' => NULL,
            ),
            20 =>
            array (
                'affiliation' => NULL,
                'created_at' => NULL,
                'deleted_at' => NULL,
                'email' => NULL,
                'id' => 21,
                'image' => NULL,
                'kana' => NULL,
                'municipality_id' => 1,
                'name' => '嶋崎　秀彦',
                'num' => NULL,
                'updated_at' => NULL,
            ),
            21 =>
            array (
                'affiliation' => NULL,
                'created_at' => NULL,
                'deleted_at' => NULL,
                'email' => NULL,
                'id' => 22,
                'image' => NULL,
                'kana' => NULL,
                'municipality_id' => 1,
                'name' => '桜井　ただし',
                'num' => NULL,
                'updated_at' => NULL,
            ),
            22 =>
            array (
                'affiliation' => NULL,
                'created_at' => NULL,
                'deleted_at' => NULL,
                'email' => NULL,
                'id' => 23,
                'image' => NULL,
                'kana' => NULL,
                'municipality_id' => 1,
                'name' => '秋谷　こうき',
                'num' => NULL,
                'updated_at' => NULL,
            ),
            23 =>
            array (
                'affiliation' => NULL,
                'created_at' => NULL,
                'deleted_at' => NULL,
                'email' => NULL,
                'id' => 24,
                'image' => NULL,
                'kana' => NULL,
                'municipality_id' => 1,
                'name' => 'おのでら　亮',
                'num' => NULL,
                'updated_at' => NULL,
            ),
            24 =>
            array (
                'affiliation' => NULL,
                'created_at' => NULL,
                'deleted_at' => NULL,
                'email' => NULL,
                'id' => 25,
                'image' => NULL,
                'kana' => NULL,
                'municipality_id' => 1,
                'name' => '富山　あゆみ',
                'num' => NULL,
                'updated_at' => NULL,
            ),
        ));


    }
}
