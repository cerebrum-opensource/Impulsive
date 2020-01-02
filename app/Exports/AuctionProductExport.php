<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class AuctionProductExport implements FromView
{	
	public $auction;
    public $bidHistory;

	/**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($auction,$bidHistory)
    {
        $this->auction= $auction;
        $this->bidHistory= $bidHistory;
    }

    public function view(): View
    {
        return view('exports.auction_product_detail', [
            'auction' => $this->auction,
            'user_bid_history' => $this->bidHistory
        ]);
    }
}
