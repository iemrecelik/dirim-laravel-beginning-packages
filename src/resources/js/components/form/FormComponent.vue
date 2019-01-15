<template>
	<component 
		:is="componentName"
		:ppfieldname="settings.fieldName"
    :ppvalue="value"
    :ppfuncs="funcs"
	>
	</component>
</template>

<script>
import textComponent from './TextComponent';
import dateComponent from './DateComponent';
import uploadImageComponent from './UploadImageComponent';
import hiddenComponent from './HiddenComponent';
import selectLangBoxComponent from './SelectLangBoxComponent';

export default {
  name: 'FormComponent',
  data () {
    return {
      type: {
      	text: 'form-text-component',
        date: 'form-date-component',
        uploadImage: 'form-upload-image-component',
      	hidden: 'form-hidden-component',
        selectLangBox: 'form-select-lang-box-component',
      },
      settings: this.ppsettings,
      funcs: {
        filtFieldName: this.filtFieldName,
        idName: this.idName,
        ariaDescribedby: this.ariaDescribedby,
        fieldLabelName: this.fieldLabelName,
      }
    };
  },
  props: {
    ppsettings: {
      type: Object, 
      required: true,
    },
  },
  computed: {
    componentName: function () {
      return this.type[this.settings.type];
    },
    value: function(){
      return { 
        val: this.settings.value ,
        settings: this.settings,
      };
    },
    transKey: function(){
      let transKey = this.settings.transKey || 'messages';
      transKey += '.';

      return transKey;
    }
  },
  methods: {
    filtFieldName: function(fieldName){
      return fieldName.replace(/\W+/g, '');
    },
    idName: function (filtFieldName) {
      return 'input' + _.upperFirst(_.camelCase(filtFieldName));
    },
    ariaDescribedby: function(filtFieldName){
      return 'desc' + _.upperFirst(_.camelCase(filtFieldName));
    },
    fieldLabelName: function(filtFieldName){
      
      let transKey = this.transKey;

      transKey +=  this.settings.fieldLabelName 
                  || filtFieldName.replace(/\d+/g,'');

      return this.$t(transKey);
    },
  },
  components: {
		'form-text-component': textComponent,
    'form-date-component': dateComponent,
    'form-upload-image-component': uploadImageComponent,
    'form-hidden-component': hiddenComponent,
		'form-select-lang-box-component': selectLangBoxComponent,
	},
}
</script>