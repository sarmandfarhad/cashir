<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->Illuminate\Contracts\Http\Kernel::handle($app->Illuminate\Http\Request::capture());

$user = App\Models\User::where('email', 'admin@gmail.com')->first();
if($user) {
    $user->password = bcrypt('22244');
    $user->save();
    echo 'Password updated successfully for ' . $user->email . PHP_EOL;
} else {
    echo 'User not found' . PHP_EOL;
}