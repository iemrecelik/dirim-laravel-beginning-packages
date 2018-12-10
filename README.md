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

## Gerekli Script Kodları

Aşağıdaki kodu konsolda çalıştırırsanız paket bu başlık altındaki işlemleri sizin için kendi yapar.(Bu işlemi yaparken aynı isimde dosya olmadığına dikkat edin. Çünkü üzerine yazacağı için dosyalarınız silinir.)
```
php artisan vendor:publish --force --tag=scriptSnippets
```

Ama kendiniz yapmak isterseniz aşağıdaki dosyaları düzenleyin. Tercihe göre aşağıdaki main.js ve main-ajax.js dosyalarındaki script kodları ana sayfadaki script dosyanıza ekleyerek de yapabilirsiniz.

**resources/js/jquery/main.js :**
```
// Document ready start
$(function () {
	$('[data-toggle="tooltip"]').tooltip({
		trigger: "hover",
	});
})
// Document ready end
```
Yada
```
php artisan vendor:publish --force --tag=mainjs
```

**resources/js/jquery/main-ajax.js :**
```
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
Yada
```
php artisan vendor:publish --force --tag=mainajaxjs
```

**resource/js/app.js :**
```
require('./bootstrap');

import Vue from 'vue';

/*VueI18n*/
import VueI18n from 'vue-i18n'
Vue.use(VueI18n);

const i18n = new VueI18n({
  locale: document.documentElement.lang, // set locale
  messages: require('../../lang/translate.js'), // set locale messages
});

/*Vuex*/
import Vuex from 'vuex';
Vue.use(Vuex);

import storeObj from './store';
const store = new Vuex.Store(storeObj(i18n));

/*Global Components Start*/
import succeedMsgComponent from './components/SucceedMsgComponent';
import errorMsgListComponent from './components/ErrorMsgListComponent';
import errorMsgComponent from './components/ErrorMsgComponent';
import formComponent from './components/form/FormComponent';

Vue.component('succeed-msg-component', succeedMsgComponent);
Vue.component('error-msg-list-component', errorMsgListComponent);
Vue.component('error-msg-component', errorMsgComponent);
Vue.component('form-form-component', formComponent);
/*Global Components End*/

/*Components*/
import components from './components';

/*Global Mixin*/
import globalMixin from './globalMixin';
Vue.mixin(globalMixin);

/*VTooltip*/
import VTooltip from 'v-tooltip'
Vue.use(VTooltip)

/*SharedState*/
const sharedState = {
	currentLocallang: document.documentElement.lang,
}

export const app = new Vue({
	el: "#app",
	store,
	sharedState,
	components,
	i18n,
})
```
Yada
```
php artisan vendor:publish --force --tag=appjs
```

**resource/js/store/mainStore.js :**
```
export const state = i18n => ({
	routes: {},
	langs: [],
	errors: {},
	succeed: '',
	old: {},
	authUser: {},
	token: document.head.querySelector('meta[name="csrf-token"]').content,
	lang: document.documentElement.lang,
	formModalBody: {},
	imgFilters: {},
});

export const getters = i18n => ({
	filtLangErrorMsg: (state) => {
		let errors = state.errors;
		let matchReg = new RegExp(/langs\.(.+)\.(.+)/, 'g');
    let replaceReg = new RegExp(/(.+)langs\.(.+)\.(\S+)(\s{1}.+)/, 'g');

    for(let errorKey in errors){
      let match = errorKey.match(matchReg);

      if (match) {
        errors[errorKey] = errors[errorKey].map((index, error) => {

          return errors[errorKey][error].replace(
            replaceReg, 
            (match, eq1, eq2, eq3, eq4, offset, string) => {
              return eq1 + '(' + eq2 + ') ' + 
                    i18n.t('messages.'+eq3) + eq4; 
            }
          );// end replace error value
        });// end errors map
      }// end if match
    }// end for in errors

    return errors;
	}
});

export const mutations = i18n => ({
	setRoutes(state, routes){
		state.routes = routes
	},
	setLangs(state, langs){
		state.langs = langs
	},
	setErrors(state, errors){
		state.errors = errors
	},
	setSucceed(state, succeed){
		state.succeed = succeed
	},
	setOld(state, old){
		state.old = old
	},
	setAuthUser(state, authUser){
		state.authUser = authUser
	},
	setFormModalBody(state, formModalBody){
		state.formModalBody = formModalBody;
	},
	setImgFilters(state, imgFilters){
		state.imgFilters = imgFilters;
	},
});

export const actions = i18n => ({
	addDataToAuthUser({commit, state}, addData){

		if (!_.isEmpty(state.authUser)) {

			let authUser = state.authUser;

			authUser[addData.key] = addData.val;
			
			commit('setAuthUser', authUser);
		}
	},
});

export const namespaced =  true;
```
Yada
```
php artisan vendor:publish --force --tag=mainStorejs
```

**resource/js/store/index.js :**
```
import {state, getters, mutations, actions} from './mainStore';

export default i18n => ({
	state: state(i18n),
	getters: getters(i18n),
	mutations: mutations(i18n),
	actions: actions(i18n),
	modules: {},
	// strict: true,
});
```
Yada
```
php artisan vendor:publish --force --tag=mainStoreIndexjs
```

**NOT: Bu işlemleri yapmak istemiyorsanız başlık altında belirtildiği gibi aşağıdaki kodu çalıştırarak otomatik olarak gerçekleşmesini sağlayabilirsiniz. Sadece bazı dosyaların otomatik yapılmasını istiyorsanız. Otomatik yapılmasını istediğiniz dosyanın altındaki kodu consol da çalıştırınız.(Bu işlemi yaparken aynı isimde dosya olmadığına dikkat edin. Çünkü üzerine yazacağı için dosyalarınız silinir.)**
```
php artisan vendor:publish --force --tag=scriptSnippets
```

## Yükleme İşlemi
Şimdi yükleme işlemine geçebiliriz. Aşağıdaki kodu konsolda çalıştırdıktan sonra paketimiz yüklenecek.

```
composer require dirim/laravel-beginning-packages
```

