<template>
	<div class="error-list alert alert-danger" role="alert"
		v-if="isErrors"
	>
		<ol>
			<li v-for="error in errors">{{ error[0] }}</li>	
		</ol>
	</div>
</template>

<script>
export default {
  name: 'ErrorMsgListComponent',
  computed: {
  	errors: function(){
  		return this.$store.getters.filtLangErrorMsg;
  	},
		isErrors: function(){
			return !_.isEmpty(this.errors);
		},
	},
}
</script>