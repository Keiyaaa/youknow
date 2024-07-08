<?php

namespace App\Observers;

use App\Models\Member;

class MemberObserver
{
    /**
     * Handle the Member "created" event.
     *
     * @param  \App\Models\Member  $member
     * @return void
     */
    public function created(Member $member)
    {
        //
    }

    /**
     * Handle the Member "updated" event.
     *
     * @param  \App\Models\Member  $member
     * @return void
     */
    public function updated(Member $member)
    {
        //statusが変更されたら、そのメンバーのタグとカテゴリーのステータスも変更する
        if($member->isDirty('status')){
            $member->tags_all()->update(['status' => $member->status]);
            $member->categories_all()->update(['status' => $member->status]);
        }
    }

    /**
     * Handle the Member "deleted" event.
     *
     * @param  \App\Models\Member  $member
     * @return void
     */
    public function deleted(Member $member)
    {
        //
    }

    /**
     * Handle the Member "restored" event.
     *
     * @param  \App\Models\Member  $member
     * @return void
     */
    public function restored(Member $member)
    {
        //
    }

    /**
     * Handle the Member "force deleted" event.
     *
     * @param  \App\Models\Member  $member
     * @return void
     */
    public function forceDeleted(Member $member)
    {
        //
    }
}
