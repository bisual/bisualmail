<?php

namespace bisual\bisualMail\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\User;
use bisual\bisualMail\Notifications\NewsletterMailNotify;

class SendNewsletterMailMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $args;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($args)
    {
        $this->args = $args;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $user = User::where("email", $this->args["email"])->first();
        if ($user) {
            $user->notify(new NewsletterMailNotify($this->args));
        }
    }

    public function tags()
    {
        return [$this->args["title"] . ' ' . $this->args["email"]];
    }}
