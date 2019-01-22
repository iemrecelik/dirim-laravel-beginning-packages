<?php
return function($params){
extract($params);

if ($imgModelPath) {
  $importImg = 'import imagesFormComponent from \'./ImagesFormComponent\';';
  $imgFormComp = '
  <images-form-component
  :ppfiltName="filtName"
  :ppcropsettings="cropSettings"
  >
  </images-form-component>
  ';
  $imgFormComp = trim($imgFormComp);

  $imgProp = '
    ppimgfilters: {
      type: Object,
      required: true,
    },
  ';
  $imgProp = trim($imgProp);

  $setImgFilts = '\'setImgFilters\',';
  $createdSetImgFilt = 'this.setImgFilters(this.ppimgfilters);';
  $imgCompTag = '\'images-form-component\': imagesFormComponent,';
  $imgData = 'filtName: \''.$modelVarName.'ImagesFilt\',';
} else {
  $imgFormComp = '';
  $importImg = '';
  $imgProp = '';
  $setImgFilts = '';
  $createdSetImgFilt = '';
  $imgCompTag = '';
  $imgData = '';
}

return '
<template>
<form 
    method="POST" 
    enctype="multipart/form-data"
    :action="advancedCreateUrl"
>
  
  <error-msg-list-component></error-msg-list-component>
  <succeed-msg-component></succeed-msg-component>
  
  <form-form-component
      :ppsettings="{type: \'hidden\', fieldName: \'_token\', value: token}"
  >
  </form-form-component>

  <create-form-component></create-form-component>
  '.$imgFormComp.'
  <button type="submit" class="btn btn-primary">
      {{ $t(\'messages.save\') }}
  </button>
</form>
</template>

<script>
import createFormComponent from \'./CreateFormComponent\';
'.$importImg.'

import { mapState, mapMutations } from \'vuex\';

export default {
  name: \'CreateAdvancedComponent\',
  data () {
    return {
      '.$imgData.'
      cropSettings: {
        cropFrameClass: \'col\',
        cropRender: true,
        collapseRender: \'multi\',
      },
    };
  },
  props: {
    '.$imgProp.'
    pproutes: {
      type: Object,
      required: true,
    },
    pperrors: {
      type: Object,
      required: true,
    },
    ppsuccess: {
      type: String,
      required: false,
      default: \'\'
    },
    ppoldinput: {
      type: String,
      required: true,
    },
  },
  computed: {
    ...mapState([
        \'routes\',
        \'token\',
      ]),
      advancedCreateUrl: function(){
        return `${this.routes.advancedStore}`; 
      },
  },
  methods: {
    ...mapMutations([
      \'setRoutes\',
      \'setErrors\',
      \'setSucceed\',
      '.$setImgFilts.'
      \'setOld\',
    ]),
  },
  created(){
    this.setRoutes(this.pproutes);
    this.setErrors(this.pperrors);
    this.setSucceed(this.ppsuccess);
    '.$createdSetImgFilt.'
    this.setOld(JSON.parse(this.ppoldinput));
  },
  components: {
    \'create-form-component\': createFormComponent,
    '.$imgCompTag.'
  }
}
</script>
';
};