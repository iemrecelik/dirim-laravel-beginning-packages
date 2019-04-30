<template>
	<div class="form-group">
    <label :for="idName">
      {{ fieldLabelName }}
    </label>
    
    <div class="input-group">
      <select 
        :id="idName"
        class="custom-select"
        v-model="selectedItem"
        :name="fieldName"
      >     
        <option value="" disabled selected>
          <b>{{ placeholderOption }}</b>
        </option>

        <option v-if="isItems"
          v-for="(item, key, index) in items"
          :key="index"
          :value="item[valFieldName]"
        >
          {{ upperFirst(item[valName]) }}
        </option>

      </select>
    </div>
  </div>
</template>

<script>
export default {
  name: 'SelectBoxAutoComponent',
  data () {
    return {
      fieldName: this.ppfieldname,
      funcs: this.ppfuncs,
      selectedItem: '',
      items: [],
      getDataUrl: this.ppvalue.settings.url,
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
    placeholderOption: function(){
      return this.isItems
        ?this.$t('messages.add_language')
        :this.$t('messages.languages_loading');
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
    value: function(){
      return this.ppvalue.val;
    },
    fieldLabelName: function(){
      return this.funcs.fieldLabelName(this.filtFieldName);
    },
    valFieldName: function () {
      return this.ppvalue.settings.valFieldName;
    },
    valName: function () {
      return this.ppvalue.settings.valName;
    },
    isItems: function () {
      return this.items.length > 0
    },
  },

  methods: {
    upperFirst: function (langName) {
      return _.upperFirst(langName);
    },
  },
  created() {
    $.post(this.getDataUrl, (data, textStatus, jqXHR) => {
      this.items = data;
    })
    .fail(function(error) {
      console.log(error);
    });
  },

  mounted () {
    $("#"+this.idName).chosen({
      disable_search_threshold: 10,
      no_results_text: "Oops, nothing found!",
      width: "100%"
    });
  }
}
</script>