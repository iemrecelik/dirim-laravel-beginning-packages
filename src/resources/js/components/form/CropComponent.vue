<template>
	<div class="row">
		
		<div class="col-sm-12">
			<div v-if="isCollapseRender('single')"
      class="d-inline"
      >
        <a v-for="(filt, key, index) in filters"
          :key="index + '-clp-render'"
          class="mr-4" 
          data-toggle="collapse" 
          :href="collapseHref(index)" 
          role="button" 
          aria-expanded="true" 
          :aria-controls="collapseIDName(index)"
        >
          {{ resizeInfo(filt) }}
        </a>
      </div>

	    <div v-if="isCollapseRender('multi')"
      class="d-inline"
      >
        <a data-toggle="collapse" 
          :href="multiCollapseHref" 
          role="button" 
          aria-expanded="true" 
          :aria-controls="allCollapseIDName"
        >
          {{$t('messages.all_collapse')}}
        </a>
      </div>

		</div>

    <div 
    	:class="cropFrameClasses" 
    	v-for="(filt, key, index) in filters"
      :key ="index+'-crp-frm-cls'"
    	:id="collapseIDName(index)"
    >
      <div>
        <img 
        	:data-filt="key"
        	:src="src"
        	:class="imgClassName"
        >
      </div>  
      <div class="sizeInfo text-center"></div>
      <input type="hidden" :name="`${fieldName}[${key}]`"/>
    </div>
  
  </div>
</template>

<script>
import { mapState } from 'vuex';

export default {
  name: 'CropComponent',
  data () {
    return {
    	filtName: this.ppfiltName,
    	imgClassName: this.uniqueDomID('cimg'),
    	fieldName: this.ppfieldname,
    	cropSettings: this.ppcropsettings,
    	collapseRender: this.ppcropsettings.collapseRender,
    }
  },
  props: {
    ppsrc: {
      type: String,
      required: false,
      default: '',
    },
    ppfiltName: {
      type: String,
      required: true,
    },
    ppcropsettings: {
      type: Object,
      required: true,
    },
    ppfieldname: {
      type: String,
      required: true,
    },
  },
  computed: {
  	...mapState([
	    'imgFilters',
	  ]),
    cropFrameClasses: function () {
      return {
      	[this.ppcropsettings.cropFrameClass]: true,
      	[this.multiCollapseClassName]: true,
      	'filt-frame': true,
      	'filt-collapse': true,
      	'show': true,
      };
    },
    filters: function () {
      return this.imgFilters[this.filtName];
    },
    src: function () {
      return this.ppsrc;
    },
    multiCollapseClassName: function () {
      return this.imgClassName + '-multi-collapse';
    },
    multiCollapseHref: function () {
      return '.' + this.multiCollapseClassName;
    },
    
    allCollapseIDName: function () {
    	let idNameArr = [];
    	for (let i = 0; i < _.size(this.filters); i++) {
    		idNameArr.push(this.collapseIDName(i));
    	}
      return idNameArr.join(' ');
    },
  },
  methods: {
  	isCollapseRender: function (type) {
      return _.isEqual(this.collapseRender, 'all') 
      			||_.isEqual(this.collapseRender, type);
    },

    collapseIDName: function (data) {
    	let collapseIDName = this.imgClassName + '-collapse-' + data;
      return collapseIDName;
    },

    collapseHref: function (data) {
      return '#' + this.collapseIDName(data);
    },

    resizeInfo: function (filt) {
      return filt.resize[0] + ' x ' + filt.resize[1];
    },
  },
  mounted(){

  	let selector = 'img.' + this.imgClassName;
    let images = document.querySelectorAll(selector);

    for(let image of images){
    	let sizeInfoDOM = image.closest('div.filt-frame')
    										.querySelector('div.sizeInfo');
			
			let cropInfoDOM = image.closest('div.filt-frame')
    										.querySelector('input[type="hidden"]');

    	let filt = image.getAttribute('data-filt');
    	let width = this.filters[filt].resize[0];
    	let height = this.filters[filt].resize[1];

    	sizeInfoDOM.innerHTML = width + ' x ' + height;

      new Cropper(image, {
        aspectRatio: width / height,
        minCropBoxWidth: width,
        minCropBoxHeight: height,
        zoomable: false,
        crop(event) {
        	let detail = event.detail;
        	let coordinate = [
        		Math.round(detail.width), 
        		Math.round(detail.height), 
        		Math.round(detail.x), 
        		Math.round(detail.y)
        	].join('*?*');

          cropInfoDOM.setAttribute('value', coordinate);
        },
      });
    }

  },
}
</script>

<style lang="css" scoped>
img {
  max-width: 100%;
}
</style>