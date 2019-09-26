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

    unixTimestamp(unix, format = 'short', phpunix = true){
      let date = phpunix ? new Date(unix*1000) : new Date(unix);
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
        language: config.language || this.$t('datatables'),
        responsive: config.responsive || true,
        processing: config.processing || true,
        serverSide: config.serverSide || true,
        ajax: {
          url: config.url || '',
          type: config.method || 'POST',
        },
        columns: config.columns,
        order: config.order || [[0, 'asc']],
        "drawCallback": config.drawCallback || function(settings) {
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
        let date = this.unixTimestamp(
          config.value,
          config.dateFormatType,
          config.phpunix
        );

        $(config.id).datepicker( 
          "setDate", date
        );

        if (!config.phpunix) {
          config.value = config.value / 1000;
        }
        
        $(altField).val(config.value);
      }

    },

    uniqueID(prefix = ''){
      let unixTime = new Date().getTime();
      let uniqueID = unixTime.toString(36).substr(2, 16);
      return prefix + uniqueID;
    },

    uniqueDomID(idName, additional = 'form') {
      idName = _.kebabCase(_.toLower(idName));
      
      if (additional) {
        additional = '-'+additional+'-';
      } else {
        additional = '';
      }
      
      return  idName + additional + this.uniqueID();
    },

    formattedOutput() { 
      return new Intl.NumberFormat('tr-TR', {
        style: 'currency',
        currency: 'TRY',
        minimumFractionDigits: 2,
      })
    },

    priceFormat: function(price) {
      return this.formattedOutput().format(price);
    },

    printPage: function (divId) {
      var content = document.getElementById(divId).innerHTML;
      var mywindow = window.open('', 'Print', 'height=600,width=800');

      // mywindow.document.write('<body><head><title>Print</title>');
      // mywindow.document.write('</head><body >');
      mywindow.document.write(content);
      // mywindow.document.write('</body></body>');

      mywindow.document.close();
      mywindow.focus()
      mywindow.print();
      mywindow.close();
      return true;
    },

    isPermission: function (perm) {
      let user = this.$store.state.authUser;
      
      if (!user.permissions) {
        return false;
      }
        
      let index = user.permissions.indexOf(perm);
      
      return index > -1;
    },

    isObjectEmpty: function (obj) {
      for(var key in obj) {
        if(obj.hasOwnProperty(key))
          return false;
      }
      return true;
    },

    isString: function (value) {
      return typeof value === 'string' || value instanceof String;
    },

    isNumber: function (value) {
      return typeof value === 'number' && isFinite(value);
    },

    isFunction: function(value) {
      return typeof value === 'function';
    },

    translateFieldMsg: function (msg, field) {
      let repFieldName = field.match(/(\w+\.{1}.*)/);
      
      if (!repFieldName) {
        repFieldName = field.replace(/_/g, ' ');
      } else {
        repFieldName = field;
      }
      
			let regex = /(\S*\w+\.\w+)/g;
			let found = msg.match(regex);
			
			if (found) {
				regex = /(\d*)\.*([\w]+)\.*(\d*)$/g;
				found = regex.exec(found[0]);

				let count = found[3] || found[1] || '';

				let res = this.$t('messages.'+found[2]) + count

				msg = msg.replace(repFieldName, res);
			} else {
				msg = msg.replace(repFieldName, this.$t('messages.'+field));
			}

			return msg;
    },
    copyObject: function (obj) {
      let target = {};

      for (let prop in obj) {
        if (obj.hasOwnProperty(prop)) {
          target[prop] = obj[prop];
        }
      }
      return target;
    },
    textTruncate: function(str, length = 20, ending = '...') {
      if (length == null) {
        length = 100;
      }
      if (ending == null) {
        ending = '...';
      }
      if (str.length > length) {
        return str.substring(0, length - ending.length) + ending;
      } else {
        return str;
      }
    },
    htmlEntities: function (str) {
        return String(str).replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;');
    }
  },

  computed: {
    isAdmin: function () {      
      let user = this.$store.state.authUser;
      
      if (user.name === undefined) {
        return false;
      }
        
      let index = user.roles.indexOf('admin');
      
      return index > -1;
    }
  },

  filters: {
    capitalize: function (value) {
      if (typeof value !== 'string') return ''
      return value.charAt(0).toUpperCase() + value.slice(1)

      /* if (!value) return '';
      return _.startCase(_.toLower(value)); */
    },
    camelCaseToString(str){
      
      str = str.replace(/[a-z][A-Z]/g, function(item){
        return item.slice(0,1) + ' ' + item.slice(1);
      });

      return _.upperFirst(str);
    }
  }
}