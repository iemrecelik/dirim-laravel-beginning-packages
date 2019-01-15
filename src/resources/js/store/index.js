import {state, getters, mutations, actions} from './mainStore';

export default i18n => ({
	state: state(i18n),
	getters: getters(i18n),
	mutations: mutations(i18n),
	actions: actions(i18n),
	modules: {},
	// strict: true,
});