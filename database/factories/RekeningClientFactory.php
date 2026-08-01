<?php

namespace Database\Factories;

use App\Models\RekeningClient;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<RekeningClient>
 */
class RekeningClientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama_client' => $this->faker->company(),
            'bank_code' => $this->faker->randomElement(['BCA', 'BNI', 'BRI', 'MANDIRI']),
            'bank_name' => $this->faker->company(),
            'account_number' => $this->faker->numerify('##########'),
            'account_holder_name' => $this->faker->name(),
            'routing_type' => 'SWIFT',
            'routing_value' => strtoupper($this->faker->lexify('????IDJA')),
            'recipient_type' => 'INDIVIDUAL',
            'relationship' => 'CUSTOMER',
            'channel_type' => 'BANK',
            'city' => 'Jakarta',
            'street_line_1' => 'Jl. '.$this->faker->streetName(),
            'keterangan' => null,
        ];
    }

    /**
     * Set the destination as an e-wallet channel.
     */
    public function ewallet(string $code = 'DANA'): static
    {
        return $this->state(fn () => [
            'channel_type' => 'EWALLET',
            'bank_code' => $code,
            'bank_name' => strtoupper($code),
            'account_number' => $this->faker->numerify('08##########'),
            'routing_type' => 'WALLET',
            'routing_value' => 'ID_'.$code,
        ]);
    }
}
