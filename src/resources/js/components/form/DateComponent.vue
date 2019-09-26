<template>
	<div class="form-group">
    <label v-if="isLabel && labelMsgRequired" 
      :for="idName"
    >
      {{ fieldLabelName }}
    </label>
    <error-msg-component v-if="errorMsgRequired"
      :ppsettings="{
        fieldName,
        transFieldName,
      }"
    >
    </error-msg-component>
    <input type="text" readonly="true" :class="inputClass" 
    	:id="idName" 
    	:aria-describedby="ariaDescribedby" 
    	:placeholder="fieldLabelName"
    />
    <input type="hidden" 
    	:id="idNameAlt" 
    	:name="fieldName"
      :value='unixTimeInput'
    />
  </div>
</template>

<script>
export default {
  name: 'DateComponent',
  data () {
    return {
      fieldName: this.ppfieldname,
      funcs: this.ppfuncs,
      unixTimeInput: '',
      label: this.ppvalue.settings.label,
      inputClass: 'form-control ' + this.ppvalue.settings.inputClass,
      phpunix: this.ppvalue.settings.phpunix === false ? false : true,
      dateFormatType: this.ppvalue.settings.dateFormatType || 'short',
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
    isLabel: function(){
      return this.label !== undefined ? this.label : true;
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
    idNameAlt: function () {
      return this.idName + 'Alt';
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
  },
  mounted(){
  	this.datepicker({ 
      id: '#' + this.idName, 
      value: this.value,
      phpunix: this.phpunix,
      dateFormatType: this.dateFormatType,
      settings: {
        onSelect: (dateStr, dateObj) => {
          let date = dateObj.currentYear + '.'
                  + (dateObj.currentMonth + 1) + '.'
                  + dateObj.currentDay;

          this.unixTimeInput = new Date(date).getTime() / 1000;
          let t = new Date(date).getTime() / 1000;
        }
      }
    });
  },
}
</script>