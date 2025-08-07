<?php

namespace Database\Factories;

use App\Models\TahunAkademik;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TahunAkademik>
 */
class TahunAkademikFactory extends Factory
{
    protected $model = TahunAkademik::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'tahun' => $this->faker->year . '/' . ($this->faker->year + 1),
            'semester' => 'Ganjil',
            'is_active' => false,
            'tanggal_mulai' => $this->faker->date(),
            'tanggal_selesai' => $this->faker->date(),
        ];
    }

    /**
     * Indicate that the tahun akademik is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => true,
        ]);
    }
}
