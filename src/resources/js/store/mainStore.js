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
	formModalLoading: false,
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
	setFormModalLoading(state, formModalLoading){
		state.formModalLoading = formModalLoading;
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