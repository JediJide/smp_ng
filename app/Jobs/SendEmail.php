<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SendEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $mail_data;

    public int $timeout = 0;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($mail_data)
    {
        $this->mail_data = $mail_data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // $users = User::get ();
        // $users = User::whereIn('id',[139,135])->get();
        $users = User::query()
            ->whereNull('deleted_at')
            ->orWhereNull('password_changed_at')
            ->get();
        $input['subject'] = $this->mail_data['subject'];

        foreach ($users as $key => $value) {
            $input['email'] = $value->email;
            $input['name'] = $value->name;

            $password = Str::random(8);

            $hashed_random_password = Hash::make($password);
            // update user table with newly generated password
            User::where('email', $value->email)
                ->update(
                    ['password' => $hashed_random_password,
                        'updated_at' => Carbon::now(),
                    ],
                );

            \Mail::send('emails.mail', ['password' => $password, 'email' => $value->email], function ($message) use ($input) {
                $message->to($input['email'], $input['name'])
                    ->subject($input['subject']);
            });
        }
    }
}
