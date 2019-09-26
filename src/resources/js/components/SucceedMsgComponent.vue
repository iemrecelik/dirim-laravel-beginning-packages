<template>
	<div class="alert alert-success" role="alert"
		v-if="isSucceed"
	>
	  {{ succeed }}
	</div>
</template>

<script>
export default {
  name: 'SucceedMsgComponent',
  computed: {
  	succeed: function(){
  		return 	this.$store.state.succeed;
  	},
		isSucceed: function(){
			return !_.isEmpty(this.succeed);
		},
	},
}
</script>