<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;


class Book extends Model
{
    protected $guarded = [];

    public function path() {

       return '/books/'.$this->id;
        //        return '/books/'.$this->id.'-'.Str::slug($this->title);

        // /books/1-enders-game
    }

    public function checkout($user){

       $this->reservations()->create([
           'user_id' => $user->id,
            'checked_out_at' => now(),
        ]);
    }

    public  function checkin($user){

        $reservation = $this->reservations()->where('user_id',$user->id)
            ->whereNotNull('checked_out_at')
       //     ->whereNull('checked_in_at') // 此句是确保已经确保 书已经借出状态，才能归还，
            ->first();                          // 不能 覆盖已经 还书的 check_ed_in_at 状态


//        dd($reservation);
        if(is_null($reservation)) {
            throw new \Exception();
        }

        $reservation->update([
            'checked_in_at' => now(),
        ]);
    }

    public function setAuthorIdAttribute($author){

        $this->attributes['author_id'] = (Author::firstOrCreate([
            'name' => $author,
        ]))->id;
    }

    public function reservations(){

         return $this->hasMany(Reservation::class);
    }
}
