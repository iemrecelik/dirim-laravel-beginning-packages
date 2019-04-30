<template>
	<div class="form-group">
    <label :for="idName">
      {{ fieldLabelName }}
    </label>
    <error-msg-component
      :ppsettings="{
        fieldName,
        renderType
      }"
    >
    </error-msg-component>
    <textarea 
      class="form-control" 
      :name="fieldName"
      :placeholder="fieldLabelName"
      :id="idName"
      :aria-describedby="ariaDescribedby"
      v-html="value"
      :rows="rows"
      :cols="cols"
    >
    </textarea>
  </div>
</template>

<script>
export default {
  name: 'TextareaComponent',
  data () {
    return {
      fieldName: this.ppfieldname,
      funcs: this.ppfuncs,
      rows: this.ppvalue.settings.rows || 6,
      cols: this.ppvalue.settings.cols || 50,
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
      return this.funcs.fieldLabelName(this.filtFieldName);
    },
    value: function(){
      return this.ppvalue.val;
    },
    renderType: function(){
      return this.ppvalue.settings.renderType || 0;
    },
  }
}
</script>