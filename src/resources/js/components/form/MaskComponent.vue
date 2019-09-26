<template>
	<div :class="className">
    <label v-if="labelMsgRequired"
      :for="idName"
      v-tooltip="labelInfoTooltip"
    >
      <span v-if="labelRequired" class="text-danger">*</span>
      {{ fieldLabelName }}
      <i v-if="labelInfoTooltip" class="icon ion-md-help-circle"></i>
    </label>
    <error-msg-component v-if="errorMsgRequired"
      :ppsettings="{
        fieldName,
        transFieldName,
        renderType
      }"
    >
    </error-msg-component>
    <masked-input
      :type="inputType"
      :id="idName"
      class="form-control"
      v-model="maskVal"
      :mask="mask"
      :guide="true"
      :placeholder="fieldLabelName"
      placeholderChar="#">
    </masked-input>
    <input type="hidden" :name="fieldName" v-model="rawMaskVal">
  </div>
</template>

<script>
import MaskedInput from 'vue-text-mask'

export default {
  name: 'MaskComponent',
  components: {
    MaskedInput
  },
  data () {
    return {
      fieldName: this.ppfieldname,
      funcs: this.ppfuncs,
      className: this.ppvalue.settings.class || 'form-group',
      translateFieldName: this.ppvalue.settings.transFieldName,
      labelName: this.ppvalue.settings.labelName,
      labelInfo: this.ppvalue.settings.labelInfo,
      labelRequired: this.ppvalue.settings.labelRequired || false,
      labelMsgRequired: this.ppvalue.settings.labelMsgRequired !== false,
      errorMsgRequired: this.ppvalue.settings.errorMsgRequired !== false,
      /* Required datas for mask */
      maskVal: '',
      inputType: this.ppvalue.settings.inputType || 'text',
      rawMask: this.ppvalue.settings.rawMask || /''/g,
      placeholderChar: this.ppvalue.settings.placeholderChar || '#',
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
    rawMaskVal: function () {
      let val = ''

      if (this.isString(this.maskVal)) {
        val = this.maskVal.replace(this.rawMask, '');
      }
      
      return val;
    },
    labelInfoTooltip: function () {
      return this.labelInfo 
        ? {content: this.labelInfo, placement: 'top-center'}
        : '';
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
  methods: {
    mask: function() {
      const mask = this.ppvalue.settings.mask;

      try {
        if (!this.isFunction(mask) && !Array.isArray(mask)) {
          throw "Parameter is not a function or array!";
        } 
      } catch (error) {
				console.log(error);
      }

      return mask;
    },
  },
  mounted() {
    this.maskVal = this.value;	
  }
}
</script>