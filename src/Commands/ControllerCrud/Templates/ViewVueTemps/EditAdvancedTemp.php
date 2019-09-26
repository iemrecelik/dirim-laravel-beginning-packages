<?php
return function($params){
extract($params);

if ($imgModelPath) {
  $imgFormComp = '
  <images-form-component
    :ppitem="imgs"
    :ppfiltName="filtName"
    :ppcropsettings="cropSettings"
  >
  </images-form-component>
  ';
  $imgFormComp = trim($imgFormComp);
  $importImg = 'import imagesFormComponent from \'./ImagesFormComponent\';';
  
  $imgProp = '  
    ppimgs: {
      type: Array,
      required: true,
    },
    ppimgfilters: {
      type: Object,
      required: true,
    },
  ';
  $imgProp = trim($imgProp);

  $setImgFilts = '\'setImgFilters\',';
  $createdSetImgFilt = 'this.setImgFilters(this.ppimgfilters);';
  $imgCompTag = '\'images-form-component\': imagesFormComponent,';
  $imgData = '
      imgs: this.ppimgs,
      filtName: \''.$modelVarName.'ImagesFilt\',
  ';
  $imgData = trim($imgData);
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
  :action="advancedUpdateUrl"
>
  <error-msg-list-component></error-msg-list-component>
  <succeed-msg-component></succeed-msg-component>
  
  <form-form-component
    :ppsettings="{type: \'hidden\', fieldName: \'_method\', value: \'PUT\'}"
  >
  </form-form-component>

  <form-form-component
    :ppsettings="{type: \'hidden\', fieldName: \'_token\', value: token}"
  >
  </form-form-component>

  <edit-form-component
    :ppitem="item"
  >
  </edit-form-component>
  '.$imgFormComp.'
  <button type="submit" class="btn btn-primary">
    {{ $t(\'messages.update\') }}
  </button>
  
</form>
</template>

<script>
import editFormComponent from \'./EditFormComponent\';
'.$importImg.'

import { mapState, mapMutations } from \'vuex\';

export default {
  name: \'EditAdvancedComponent\',
  data () {
    return {
      item: this.ppitem,
      '.$imgData.'
      cropSettings: {
        cropFrameClass: \'col\',
        cropRender: true,
        collapseRender: \'multi\',
      },
    };
  },
  props: {
    ppitem: {
      type: Object,
      required: true,
    },
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
    advancedUpdateUrl: function(){
      return `${this.routes.index}/${this.item.'.$fieldIDName.'}/advanced-update`; 
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
    \'edit-form-component\': editFormComponent,
    '.$imgCompTag.'
  }
}
</script>
';
};
