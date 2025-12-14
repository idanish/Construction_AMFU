<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\AdminNotification;

class SendLiveTestMail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail:live-test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a live test email to the admin (dev@amfu.net) using AdminNotification mailable';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $adminEmail = env('ADMIN_EMAIL', config('mail.from.address')) ?? env('MAIL_FROM_ADDRESS', 'dev@amfu.net');

        $this->info("Sending test email to: {$adminEmail}");

        try {
            Mail::to($adminEmail)->send(new AdminNotification(
                'LiveTest',
                'TEST-'.time(),
                'Live Test',
                [
                    'note' => 'This is a live test email sent from the automated command',
                    'environment' => config('app.env')
                ],
                null
            ));

            $this->info('Test email queued/sent (check inbox / spam).');
            return 0;
        } catch (\Exception $e) {
            $this->error('Failed to send test email: ' . $e->getMessage());
            return 1;
        }
    }
}
