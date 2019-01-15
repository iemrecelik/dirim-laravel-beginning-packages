require('./bootstrap');

import Vue from 'vue';

/*VueI18n*/
import VueI18n from 'vue-i18n'
Vue.use(VueI18n);

const i18n = new VueI18n({
  locale: document.documentElement.lang, // set locale
  messages: require('../lang/translate.js'), // set locale messages
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

const app = new Vue({
	el: "#app",
	store,
	sharedState,
	components,
	i18n,
})
