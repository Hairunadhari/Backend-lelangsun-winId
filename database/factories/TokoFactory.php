<?php

namespace Database\Factories;

use App\Models\Toko;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Toko>
 */
class TokoFactory extends Factory
{
    protected $model = Toko::class;

    public function definition()
    {
        return [
            'toko' => $this->faker->name,
            'logo' => '0JX6MmkWrm0En3MdvTxLCJWQjhVx8gCeRdxefHIZ.jpg',
        ];
    }
}
