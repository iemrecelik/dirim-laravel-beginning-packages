# Laravel Beginning Packages
Laravel framework' ü için gerekli olan mini paketleri içerir. Crud işlemleri, kullanıcıların girdiği sayfaları dosyaya kaydetme, veri tabanı işlemlerini kaydetme ve language' deki php array dosyalarını vue-i18n için gerekli olan js dosyasına çevirme işlemlerini yapar.

## Yükleme İşlemi
Aşağıdaki kodu konsolda çalıştırdıktan sonra paketimiz yüklenecek.

```
composer require dirim/laravel-beginning-packages
```

## Gerekli Paketler
### Predis
Tag'larla cache'lemek için predis yüklü olması gereklidir.
```
composer require predis/predis
```
### Image Intervention
Resim resize işlemleri için bu paketi yüklemeniz lazım. Aşağıdaki kodu çalıştırabilirsiniz. Yada bu adresinden yükleme işlemi gerçekleştirin.
```
composer require intervention/image

//Laravel 5 için
php artisan vendor:publish --provider="Intervention\Image\ImageServiceProviderLaravel5"
```
Resim dosyaları storage dosyasına kaydedileceği için public klasörü ile storage arasında bağlantı oluşturmalısınız. Yoksa bu dosyaları sistem okuyamaz. Bunun için aşağıdaki kodu çalıştırın.
```
php artisan storage:link
```
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
.env dosyası:
```
MONGO_DB_CONNECTION=mongodb
MONGO_DB_HOST=mongo
MONGO_DB_PORT=27017
MONGO_DB_DATABASE=lvpacktest
MONGO_DB_USERNAME=root
MONGO_DB_PASSWORD=root
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
    "vuex": "^3.0.1",
    "vue-notification": "^1.3.16",
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
npm i --save v-tooltip
npm i --save vue-i18n
npm i --save vuex
npm i --save vue-notification
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
Yukarıdaki kodu çalıştırdığınızda yüklenecek dosyalar : 
- resources/js/jquery/main.js
- resources/js/jquery/main-ajax.js
- resource/js/app.js
- resource/js/store/mainStore.js
- resource/js/store/index.js
- resource/js/globalMixin.js

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
import succeedNotifyMsgComponent from './components/SucceedNotifyMsgComponent';
import errorNotifyMsgListComponent
from './components/ErrorNotifyMsgListComponent';

import succeedMsgComponent from './components/SucceedMsgComponent';
import errorMsgListComponent from './components/ErrorMsgListComponent';
import errorMsgComponent from './components/ErrorMsgComponent';
import formComponent from './components/form/FormComponent';

Vue.component('succeed-notify-msg-component', succeedNotifyMsgComponent);
Vue.component('error-notify-msg-list-component', errorNotifyMsgListComponent);
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

/* Vue Notification */
import Notifications from 'vue-notification'
Vue.use(Notifications)

/*SharedState*/
const sharedState = {
	currentLocallang: document.documentElement.lang,
}

const app = new Vue({
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

**resource/js/globalMixin.js :**
```
export default {
  data () {
    return {
      currentLocallang: document.documentElement.lang,
    };
  },

  methods: {
    getQueryParameters: function(name){
      name = name.replace(/[\[]/, '\\[').replace(/[\]]/, '\\]');
      var regex = new RegExp('[\\?&]' + name + '=([^&#]*)');
      var results = regex.exec(location.search);
      return results === null ? 
      '' : decodeURIComponent(results[1].replace(/\+/g, ' '));
    },

    isActiveTab: function(tabName){
      return tabName === this.activeTab?'active show':'';
    },

    getImageFiltUrl: function(url, filt = 'raw'){
      
      if(url && url.indexOf('img/') < 0) 
        url = `/storage/upload/images/${filt}${url}`;
        
      return url;
    },

    convertTime(loginTime){
      if(!loginTime) return '';

      let nowTime = new Date().getTime();
      let onlineTime = nowTime - loginTime;

      if (onlineTime < 60000) {
        return 'NEW';

      } else if(onlineTime < 3600000 ) {

        return 'before '+Math.floor( (onlineTime/60000) )+' minute';

      }else if(onlineTime < 86400000){

        return 'before '+Math.floor( (onlineTime/3600000) )+' hour';
      }else
        return 'before '+Math.floor( (onlineTime/86400000) )+' day';

    },

    unixTimestamp(unix, format = 'short'){

      let date = new Date(unix*1000);
      let timestamp;

      switch(format){
        case 'time':
          timestamp = this.unixTime(date);
          break;
        case 'short': 
          timestamp = this.unixShortTime(date);
          break;
        case 'long': 
          timestamp = this.unixLongTime(date);
          break;
      }

      return timestamp;
    },

    unixTime(date){
      let hours = date.getHours();
      let minutes = "0" + date.getMinutes();
      let seconds = "0" + date.getSeconds();
      return hours + ':' + minutes.substr(-2) + ':' + seconds.substr(-2);
    },

    unixShortTime(date){
      let day = "0" + date.getDate();
      let month = "0" + (date.getMonth() + 1);
      let year = date.getFullYear();
      return `${day.substr(-2)}/${month.substr(-2)}/${year}`;
    },

    unixLongTime(date){
      let time = this.unixTime(date);
      let shortTime = this.unixShortTime(date);
      return `${shortTime} ${time}`;
    },

    showModalBody(selector){
      let $this = this;
      let store = this.$store;
      $(selector).on('show.bs.modal', function (event){

        let button = $(event.relatedTarget)
          , datas = button.data('datas')
          , component = button.data('component');

        store.commit('setFormModalBody', {datas, component, show: true});
      });

      $(selector).on('hide.bs.modal', function (event){
        store.commit('setErrors', {});
        store.commit('setSucceed', '');
        store.commit('setFormModalBody', {show: false});
      });
    },

    setModalOption(selector, options){
      $(selector).modal(options);
    },

    dataTableRun(config = null){
      return $(config.jQDomName).DataTable({
        responsive: config.responsive || true,
        processing: config.processing || true,
        serverSide: config.serverSide || true,
        ajax: {
          url: config.url || '',
          type: config.method || 'POST',
        },
        columns: config.columns,
        "drawCallback": function( settings ) {
          $('[data-toggle="tooltip"]').tooltip({
            trigger: "hover",
          });
        }
      });
    },

    datepicker(config){
      
      let altField = `${config.id}Alt`;
      let dateFormatArgs = ['option', 'dateFormat', 'dd/mm/yy'];
      dateFormatArgs =  config.dateFormat || dateFormatArgs;

      let settings = {
        showOtherMonths: true,
        selectOtherMonths: false,
        changeMonth: true,
        changeYear: true,
        altField: altField,
        altFormat: "@",
        
      };

      if(config.settings)
        settings = Object.assign(settings, config.settings);
      
      $(config.id).datepicker(settings);
      
      $(config.id).datepicker(
        'option', 
        $.datepicker.regional[this.currentLocallang]
      );

      $(config.id).datepicker(...dateFormatArgs);

      if (config.value) {

        let date = this.unixTimestamp(config.value);

        $(config.id).datepicker( 
          "setDate", date
        );

        $(altField).val(config.value);
      }

    },

    uniqueID(prefix = ''){
      let unixTime = new Date().getTime();
      let uniqueID = unixTime.toString(36).substr(2, 16);
      return prefix + uniqueID;
    },

    uniqueDomID(idName) {
      idName = _.kebabCase(_.toLower(idName));
      return  idName + '-form-' + this.uniqueID();
    },
  },

  computed: {},

  filters: {
    capitalize: function (value) {
      if (!value) return '';

      value = value.toString();
      return value.charAt(0).toUpperCase() + value.slice(1)
    },
    camelCaseToString(str){
      
      str = str.replace(/[a-z][A-Z]/g, function(item){
        return item.slice(0,1) + ' ' + item.slice(1);
      });

      return _.upperFirst(str);
    }
  }
}
```

Yada
```
php artisan vendor:publish --force --tag=globalMixinjs
```

**css ve scss dosyaları :**
```
php artisan vendor:publish --force --tag=beginningPackScss
```
**Genel componentler :**
```
php artisan vendor:publish --force --tag=stablePublicComponents
```

**NOT: Bu işlemleri yapmak istemiyorsanız başlık altında belirtildiği gibi aşağıdaki kodu çalıştırarak otomatik olarak gerçekleşmesini sağlayabilirsiniz. Sadece bazı dosyaların otomatik yapılmasını istiyorsanız. Otomatik yapılmasını istediğiniz dosyanın altındaki kodu consol da çalıştırınız.(Bu işlemi yaparken aynı isimde dosya olmadığına dikkat edin. Çünkü üzerine yazacağı için dosyalarınız silinir.)**
```
php artisan vendor:publish --force --tag=scriptSnippets
```

# Genel Kullanım
Konsolda size ilk başta aşağıdaki soruyu soracak : 
```
Do you want to generate crud processes it manually or from file? Default: [manual]
```
Eğer config dosyasınıza crud dosyası oluşturup aşağıdaki : 
```
'books' => [
'crudType' => 'all',
'modelPath' => 'Admin/Books',
'imgModelPath' => 'Admin/Images',
'imgReqRules' => 'Admin/UpdateImagesPost',
'fieldIDName' => 'bks_id',
'addFields' => [
    [
	'name' => 'bks_edition',
	'type' => 'text',
    ],
    [
	'name' => 'bks_salary',
	'type' => 'text',
    ],
    [
	'name' => 'bks_start_date',
	'type' => 'date',
    ],
],
'reqRules' => 'Admin/BooksRequest',
'advancedReqRules' => 'Admin/BooksAdvancedRequest',
'langInfoTblPath' => 'Languages',
'langModelPath' => 'Admin/BooksLang',
'fieldDependsOnLang' => 'bksl_lang',
'langFieldIDName' => 'bksl_id',
'addLangFields' => [
    [
	'name' => 'bksl_name',
	'type' => 'text',
    ],
    [
	'name' => 'bksl_subject',
	'type' => 'text',
    ],
],
]
```

yukarıdaki bilgileri doldurursanız. Sistem otomatik tüm bilgileri tanıyacaktır. Consoldan kendi elinizle bilgileri doldurmak isterseniz. Manaul seçeneğini seçiniz.

yeni bilgiler
