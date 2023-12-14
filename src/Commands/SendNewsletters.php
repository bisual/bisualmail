<?php

namespace bisual\bisualMail\Commands;

use App\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use bisual\bisualMail\Models\Newsletter;
use bisual\bisualMail\Jobs\SendNewsletterMailMail;

class SendNewsletters extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bisualmail:send-newsletter';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envía las newsletter programadas';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $news = Newsletter::whereDate('send_date', '<=', Carbon::now())->get();
        
        foreach ($news as $key => $new) {
            $filters = collect($new->filters);
            if (isset($filters['role_id']) and !isset($filters['country_id'])) {
                $users = User::whereIn('role_id', $filters['role_id'])->get();
            } else if (!isset($filters['role_id']) and isset($filters['country_id'])) {
                $users = User::whereIn('country_id', $filters['country_id'])->get();
            } else if (isset($filters['role_id']) and isset($filters['country_id'])) {
                $users = User::whereIn('role_id', $filters['role_id'])->whereIn('country_id', $filters['country_id'])->get();
            }
            $users->map(function ($user) use ($new) {
                $args = [];
                $args['email'] = $user->email;
                $args['name'] = $user->name;
                $args['title'] = $new->title;
                $args['content'] = $new->content;
                SendNewsletterMailMail::dispatch($args);
            });
            $new->deleted_at = Carbon::now();
            $new->save();
        }
    }
}
