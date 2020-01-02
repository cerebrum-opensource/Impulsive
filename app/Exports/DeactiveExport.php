<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class DeactiveExport implements FromView
{	
	public $data;

	/**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data= $data;
    }

    public function view(): View
    {
        return view('exports.deactive', [
            'deactive' => $this->data
        ]);
    }
}
