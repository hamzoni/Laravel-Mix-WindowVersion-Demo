<template>
  <div class="container notification" id="app">
        <form v-on:submit.prevent>
            <div class="column">
                <div class="columns">
                    <div class="column">
                        <div class="select is-fullwidth">
                            <select v-for="file in files">
                                <option>{{ file }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="column">
                        <div class="field-body">
                            <div class="field">
                                <p class="control is-expanded has-icons-left">
                                    <div class="file has-name">
                                        <label class="file-label">
                                            <input class="file-input" type="file" multiple :name="uploadFieldName" :disabled="isSaving" @change="filesChange($event.target.name, $event.target.files); fileCount = $event.target.files.length">
                                            <span class="file-cta">
                                                <span class="file-icon">
                                                    <i class="fa fa-upload"></i>
                                                </span>
                                                <span class="file-label">
                                                    Upload file
                                                </span>
                                            </span>
                                            <span class="file-name">
                                            <p v-if="isSaving">
                                              Uploading {{ fileCount }} files...
                                            </p>
                                            </span>
                                        </label>
                                    </div>
                                </p>
                            </div>
                            <div class="field">
                                <p class="control is-expanded has-icons-left has-icons-right">
                                   <a class="button is-primary is-horizontal" @click="saveXML()">Save file</a>
                                </p>
                            </div>
                            <div class="field">
                                <p class="control is-expanded has-icons-left has-icons-right">
                                   <a class="button is-primary is-horizontal" @click="downloadXML()">Download</a>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="column">

                    </div>
                </div>
            </div>
        </form>
        <form v-on:submit.prevent>
            <div class="wrapper" v-for="(line, index) in linesv" v-if="validLine(line)">
                <div class="column">{{ line }}</div>
                <div class="column">
                      <record :time="showTime(index)" :show="showl[index]" :index="index" @extendLine="extendLine"></record>
                </div>
            </div>
            <!-- end of wrapper -->
        </form>
    </div>
</template>
<script>
  import Line from './components/WholeLine.vue'
  import API from './utility/api'
  import Record from './entities/record'
  import XML from 'xmlbuilder'



  const STATUS_INITIAL = 0, STATUS_SAVING = 1, STATUS_SUCCESS = 2, STATUS_FAILED = 3;
  export default {
    data: function () {
        return {
          files: [],
          lines: [],
          linesv: [],
          ndates: [],

          showl: [],
          datal: [],
          api: new API(),

        uploadError: null,
        currentStatus: null,
        uploadFieldName: 'files',
        }
    },
    components: {
      'record': Line
    },
    mounted: function () {
      this.resetData();
      this.api.listFiles().then(data => {
        this.files = data.data.files;
        if (this.files.length != 0) {
          this.api.listLines(this.files[0]).then(data => {
            this.lines = data.data.lines;
            this.linesv = data.data.linesv;
            this.ndates = data.data.ndates;
            _.forEach(this.lines, () => this.showl.push(false))
          }).catch(error => console.log(error));
        }
      }).catch(error => console.log(error));

    },
    computed: {
      isInitial() {
        return this.currentStatus === STATUS_INITIAL;
      },
      isSaving() {
        return this.currentStatus === STATUS_SAVING;
      },
      isSuccess() {
        return this.currentStatus === STATUS_SUCCESS;
      },
      isFailed() {
        return this.currentStatus === STATUS_FAILED;
      }
    },
    watch: {
    },
    methods: {
      filesChange(fieldName, fileList) {
        const formData = new FormData();
        if (!fileList.length) return;

        // append the files to FormData
        Array
          .from(Array(fileList.length).keys())
          .map(x => {
            formData.append(fieldName, fileList[x], fileList[x].name);
          });
        this.upload(formData);
      },

      upload(formData) {
        this.currentStatus = STATUS_SAVING;

        this.api.uploadFile(formData)
          .then(response => {
            console.log(response.data);
            this.currentStatus = STATUS_SUCCESS;
          })
          .catch(exception => {
            console.log(exception);
            this.currentStatus = STATUS_FAILED;
          });
      },

      resetData() {
        this.currentStatus = STATUS_INITIAL;
        this.uploadError = null;
      },

      downloadXML: function() {

        var xmls = this.generateXMLCollection();
        xmls.forEach(xml => this.downloadFile("file.xml", xml));

      },

      saveXML: function() {

        var xmls = this.generateXMLCollection();
        this.api.saveXml(xmls)
            .then(response => console.log(response))
            .catch(exception => console.log(exception));
      },

      downloadFile: (fn, content) => {
        var element = document.createElement('a');

        element.setAttribute('href', 'data:text/plain;charset=utf-8,' + encodeURIComponent(content));
        element.setAttribute('download', fn);
        element.style.display = 'none';
        document.body.appendChild(element);

        element.click();

        document.body.removeChild(element);
      },

      generateXMLCollection: function() {
        let records = this.$children;
        var xmls = [];
        records.forEach(record => {
          let r = this.generateRecord(record);
          let xml = this.generateXML(r);
          if (xml != null) xmls.push(xml.toString());
        });
        return xmls;
      },

      generateXML: (r) => {
        if (r.start != null) {
          let xml = XML.create('annotation');
          xml.att('time', 1);
          xml.ele('start', r.start + "");
          xml.ele('end', r.end + "");
          let action = xml.ele('action', {'id': 1});
          action.ele('actor', r.author + "");
          action.ele('activity', r.activity + "");
          xml.end({pretty: true});
          return xml;
        }
        return null;
      },

      generateRecord: (record) => {
          var lines = record.$children;
          var r = new Record();
          lines.forEach(line => {
            if (line.ipStart) r.start = line.ipStart;
            if (line.ipEnd) r.end = line.ipEnd;
            if (line.ipActivity) r.author = line.ipActivity;
            if (line.ipAuthor) r.activity = line.ipAuthor;
          });
          return r;
      },
      compressTime: (time) => {
          return time.split("/").join("").split(":").join("").split(" ").join("_");
      },
      showTime: function(index) {
          return this.ndates[index];
      },
      extendLine: function (index) {
          this.showl = this.showl.map((el, i) =>
              i === index ? !el : el
          )
      },
      validLine: (line) => {
          return (line.match(/\w+/i));
      }
    }
}
</script>

<style type="scss">

</style>
