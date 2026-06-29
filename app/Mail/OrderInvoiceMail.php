<?php

namespace App\Mail;

use App\Models\Pembayaran;
use App\Models\PesananPickup;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderInvoiceMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Pembayaran $pembayaran,
        public $transaksis,
        public $toko,
        public PesananPickup $pesananPickup,
        public string $paymentMethodLabel,
        public int $fee,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Invoice Pesanan Hijabkku - '.$this->pembayaran->kode_invoice,
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.invoice',
            with: [
                'pembayaran' => $this->pembayaran,
                'transaksis' => $this->transaksis,
                'toko' => $this->toko,
                'pesananPickup' => $this->pesananPickup,
                'paymentMethodLabel' => $this->paymentMethodLabel,
                'fee' => $this->fee,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
