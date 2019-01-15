export default {
  data () {
    return {
      currentLocallang: document.documentElement.lang,
    };
  },

  methods: {
    getQueryParameters: function(name){
      name = name.replace(/[\[]/, '\\[').replace(/[\]]/, '\\]');
      var regex = new RegExp('[\\?&]' + name + '=([^&#]*)');
      var results = regex.exec(location.search);
      return results === null ? 
      '' : decodeURIComponent(results[1].replace(/\+/g, ' '));
    },

    isActiveTab: function(tabName){
      return tabName === this.activeTab?'active show':'';
    },

    getImageFiltUrl: function(url, filt = 'raw'){
      
      if(url && url.indexOf('img/') < 0) 
        url = `/storage/upload/images/${filt}${url}`;
        
      return url;
    },

    convertTime(loginTime){
      if(!loginTime) return '';

      let nowTime = new Date().getTime();
      let onlineTime = nowTime - loginTime;

      if (onlineTime < 60000) {
        return 'NEW';

      } else if(onlineTime < 3600000 ) {

        return 'before '+Math.floor( (onlineTime/60000) )+' minute';

      }else if(onlineTime < 86400000){

        return 'before '+Math.floor( (onlineTime/3600000) )+' hour';
      }else
        return 'before '+Math.floor( (onlineTime/86400000) )+' day';

    },

    unixTimestamp(unix, format = 'short'){

      let date = new Date(unix*1000);
      let timestamp;

      switch(format){
        case 'time':
          timestamp = this.unixTime(date);
          break;
        case 'short': 
          timestamp = this.unixShortTime(date);
          break;
        case 'long': 
          timestamp = this.unixLongTime(date);
          break;
      }

      return timestamp;
    },

    unixTime(date){
      let hours = date.getHours();
      let minutes = "0" + date.getMinutes();
      let seconds = "0" + date.getSeconds();
      return hours + ':' + minutes.substr(-2) + ':' + seconds.substr(-2);
    },

    unixShortTime(date){
      let day = "0" + date.getDate();
      let month = "0" + (date.getMonth() + 1);
      let year = date.getFullYear();
      return `${day.substr(-2)}/${month.substr(-2)}/${year}`;
    },

    unixLongTime(date){
      let time = this.unixTime(date);
      let shortTime = this.unixShortTime(date);
      return `${shortTime} ${time}`;
    },

    showModalBody(selector){
      let $this = this;
      let store = this.$store;
      $(selector).on('show.bs.modal', function (event){

        let button = $(event.relatedTarget)
          , datas = button.data('datas')
          , component = button.data('component');

        store.commit('setFormModalBody', {datas, component, show: true});
      });

      $(selector).on('hide.bs.modal', function (event){
        store.commit('setErrors', {});
        store.commit('setSucceed', '');
        store.commit('setFormModalBody', {show: false});
      });
    },

    setModalOption(selector, options){
      $(selector).modal(options);
    },

    dataTableRun(config = null){
      return $(config.jQDomName).DataTable({
        responsive: config.responsive || true,
        processing: config.processing || true,
        serverSide: config.serverSide || true,
        ajax: {
          url: config.url || '',
          type: config.method || 'POST',
        },
        columns: config.columns,
        "drawCallback": function( settings ) {
          $('[data-toggle="tooltip"]').tooltip({
            trigger: "hover",
          });
        }
      });
    },

    datepicker(config){
      
      let altField = `${config.id}Alt`;
      let dateFormatArgs = ['option', 'dateFormat', 'dd/mm/yy'];
      dateFormatArgs =  config.dateFormat || dateFormatArgs;

      let settings = {
        showOtherMonths: true,
        selectOtherMonths: false,
        changeMonth: true,
        changeYear: true,
        altField: altField,
        altFormat: "@",
        
      };

      if(config.settings)
        settings = Object.assign(settings, config.settings);
      
      $(config.id).datepicker(settings);
      
      $(config.id).datepicker(
        'option', 
        $.datepicker.regional[this.currentLocallang]
      );

      $(config.id).datepicker(...dateFormatArgs);

      if (config.value) {

        let date = this.unixTimestamp(config.value);

        $(config.id).datepicker( 
          "setDate", date
        );

        $(altField).val(config.value);
      }

    },

    uniqueID(prefix = ''){
      let unixTime = new Date().getTime();
      let uniqueID = unixTime.toString(36).substr(2, 16);
      return prefix + uniqueID;
    },

    uniqueDomID(idName) {
      idName = _.kebabCase(_.toLower(idName));
      return  idName + '-form-' + this.uniqueID();
    },
  },

  computed: {},

  filters: {
    capitalize: function (value) {
      if (!value) return '';

      value = value.toString();
      return value.charAt(0).toUpperCase() + value.slice(1)
    },
    camelCaseToString(str){
      
      str = str.replace(/[a-z][A-Z]/g, function(item){
        return item.slice(0,1) + ' ' + item.slice(1);
      });

      return _.upperFirst(str);
    }
  }
}