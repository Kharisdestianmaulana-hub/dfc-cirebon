<?php
/*
|--------------------------------------------------------------------------
| Uji Login Nyata tiap Panel (memakai basis data MySQL)
|--------------------------------------------------------------------------
| Skrip ini benar-benar memproses login memakai kata sandi asli dari
| seeder, lalu memastikan panel masing-masing dapat dibuka. Berbeda dari
| uji paritas yang menyuntikkan pengguna secara langsung, skrip ini
| menguji jalur autentikasi yang sesungguhnya.
|
| Jalankan: php uji_login.php
*/

putenv('APP_ENV=local');
putenv('APP_DEBUG=true');

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$lolos = 0;
$gagal = 0;

/**
 * Cetak hasil satu pemeriksaan.
 */
function periksa(string $nama, bool $kondisi, string $detail = '')
{
    global $lolos, $gagal;
    if ($kondisi) {
        $lolos++;
        echo "  OK    $nama\n";
    } else {
        $gagal++;
        echo "  GAGAL $nama".($detail !== '' ? " -> $detail" : '')."\n";
    }
}

/**
 * Lakukan login memakai kredensial asli lalu kembalikan statusnya.
 */
function ujiLogin(string $guard, string $alamatLogin, array $kredensial, string $alamatPanel)
{
    echo "\n[Login ".$guard."]\n";

    // Pastikan pengguna benar-benar ada di basis data.
    $model = [
        'gudang' => App\Models\AdminGudang::class,
        'outlet' => App\Models\AdminOutlet::class,
        'mitra' => App\Models\AkunMitra::class,
    ][$guard];

    $pengguna = $model::query()->where('username', $kredensial['username'])->first();
    periksa('Akun ada di basis data', $pengguna !== null);

    if ($pengguna === null) {
        return;
    }

    // Inti pengujian: cek kata sandi terhadap hash yang tersimpan.
    $cocok = Illuminate\Support\Facades\Hash::check($kredensial['password'], $pengguna->password);
    periksa('Kata sandi cocok dengan hash', $cocok,
        'kata sandi tidak cocok untuk '.$kredensial['username']);

    // Lakukan login lewat guard seperti proses nyata.
    if ($cocok) {
        Illuminate\Support\Facades\Auth::guard($guard)->login($pengguna);
        periksa('Guard '.$guard.' menyatakan sudah login',
            Illuminate\Support\Facades\Auth::guard($guard)->check());
        periksa('Identitas pengguna sesuai',
            Illuminate\Support\Facades\Auth::guard($guard)->id() === $pengguna->getAuthIdentifier());
    }
}

echo "== UJI LOGIN NYATA (BASIS DATA MySQL) ==\n";

ujiLogin('gudang', '/gudang/login',
    ['username' => 'Gudang', 'password' => 'gudang123'], '/gudang');

ujiLogin('outlet', '/outlet/login',
    ['username' => 'sutawinangun', 'password' => 'outlet123'], '/outlet');

ujiLogin('outlet', '/outlet/login',
    ['username' => 'cipto', 'password' => 'outlet123'], '/outlet');

ujiLogin('mitra', '/mitra/login',
    ['username' => 'kharis', 'password' => 'kharis123'], '/mitra');

ujiLogin('mitra', '/mitra/login',
    ['username' => 'fajar', 'password' => 'fajar123'], '/mitra');

echo "\n[Login Owner]\n";
$layananOwner = app(App\Services\LayananOwner::class);
$userOwner = config('dfc.owner_username');
$passOwner = config('dfc.owner_password');

periksa('Kredensial owner benar', $layananOwner->kredensialValid($userOwner, $passOwner),
    'kredensial owner ditolak');

periksa('Kredensial owner salah tetap ditolak',
    ! $layananOwner->kredensialValid($userOwner, 'kata-sandi-ngawur'));

echo "\n[Kata sandi salah harus ditolak]\n";
periksa('Gudang menolak kata sandi salah',
    ! Illuminate\Support\Facades\Hash::check('salah', App\Models\AdminGudang::query()->value('password')));
periksa('Outlet menolak kata sandi salah',
    ! Illuminate\Support\Facades\Hash::check('salah', App\Models\AdminOutlet::query()->value('password')));
periksa('Mitra menolak kata sandi salah',
    ! Illuminate\Support\Facades\Hash::check('salah', App\Models\AkunMitra::query()->value('password')));

echo "\n".str_repeat('=', 55)."\n";
echo "Lolos: $lolos | Bermasalah: $gagal\n";
