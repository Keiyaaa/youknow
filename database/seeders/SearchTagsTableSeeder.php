<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SearchTagsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('search_tags')->delete();
        
        \DB::table('search_tags')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => '子育て支援・教育と文化',
                'sort_order' => 1,
                'created_at' => '2023-10-01 18:19:09',
                'updated_at' => '2023-10-12 12:06:36',
                'deleted_at' => NULL,
                'color' => '#EB3B49',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => '社会福祉と支援',
                'sort_order' => 2,
                'created_at' => '2023-10-01 18:19:34',
                'updated_at' => '2023-10-12 12:06:50',
                'deleted_at' => NULL,
                'color' => '#FC6689',
            ),
            2 => 
            array (
                'id' => 3,
                'name' => '健康と感染症対策',
                'sort_order' => 3,
                'created_at' => '2023-10-01 18:20:14',
                'updated_at' => '2023-10-12 12:07:12',
                'deleted_at' => NULL,
                'color' => '#7E342D',
            ),
            3 => 
            array (
                'id' => 4,
                'name' => '経済支援',
                'sort_order' => 4,
                'created_at' => '2023-10-01 18:20:28',
                'updated_at' => '2023-10-12 12:07:32',
                'deleted_at' => NULL,
                'color' => '#EF6500',
            ),
            4 => 
            array (
                'id' => 5,
                'name' => 'デジタル化とイノベーション',
                'sort_order' => 5,
                'created_at' => '2023-10-01 18:20:49',
                'updated_at' => '2023-10-12 12:07:54',
                'deleted_at' => NULL,
                'color' => '#E89B00',
            ),
            5 => 
            array (
                'id' => 6,
                'name' => '防災と危機管理',
                'sort_order' => 6,
                'created_at' => '2023-10-01 18:21:05',
                'updated_at' => '2023-10-12 12:08:18',
                'deleted_at' => NULL,
                'color' => '#8DA702',
            ),
            6 => 
            array (
                'id' => 7,
                'name' => '環境と自然保護',
                'sort_order' => 7,
                'created_at' => '2023-10-01 18:21:22',
                'updated_at' => '2023-10-12 12:08:40',
                'deleted_at' => NULL,
                'color' => '#017B13',
            ),
            7 => 
            array (
                'id' => 8,
                'name' => 'まちづくりと協働',
                'sort_order' => 8,
                'created_at' => '2023-10-01 18:21:40',
                'updated_at' => '2023-10-12 12:09:08',
                'deleted_at' => NULL,
                'color' => '#00ADAD',
            ),
            8 => 
            array (
                'id' => 9,
                'name' => 'その他',
                'sort_order' => 9,
                'created_at' => '2023-10-01 18:21:59',
                'updated_at' => '2023-10-12 12:09:32',
                'deleted_at' => NULL,
                'color' => '#00A6DD',
            ),
            9 => 
            array (
                'id' => 10,
                'name' => '清掃・環境・自然',
                'sort_order' => 10,
                'created_at' => '2023-10-01 18:22:23',
                'updated_at' => '2023-10-12 14:54:23',
                'deleted_at' => '2023-10-12 14:54:23',
                'color' => '#3362BA',
            ),
            10 => 
            array (
                'id' => 11,
                'name' => '公共施設・土木',
                'sort_order' => 11,
                'created_at' => '2023-10-01 18:22:41',
                'updated_at' => '2023-10-12 14:54:23',
                'deleted_at' => '2023-10-12 14:54:23',
                'color' => '#80569B',
            ),
            11 => 
            array (
                'id' => 12,
                'name' => '行政事務',
                'sort_order' => 12,
                'created_at' => '2023-10-01 18:22:57',
                'updated_at' => '2023-10-12 14:54:23',
                'deleted_at' => '2023-10-12 14:54:23',
                'color' => '#232F86',
            ),
            12 => 
            array (
                'id' => 13,
                'name' => 'その他',
                'sort_order' => 13,
                'created_at' => '2023-10-01 18:22:57',
                'updated_at' => '2023-10-12 14:54:23',
                'deleted_at' => '2023-10-12 14:54:23',
                'color' => '#818181',
            ),
        ));
        
        
    }
}