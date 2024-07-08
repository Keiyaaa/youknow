<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Municipality;
use App\Models\Member;
use App\Models\SearchTag;
use App\Models\Tag;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function select_municipality()
    {
        $municipalities = Municipality::OrderBy('sort_order','asc')->get();
        return view('home.select_municipality',['municipalities' => $municipalities]);
    }

    public function members(Request $request)
    {
        $municipality_id = $request->input('municipality_id');
        $members = Member::where('municipality_id',$municipality_id)->where('status',1)->get();
        return view('home.members',['members' => $members, 'search_flg' => false]);
    }

    public function search(Request $request)
    {
        $municipality_id = $request->input('municipality_id');
        $searchTags = SearchTag::OrderBy('sort_order','asc')->get();

        // カテゴリーの配色指定: 変更が多くなるようであればDB側で管理するように変更
        $colors = SearchTag::pluck('color', 'name');
        return view('home.search',['searchTags' => $searchTags, 'municipality_id' => $municipality_id, 'colors' => $colors]);
    }

    public function search_members(Request $request)
    {
        $municipality_id = $request->municipality_id;
        $searchTag = SearchTag::find($request->input('search_tag_id'));
        $search_tags = SearchTag::OrderBy('sort_order','asc')->get();
        foreach($search_tags as $search_tag){
            if($request->input('category_' . $search_tag->id )){
                //配列から取り出す
                $category_arr[] = $request->input('category_' . $search_tag->id );
            }
        }
        //$category_arrを連想配列から一次元配列に変換
        $category_arr = array_merge(...$category_arr);
        //カテゴリーテーブルをcategory_arrで絞り込み
        $categories = Category::whereIn('name',$category_arr)->get();
        // カテゴリーテーブルをcategory_arrで絞り込み、nameが重複していないものだけを取得し、search_tag_idの昇順で並び替える。
        $display_categories = Category::join('search_tags', 'categories.search_tag_id', '=', 'search_tags.id')
            ->whereIn('categories.name', $category_arr)
            ->select('categories.*')
            ->groupBy('categories.name')
            ->orderBy('search_tags.sort_order', 'asc')
            ->get();

        //タグテーブルからmember_idを取り出す
        foreach($categories as $category){
            $member_ids[] = $category->member_id;
        }
        //member_idを1つづつ取り出し、同じidがいくつあるかを数える
        $match_count = array_count_values($member_ids);

        //member_idでmemberテーブルを絞り込み
        $members = Member::whereIn('id',$member_ids)->where('municipality_id',$municipality_id)->where('status',1)->get();
        //membersに、カテゴリーの数を追加
        foreach($members as $member){
            $member->category_count = $match_count[$member->id];
        }
        //category_countで降順に並び替え
        $members = $members->sortByDesc('category_count');

        return view('home.members',['members' => $members, 'searchTag' => $searchTag, 'municipality_id' => $municipality_id, 'display_categories' => $display_categories, 'search_flg' => true]);
    }

    public function show($id)
    {
        $member = Member::find($id);
        //この議員に紐づくcategory名を配列に格納
        $graph_names = array();
        $graph_persent = array();
        $categories = Category::where('member_id',$id)->get();
        foreach($categories as $category){
            $category_names[] = $category->name;
            $category_persent[] = $category->persent;
            if($category->searchTag){
                $category_colors[] = $category->searchTag->color;
            }else{
                $category_colors[] = "#818181";
            }
            // $category_names内に同じ名前があれば、パーセンテージを足し合わせる
            if(in_array($category->searchTag->name, $graph_names)){
                $key = array_search($category->searchTag->name, $graph_names);
                $graph_persent[$key] += 20;
                continue;
            }else{
                $graph_names[] = $category->searchTag->name;
                $graph_persent[] += 20;
            }
            if($category->searchTag){
                $graph_colors[] = $category->searchTag->color;
            }else{
                $graph_colors[] = "#818181";
            }
            if($category->searchTag){
                $searchTagId = $category->searchTag->id;
                $graph_alphabet[] = chr(65 + $searchTagId - 1);
            }else{
                $graph_alphabet[] = "Z";
            }
        }
        return view('home.show',['member' => $member,'name' => $category_names,'persent' => $category_persent, 'color' => $category_colors, 'graph_name' => $graph_names, 'graph_persent' => $graph_persent, 'graph_color' => $graph_colors , 'graph_alphabet' => $graph_alphabet]);
    }
}
