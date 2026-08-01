<?php

namespace Database\Factories;

use App\Models\Transfer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Transfer>
 */
class TransferFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'kode_transfer' => 'TRF-'.date('ymd').'-'.strtoupper($this->faker->unique()->bothify('###???')),
            'nama_client' => $this->faker->company(),
            'bank_code' => 'BCA',
            'bank_name' => 'Bank Central Asia',
            'account_number' => $this->faker->numerify('##########'),
            'account_holder_name' => $this->faker->name(),
            'routing_type' => 'SWIFT',
            'routing_value' => 'CENAIDJA',
            'recipient_type' => 'INDIVIDUAL',
            'relationship' => 'CUSTOMER',
            'amount' => $this->faker->numberBetween(50000, 5000000),
            'description' => null,
            'status' => 'PENDING',
            'source_of_fund' => 'BUSINESS_REVENUE',
            'purpose_code' => 'OTHER',
        ];
    }
}
