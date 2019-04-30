<template>
	<input type="hidden" 
    :name="fieldName" 
    :value="value" 
	/>
</template>

<script>
export default {
  name: 'HiddenComponent',
  data () {
    return {
    	fieldName: this.ppfieldname,
    	value: this.ppvalue.val,
    }
  },
  props: {
    ppfieldname: {
      type: String,
      required: true,
    },
    ppvalue:  {
      type: Object,
      required: true,
      default: null
    }
  }
}
</script>