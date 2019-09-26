<template>
	<div class="form-group">
    <label v-if="labelMsgRequired">
      {{ fieldLabelName }}
    </label>
    <error-msg-component v-if="errorMsgRequired"
      :ppsettings="{
    	 fieldName: filtFieldName,
       transFieldName,
      }"
    >
    </error-msg-component>

    <div class="input-group mb-3">
      
      <div class="custom-file">
        <input type="file" class="custom-file-input" 
          :id="idName"
          :name="imgFieldName" 
        />
        <label class="custom-file-label" 
          :for="idName"
          :aria-describedby="ariaDescribedby"
        >
          {{$t('messages.chooseImage')}}
        </label>
      </div>
      
      <div class="input-group-append tooltip-target">
        <v-popover
          offset="3"
          trigger="hover"
          placement="right"
        >
          <span class="input-group-text">
            <i class="icon ion-md-image"></i>
          </span>

          <template slot="popover">
            <img class="img-thumbnail"
              :src="prwAttr.src" 
              :alt="prwAttr.alt"
            />
          </template>
        </v-popover>
        <button type="button" class="btn btn-danger"
          v-tooltip.top="$t('messages.imageRemove')"
          @click="removeImage"
        >
          <i class="icon ion-md-close-circle-outline"></i>
        </button>
      </div>

      <input type="hidden" 
        v-if="isImgID"
        :name="altFieldName" 
        :value="imgID" 
      />
    </div>

    <samp :id="infoIdName"></samp>
    
    <div v-if="isCropRender">
      <form-crop-component
        :ppsrc="cropSrc"
        :ppfiltName="filtName"
        :ppcropsettings="cropSettings"
        :ppfieldname="cropFieldName"
      >
      </form-crop-component>
    </div>

  </div>
</template>

<script>
import cropComponent from './CropComponent';

export default {
  name: 'UploadImageComponent',
  data () {
    return {
      value: this.ppvalue,
      filtName: this.ppvalue.settings.filtName,
      defaultCropSettings: {
        cropFrameClass: 'col-sm-12',
        cropRender: true,
        collapseRender: 'all',//single, multi, all
      },
      cropSrc: '',
      fieldName: this.ppfieldname,
      altFieldName: 'alt' + _.capitalize(this.ppfieldname),
      prwAttr: {
        src: this.getImageFiltUrl(this.ppvalue.val.img_path, '_1')
      },
      imgID: this.ppvalue.val.img_id,
      funcs: this.ppfuncs,
      translateFieldName: this.ppvalue.settings.transFieldName,
      labelName: this.ppvalue.settings.labelName,
      labelRequired: this.ppvalue.settings.labelRequired || false,
      labelMsgRequired: this.ppvalue.settings.labelMsgRequired !== false,
      errorMsgRequired: this.ppvalue.settings.errorMsgRequired !== false,
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
    cropSettings: function(){
      return this.value.settings.cropSettings 
              || this.defaultCropSettings;
    },
    isCropRender: function(){
      return !_.isEmpty(this.filtName) 
              && !_.isEmpty(this.cropSrc)
              && this.cropSettings.cropRender;
    },
    isImgID: function(){
      return _.isNumber(this.imgID);
    },
    imgFieldName: function(){
      return this.fieldName + '[file]';
    },
    cropFieldName: function(){
      return this.fieldName + '[crops]';
    },
    transFieldName: function(){
      let labelName = this.labelName ? this.$t('messages.'+this.labelName) : null;

      return this.translateFieldName || labelName;
    },
    filtFieldName: function(){
      return this.funcs.filtFieldName(this.fieldName);
    },
    idName: function () {
      return this.funcs.idName(this.filtFieldName);
    },
    infoIdName: function () {
      return this.funcs.idName(this.filtFieldName) + 'Info';
    },
    previewIdName: function () {
      return this.funcs.idName(this.filtFieldName) + 'preview';
    },
    ariaDescribedby: function () {
      return this.funcs.ariaDescribedby(this.filtFieldName);
    },
    fieldLabelName: function(){
      let value;
      
      if (this.transFieldName) {
        value = this.transFieldName
      } else {
        value = this.funcs.fieldLabelName(this.filtFieldName)
      }

      return value;
    },
    /* fieldLabelName: function(){
      return this.funcs.fieldLabelName(this.filtFieldName);
    }, */
    inputFileDOM: function(){
      return document.getElementById(this.idName);
    },
    outputDOM: function(){
      return document.getElementById(this.infoIdName);
    },
    settings: function(){
      return this.value.settigns;
    },
  },
  methods: {

    removeImage: function(){
      this.imgID = null;
      this.prwAttr = {};
      this.cropSrc = '';
      this.inputFileDOM.value="";
      this.outputDOM.innerHTML="";
    },

    infoSelectFiles: function(evt) {

      let files = evt.target.files;

      if (files.length > 0) {

        this.cropSrc = '';

        let output = '<ul class="pl-3">';
        for (let f of files) {

          if (f.size > (1000 * 2500)){
            output = `
              <li class="text-danger">
                ${this.$t('validation.gt.file', {value: 2500})}
              </li>
            `;
            this.inputFileDOM.value="";
            this.prwAttr = {};
            break;
          }//end if f.size 

          output += `
            <li>
              <strong>${escape(f.name)}</strong> 
              (${f.type || 'n/a' }) - ${f.size} bytes
            </li>
          `;

          this.readingSelectFiles(f);
        }//end for
        output += '</ul>';
        this.outputDOM.innerHTML = output;
      }//end if files.length
      
    },

    readingSelectFiles: function(f) {

      if (!f.type.match('image.*')) {
        return false;
      }

      let reader = new FileReader();

      reader.onerror = this.errorHandler;
      reader.onload = (e) => {
        this.prwAttr = {
          src: reader.result,
          alt: escape(f.name),
        };
        this.imgID = null;
        this.cropSrc = reader.result;
      };

      reader.readAsDataURL(f);
    },

    errorHandler: function (evt) {
      switch(evt.target.error.code) {
        case evt.target.error.NOT_FOUND_ERR:
          alert('File Not Found!');
          break;
        case evt.target.error.NOT_READABLE_ERR:
          alert('File is not readable');
          break;
        case evt.target.error.ABORT_ERR:
          alert('File read canceled');
          break; // noop
        default:
          alert('An error occurred reading this file.');
      };
    }

  },
  mounted(){
    this.inputFileDOM.addEventListener('change', this.infoSelectFiles, false);
  },
  components: {
    'form-crop-component': cropComponent,
  }
}
</script>