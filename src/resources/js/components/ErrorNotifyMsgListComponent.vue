<template>
	<notifications 
		group="error-list-notify"  
		position="top right"
	>
		<template slot="body" slot-scope="props">
			<div class="alert alert-danger" role="alert">
				<h5 class="alert-heading">
					{{ $t('messages.error') }}

					<button type="button" 
						class="close"
						data-dismiss="alert"
						aria-label="Close"
						@click="props.close"
					>
						<span aria-hidden="true">&times;</span>
					</button>
				</h5>
				<ol>
					<li v-for="(error, key, index) in errors"
						:key="index"
					>
						{{ translateFieldMsg(error[0], key) }}
					</li>	
				</ol>
			</div>

		</template>
	</notifications>
</template>

<script>
export default {
	name: 'ErrorNotifyMsgListComponent',
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