<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class AdminMenuTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('admin_menu')->delete();
        
        \DB::table('admin_menu')->insert(array (
            0 => 
            array (
                'id' => 2,
                'parent_id' => 0,
                'order' => 9,
                'title' => 'Admin',
                'icon' => 'fa-tasks',
                'uri' => '',
                'permission' => NULL,
                'created_at' => NULL,
                'updated_at' => '2024-02-08 16:56:10',
            ),
            1 => 
            array (
                'id' => 3,
                'parent_id' => 2,
                'order' => 10,
                'title' => 'Users',
                'icon' => 'fa-users',
                'uri' => 'auth/users',
                'permission' => NULL,
                'created_at' => NULL,
                'updated_at' => '2024-02-08 16:56:10',
            ),
            2 => 
            array (
                'id' => 4,
                'parent_id' => 2,
                'order' => 11,
                'title' => 'Roles',
                'icon' => 'fa-user',
                'uri' => 'auth/roles',
                'permission' => NULL,
                'created_at' => NULL,
                'updated_at' => '2024-02-08 16:56:10',
            ),
            3 => 
            array (
                'id' => 5,
                'parent_id' => 2,
                'order' => 12,
                'title' => 'Permission',
                'icon' => 'fa-ban',
                'uri' => 'auth/permissions',
                'permission' => NULL,
                'created_at' => NULL,
                'updated_at' => '2024-02-08 16:56:10',
            ),
            4 => 
            array (
                'id' => 6,
                'parent_id' => 2,
                'order' => 13,
                'title' => 'Menu',
                'icon' => 'fa-bars',
                'uri' => 'auth/menu',
                'permission' => NULL,
                'created_at' => NULL,
                'updated_at' => '2024-02-08 16:56:10',
            ),
            5 => 
            array (
                'id' => 7,
                'parent_id' => 2,
                'order' => 14,
                'title' => 'Operation log',
                'icon' => 'fa-history',
                'uri' => 'auth/logs',
                'permission' => NULL,
                'created_at' => NULL,
                'updated_at' => '2024-02-08 16:56:10',
            ),
            6 => 
            array (
                'id' => 8,
                'parent_id' => 0,
                'order' => 6,
                'title' => 'マスタ設定',
                'icon' => 'fa-database',
                'uri' => NULL,
                'permission' => NULL,
                'created_at' => '2023-09-17 16:13:00',
                'updated_at' => '2024-02-08 16:56:10',
            ),
            7 => 
            array (
                'id' => 9,
                'parent_id' => 8,
                'order' => 8,
                'title' => '市区町村管理',
                'icon' => 'fa-area-chart',
                'uri' => 'municipalities',
                'permission' => NULL,
                'created_at' => '2023-09-17 16:13:56',
                'updated_at' => '2024-02-08 16:56:10',
            ),
            8 => 
            array (
                'id' => 12,
                'parent_id' => 0,
                'order' => 1,
                'title' => '議員管理',
                'icon' => 'fa-users',
                'uri' => 'members',
                'permission' => NULL,
                'created_at' => '2023-09-22 18:50:21',
                'updated_at' => '2023-09-22 18:51:32',
            ),
            9 => 
            array (
                'id' => 13,
                'parent_id' => 0,
                'order' => 3,
                'title' => '質問管理',
                'icon' => 'fa-question',
                'uri' => 'questions',
                'permission' => NULL,
                'created_at' => '2023-09-22 18:51:24',
                'updated_at' => '2023-09-29 15:57:46',
            ),
            10 => 
            array (
                'id' => 14,
                'parent_id' => 0,
                'order' => 4,
                'title' => '質問タグ管理',
                'icon' => 'fa-tag',
                'uri' => 'tags',
                'permission' => NULL,
                'created_at' => '2023-09-28 18:09:26',
                'updated_at' => '2023-09-29 15:57:46',
            ),
            11 => 
            array (
                'id' => 15,
                'parent_id' => 0,
                'order' => 2,
                'title' => '議員タグ管理',
                'icon' => 'fa-tags',
                'uri' => 'categories',
                'permission' => NULL,
                'created_at' => '2023-09-29 15:57:39',
                'updated_at' => '2023-09-29 15:57:46',
            ),
            12 => 
            array (
                'id' => 16,
                'parent_id' => 8,
                'order' => 7,
                'title' => '検索タグ管理',
                'icon' => 'fa-search',
                'uri' => 'search-tags',
                'permission' => NULL,
                'created_at' => '2023-10-01 18:17:43',
                'updated_at' => '2024-02-08 16:56:10',
            ),
            13 => 
            array (
                'id' => 17,
                'parent_id' => 0,
                'order' => 5,
                'title' => '議事録管理',
                'icon' => 'fa-book',
                'uri' => 'congressional-minutes',
                'permission' => NULL,
                'created_at' => '2024-02-08 16:55:45',
                'updated_at' => '2024-02-08 16:56:10',
            ),
        ));
        
        
    }
}