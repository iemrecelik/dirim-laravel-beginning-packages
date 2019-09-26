<template>
	<div class="error-msg" 
    v-if="isFieldError(filtFieldName)" 
    v-html="renderError(filtFieldName)"
  ></div>
</template>

<script>
export default {
  name: 'ErrorMsgComponent',
  data () {
    return {
      fieldName: this.ppsettings.fieldName,
      filtErrorFunc: this.ppsettings.filtErrorFunc || null,
      renderType: 'renderType' + (this.ppsettings.renderType || 0),
      transFieldName: this.ppsettings.transFieldName,
    };
  },
  props: {
    ppsettings: {
      type: Object,
      required: true,
    },
  },
  computed: {
    errors: function () {
      return this.$store.getters.filtLangErrorMsg;
    },
    filtFieldName: function () {
      let name = this.fieldName;
      name = name.replace(/\]/g, '');
      name = name.replace(/\[/g, '.');

      return name;
    },
  },
  methods: {
    errorValue: function(name){
      let value = this.errors[name][0];

      if (_.isFunction(this.filtErrorFunc)) {
        value = this.filtErrorFunc(value);
      }

      if (this.transFieldName) {
        let repFieldName = name.match(/(\w+\.{1}.*)/);
        
        if (!repFieldName) {
          repFieldName = name.replace(/_/g, ' ');
        } else {
          repFieldName = name;
        }

        value = value.replace(repFieldName, this.transFieldName);
      } else {
        value = this.translateFieldMsg(value, name);
      }

      return value;
    },
    isFieldError: function (name) {
      return !_.isEmpty(this.errors[name]);
    },
    renderError: function(name){
      return this[this.renderType](name);
    },
    renderType0: function(name){
      return `
        <small class="form-text text-danger">
          ${this.errorValue(name)}
        </small>
      `;
    }
  },
}
</script>