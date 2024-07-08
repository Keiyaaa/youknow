<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Municipality;

class MemberFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'municipality_id' => 1,
            'name' => $this->faker->name(),
            'kana' => $this->faker->kanaName(),
            //画像は、imagesディレクトリ下に保存する。DB保存のパスは、storage/images/xxxxx.jpg
            //'image' => $this->faker->image('public/storage/images', 640, 480, null, false),
            'affiliation' => $this->faker->company(),
            'email' => $this->faker->unique()->safeEmail(),
            'num' => $this->faker->numberBetween(1, 10),
        ];
    }
}
