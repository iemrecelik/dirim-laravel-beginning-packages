<template>
	<notifications 
		group="succeed-notify"  
		position="top right"
	>
		<template slot="body" slot-scope="props">
			<div class="sufee-alert alert with-close alert-success alert-dismissible fade show">
				<span class="badge badge-pill badge-success">
					{{ $t('messages.success') }}
				</span>
				{{ succeed }}
				<button type="button" 
					class="close"
					data-dismiss="alert"
					aria-label="Close"
					@click="props.close"
				>
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
		</template>
	</notifications>
</template>

<script>
export default {
  name: 'SucceedNotifyMsgComponent',
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