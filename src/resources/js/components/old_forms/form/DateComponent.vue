<template>
	<div class="form-group">
    <label v-if="isLabel" :for="idName">{{ fieldLabelName }}</label>
    <error-msg-component
      :ppsettings="{
    	 fieldName 
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
      return this.funcs.fieldLabelName(this.filtFieldName);
    },
  },
  mounted(){
  	this.datepicker({ 
      id: '#' + this.idName, 
      value: this.value,
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