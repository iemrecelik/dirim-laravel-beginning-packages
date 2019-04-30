<template>
	<div class="form-group">
    <label :for="idName">
      {{ fieldLabelName }}
    </label>

    <error-msg-component
      :ppsettings="{
        fieldName,
        renderType
      }"
    >
    </error-msg-component>
    
    <div class="input-group">
      <select 
        :id="idName"
        class="custom-select"
        v-model="selectedItem"
        :name="fieldName"
        :size="selectBoxSize"
        :multiple="multiple"
      >     
        <option v-if="!selectBoxSize" value="" disabled selected>
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
  name: 'SelectBoxComponent',
  data () {
    return {
      funcs: this.ppfuncs,
      selectedItem: [],
      items: this.ppvalue.settings.items || [],
      dataAjaxSettings: this.ppvalue.settings.ajax,
      multiple: this.ppvalue.settings.multiple || false,
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
        ?this.$t('messages.select_item')
        :this.$t('messages.items_loading');
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
      return this.items.length > 0 || false;
    },
    selectBoxSize: function () {
      return this.ppvalue.settings.size ? this.items.length : false;
    },
    fieldName: function() {
      let name;
      
      if (this.ppvalue.settings.multiple === true) {
        name = this.ppfieldname + '[]';
      } else {
        name = this.ppfieldname;
      }

      return name;
    },
    renderType: function(){
      return this.ppvalue.settings.renderType || 0;
    },
  },

  methods: {
    upperFirst: function (langName) {
      return _.upperFirst(langName);
    },
  },
  
  created () {

    if (this.dataAjaxSettings && this.isItems == false) {
      $.ajax({
        type: this.dataAjaxSettings.method,
        url: this.dataAjaxSettings.url,
        data: this.dataAjaxSettings.data,
        success: (datas) => {
          this.items = datas;
        }
      });
    }
  },

  mounted () {
    if (this.value) {
      if (Array.isArray(this.value)) {
        this.value.forEach(item => {
          this.selectedItem.push(item[this.valFieldName]);
        });
      } else {
        this.selectedItem = this.value;
      }
      
    }
  }
}
</script>