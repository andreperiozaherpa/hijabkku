<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LandingComplianceTest extends TestCase
{
    /**
     * Test that About Us page returns HTTP 200 and has business info.
     */
    public function test_about_us_page_loads_and_has_compliance_data()
    {
        $response = $this->get(route('about'));

        $response->assertStatus(200);
        $response->assertSee('Tentang Kami');
        $response->assertSee('Panaragan Jaya');
        $response->assertSee('andreperiozaherpa@gmail.com');
    }

    /**
     * Test that Contact Us page returns HTTP 200 and has business info.
     */
    public function test_contact_us_page_loads_and_has_compliance_data()
    {
        $response = $this->get(route('contact'));

        $response->assertStatus(200);
        $response->assertSee('Hubungi Kami');
        $response->assertSee('0822 8078 3843');
        $response->assertSee('andreperiozaherpa@gmail.com');
        $response->assertSee('08.00 – 20.00 WIB');
    }

    /**
     * Test that Privacy Policy page returns HTTP 200 and has business info.
     */
    public function test_privacy_policy_page_loads_and_has_compliance_data()
    {
        $response = $this->get(route('privacy-policy'));

        $response->assertStatus(200);
        $response->assertSee('Kebijakan Privasi');
        $response->assertSee('andreperiozaherpa@gmail.com');
        $response->assertSee('082280783843');
    }

    /**
     * Test that Terms & Conditions page returns HTTP 200 and has business info.
     */
    public function test_terms_page_loads_and_has_compliance_data()
    {
        $response = $this->get(route('terms'));

        $response->assertStatus(200);
        $response->assertSee('Syarat & Ketentuan');
        $response->assertSee('Store Pickup');
        $response->assertSee('payment gateway');
    }

    /**
     * Test that Refund Policy page returns HTTP 200 and has business info.
     */
    public function test_refund_policy_page_loads_and_has_compliance_data()
    {
        $response = $this->get(route('refund-policy'));

        $response->assertStatus(200);
        $response->assertSee('Kebijakan Pengembalian');
        $response->assertSee('video unboxing');
        $response->assertSee('082280783843');
    }

    /**
     * Test that base template shows compliance links in footer.
     */
    public function test_homepage_footer_has_all_compliance_links_and_info()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee(route('privacy-policy'));
        $response->assertSee(route('terms'));
        $response->assertSee(route('refund-policy'));
        $response->assertSee(route('about'));
        $response->assertSee(route('contact'));
        $response->assertSee('andreperiozaherpa@gmail.com');
        $response->assertSee('0822 8078 3843');
        $response->assertSee('08.00 – 20.00 WIB');
    }
}
