<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    protected $settings_data = [
        ['name'=>'title',             'value'=>'Gazi Rice Mills'],
        ['name'=>'email',             'value'=>''],
        ['name'=>'contact_no',        'value'=>''],
        ['name'=>'address',           'value'=>''],
        
        ['name'=>'logo',              'value'=>''],
        ['name'=>'favicon',           'value'=>''],

        ['name'=>'currency_code',     'value'=>'BDT'],
        ['name'=>'currency_symbol',   'value'=>'Tk'],
        ['name'=>'currency_position', 'value'=>'1'], //1=Prefix,2=Suffix
        
        ['name'=>'invoice_prefix',    'value'=>'INV-'],
        ['name'=>'invoice_number',    'value'=>'1001'],
        ['name'=>'timezone',          'value'=>'Asia/Dhaka'],
        ['name'=>'date_format',       'value'=>'d-M-Y'],

        ['name'=>'mail_mailer',       'value'=>'smtp'],
        ['name'=>'mail_host',         'value'=>''],
        ['name'=>'mail_port',         'value'=>''],
        ['name'=>'mail_username',     'value'=>''],
        ['name'=>'mail_password',     'value'=>''],
        ['name'=>'mail_encryption',   'value'=>''],
        ['name'=>'mail_from_name',    'value'=>''],
    ];
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Setting::insert($this->settings_data);
    }
}
