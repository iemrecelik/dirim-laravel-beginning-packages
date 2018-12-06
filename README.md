# Laravel Beginning Packages
Laravel framework' ü için gerekli olan mini paketleri içerir. Crud işlemleri, kullanıcıların girdiği sayfaları dosyaya kaydetme, veri tabanı işlemlerini kaydetme ve language' deki php array dosyalarını vue-i18n için gerekli olan js dosyasına çevirme işlemlerini yapar.

## Gerekli Paketler
### MongoDB
Veritabanı işlermleri mongodb' de kayıt ediliyor. O yüzden aşağıdaki component' i yükleyin. Eğer projenizde mongodb yüklüyse bu kısmı geçin.

```
composer require jenssegers/mongodb
```

Aşağıdaki kodu config/app.php dosyasındaki 'aliases' kısmına ekleyin.
```
'Moloquent' => Jenssegers\Mongodb\Eloquent\Model::class,
```
#### MongoDB Ayarları

config/databse.php dosyasına aşağıdaki driver ayarlarını girin.

```
'mongodb' => [
    'driver'   => 'mongodb',
    'host'     => env('MONGO_DB_HOST', 'mongo'), // Buraya bağlancağınız host' u yazın.
    'port'     => env('MONGO_DB_PORT', 27017), // Hangi portdan bağlanacağınızı girin.
    'database' => env('MONGO_DB_DATABASE', 'lvcomposertest'), // Collection ismini girin.
    /*'username' => env('MonogoDB_USERNAME'), // Varsa kullanıcı ismi
    'password' => env('MonogoDB_PASSWORD'), // Varsa password
    'options'  => [
        'database' => 'admin' // Buraya database'de yapmasına izin vereceğiniz işlemler için yetki grubunu girin.
    ]*/
],
```
## Crud İşlemleri İçin Gerekli NPM Paketleri
Javascript işlemleri için aşağıdaki paketleri yüklemelisiniz.

İsterseniz package.json dosyasındaki dependencies kısmına aşağıdaki listeyi ekleyin.
```
...
"dependencies": {
    ...
    "cropperjs": "^1.4.1",
    "datatables.net-bs4": "^1.10.19",
    "datatables.net-responsive-bs4": "^2.2.3",
    "jquery-ui": "^1.12.1",
    "moment": "^2.22.1",
    "v-tooltip": "^2.0.0-rc.33",
    "vue-i18n": "^8.0.0",
    "vuex": "^3.0.1"
}
...
```
Yada aşağıdaki kodları konsol da çalıştırın.

```
npm i --save cropperjs
npm i --save datatables.net-bs4
npm i --save datatables.net-responsive-bs4
npm i --save jquery-ui
npm i --save moment
npm i --save vue-i18n
npm i --save vuex
```

## Gerekli Script Kodları

Aşağıdaki kodu konsolda çalıştırırsanız paket bu işlemleri sizin için kendi yapar.(Bu işlemi yaparken aynı isimde dosya olmadığına dikkat edin.)
```
php artisan vendor:publish --tag=scriptSnippet
```


resources/js/jquery/main.js ve resources/js/jquery/main-ajax.js adında iki tane dosya düzenleyin. Tercihe göre aşağıdaki script kodları ana sayfaya ekleyerek de yapabilirsiniz.

```
//resources/js/jquery/main.js

// Document ready start
$(function () {
	$('[data-toggle="tooltip"]').tooltip({
		trigger: "hover",
	});
})
// Document ready end
```

```
//resources/js/jquery/main-ajax.js

var ajaxRun = true;

$.ajaxSetup({
  headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
  },
  beforeSend: (xhr, opts) => {

  	if (ajaxRun)
  		ajaxRun = false;
  	else
  		xhr.abort();
  },
  complete: () => {
  	ajaxRun = true;
  }
});
```

Sonrasında resources/js/bootstrap.js dosyasına aşağıdaki satırları ekleyin.
```
...
try {
    ...
    
    require('jquery-ui/ui/widgets/datepicker.js');
    require('jquery-ui/ui/i18n/datepicker-tr.js');

    require('bootstrap');
    
    /*Datatables*/
    require( 'datatables.net-responsive-bs4' )();
} catch (e) {}

/*Moment*/
window.moment = require('moment');

/*CropperJS*/
window.Cropper = require('cropperjs/dist/cropper.js');

/*Main JS*/
require('./jquery/main.js');
require('./jquery/main-ajax.js');
...
```

## Yükleme İşlemi

```
composer require dirim/laravel-beginning-packages
```
