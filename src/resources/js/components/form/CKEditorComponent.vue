<template>
	<div :class="className">
    <label :for="idName" v-if="labelMsgRequired">
      <span v-if="labelRequired" class="text-danger">*</span>
      {{ fieldLabelName }}
    </label>
    <error-msg-component v-if="errorMsgRequired"
      :ppsettings="{
        fieldName,
        transFieldName,
        renderType
      }"
    >
    </error-msg-component>
    
    <textarea 
      :id="idName"
      :name="fieldName"
      :placeholder="fieldLabelName"
      rows="10"
      cols="80"
    >
    </textarea>
  </div>
</template>

<script>
export default {
  name: 'CKEditorComponent',
  data () {
    return {
      ckeditor: null,
      fieldName: this.ppfieldname,
      funcs: this.ppfuncs,
      className: this.ppvalue.settings.class || 'form-group',
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
    fieldLabelName: function(){
      let value;
      
      if (this.transFieldName) {
        value = this.transFieldName
      } else {
        value = this.funcs.fieldLabelName(this.filtFieldName)
      }

      return value;
    },
    value: function(){
      return this.ppvalue.val;
    },
    renderType: function(){
      return this.ppvalue.settings.renderType || 0;
    },
  },
  mounted() {
    /* ckedtior 4 */
    this.ckeditor = CKEDITOR.replace(this.idName);
    this.ckeditor.setData(this.value);
  }
}
</script>