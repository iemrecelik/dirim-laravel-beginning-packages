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
import numberComponent from './NumberComponent';
import textareaComponent from './TextareaComponent';
import ckeditorComponent from './CKEditorComponent';
import passwordComponent from './PasswordComponent';
import dateComponent from './DateComponent';
import uploadImageComponent from './UploadImageComponent';
import hiddenComponent from './HiddenComponent';
import checkBoxesComponent from './CheckBoxesComponent';
import selectBoxComponent from './SelectBoxComponent';
import selectBoxAutoComponent from './SelectBoxAutoComponent';
import selectLangBoxComponent from './SelectLangBoxComponent';

export default {
  name: 'FormComponent',
  data () {
    return {
      type: {
      	text: 'form-text-component',
      	number: 'form-number-component',
      	textarea: 'form-textarea-component',
      	ckeditor: 'form-ckeditor-component',
      	password: 'form-password-component',
        date: 'form-date-component',
        uploadImage: 'form-upload-image-component',
        hidden: 'form-hidden-component',
        checkBoxes: 'form-check-boxes-component',
      	selectBox: 'form-select-box-component',
      	autoSelectBox: 'form-auto-select-box-component',
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
		'form-number-component': numberComponent,
		'form-textarea-component': textareaComponent,
		'form-ckeditor-component': ckeditorComponent,
		'form-password-component': passwordComponent,
    'form-date-component': dateComponent,
    'form-upload-image-component': uploadImageComponent,
    'form-hidden-component': hiddenComponent,
		'form-check-boxes-component': checkBoxesComponent,
		'form-select-box-component': selectBoxComponent,
		'form-select-box-auto-component': selectBoxAutoComponent,
		'form-select-lang-box-component': selectLangBoxComponent,
	},
}
</script>