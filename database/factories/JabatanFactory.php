<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Jabatan>
 */
class JabatanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $namaJabatan = [
            'Software Engineer',
            'Backend Developer',
            'Frontend Developer',
            'Project Manager',
            'UI/UX Designer',
            'System Analyst',
            'Finance Staff',
            'IT Support',
            'Human Resource',
        ];

        return [
            'nama_jabatan' => $this->faker->randomElement($namaJabatan),
        ];
    }
}
