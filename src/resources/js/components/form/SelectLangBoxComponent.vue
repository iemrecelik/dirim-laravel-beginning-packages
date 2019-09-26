<template>
	<div class="form-group">
    <label :for="idName" v-if="labelMsgRequired">
      {{ fieldLabelName }}
    </label>
    

    <div class="input-group">
      <select 
        :id="idName"
        class="custom-select"
        v-model="selectedLang"
      >
        
        <option value="" disabled selected>
          <b>{{ placeholderOption }}</b>
        </option>

        <option v-if="isLangs"
          v-for="lang in langs"
          :value="lang.lang_short_name"
        >
          {{ upperFirst(lang.lang_name) }}
        </option>

      </select>

      <div class="input-group-append">
        <button 
          type="button" 
          class="btn btn-primary"
          @click="addSelectedLang"
        >
          Add
        </button>
        <button 
          type="button" 
          class="btn btn-primary"
          @click="removeSelectedLang"
        >
          Remove
        </button>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'SelectLangBoxComponent',
  data () {
    return {
      fieldName: this.ppfieldname,
      selectedLang: '',
      funcs: this.ppfuncs,
      translateFieldName: this.ppvalue.settings.transFieldName,
      labelName: this.ppvalue.settings.labelName,
      labelRequired: this.ppvalue.settings.labelRequired || false,
      labelMsgRequired: this.ppvalue.settings.labelMsgRequired !== false,
      errorMsgRequired: this.ppvalue.settings.errorMsgRequired !== false,
    };
  },
  props: {
    ppfieldname: {
      type: String,
      required: true,
    },
    ppvalue: {
      type: Object,
      required: false,
      default: ''
    },
    ppfuncs: {
      type: Object,
      required: true,
    },
  },
  computed: {
    placeholderOption: function(){
      return this.isLangs
        ?this.$t('messages.add_language')
        :this.$t('messages.languages_loading');
    },
    transFieldName: function(){
      let labelName = this.labelName ? this.$t('messages.'+this.labelName) : null;

      return this.translateFieldName || labelName;
    },
    filtFieldName: function(){
      return this.funcs.filtFieldName(this.fieldName);
    },
    idName: function () {
      return this.funcs.idName(this.filtFieldName);
    },
    ariaDescribedby: function () {
      return this.funcs.ariaDescribedby(this.filtFieldName);
    },
    value: function(){
      return this.ppvalue.val;
    },
    fieldLabelName: function(){
      let value;
      
      if (this.transFieldName) {
        value = this.transFieldName
      } else {
        value = this.funcs.fieldLabelName(this.filtFieldName)
      }

      return value;
    },
    getLangsLink: function(){
      return this.$store.state.routes.index+'/lang/list';
    },
    langs: function(){
      return this.$store.state.langs;
    },
    isLangs: function () {
      return this.langs.length > 0;
    },
  },
  methods: {
    isLang: function (lang) {
      return this.langs.findIndex(
        thislang => thislang.lang_short_name === lang
      );
    },
    addSelectedLang: function () {
      let index = this.isLang(this.selectedLang);
      if (index > -1)
        this.$root.$emit('addSelectedLang', this.langs[index]);
    },
    removeSelectedLang: function () {
      let index = this.isLang(this.selectedLang);
      if (index > -1)
        this.$root.$emit('removeSelectedLang', this.langs[index]);
    },
    upperFirst: function (langName) {
      return _.upperFirst(langName);
    },
  },
  created(){
    this.$store.commit('setLangs', []);
    $.get(this.getLangsLink, (data) => {
      this.$store.commit('setLangs', data);
      this.$root.$emit('setFieldLangs');
    });
  },
}
</script>