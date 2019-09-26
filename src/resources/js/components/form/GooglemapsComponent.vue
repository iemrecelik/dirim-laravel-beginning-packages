<template>
	<div :class="className">
    <label v-if="labelMsgRequired"
      :for="idName"
      v-tooltip="labelInfoTooltip"
    >
      <span v-if="labelRequired" class="text-danger">*</span>
      {{ fieldLabelName }}
      <i v-if="labelInfoTooltip" class="icon ion-md-help-circle"></i>
    </label>
    <error-msg-component v-if="errorMsgRequired"
      :ppsettings="{
        fieldName,
        transFieldName,
        renderType
      }"
    >
    </error-msg-component>
    <div id='map_canvas'></div>
    <input :id="idName+'-lat'" type="hidden" :name="latFieldName"/>
    <input :id="idName+'-lng'" type="hidden" :name="lngFieldName"/>
  </div>
</template>

<script>
export default {
  name: 'GooglemapsComponent',
  data () {
    return {
      fieldName: this.ppfieldname,
      funcs: this.ppfuncs,
      className: this.ppvalue.settings.class || 'form-group',
      translateFieldName: this.ppvalue.settings.transFieldName,
      labelName: this.ppvalue.settings.labelName,
      labelInfo: this.ppvalue.settings.labelInfo,
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
    latDom: function () {
      return document.getElementById(this.idName+'-lat');
    },
    lngDom: function () {
      return document.getElementById(this.idName+'-lng');
    },
    latFieldName: function () {
      return `${this.fieldName}[lat]`;
    },
    lngFieldName: function () {
      return `${this.fieldName}[lng]`;
    },
    labelInfoTooltip: function () {
      return this.labelInfo 
        ? {content: this.labelInfo, placement: 'top-center'}
        : '';
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
    value: function(){
      return this.ppvalue.val;
    },
    renderType: function(){
      return this.ppvalue.settings.renderType || 0;
    },
  },
  created() {
    /* Google Maps start */
    /* const plugin = document.createElement("script");
    plugin.setAttribute(
      "src",
      "//maps.googleapis.com/maps/api/js?key=AIzaSyCErxmyXksvO6kmlK4eFGbxvr4uoc0xklo"
    );
    plugin.async = true;
    plugin.defer = true;
    document.head.appendChild(plugin); */
  },
  mounted() {
    let lat = this.latDom.value = this.value['lat'] || 41.02600929752427;
    let lng = this.lngDom.value = this.value['lng'] || 29.003936660156228;
    
    setTimeout(() => {
      var map = new google.maps.Map(document.getElementById('map_canvas'), {
        zoom: 10,
        center: new google.maps.LatLng(35.137879, -82.836914),
        mapTypeId: google.maps.MapTypeId.ROADMAP
      });

      var myMarker = new google.maps.Marker({
        position: new google.maps.LatLng(lat, lng),
        draggable: true
      });

      google.maps.event.addListener(myMarker, 'dragend', (evt) => {
        this.latDom.value = evt.latLng.lat();
        this.lngDom.value = evt.latLng.lng();
      });

      google.maps.event.addListener(myMarker, 'dragstart', (evt) => {
        this.latDom.value = evt.latLng.lat();
        this.lngDom.value = evt.latLng.lng();
      });

      map.setCenter(myMarker.position);
      myMarker.setMap(map);
    }, 300);
    /* Google Maps end */
  }
}
</script>

<style scoped>
  #map_canvas {
    height: 300px;
    width: 100%;
  }
</style>
