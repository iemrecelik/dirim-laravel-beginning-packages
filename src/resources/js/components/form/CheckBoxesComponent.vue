<template>
  <div class="form-group">
    <div class="col-md-12">
      <div class="row">
        <div class="col-sm">
          <div v-for="(item, key) in items"
            :key="key"
            class="checkbox"
          >
            <label :for="checkboxIdName(key)" 
              class="form-check-label"
            >
              <input
                class="form-check-input"
                type="checkbox"
                :id="checkboxIdName(key)"
                :name="fieldName"
                :value="item[valFieldName]"
                :checked="isChecked(item[valFieldName])"
              >
              {{ upperFirst(item[valName]) }}
            </label>
          </div>
        </div>
        <!-- /.col-sm -->
      </div>
      <!-- /.row -->
    </div>
    <!-- /.col-sm-12 -->
  </div>
  <!-- /.form-group -->
</template>

<script>
import { mapState } from 'vuex';

export default {
  name: "CheckboxesComponent",
  data() {
    return {
      funcs: this.ppfuncs,
      selectedItem: "",
      items: [],
      getDataUrl: this.ppvalue.settings.url,
    };
  },
  props: {
    ppfieldname: {
      type: String,
      required: true
    },
    ppvalue: {
      type: Object,
      required: false,
      default: ""
    },
    ppfuncs: {
      type: Object,
      required: true
    }
  },
  computed: {
    ...mapState([
      'routes',
    ]),
    fieldName: function() {
      let name;
      
      if (this.ppvalue.settings.multiple === true) {
        name = this.ppfieldname + '[]';
      } else {
        name = this.ppfieldname;
      }

      return name;
    },
    value: function() {
      return this.ppvalue.val;
    },
    valFieldName: function() {
      return this.ppvalue.settings.valFieldName;
    },
    valName: function() {
      return this.ppvalue.settings.valName;
    },
    isItems: function() {
      return this.items.length > 0;
    }
  },

  methods: {
    isChecked: function(valFieldValue) {
      let isChecked;
      
      if (this.value) {
        isChecked = this.value.some((val) => {
          return val[this.valFieldName] == valFieldValue;
        });
      } else {
        isChecked = false;
      }

      return isChecked;
    },
    upperFirst: function(langName) {
      return _.upperFirst(langName);
    },
    checkboxIdName: function(index) {
      return 'checkbox'+index;
    }
  },
  created() {
    $.post(this.getDataUrl, (data, textStatus, jqXHR) => {
      this.items = data;
    })
    .fail(function(error) {
      console.log(error);
    });
  }
};
</script>