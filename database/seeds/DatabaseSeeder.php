<?php

use App\WhatsappClient;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DatabaseSeeder extends Seeder
{
  /**
   * Seed the application's database.
   *
   * @return void
   */
  public function run()
  {
    // $modules = [
    //   'Censo'                  => 'census',
    //   'Cartelera'              => 'posts',
    //   'Censo'                  => 'census',
    //   'Facturación Residentes' => 'residents_billing',
    //   'Manuales & Documentos'  => 'documents',
    //   'Mensajes masivos'       => 'whatsapp',
    //   'Notificaciones'         => 'notifications',
    //   'Novedades'              => 'news',
    //   'Pagos'                  => 'payment_links',
    //   'Solicitudes'            => 'requests',
    //   'Visitas'                => 'visits'
    // ];

    // foreach ($modules as $name => $slug) {
    //   \App\Module::create([
    //     'name' => $name,
    //     'slug' => $slug
    //   ]);
    // }

    // App\Admin::create([
    //   'name'     => 'Admin',
    //   'email'    => 'admin@phenlinea.com',
    //   'password' => bcrypt('123456'),
    //   'address'  => 'Undefined',
    //   'nit'      => '1234567890',
    //   'phone'    => '584147912134'
    // ]);

    // App\User::create([
    //   'name'     => 'Root',
    //   'email'    => 'root@phenlinea.com',
    //   'password' => bcrypt('123456'),
    // ]);

    WhatsappClient::create([
      'name'                 => 'PHenlínea WA',
      'base_url'             => 'http://137.184.93.41/',
      'access_token'         => '',
      'batch_instance_id'    => '',
      'comunity_instance_id' => '',
      'delivery_instance_id' => '',
      'enabled' => 1,
      'user_id' => 1
    ]);

    // DB::unprepared(file_get_contents(storage_path('app/ddbb.sql')));
  }
}
