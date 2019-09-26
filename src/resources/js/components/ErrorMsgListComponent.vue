<template>
	<div class="error-list alert alert-danger" role="alert"
		v-if="isErrors"
	>
		<ol>
			<li v-for="(error, key, index) in errors"
				:key="index"
			>
				{{ translateFieldMsg(error[0], key) }}
			</li>	
		</ol>
	</div>
</template>

<script>
export default {
	name: 'ErrorMsgListComponent',
	data () {
		return {
			handleErr: this.pperrors,
		};
	},
	props: {
		pperrors: {
			type: Object,
			required: false,
			default: function () {
				return {};
			},
		},
	},
  computed: {
  	errors: function(){
			let errors;
			
			if (this.isObjectEmpty(this.handleErr)) {
				errors = this.$store.getters.filtLangErrorMsg;
			} else {
				errors = this.handleErr;
			}

  		return errors;
  	},
		isErrors: function(){
			return !_.isEmpty(this.errors);
		},
	},
}
</script>