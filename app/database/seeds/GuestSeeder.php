<?php
 
class GuestSeeder extends Seeder {
 
    public function run() {
        
        $user = User::firstOrCreate(['email' => 'guest@configurely.com']);
    
    }
 
}