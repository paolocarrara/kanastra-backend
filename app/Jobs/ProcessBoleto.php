<?php

namespace App\Jobs;

use App\Models\Boleto;
use Barryvdh\DomPDF\PDF;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessBoleto implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $boleto;

    /**
     * Create a new job instance.
     */
    public function __construct(public Boleto $boleto2)
    {
        $this->boleto = $boleto2;
        $this->onQueue('process_boleto');

    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $pdf = PDF::loadView('boleto', [
            'data' => $this->boleto->toJson()
        ]);

        // TODO Enviar boleto por e-mail
    }
}
