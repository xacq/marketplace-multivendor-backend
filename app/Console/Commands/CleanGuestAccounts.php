<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CleanGuestAccounts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cleanup:guests';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up abandoned phantom guest accounts creating more than 48 hours ago';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $guests = \App\Models\User::where('is_guest', 1)
            ->where('created_at', '<', now()->subHours(48))
            ->get();

        $count = $guests->count();

        foreach($guests as $guest) {
            // Delete associated shopping cart items first manually
            $carts = \App\Models\ShoppingCart::where('user_id', $guest->id)->get();
            foreach($carts as $cart) {
                \App\Models\ShoppingCartVariant::where('shopping_cart_id', $cart->id)->delete();
                $cart->delete();
            }

            // Finally delete the phantom user
            $guest->delete();
        }

        $this->info("Cleaned up {$count} abandoned guest accounts.");

        return Command::SUCCESS;
    }
}
