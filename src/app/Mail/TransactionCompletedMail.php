<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Purchase;

class TransactionCompletedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $purchase;

    /**
     * Create a new message instance.
     */
    public function __construct(Purchase $purchase)
    {
        $this->purchase = $purchase;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('【取引完了のお知らせ】商品が購入者により完了されました')
            ->view('emails.transaction_completed')
            ->with([
                'purchase' => $this->purchase,
                'buyer' => $this->purchase->user,
                'item' => $this->purchase->item,
            ]);
    }
}
